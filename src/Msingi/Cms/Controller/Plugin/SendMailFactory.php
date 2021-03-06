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
        /** @var \Zend\I18n\Translator\Translator $translator */
        $translator = $serviceLocator->getServiceLocator()->get('Translator');
        $router = $serviceLocator->getServiceLocator()->get('Router');
        /** @var \Msingi\Cms\Mailer\Mailer $mailer */
        $mailer = $serviceLocator->getServiceLocator()->get('Msingi\Cms\Mailer\Mailer');

        $plugin = new \Msingi\Cms\Controller\Plugin\SendMail();
        $plugin->setTranslator($translator);
        $plugin->setRouter($router);
        $plugin->setMailer($mailer);

        return $plugin;
    }
}