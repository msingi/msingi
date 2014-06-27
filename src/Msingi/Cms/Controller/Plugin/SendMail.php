<?php

namespace Msingi\Cms\Controller\Plugin;

use Zend\Mvc\I18n\Translator;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Msingi\Cms\Mailer\Mailer;
use Zend\Mvc\Router\RouteStackInterface;

/**
 * Class SendMail
 * @package Msingi\Cms\Controller\Plugin
 */
class SendMail extends AbstractPlugin
{
    /**
     * @var Translator
     */
    protected $translator;

    /**
     * @var RouteStackInterface
     */
    protected $router;

    /**
     * @var Mailer
     */
    protected $mailer;

    /**
     *
     *
     * @param string $templateName
     * @param string $to
     * @param array $params
     * @return
     */
    public function __invoke($templateName, $to, array $params = array())
    {
        // set mail language if not given
        if (!isset($params['language']) && $this->translator) {
            $params['language'] = \Locale::getPrimaryLanguage($this->translator->getLocale());
        }

        // set root url if not given
        if (!isset($params['root_url']) && $this->router) {
            $params['root_url'] = rtrim($this->router->assemble(array('language' => $params['language']), array('name' => 'frontend/index')), '/');
        }

        //
        $params['to'] = $to;

        return $this->mailer->sendMail($templateName, $to, $params);
    }

    /**
     * @param Translator $translator
     */
    public function setTranslator(Translator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param RouteStackInterface $router
     */
    public function setRouter(RouteStackInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param Mailer $mailer
     */
    public function setMailer(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }
}
