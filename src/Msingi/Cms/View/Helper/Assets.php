<?php

namespace Msingi\Cms\View\Helper;

use Msingi\Cms\View\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class Assets
 *
 * @package Msingi\Cms\View\Helper
 */
class Assets extends AbstractHelper
{
    /** @var array */
    protected $config;

    /** @var ServiceLocatorInterface */
    protected $serviceManager;

    /** @var string */
    protected $moduleName;

    /** @var int */
    protected $call = 0;

    /**
     * @return string
     */
    public function __invoke()
    {
        $config = $this->getConfig();

        $moduleName = $this->getModuleName();

        if (is_array($config[$moduleName])) {

            $hostnames = $config[$moduleName]['hostnames'];

            $load = isset($config[$moduleName]['load']) ? $config[$moduleName]['load'] : 4;

            $index = ($this->call / $load) % count($hostnames);

            $assetsHost = $hostnames[$index];
        } else {
            $assetsHost = $config[$moduleName];
        }

        $this->call += 1;

        return $assetsHost;
    }

    /**
     * @return mixed
     */
    protected function getConfig()
    {
        if (!$this->config) {
            $config = $this->getServiceManager()->get('Config');

            $this->config = $config['assets'];
        }

        return $this->config;
    }

    /**
     * @return ServiceLocatorInterface
     */
    protected function getServiceManager()
    {
        if (!$this->serviceManager) {
            $this->serviceManager = $this->serviceLocator->getServiceLocator();
        }

        return $this->serviceManager;
    }

    /**
     * @return string
     */
    protected function getModuleName()
    {
        if (!$this->moduleName) {

            $moduleName = 'frontend';

            /** @var \Zend\Mvc\Application $application */
            $application = $this->getServiceManager()->get('Application');

            $mvcEvent = $application->getMvcEvent();

            $route_match = $mvcEvent->getRouteMatch();
            if ($route_match != null) {
                $route_name = $route_match->getMatchedRouteName();

                $moduleName = substr($route_name, 0, strpos($route_name, '/'));
            }

            $this->moduleName = $moduleName;
        }

        return $this->moduleName;
    }
}
