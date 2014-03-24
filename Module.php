<?php

namespace Msingi;

use Doctrine\DBAL\Types\Type;
use Msingi\Cms\View\Helper\CurrentRoute;
use Msingi\Cms\View\Helper\PageFragment;
use Msingi\Cms\View\Helper\PageMeta;
use Msingi\Cms\View\Helper\SettingsValue;
use Msingi\Cms\View\Helper\Url;
use Msingi\Doctrine\InjectListener;
use Zend\Authentication\Adapter\DbTable;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class Module
 *
 * @package Msingi
 */
class Module implements AutoloaderProviderInterface, ConfigProviderInterface, BootstrapListenerInterface
{
    /**
     * @param EventInterface $e
     */
    public function onBootstrap(EventInterface $e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();

        $config = $serviceManager->get('Config');

        //
        $eventManager = $e->getApplication()->getEventManager();

        // route matching
        $eventManager->attach($serviceManager->get('Msingi\Cms\Event\RouteListener'));
        // determine locale
        //$eventManager->attach($serviceManager->get('Msingi\Cms\Event\LocaleListener'));
        // http processing
        $eventManager->attach($serviceManager->get('Msingi\Cms\Event\HttpListener'));

        $this->initLayouts($e);

        // Enable using of enum fields with Doctrine ORM
        /** @var \Doctrine\ORM\EntityManager $entityManager */
        $entityManager = $serviceManager->get('Doctrine\ORM\EntityManager');

        $platform = $entityManager->getConnection()->getDatabasePlatform();
        $platform->registerDoctrineTypeMapping('enum', 'string');

        // Register enum types
        if (isset($config['doctrine']['enums'])) {
            foreach ($config['doctrine']['enums'] as $enum => $className) {
                Type::addType($enum, $className);
            }
        }

        //
        $eventManager = $entityManager->getEventManager();
        $eventManager->addEventListener(array(\Doctrine\ORM\Events::postLoad), new InjectListener($serviceManager));
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * @return array
     */
    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'currentRoute' => function (ServiceLocatorInterface $helpers) {
                        $viewHelper = new CurrentRoute();
                        $viewHelper->setServiceLocator($helpers->getServiceLocator());
                        return $viewHelper;
                    },
                'fragment' => function (ServiceLocatorInterface $helpers) {
                        $services = $helpers->getServiceLocator();
                        $app = $services->get('Application');
                        return new PageFragment($app->getMvcEvent());
                    },
                'metaValue' => function (ServiceLocatorInterface $helpers) {
                        $services = $helpers->getServiceLocator();
                        $app = $services->get('Application');
                        return new PageMeta($app->getMvcEvent());
                    },
                'settingsValue' => function (ServiceLocatorInterface $helpers) {
                        $viewHelper = new SettingsValue();
                        $viewHelper->setServiceLocator($helpers->getServiceLocator());
                        return $viewHelper;
                    },
                'u' => function (ServiceLocatorInterface $helpers) {
                        $helper = new Url();
                        $helper->setRouter($helpers->getServiceLocator()->get('Router'));
                        $match = $helpers->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch();
                        $helper->setRouteMatch($match);
                        return $helper;
                    },
            ),
        );
    }

    /**
     * @return array
     */
    public function getAutoloaderConfig()
    {
        if (getenv('APPLICATION_ENV') != 'production') {
            // use standard autoloader
            return array(
                'Zend\Loader\StandardAutoloader' => array(
                    'namespaces' => array(
                        __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                    )
                )
            );
        } else {
            // use classmap autoloader
            return array(
                'Zend\Loader\ClassMapAutoloader' => array(
                    __DIR__ . '/autoload_classmap.php',
                ),
            );
        }
    }

    /**
     * @todo check this code
     *
     * @param EventInterface $e
     */
    protected function initLayouts(EventInterface $e)
    {
        $eventManager = $e->getApplication()->getEventManager();

        $eventManager->getSharedManager()->attach('Zend\Mvc\Controller\AbstractController', 'dispatch', function ($e) {
            $controller = $e->getTarget();
            $controllerClass = get_class($controller);
            $moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));
            $config = $e->getApplication()->getServiceManager()->get('config');
            if (isset($config['module_layouts'][$moduleNamespace])) {
                $controller->layout($config['module_layouts'][$moduleNamespace]);
            } else {
                $controller->layout('layout/' . strtolower($moduleNamespace));
            }
        }, 100);

        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }
}
