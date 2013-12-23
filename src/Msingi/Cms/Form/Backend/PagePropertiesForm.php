<?php

namespace Msingi\Cms\Form\Backend;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class PagePropertiesForm extends Form implements InputFilterProviderInterface
{
    /**
     * @param null $name
     */
    public function __construct($name = null)
    {
        parent::__construct($name != null ? $name : 'page-properties');

        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'id',
            'type' => 'hidden'
        ));

        $this->add(array(
            'name' => 'path',
            'type' => 'text',
            'attributes' => array(
                'class' => 'form-control placeholder-no-fix',
            ),
            'options' => array(
                'label' => 'Username'
            ),
        ));

        $this->add(array(
            'name' => 'template',
            'type' => 'select',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Template',
                'value_options' => array(
                    'default' => 'Default',
                    'homepage' => 'Homepage',
                )
            ),
        ));
    }

    /**
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return array(
            'username' => array(
                'required' => true,
                'filters' => array(
                    array('name' => 'Zend\Filter\StringTrim'),
                ),
            ),
        );
    }
}