<?php

namespace Msingi\Cms\Model\Backend;

use Zend\Authentication\Storage\Session;

class AuthStorage extends Session
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct('msingi\backend\auth');
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