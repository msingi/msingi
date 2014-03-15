<?php

/**
 * Available languages
 */
$available_languages = array(
    'en' => _('English'),
    'de' => _('German'),
    'cs' => _('Czech'),
    'ru' => _('Russian'),
    'fr' => _('French'),
    'es' => _('Spanish')
);

/**
 * Default application settings
 */
$default_settings = array(
    'general' => array(
        'label' => _('General settings'),
        'values' => array(
            'application:name' => array(
                'label' => _('Application name'),
                'input_class' => 'form-control input-large'
            ),
        ),
    ),
    'frontend' => array(
        'label' => _('Frontend'),
        'values' => array(
            'frontend:languages:default' => array(
                'type' => 'select',
                'label' => _('Default language'),
                'value_options' => $available_languages,
                'default' => 'en',
                'input_class' => 'form-control input-medium'
            ),
            'frontend:languages:multilanguage' => array(
                'type' => 'checkbox',
                'label' => _('Multilanguage enabled'),
                'default' => false
            ),
            'frontend:languages:enabled' => array(
                'type' => 'MultiCheckbox',
                'value_options' => $available_languages,
                'default' => array_keys($available_languages),
            ),
        ),
    ),
    'backend' => array(
        'label' => _('Backend'),
        'values' => array(
            'backend:languages:default' => array(
                'type' => 'Select',
                'label' => _('Default language'),
                'value_options' => $available_languages,
                'default' => 'en',
                'input_class' => 'form-control input-medium'
            ),
        ),
    ),
    'mail' => array(
        'label' => _('Mail'),
        'values' => array(
            'mail:from' => array(
                'label' => _('Email From'),
                'input_class' => 'form-control input-large',
                'default' => 'noreply@example.com',
            ),
            'mail:log' => array(
                'type' => 'checkbox',
                'label' => _('Log sent mail'),
                'default' => false
            ),
            'mail:send' => array(
                'type' => 'checkbox',
                'label' => _('Really send mail'),
                'default' => false
            ),
        ),
    ),
);

/**
 * Config
 */
return array(

    'translator' => array(
        'translation_file_patterns' => array(
            array(
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => 'msingi_%s.mo',
            ),
        ),
    ),

    'settings' => $default_settings,

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

    'doctrine' => array(
        'driver' => array(
            'application_entities' => array(
                'paths' => array(__DIR__ . '/../src/Cms/Entity')
            ),

            'orm_default' => array(
                'drivers' => array(
                    'Msingi\Cms\Entity' => 'application_entities'
                ),
            ),
        ),

        'enums' => array(
            'page_type' => 'Msingi\Cms\Entity\Enum\PageType',
        ),
    ),
);