<?php

namespace Msingi\Cms\Form\Backend;

use Msingi\Cms\Settings;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

/**
 * Class SettingsFieldset
 *
 * @package Msingi\Cms\Form\Backend
 */
class SettingsFieldset extends Fieldset implements InputFilterProviderInterface
{
    protected $inputFilter = array();

    /**
     * @param null $name
     * @param array $options
     */
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
    }

    /**
     * @param $spec
     */
    public function createControls($spec)
    {
        foreach ($spec['values'] as $value => $valueSpec) {

            $name = Settings::formatValueName($value);

            $options = array(
                'name' => $name,
                'options' => array(
                    'label' => isset($valueSpec['label']) ? $valueSpec['label'] : '',
                ),
                'attributes' => array(
                    'class' => isset($valueSpec['input_class']) ? $valueSpec['input_class'] : 'form-control',
                ),
            );

            if (isset($valueSpec['type']))
                $options['type'] = $valueSpec['type'];

            if (isset($valueSpec['default'])) {
                $default = $valueSpec['default'];
                $options['options']['value'] = $default;
            }

            if (isset($valueSpec['value_options']))
                $options['options']['value_options'] = $valueSpec['value_options'];

            if (isset($valueSpec['help']))
                $options['options']['help'] = $valueSpec['help'];

            $this->add($options);

            //
            $this->inputFilter[$name] = array(
                'required' => isset($valueSpec['required']) && $valueSpec['required']
            );
        }
    }

    /**
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return $this->inputFilter;
    }
}