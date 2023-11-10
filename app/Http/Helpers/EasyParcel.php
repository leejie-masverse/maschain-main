<?php


namespace App\Http\Helpers;


use Diver\Field\Address;
use Illuminate\Support\Facades\Log;
use Src\People\UserAddress;
use Src\Store\DeliveryHub\DeliveryHub;
use Src\Store\Booking\Facades\OrderRepository;
use Src\Store\Booking\Order;
use Src\Store\Booking\OrderDelivery;
use Src\Store\Booking\Facades\OrderDeliveryRepository;
use function count;
use function json_encode;

class EasyParcel
{
    /**
     * Currently using EasyParcel as Delivery gateway
     * https://developers.easyparcel.com/?pg=DocAPI&c=Malaysia&type=Individual&t=70HlDMxF4Sd9%2BIQZjssdQ8w7fDMmi%2Bif1x94JQHQqIw%3D
     */

    CONST API_URL = "http://connect.easyparcel.my/";
    CONST STAGING_API_URL = "http://demo.connect.easyparcel.my/";

    //NOTES: Only select services from this two couriers
    protected static $couriers = [
        "DHL eCommerce", //DHL
//        "POSLAJU NATIONAL COURIER", //Post Laju
    ];

    //NOTES: Act as a fallback if primary couriers not available
    protected static $fallbackCouriers = [
        "DHL eCommerce", //DHL
        "POSLAJU NATIONAL COURIER", //Post Laju
    ];

    public static function getCredentials()
    {
        $env = env('EP_ENV');

        $credential = [
            "url" => self::STAGING_API_URL,
            "key" => env('EP_KEY'),
        ];

        if ($env === 'production') {
            $credential['url'] = self::API_URL;
        }

        return $credential;
    }

    /**
     * Check rate
     *
     * @group
     * @param $phoneNumber
     * @param $message
     * @return array
     */
    public static function checkRate(Address $senderAddress, UserAddress $receiverAddress, $weight = 1, $serviceId = null)
    {
        $credentials = self::getCredentials();

        $restApi = new RestAPI();
        $url = $credentials['url'] . '?ac=EPRateCheckingBulk';

        $data = [
            "api" => $credentials['key'],
            "bulk" => [
                [
                    "pick_code" => $senderAddress->postal_code,
                    "pick_state" => $senderAddress->subdivision->ep_code,
                    "pick_country" => $senderAddress->country->ep_code,
                    "send_code" => $receiverAddress->postal_code,
                    "send_state" => $receiverAddress->subdivision->ep_code,
                    "send_country" => $receiverAddress->country->ep_code,
                    "weight" => $weight //Unit is in KG.
                ]
            ],
            "exclude_fields" => [
                "rates.*.dropoff_point",
                "rates.*.pickup_point"
            ]
        ];

        $data_string = http_build_query($data);

        $response = $restApi->makeRequest($url, $data_string, 'POST', 'form');
        $response = json_decode($response, true);
//        dd($response);

        if ($response['error_code'] !== 0 && $response['error_code'] !== '0') {
            Log::error('Easyparcel - checkrate - response error: ' . json_encode($response));
            return [];
        }

        $rates = isset($response['result']) && isset($response['result'][0]['rates']) && count($response['result'][0]['rates']) > 0 ? $response['result'][0]['rates'] : null;

        if ($rates == null) {
            return [];
        }

        $results = [];
        $primaryCouriers = [];
        $secondaryCouriers = [];

//        dd($rates);
        foreach ($rates as $rate) {
            if ($serviceId !== null) {
                if ($rate['service_id'] === $serviceId) {
                    return $rate;
                }
            }
            else {
                if (in_array($rate['courier_name'], self::$couriers)) {
                    array_push($primaryCouriers, $rate);
                }
                if (in_array($rate['courier_name'], self::$fallbackCouriers)) {
                    array_push($secondaryCouriers, $rate);
                }
            }
        }

        if (count($primaryCouriers) > 0) {
            $results = $primaryCouriers;
        }
        else {
            $results = $secondaryCouriers;
        }

        foreach ($results as $key => $result) {
            $results[$key]['provider_id'] = Order::DELIVERY_PROVIDER_EP;
        }

        return $results;
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

        $restApi = new RestAPI();
        $url = $credentials['url'] . '?ac=EPSubmitOrderBulk';

        $deliveryHub = DeliveryHub::getValidDeliveryHub();

        $unitNo = $deliveryHub->address->unit_no !== null && $deliveryHub->address->unit_no !== '' ?  $deliveryHub->address->unit_no . ', ' : '';
        $buildingName = $deliveryHub->address->building_name !== null && $deliveryHub->address->building_name !== '' ?  $deliveryHub->address->building_name . ', ' : '';

        $orderUnitNo = $order->address->unit_no !== null && $order->address->unit_no !== '' ?  $order->address->unit_no . ', ' : '';
        $orderBuildingName = $order->address->building_name !== null && $order->address->building_name !== '' ?  $order->address->building_name . ', ' : '';

        $data = [
            "api" => $credentials['key'],
            "bulk" => [
                [
                    "weight" => $order->getTotalOrderWeight(),
                    "content" => Order::PARCEL_CONTENT,
                    "value" => $order->amount,
                    "service_id" => $order->delivery_service_id,
                    "pick_name" => $deliveryHub->pic_name,
                    "pick_contact" => $deliveryHub->formatted_phone_number,
                    "pick_point" => '',
                    "pick_addr1" => $unitNo . $buildingName . $deliveryHub->address->street1,
                    "pick_addr2" => $deliveryHub->address->street2,
                    "pick_city" => $deliveryHub->address->city,
                    "pick_state" => $deliveryHub->address->subdivision->name,
                    "pick_code" => $deliveryHub->address->postal_code,
                    "pick_country" => $deliveryHub->address->country->ep_code,
                    "send_name" => $order->address->name,
                    "send_contact" => $order->address->phone_number,
                    "send_addr1" => $orderUnitNo . $orderBuildingName . $order->address->street1 !== '' ? $order->address->street1 : '',
                    "send_addr2" => $order->address->street2,
                    "send_city" => $order->address->city,
                    "send_state" => $order->address->subdivision->name,
                    "send_code" => $order->address->postal_code,
                    "send_country" => $order->address->country->ep_code,
                    "collect_date" => DeliveryHub::getDeliveryCollectionDate(),
                    "sms" => true,
                    "send_email" => $order->user->email,
                    "reference" => $order->booking_number,
                ]
            ],
        ];

        $data_string = http_build_query($data);

        $response = $restApi->makeRequest($url, $data_string, 'POST', 'form');
        Log::info('Easyparcel - createorder - response: ' . json_encode($response));
        $response = json_decode($response, true);

        $response = $response['result'][0];

        if ($response['status'] === 'fail') {
            Log::error('Easyparcel - createorder - response error: ' . json_encode($response));
            return null;
        }

        $orderDelivery = self::createOrderDelivery($order, $response);

        if ($orderDelivery !== null) {
            $orderDelivery = self::makeOrderPayment($orderDelivery);
        }

        return $orderDelivery;
    }


