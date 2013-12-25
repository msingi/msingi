<?php

$available_languages = array(
    'en' => 'English',
    'de' => 'German',
);


return array(

    'settings' => array(
        'general' => array(
            'label' => 'General settings',
            'values' => array(
                'application:name' => array(
                    'label' => 'Application name',
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
                ),
                'frontend:languages:multilanguage' => array(
                    'type' => 'checkbox',
                    'label' => 'Multilanguage enabled',
                    'default' => false
                ),
                'frontend:languages:enabled' => array(
                    'type' => 'MultiCheckbox',
                    'label' => 'Enabled languages',
                    'value_options' => $available_languages,
                    'default' => array('en', 'de'),
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
                    'default' => 'en'
                ),
            ),
        ),
    ),
);