<?php

namespace Msingi;

use Msingi\Cms\Model\Backend\AuthStorage;
use Msingi\Cms\View\Helper\PageFragment;
use Msingi\Cms\View\Helper\Url;
use Zend\Authentication\Adapter\DbTable;
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
     * @return mixed
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
            'invokables' => array(
                'assets' => 'Msingi\Cms\View\Helper\Assets',
                'headLess' => 'Msingi\Cms\View\Helper\HeadLess',
                'deferJs' => 'Msingi\Cms\View\Helper\DeferJs',

                'language' => 'Msingi\Cms\View\Helper\Language',
                'languageName' => 'Msingi\Cms\View\Helper\LanguageName',
                'locale' => 'Msingi\Cms\View\Helper\Locale',

                'gravatar' => 'Msingi\Cms\View\Helper\Gravatar',

                '_' => 'Zend\I18n\View\Helper\Translate',
                '_p' => 'Zend\I18n\View\Helper\TranslatePlural',

                'excerpt' => 'Msingi\Cms\View\Helper\Excerpt',

                'formElementErrorClass' => 'Msingi\Cms\View\Helper\FormElementErrorClass',
            ),
            'factories' => array(
                'Fragment' => function (ServiceLocatorInterface $helpers) {
                        $services = $helpers->getServiceLocator();
                        $app = $services->get('Application');
                        return new PageFragment($app->getMvcEvent());
                    },
                'u' => function (ServiceLocatorInterface $helpers) {
                        $helper = new Url();
                        //
                        $helper->setRouter($helpers->getServiceLocator()->get('Router'));
                        //
                        $match = $helpers->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch();
                        $helper->setRouteMatch($match);
                        //
                        return $helper;
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
            'abstract_factories' => array(
                'Msingi\Service\TableGatewayFactory'
            ),
            'factories' => array(
                'Msingi\Cms\RouteListener' => 'Msingi\Cms\Service\RouteListenerFactory',

//                'Msingi\Cms\Db\Table\Menu' => 'Msingi\Cms\Service\MenuTableFactory',
//                'Msingi\Cms\Db\Table\Pages' => 'Msingi\Cms\Service\PagesTableFactory',
//                'Msingi\Cms\Db\Table\PageFragments' => 'Msingi\Cms\Service\PageFragmentsTableFactory',
//                'Msingi\Cms\Db\Table\PageTemplates' => 'Msingi\Cms\Service\PageTemplatesTableFactory',
//                'Msingi\Cms\Db\Table\BackendUsers' => 'Msingi\Cms\Service\BackendUsersTableFactory',
//                'Msingi\Cms\Db\Table\Settings' => 'Msingi\Cms\Service\SettingsTableFactory',

                'BackendAuthService' => 'Msingi\Cms\Service\Factory\BackendAuthService',
                'Msingi\Cms\Model\BackendAuthStorage' => function ($sm) {
                        return new AuthStorage();
                    },

            ),
            'invokables' => array(
                'Msingi\Cms\Form\Backend\SettingsForm' => 'Msingi\Cms\Form\Backend\SettingsForm',
                'Settings' => 'Msingi\Cms\Model\Settings',
            ),
        );
    }

    /**
     * @param MvcEvent $e
     */
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