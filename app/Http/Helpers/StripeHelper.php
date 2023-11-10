<?php


namespace App\Http\Helpers;


use Carbon\Carbon;
use Diver\Dataset\Bank;
use Src\Store\Booking\Booking;
use Src\Store\Booking\BookingDownpayment;
use Src\Store\Booking\BookingTransaction;
use Src\Store\Booking\Facades\BookingDownpaymentRepository;
use Src\Store\Booking\Facades\BookingRepository;
use Stripe\Stripe;
use Illuminate\Support\Facades\Log;

class StripeHelper
{
    const API_URL = "https://api.stripe.com";
    const STAGING_API_URL = "https://api.stripe.com";

    public static function getCredentials()
    {
        $env = env('STRIPE_ENV');

        if ($env === 'production') {
            $credential['url'] = self::API_URL;
            $credential['secret'] = env('STRIPE_PRODUCTION_SECRET_KEY');
            $credential['pub'] = env('STRIPE_PRODUCTION_PUB_KEY');
        } else {
            $credential['url'] = self::STAGING_API_URL;
            $credential['secret'] = env('STRIPE_STAGING_SECRET_KEY');
            $credential['pub'] = env('STRIPE_STAGING_PUB_KEY');
        }

        return $credential;
    }

    public static function createCheckoutSession(Booking $booking)
    {
        $credentials = self::getCredentials();

        \Stripe\Stripe::setApiKey($credentials['secret']);


        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'myr',
                    'product_data' => [
                        'name' => 'Booking Deposit',
                    ],
                    'unit_amount' => number_format($booking->downpayment_amount * 100,0,'',''),
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' =>  env('APP_URL') . '/booking/success?session_id={CHECKOUT_SESSION_ID}&booking_id='.$booking->id,
            'cancel_url' => env('APP_URL') . '/booking/cancel?session_id={CHECKOUT_SESSION_ID}&booking_id='.$booking->id,
        ]);

        BookingRepository::createDownpayment($booking,$session);

        return $session->id;
    }

    public static function handlePayment(Booking $booking,$session_id)
    {
        $credentials = self::getCredentials();

        \Stripe\Stripe::setApiKey($credentials['secret']);
        $session = \Stripe\Checkout\Session::retrieve($session_id);
        $bookingDownpayment = BookingDownpayment::where('session_id',$session_id)->first();
        if($session->payment_status==='paid'){
            BookingRepository::pay($booking,$session->amount_total);
            BookingRepository::createTransaction($booking,$session,BookingTransaction::TYPE_DOWNPAYMENT);
            if($bookingDownpayment){
                BookingDownpaymentRepository::pay($bookingDownpayment,$session->amount_total);
            }
        }else{
            BookingRepository::pending($booking);
        }

    }
    /**
     * Get payment gateway list
     *
     * @group
     * @param $phoneNumber
     * @param $message
     * @return array
     */
    public static function getPaymentGatewayList()
    {
        $credentials = self::getCredentials();

        $restApi = new RestAPI();
        $url = $credentials['url'] . "v4/payment_gateways";

        $response = $restApi->makeRequest($url, '', 'GET', 'form', $credentials['key']);
        $response = json_decode($response, true);

        if (isset($response['payment_gateways']) && count($response['payment_gateways']) > 0) {
            $paymentGateways = $response['payment_gateways'];

            $bankCodes = self::getAddedBanks();

            $filteredPaymentGateways = [];

            foreach ($paymentGateways as $paymentGateway) {

                // NOTES: Ignore if the bank is not active
                if (!$paymentGateway['active']) {
                    continue;
                }

                // NOTES: Ignore if the bank is not in the database
                if (!in_array($paymentGateway['code'], $bankCodes)) {
                    continue;
                }

                // NOTES: Ignore if the bank is not in the approved payment gateway category
                if (!in_array($paymentGateway['category'], self::$categories)) {
                    continue;
                }

                $bank = Bank::where('billplz_code', $paymentGateway['code'])->firstOrFail();

                $paymentGateway['bank_id'] = $bank->id;
                $paymentGateway['image_base64'] = $bank->image_base64;

                array_push($filteredPaymentGateways, $paymentGateway);
            }

            return $filteredPaymentGateways;
        }
    }

    /**
     * Create a bill under the collection specified in env
     *
     * @group
     * @param User $user
     * @return void|null
     */
    public static function createBill(User $user, $amount)
    {
        $credentials = self::getCredentials();

        if ($user == null) {
            return;
        }

        $restApi = new RestAPI();
        $url = $credentials['url'] . "v3/bills";

        $data = [
            'collection_id' => $credentials['collectionId'],
            'email' => $user->email,
            'mobile' => $user->formatted_phone_number,
            'name' => $user->profile->username,
            'amount' => number_format($amount * 100, 0, '', ''), //NOTES: Need to charge in cents unit
            'redirect_url' => url(route('storefront.store.order.index')),
//            'redirect_url' => url(route('storefront.store.order.summary', ['id' => $order->id])),
            'callback_url' => url(route('payment.billplz.callback')),
            'description' => 'President Club Top Up - RN' . $amount,
        ];
        $data = json_encode($data);

        $response = $restApi->makeRequest($url, $data, 'POST', 'json', $credentials['key']);
        $response = json_decode($response, true);

        if (isset($response['error']) && count($response['error']) > 0) {
            Log::info('Billplz : createbill : error : ' . json_encode($response));
            return null;
        }

        $dueAt = isset($response['due_at']) ? Carbon::createFromFormat('Y-n-j', $response['due_at'])->toDateTimeString() : null;

        $data = [];
        $data['top_up_payment']['user_id'] = $user->id;
        $data['top_up_payment']['payment_service_provider'] = TopUpPayment::SERVICE_PROVIDER_BILLPLZ;
        $data['top_up_payment']['payment_provider_id'] = $response['id'];
        $data['top_up_payment']['collection_id'] = $response['collection_id'];
        $data['top_up_payment']['amount'] = $response['amount'] / 100;
        $data['top_up_payment']['paid_amount'] = $response['paid_amount'] / 100;
        $data['top_up_payment']['payment_url'] = $response['url'];
        $data['top_up_payment']['due_at'] = $dueAt;
        $data['top_up_payment']['status'] = TopUpPayment::STATUS_PENDING;

        $topUpPayment = TopUpRepository::create($data);

        return $topUpPayment;
    }

    /**
     * Handle callback from Billplz, input is a Bill object
     *
     * @group
     * @param array $bill
     * @return mixed
     */
    public static function handlePaymentCallback(array $bill)
    {
        $topUpPayment = TopUpPayment::where('payment_provider_id', $bill['id'])
            ->where('collection_id', $bill['collection_id'])
            ->firstOrFail();

        if ($bill['paid'] === true || $bill['paid'] === 'true') {
            $data['top_up_payment']['status'] = TopUpPayment::STATUS_PAID;
        }
        $data['top_up_payment']['paid_amount'] = $bill['paid_amount'] / 100;

        $topUpPayment = TopUpRepository::update($topUpPayment, $data);

        return $topUpPayment;
    }

    /**
     * Retrieve list of banks added to database.
     *
     * @group
     * @return array
     */
    public static function getAddedBanks()
    {
        return Bank::pluck('billplz_code')->toArray();
    }


}
