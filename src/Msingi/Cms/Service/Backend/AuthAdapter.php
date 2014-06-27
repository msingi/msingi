<?php
namespace Msingi\Cms\Service\Backend;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result as AuthResult;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AuthAdapter
 *
 * @package Msingi\Cms\Service\Backend
 */
class AuthAdapter implements AdapterInterface, ServiceLocatorAwareInterface
{
    /** @var ServiceLocatorInterface */
    protected $serviceLocator = null;

    /** @var null */
    protected $identity = null;

    /** @var */
    protected $credential = null;

    /**
     * Performs an authentication attempt
     *
     * @return \Zend\Authentication\Result
     * @throws \Zend\Authentication\Adapter\Exception\ExceptionInterface If authentication cannot be performed
     */
    public function authenticate()
    {
        $config = $this->getServiceLocator()->get('Config');

        $salt = $config['backend']['auth']['salt'];

        /** @var \Doctrine\ORM\EntityManager $entityManager */
        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        /** @var \Msingi\Cms\Entity\BackendUser $user */
        $user = $entityManager->getRepository('Msingi\Cms\Entity\BackendUser')->findUser($this->identity);
        if ($user == null) {
            return new AuthResult(AuthResult::FAILURE_IDENTITY_NOT_FOUND, null);
        }

        if ($user->getPassword() != sha1($salt . $this->credential . $user->getPasswordSalt())) {
            return new AuthResult(AuthResult::FAILURE_CREDENTIAL_INVALID, null);
        }

        return new AuthResult(AuthResult::SUCCESS, $user);
    }

    /**
     * @param $identity
     * @return $this
     */
    public function setIdentity($identity)
    {
        $this->identity = $identity;

        return $this;
    }

    /**
     * @param $credential
     * @return $this
     */
    public function setCredential($credential)
    {
        $this->credential = $credential;

        return $this;
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
