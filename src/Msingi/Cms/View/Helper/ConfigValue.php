<?php

namespace Msingi\Cms\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;

/**
 * Class ConfigValue
 *
 * @package Msingi\Cms\View\Helper
 */
class ConfigValue extends AbstractHelper implements ServiceLocatorAwareInterface
{
    /** @var ServiceLocatorInterface */
    protected $serviceLocator;

    /** @var array */
    protected $config;

    /**
     * Find config part by given name
     *
     * @param string $name
     * @return mixed|null
     */
    public function __invoke($name)
    {
        $search = $this->getConfig();

        $name = explode('/', $name);

        foreach ($name as $part) {
            if (!isset($search[$part]))
                return null;

            $search = $search[$part];
        }

        return $search;
    }

    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator->getServiceLocator();
    }

    /**
     * Get service locator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * Get config
     *
     * @return array|object
     */
    protected function getConfig()
    {
        if ($this->config == null) {
            $this->config = $this->getServiceLocator()->get('Config');
        }

        return $this->config;
    }
}
