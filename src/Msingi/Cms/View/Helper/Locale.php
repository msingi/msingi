<?php

namespace Msingi\Cms\View\Helper;

use Zend\I18n\View\Helper\AbstractTranslatorHelper;

class Locale extends AbstractTranslatorHelper
{
    /**
     * @return string
     */
    public function __invoke()
    {
        $translator = $this->getTranslator();

        return $translator->getLocale();
    }
}