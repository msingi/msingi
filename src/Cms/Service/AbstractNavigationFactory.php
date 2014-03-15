<?php

namespace Msingi\Cms\Service;

use Zend\Navigation\Service;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AbstractNavigationFactory
 * @package Msingi\Cms\Service
 */
abstract class AbstractNavigationFactory extends \Zend\Navigation\Service\AbstractNavigationFactory
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    protected function getPages(ServiceLocatorInterface $serviceLocator)
    {
        if (null === $this->pages) {
            // get translator
            $translator = $serviceLocator->get('Translator');
            $locale = $translator->getLocale();
            $language = \Locale::getPrimaryLanguage($locale);

            // try to get menu from cache
            $cache = $serviceLocator->get('Application\Cache');
            if ($cache) {
                $cacheKey = sprintf('menu_%s_%s', $this->getName(), $language);
                $pages = $cache->getItem($cacheKey);
            }

            if (!$pages) {
                $pages = $this->fetchPages($serviceLocator, $language);
            }

            if ($cache) {
                $cache->setItem($cacheKey, $pages);
            }

            $this->pages = $this->preparePages($serviceLocator, $pages);
        }

        return $this->pages;
    }

    /**
     *
     */
    protected function fetchPages(ServiceLocatorInterface $serviceLocator, $language)
    {
        /** @var \Doctrine\ORM\EntityManager $entityManager */
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');

        /** @var \Msingi\Cms\Repository\Menus $menus */
        $menus = $entityManager->getRepository('Msingi\Cms\Entity\Menu');

        return $menus->fetchMenuArray($this->getName(), $language);
    }
}