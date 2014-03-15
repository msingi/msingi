<?php

namespace Msingi\Cms\Router;

use Zend\Mvc\Router\Http\RouteInterface;
use Zend\Mvc\Router\Http\RouteMatch;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;
use Zend\Stdlib\RequestInterface;

/**
 * Class StaticPage
 *
 * @package Msingi\Cms\Router
 */
class StaticPage implements RouteInterface, ServiceLocatorAwareInterface
{
    /**
     * @var array
     */
    protected $defaults = array();

    /**
     * @var null
     */
    protected $routePluginManager = null;

    /**
     * Create a new page route.
     *
     * @params array $defaults
     */
    public function __construct(array $defaults = array())
    {
        $this->defaults = array_merge(array(
            'controller' => 'frontend-index-page',
            'action' => 'page'
        ), $defaults);
    }

    /**
     * Match a given request.
     *
     * @param RequestInterface $request
     * @param int $pathOffset
     * @return null|RouteMatch|\Zend\Mvc\Router\RouteMatch
     */
    public function match(RequestInterface $request, $pathOffset = null)
    {
        /** @var string $path */
        $path = trim(substr($request->getUri()->getPath(), $pathOffset), '/');

        /** @var \Msingi\Cms\Entity\Page $page */
        $page = $this->loadPage($path);
        if ($page == null)
            return null;

        /** @var array $routeParams */
        $routeParams = array_merge($this->defaults, array(
            'cms_page' => $page
        ));

        $routeMatch = new RouteMatch($routeParams, strlen($page->getPath()));

        return $routeMatch;
    }

    /**
     * @param $path
     * @return \Msingi\Cms\Entity\Page
     */
    protected function loadPage($path)
    {
        $serviceLocator = $this->routePluginManager->getServiceLocator();

        $cache = $serviceLocator->get('Application\Cache');
        if ($cache) {
            $path = preg_replace('/^[a-z0-9_]/', '_', $path);
            $path = preg_replace('/[_]+/', '_', $path);
            $cacheKey = 'page_' . $path;
            $page = $cache->getItem($cacheKey);
        }

        if (!$page) {
            /** @var \Doctrine\ORM\EntityManager $entityManager */
            $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');

            /** @var \Msingi\Cms\Repository\Pages $pages */
            $pages = $entityManager->getRepository('Msingi\Cms\Entity\Page');

            // 1 is root page always
            $parent = $pages->find(1);
            foreach (explode('/', $path) as $slug) {
                /** @var \Msingi\Cms\Entity\Page $page */
                $page = $pages->fetchPage($slug, $parent);
                if ($page == null) {
                    return null;
                }
                $parent = $page;
            }
        }

        if ($cache) {
            $cache->setItem($cacheKey, $page);
        }

        return $page;
    }

    /**
     * Assemble the route.
     *
     * @param array $params
     * @param array $options
     * @return string
     */
    public function assemble(array $params = array(), array $options = array())
    {
        return $params['path'];
    }

    /**
     * Get a list of parameters used while assembling.
     *
     * @return array
     */
    public function getAssembledParams()
    {
        return array();
    }

    /**
     * Create a new route with given options.
     *
     * @param array $options
     * @return void|static
     * @throws InvalidArgumentException
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