<?php

namespace Msingi\Cms\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Gravatar extends AbstractHelper
{
    /**
     * @return string
     */
    public function __invoke($email, $size = null)
    {
        $hash = md5(strtolower(trim($email)));

        $src = sprintf('http://www.gravatar.com/avatar/%s.jpg', $hash);

        if (intval($size) != 0) {
            $src .= '?s=' . intval($size);
        }

        return $src;
    }

}
