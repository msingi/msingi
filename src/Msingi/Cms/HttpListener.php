<?php

namespace Msingi\Cms;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Http\PhpEnvironment\Response;
use Zend\Mvc\MvcEvent;

class HttpListener implements ListenerAggregateInterface
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
        $this->listeners[] = $events->attach(MvcEvent::EVENT_FINISH, array($this, 'onFinish'));
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
     * Called after everything
     *
     * @param MvcEvent $e
     */
    public function onFinish(MvcEvent $e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();
        $settings = $serviceManager->get('Settings');

        $responseHeaders = $e->getResponse()->getHeaders();
        if (!$responseHeaders->has('Content-Type')) {
            $responseHeaders->addHeaderLine('Content-Type', 'text/html; charset=UTF-8');
        }

        if ($settings->get('performance:minify_html')) {
            $this->minifyHtml($e);
        }

        if ($settings->get('performance:cache_control')) {
            if ($e->getResponse()->getMetadata('No-Cache')) {
                $this->addCacheControl($e, false);
            } else {
                $this->addCacheControl($e, $settings->get('performance:cache_lifetime', 300));
            }

            if ($settings->get('performance:conditional')) {
                $this->addConditional($e);
            }
        }
    }

    /**
     * Add Cache-Control headers
     *
     * @param MvcEvent $e
     * @param $lifetime
     */
    protected function addCacheControl(MvcEvent $e, $lifetime)
    {
        $responseHeaders = $e->getResponse()->getHeaders();

        if ($lifetime === false) {
            $responseHeaders->addHeaderLine('Pragma', 'no-cache', true);
            $responseHeaders->addHeaderLine('Cache-Control', 'no-cache', true);
            $responseHeaders->addHeaderLine('Expires', gmdate('D, d M Y H:i:s', time() - 1) . ' GMT', true);
        } else {
            $responseHeaders->addHeaderLine('Pragma', '', true);
            $responseHeaders->addHeaderLine('Expires', gmdate('D, d M Y H:i:s', time() + $lifetime) . ' GMT', true);
            $responseHeaders->addHeaderLine('Cache-Control', 'public, must-revalidate, max-age=' . $lifetime, true);
        }
    }

    /**
     * @param MvcEvent $e
     */
    protected function addConditional(MvcEvent $e)
    {
        $requestHeaders = $e->getRequest()->getHeaders();
        $responseHeaders = $e->getResponse()->getHeaders();

        $etag = md5($e->getResponse()->getContent());
        $responseHeaders->addHeaderLine('ETag', $etag, true);

        if ($requestHeaders->has('If-None-Match')) {
            foreach ($requestHeaders->get('If-None-Match') as $i) {
                if ($i == $etag) {
                    $e->getResponse()->getHeaders()->clearHeaders();
                    $e->getResponse()->setStatusCode(Response::STATUS_CODE_304);
                    $e->getResponse()->setContent(null);
                    break;
                }
            }
        }
    }

    /**
     * @param MvcEvent $e
     */
    protected function minifyHtml(MvcEvent $e)
    {
    }
}