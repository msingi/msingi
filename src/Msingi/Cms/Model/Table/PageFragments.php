<?php

namespace Msingi\Cms\Model\Table;

use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;

class PageFragments
{
    protected $tableGateway;

    /**
     * @param TableGateway $tableGateway
     */
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    /**
     *
     *
     * @param $name
     * @param $language
     * @return array
     */
    public function fetchFragment($page_id, $name, $language)
    {
        $rowset = $this->tableGateway->select(function (Select $select) use ($page_id, $name, $language) {
            $select->join('cms_page_fragments_i18n', 'cms_page_fragments_i18n.parent_id = cms_page_fragments.id', array('content'), 'left');
            $select->where(array('name' => $name, 'page_id' => $page_id, 'language' => $language));
        });

        return $rowset->current();
    }
}