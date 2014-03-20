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
return array(
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
