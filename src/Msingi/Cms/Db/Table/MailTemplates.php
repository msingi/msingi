<?php

namespace Msingi\Cms\Db\Table;

use Msingi\Db\TableI18n;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;

/**
 * Class MailTemplates
 * @package Msingi\Cms\Db\Table
 */
class MailTemplates extends TableI18n
{
    /**
     * Get definition of the object properties
     * @return array
     */
    protected static function getDefinition()
    {
        return array(
            'table' => 'cms_mail_templates',
            'object' => 'Msingi\Cms\Model\MailTemplate',
            'fields' => array(
                'name' => 'string',
                'description' => 'string',
                'tokens' => 'string'
            )
        );
    }

    /**
     * @param string $templateName
     * @param string $language
     * @return \Msingi\Cms\Model\MailTemplate
     */
    public function fetchOrCreate($templateName, $language)
    {
        $rowset = $this->select(function (Select $select) use ($templateName, $language) {
            $select->join('cms_mail_templates_i18n',
                new Expression('cms_mail_templates_i18n.parent_id = cms_mail_templates.id AND language = ?', $language),
                array('subject', 'template'), 'left');
            $select->where(array('name' => $templateName));
        });

        $template = $rowset->current();

        if ($template == null) {

            $this->insert(array(
                'name' => $templateName,
                'description' => '',
                'tokens' => ''
            ));

            $rowset = $this->select(array('id' => $this->lastInsertValue));
            $template = $rowset->current();
        }

        return $template;
    }

}