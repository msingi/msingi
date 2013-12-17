<?php

namespace Msingi\Cms\Controller\Frontend;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class PageController extends AbstractActionController
{
    /**
     * @return ViewModel
     */
    public function pageAction()
    {
        $cms_page = $this->params('cms_page');

        $vm = new ViewModel(array());

        $vm->setTemplate('frontend/page/' . $cms_page->template . '.phtml');

        return $vm;
    }

}