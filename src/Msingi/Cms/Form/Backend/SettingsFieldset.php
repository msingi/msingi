<?php

namespace Msingi\Cms\Form\Backend;

use Zend\Feed\PubSubHubbub\HttpResponse;
use Zend\Form\Fieldset;

class SettingsFieldset extends Fieldset
{
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

            $options = array(
                'name' => $this->formatValueName($value),
                'options' => array(
                    'label' => $valueSpec['label']
                ),
                'attributes' => array(
                    'class' => 'form-control'
                ),
            );

            if (isset($valueSpec['type']))
                $options['type'] = $valueSpec['type'];

            if (isset($valueSpec['value_options']))
                $options['options']['value_options'] = $valueSpec['value_options'];

            $this->add($options);

        }
    }

    /**
     * @param $valueName
     * @return mixed
     */
    protected function formatValueName($valueName)
    {
        $valueName = preg_replace('/[^a-z0-9_]/i', '_', $valueName);
        $valueName = preg_replace('/[_]+/', '_', $valueName);
        return $valueName;
    }
}