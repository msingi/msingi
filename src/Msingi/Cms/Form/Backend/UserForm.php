<?php

namespace Msingi\Cms\Form\Backend;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class UserForm extends Form implements InputFilterProviderInterface
{
    public function __construct($name = null)
    {
        parent::__construct('user');

        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'id',
            'type' => 'hidden'
        ));

        $this->add(array(
            'name' => 'name',
            'type' => 'text',
            'attributes' => array(
                'placeholder' => 'Name',
                'class' => 'form-control input-large',
                'autocomplete' => 'off'
            ),
            'options' => array(
                'label' => 'Name'
            ),
        ));

        $this->add(array(
            'name' => 'username',
            'type' => 'text',
            'attributes' => array(
                'placeholder' => 'User name',
                'class' => 'form-control input-large',
                'autocomplete' => 'off'
            ),
            'options' => array(
                'label' => 'User name'
            ),
        ));

        $this->add(array(
            'name' => 'email',
            'type' => 'text',
            'attributes' => array(
                'placeholder' => 'E-mail',
                'class' => 'form-control input-large',
                'autocomplete' => 'off'
            ),
            'options' => array(
                'label' => 'E-mail'
            ),
        ));

        $this->add(array(
            'name' => 'role',
            'type' => 'select',
            'attributes' => array(
                'class' => 'form-control input-medium',
            ),
            'options' => array(
                'label' => 'Role',
                'value_options' => array(
                    'admin' => 'Admin'
                ),
            ),
        ));

        $this->add(array(
            'name' => 'password',
            'type' => 'password',
            'attributes' => array(
                'placeholder' => 'New password',
                'class' => 'form-control input-medium',
                'autocomplete' => 'off'
            ),
            'options' => array(
                'label' => 'New password'
            ),
        ));

        $this->add(array(
            'name' => 'password_confirm',
            'type' => 'password',
            'attributes' => array(
                'placeholder' => 'Confirm new password',
                'class' => 'form-control input-medium',
                'autocomplete' => 'off'
            ),
            'options' => array(
                'label' => 'Confirm new password'
            ),
        ));
    }

    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return array(
            'name' => array(
                'required' => true,
                'filters' => array(
                    array('name' => 'Zend\Filter\StringTrim'),
                ),
            ),
            'username' => array(
                'required' => true,
                'filters' => array(
                    array('name' => 'Zend\Filter\StringTrim'),
                ),
            ),
            'email' => array(
                'required' => true,
                'filters' => array(
                    array('name' => 'Zend\Filter\StringTrim'),
                ),
            ),
        );
    }
}
