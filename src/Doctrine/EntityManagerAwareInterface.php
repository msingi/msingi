<?php
namespace Application\DBAL;

use Doctrine\ORM\EntityManager;

/**
 * Interface EntityManagerAwareInterface
 * @package Application\DBAL
 */
interface EntityManagerAwareInterface
{
    public function setEntityManager(EntityManager $entityManager);

    public function getEntityManager();
}