<?php

namespace Msingi\Cms\Form;

class Form extends \Zend\Form\Form
{
    /**
     * Placeholder function for gettext translation collector
     *
     * @param $text
     * @return mixed
     */
    public function _($text)
    {
        return $text;
    }
}
