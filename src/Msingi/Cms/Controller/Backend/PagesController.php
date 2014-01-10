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

    public function addAction()
    {

    }

    /**
     *
     */
    public function editAction()
    {
        $settings = $this->getServiceLocator()->get('Settings');

        $page_id = intval($this->params()->fromQuery('id'));
        $page = $this->getPagesTable()->fetchById($page_id);
        if ($page == null) {
            return $this->redirect()->toRoute('backend/default', array('controller' => 'pages', 'action' => 'index'));
        }

        //
        return new ViewModel(array(
            'page' => $page,
            'languages' => $settings->get('frontend:languages:enabled'),
        ));
    }

    /**
     *
     */
    public function loadAction()
    {
        $page_id = intval($this->params()->fromPost('page'));
        $language = trim($this->params()->fromPost('language'));

        $page = $this->getPagesTable()->fetchById($page_id);
        if ($page != null) {

            $contents = $this->getPageFragmentsTable()->fetchFragments($page->id, $language);

            $pageTemplate = $this->getPageTemplatesTable()->fetchByName($page->template);

            $fragments = array();
            foreach (explode(',', $pageTemplate['fragments']) as $fragment) {
                $fragment = trim($fragment);
                if ($fragment != '') {
                    $fragments[$fragment] = isset($contents[$fragment]) ? $contents[$fragment] : '';
                }
            }

            // set page data
            $vm = new ViewModel(array(
                'language' => $language,
                'page' => $page,
                'meta' => $this->getPagesTable()->fetchMeta($page->id, $language),
                'fragments' => $fragments,
            ));

            // disable layout
            $vm->setTerminal(true);

            // render the template
            $template = 'backend/pages/templates/' . preg_replace('/[^a-z0-9_]+/', '_', $page->template);
            $resolver = $this->getEvent()->getApplication()->getServiceManager()->get('Zend\View\Resolver\TemplatePathStack');
            $template = $resolver->resolve($template) ? $template : 'backend/pages/templates/default';
            $vm->setTemplate($template);
        }

        return $vm;
    }

    /**
     * @return \Zend\Stdlib\ResponseInterface
     */
    public function saveMetaAction()
    {
        $page_id = $this->params()->fromPost('pk');
        list($meta, $language) = explode('_', $this->params()->fromPost('name'));
        $content = $this->params()->fromPost('value');

        if (in_array($meta, array('title', 'keywords', 'description'))) {
            $page = $this->getPagesTable()->fetchById($page_id);
            if ($page != null) {
                $this->getPagesTable()->update_i18n($page->id, $language, array(
                    $meta => $content
                ));
            }
        }

        return $this->getResponse();
    }

    /**
     * @return \Zend\Stdlib\ResponseInterface
     */
    public function saveFragmentAction()
    {
        $page_id = $this->params()->fromPost('page');
        $language = $this->params()->fromPost('language');
        $fragment = trim(str_replace('fragment_', '', $this->params()->fromPost('fragment')));
        // @todo filter content tags!!!
        $content = $this->params()->fromPost('content');

        $page = $this->getPagesTable()->fetchById($page_id);
        if ($page != null) {

            $fragment = $this->getPageFragmentsTable()->fetchOrCreate(array(
                'page_id' => $page->id,
                'name' => $fragment
            ));

            $this->getPageFragmentsTable()->update_i18n($fragment->id, $language, array(
                'content' => $content
            ));
        }

        return $this->getResponse();
    }

    /**
     * @return ViewModel
     */
    public function propertiesAction()
    {
        //
        $settings = $this->getServiceLocator()->get('Settings');

        $page_id = intval(str_replace('page-', '', $this->params()->fromQuery('page')));
        $page = $this->getPagesTable()->fetchById($page_id);
        if ($page == null) {

        }

        //
        $form = new PagePropertiesForm();
        $form->get('template')->setValueOptions($this->getPageTemplatesTable()->fetchOptions());

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
     *
     * @return ViewModel
     */
    public function savepropsAction()
    {
        $page_id = $this->params()->fromPost('id');
        $page = $this->getPagesTable()->fetchById($page_id);
        if ($page != null) {
            $page->path = $this->params()->fromPost('path');
            $page->template = $this->params()->fromPost('template');

            $this->getPagesTable()->save($page);
        }

        return $this->redirect()->toRoute('backend/default', array('controller' => 'pages', 'action' => 'index'));
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