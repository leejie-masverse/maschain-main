<?php


namespace App\Http\Helpers\Lalamove;


use App\Notifications\InsufficientCreditNotification;
use Carbon\Carbon;
use Diver\Field\Address;
use Illuminate\Support\Facades\Log;
use Notification;
use Src\Store\DeliveryHub\DeliveryHub;
use Src\Store\Booking\Facades\OrderRepository;
use Src\Store\Booking\Order;
use Src\Store\Booking\OrderDelivery;
use Src\Store\Booking\Facades\OrderDeliveryRepository;
use function array_push;
use function config;
use function env;
use function in_array;

class Lalamove
{
    /**
     * https://developers.lalamove.com/#introduction
     */

    CONST API_URL = "https://rest.lalamove.com/";
    CONST STAGING_API_URL = "https://sandbox-rest.lalamove.com/";

    CONST DELIVERY_SERVICE_ID = "MOTORCYCLE";

    CONST START_TIME = "11"; // NOTE: available start from 11am
    CONST END_TIME = "18"; // NOTE: available till 6pm

    public static function getCredentials()
    {
        $env = env('LM_ENV');

        $credential = [
            "url" => self::STAGING_API_URL,
            "key" => env('LM_KEY'),
        ];

        if ($env === 'production') {
            $credential['url'] = self::API_URL;
        }

        return $credential;
    }

    /**
     * CHeck if lalamove is activated, or active
     *
     * @group
     */
    public static function isActive()
    {
        if (env('LM_IS_ENABLE') == false || env('LM_IS_ENABLE') == 'false' || env('LM_IS_ENABLE') == null) {
            return false;
        }

        // NOTE: Only available on weekday
        if (now(env('USER_TIMEZONE'))->isWeekend()) {
            return false;
        }

        // NOTE: Check between the operating time based on config above
        $startTime = Carbon::createFromTime((int) self::START_TIME);
        $endTime = Carbon::createFromTime((int) self::END_TIME);
        $now = now(env('USER_TIMEZONE'));
        if (!$now->between($startTime, $endTime)) {
            return false;
        }

        return true;
    }

    /**
     * Check rate
     *
     * @group
     * @param $phoneNumber
     * @param $message
     * @return array
     */
    public static function checkRate(Address $senderAddress, Address $receiverAddress, $weight = 1, $serviceId = null)
    {
        $credentials = self::getCredentials();

        $restApi = new LalamoveApi($credentials['url'], $credentials['key'], $credentials['secret']);

        // NOTE: To ensure the phone number is correct, we used the phone number of the user which placed the order to make an order with lalamove
        $phoneNumber = auth()->user() ? auth()->user()->phone_number : '123456789';
        $phoneCode = auth()->user() ? auth()->user()->phone_code : '+60';

        $data = [
            "scheduledAt" => Carbon::now()->toIso8601String(),
            "serviceType" => self::DELIVERY_SERVICE_ID,
            "stops" => [
                [
                    "location" => [
                        "lat" => $senderAddress->latitude,
                        "lng" => $senderAddress->longitude
                    ],
                    "addresses" => [
                        "en_MY" => [
                            "displayString" => $senderAddress->getAddressLineAttribute(),
                            "country" => "MY"
                        ]
                    ]
                ],
                [
                    "location" => [
                        "lat" => $receiverAddress->latitude,
                        "lng" => $receiverAddress->longitude
                    ],
                    "addresses" => [
                        "en_MY" => [
                            "displayString" => $receiverAddress->getAddressLineAttribute(),
                            "country" => "MY"
                        ]
                    ]
                ]
            ],
            "deliveries" => [
                [
                    "toStop" => 1,
                    "toContact" => [
                        "name" => $receiverAddress->name,
                        "phone" => self::formatPhoneNumber($phoneNumber, $phoneCode),
                    ],
                ]
            ],
            "requesterContact" => [
                "name" => $senderAddress->name,
                "phone" => self::formatPhoneNumber($senderAddress->phone_number),
            ],
        ];

        $response = $restApi->quotation($data);

        if (isset($response['message'])) {
            Log::error('Lalamove - checkrate - response error: ' . json_encode($response));

            return null;
        }

        $details = [];
        $detail['provider_id'] = Order::DELIVERY_PROVIDER_LM;
        $detail['service_id'] = self::DELIVERY_SERVICE_ID;
        $detail['courier_name'] = 'Lalamove';
        $detail['price'] = isset($response['price']) ? $response['price'] : '';
        $detail['delivery'] = 'Within an hour';
        array_push($details, $detail);

        return $details;
    }

