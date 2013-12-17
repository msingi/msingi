<?php

return array(
    'view_helpers' => array(
        'invokables' => array(
            'assets' => 'Msingi\Cms\View\Helper\Assets',
            'headLess' => 'Msingi\Cms\View\Helper\HeadLess',
            'deferJs' => 'Msingi\Cms\View\Helper\DeferJs',

            'language' => 'Msingi\Cms\View\Helper\Language',
            'locale' => 'Msingi\Cms\View\Helper\Locale',

            '_' => 'Zend\I18n\View\Helper\Translate',
            '_p' => 'Zend\I18n\View\Helper\TranslatePlural',

            'formElementErrorClass' => 'Msingi\Cms\View\Helper\FormElementErrorClass',
        ),
    ),
);