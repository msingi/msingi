<?php

$available_languages = array(
    'en' => 'English',
    'de' => 'German',
    'cs' => 'Czech',
);

return array(

    'models' => array(
        'Msingi\Cms\Model\Backend\User',
        'Msingi\Cms\Model\MailTemplate',
        'Msingi\Cms\Model\Menu',
        'Msingi\Cms\Model\Page',
        'Msingi\Cms\Model\PageFragment',
    ),

    'tables' => array(
        'Msingi\Cms\Db\Table\BackendUsers',
        'Msingi\Cms\Db\Table\MailTemplates',
        'Msingi\Cms\Db\Table\Menu',
        'Msingi\Cms\Db\Table\PageFragments',
        'Msingi\Cms\Db\Table\Pages',
        'Msingi\Cms\Db\Table\PageTemplates',
        'Msingi\Cms\Db\Table\Settings',
    ),

    'translator' => array(
        'translation_file_patterns' => array(
            array(
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => 'msingi_%s.mo',
            ),
        ),
    ),

    'settings' => array(
        'general' => array(
            'label' => 'General settings',
            'values' => array(
                'application:name' => array(
                    'label' => 'Application name',
                    'input_class' => 'form-control input-large'
                ),
            ),
        ),
        'frontend' => array(
            'label' => 'Frontend',
            'values' => array(
                'frontend:languages:default' => array(
                    'type' => 'select',
                    'label' => 'Default language',
                    'value_options' => $available_languages,
                    'default' => 'en',
                    'input_class' => 'form-control input-medium'
                ),
                'frontend:languages:multilanguage' => array(
                    'type' => 'checkbox',
                    'label' => 'Multilanguage enabled',
                    'default' => false
                ),
                'frontend:languages:enabled' => array(
                    'type' => 'MultiCheckbox',
                    'value_options' => $available_languages,
                    'default' => array('en', 'de', 'cs'),
                ),
            ),
        ),
        'backend' => array(
            'label' => 'Backend',
            'values' => array(
                'backend:languages:default' => array(
                    'type' => 'select',
                    'label' => 'Default language',
                    'value_options' => $available_languages,
                    'default' => 'en',
                    'input_class' => 'form-control input-medium'
                ),
            ),
        ),
        'mail' => array(
            'label' => 'Mail',
            'values' => array(
                'mail:from' => array(
                    'label' => 'Mail From email',
                    'input_class' => 'form-control input-large',
                    'default' => 'noreply@example.com',
                ),
                'mail:log' => array(
                    'type' => 'checkbox',
                    'label' => 'Log sent mail',
                    'default' => false
                ),
                'mail:send' => array(
                    'type' => 'checkbox',
                    'label' => 'Really send mail',
                    'default' => false
                ),
            ),
        ),
    ),

    'controller_plugins' => array(
        'factories' => array(
            '_' => function ($sm) {
                    $translator = $sm->getServiceLocator()->get('Translator');

                    $plugin = new \Msingi\Cms\Controller\Plugin\Translate();

                    $plugin->setTranslator($translator);

                    return $plugin;
                },
            'SendMail' => function ($sm) {
                    $translator = $sm->getServiceLocator()->get('Translator');
                    $router = $sm->getServiceLocator()->get('Router');
                    $mailer = $sm->getServiceLocator()->get('Msingi\Cms\Mailer\Mailer');

                    $plugin = new \Msingi\Cms\Controller\Plugin\SendMail();
                    $plugin->setTranslator($translator);
                    $plugin->setRouter($router);
                    $plugin->setMailer($mailer);

                    return $plugin;
                }
        ),
    ),
);