<?php

namespace Msingi\Cms\View\Helper;

use Zend\I18n\View\Helper\AbstractTranslatorHelper;

/**
 * Class SelectOptions
 *
 * @package Msingi\Cms\View\Helper
 */
class SelectOptions extends AbstractTranslatorHelper
{

    /**
     * @param array $values
     * @param mixed $selected
     * @return string
     */
    public function __invoke($values, $selected = null)
    {
        $ret = array();

        foreach ($values as $value => $label) {
            $option = '<option';
            $option .= ' value="' . $value . '"';
            if ($selected != null && $value == $selected) {
                $option .= ' selected="selected"';
            }
            $option .= '>';
            $option .= $label;
            $option .= '</option>';

            $ret[] = $option;
        }

        return implode(PHP_EOL, $ret);
    }

}