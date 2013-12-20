<?php

namespace Msingi\Cms\Controller\Backend;

use Zend\Mvc\MvcEvent;

class ActionController extends AbstractController
{
    public function onDispatch(MvcEvent $e)
    {
        if (!$this->getAuthService()->hasIdentity()) {
            return $this->redirect()->toRoute('backend/login');
        }

        return parent::onDispatch($e);
    }
}