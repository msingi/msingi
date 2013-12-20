<?php

namespace Msingi\Cms\Controller\Backend;

use Zend\Mvc\Controller\AbstractActionController;

class AbstractController extends AbstractActionController
{
    protected $authservice;

    protected function getAuthService()
    {
        if (!$this->authservice) {
            $this->authservice = $this->getServiceLocator()->get('BackendAuthService');
        }

        return $this->authservice;
    }
}