<?php

namespace Msingi\Cms\View\Helper;

/**
 * Class Date
 * @package Msingi\Cms\View\Helper
 */
class Date extends DateTime
{
    /**
     * @param \DateTime $date
     */
    public function __invoke($date, $datetype = \IntlDateFormatter::MEDIUM, $timetype = \IntlDateFormatter::NONE)
    {
        return parent::__invoke($date, $datetype, $timetype);
    }
}
