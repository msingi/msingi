<?php

namespace Msingi\Cms\Entity\Enum;

use Msingi\Doctrine\EnumType;

/**
 * Class ArticleStatus
 *
 * @package Msingi\Cms\Entity\Enum
 */
class ArticleStatus extends EnumType
{
    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';

    protected $name = 'article_status';
    protected $values = array(
        self::STATUS_DRAFT,
        self::STATUS_PUBLISHED
    );
}