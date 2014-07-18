<?php

namespace Msingi\Cms\View\Helper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;

/**
 * Class PageMeta
 *
 * @package Msingi\Cms\View\Helper
 */
class PageMeta extends AbstractHelper implements FactoryInterface
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

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $services = $serviceLocator->getServiceLocator();

        $app = $services->get('Application');

        $event = $app->getMvcEvent();

        $meta = new PageMeta();

        $meta->event = $event;
        $routeMatch = $event->getRouteMatch();
        if ($routeMatch) {
            $meta->page = $routeMatch->getParam('cms_page');
        }

        return $meta;
    }
}
