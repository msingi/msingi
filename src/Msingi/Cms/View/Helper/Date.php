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
     */
    public function __invoke($date, $datetype = \IntlDateFormatter::MEDIUM, $timetype = \IntlDateFormatter::NONE)
    {
        $translator = $this->getTranslator();

        $df = new \IntlDateFormatter($translator->getLocale(), $datetype, $timetype);

        return $df->format($date);
    }
}
