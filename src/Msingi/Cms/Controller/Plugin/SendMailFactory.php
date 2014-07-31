<?php

namespace Msingi\Cms\Controller\Plugin;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class SendMailFactory
 *
 * @package Msingi\Cms\Controller\Plugin
 */
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
        /** @var ServiceLocatorInterface $serviceManager */
        $serviceManager = $serviceLocator->getServiceLocator();

        /** @var \Zend\I18n\Translator\Translator $translator */
        $translator = $serviceManager->get('Translator');

        $router = $serviceManager->get('Router');

        /** @var \Msingi\Cms\Mailer\Mailer $mailer */
        $mailer = $serviceManager->get('Msingi\Cms\Mailer\Mailer');

        $plugin = new \Msingi\Cms\Controller\Plugin\SendMail();
        $plugin->setTranslator($translator);
        $plugin->setRouter($router);
        $plugin->setMailer($mailer);

        return $plugin;
    }
}
