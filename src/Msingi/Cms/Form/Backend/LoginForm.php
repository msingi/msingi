<?php

namespace Msingi\Cms\Form\Backend;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class LoginForm extends Form implements InputFilterProviderInterface
{
    public function __construct($name = null)
    {
        parent::__construct('login');

        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'username',
            'type' => 'text',
            'attributes' => array(
                'placeholder' => 'Username',
                'class' => 'form-control placeholder-no-fix',
                'autocomplete' => 'off'
            ),
            'options' => array(
                'label' => 'Username'
            ),
        ));

        $this->add(array(
            'name' => 'password',
            'type' => 'password',
            'attributes' => array(
                'placeholder' => 'Password',
                'class' => 'form-control placeholder-no-fix',
                'autocomplete' => 'off'
            ),
            'options' => array(
                'label' => 'Password',
            ),
        ));

        $this->add(array(
            'name' => 'remember',
            'type' => 'Zend\Form\Element\Checkbox',
            'attributes' => array(),
            'options' => array(
                'label' => 'Remember me',
                'label_attributes' => array(
                    'class' => 'checkbox'
                ),
                'use_hidden_element' => false,
            ),
        ));
    }

    public function getInputFilterSpecification()
    {
        return array(
            'username' => array(
                'filters' => array(
                    array('name' => 'Zend\Filter\StringTrim'),
                ),
            ),
            'password' => array(
                'required' => true,
                'filters' => array(
                    array('name' => 'Zend\Filter\StringTrim'),
                ),
            ),
            'remember' => array(
                'required' => false,
                'filters' => array(
                    array('name' => 'Zend\Filter\StringTrim'),
                ),
            ),
        );
    }
}
