<?php

namespace Msingi\Cms\View\Helper;

use Zend\Mvc\MvcEvent;
use Zend\View\Helper\AbstractHelper;

/**
 * Class PageMeta
 *
 * @package Msingi\Cms\View\Helper
 */
class PageMeta extends AbstractHelper
{
    /** @var \Doctrine\Orm\EntityManager */
    protected $entityManager;

    /** @var \Msingi\Cms\Entity\Page */
    protected $page;

    /** @var \Msingi\Cms\Entity\PageI18n */
    protected $meta;

    /** @var \Zend\Mvc\MvcEvent */
    protected $event;

    /**
     * @param MvcEvent $event
     */
    public function __construct(MvcEvent $event)
    {
        $this->event = $event;
        $routeMatch = $event->getRouteMatch();
        if ($routeMatch) {
            $this->page = $routeMatch->getParam('cms_page');
        }
    }

    /**
     * @param string $name
     * @return string
     */
    public function __invoke($name)
    {
        if ($this->meta == null) {

            $serviceManager = $this->event->getApplication()->getServiceManager();

            $translator = $serviceManager->get('Translator');

            $locale = $translator->getLocale();

            /** @var \Msingi\Cms\Repository\PageI18ns $meta_repository */
            $meta_repository = $this->getEntityManager()->getRepository('Msingi\Cms\Entity\PageI18n');

            $this->meta = $meta_repository->fetchOrCreate($this->page, \Locale::getPrimaryLanguage($locale));
        }

        switch ($name) {
            case 'title':
                return $this->meta->getTitle();
            case 'keywords':
                return $this->meta->getKeywords();
            case 'description':
                return $this->meta->getDescription();
        }

        return '';
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        if (null === $this->entityManager) {
            $this->entityManager = $this->event->getApplication()->getServiceManager()->get('Doctrine\ORM\EntityManager');
        }

        return $this->entityManager;
    }
}