<?php

namespace Src\People;

use Diver\Field\Person;

class UserProfile extends Person
{
	/**
	 * Field name
	 *
	 * @var string
	 */
	protected $field = 'profile';

    public static function cleanPhoneNumber($phoneNumber)
    {
        $phoneNumber = (string) $phoneNumber;

        // NOTE: Remove leading zero if any
        $firstCharacter = substr($phoneNumber, 0, 1);
        if ($firstCharacter == '0' || $firstCharacter == 0) {
            $phoneNumber = substr($phoneNumber, 1);
        }

        return $phoneNumber;
    }
}
