<?php

namespace Msingi\Cms\View\Helper;

use Zend\I18n\View\Helper\AbstractTranslatorHelper;

/**
 * Class DateTime
 * @package Msingi\Cms\View\Helper
 */
class DateTime extends AbstractTranslatorHelper
{
    /**
     * @param \DateTime $date
     */
    public function __invoke($date, $datetype = \IntlDateFormatter::MEDIUM, $timetype = \IntlDateFormatter::SHORT)
    {
        if (!$date) {
            return '';
        }

        $translator = $this->getTranslator();

        $df = new \IntlDateFormatter($translator->getLocale(), $datetype, $timetype);

        return $df->format($date);
    }
}
