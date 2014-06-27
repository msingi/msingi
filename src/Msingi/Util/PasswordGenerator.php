<?php

namespace Msingi\Util;

/**
 * Class PasswordGenerator
 *
 * @package Msingi\Util
 */
class PasswordGenerator
{
    const POSSIBLE_CHARS = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * Generate password of given length
     *
     * @param int $length password length
     * @param boolean $allow_repeat allow repeat characters in the password
     * @return string
     */
    public static function generate($length = 8, $allow_repeat = false)
    {
        // set up a counter
        $i = 0;

        // avoid infinite loop
        if($length > strlen(self::POSSIBLE_CHARS)) {
            $allow_repeat = true;
        }

        // start with a blank password
        $password = '';

        // add random characters to $password until $length is reached
        while ($i < $length) {
            // pick a random character from the possible ones
            $char = substr(self::POSSIBLE_CHARS, mt_rand(0, strlen(self::POSSIBLE_CHARS) - 1), 1);

            // we don't want this character if it's already in the password
            if ($allow_repeat || !strstr($password, $char)) {
                $password .= $char;
                $i++;
            }
        }

        // done!
        return $password;
    }
}
