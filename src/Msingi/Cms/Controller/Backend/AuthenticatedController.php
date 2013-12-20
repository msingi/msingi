<?php

namespace Msingi\Cms\Controller\Backend;

use Zend\Mvc\MvcEvent;

class AuthenticatedController extends ActionController
{
    /**
     * @param MvcEvent $e
     * @return mixed
     */
    public function onDispatch(MvcEvent $e)
    {
        if (!$this->getAuthService()->hasIdentity()) {
            return $this->redirect()->toRoute('backend/login');
        }

        return parent::onDispatch($e);
    }
}