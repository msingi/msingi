<?php

namespace Msingi\Cms\Service;

use Zend\Authentication\Storage\Session;

/**
 * Class AuthStorage
 *
 * @package Msingi\Cms\Service
 */
class AuthStorage extends Session
{
    /**
     *
     */
    public function __construct($namespace)
    {
        parent::__construct($namespace);
    }

    /**
     * @param int $rememberMe
     * @param int $time
     */
    public function setRememberMe($rememberMe = 0, $time = 1209600)
    {
        if ($rememberMe == 1) {
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