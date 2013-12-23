<?php

namespace Msingi\Cms\Controller\Backend;

use Msingi\Cms\Form\Backend\SettingsForm;
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

        return new ViewModel(array(
            'form' => $form
        ));
    }

}