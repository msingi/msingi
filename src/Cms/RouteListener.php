<?php

namespace Msingi\Cms;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use Doctrine\ORM\EntityManager;

/**
 * Class RouteListener
 *
 * @package Msingi\Cms
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
            $cms_page = $routeMatch->getParam('cms_page');
            if ($cms_page == null) {
                $serviceManager = $e->getApplication()->getServiceManager();

                /** @var \Doctrine\ORM\EntityManager $entityManager */
                $entityManager = $serviceManager->get('Doctrine\ORM\EntityManager');

                $pagesRepository = $entityManager->getRepository('Msingi\Cms\Entity\Page');

                $cms_page = $pagesRepository->fetchOrCreate($routeName);

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