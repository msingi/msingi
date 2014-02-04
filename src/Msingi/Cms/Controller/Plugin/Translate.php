<?php

namespace Msingi\Cms\Controller\Plugin;

use Zend\I18n\Translator\Translator;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * Class Translate
 *
 * @package Msingi\Cms\Controller\Plugin
 */
class Translate extends AbstractPlugin
{
    /** @var Translator */
    protected $translator;

    /**
     * @param $string
     * @return string
     */
    public function __invoke($string)
    {
        return $this->translator->translate($string);
    }

    /**
     * @param Translator $translator
     */
    public function setTranslator(Translator $translator)
    {
        $this->translator = $translator;
    }
}