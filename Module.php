<?php

namespace Msingi;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\MvcEvent;

class Module implements AutoloaderProviderInterface
{
    public function onBootstrap($e)
    {
        //$this->initLayouts($e);
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
                'Msingi\Cms\Model\Table\Menu' => 'Msingi\Cms\Service\MenuFactory',
                'Msingi\Cms\Model\Table\Pages' => 'Msingi\Cms\Service\PagesFactory',


                'MenuTableGateway' => 'Msingi\Cms\Model\Gateway\MenuTableGatewayFactory',
                'PagesTableGateway' => 'Msingi\Cms\Model\Gateway\PagesTableGatewayFactory',




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

    protected function initLayouts(MvcEvent $e)
    {
        $e->getApplication()->getEventManager()->getSharedManager()->attach('Zend\Mvc\Controller\AbstractController', 'dispatch', function ($e) {
            $controller = $e->getTarget();
            $controllerClass = get_class($controller);
            $moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));
            $config = $e->getApplication()->getServiceManager()->get('config');
            if (isset($config['module_layouts'][$moduleNamespace])) {
                $controller->layout($config['module_layouts'][$moduleNamespace]);
            } else {
                $controller->layout('layout/' . strtolower($moduleNamespace) . '.phtml');
            }
        }, 100);
    }
}