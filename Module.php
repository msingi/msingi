<?php

namespace Msingi;

use Msingi\Cms\View\Helper\PageFragment;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceLocatorInterface;

class Module implements AutoloaderProviderInterface
{
    /**
     * @param $e
     */
    public function onBootstrap($e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();

        $eventManager = $e->getApplication()->getEventManager();
        $eventManager->attach($serviceManager->get('Msingi\Cms\RouteListener'));

        $this->initLayouts($e);


    }

    /**
     * @return array
     */
    public function getViewHelperConfig()
    {
        return array(
            'invokables' => array(
                'assets' => 'Msingi\Cms\View\Helper\Assets',
                'headLess' => 'Msingi\Cms\View\Helper\HeadLess',
                'deferJs' => 'Msingi\Cms\View\Helper\DeferJs',

                'language' => 'Msingi\Cms\View\Helper\Language',
                'locale' => 'Msingi\Cms\View\Helper\Locale',

                '_' => 'Zend\I18n\View\Helper\Translate',
                '_p' => 'Zend\I18n\View\Helper\TranslatePlural',

                'formElementErrorClass' => 'Msingi\Cms\View\Helper\FormElementErrorClass',
            ),
            'factories' => array(
                'Fragment' => function (ServiceLocatorInterface $helpers) {
                        $services = $helpers->getServiceLocator();
                        $app = $services->get('Application');
                        return new PageFragment($app->getMvcEvent());
                    }
            ),

        );
    }

    /**
     * @return array
     */
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

    /**
     * @return array
     */
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Msingi\Cms\RouteListener' => 'Msingi\Cms\Service\RouteListenerFactory',

                'Msingi\Cms\Model\Table\Menu' => 'Msingi\Cms\Service\MenuFactory',
                'Msingi\Cms\Model\Table\Pages' => 'Msingi\Cms\Service\PagesFactory',
                'Msingi\Cms\Model\Table\PageFragments' => 'Msingi\Cms\Service\PageFragmentsFactory',

                'MenuTableGateway' => 'Msingi\Cms\Service\MenuTableGatewayFactory',
                'PagesTableGateway' => 'Msingi\Cms\Service\PagesTableGatewayFactory',
                'PageFragmentsTableGateway' => 'Msingi\Cms\Service\PageFragmentsTableGatewayFactory',

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
        $eventManager = $e->getApplication()->getEventManager();

        $eventManager->getSharedManager()->attach('Zend\Mvc\Controller\AbstractController', 'dispatch', function ($e) {
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

        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }
}