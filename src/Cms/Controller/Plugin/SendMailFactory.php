<?php

namespace Msingi\Cms\Controller\Plugin;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SendMailFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $translator = $sm->getServiceLocator()->get('Translator');
        $router = $sm->getServiceLocator()->get('Router');
        $mailer = $sm->getServiceLocator()->get('Msingi\Cms\Mailer\Mailer');

        $plugin = new \Msingi\Cms\Controller\Plugin\SendMail();
        $plugin->setTranslator($translator);
        $plugin->setRouter($router);
        $plugin->setMailer($mailer);

        return $plugin;
    }
}