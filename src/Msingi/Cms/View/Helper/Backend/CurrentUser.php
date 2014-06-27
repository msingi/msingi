<?php

namespace Msingi\Cms\View\Helper\Backend;

use Msingi\Cms\View\AbstractHelper;

/**
 * Class CurrentUser
 * @package Msingi\Cms\View\Helper\Backend
 */
class CurrentUser extends AbstractHelper
{
    protected $currentUser;

    /**
     * @return \Msingi\Cms\Entity\BackendUser
     */
    public function __invoke()
    {
        if ($this->currentUser == null) {
            $this->currentUser = $this->fetchCurrentUser();
        }

        return $this->currentUser;
    }

    /**
     * @return \Msingi\Cms\Entity\BackendUser
     */
    protected function fetchCurrentUser()
    {
        $sl = $this->getServiceLocator()->getServiceLocator();

        $authService = $sl->get('Msingi\Cms\Service\Backend\AuthService');

        return $authService->getIdentity();
    }
}
