<?php

namespace Msingi\Cms\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Excerpt extends AbstractHelper
{
    /**
     * @return string
     */
    public function __invoke($text, $words = 50)
    {
        $text = strip_tags(trim($text));
        $text = preg_replace('/\s+/', ' ', $text);

        $text = explode(' ', $text);

        if (count($text) > $words) {
            return implode(' ', array_slice($text, 0, $words)) . '&hellip;';
        }

        return implode(' ', $text);

    }

}
