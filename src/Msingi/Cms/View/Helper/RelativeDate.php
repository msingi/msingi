<?php

namespace Msingi\Cms\View\Helper;

use Zend\I18n\View\Helper\AbstractTranslatorHelper;

/**
 * Class RelativeDate
 *
 * @package Msingi\Cms\View\Helper
 */
class RelativeDate extends AbstractTranslatorHelper
{
    /**
     * @param \DateTime $date
     */
    public function __invoke($date)
    {
        /** @var \Zend\I18n\Translator\Translator $t */
        $t = $this->getTranslator();

        //
        if ($date == null)
            return $t->translate('never');

        // get difference in seconds
        $ts = $date->getTimestamp();
        $diff = time() - $ts;
        if ($diff == 0)
            return $t->translate('now');
        else
            if ($diff > 0) {
                $day_diff = floor($diff / 86400);
                if ($day_diff == 0) {
                    if ($diff < 60)
                        return $t->translate('just now');
                    if ($diff < 120)
                        return $t->translate('1 minute ago');
                    if ($diff < 3600)
                        return sprintf($t->translate('%d minutes ago'), floor($diff / 60));
                    if ($diff < 7200)
                        return $t->translate('1 hour ago');
                    if ($diff < 86400)
                        return sprintf($t->translate('%d hours ago'), floor($diff / 3600));
                }
                if ($day_diff == 1)
                    return $t->translate('Yesterday');
                if ($day_diff < 7)
                    return sprintf($t->translate('%d days ago'), $day_diff);
                if ($day_diff < 31)
                    return sprintf($t->translate('%d weeks ago'), ceil($day_diff / 7));
                if ($day_diff < 60)
                    return $t->translate('last month');

                return date('F Y', $ts);
            } else {
                $diff = abs($diff);
                $day_diff = floor($diff / 86400);
                if ($day_diff == 0) {
                    if ($diff < 120)
                        return $t->translate('in a minute');
                    if ($diff < 3600)
                        return sprintf($t->translate('in %d minutes'), floor($diff / 60));
                    if ($diff < 7200)
                        return $t->translate('in an hour');
                    if ($diff < 86400)
                        return sprintf($t->translate('in %d hours'), floor($diff / 3600));
                }
                if ($day_diff == 1)
                    return $t->translate('Tomorrow');
                if ($day_diff < 4)
                    return date('l', $ts);
                if ($day_diff < 7 + (7 - date('w')))
                    return $t->translate('next week');
                if (ceil($day_diff / 7) < 4)
                    return sprintf($t->translate('in %d weeks'), ceil($day_diff / 7));
                if (date('n', $ts) == date('n') + 1)
                    return $t->translate('next month');

                return date('F Y', $ts);
            }
    }
}