<?php

namespace Msingi\Cms\Db\Table;

use Msingi\Db\Table;
use Zend\Db\Sql\Select;

class PageFragments extends Table
{
    protected static function getDefinition()
    {
        return array(
            'table' => 'cms_page_fragments',
            'object' => 'Msingi\Cms\Model\PageFragment',
            'fields' => array(
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
            $rowset = $this->tableGateway->select(function (Select $select) use ($page_id, $language) {
                $select->join('cms_page_fragments_i18n', 'cms_page_fragments_i18n.parent_id = cms_page_fragments.id', array('content'), 'left');
                $select->where(array('page_id' => $page_id, 'language' => $language));
            });

            $fragments = array();
            foreach ($rowset as $row) {
                $fragments[$row->name] = $row;
            }

            $cache->setItem($key, $fragments);
        }

        return $fragments;
    }
}