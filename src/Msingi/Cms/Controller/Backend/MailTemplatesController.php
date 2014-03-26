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
class MailTemplatesController extends AbstractEntitiesController
{
    protected $entityClass = 'Msingi\Cms\Entity\MailTemplate';
    protected $indexRoute = 'backend/mailtemplates';

    /**
     * @return ViewModel
     */
    public function editAction()
    {
        $settings = $this->getServiceLocator()->get('Settings');

        $template_id = intval($this->params()->fromQuery('id'));
        $template = $this->getRepository()->find($template_id);
        if ($template == null) {
            return $this->redirect()->toRoute($this->getIndexRoute());
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

        /** @var \Msingi\Cms\Entity\MailTemplate $template */
        $template = $this->getRepository()->find($template_id);
        if ($template != null) {

            $i18n_repository = $this->getEntityManager()->getRepository('Msingi\Cms\Entity\MailTemplateI18n');

            $content = $i18n_repository->fetchOrCreate($template, $language);

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
            $template = $this->getRepository()->find($template_id);
            if ($template != null) {

                $i18n_repository = $this->getEntityManager()->getRepository('Msingi\Cms\Entity\MailTemplateI18n');

                $i18n = $i18n_repository->fetchOrCreate($template, $language);

                $i18n->setSubject($content);

                $this->getEntityManager()->persist($i18n);
                $this->getEntityManager()->flush();
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

        $template = $this->getRepository()->find($template_id);
        if ($template != null) {
            $i18n_repository = $this->getEntityManager()->getRepository('Msingi\Cms\Entity\MailTemplateI18n');

            $i18n = $i18n_repository->fetchOrCreate($template, $language);

            $i18n->setTemplate($content);

            $this->getEntityManager()->persist($i18n);
            $this->getEntityManager()->flush();
        }

        return $this->getResponse();
    }
}