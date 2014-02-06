<?php

namespace Msingi\Cms;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;

/**
 * Class LocaleListener
 *
 * @package Msingi\Cms
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
     * @param MvcEvent $e
     */
    public function onRoute(MvcEvent $e)
    {
        /* @var RouteMatch $routeMatch */
        $routeMatch = $e->getRouteMatch();

        // check if the language is set by routing (parameter, domain name, etc)
        if ($routeMatch->getParam('language') == '') {
            // get route
            $route = explode('/', $routeMatch->getMatchedRouteName());
            //
            $module = $route[0];

            /** @var \Msingi\Cms\Model\Settings $settings */
            $settings = $e->getApplication()->getServiceManager()->get('Settings');

            // get defaults from settings
            $multilanguage = boolval($settings->get($module . ':languages:multilanguage'));
            $language_default = $settings->get($module . ':languages:default');
            $languages_enabled = $settings->get($module . ':languages:enabled');

            if ($multilanguage && is_array($languages_enabled)) {
                /** @var \Zend\Http\Request $request */
                $request = $e->getRequest();

                // try to get language from cookie
                if ($request->getCookie('language') != '') {
                    $language = $e->getRequest()->getCookie('language');
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
        }

        $serviceManager = $e->getApplication()->getServiceManager();

        // translator
        $translator = $serviceManager->get('Translator');
        $translator->setLocale($language)->setFallbackLocale($language_default);

        // cache
        $cache = $serviceManager->get('Application\Cache');
        $translator->setCache($cache);
    }
}