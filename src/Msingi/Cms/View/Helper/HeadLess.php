<?php

namespace Msingi\Cms\View\Helper;

/**
 * Class HeadLess
 *
 * Use less.js compiler for stylesheets in development environment
 *
 * @package Msingi\Cms\View\Helper
 */
class HeadLess extends \Zend\View\Helper\Placeholder\Container\AbstractStandalone
{
    /** @var string */
    protected $less_js_compiler;

    /** @var string */
    protected $environment;

    /** @var bool */
    protected $debug = false;

    /** @var bool */
    protected $async = false;

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->environment = getenv('APPLICATION_ENV') ? : 'production';
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
     * @param string $value
     */
    public function setLessCompiler($value)
    {
        $this->less_js_compiler = $value;
    }

    /**
     * @param bool $debug
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;
    }

    /**
     * @return bool
     */
    public function isProduction()
    {
        return $this->environment == 'production';
    }

    /**
     * @param string $url
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
     * @param int|null $indent
     * @return string
     */
    public function toString($indent = null)
    {
        $indent = (null !== $indent)
            ? $this->getWhitespace($indent)
            : $this->getIndent();

        $items = array();

        $this->getContainer()->ksort();
        foreach ($this as $item) {
            $items[] = $this->itemToString($item, $indent);
        }

        // add less.js file
        if (count($items) > 0 && !$this->isProduction() && $this->less_js_compiler != '') {
            $view = $this->getView();

            $script = array();

            if ($this->debug) {
                $script[] = 'env: "development"';
            }

            if ($this->async) {
                $script[] = 'async: true';
            }

            if (!empty($script)) {
                $view->headScript()->appendScript('less = {' . implode(',', $script) . '};');
            }

            $view->headScript()->appendFile($this->less_js_compiler);
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

        if($this->isProduction()) {
            // change .less to .css for production environment
            $ext = 'css';
            $rel = 'stylesheet';
            $url .= '.' . $ext;
        }
        else {
            // change .less to .css for production environment
            $ext = 'less';
            $rel = 'stylesheet/less';
            $url .= '.' . $ext;

            if ($item->forceRefresh) {
                $url .= '?r=' . time();
            }
        }

        return '<link href="' . $url . '" rel="' . $rel . '" type="text/css">';
    }

    /**
     * @param bool $async
     */
    public function setAsync($async)
    {
        $this->async = $async;
    }

    /**
     * @return bool
     */
    public function isAsync()
    {
        return $this->async;
    }
}
