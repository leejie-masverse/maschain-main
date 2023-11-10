<?php

namespace Src\People\Repositories;

use App\Http\Helpers\SMSGateway;
use Bouncer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Src\Auth\Role;
use Src\People\OtpVerification;
use Src\People\Staff;
use Src\People\SystemUser;
use Src\People\User;
use Src\People\WalletTransaction;

abstract class UserRepository
{
    /**
     * Get user model
     *
     * @return \Src\People\User
     * @throws \ErrorException
     */
    protected static function getUserModel()
    {
        throw new \ErrorException(__METHOD__ . ' is not implemented');
    }

    /**
     * Create user
     *
     * @param array $input
     *
     * @return \Src\People\User
     */
    public function create(array $input)
    {
        $data = data_only($input, [
            'user.email',
            'user.password',
            'user.created_by',
            'user.updated_by',
            'user.referred_by',
            'user.status',
            'user_profile.full_name',
            'user_profile.username',
            'user_profile.date_of_birth',
            'user_portrait.path',
            'user_portrait.file',
            'user.phone_code',
            'user.phone_number',
            'user.formatted_phone_number',
            'user_abilities',
        ]);

        if (isset($data['user']) && isset($data['user']['email']) && $data['user']['email'] !== null) {
            $data['user']['email'] = strtolower($data['user']['email']);
        }

        $data['user']['affiliate_id'] = User::createAffiliateId();

        return DB::transaction(function () use ($data) {

            if (!empty($data['user'])) {
                $user = User::create($data['user']);
            }

            if (!empty($data['user_profile'])) {
                $user->profile()->create($data['user_profile']);
            }

            if (!empty($data['user_portrait'])) {
                $user->portrait()->create($data['user_portrait']);
            }

            if (!empty($data['user_abilities'])) {
                Bouncer::sync($user)->abilities($data['user_abilities']);
            }


            return $user;
        });
    }


    /**
     * Create user with role
     *
     * @param array $input
     * @param string $role
     *
     * @return \Src\People\User
     * @throws \Exception
     * @throws \Throwable
     */
    protected function createWithRole(array $input, string $role)
    {
        return DB::transaction(function () use ($input, $role) {
            $user = $this->create($input);
            $user = $this->assignRole($user, $role);

            return $user;
        });
    }

    /**
     * Update user
     *
     * @param \Src\People\User $user
     * @param array $input
     * @param \Src\Auth\Role $newRole
     *
     * @return \Src\People\User
     * @throws \Exception
     * @throws \Throwable
     */
    public function update(User $user, array $input, $newRole = null)
    {
        $data = data_only($input, [
            'user.email',
            'user.referred_by',
            'user.created_by',
            'user.updated_by',
            'user.wallet_balance',
            'user_profile.full_name',
            'user_profile.username',
            'user_profile.gender',
            'user_profile.date_of_birth',
            'user_portrait.path',
            'user_portrait.file',
            'user.phone_code',
            'user.phone_number',
            'user.formatted_phone_number',
            'user_abilities',
        ]);

        if (is_string($newRole)) {
            $newRole = Role::where('name', $newRole)->first();
        }

        if (isset($data['user']) && isset($data['user']['email']) && $data['user']['email'] !== null) {
            $data['user']['email'] = strtolower($data['user']['email']);
        }

        return DB::transaction(function () use ($user, $data, $newRole) {
            if (!empty($data['user'])) {
                $user->update($data['user']);
            }

            if (!empty($data['user_profile'])) {
                $user->profile->update($data['user_profile']);
            }

            if (!empty($data['user_portrait'])) {
                $user->portrait->update($data['user_portrait']);
            }

            if ($newRole) {
                $user = $this->retractRole($user, $user->role);
                $user = $this->assignRole($user, $newRole);
            }

            if (!empty($data['user_abilities'])) {
                Bouncer::sync($user)->abilities($data['user_abilities']);
            }

            return $user->fresh();
        });
    }


    /**
     * Update user password
     *
     * @param \Src\People\User $user
     * @param $password
     *
     * @return \Src\People\User
     */
    public function updatePassword(User $user, $password)
    {
        return DB::transaction(function () use ($user, $password) {
            $user->password = $password;
            $user->save();

            return $user->refresh();
        });
    }

    /**
     * Set portrait
     *
     * @param array $input
     *
     * @return \Src\People\User
     */
    public function setPortrait(User $user,array $input)
    {
        $data = data_only($input, [
            'user_portrait.path',
            'user_portrait.file',
        ]);

        return DB::transaction(function () use ($data, $user) {
            if ($user->portrait->path === null) {
                $user->portrait()->create($data['user_portrait']);
            }
            else {
                $user->portrait->update($data['user_portrait']);
            }

            return $user->refresh();
        });
    }

