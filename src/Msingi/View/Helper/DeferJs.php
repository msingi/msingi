<?php

namespace Msingi\View\Helper;

class DeferJs extends \Zend\View\Helper\Placeholder\Container\AbstractStandalone
{
    protected $_head_js;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->setSeparator(PHP_EOL);
    }

    /**
     *
     */
    public function __invoke()
    {
        return $this;
    }

    /**
     * @param $value
     */
    public function setHeadJs($value)
    {
        $this->_head_js = $value;
    }

    /**
     *
     * @param type $file
     */
    public function appendFile($file)
    {
        $value = (object)array(
            'file' => $file,
        );

        return $this->getContainer()->append($value);
    }

    /**
     *
     */
    public function updateHeadScript()
    {
        $items = array();
        $this->getContainer()->ksort();
        foreach ($this as $item)
        {
            $items[] = sprintf('"%s"', $item->file);
        }

        $script = 'head.js(' . implode(', ', $items) . ')';

        $this->getView()->headScript()->appendFile($this->_head_js);

        $this->getView()->headScript()->appendScript($script);
    }

    /**
     *
     * @return string
     */
    public function toString($indent = null)
    {
        return '';
    }
}