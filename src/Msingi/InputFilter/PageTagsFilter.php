<?php

namespace Msingi\InputFilter;

use Msingi\Util\StripAttributes;

/**
 * Class PageTagsFilter
 *
 * @package Msingi\InputFilter
 */
class PageTagsFilter
{
    /** @var array */
    protected $allowedTags = array(
        'div' => array('class'),
        'p' => array('class'),
        'img' => array('src', 'alt', 'title', 'width', 'height'),
        'a' => array('href', 'target', 'name', 'class', 'id'),
        'table' => array('width', 'border', 'cellspacing', 'cellpadding', 'class'),
        'tr' => array('colspan', 'rowspan', 'class'),
        'td' => array('colspan', 'rowspan', 'class'),
        'span' => array('class'),
        'i' => array(),
        'b' => array(),
        'u' => array(),
        'strong' => array(),
        'em' => array(),
        'br' => array(),
        'h1' => array(),
        'h2' => array(),
        'h3' => array(),
        'h4' => array(),
        'h5' => array(),
        'h6' => array(),
        'ul' => array('class'),
        'ol' => array('class'),
        'li' => array()
    );

    /**
     * @param string $text
     * @param array $allowedTags
     * @return string
     */
    public function filterTags($text, $allowedTags = array())
    {
        $allowedTags = array_merge_recursive($this->allowedTags, $allowedTags);

        // filter tags
        $tags = '<' . implode('><', array_keys($allowedTags)) . '>';
        $text = strip_tags($text, $tags);

        // filter attributes
        $sa = new StripAttributes();
        $sa->exceptions = $allowedTags;
        $text = $sa->strip($text);

        return $text;
    }

}
