<?php

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
                ),
                'frontend:languages:multilanguage' => array(
                    'type' => 'checkbox',
                    'label' => 'Multilanguage enabled',
                ),
                'frontend:languages:enabled' => array(
                    'type' => 'MultiCheckbox',
                    'label' => 'Enabled languages',
                    'value_options' => array(
                        'en' => 'English',
                        'de' => 'German',
                    ),
                ),
            ),
        ),
        'backend' => array(
            'label' => 'Backend',
            'values' => array(
                'backend:languages:default' => array(
                    'type' => 'select',
                    'label' => 'Default language',
                ),
                'backend:languages:multilanguage' => array(
                    'type' => 'checkbox',
                    'label' => 'Multilanguage enabled',
                ),
                'backend:languages:enabled' => array(
                    'type' => 'MultiCheckbox',
                    'label' => 'Enabled languages',
                    'value_options' => array(
                        'en' => 'English',
                        'de' => 'German',
                    ),
                ),
            ),
        ),
    ),
);