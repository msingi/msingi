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
     * @param boolean $empty
     * @param mixed $selected
     * @return string
     */
    public function __invoke($values, $empty = false, $selected = null)
    {
        $ret = array();

        if ($empty) {
            $option = '<option value=""';
            if ($selected == null) {
                $option .= ' selected="selected"';
            }
            $option .= '></option>';

            $ret[] = $option;
        }

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