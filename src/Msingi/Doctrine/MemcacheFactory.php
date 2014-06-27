<?php
namespace Msingi\Doctrine;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class MemcacheFactory
 * @package Msingi\Doctrine
 */
class MemcacheFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return \Doctrine\Common\Cache\MemcacheCache
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $cache = new \Doctrine\Common\Cache\MemcacheCache();
        $memcache = new \Memcache();
        $memcache->connect('localhost', 11211);
        $cache->setMemcache($memcache);

        return $cache;
    }
}
