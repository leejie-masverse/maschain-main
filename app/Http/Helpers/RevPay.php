<?php


namespace App\Http\Helpers;


use Src\People\Facades\TopUpRepository;
use Src\People\TopUpPayment;
use Src\Store\Order\Facades\OrderRepository;
use Src\Store\Order\OrderPayment;

class RevPay
{

    CONST API_URL = "https://staging-gateway.revpay.com.my/payment";
    CONST STAGING_API_URL = "https://staging-gateway.revpay.com.my/payment";

    public static function getCredentials()
    {
        $env = env('REVPAY_ENV');

        if ($env === 'production') {
            $credential['url'] = self::API_URL;
        }
        else {
            $credential['url'] = self::STAGING_API_URL;
        }

        $credential['merchant_id'] = env('REVPAY_MERCHANT_ID');
        $credential['merchant_key'] = env('REVPAY_MERCHANT_KEY');
        $credential['currency'] = env('REVPAY_CURRENCY');

        return $credential;
    }

    public static function isActive()
    {
        if (env('REVPAY_IS_ENABLE') === false || env('REVPAY_IS_ENABLE') === 'false' || env('REVPAY_IS_ENABLE') === null || env('REVPAY_IS_ENABLE') === '') {
            return false;
        }

        return true;
    }

    protected static $paymentMethod = [
        array("payment_id" => 2, "payment_name" => "Visa MasterCard"),  //Visa MasterCard (Credit/Debit Card Payment)
        array("payment_id" => 3, "payment_name" => "FPX (B2C Personal Account)"),  //FPX (Bank Fund Transfer) – B2C Personal Account
        //array("payment_id" => 4, "payment_name" => "AMEX CreditCard"),  //AMEX CreditCard
        //array("payment_id" => 5, "payment_name" => "Alipay Spot QR"),  //Alipay Spot QR
        //array("payment_id" => 6, "payment_name" => "UPOP (Union Pay Online Payment)"),  //UPOP (Union Pay Online Payment)
        //array("payment_id" => 7, "payment_name" => "UPI QR Code"),  //UPI QR Code
        //array("payment_id" => 8, "payment_name" => "Internet Banking"),  //Internet Banking
        array("payment_id" => 9, "payment_name" => "FPX (B2B Corporate Account)"),  //FPX (Bank Fund Transfer) – B2B Corporate Account
        //array("payment_id" => 10, "payment_name" => "MerchanTrade"), //MerchanTrade
        //array("payment_id" => 11, "payment_name" => "Axiata Boost"), //Axiata Boost
        //array("payment_id" => 12, "payment_name" => "Maybank QR"), //Maybank QR
        //array("payment_id" => 13, "payment_name" => "DiGi VCash"), //DiGi VCash
        //array("payment_id" => 14, "payment_name" => "WeChat Pay"), //WeChat Pay
        //array("payment_id" => 15, "payment_name" => "Alipay Online"), //Alipay Online
        //array("payment_id" => 16, "payment_name" => "AirAsia BIG"), //AirAsia BIG
        //array("payment_id" => 17, "payment_name" => "Grab Pay"), //Grab Pay
        //array("payment_id" => 18, "payment_name" => "Internet Banking"), //Internet Banking (MayBank)
        //array("payment_id" => 19, "payment_name" => "Internet Banking"), //Internet Banking (CIMB)
        //array("payment_id" => 20, "payment_name" => "Internet Banking"), //Internet Banking (RHB)
        //array("payment_id" => 21, "payment_name" => "Internet Banking"), //Internet Banking (Public Bank)
        //array("payment_id" => 22, "payment_name" => "Internet Banking"), //Internet Banking (Hong Leong Bank)
        //array("payment_id" => 23, "payment_name" => "Internet Banking"), //Internet Banking (AmBank)
        //array("payment_id" => 24, "payment_name" => "Internet Banking"), //Internet Banking (Bank Rakyat)
        //array("payment_id" => 25, "payment_name" => "Internet Banking"), //Internet Banking (Bank Muamalat)
        //array("payment_id" => 26, "payment_name" => "Internet Banking"), //Internet Banking (BSN)
        //array("payment_id" => 27, "payment_name" => "Hong Leong Bank – Scan and Pay"), //Hong Leong Bank – Scan and Pay
        //array("payment_id" => 28, "payment_name" => "Touch ’n Go"), //Touch ’n Go
        //array("payment_id" => 29, "payment_name" => "Diners Club")  //Diners Club
    ];

