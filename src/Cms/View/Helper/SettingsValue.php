<?php

namespace Msingi\Cms\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;

class SettingsValue extends AbstractHelper implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;

    /**
     * @param $valueName
     * @param null $default
     * @return mixed
     */
    public function __invoke($valueName, $default = null)
    {
        /** @var \Msingi\Cms\Settings $settings */
        $settings = $this->serviceLocator->getServiceLocator()->get('Settings');

        return $settings->get($valueName, $default);
    }

    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
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
}