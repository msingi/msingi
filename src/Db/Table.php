<?php

namespace Msingi\Db;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\Stdlib\ArrayObject;
use Zend\Db\Adapter\AdapterInterface;

abstract class Table extends TableGateway implements ServiceLocatorAwareInterface
{
    protected $name;
    protected $cache;
    protected $serviceLocator;

    /**
     * Get definition of the object properties
     * @return array
     */
    abstract protected static function getDefinition();

    /**
     * @param AdapterInterface $dbAdapter
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function __construct(AdapterInterface $dbAdapter, ServiceLocatorInterface $serviceLocator)
    {
        $class = get_called_class();
        $definition = $class::getDefinition();

        $this->name = $definition['table'];
        $this->serviceLocator = $serviceLocator;

        $objectClass = $definition['object'];

        $resultSetPrototype = new ResultSet();
        if ($serviceLocator->has($objectClass)) {
            $resultSetPrototype->setArrayObjectPrototype(clone $serviceLocator->get($objectClass));
        } else {
            $resultSetPrototype->setArrayObjectPrototype(new $objectClass());
        }

        parent::__construct($this->name, $dbAdapter, null, $resultSetPrototype);
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * @param $cache
     */
    public function setCache($cache)
    {
        $this->cache = $cache;
    }

    /**
     * @return mixed
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * @param $id
     * @return array|\ArrayObject|null
     */
    public function fetchById($id)
    {
        $resultSet = $this->select(array('id' => $id));

        return $resultSet->current();
    }

    /**
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchAll()
    {
        $resultSet = $this->select();
        return $resultSet;
    }

    /**
     * @param array $where
     * @return array|\ArrayObject|null
     */
    public function fetchRow(array $where)
    {
        $rowset = $this->select($where);
        if ($rowset != null)
            return $rowset->current();

        return null;
    }

    /**
     * @param array $rowData
     * @return array|\ArrayObject|null
     */
    public function createRow(array $rowData)
    {
        $class = get_called_class();
        $definition = $class::getDefinition();

        $set = array();
        foreach ($definition['fields'] as $field => $desc) {
            if (isset($rowData[$field])) {
                $set[$field] = $rowData[$field];
            }
        }

        $this->insert($set);

        return $this->fetchRow(array('id' => $this->lastInsertValue));
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
            $this->update($set, array('id' => $object->id));
        }
    }
}