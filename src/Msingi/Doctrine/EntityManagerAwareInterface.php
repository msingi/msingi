<?php
namespace Msingi\Doctrine;

use Doctrine\ORM\EntityManager;

/**
 * Interface EntityManagerAwareInterface
 *
 * @package Application\DBAL
 */
interface EntityManagerAwareInterface
{
    /**
     * @param EntityManager $entityManager
     */
    public function setEntityManager(EntityManager $entityManager);

    /**
     * @return EntityManager
     */
    public function getEntityManager();
}