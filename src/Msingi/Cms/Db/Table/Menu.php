<?php

namespace Msingi\Cms\Db\Table;

use Msingi\Db\Table;
use Zend\Db\Sql\Select;

class Menu extends Table
{
    protected static function getDefinition()
    {
        return array(
            'table' => 'cms_menu',
            'object' => 'Msingi\Cms\Model\Menu',
            'fields' => array(
                'route' => 'string',
                'params' => 'string',
                'label' => 'string'
            )
        );
    }

    /**
     *
     *
     * @param $name
     * @param $language
     * @return array
     */
    public function fetchMenu($name, $language)
    {
        $key = sprintf('menu_%s_%s', $name, $language);

        $cache = $this->getCache();

        $menu = $cache->getItem($key);

        if ($menu == null) {

            $rowset = $this->tableGateway->select(function (Select $select) use ($name, $language) {
                $select->join('cms_menu_i18n', 'cms_menu_i18n.parent_id = cms_menu.id', array('label'), 'left');
                $select->where(array('menu' => $name, 'language' => $language));
                $select->order('cms_menu.parent_id')->order('order');
            });

            $pages = array();
            foreach ($rowset as $row) {
                $page = array(
                    'label' => $row->label
                );

                if ($row->route != '') {
                    $page['route'] = $row->route;
                    // get route parameters
                    $params = array();
                    parse_str(trim($row->params), $params);
                    $page['params'] = $params;
                }

                $parent_id = intval($row->parent_id);

                if (!isset($pages[$parent_id])) {
                    $pages[$parent_id] = array();
                }

                $pages[$parent_id][$row->id] = $page;
            }

            // attach subpages
            foreach ($pages as $parent_id => $subpages) {
                if ($parent_id == 0) continue;

                $pages[0][$parent_id]['pages'] = $subpages;
            }


            $menu = $pages[0];

            $cache->setItem($key, $menu     );
        }

        return $menu;
    }
}