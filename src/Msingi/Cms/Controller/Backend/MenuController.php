<?php

namespace Msingi\Cms\Controller\Backend;

use Zend\View\Model\ViewModel;

class MenuController extends AuthenticatedController
{
    /**
     *
     * @return array|ViewModel
     */
    public function indexAction()
    {
        $config = $this->getServiceLocator()->get('Config');
        $settings = $this->getServiceLocator()->get('Settings');

        return new ViewModel(array(
            'menus' => $config['menus'],
            'languages' => $settings->get('frontend:languages:enabled')
        ));
    }

}