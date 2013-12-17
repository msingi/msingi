<?php

namespace Msingi\Cms\View\Helper;

use Zend\Mvc\MvcEvent;
use Zend\View\Helper\AbstractHelper;

class PageFragment extends AbstractHelper
{
    protected $page;
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
        $serviceManager = $this->event->getApplication()->getServiceManager();

        $pageFragmentsTable = $serviceManager->get('Msingi\Cms\Model\Table\PageFragments');

        $translator = $serviceManager->get('Translator');

        $locale = $translator->getLocale();

        $fragment = $pageFragmentsTable->fetchFragment($this->page->id, $name, \Locale::getPrimaryLanguage($locale));
        if ($fragment != null) {
            return $fragment->content;
        }

        return '';
    }
}