    /**
     * Update bank account
     *
     * @param \Src\People\User $user
     * @param                     $input
     * @param \Src\Auth\Role $newRole
     *
     * @return \Src\People\User
     * @throws \Exception
     * @throws \Throwable
     */
    public function updateBankAccount(User $user, $input)
    {
        $data = data_only($input, [
            'bank_account.bank_id',
            'bank_account.account_number',
            'bank_account.account_holder_name',
            'bank_account.identification_no',
        ]);

        return DB::transaction(function () use ($user, $data) {
            if ( empty($data['bank_account'])) {
                return $user;
            }

            if ($user->bankAccount == null) {
                $user->bankAccount()->create($data['bank_account']);
            }
            else {
                $user->bankAccount->update($data['bank_account']);
            }

            return $user->fresh();
        });
    }

    /**
     * Delete system user
     *
     * @param \Src\People\User $user
     * @param bool $forceDelete
     *
     * @return bool
     */
    public function delete(User $user, $forceDelete = false)
    {
        return DB::transaction(function () use ($user, $forceDelete) {
            return $forceDelete ? $user->forceDelete() : $user->delete();
        });
    }

    /**
     * Assign role to user
     *
     * @param \Src\People\User $user
     * @param \Src\Auth\Role|string $role
     * @param array $input
     * @param bool $sync
     *
     * @return \Src\People\User
     * @throws \Exception
     * @throws \Throwable
     */
    protected function assignRole(User $user, $role, bool $sync = true)
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->first();
        }

        if ($user->isA($role->name)) {
            return $user;
        }

        return DB::transaction(function () use ($user, $role, $sync) {
            if ($sync) {
                $user->roles()->sync([$role->id]);
            }
            else {
                $user->roles()->attach($role->id);
            }

            return $user->refresh();
        });
    }

    /**
     * Retract role from user
     *
     * @param \Src\People\User $user
     * @param $role
     *
     * @return \Src\People\User
     * @throws \Exception
     * @throws \Throwable
     */
    protected function retractRole(User $user, $role)
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->first();
        }

        return DB::transaction(function () use ($user, $role) {
            $user->roles()->detach($role->id);

            return $user->refresh();
        });
    }

    /**
     * Ban user
     *
     * @param \Src\People\User $user
     *
     * @return \Src\People\User
     * @throws \Exception
     * @throws \Throwable
     */
    public function ban(User $user)
    {
        return DB::transaction(function () use ($user) {
            $user->status = User::STATUS_BANNED;
            $user->save();

            return $user->refresh();
        });
    }

    /**
     * Unban user
     *
     * @param \Src\People\User $user
     *
     * @return \Src\People\User
     * @throws \Exception
     * @throws \Throwable
     */
    public function unban(User $user)
    {
        return DB::transaction(function () use ($user) {
            $user->status = User::STATUS_ACTIVE;
            $user->save();

            return $user->refresh();
        });
    }

    /**
     * Verify OTP verification
     *
     * @group
     * @param User $user
     * @return mixed
     * @throws \Throwable
     */
    public function verify(User $user)
    {
        return DB::transaction(function () use ($user) {
            $user->status = User::STATUS_ACTIVE;
            OtpVerification::where('user_id',$user->id)->delete();
            $user->save();

            return $user->fresh();
        });
    }

    /**
     * Create OTP
     *
     * @param array $input
     *
     * @return mixed
     * @throws \Exception
     * @throws \Throwable
     */
    public function createOtp(array $input)
    {
        $data = data_only($input, [
            'user.phone_code',
            'user.phone_number',
            'user.formatted_phone_number',

            'otp.phone_number',
            'otp.user_id',
        ]);
        $data['otp']['code'] = rand(100000, 999999);

        $user = User::findOrFail($data['otp']['user_id']);

        return DB::transaction(function () use ($data, $user) {
            $otpVerification = OtpVerification::create($data['otp']);

            if ( ! empty($data['user'])) {
                $user->update($data['user']);
            }

            if (env('APP_ENV') !== 'local') {
                $otpMessage = OtpVerification::getOtpMessage($otpVerification->code);
                $isSuccess = SMSGateway::sendSMS($otpVerification->phone_number, $otpMessage);
            }

            return $otpVerification;
        });
    }

    /**
     * Update phone number and user status to active
     *
     * Valid for register account OTP verification and update phone number verification
     *
     * @group
     * @param OtpVerification $otpVerfication
     * @return mixed
     * @throws \Throwable
     */
    public function verifyOtp(OtpVerification $otpVerfication)
    {
        return DB::transaction(function () use ($otpVerfication) {

            $user = $otpVerfication->user;
            $phoneNumber = substr($otpVerfication->phone_number,strlen( $user->phone_code)-1);
            $user->phone_number = $phoneNumber;
            $user->formatted_phone_number = $otpVerfication->phone_number;
            $user->save();

            if ($user->status === User::STATUS_VERIFYING) {
                $user->status = User::STATUS_ACTIVE;
                $user->save();
            }

            $otpVerfication->delete();

            return $user->refresh();
        });
    }
}
