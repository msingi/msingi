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
        $vm = new ViewModel(array());

        $cms_page = $this->params('cms_page');

        $vm->setTemplate('frontend/page/' . $cms_page->template);

        return $vm;
    }

}