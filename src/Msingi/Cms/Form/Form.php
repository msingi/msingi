<?php

namespace Msingi\Cms\Form;

use Zend\InputFilter\InputFilterProviderInterface;

/**
 * Class Form
 * @package Msingi\Cms\Form
 */
class Form extends \Zend\Form\Form implements InputFilterProviderInterface
{
    /** @var array */
    protected $inputFilterSpecification = array();

    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return $this->inputFilterSpecification;
    }

    /**
     * Placeholder function for gettext translation collector
     *
     * @param $text
     * @return mixed
     */
    protected function _($text)
    {
        return $text;
    }

    /**
     * @return $this
     */
    protected function addCsrfInput()
    {
        $this->add(array(
            'type' => 'Csrf',
            'name' => 'csrf',
            'options' => array(
                'csrf_options' => array(
                    'timeout' => 600,
                )
            ),
        ));

        return $this;
    }
}