    /**
     * Make order
     *
     * @group
     * @param $phoneNumber
     * @param $message
     * @return array
     */
    public static function makeOrderPayment(OrderDelivery $orderDelivery)
    {
        $credentials = self::getCredentials();

        $restApi = new RestAPI();
        $url = $credentials['url'] . '?ac=EPPayOrderBulk';

        $data = [
            "api" => $credentials['key'],
            "bulk" => [
                [
                    "order_no" => $orderDelivery->booking_number,
                ],
            ],
        ];

        $data_string = http_build_query($data);

        $response = $restApi->makeRequest($url, $data_string, 'POST', 'form');
        Log::info('Easyparcel - createorderpayment - response: ' . json_encode($response));
        $response = json_decode($response, true);

        if (!isset($response['result']) || !isset($response['result'][0]) || !isset($response['result'][0]['parcel']) || !isset($response['result'][0]['parcel'][0]) || count($response['result'][0]['parcel'][0]) == 0) {
            Log::error('Easyparcel - createorderpayment - response error: ' . json_encode($response));
            return null;
        }

        $result = $response['result'][0];
        $parcelDetails = $result['parcel'][0];

        $orderDelivery = self::updateOrderDelivery($orderDelivery, $parcelDetails);

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
        $orderDelivery = $order->orderDelivery;

        if ($orderDelivery == null) {
            return;
        }

        $restApi = new RestAPI();
        $url = $credentials['url'] . '?ac=EPParcelStatusBulk';

        $data = [
            "api" => $credentials['key'],
            "bulk" => [
                [
                    "order_no" => $orderDelivery->booking_number,
                ],
            ],
        ];

        $data_string = http_build_query($data);

        $response = $restApi->makeRequest($url, $data_string, 'POST', 'form');
        Log::error('Easyparcel - checkstatus: ' . json_encode($response));
        $response = json_decode($response, true);

//        dd($response);

        if ($response['error_code'] !== 0 && $response['error_code'] !== '0') {
            Log::error('Easyparcel - checkstatus - response error: ' . json_encode($response));
            return null;
        }

        // TODO: Need to set a different status for those invalid order so that it stop checking everyday
        if (isset($response['result']) && isset($response['result'][0])) {
            $result = $response['result'][0];
        }
        else {
            return null;
        }

        // TODO: Need to set a different status for those invalid order so that it stop checking everyday
        if (isset($result['parcel']) && isset($result['parcel'][0])) {
            $parcelDetails = $result['parcel'][0];
        }
        else {
            return null;
        }

        $status = $parcelDetails['ship_status'];

        $orderDelivery = self::updateOrderDeliveryStatus($orderDelivery, $status);

        if ($status == 'Successfully Delivered') {
            $order = OrderRepository::complete($order);
        }
        else if ($status == 'Collected') {
            $order = OrderRepository::ship($order);
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
        $data['order_delivery']['delivery_service_provider'] = Order::DELIVERY_PROVIDER_EP;
        $data['order_delivery']['reference'] = $result['REQ_ID'];
        $data['order_delivery']['parcel_number'] = $result['parcel_number'];
        $data['order_delivery']['booking_number'] = $result['booking_number'];
        $data['order_delivery']['price'] = $result['price'];
        $data['order_delivery']['remarks'] = $result['remarks'];
        $data['order_delivery']['courier'] = $result['courier'];
        $data['order_delivery']['order_id'] = $order->id;
        $data['order_delivery']['delivery_service_id'] = $order->delivery_service_id;

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
    public static function updateOrderDelivery(OrderDelivery $orderDelivery, array $parcelDetails)
    {
        $data['order_delivery']['status'] = OrderDelivery::STATUS_PAID;
        $data['order_delivery']['awb'] = $parcelDetails['awb'];
        $data['order_delivery']['awb_id_link'] = $parcelDetails['awb_id_link'];
        $data['order_delivery']['tracking_url'] = $parcelDetails['tracking_url'];

        $orderDelivery = OrderDeliveryRepository::update($orderDelivery, $data);

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
}
