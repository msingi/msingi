<?php

namespace Msingi\Db;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayObject;

abstract class TableI18n extends Table
{
    protected $i18nTableName;
    protected $i18nTable;

    /**
     * @param AdapterInterface $dbAdapter
     */
    public function __construct(AdapterInterface $dbAdapter, ServiceLocatorInterface $serviceLocator)
    {
        parent::__construct($dbAdapter, $serviceLocator);

        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new ArrayObject());

        $this->i18nTableName = $this->name . '_i18n';

        $this->i18nTable = new TableGateway($this->i18nTableName, $dbAdapter, null, $resultSetPrototype);
    }

    /**
     * Fetches localized data for row or creates new one
     *
     * @param $id
     * @param $language
     * @return array|\ArrayObject|null
     */
    public function fetch_i18n($id, $language)
    {
        $rowset = $this->i18nTable->select(array('parent_id' => $id, 'language' => $language));
        $row = $rowset->current();
        if ($row == null) {
            $this->i18nTable->insert(array(
                'parent_id' => $id,
                'language' => $language
            ));
            $rowset = $this->i18nTable->select(array('id' => $this->i18nTable->lastInsertValue));
            $row = $rowset->current();
        }
        return $row;
    }

    /**
     * Updates localized data for row
     *
     * @param $id
     * @param $language
     * @param $data
     */
    public function update_i18n($id, $language, $data)
    {
        $row = $this->fetch_i18n($id, $language);

        $this->i18nTable->update($data, array('id' => $row['id']));
    }
}