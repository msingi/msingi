<?php
namespace Msingi\Cms;

use Doctrine\ORM\EntityManager;
use Msingi\Doctrine\EntityManagerAwareInterface;

/**
 * Class Settings
 *
 * @package Msingi\Cms
 */
class Settings implements EntityManagerAwareInterface
{
    /** @var EntityManager */
    protected $entityManager = null;

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
     * @param EntityManager $entityManager
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     *
     */
    protected function loadSettings()
    {

    }

}