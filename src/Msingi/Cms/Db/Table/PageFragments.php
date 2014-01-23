<?php

namespace Msingi\Cms\Db\Table;

use Msingi\Db\TableI18n;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;

class PageFragments extends TableI18n
{
    protected static function getDefinition()
    {
        return array(
            'table' => 'cms_page_fragments',
            'object' => 'Msingi\Cms\Model\PageFragment',
            'fields' => array(
                'page_id' => 'integer',
                'name' => 'string',
                'content' => 'string'
            )
        );
    }

    /**
     *
     *
     * @param $language
     * @return array
     */
    public function fetchFragments($page_id, $language)
    {
        $key = sprintf('page_%d_fragments_%s', $page_id, $language);

        $cache = $this->getCache();

        $fragments = $cache->getItem($key);
        if ($fragments == null) {
            $rowset = $this->select(function (Select $select) use ($page_id, $language) {
                $select->join('cms_page_fragments_i18n',
                    new Expression('cms_page_fragments_i18n.parent_id = cms_page_fragments.id AND language = ?', $language),
                    array('content'), 'left');
                $select->where(array('page_id' => $page_id));
            });

            $fragments = array();
            foreach ($rowset as $row) {
                $fragments[$row->name] = $row->content;
            }

            $cache->setItem($key, $fragments);
        }

        return $fragments;
    }

    /**
     * @param $route
     */
    public function fetchOrCreate($params)
    {
        $fragment = $this->fetchRow(array('page_id' => $params['page_id'], 'name' => $params['name']));
        if ($fragment == null) {
            $fragment = $this->createRow(array(
                'page_id' => $params['page_id'],
                'name' => $params['name'],
            ));
        }

        return $fragment;
    }

}