<?php

namespace Msingi\Cms\View\Helper\Backend;

use Msingi\Cms\View\AbstractHelper;

class CurrentUser extends AbstractHelper
{
    protected $currentUser;

    /**
     * @return string
     */
    public function __invoke()
    {
        $sl = $this->getServiceLocator()->getServiceLocator();

        $authService = $sl->get('BackendAuthService');

        if ($this->currentUser == null) {
            $username = $authService->getStorage()->read('username');

            $usersTable = $sl->get('Msingi\Cms\Db\Table\BackendUsers');

            $this->currentUser = $usersTable->fetchByUsername($username);
        }

        return $this->currentUser;
    }
}