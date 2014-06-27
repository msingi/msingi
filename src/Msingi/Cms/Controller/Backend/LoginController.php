<?php

namespace Msingi\Cms\Controller\Backend;

use Msingi\Cms\Form\Backend\LoginForm;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;

/**
 * Class LoginController
 *
 * @package Msingi\Cms\Controller\Backend
 */
class LoginController extends ActionController
{
    /** @var \Msingi\Cms\Service\AuthStorage */
    protected $sessionStorage;

    /**
     * @param MvcEvent $e
     * @return mixed|void
     */
    public function onDispatch(MvcEvent $e)
    {
        $this->layout('backend/layout/login');

        return parent::onDispatch($e);
    }

    /**
     * @return \Msingi\Cms\Service\AuthStorage
     */
    public function getSessionStorage()
    {
        if (!$this->sessionStorage) {
            $this->sessionStorage = $this->getServiceLocator()->get('Msingi\Cms\Service\Backend\AuthStorage');
        }

        return $this->sessionStorage;
    }

    /**
     * @return array|\Zend\Http\Response
     */
    public function indexAction()
    {
        if ($this->getAuthService()->hasIdentity()) {
            return $this->redirect()->toRoute('backend/index');
        }

        $form = new LoginForm();

        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {

                $this->getAuthService()->getAdapter()
                    ->setIdentity($request->getPost('username'))
                    ->setCredential($request->getPost('password'));

                $result = $this->getAuthService()->authenticate();

                //save message temporary into flashmessenger
                foreach ($result->getMessages() as $message) {
                    $this->flashmessenger()->addMessage($message);
                }

                //
                if ($result->isValid()) {
                    // authentication succeeded
                    if ($request->getPost('rememberme') == 1) {
                        $this->getSessionStorage()->setRememberMe(1);

                        //set storage again
                        $this->getAuthService()->setStorage($this->getSessionStorage());
                    }

                    $this->getAuthService()->getStorage()->write($result->getIdentity());
                }

                return $this->redirect()->toRoute('backend/index');
            }
        }

        return new ViewModel(array(
            'form' => $form
        ));
    }

    /**
     * @return \Zend\Http\Response
     */
    public function logoutAction()
    {
        if (!$this->getAuthService()->hasIdentity()) {
            return $this->redirect()->toRoute('backend/login');
        }

        $this->getSessionStorage()->forgetMe();
        $this->getAuthService()->clearIdentity();

        $this->flashmessenger()->addMessage($this->_("You've been logged out"));

        return $this->redirect()->toRoute('backend/login');
    }
}
