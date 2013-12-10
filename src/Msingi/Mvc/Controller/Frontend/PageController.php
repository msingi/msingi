<?php

namespace Msingi\Mvc\Controller\Frontend;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class PageController extends AbstractActionController
{
    /**
     * @return ViewModel
     */
    public function pageAction()
    {
        $vm = new ViewModel();

        $vm->setTemplate('frontend/page/default.phtml');

        return $vm;
    }
}