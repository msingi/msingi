<?php

namespace Msingi\Cms\View\Helper;

use Zend\Mvc\MvcEvent;
use Zend\View\Helper\AbstractHelper;

class PageFragment extends AbstractHelper
{
    protected $page;
    protected $fragments;
    protected $event;

    public function __construct(MvcEvent $event)
    {
        $this->event = $event;
        $this->page = $event->getRouteMatch()->getParam('cms_page');
    }

    /**
     * @param $name
     * @return string
     */
    public function __invoke($name)
    {
        if ($this->fragments == null) {

            $serviceManager = $this->event->getApplication()->getServiceManager();

            $pageFragmentsTable = $serviceManager->get('Msingi\Cms\Db\Table\PageFragments');

            $translator = $serviceManager->get('Translator');

            $locale = $translator->getLocale();

            $this->fragments = $pageFragmentsTable->fetchFragments($this->page->id, \Locale::getPrimaryLanguage($locale));
        }

        return isset($this->fragments[$name]) ? $this->fragments[$name]->content : '';
    }
}