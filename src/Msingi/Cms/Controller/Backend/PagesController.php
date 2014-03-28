<?php

namespace Msingi\Cms\Controller\Backend;

use Msingi\Cms\Entity\Enum\PageType;
use Msingi\Cms\Form\Backend\PagePropertiesForm;
use Msingi\Util\StripAttributes;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

/**
 * Class PagesController
 * @package Msingi\Cms\Controller\Backend
 */
class PagesController extends AuthenticatedController
{
    /** @var \Doctrine\ORM\EntityManager */
    protected $entityManager;

    /** @var array */
    protected $allowedTags = array(
        'div' => array('class'),
        'p' => array('class'),
        'img' => array('src', 'alt', 'title', 'width', 'height'),
        'a' => array('href', 'target', 'name', 'class', 'id'),
        'table' => array('width', 'border', 'cellspacing', 'cellpadding', 'class'),
        'tr' => array('colspan', 'rowspan', 'class'),
        'td' => array('colspan', 'rowspan', 'class'),
        'span' => array('class'),
        'i' => array(),
        'b' => array(),
        'u' => array(),
        'strong' => array(),
        'em' => array(),
        'br' => array(),
        'h1' => array(),
        'h2' => array(),
        'h3' => array(),
        'h4' => array(),
        'h5' => array(),
        'h6' => array(),
        'table' => array(),
        'ul' => array('class'),
        'ol' => array('class'),
        'li' => array()
    );


    /**
     * @return array|ViewModel
     */
    public function indexAction()
    {
        /** @var \Msingi\Cms\Repository\Pages $pages */
        $pages = $this->getEntityManager()->getRepository('Msingi\Cms\Entity\Page');

        return new ViewModel(array(
            //'pages_tree' => $pages->fetchTree(),
            'root' => $pages->find(1),
        ));
    }

    /**
     *
     */
    public function addAction()
    {
        $pages = $this->getEntityManager()->getRepository('Msingi\Cms\Entity\Page');

        $root = $pages->find(1);

        /** @var \Msingi\Cms\Entity\Page $page */
        $page = $this->getServiceLocator()->get('Msingi\Cms\Entity\Page');
        $page->setParent($root);
        $page->setType(PageType::PAGE_STATIC);
        $page->setPath(trim($this->params()->fromPost('path')));
        $page->setTemplate('default');

        $this->getEntityManager()->persist($page);
        $this->getEntityManager()->flush();

        return $this->redirect()->toRoute('backend/pages');
    }

