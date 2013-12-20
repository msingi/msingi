<?php

namespace Msingi\Cms\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;

class BackendCurrentUser extends AbstractHelper implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;

    /**
     * @return string
     */
    public function __invoke()
    {
        //$authStorage = $this->getServiceLocator()->get('Msingi\Cms\Model\Backend\AuthStorage');

        return (object)array(
            'name' => 'current user',
            'email' => 'user@example.com'
        );
    }

    /**
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return $this
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

}