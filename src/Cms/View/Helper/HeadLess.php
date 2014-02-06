<?php

namespace Msingi\Cms\View\Helper;

class HeadLess extends \Zend\View\Helper\Placeholder\Container\AbstractStandalone
{
    protected $_less_js;
    protected $_environment;
    protected $_debug = false;
    protected $async = false;

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->_environment = getenv('APPLICATION_ENV');
        $this->setSeparator(PHP_EOL);
    }

    /**
     * @return $this
     */
    public function __invoke()
    {
        return $this;
    }

    /**
     * @param $value
     */
    public function setLessCompiler($value)
    {
        $this->_less_js = $value;
    }

    /**
     * @param $debug
     */
    public function setDebug($debug)
    {
        $this->_debug = $debug;
    }

    /**
     * @return bool
     */
    public function isProduction()
    {
        return $this->_environment == 'production';
    }

    /**
     * @param $url
     * @param bool $forceRefresh
     * @return \Zend\View\Helper\Placeholder\Container\AbstractContainer
     */
    public function appendStylesheet($url, $forceRefresh = true)
    {
        $value = (object)array(
            'url' => $url,
            'forceRefresh' => $forceRefresh
        );

        return $this->getContainer()->append($value);
    }

    /**
     * @param null $indent
     * @return string
     */
    public function toString($indent = null)
    {
        $indent = (null !== $indent)
            ? $this->getWhitespace($indent)
            : $this->getIndent();

        $items = array();

        // add less.js file
        if (getenv('APPLICATION_ENV') != 'production' && $this->_less_js != '') {
            $view = $this->getView();

            $script = array();
            if ($this->_debug) {
                $script[] = 'env:"development"';
            }
            if ($this->async) {
                $script[] = 'async:true';
            }
            if (!empty($script)) {
                $view->headScript()->appendScript('less={' . implode(',', $script) . '};');
            }

            $view->headScript()->appendFile($this->_less_js);
        }

        $this->getContainer()->ksort();
        foreach ($this as $item) {
            $items[] = $this->itemToString($item, $indent);
        }

        return $indent . implode($this->getSeparator() . $indent, $items) . $this->getSeparator();
    }

    /**
     * @param $item
     * @param $indent
     * @return string
     */
    public function itemToString($item, $indent)
    {
        $url = str_replace('.less', '', $item->url);

        // change .less to .css for production environment
        $ext = $this->isProduction() ? 'css' : 'less';
        $rel = $this->isProduction() ? 'stylesheet' : 'stylesheet/less';

        $url .= '.' . $ext;

        if ($item->forceRefresh) {
            $url .= '?r=' . time();
        }

        return '<link href="' . $url . '" rel="' . $rel . '" type="text/css">';
    }

    /**
     * @param boolean $async
     */
    public function setAsync($async)
    {
        $this->async = $async;
    }

    /**
     * @return boolean
     */
    public function isAsync()
    {
        return $this->async;
    }
}