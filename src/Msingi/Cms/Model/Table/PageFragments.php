<?php

namespace Msingi\Cms\Model\Table;

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
     * @param $name
     * @param $language
     * @return array
     */
    public function fetchFragment($page_id, $name, $language)
    {
        $key = sprintf('page_fragment_%d_%s_%s', $page_id, $name, $language);

        $cache = $this->getCache();

        $fragment = $cache->getItem($key);
        if ($fragment == null) {
            $rowset = $this->tableGateway->select(function (Select $select) use ($page_id, $name, $language) {
                $select->join('cms_page_fragments_i18n', 'cms_page_fragments_i18n.parent_id = cms_page_fragments.id', array('content'), 'left');
                $select->where(array('name' => $name, 'page_id' => $page_id, 'language' => $language));
            });

            $fragment = $rowset->current();

            $cache->setItem($key, $fragment);
        }

        return $fragment;
    }
}