<?php

namespace Msingi\Cms\View\Helper;

use Zend\Mvc\MvcEvent;
use Zend\View\Helper\AbstractHelper;

/**
 * Class PageMeta
 * @package Msingi\Cms\View\Helper
 */
class PageMeta extends AbstractHelper
{
    protected $page;
    protected $meta;
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
        if ($this->meta == null) {

            $serviceManager = $this->event->getApplication()->getServiceManager();

            $pagesTable = $serviceManager->get('Msingi\Cms\Db\Table\Pages');

            $translator = $serviceManager->get('Translator');

            $locale = $translator->getLocale();

            $this->meta = $pagesTable->fetchMeta($this->page->id, \Locale::getPrimaryLanguage($locale));
        }

        return isset($this->meta[$name]) ? $this->meta[$name] : '';
    }
}