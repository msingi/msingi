<?php

namespace Msingi\Cms\View\Helper;

use Zend\Mvc\Router\RouteStackInterface;
use Zend\View\Exception;
use Zend\Stdlib\Exception as StdlibException;
use Zend\View\Helper\AbstractHelper;

class SegmentRoute extends AbstractHelper
{
    /**
     * RouteStackInterface instance.
     *
     * @var RouteStackInterface
     */
    protected $router;

    /**
     * @param $route
     * @param null $query
     * @return mixed
     * @throws \Zend\View\Exception\RuntimeException
     */
    public function __invoke($route, $params, $query = null)
    {
        if (null === $this->router) {
            throw new Exception\RuntimeException('No RouteStackInterface instance provided');
        }

        //
        $params = explode('/', $params);
        $routeParams = array(
            'controller' => isset($params[0]) ? $params[0] : 'index',
            'action' => isset($params[1]) ? $params[1] : 'index',
        );

        $options = array(
            'name' => $route,
            'query' => $query
        );

        //
        return $this->router->assemble($routeParams, $options);
    }

    /**
     * Set the router to use for assembling.
     *
     * @param RouteStackInterface $router
     * @return Url
     */
    public function setRouter(RouteStackInterface $router)
    {
        $this->router = $router;
        return $this;
    }
}
