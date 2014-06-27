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
        $e->getResponse()->setMetadata('No-Cache', true);

        if (!$this->getAuthService()->hasIdentity()) {
            return $this->redirect()->toRoute('backend/login');
        }

        return parent::onDispatch($e);
    }
}
