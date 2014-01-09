<?php

namespace Msingi\Cms\Controller\Backend;

use Msingi\Cms\Form\Backend\PagePropertiesForm;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class PagesController extends AuthenticatedController
{
    protected $pagesTable;

    /**
     * @return array|ViewModel
     */
    public function indexAction()
    {
        return new ViewModel(array(
            'pages' => $this->getPagesTable()->fetchTree()
        ));
    }

    /**
     *
     */
    public function editAction()
    {
        //
        $pagesTable = $this->getPagesTable();
        $templatesTable = $this->getPageTemplatesTable();
        $pageFragmentsTable = $this->getPageFragmentsTable();

        //
        $language = trim($this->params()->fromQuery('language'));
        $page_id = intval($this->params()->fromQuery('page'));

        //
        $page = $pagesTable->fetchById($page_id);
        if ($page == null) {
            return $this->redirect()->toRoute('backend/default', array('controller' => 'pages', 'action' => 'index'));
        }

        //
        $template = $templatesTable->fetchByName($page->template);

        $fragmentNames = array_filter(explode(',', $template['fragments']));

        $fragments = $pageFragmentsTable->fetchFragments($page_id, \Locale::getPrimaryLanguage($language));

        //
        return new ViewModel(array(
            'page' => $page,
            'fragmentNames' => $fragmentNames,
            'fragments' => $fragments,
            'language' => $language
        ));
    }

    /**
     *
     */
    public function saveAction()
    {
        //
        $pagesTable = $this->getPagesTable();
        $templatesTable = $this->getPageTemplatesTable();
        $pageFragmentsTable = $this->getPageFragmentsTable();

        //
        $language = trim($this->params()->fromPost('language'));
        $page_id = intval($this->params()->fromPost('page'));

        //
        $page = $pagesTable->fetchById($page_id);
        if ($page == null) {
            return $this->redirect()->toRoute('backend/default', array('controller' => 'pages', 'action' => 'index'));
        }

        //
        $template = $templatesTable->fetchByName($page->template);

        $fragmentNames = array_filter(explode(',', $template['fragments']));

        foreach ($fragmentNames as $fragmentName) {
            $fragment = $pageFragmentsTable->fetchOrCreate(array(
                'page_id' => $page_id,
                'name' => $fragmentName,
            ));

            // @todo filter content!
            $fragmentContent = trim($this->params()->fromPost('fragment_' . $fragmentName));

            $pageFragmentsTable->update_i18n($fragment->id, $language, array(
                'content' => $fragmentContent,
            ));
        }

        return $this->redirect()->toRoute('backend/default', array('controller' => 'pages', 'action' => 'index'));
    }

    /**
     * @return ViewModel
     */
    public function propertiesAction()
    {
        //
        $settings = $this->getServiceLocator()->get('Settings');

        //
        $pagesTable = $this->getPagesTable();

        $page_id = intval(str_replace('page-', '', $this->params()->fromQuery('page')));

        $page = $pagesTable->fetchById($page_id);

        //
        $form = new PagePropertiesForm();

        $form->bind($page);

        $vm = new ViewModel(array(
            'page' => $page,
            'form' => $form,
            'languages' => $settings->get('frontend:languages:enabled'),
        ));

        // disable layout
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
        if ($this->pagesTable == null) {
            $serviceManager = $this->getServiceLocator();

            $this->pagesTable = $serviceManager->get('Msingi\Cms\Db\Table\Pages');
        }

        return $this->pagesTable;
    }

    /**
     * @return \Msingi\Cms\Db\Table\PageTemplates
     */
    protected function getPageTemplatesTable()
    {
        $serviceManager = $this->getServiceLocator();

        return $serviceManager->get('Msingi\Cms\Db\Table\PageTemplates');
    }

    /**
     * @return \Msingi\Cms\Db\Table\PageFragments
     */
    protected function getPageFragmentsTable()
    {
        $serviceManager = $this->getServiceLocator();

        return $serviceManager->get('Msingi\Cms\Db\Table\PageFragments');
    }

}