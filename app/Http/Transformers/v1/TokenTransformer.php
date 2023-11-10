<?php

namespace App\Http\Transformers\v1;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class TokenTransformer extends Transformer
{
    /**
     * Transform
     *
     * @param $token
     *
     * @return array
     */
    public function transform($token)
    {
        $transformed = [
            'token' => $token,
            'expired_at' => Carbon::now()->addMinutes(config('jwt.ttl')),
            'refresh_expired_at' => Carbon::now()->addMinutes(config('jwt.refresh_ttl')),
            'role' => Auth::user()->role['name'],
        ];

        return $this->touchTransformedData($transformed);
    }
}
