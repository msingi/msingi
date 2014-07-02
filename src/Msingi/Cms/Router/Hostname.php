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

    /**
     * @param array $hostnames
     */
    public function __construct(array $hostnames = array())
    {
        $this->hostnames = array_merge(array(), $hostnames);
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
        return '';
    }

    /**
     * Get a list of parameters used while assembling.
     *
     * @return array
     */
    public function getAssembledParams()
    {
        return array();
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

        return new static($options['hostnames']);
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
