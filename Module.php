<?php

namespace Msingi;

use Msingi\Cms\View\Helper\PageFragment;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Module implements AutoloaderProviderInterface
{
    public function onBootstrap($e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();

        $eventManager = $e->getApplication()->getEventManager();

        $eventManager->attach($serviceManager->get('Msingi\Cms\RouteListener'));
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

//    public function getViewHelperConfig()
//    {
//        return array(
//            'invokables' => array(
//                'assets' => 'Msingi\View\Helper\Assets',
//                'formElementErrorClass' => 'Msingi\View\Helper\FormElementErrorClass',
//            )
//        );
//    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Msingi\Cms\RouteListener' => 'Msingi\Cms\Service\RouteListenerFactory',

                'Msingi\Cms\Model\Table\Menu' => 'Msingi\Cms\Service\MenuFactory',
                'Msingi\Cms\Model\Table\Pages' => 'Msingi\Cms\Service\PagesFactory',
                'Msingi\Cms\Model\Table\PageFragments' => 'Msingi\Cms\Service\PageFragmentsFactory',

                'MenuTableGateway' => 'Msingi\Cms\Model\Gateway\MenuTableGatewayFactory',
                'PagesTableGateway' => 'Msingi\Cms\Model\Gateway\PagesTableGatewayFactory',
                'PageFragmentsTableGateway' => 'Msingi\Cms\Model\Gateway\PageFragmentsTableGatewayFactory',





                'Msingi\Model\Backend\AuthStorage' => function ($sm) {
                        return new \Msingi\Model\Backend\AuthStorage('msingi-backend');
                    },
                'AuthService' => function ($sm) {
                        $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                        $dbTableAuthAdapter = new DbTableAuthAdapter($dbAdapter, 'backend_users', 'username', 'password', 'MD5(?)');

                        $authService = new AuthenticationService();
                        $authService->setAdapter($dbTableAuthAdapter);
                        $authService->setStorage($sm->get('Msingi\Model\Backend\AuthStorage'));

                        return $authService;
                    },
            ),
        );
    }

    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'Fragment' => function (ServiceLocatorInterface $helpers) {
                        $services = $helpers->getServiceLocator();
                        $app = $services->get('Application');
                        return new PageFragment($app->getMvcEvent());
                    }
            ),
        );
    }
}