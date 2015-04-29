<?php

namespace Msingi\Cms\Event;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;

/**
 * Class LocaleListener
 *
 * @package Msingi\Cms\Event
 */
class LocaleListener implements ListenerAggregateInterface
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
        $this->listeners[] = $events->attach(MvcEvent::EVENT_ROUTE, array($this, 'onRoute'), -110);
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
     * @param MvcEvent $event
     */
    public function onRoute(MvcEvent $event)
    {
        $serviceManager = $event->getApplication()->getServiceManager();

        /* @var RouteMatch $routeMatch */
        $routeMatch = $event->getRouteMatch();

        $language = '';

        // check if the language is set by routing (parameter, domain name, etc)
        if ($routeMatch->getParam('language') == '') {
            // get route
            $route = explode('/', $routeMatch->getMatchedRouteName());
            //
            $module = $route[0];

            /** @var \Msingi\Cms\Settings $settings */
            $settings = $event->getApplication()->getServiceManager()->get('Settings');

            // get defaults from settings
            $multilanguage = (bool)$settings->get($module . ':languages:multilanguage');
            $language_default = $settings->get($module . ':languages:default');
            $languages_enabled = $settings->get($module . ':languages:enabled');

            if ($multilanguage && is_array($languages_enabled)) {
                /** @var \Zend\Http\Request $request */
                $request = $event->getRequest();

                // try to get language from cookie
                if ($request->getCookie('language') != '') {
                    $language = $event->getRequest()->getCookie('language');
                }

                // try to get language from browser
                if ($language == '' && isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
                    $language = \Locale::getPrimaryLanguage(\Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']));
                }

                // fallback to default language if given one is not supported
                if (!in_array($language, $languages_enabled)) {
                    $language = $language_default;
                }

            } else {
                // not multilanguage module, use default language
                $language = $language_default;
            }

            $routeMatch->setParam('language', $language);
        } else {
            $language = $routeMatch->getParam('language');
            $language_default = $language;
        }

        // translator
        $translator = $serviceManager->get('Translator');
        $translator->setLocale($language)->setFallbackLocale($language_default);

        // cache
        $cache = $serviceManager->get('Application\Cache');
        if ($cache) {
            $translator->setCache($cache);
        }
    }
}
