<?php

namespace Msingi\Cms\Model;

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;

/**
 * Class Settings
 * @package Msingi\Cms\Model
 */
class Settings implements ServiceManagerAwareInterface
{
    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * @var \Msingi\Cms\Db\Table\Settings
     */
    protected $tableSettings;

    /**
     * @var array
     */
    protected $values;

    /**
     * @param string $name
     * @param null $default
     * @return mixed
     */
    public function get($name, $default = null)
    {
        if ($this->values == null) {
            $this->loadSettings();
        }

        return isset($this->values[$name]) ? $this->values[$name] : $default;
    }

    /**
     * @param string $name
     * @param $value
     */
    public function set($name, $value)
    {
        if (is_array($value)) {
            $this->getSettingsTable()->set($name, serialize($value));
        } else {
            $this->getSettingsTable()->set($name, $value);
        }

        // update cache
        $cache = $this->getCache();

        $this->values[$name] = $value;

        $cache->setItem('settings', $this->values);
    }

    /**
     * @param ServiceManager $serviceManager
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    /**
     *
     */
    protected function loadSettings()
    {
        $cache = $this->getCache();

        $values = $cache->getItem('settings');
        if ($values != null) {
            $this->values = $values;
            return;
        }

        $this->values = array();

        $rowset = $this->getSettingsTable()->fetchAll();
        foreach ($rowset as $row) {
            // try to unserialize value
            $value = @unserialize($row['value']);
            $this->values[$row['name']] = ($value !== false) ? $value : $row['value'];
        }

        // update cache
        $cache->setItem('settings', $this->values);
    }

    /**
     * @return \Msingi\Cms\Db\Table\Settings
     */
    protected function getSettingsTable()
    {
        if ($this->tableSettings == null) {
            $this->tableSettings = $this->serviceManager->get('Msingi\Cms\Db\Table\Settings');
        }

        return $this->tableSettings;
    }

    /**
     * @param string $valueName
     * @return string
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
        return $this->serviceManager->get('Application\Cache');
    }

}