<?php

namespace Msingi\Cms\Model;

class Settings
{
    protected $tableSettings;

    public static function get($name, $default = null)
    {

    }

    public static function set($name, $value)
    {

    }

    /**
     * @return mixed
     */
    protected function getSettingsTable()
    {
        if ($this->tableSettings == null) {
            $serviceManager = $this->getServiceLocator();

            $this->tableSettings = $serviceManager->get('Msingi\Cms\Db\Table\Settings');
        }

        return $this->tableSettings;
    }

}