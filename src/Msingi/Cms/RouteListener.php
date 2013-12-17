<?php

namespace Msingi\Cms;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\Http\Literal;

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
        $routeMatch = $e->getRouteMatch();

        $cms_page = $routeMatch->getParam('cms_page');
        if ($cms_page == null) {
            $serviceManager = $e->getApplication()->getServiceManager();

            $pagesTable = $serviceManager->get('Msingi\Cms\Model\Table\Pages');

            $cms_page = $pagesTable->fetchOrCreate($routeMatch->getMatchedRouteName());

            $routeMatch->setParam('cms_page', $cms_page);
        }
    }
}