<?php

namespace Msingi\Cms\Service;

use Zend\Navigation\Service;
use Zend\ServiceManager\ServiceLocatorInterface;

abstract class AbstractNavigationFactory extends \Zend\Navigation\Service\AbstractNavigationFactory
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    protected function getPages(ServiceLocatorInterface $serviceLocator)
    {
        if (null === $this->pages) {
            $menuTable = $serviceLocator->get('Msingi\Cms\Model\Table\Menu');

            $translator = $serviceLocator->get('Translator');

            $locale = $translator->getLocale();

            $pages = $menuTable->fetchMenu($this->getName(), \Locale::getPrimaryLanguage($locale));

            $this->pages = $this->preparePages($serviceLocator, $pages);
        }

        return $this->pages;
    }
}