    protected static $bankCode = [
        array("bank_code"=>"ABB0233","bank_name" => "Affin Bank Berhad"),
        array("bank_code"=>"ABMB0212","bank_name" => "Alliance Bank Malaysia Berhad"),
        array("bank_code"=>"AMBB0209","bank_name" => "AmBank Malaysia Berhad"),
        array("bank_code"=>"BIMB0340","bank_name" => "Bank Islam Malaysia Berhad"),
        array("bank_code"=>"BKRM0602","bank_name" => "Bank Kerjasama Rakyat Malaysia Berhad"),
        array("bank_code"=>"BMMB0341","bank_name" => "Bank Muamalat Malaysia Berhad"),
        array("bank_code"=>"BSN0601","bank_name" => "Bank Simpanan Nasional"),
        array("bank_code"=>"BCBB0235","bank_name" => "CIMB Bank Berhad"),
        array("bank_code"=>"HLB0224","bank_name" => "Hong Leong Bank Berhad"),
        array("bank_code"=>"HSBC0223","bank_name" => "HSBC Bank Malaysia Berhad"),
        array("bank_code"=>"KFH0346","bank_name" => "Kuwait Finance House (Malaysia) Berhad"),
        array("bank_code"=>"MB2U0227","bank_name" => "Malayan Banking Berhad (M2U)"),
        array("bank_code"=>"MBB0228","bank_name" => "Malayan Banking Berhad (M2E)"),
        array("bank_code"=>"OCBC0229","bank_name" => "OCBC Bank Malaysia Berhad"),
        array("bank_code"=>"PBB0233","bank_name" => "Public Bank Berhad"),
        array("bank_code"=>"RHB0218","bank_name" => "RHB Bank Berhad"),
        array("bank_code"=>"SCB0216","bank_name" => "Standard Chartered Bank"),
        array("bank_code"=>"UOB0226","bank_name" => "United Overseas Bank"),
        ];

    public static function returnPaymentMethod(){
        return self::$paymentMethod;
    }

    public static function returnBankCode(){
        return self::$bankCode;
    }

    public static function createSign($id,$amount){
        $credentials = self::getCredentials();

        $signature = $credentials['merchant_key'] . $credentials['merchant_id'] . $id . $amount . $credentials['currency'];

        $signature = hash('sha512', $signature);
        return strtolower($signature);
    }

    public static function handlePaymentCallback(array $bill)
    {
        $topUpPayment = TopUpPayment::findOrFail( $bill['Reference_Number']);
        if ($bill['Response_Code'] === 00 || $bill['Response_Code'] === '00') {
            $data['top_up_payment']['status'] = TopUpPayment::STATUS_PAID;
            $data['top_up_payment']['paid_amount'] = $bill['Amount'];
            $topUpPayment = TopUpRepository::update($topUpPayment, $data);
            return ['success'=>true];
        }else{
            $data['top_up_payment']['status'] = TopUpPayment::STATUS_FAILED;
            $topUpPayment = TopUpRepository::update($topUpPayment, $data);

            if(isset($bill['Error_Description']) &&$bill['Error_Description'] != null){
                return ['success'=>false,'msg'=>$bill['Error_Description']];
            }else{
                return ['success'=>false,'msg'=>$bill['Transaction_Description']];
            }
        }
    }
}
