<?php

namespace Msingi\Cms\View\Helper;

use Zend\I18n\View\Helper\AbstractTranslatorHelper;

class Language extends AbstractTranslatorHelper
{
    /**
     * @return string
     */
    public function __invoke()
    {
        $translator = $this->getTranslator();

        $locale = $translator->getLocale();

        return \Locale::getPrimaryLanguage($locale);
    }
}