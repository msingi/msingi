<?php

namespace Msingi\Cms\Controller\Backend;

use Zend\Mvc\Controller\AbstractActionController;

/**
 * Class ActionController
 *
 * @package Msingi\Cms\Controller\Backend
 */
class ActionController extends AbstractActionController
{
    /** @var \Zend\Authentication\AuthenticationService */
    protected $authService;

    /**
     * @return null|\Zend\Authentication\AuthenticationService
     */
    protected function getAuthService()
    {
        if (!$this->authService) {
            $this->authService = $this->getServiceLocator()->get('Msingi\Cms\Service\Backend\AuthService');
        }

        return $this->authService;
    }

}
