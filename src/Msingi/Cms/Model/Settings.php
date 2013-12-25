<?php

namespace Msingi\Cms\Model;

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;

class Settings implements ServiceManagerAwareInterface
{
    protected $serviceManager;
    protected $tableSettings;

    /**
     * @param $name
     * @param null $default
     * @return mixed
     */
    public function get($name, $default = null)
    {
        $settingsTable = $this->getSettingsTable();

        $rowValue = $settingsTable->fetch($name);
        if ($rowValue != null) {
            // try to unserialize value
            $value = @unserialize($rowValue);
            $setting = ($value !== false) ? $value : $rowValue;
        } else {
            $setting = $default;
        }

        return $setting;
    }

    /**
     * @param $name
     * @param $value
     */
    public function set($name, $value)
    {
        $settingsTable = $this->getSettingsTable();

        if (is_array($value)) {
            $settingsTable->set($name, serialize($value));
        } else {
            $settingsTable->set($name, $value);
        }
    }

    /**
     * @param ServiceManager $serviceManager
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    /**
     * @return mixed
     */
    protected function getSettingsTable()
    {
        if ($this->tableSettings == null) {
            $this->tableSettings = $this->serviceManager->get('Msingi\Cms\Db\Table\Settings');
        }

        return $this->tableSettings;
    }

    /**
     * @param $valueName
     * @return mixed
     */
    public static function formatValueName($valueName)
    {
        $valueName = preg_replace('/[^a-z0-9_]/i', '_', $valueName);
        $valueName = preg_replace('/[_]+/', '_', $valueName);
        return $valueName;
    }

    /**
     * @return null|\Zend\Cache\Storage\StorageInterface
     */
    protected function getCache()
    {
        return $this->serviceLocator->get('Application\Cache');
    }

}