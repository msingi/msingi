<?php

namespace Msingi\Cms\Db\Table;

use Msingi\Db\TableI18n;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;

class Menu extends TableI18n
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
     * @param $useCache
     * @return array
     */
    public function fetchMenu($name, $language, $useCache = true)
    {
        $cache = $this->getCache();

        if ($useCache && $cache != null) {
            $key = sprintf('menu_%s_%s', $name, $language);
            $menu = $cache->getItem($key);
            if ($menu == null) {
                $menu = $this->fetchMenuRaw($name, $language);
                $cache->setItem($key, $menu);
            }
        } else {
            $menu = $this->fetchMenuRaw($name, $language);
        }

        return $menu;
    }

    /**
     * @param $menu
     * @param $route
     * @param $params
     */
    public function addPage($menu, $route, $params)
    {
        $select = $this->getSql()->select();
        $select->columns(array(
            'max' => new Expression('MAX(`order`)')
        ));
        $select->where(array('menu' => $menu));

        $rowset = $this->selectWith($select);
        $row = $rowset->current();

        //
        $this->insert(array(
            'parent_id' => null,
            'menu' => $menu,
            'order' => $row->max + 1,
            'route' => $route,
            'params' => $params
        ));
    }

    /**
     * @param $item
     */
    public function deleteItem($item)
    {
        $this->delete(array('id' => $item));
    }

    /**
     * @param $item
     * @param $language
     * @param $label
     */
    public function setLabel($item, $language, $label)
    {
        $this->update_i18n($item, $language, array('label' => $label));
    }

    /**
     * @param $name
     * @param $language
     * @return array
     */
    protected function fetchMenuRaw($name, $language)
    {
        $rowset = $this->select(function (Select $select) use ($name, $language) {
            $select->join('cms_menu_i18n',
                new Expression('cms_menu_i18n.parent_id = cms_menu.id AND language = ?', $language),
                array('label'), 'left');
            $select->where(array('menu' => $name));
            $select->order('cms_menu.parent_id')->order('order');
        });

        $pages = array();
        foreach ($rowset as $row) {
            $page = array(
                'id' => $row->id,
                'label' => $row->label,
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

        if (!empty($pages) > 0) {
            $menu = $pages[0];
            return $menu;
        } else {
            $menu = array();
            return $menu;
        }
    }
}