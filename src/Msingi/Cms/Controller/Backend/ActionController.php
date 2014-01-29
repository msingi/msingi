<?php

namespace Msingi\Cms\Controller\Backend;

use Zend\Mvc\Controller\AbstractActionController;

class ActionController extends AbstractActionController
{
    protected $authService;

    /**
     * @return null|\Zend\Authentication\AuthenticationService
     */
    protected function getAuthService()
    {
        if (!$this->authService) {
            $this->authService = $this->getServiceLocator()->get('BackendAuthService');
        }

        return $this->authService;
    }

    /**
     * @param $template
     * @param $to
     * @param array $params
     * @return mixed
     */
    protected function sendMail($template, $to, array $params = array())
    {
        $plugin = $this->SendMailPlugin();

        return $plugin->sendMail($template, $to, $params);
    }

}