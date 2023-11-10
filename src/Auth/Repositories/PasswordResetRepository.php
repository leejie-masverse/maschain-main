<?php


namespace Src\Auth\Repositories;


use Src\Auth\PasswordReset;
use Src\People\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PasswordResetRepository
{
    public function createToken($email, $token)
    {
        $data['email'] = $email;
        $data['token'] = $token;

        return DB::transaction(function () use ($data) {
            $passwordReset = PasswordReset::create($data);
            return $passwordReset;
        });


    }
}
