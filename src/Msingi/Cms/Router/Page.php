<?php

namespace Msingi\Cms\Router;

use Zend\Mvc\Router\Http\RouteInterface;
use Zend\Mvc\Router\Http\RouteMatch;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;
use Zend\Stdlib\RequestInterface;

class Page implements RouteInterface, ServiceLocatorAwareInterface
{
//    protected $route = '';
//    protected $page = '';
    protected $defaults = array();
    protected $routePluginManager = null;
    protected $pages;

    /**
     * Create a new page route.
     */
    public function __construct(array $defaults = array())
    {
//        $this->route = $route;
//        $this->page = $page;

        $this->defaults = array_merge(array(
            'controller' => 'frontend-page',
            'action' => 'page'
        ), $defaults);
    }

    /**
     * Match a given request.
     */
    public function match(RequestInterface $request, $pathOffset = null)
    {
        $uri = $request->getUri();
        $path = substr($uri->getPath(), $pathOffset);

        $page = $this->loadPage($path);

        if ($page != null) {
            $routeParams = array_merge($this->defaults, array(
                'cms_page' => $page
            ));

            return new RouteMatch($routeParams, strlen($path));
        }

        return null;
    }

    /**
     * @param $path
     */
    protected function loadPage($path)
    {
        $serviceLocator = $this->routePluginManager->getServiceLocator();

        $pagesTable = $serviceLocator->get('Msingi\Cms\Model\Table\Pages');

        $path = explode('/', $path);

        // 1 is root page always
        $parent_id = 1;
        foreach ($path as $slug) {
            $page = $pagesTable->fetchPage($slug, $parent_id);

            if ($page == null) {
                return null;
            }

            $parent_id = $page->id;
        }

        return $page;
    }

    /**
     * Assemble the route.
     */
    public function assemble(array $params = array(), array $options = array())
    {
        return $params['path'];
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