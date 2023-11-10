<?php


namespace App\Http\Helpers;


use Src\People\User;
use Src\Referral\Facades\ReferralCommissionTransactionRepository;
use Src\Referral\ReferralCommissionTransaction;
use Src\Store\Booking\Order;
use Src\Store\Booking\OrderProduct;
use Src\Store\Product\Product;

class Utils
{
    // NOTES: for empty value, modify it to be null, only for array, not list of array
    public static function cleanArray(array $inputs)
    {
        foreach ($inputs as $key => $value) {
            if ($value == '') {
                $inputs[$key] = null;
            }
        }

        return $inputs;
    }
}
