<?php

namespace Msingi\Cms\View\Helper;

use Zend\Mvc\Router\RouteMatch;
use Zend\Mvc\Router\RouteStackInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Exception;
use Zend\Stdlib\Exception as StdlibException;
use Zend\View\Helper\AbstractHelper;

/**
 * Class Url
 * @package Msingi\Cms\View\Helper
 */
class Url extends AbstractHelper implements FactoryInterface
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
     * @param string $route
     * @param null $query
     * @return mixed
     * @throws \Zend\View\Exception\RuntimeException
     */
    public function __invoke($route, $query = null)
    {
        if (null === $this->router) {
            throw new Exception\RuntimeException('No RouteStackInterface instance provided');
        }

        $options = array(
            'name' => $route,
            'query' => $query
        );

        //
        return $this->router->assemble(array(), $options);
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

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $helper = new Url();
        $helper->setRouter($serviceLocator->getServiceLocator()->get('Router'));
        $match = $serviceLocator->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch();
        $helper->setRouteMatch($match);
        return $helper;
    }
}