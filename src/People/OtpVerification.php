<?php

namespace Src\People;

use Diver\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OtpVerification extends Model
{
    /**
     * Message for OTP message
     *
     * @group
     * @param $code
     * @return string
     */
    public static function getOtpMessage($code)
    {
        return "Your verification code for " . env('APP_NAME') . " is $code.";
    }

    /**
     * Relationship
     *
     * @group
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
