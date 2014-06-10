<?php

namespace Msingi\Cms\View\Helper;

use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;

/**
 * Class PageFragment
 *
 * @package Msingi\Cms\View\Helper
 */
class PageFragment extends AbstractHelper implements FactoryInterface
{
    /**
     * @var \Msingi\Cms\Entity\Page|null
     */
    protected $page = null;

    /**
     * @var array
     */
    protected $fragments = null;

    /**
     * @var \Zend\Mvc\MvcEvent
     */
    protected $event = null;

    /**
     * @param MvcEvent $event
     */
    public function __construct(MvcEvent $event)
    {
        $this->event = $event;
        $this->page = $event->getRouteMatch()->getParam('cms_page');
        if (!$this->page) {
            $this->fragments = array();
        }
    }

    /**
     * @param $name
     * @return string
     */
    public function __invoke($name)
    {
        if ($this->fragments == null) {
            //
            $serviceManager = $this->event->getApplication()->getServiceManager();

            // get translator
            $translator = $serviceManager->get('Translator');
            $locale = $translator->getLocale();
            $language = \Locale::getPrimaryLanguage($locale);

            // try to get fragments from cache
            $cache = $serviceManager->get('Application\Cache');
            if ($cache) {
                $cacheKey = sprintf('page-fragments-%s-%s', $this->page->getId(), $language);
                $page_fragments = $cache->getItem($cacheKey);
            }

            // fetch from the DB
            if ($page_fragments == null) {
                /** @var \Doctrine\ORM\EntityManager $entity_manager */
                $entity_manager = $serviceManager->get('Doctrine\ORM\EntityManager');

                $page_fragments_repository = $entity_manager->getRepository('Msingi\Cms\Entity\PageFragment');

                $page_fragments = $page_fragments_repository->fetchFragmentsArray($this->page->getId(), $language);
            }

            // store to cache
            if ($cache) {
                $cache->setItem($cacheKey, $page_fragments);
            }

            $this->fragments = $page_fragments;
        }

        return isset($this->fragments[$name]) ? $this->fragments[$name] : '';
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return PageFragment
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $services = $serviceLocator->getServiceLocator();
        $app = $services->get('Application');
        return new PageFragment($app->getMvcEvent());
    }
}