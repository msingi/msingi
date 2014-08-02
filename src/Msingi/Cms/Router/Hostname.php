<?php

namespace Msingi\Cms\Router;

use Zend\Mvc\Router\Exception\InvalidArgumentException;
use Zend\Mvc\Router\Http\RouteInterface;
use Zend\Mvc\Router\Http\RouteMatch;
use Zend\Stdlib\ArrayUtils;
use Zend\Stdlib\RequestInterface as Request;

/**
 * Class Hostname - hostname router with multiple predefined hostnames
 *
 * @package Msingi\Cms\Router
 */
class Hostname implements RouteInterface
{
    /** @var array */
    protected $hostnames = array();

    /** @var string */
    protected $default = '';

    /** @var array */
    protected $assembledParams = array();

    /**
     * @param array $hostnames
     */
    public function __construct(array $hostnames = array(), $default = '')
    {
        $this->hostnames = array_merge(array(), $hostnames);
        $this->default = $default;
    }

    /**
     * Assemble the route.
     *
     * @param  array $params
     * @param  array $options
     * @return mixed
     */
    public function assemble(array $params = array(), array $options = array())
    {
        $this->assembledParams = array();

        if (isset($options['uri'])) {
            $options['uri']->setHost($this->default);
        }

        // A hostname does not contribute to the path, thus nothing is returned.
        return '';
    }

    /**
     * Get a list of parameters used while assembling.
     *
     * @return array
     */
    public function getAssembledParams()
    {
        return $this->assembledParams;
    }

    /**
     * Create a new route with given options.
     *
     * @param  array|\Traversable $options
     * @return Frontend
     */
    public static function factory($options = array())
    {
        if ($options instanceof \Traversable) {
            $options = ArrayUtils::iteratorToArray($options);
        } elseif (!is_array($options)) {
            throw new InvalidArgumentException(__METHOD__ . ' expects an array or Traversable set of options');
        }

        if (!isset($options['hostnames'])) {
            $options['hostnames'] = array();
        }

        if (!isset($options['default'])) {
            $options['default'] = count($options['hostnames']) > 0 ? $options['hostnames'][0] : '';
        }

        return new static($options['hostnames'], $options['default']);
    }

    /**
     * Match a given request.
     *
     * @param  Request $request
     * @return RouteMatch|null
     */
    public function match(Request $request)
    {
        if (!method_exists($request, 'getUri')) {
            return null;
        }

        $uri = $request->getUri();
        $host = $uri->getHost();

        if (!in_array($host, $this->hostnames)) {
            return null;
        }

        return new RouteMatch(array());
    }
}
