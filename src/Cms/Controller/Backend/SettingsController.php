<?php

namespace Msingi\Cms\Controller\Backend;

use Msingi\Cms\Model\Settings;
use Zend\View\Model\ViewModel;

class SettingsController extends AuthenticatedController
{
    /**
     * @return array|ViewModel
     */
    public function indexAction()
    {
        $form = $this->getServiceLocator()->get('Msingi\Cms\Form\Backend\SettingsForm');
        $form->init();

        $request = $this->getRequest();
        if ($request->isPost()) {
            //$form->setInputFilter();
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->saveSettings($form->getData());

                return $this->redirect()->toRoute('backend/settings');
            }
        } else {
            $form->setData($this->loadSettings());
        }

        return new ViewModel(array(
            'form' => $form
        ));
    }

    /**
     *
     * @return array
     */
    protected function loadSettings()
    {
        $config = $this->getServiceLocator()->get('Config');
        $settings = $this->getServiceLocator()->get('Settings');

        $settingsConfig = $config['settings'];

        $data = array();
        foreach ($settingsConfig as $section => $spec) {
            $data[$section] = array();
            foreach ($spec['values'] as $value => $valueSpec) {
                $valueName = Settings::formatValueName($value);

                $data[$section][$valueName] = $settings->get($value, isset($valueSpec['default']) ? $valueSpec['default'] : null);
            }
        }

        return $data;
    }

    /**
     *
     * @param $data
     */
    protected function saveSettings($data)
    {
        $config = $this->getServiceLocator()->get('Config');
        $settings = $this->getServiceLocator()->get('Settings');

        $settingsConfig = $config['settings'];

        foreach ($settingsConfig as $section => $spec) {
            foreach ($spec['values'] as $value => $valueSpec) {
                $valueName = Settings::formatValueName($value);

                $settings->set($value, $data[$section][$valueName]);
            }
        }
    }
}