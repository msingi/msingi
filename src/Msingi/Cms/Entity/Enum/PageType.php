<?php

namespace Msingi\Cms\Entity\Enum;

use Msingi\Doctrine\EnumType;

/**
 * Class PageType
 * @package Msingi\Cms\Entity\Enum
 */
class PageType extends EnumType
{
    const PAGE_MVC = 'mvc';
    const PAGE_STATIC = 'static';
    const PAGE_URI = 'uri';

    protected $name = 'page_type';
    protected $values = array(
        self::PAGE_MVC,
        self::PAGE_STATIC,
        self::PAGE_URI,
    );
}