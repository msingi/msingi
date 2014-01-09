<?php

namespace Msingi\Cms\Controller\Backend;

use Msingi\Cms\Form\Backend\LoginForm;
use Zend\Mvc\MvcEvent;

class LoginController extends ActionController
{
    protected $storage;

    /**
     * @param MvcEvent $e
     * @return mixed|void
     */
    public function onDispatch(MvcEvent $e)
    {
        $this->layout('layout/login');

        return parent::onDispatch($e);
    }

    /**
     * @return array|object
     */
    public function getSessionStorage()
    {
        if (!$this->storage) {
            $this->storage = $this->getServiceLocator()->get('Msingi\Cms\Model\Backend\AuthStorage');
        }

        return $this->storage;
    }

    /**
     * @return array|\Zend\Http\Response
     */
    public function indexAction()
    {
        if ($this->getAuthService()->hasIdentity()) {
            return $this->redirect()->toRoute('backend/home');
        }

        $form = new LoginForm();

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
                    //
                    if ($request->getPost('rememberme') == 1) {
                        $this->getSessionStorage()->setRememberMe(1);

                        //set storage again
                        $this->getAuthService()->setStorage($this->getSessionStorage());
                    }

                    $this->getAuthService()->getStorage()->write($request->getPost('username'));
                }

                return $this->redirect()->toRoute('backend/home');
            }
        }

        return array('form' => $form);
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

        $this->flashmessenger()->addMessage("You've been logged out");

        return $this->redirect()->toRoute('backend/login');
    }
}