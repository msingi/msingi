<?php

namespace Msingi\Cms\Controller\Backend;

use Msingi\InputFilter\PageTagsFilter;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;

/**
 * Class MailTemplatesController
 *
 * @package Msingi\Cms\Controller\Backend
 */
class MailTemplatesController extends AuthenticatedController
{
    /** @var \Msingi\Cms\Db\Table\MailTemplates */
    protected $mailTemplatesTable;

    /**
     * @return array|ViewModel
     */
    public function indexAction()
    {
        $paginator = new Paginator($this->getPaginatorAdapter());
        $paginator->setItemCountPerPage(10);
        $paginator->setCurrentPageNumber($this->params()->fromQuery('page', 1));

        return new ViewModel(array(
            'paginator' => $paginator
        ));
    }

    /**
     * @return ViewModel
     */
    public function editAction()
    {
        $settings = $this->getServiceLocator()->get('Settings');

        $template_id = intval($this->params()->fromQuery('id'));
        $template = $this->getTemplatesTable()->fetchById($template_id);
        if ($template == null) {
            return $this->redirect()->toRoute('backend/mailtemplates');
        }

        //
        return new ViewModel(array(
            'template' => $template,
            'languages' => $settings->get('frontend:languages:enabled'),
        ));
    }

    /**
     *
     */
    public function loadAction()
    {
        $template_id = intval($this->params()->fromPost('template'));
        $language = trim($this->params()->fromPost('language'));

        $template = $this->getTemplatesTable()->fetchById($template_id);
        if ($template != null) {

            $content = $this->getTemplatesTable()->fetch_i18n($template->id, $language);

            // set page data
            $vm = new ViewModel(array(
                'language' => $language,
                'template' => $template,
                'content' => $content,
            ));

            // disable layout
            $vm->setTerminal(true);
        }

        return $vm;
    }

    /**
     * @return \Zend\Stdlib\ResponseInterface
     */
    public function saveSubjectAction()
    {
        $template_id = intval($this->params()->fromPost('pk'));
        list($meta, $language) = explode('_', $this->params()->fromPost('name'));

        //
        $content = trim(strip_tags($this->params()->fromPost('value')));

        if (in_array($meta, array('subject'))) {
            $template = $this->getTemplatesTable()->fetchById($template_id);
            if ($template != null) {
                $this->getTemplatesTable()->update_i18n($template->id, $language, array(
                    'subject' => $content
                ));
            }
        }

        return $this->getResponse();
    }

    /**
     *
     */
    public function saveContentAction()
    {
        $template_id = intval($this->params()->fromPost('template'));
        $language = $this->params()->fromPost('language');

        $filter = new PageTagsFilter();

        $content = $filter->filterTags($this->params()->fromPost('content'));

        $template = $this->getTemplatesTable()->fetchById($template_id);
        if ($template != null) {
            $this->getTemplatesTable()->update_i18n($template->id, $language, array(
                'template' => $content
            ));
        }

        return $this->getResponse();
    }

    /**
     * Get storage
     *
     */
    protected function getTemplatesTable()
    {
        if (!$this->mailTemplatesTable) {
            $sm = $this->getServiceLocator();
            $this->mailTemplatesTable = $sm->get('Msingi\Cms\Db\Table\MailTemplates');
        }

        return $this->mailTemplatesTable;
    }


    /**
     * Get query for paginator adapter
     *
     * @param $request
     * @param $filter
     * @return Select
     */
    protected function getPaginatorAdapter($filter = null)
    {
        $select = $this->getTemplatesTable()->getSql()->select();

        return new DbSelect($select, $this->getTemplatesTable()->getAdapter(), $this->getTemplatesTable()->getResultSetPrototype());;
    }
}