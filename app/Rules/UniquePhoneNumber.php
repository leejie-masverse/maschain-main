<?php


namespace App\Rules;


use Illuminate\Contracts\Validation\Rule;
use Src\People\User;

class UniquePhoneNumber implements Rule
{
    public $ignoreUerId = null;
    public $phonePrefix = null;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($ignoreUerId = null,$phonePrefix)
    {
        $this->ignoreUerId = $ignoreUerId;
        $this->phonePrefix = $phonePrefix;
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
        $phone = User::extractPhoneInfo($this->phonePrefix,$value);
        if ($this->ignoreUerId && $this->ignoreUerId !== '') {
            $users = User::where('id', '!=', $this->ignoreUerId);


            if (with(clone $users)->where('phone_number', $phone['phone_number'])->exists()) {
                return false;
            }

            if(with(clone $users)->where('phone_number', (int) $phone['phone_number'])->exists()) {
                return false;
            }
        }
        else {
            if (User::where('phone_number', $phone['phone_number'])->exists()) {
                return false;
            }

            if(User::where('phone_number',$phone['phone_number'])->exists()) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The phone number is already registered with an user.';
    }
}
