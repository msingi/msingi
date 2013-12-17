<?php

namespace Msingi\Cms\Model\Table;

use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;

class Menu
{
    protected $_tableGateway;

    /**
     * @param TableGateway $tableGateway
     */
    public function __construct(TableGateway $tableGateway)
    {
        $this->_tableGateway = $tableGateway;
    }

    /**
     * @param $name
     * @param $language
     * @return array
     */
    public function fetchMenu($name, $language, $parent_id = null)
    {
        $rowset = $this->_tableGateway->select(function (Select $select) use ($name, $parent_id, $language) {
            $select->join('cms_menu_i18n', 'cms_menu_i18n.parent_id = cms_menu.id', array('label'), 'left');
            $select->where(array('menu' => $name, 'cms_menu.parent_id' => $parent_id, 'language' => $language));
            $select->order('order');
        });

        $pages = array();

        foreach ($rowset as $row) {
            $page = array(
                'label' => $row->label
            );

            if ($row->route != '') {
                $page['route'] = $row->route;
            }

            $subpages = $this->fetchMenu($name, $language, $row->id);
            if (count($subpages) > 0) {
                $page['pages'] = $subpages;
            }

            $pages[] = $page;
        }

        return $pages;
    }
}