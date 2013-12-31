<?php

namespace Msingi\Cms\Db\Table;

use Msingi\Db\Table;
use Zend\Db\Sql\Select;

class Settings extends Table
{
    protected static function getDefinition()
    {
        return array(
            'table' => 'cms_settings',
            'object' => '\ArrayObject',
            'fields' => array(
                'name' => 'string',
                'value' => 'string'
            )
        );
    }

    /**
     * @param $name
     * @return mixed
     */
    public function fetch($name)
    {
        $key = $this->getCacheKey($name);

        $cache = $this->getCache();

        $setting = $cache->getItem($key);
        if (!$setting) {
            $rowset = $this->select(array(
                'name' => $name
            ));

            $row = $rowset->current();
            if ($row != null) {
                $setting = $row['value'];

                $cache->setItem($key, $setting);
            }
        }

        return $setting;
    }

    /**
     * @param $name
     * @param $value
     */
    public function set($name, $value)
    {
        $rowset = $this->select(array(
            'name' => $name
        ));

        $row = $rowset->current();
        if ($row == null) {
            $this->insert(array(
                'name' => $name,
                'value' => $value
            ));
        } else {
            $this->update(array(
                    'value' => $value
                ),
                array(
                    'id' => $row['id']
                )
            );
        }

        $key = $this->getCacheKey($name);

        $cache = $this->getCache();

        $cache->setItem($key, $value);
    }

    /**
     * @param $name
     * @return string
     */
    protected function getCacheKey($name)
    {
        return sprintf('setting_%s', \Msingi\Cms\Model\Settings::formatValueName($name));
    }
}