    /**
     * Make order
     *
     * @group
     * @param $phoneNumber
     * @param $message
     * @return array
     */
    public static function makeOrder(Order $order)
    {
        $credentials = self::getCredentials();

        $restApi = new LalamoveApi($credentials['url'], $credentials['key'], $credentials['secret']);

        // NOTE: To ensure the phone number is correct, we used the phone number of the user which placed the order to make an order with lalamove
        $phoneNumber = $order->user ? $order->user->phone_number : '123456789';
        $phoneCode = $order->user ? $order->user->phone_code : '+60';

        $deliveryHub = DeliveryHub::getValidDeliveryHub();

        if (!$deliveryHub) {
            return null;
        }

        $senderAddress = $deliveryHub->address;
        $receiverAddress = $order->address;

        $price = null; // NOTE: Retrieved from quotation
        $currency = null; // NOTE: Retrieved from quotation

        $data = [
            "quotedTotalFee" => [
                "amount" => $price,
                "currency" => $currency
            ],
            "sms" => true,
            "scheduledAt" => Carbon::now()->addMinutes(5)->toIso8601String(),
            "serviceType" => self::DELIVERY_SERVICE_ID,
            "stops" => [
                [
                    "location" => [
                        "lat" => $senderAddress->latitude,
                        "lng" => $senderAddress->longitude
                    ],
                    "addresses" => [
                        "en_MY" => [
                            "displayString" => $senderAddress->getAddressLineAttribute(),
                            "country" => "MY"
                        ]
                    ]
                ],
                [
                    "location" => [
                        "lat" => $receiverAddress->latitude,
                        "lng" => $receiverAddress->longitude
                    ],
                    "addresses" => [
                        "en_MY" => [
                            "displayString" => $receiverAddress->getAddressLineAttribute(),
                            "country" => "MY"
                        ]
                    ]
                ]
            ],
            "deliveries" => [
                [
                    "toStop" => 1,
                    "toContact" => [
                        "name" => $receiverAddress->name,
                        "phone" => self::formatPhoneNumber($phoneNumber, $phoneCode),
                    ],
                ]
            ],
            "requesterContact" => [
                "name" => $senderAddress->name,
                "phone" => self::formatPhoneNumber($senderAddress->phone_number),
            ],
        ];

        $response = $restApi->quotation($data);

        Log::info('Lalamove - createorder - response: ' . json_encode($response));

        if (!isset($response['orderRef'])) {
            Log::error('Lalamove - createorder - response error: ' . json_encode($response));

            if (isset($response['message'])) {
                if ($response['message'] == 'ERR_INSUFFICIENT_CREDIT') {
                    Notification::route('mail', config('app.admin_email'))->notify(new InsufficientCreditNotification('Lalamove'));
                }
            }

            return null;
        }

        $orderDelivery = null;
        if (isset($response['orderRef'])) {
            $response['price'] = $price;
            $orderDelivery = self::createOrderDelivery($order, $response);
        }

        return $orderDelivery;
    }


    /**
     * Check status
     *
     * @group
     * @param $phoneNumber
     * @param $message
     * @return array
     */
    public static function checkStatus(Order $order)
    {
        $credentials = self::getCredentials();

        $restApi = new LalamoveApi($credentials['url'], $credentials['key'], $credentials['secret']);

        $bookingNumber = $order->orderDelivery ? $order->orderDelivery->booking_number : null;

        if (!$bookingNumber) {
            return null;
        }

        $response = $restApi->getOrderStatus($bookingNumber);

        $status = $response['status'];

        $orderDelivery = $order->orderDelivery;
        if ($status !== $orderDelivery->status) {
            $orderDelivery = self::updateOrderDeliveryStatus($orderDelivery, $status);

            if ($status == 'COMPLETED') {
                $order = OrderRepository::complete($order);
            }
            else if ($status == 'PICKED_UP') {
                $order = OrderRepository::ship($order);
            }
            else if (in_array($status, ['CANCELED', 'EXPIRED'])) {
                $order = OrderRepository::deliveryFail($order);
            }
        }


        return $orderDelivery;
    }

    /**
     * Create a row in database
     *
     * @group
     * @param array $result
     * @return mixed
     */
    public static function createOrderDelivery(Order $order, array $result)
    {
        $data['order_delivery']['delivery_service_provider'] = Order::DELIVERY_PROVIDER_LM;
        $data['order_delivery']['booking_number'] = $result['orderRef'];
        $data['order_delivery']['price'] = $result['price'];
        $data['order_delivery']['order_id'] = $order->id;
        $data['order_delivery']['delivery_service_id'] = self::DELIVERY_SERVICE_ID;
        $data['order_delivery']['courier'] = self::DELIVERY_SERVICE_ID;

        $orderDelivery = OrderDeliveryRepository::create($data);

        return $orderDelivery;
    }

    /**
     * Update order delivery in database
     *
     * @group
     * @param array $result
     * @return mixed
     */
    public static function updateOrderDeliveryStatus(OrderDelivery $orderDelivery, $status)
    {
        $data['order_delivery']['status'] = $status;

        $orderDelivery = OrderDeliveryRepository::update($orderDelivery, $data);

        return $orderDelivery;
    }

    /**
     * Format phone number to suit Lalamove
     *
     * @group
     * @param Address $address
     * @return string|string[]
     */
    public static function formatPhoneNumber($phoneNumber, $phoneCode)
    {
        if ($phoneCode == null) {
            return str_replace('+6', '', (string) $phoneNumber);
        }

        return str_replace('+6', '', (string) $phoneNumber . (string) $phoneCode);
    }
}
