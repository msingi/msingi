<?php

namespace Msingi\Cms\Controller\Backend;

use Zend\Mvc\MvcEvent;

/**
 * Class AuthenticatedController
 *
 * @package Msingi\Cms\Controller\Backend
 */
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

        $e->getResponse()->setMetadata('No-Cache', true);

        return parent::onDispatch($e);
    }
}