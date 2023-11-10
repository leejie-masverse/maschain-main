<?php


namespace App\Http\Helpers;


use App\Notifications\InsufficientCreditNotification;
use Illuminate\Support\Facades\Log;
use Notification;
use Src\Utilities\SmsLog;
use function config;

class SMSGateway
{
    /**
     * Currently using https://www.24x7sms.com/ as SMS gateway
     * https://www.24x7sms.com/downloads/24X7SMS_http_API2.0.pdf
     */

    CONST API_URL = "https://smsapi.24x7sms.com/api_2.0/";

    /**
     * Send SMS
     *
     * @group
     * @param $phoneNumber
     * @param $message
     * @return bool|string
     */
    public static function sendSMS($phoneNumber, $message)
    {
        $restApi = new RestAPI();
        $url = self::API_URL . 'SendSMS.aspx';

        $data = [
            "APIKEY" => env('SMS_KEY'),
            "MobileNo" => $phoneNumber,
            "SenderID" => env('SMS_ID'),
            "Message" => $message, //NOTE: documentation suggested to use rawurlencode()
            "ServiceName" => env('SMS_SERVICE'),
        ];

        $data_string = http_build_query($data);

        $smsLog = new SmsLog();
        $smsLog->phone_number = $phoneNumber;
        $smsLog->message = $message;
        $smsLog->request = $data_string;
        $smsLog->save();

        $response = $restApi->makeRequest($url, $data_string, 'GET', 'form');

        Log::info('sms response: ' . json_encode($response));

        $smsLog->response = $response;
        $smsLog->save();

        if ($response == "INSUFFICIENT_CREDIT") {
            Notification::route('mail', config('app.admin_email'))->notify(new InsufficientCreditNotification('SMS Gateway'));
        }

        return $response;
    }
}
