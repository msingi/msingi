<?php

namespace Msingi\Cms\View\Helper;

use Zend\I18n\View\Helper\AbstractTranslatorHelper;

/**
 * Class Date
 * @package Msingi\Cms\View\Helper
 */
class Date extends AbstractTranslatorHelper
{
    /**
     * @param \DateTime $date
     * @param null $format
     */
    public function __invoke($date, $format = null)
    {
        $translator = $this->getTranslator();

        $df = new \IntlDateFormatter($translator->getLocale(), \IntlDateFormatter::MEDIUM, \IntlDateFormatter::NONE);

        return $df->format($date);
    }
}