    /**
     *
     */
    public function editAction()
    {
        $settings = $this->getServiceLocator()->get('Settings');

        $pages = $this->getEntityManager()->getRepository('Msingi\Cms\Entity\Page');

        $page = $pages->find(intval($this->params()->fromQuery('id')));
        if ($page == null) {
            return $this->redirect()->toRoute('backend/pages');
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
    public function deleteAction()
    {
        $page_id = intval($this->params()->fromQuery('id'));
        $pages = $this->getEntityManager()->getRepository('Msingi\Cms\Entity\Page');

        /** @var \Msingi\Cms\Entity|Page $page */
        $page = $pages->find($page_id);
        if ($page != null) {
            $this->getEntityManager()->remove($page);
            $this->getEntityManager()->flush();
        }

        return $this->redirect()->toRoute('backend/pages');
    }

    /**
     *
     */
    public function loadAction()
    {
        $page_id = intval($this->params()->fromPost('page'));
        $language = trim($this->params()->fromPost('language'));

        $pages = $this->getEntityManager()->getRepository('Msingi\Cms\Entity\Page');
        $templates = $this->getEntityManager()->getRepository('Msingi\Cms\Entity\PageTemplate');
        $page_fragments = $this->getEntityManager()->getRepository('Msingi\Cms\Entity\PageFragment');
        $page_fragments_i18n = $this->getEntityManager()->getRepository('Msingi\Cms\Entity\PageFragmentI18n');

        /** @var \Msingi\Cms\Entity\Page $page */
        $page = $pages->find($page_id);
        if ($page != null) {

            $meta = $this->getEntityManager()->getRepository('Msingi\Cms\Entity\PageI18n')->fetchOrCreate($page, $language);

            /** @var \Msingi\Cms\Entity\PageTemplate $pageTemplate */
            $pageTemplate = $templates->findOneBy(array('name' => $page->getTemplate()));

            $fragments = array();
            if ($pageTemplate != null) {
                foreach (explode(',', $pageTemplate->getFragments()) as $fragment) {
                    $fragment = trim($fragment);
                    if ($fragment == '')
                        continue;

                    $page_fragment = $page_fragments->fetchOrCreate($page, $fragment);

                    $content = $page_fragments_i18n->fetchOrCreate($page_fragment, $language);

                    $fragments[$fragment] = $content->getContent();
                }
            }

            // set page data
            $viewModel = new ViewModel(array(
                'language' => $language,
                'page' => $page,
                'meta' => $meta,
                'fragments' => $fragments,
            ));

            // disable layout
            $viewModel->setTerminal(true);

            // render the template
            $template = 'backend/pages/templates/' . preg_replace('/[^a-z0-9_]+/', '_', $page->getTemplate());
            $resolver = $this->getEvent()->getApplication()->getServiceManager()->get('Zend\View\Resolver\TemplatePathStack');
            $template = $resolver->resolve($template) ? $template : 'backend/pages/templates/default';
            $viewModel->setTemplate($template);
        }

        return $viewModel;
    }

    /**
     * @return \Zend\Stdlib\ResponseInterface
     */
    public function saveMetaAction()
    {
        $pages = $this->getEntityManager()->getRepository('Msingi\Cms\Entity\Page');
        /** @var \Msingi\Cms\Entity\Page $page */
        $page = $pages->find($this->params()->fromPost('pk'));

        list($meta_name, $language) = explode('_', $this->params()->fromPost('name'));

        //
        $content = trim(strip_tags($this->params()->fromPost('value')));

        /** @var \Msingi\Cms\Entity\PageI18n $meta */
        $meta = $this->getEntityManager()->getRepository('Msingi\Cms\Entity\PageI18n')->fetchOrCreate($page, $language);

        switch ($meta_name) {
            case 'title':
                $meta->setTitle($content);
                break;
            case 'keywords':
                $meta->setKeywords($content);
                break;
            case 'description':
                $meta->setDescription($content);
                break;
        }

        $this->getEntityManager()->flush();

        return $this->getResponse();
    }

    /**
     * @return \Zend\Stdlib\ResponseInterface
     */
    public function saveFragmentAction()
    {
        $pages = $this->getEntityManager()->getRepository('Msingi\Cms\Entity\Page');
        $page_fragments = $this->getEntityManager()->getRepository('Msingi\Cms\Entity\PageFragment');
        $page_fragments_i18n = $this->getEntityManager()->getRepository('Msingi\Cms\Entity\PageFragmentI18n');

        /** @var \Msingi\Cms\Entity\Page $page */
        $page = $pages->find($this->params()->fromPost('page'));

        $language = trim($this->params()->fromPost('language'));
        $fragment = trim(str_replace('fragment_', '', $this->params()->fromPost('fragment')));

        if ($page != null) {

            $page_fragment = $page_fragments->fetchOrCreate($page, $fragment);

            $page_fragment_i18n = $page_fragments_i18n->fetchOrCreate($page_fragment, $language);

            $content = $this->filterContent($this->params()->fromPost('content'));

            $page_fragment_i18n->setContent($content);

            $this->getEntityManager()->flush();
        }

        return $this->getResponse();
    }

    /**
     * @param string $text
     * @return string
     */
    protected function filterContent($text)
    {
        // filter tags
        $tags = '<' . implode('><', array_keys($this->allowedTags)) . '>';
        $text = strip_tags($text, $tags);

        // filter attributes
        $sa = new StripAttributes();
        $sa->exceptions = $this->allowedTags;
        $text = $sa->strip($text);

        return $text;
    }

    /**
     * @return ViewModel
     */
    public function propertiesAction()
    {
        $page_id = intval(str_replace('page-', '', $this->params()->fromQuery('page')));

        $pages = $this->getEntityManager()->getRepository('Msingi\Cms\Entity\Page');
        $page_templates = $this->getEntityManager()->getRepository('Msingi\Cms\Entity\PageTemplate');

        /** @var \Msingi\Cms\Entity\Page $page */
        $page = $pages->find($page_id);
        if ($page != null) {
            //
            $form = new PagePropertiesForm();
            $form->get('id')->setValue($page->getId());
            $form->get('path')->setValue($page->getPath());
            $form->get('template')->setValueOptions($page_templates->fetchOptions());

            $vm = new ViewModel(array(
                'page' => $page,
                'form' => $form,
            ));

            // disable layout
            $vm->setTerminal(true);
        }


        return $vm;
    }

    /**
     *
     * @return ViewModel
     */
    public function savepropsAction()
    {
        $page_id = intval($this->params()->fromPost('id'));

        $pages = $this->getEntityManager()->getRepository('Msingi\Cms\Entity\Page');
        /** @var \Msingi\Cms\Entity\Page $page */
        $page = $pages->find($page_id);

        if ($page != null) {
            $page->setPath(trim(strip_tags($this->params()->fromPost('path'))));
            $page->setTemplate(trim(strip_tags($this->params()->fromPost('template'))));

            $this->getEntityManager()->flush();
        }

        return $this->redirect()->toRoute('backend/pages');
    }

    /**
     * @return JsonModel
     */
    public function setParentAction()
    {
        $pages = $this->getEntityManager()->getRepository('Msingi\Cms\Entity\Page');

        $parent_id = intval(str_replace('page-', '', $this->params()->fromQuery('parent')));
        $child_id = intval(str_replace('page-', '', $this->params()->fromQuery('child')));

        /** @var \Msingi\Cms\Entity\Page $page */
        $parent = $pages->find($parent_id);
        /** @var \Msingi\Cms\Entity\Page $child */
        $child = $pages->find($child_id);

        if ($parent != null && $child != null) {
            $child->setParent($parent);

            $this->getEntityManager()->flush();

            return new JsonModel(array('success' => true, 'parent_id' => $parent_id));
        }

        return new JsonModel(array('success' => false));
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        if (null === $this->entityManager) {
            $this->entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }

        return $this->entityManager;
    }

}