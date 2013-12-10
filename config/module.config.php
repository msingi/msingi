<?php

return array(
    'view_helpers' => array(
        'invokables' => array(
            'assets' => 'Msingi\View\Helper\Assets',
            'headLess' => 'Msingi\View\Helper\HeadLess',
            'deferJs' => 'Msingi\View\Helper\DeferJs',

            'language' => 'Msingi\View\Helper\Language',
            'locale' => 'Msingi\View\Helper\Locale',

            '_' => 'Zend\I18n\View\Helper\Translate',
            '_p' => 'Zend\I18n\View\Helper\TranslatePlural',

            'fragment' => 'Msingi\View\Helper\PageFragment',

            'formElementErrorClass' => 'Msingi\View\Helper\FormElementErrorClass',
        ),
    ),
);