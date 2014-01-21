<?php

$available_languages = array(
    'en' => 'English',
    'de' => 'German',
);

return array(

    'models' => array(
        'Msingi\Cms\Model\Backend\User',
        'Msingi\Cms\Model\Menu',
        'Msingi\Cms\Model\Page',
        'Msingi\Cms\Model\PageFragment',
    ),

    'tables' => array(
        'Msingi\Cms\Db\Table\BackendUsers',
        'Msingi\Cms\Db\Table\Menu',
        'Msingi\Cms\Db\Table\PageFragments',
        'Msingi\Cms\Db\Table\Pages',
        'Msingi\Cms\Db\Table\PageTemplates',
        'Msingi\Cms\Db\Table\Settings',
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
                    'default' => 'en',
                    'input_class' => 'form-control input-medium'
                ),
            ),
        ),
    ),
);