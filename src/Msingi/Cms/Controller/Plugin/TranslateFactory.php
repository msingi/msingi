<?php

namespace Msingi\Cms\Controller\Plugin;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TranslateFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $translator = $serviceLocator->getServiceLocator()->get('Translator');
        $plugin = new \Msingi\Cms\Controller\Plugin\Translate();
        $plugin->setTranslator($translator);
        return $plugin;
    }
}
