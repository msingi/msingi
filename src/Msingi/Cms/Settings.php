<?php
namespace Msingi\Cms;

use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class Settings
 *
 * @package Msingi\Cms
 */
class Settings implements ServiceLocatorAwareInterface
{
    /** @var EntityManager */
    protected $entityManager = null;

    /** @var ServiceLocatorInterface */
    protected $serviceLocator = null;

    /** @var array */
    protected $values = null;

    /**
     * @param $name
     * @param null $default
     * @return mixed
     */
    public function get($name, $default = null)
    {
        if ($this->values == null) {
            $this->values = $this->loadSettings();
        }

        return isset($this->values[$name]) ? $this->values[$name] : $default;
    }

    /**
     * @param $name
     * @param $value
     */
    public function set($name, $value)
    {
        $this->values[$name] = $value;

        /** @var \Msingi\Cms\Repository\Settings $settings */
        $settings = $this->getEntityManager()->getRepository('Msingi\Cms\Entity\Setting');

        if (is_array($value)) {
            $settings->set($name, serialize($value));
        } else {
            $settings->set($name, $value);
        }

        // try to get fragments from cache
        $cache = $this->getServiceLocator()->get('Application\Cache');
        if ($cache) {
            $cacheKey = sprintf('settings');
            $cache->removeItem($cacheKey);
        }
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        if ($this->entityManager == null) {
            $this->entityManager = $this->serviceLocator->get('Doctrine\ORM\EntityManager');
        }

        return $this->entityManager;
    }

    /**
     *
     */
    protected function loadSettings()
    {
        // try to get fragments from cache
        $cache = $this->getServiceLocator()->get('Application\Cache');
        if ($cache) {
            $cacheKey = sprintf('settings');
            $values = $cache->getItem($cacheKey);
        }

        // fetch from the DB
        if ($values == null) {
            /** @var \Msingi\Cms\Repository\Settings $settings */
            $settings = $this->getEntityManager()->getRepository('Msingi\Cms\Entity\Setting');

            $values = $settings->fetchArray();
        }

        // store to cache
        if ($cache) {
            $cache->setItem($cacheKey, $values);
        }

        return $values;
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
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Get service locator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
}