<?php

namespace Msingi\Cms\Db\Table;

use Msingi\Db\TableI18n;
use Zend\Db\Sql\Select;

/**
 * Class Pages
 * @package Msingi\Cms\Db\Table
 */
class Pages extends TableI18n
{
    /**
     * @return array
     */
    protected static function getDefinition()
    {
        return array(
            'table' => 'cms_pages',
            'object' => 'Msingi\Cms\Model\Page',
            'fields' => array(
                'parent_id' => 'integer',
                'type' => 'string',
                'path' => 'string',
                'template' => 'string'
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
    public function fetchPage($path, $parent_id = null)
    {
        $rowset = $this->select(function (Select $select) use ($path, $parent_id) {
            $select->where(array('type' => 'static', 'path' => $path, 'parent_id' => $parent_id));
        });

        return $rowset->current();
    }

    /**
     * @param $page_id
     * @param $language
     * @return array|\ArrayObject|null
     */
    public function fetchMeta($page_id, $language)
    {
        return $this->fetch_i18n($page_id, $language);
    }

    /**
     * @return mixed
     */
    public function fetchTree()
    {
        $rowset = $this->select();

        $pages = array();
        foreach ($rowset as $row) {
            $parent_id = intval($row->parent_id);
            if (!isset($pages[$parent_id])) {
                $pages[$parent_id] = array();
            }
            $pages[$parent_id][$row->id] = $row;
        }

        // update paths
        foreach ($pages as $parent_id => $subpages) {
            foreach ($subpages as $subpage) {
                // if we have children
                if (isset($pages[$subpage->id])) {
                    $path = $subpage->path;
                    foreach ($pages[$subpage->id] as $child) {
                        $child->path = trim(str_replace($path, '', $child->path), '/');
                    }
                }
            }
        }

        // attach subpages
        foreach ($pages as $parent_id => $subpages) {
            foreach ($subpages as $subpage) {
                if (isset($pages[$subpage->id])) {
                    $children = $pages[$subpage->id];
                    usort($children, array($this, 'comparePages'));
                    $subpage->children = $children;
                }
            }
        }

        return $pages[0];
    }

    /**
     * @param $a
     * @param $b
     * @return int
     */
    public function comparePages($a, $b)
    {
        $types = array('static' => 0, 'mvc' => 1);
        if ($a->type != $b->type) {
            return ($types[$a->type] > $types[$b->type]);
        }
        return strcmp($a->path, $b->path);
    }

    /**
     * @param $route
     */
    public function fetchOrCreate($route)
    {
        $rowset = $this->select(array('type' => 'mvc', 'path' => $route));

        $page = $rowset->current();

        if ($page == null) {
            $this->insert(array(
                'parent_id' => 1,
                'type' => 'mvc',
                'path' => $route,
                'template' => $route
            ));

            $rowset = $this->select(array('id' => $this->lastInsertValue));
            $page = $rowset->current();
        }

        return $page;
    }
}