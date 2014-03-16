<?php

namespace Msingi\Cms\Event;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;

/**
 * Class RouteListener
 *
 * @package Msingi\Cms\Event
 */
class RouteListener implements ListenerAggregateInterface
{
    /**
     * @var \Zend\Stdlib\CallbackHandler[]
     */
    protected $listeners = array();

    /**
     * @param EventManagerInterface $events
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_ROUTE, array($this, 'onRoute'), -100);
    }

    /**
     * @param EventManagerInterface $events
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    /**
     * Called after routing
     *
     * @param MvcEvent $e
     */
    public function onRoute(MvcEvent $e)
    {
        /* @var RouteMatch $routeMatch */
        $routeMatch = $e->getRouteMatch();

        $routeName = $this->formatRouteName($routeMatch);

        if (substr($routeName, 0, 9) == 'frontend/') {
            /** @var \Msingi\Cms\Entity\Page $cms_page */
            $cms_page = $routeMatch->getParam('cms_page');
            if ($cms_page == null) {
                $serviceManager = $e->getApplication()->getServiceManager();

                // get cache
                $cache = $serviceManager->get('Application\Cache');
                if ($cache) {
                    $cacheKey = 'page-' . preg_replace('/[^a-z0-9\-_]/i', '-', $routeName);
                    $cacheKey = preg_replace('/\-+/', '-', $cacheKey);
                    $cms_page = $cache->getItem($cacheKey);
                }

                // fetch page from the DB
                if (!$cms_page) {
                    /** @var \Doctrine\ORM\EntityManager $entityManager */
                    $entityManager = $serviceManager->get('Doctrine\ORM\EntityManager');

                    /** @var \Msingi\Cms\Repository\Pages $pagesRepository */
                    $pagesRepository = $entityManager->getRepository('Msingi\Cms\Entity\Page');

                    $cms_page = $pagesRepository->fetchOrCreate($routeName);
                }

                // store page in cache
                if ($cache) {
                    $cache->setItem($cacheKey, $cms_page);
                }

                $routeMatch->setParam('cms_page', $cms_page);
            }
        }
    }

    /**
     * @param RouteMatch $routeMatch
     * @return mixed
     */
    protected function formatRouteName(RouteMatch $routeMatch)
    {
        $routeName = $routeMatch->getMatchedRouteName();

        return $routeName;
    }
}