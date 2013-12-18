<?php

namespace Msingi\Db;

use Zend\Stdlib\ArrayObject;

abstract class TableRow
{
    protected $data = array();

    protected static $definitions = array();

    /**
     * Get definition of the object properties
     * @return array
     */
    abstract protected static function getDefinition();

    /**
     *
     */
    public static function getPrototype()
    {
        $class = get_called_class();

        TableRow::$definitions[$class] = array_merge(array('id' => 'integer'), $class::getDefinition());

        return new $class();
    }

    /**
     * @param $name
     * @param $value
     * @return null
     */
    public function __set($name, $value)
    {
        $class = get_called_class();
        $definition = TableRow::$definitions[$class];

        if (!isset($definition[$name])) {
            trigger_error(
                'Undefined property via __get(): ' . $name . ' in ' . $trace[0]['file'] . ' on line ' . $trace[0]['line'], E_USER_NOTICE);
            return null;
        }

        $this->data[$name] = $value;
    }

    /**
     * @param $name
     * @return null
     */
    public function __get($name)
    {
        $class = get_called_class();
        $definition = TableRow::$definitions[$class];

        if (!isset($definition[$name])) {
            trigger_error(
                'Undefined property via __get(): ' . $name . ' in ' . $trace[0]['file'] . ' on line ' . $trace[0]['line'], E_USER_NOTICE);
            return null;
        }

        return isset($this->data[$name]) ? $this->data[$name] : null;
    }

    /**
     * @param array $data
     */
    public function exchangeArray($data)
    {
        $class = get_called_class();
        $definition = TableRow::$definitions[$class];

        foreach ($definition as $field => $desc) {
            $this->data[$field] = (isset($data[$field])) ? $data[$field] : null;
        }
    }

}