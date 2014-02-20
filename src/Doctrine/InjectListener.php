<?php
namespace Application\DBAL;

/**
 * Class InjectListener
 * @package Application\DBAL
 */
class InjectListener
{
    private $serviceLocator;

    /** @var \Doctrine\ORM\EntityManager */
    private $entityManager;

    /**
     * @param $serviceLocator
     */
    public function __construct($serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * @param $eventArgs
     */
    public function postLoad($eventArgs)
    {
        $entity = $eventArgs->getEntity();
        if ($entity instanceof EntityManagerAwareInterface) {
            $entity->setEntityManager($this->getEntityManager());
        }
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEntityManager()
    {
        if($this->entityManager == null) {
            $this->entityManager = $this->serviceLocator->get('Doctrine\ORM\EntityManager');
        }

        return $this->entityManager;
    }
}