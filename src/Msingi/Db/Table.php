<?php

namespace Msingi\Db;

use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayObject;

abstract class Table
{
    /**
     * @var \Zend\Db\TableGateway\TableGateway
     */
    protected $tableGateway;

    /**
     * @var \Zend\ServiceManager\ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @var array
     */
    protected static $definitions = array();

    /**
     * @param TableGateway $tableGateway
     */
    public function __construct(TableGateway $tableGateway, ServiceLocatorInterface $serviceLocator)
    {
        $this->tableGateway = $tableGateway;
        $this->serviceLocator = $serviceLocator;
    }

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
        $definition = $class::getDefinition();

        if (!isset($definition['object'])) {
            throw new Exception();
        }

        $objectClass = $definition['object'];

        return new $objectClass();
    }

    /**
     * @param $id
     * @return array|\ArrayObject|null
     */
    public function fetchById($id)
    {
        $cache = $this->getCache();

        $resultSet = $this->tableGateway->select(array('id' => $id));
        return $resultSet->current();
    }

    /**
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    /**
     * @return null|\Zend\Cache\Storage\StorageInterface
     */
    protected function getCache()
    {
        return $this->serviceLocator->get('Application\Cache');
    }

    /**
     * @param $object
     * @return int
     */
    public function save($object)
    {
        $class = get_called_class();
        $definition = $class::getDefinition();

        $set = array();
        foreach ($definition['fields'] as $field => $desc) {
            if ($object->valueChanged($field)) {
                $set[$field] = $object->__get($field);
            }
        }

        if (count($set) > 0) {
            $this->tableGateway->update($set, array('id' => $object->id));
        }
    }
}