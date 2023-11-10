<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;
use Src\People\UserProfile;
use Src\Store\Booking\VoucherCode;

class ValidVoucherCode implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $value = strtolower($value);
        $now = Carbon::now()->toDateTimeString();

        $isExist = VoucherCode::where('code', $value)
                                ->where('status', VoucherCode::STATUS_ACTIVE)
                                ->where('start_at', '<=', $now)
                                ->where('expired_at', '>=', $now)
                                ->exists();

        if ($isExist) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The voucher code is invalid or had expired.';
    }
}
