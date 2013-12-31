<?php

namespace Msingi\Cms\View\Helper;

use Zend\I18n\View\Helper\AbstractTranslatorHelper;

class LanguageName extends AbstractTranslatorHelper
{
    /**
     * @return string
     */
    public function __invoke($language)
    {
        return \Locale::getDisplayLanguage($language);
    }
}