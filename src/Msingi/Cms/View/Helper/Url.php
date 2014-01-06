<?php

namespace Msingi\Cms\View\Helper;

use Zend\Mvc\Router\RouteMatch;
use Zend\Mvc\Router\RouteStackInterface;
use Zend\View\Exception;
use Zend\Stdlib\Exception as StdlibException;
use Zend\View\Helper\AbstractHelper;

class Url extends AbstractHelper
{
    /**
     * RouteStackInterface instance.
     *
     * @var RouteStackInterface
     */
    protected $router;

    /**
     * RouteInterface match returned by the router.
     *
     * @var RouteMatch.
     */
    protected $routeMatch;

    /**
     * @param $route
     * @param null $query
     * @return mixed
     * @throws \Zend\View\Exception\RuntimeException
     */
    public function __invoke($route, $query = null)
    {
        if (null === $this->router) {
            throw new Exception\RuntimeException('No RouteStackInterface instance provided');
        }

        //
        $route = explode('/', $route);
        $params = array(
            'controller' => $route[0],
            'action' => $route[1],
        );

        //
        $routeName = explode('/', $this->routeMatch->getMatchedRouteName());
        $routeName = implode('/', array($routeName[0], 'default'));

        $options = array(
            'name' => $routeName,
            'query' => $query
        );

        //
        return $this->router->assemble($params, $options);
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

    /**
     * Set route match returned by the router.
     *
     * @param  RouteMatch $routeMatch
     * @return Url
     */
    public function setRouteMatch(RouteMatch $routeMatch)
    {
        $this->routeMatch = $routeMatch;
        return $this;
    }
}