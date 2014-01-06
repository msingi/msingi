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
     * @param $name
     * @return string
     */
    protected function getActionUrl($action, array $params = null)
    {
        $event = $this->getEvent();

        $rm = $event->getRouteMatch();

        $params = $rm->getParams();
        $params['action'] = $action;

        return $event->getRouter()->assemble($params, array('name' => $rm->getMatchedRouteName()));
    }

}