<?php

namespace Msingi\Util;

class PasswordGenerator
{
    const POSSIBLE_CHARS = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * Generate password of given length
     *
     * @param int $length
     * @return string
     */
    public static function generate($length = 8)
    {
        // set up a counter
        $i = 0;

        // start with a blank password
        $password = '';

        // add random characters to $password until $length is reached
        while ($i < $length) {
            // pick a random character from the possible ones
            $char = substr(PasswordGenerator::POSSIBLE_CHARS, mt_rand(0, strlen(PasswordGenerator::POSSIBLE_CHARS) - 1), 1);

            // we don't want this character if it's already in the password
            if (!strstr($password, $char)) {
                $password .= $char;
                $i++;
            }
        }

        // done!
        return $password;
    }
}