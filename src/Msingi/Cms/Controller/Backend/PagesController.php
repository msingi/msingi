<?php

namespace Msingi\Cms\Controller\Backend;

use Msingi\Cms\Form\Backend\PagePropertiesForm;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class PagesController extends AuthenticatedController
{
    /**
     * @return array|ViewModel
     */
    public function indexAction()
    {
        $pagesTable = $this->getPagesTable();

        return new ViewModel(array('pages' => $pagesTable->fetchTree()));
    }

    /**
     * @return ViewModel
     */
    public function propertiesAction()
    {
        $pagesTable = $this->getPagesTable();

        $page_id = intval(str_replace('page-', '', $this->params()->fromQuery('page')));

        $page = $pagesTable->fetchById($page_id);

        $form = new PagePropertiesForm();

        $form->bind($page);

        $vm = new ViewModel(array('page' => $page, 'form' => $form));

        $vm->setTerminal(true);

        return $vm;
    }

    /**
     * @return JsonModel
     */
    public function setParentAction()
    {
        $pagesTable = $this->getPagesTable();

        $parent_id = intval(str_replace('page-', '', $this->params()->fromQuery('parent')));
        $child_id = intval(str_replace('page-', '', $this->params()->fromQuery('child')));

        $parent = $pagesTable->fetchById($parent_id);
        $child = $pagesTable->fetchById($child_id);

        if ($parent != null && $child != null) {
            $child->parent_id = $parent->id;
            $result = $pagesTable->save($child);

            return new JsonModel(array('success' => true, 'result' => $result, 'parent_id' => $child->parent_id));
        }

        return new JsonModel(array('success' => false));
    }

    /**
     * @return \Msingi\Cms\Db\Table\Pages
     */
    protected function getPagesTable()
    {
        $serviceManager = $this->getServiceLocator();

        return $serviceManager->get('Msingi\Cms\Db\Table\Pages');
    }
}