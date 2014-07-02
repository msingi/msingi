<?php

/**
 * Msingi module configuration
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

    'service_manager' => array(
        'invokables' => array(
            //
            'Settings' => 'Msingi\Cms\Settings',
            // mailer
            'Msingi\Cms\Mailer\Mailer' => 'Msingi\Cms\Mailer\Mailer',
            // Event listeners
            'Msingi\Cms\Event\RouteListener' => 'Msingi\Cms\Event\RouteListener',
            'Msingi\Cms\Event\LocaleListener' => 'Msingi\Cms\Event\LocaleListener',
            'Msingi\Cms\Event\HttpListener' => 'Msingi\Cms\Event\HttpListener',
            //
            'Msingi\Cms\Service\Backend\AuthAdapter' => 'Msingi\Cms\Service\Backend\AuthAdapter',
            'Msingi\Cms\Form\Backend\SettingsForm' => 'Msingi\Cms\Form\Backend\SettingsForm',
            //
            'Msingi\Cms\Entity\Page' => 'Msingi\Cms\Entity\Page',
            'Msingi\Cms\Entity\Article' => 'Msingi\Cms\Entity\Article',
        ),
        'factories' => array(
            // memcached caching for doctrine
            'doctrine.cache.memcached' => 'Msingi\Doctrine\MemcacheFactory',
            // content manager
            'Msingi\Cms\ContentManager' => 'Msingi\Cms\Service\ContentManager',
            // backend authentication
            'Msingi\Cms\Service\Backend\AuthStorage' => 'Msingi\Cms\Service\Backend\AuthStorageFactory',
            'Msingi\Cms\Service\Backend\AuthService' => 'Msingi\Cms\Service\Backend\AuthServiceFactory',
        ),
    ),

    'controller_plugins' => array(
        'factories' => array(
            '_' => 'Msingi\Cms\Controller\Plugin\TranslateFactory',
            'SendMail' => 'Msingi\Cms\Controller\Plugin\SendMailFactory',
        ),
    ),

    'view_helpers' => array(
        'invokables' => array(
            'assets' => 'Msingi\Cms\View\Helper\Assets',
            'headLess' => 'Msingi\Cms\View\Helper\HeadLess',
            'deferJs' => 'Msingi\Cms\View\Helper\DeferJs',

            'language' => 'Msingi\Cms\View\Helper\Language',
            'languageName' => 'Msingi\Cms\View\Helper\LanguageName',
            'locale' => 'Msingi\Cms\View\Helper\Locale',

            'date' => 'Msingi\Cms\View\Helper\Date',
            'relativeDate' => 'Msingi\Cms\View\Helper\RelativeDate',

            'selectOptions' => 'Msingi\Cms\View\Helper\SelectOptions',

            'imageAttachment' => 'Msingi\Cms\View\Helper\ImageAttachment',
            'fileAttachment' => 'Msingi\Cms\View\Helper\FileAttachment',

            'gravatar' => 'Msingi\Cms\View\Helper\Gravatar',

            '_' => 'Zend\I18n\View\Helper\Translate',
            '_p' => 'Zend\I18n\View\Helper\TranslatePlural',

            'excerpt' => 'Msingi\Cms\View\Helper\Excerpt',

            'configValue' => 'Msingi\Cms\View\Helper\ConfigValue',

            'formElementErrorClass' => 'Msingi\Cms\View\Helper\FormElementErrorClass',
        ),
        'factories' => array(
            'currentRoute' => 'Msingi\Cms\View\Helper\CurrentRoute',
            'fragment' => 'Msingi\Cms\View\Helper\PageFragment',
            'metaValue' => 'Msingi\Cms\View\Helper\PageMeta',
            'settingsValue' => 'Msingi\Cms\View\Helper\SettingsValue',
            'u' => 'Msingi\Cms\View\Helper\Url',
        ),
    ),

    'doctrine' => array(
        'driver' => array(
            'application_entities' => array(
                'paths' => array(__DIR__ . '/../src/Msingi/Cms/Entity')
            ),

            'orm_default' => array(
                'drivers' => array(
                    'Msingi\Cms\Entity' => 'application_entities'
                ),
            ),
        ),

        'enums' => array(
            'article_status' => 'Msingi\Cms\Entity\Enum\ArticleStatus',
            'page_type' => 'Msingi\Cms\Entity\Enum\PageType',
        ),
    ),

    'settings' => include 'settings.php',
);
