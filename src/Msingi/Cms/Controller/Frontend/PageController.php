<?php

namespace Msingi\Cms\Controller\Frontend;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class PageController
 *
 * @package Msingi\Cms\Controller\Frontend
 */
class PageController extends AbstractActionController
{
    /**
     * @return ViewModel
     */
    public function pageAction()
    {
        /** @var \Msingi\Cms\Entity\Page $cms_page */
        $cms_page = $this->params('cms_page');
        if ($cms_page == null) {
            die;
        }

        $vm = new ViewModel(array());

        $vm->setTemplate('frontend/page/' . $cms_page->getTemplate());

        return $vm;
    }

}
