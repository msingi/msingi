<?php

namespace Msingi\Cms\Service;

use Zend\Authentication\Storage\Session;

/**
 * Class AuthStorage
 *
 * Authentication storage
 *
 * @package Msingi\Cms\Service
 */
class AuthStorage extends Session
{
    /**
     *
     */
    public function __construct($namespace = 'Msingi\Cms\Service\AuthStorage')
    {
        parent::__construct($namespace);
    }

    /**
     * @param bool $rememberMe
     * @param int $time default = 14 days
     */
    public function setRememberMe($rememberMe = false, $time = 1209600)
    {
        if ($rememberMe) {
            $this->session->getManager()->rememberMe($time);
        }
    }

    /**
     *
     */
    public function forgetMe()
    {
        $this->session->getManager()->forgetMe();
    }
}