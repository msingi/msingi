<?php

namespace Msingi\Mvc\Router;

use Zend\Mvc\Router\Http\RouteInterface;
use Zend\Mvc\Router\Http\RouteMatch;
use Zend\Mvc\Router\Http\TreeRouteStack;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;
use Zend\Stdlib\RequestInterface;

class Page implements RouteInterface, ServiceLocatorAwareInterface
{
    protected $defaults = array();
    protected $routePluginManager = null;

    /**
     * Create a new page route.
     */
    public function __construct(array $defaults = array())
    {
        $this->defaults = array_merge(array(
            'controller' => 'page',
            'action' => 'page'
        ), $defaults);
    }

    /**
     * Match a given request.
     */
    public function match(RequestInterface $request, $pathOffset = null)
    {
        // get the service locator
        $serviceLocator = $this->routePluginManager->getServiceLocator();

        $uri = $request->getUri();
        $path = substr($uri->getPath(), $pathOffset);

        $routeParams = array_merge($this->defaults, array(
            'path' => $path
        ));

        return new RouteMatch($routeParams, strlen($path));
    }

    /**
     * Assemble the route.
     */
    public function assemble(array $params = array(), array $options = array())
    {
        echo 'assemble';
        die;

        return '';
    }

    /**
     * Get a list of parameters used while assembling.
     */
    public function getAssembledParams()
    {
        echo 'getAssembledParams';
        die;

        return array();
    }

    /**
     * Create a new route with given options.
     */
    public static function factory($options = array())
    {
        if ($options instanceof \Traversable) {
            $options = ArrayUtils::iteratorToArray($options);
        } elseif (!is_array($options)) {
            throw new InvalidArgumentException(__METHOD__ . ' expects an array or Traversable set of options');
        }

        if (!isset($options['defaults'])) {
            $options['defaults'] = array();
        }

        return new static($options['defaults']);
    }

    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $routePluginManager
     */
    public function setServiceLocator(ServiceLocatorInterface $routePluginManager)
    {
        $this->routePluginManager = $routePluginManager;
    }

    /**
     * Get service locator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->routePluginManager;
    }

}