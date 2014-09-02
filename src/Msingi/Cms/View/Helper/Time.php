<?php

namespace Msingi\Cms\View\Helper;

/**
 * Class Time
 * @package Msingi\Cms\View\Helper
 */
class Time extends DateTime
{
    /**
     * @param \DateTime $date
     */
    public function __invoke($date, $datetype = \IntlDateFormatter::NONE, $timetype = \IntlDateFormatter::SHORT)
    {
        return parent::__invoke($date, $datetype, $timetype);
    }
}
