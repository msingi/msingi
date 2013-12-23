<?php

namespace Msingi\Cms\Form\Backend;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;

class SettingsForm extends Form implements InputFilterProviderInterface, ServiceManagerAwareInterface
{
    /* @var ServiceManager $serviceManager */
    protected $serviceManager;

    /**
     * @param null $name
     */
    public function __construct($name = null)
    {
        parent::__construct($name != null ? $name : 'settings');
    }

    /**
     *
     */
    public function init()
    {
        $this->setAttribute('method', 'post');

        $config = $this->serviceManager->get('Config');

        $settingsConfig = $config['settings'];

        foreach ($settingsConfig as $section => $spec) {
            $this->add(array(
                'type' => 'Msingi\Cms\Form\Backend\SettingsFieldset',
                'name' => $section,
                'options' => array(
                    'label' => $spec['label'],
                ),
            ));

            $this->get($section)->createControls($spec);
        }
    }

    /**
     * @param ServiceManager $serviceManager
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    /**
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return array();
    }
}