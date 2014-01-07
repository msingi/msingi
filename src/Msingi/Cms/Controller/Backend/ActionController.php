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
     * @todo solve problem with non-default routes here or in router?
     *
     * @param $action
     * @param array $options
     * @return mixed
     */
    protected function getActionUrl($action, array $query = null)
    {
        $event = $this->getEvent();

        $rm = $event->getRouteMatch();

        $params = $rm->getParams();
        $params['action'] = $action;

        $options = array(
            'name' => $rm->getMatchedRouteName(),
            'query' => $query
        );

        return $event->getRouter()->assemble($params, $options);
    }

}