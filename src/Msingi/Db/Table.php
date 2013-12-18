<?php

namespace Msingi\Db;

use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\ServiceLocatorInterface;

class Table
{
    protected $tableGateway;
    protected $serviceLocator;

    /**
     * @param TableGateway $tableGateway
     */
    public function __construct(TableGateway $tableGateway, ServiceLocatorInterface $serviceLocator)
    {
        $this->tableGateway = $tableGateway;
        $this->serviceLocator = $serviceLocator;
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
}