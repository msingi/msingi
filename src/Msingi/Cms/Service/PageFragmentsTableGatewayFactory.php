<?php

namespace Msingi\Cms\Service;

use Msingi\Cms\Model\Table\PageFragments;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PageFragmentsTableGatewayFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed|TableGateway
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(PageFragments::getPrototype());
        return new TableGateway('cms_page_fragments', $dbAdapter, null, $resultSetPrototype);
    }
}