<?php

namespace Msingi;

use Doctrine\DBAL\Types\Type;
use Msingi\Doctrine\InjectListener;
use Zend\Authentication\Adapter\DbTable;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

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
        $eventManager->attach($serviceManager->get('Msingi\Cms\Event\LocaleListener'));
        // http processing
        $eventManager->attach($serviceManager->get('Msingi\Cms\Event\HttpListener'));

        $this->initLayouts($e);

        // Enable using of enum fields with Doctrine ORM
        /** @var \Doctrine\ORM\EntityManager $entityManager */
        $entityManager = $serviceManager->get('Doctrine\ORM\EntityManager');

        $this->registerDoctrineEnums($entityManager, $config);

        $this->registerDoctrineSets($entityManager, $config);

        $this->registerDoctrineFunctions($entityManager, $config);

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

        $eventManager->getSharedManager()->attach('Zend\Mvc\Controller\AbstractController', 'dispatch', function (MvcEvent $event) {
            $controller = $event->getTarget();
            $controllerClass = get_class($controller);
            $moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));

            $config = $event->getApplication()->getServiceManager()->get('config');

            if (isset($config['module_layouts'][$moduleNamespace])) {
                $layout = $config['module_layouts'][$moduleNamespace];
            } else {
                $layout = strtolower($moduleNamespace) . '/layout/layout';
            }

            $controller->layout($layout);

        }, 100);

        $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, function (MvcEvent $event) {
            $viewModel = $event->getViewModel();
            $viewModel->setTemplate('frontend/layout/error');
        }, -200);

        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    /**
     * @param \Doctrine\ORM\EntityManager $entityManager
     * @param array $config
     */
    protected function registerDoctrineEnums($entityManager, $config)
    {
        $platform = $entityManager->getConnection()->getDatabasePlatform();
        $platform->registerDoctrineTypeMapping('enum', 'string');

        if (isset($config['doctrine']['enums'])) {
            foreach ($config['doctrine']['enums'] as $enum => $className) {
                Type::addType($enum, $className);
            }
        }
    }

    /**
     * @param \Doctrine\ORM\EntityManager $entityManager
     * @param array $config
     */
    protected function registerDoctrineSets($entityManager, $config)
    {
        $platform = $entityManager->getConnection()->getDatabasePlatform();
        $platform->registerDoctrineTypeMapping('set', 'string');

        if (isset($config['doctrine']['sets'])) {
            foreach ($config['doctrine']['sets'] as $set => $className) {
                Type::addType($set, $className);
            }
        }
    }

    /**
     * @param \Doctrine\ORM\EntityManager $entityManager
     * @param array $config
     */
    protected function registerDoctrineFunctions($entityManager, $config)
    {
        if (isset($config['doctrine']['functions'])) {
            $emConfig = $entityManager->getConfiguration();

            // datetime functions
            if (isset($config['doctrine']['functions']['datetime'])) {
                foreach ($config['doctrine']['functions']['datetime'] as $function => $className) {
                    $emConfig->addCustomDatetimeFunction($function, $className);
                }
            }

            // numeric functions
            if (isset($config['doctrine']['functions']['numeric'])) {
                foreach ($config['doctrine']['functions']['numeric'] as $function => $className) {
                    $emConfig->addCustomNumericFunction($function, $className);
                }
            }

            // string functions
            if (isset($config['doctrine']['functions']['string'])) {
                foreach ($config['doctrine']['functions']['string'] as $function => $className) {
                    $emConfig->addCustomStringFunction($function, $className);
                }
            }
        }
    }
}
