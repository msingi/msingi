<?php

namespace Msingi\Cms\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ContentManagerFactory implements FactoryInterface
{
    protected $config;
    protected $configKey = 'content';

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $this->getConfig($serviceLocator);

        return new ContentManager($config);
    }

    /**
     * @param $serviceLocator
     * @return mixed
     */
    protected function getConfig($serviceLocator)
    {
        if ($this->config != null)
            return $this->config;

        $config = $serviceLocator->get('Config');

        $this->config = $config[$this->configKey];
        return $this->config;
    }
}