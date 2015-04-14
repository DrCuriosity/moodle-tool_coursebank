<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Filter for filesize fields.
 *
 * @package    tool_coursestore
 * @author     Dmitrii Metelkin <dmitriim@catalyst-au.net>
 * @copyright  2015 Catalyst IT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once($CFG->dirroot.'/user/filters/lib.php');

class coursestore_filter_filesize extends user_filter_text {

    /**
     * Returns an array of comparison operators
     * @return array of comparison operators
     */
    public function getoperators() {
        return array(0 => get_string('filtermorethan', 'tool_coursestore'),
                     1 => get_string('filterlessthan', 'tool_coursestore'),
                     2 => get_string('filterisequalto', 'tool_coursestore')
        );
    }
    /**
     * Returns an array of sizes
     *
     * @return type
     */
    public function get_size() {
        return array(0 => get_string('sizeb'),
                     1 => get_string('sizekb'),
                     2 => get_string('sizemb'),
                     3 => get_string('sizegb')
        );
    }
    /**
     * Convert to bytes based on scale provided.
     *
     * @param int $scale Size format
     * @param int $value
     * @return type
     */
    public function value_to_bytes($scale, $value) {
        switch($scale) {
            case 0: // Bytes.
                return $value;
                break;
            case 1: // KB.
                return $value * 1024;;
                break;
            case 2: // MB.
                return $value * pow(1024, 2);
                break;
            case 3: // GB.
                return $value * pow(1024, 3);
                break;
            default:
                return $value;
        }
    }
    /**
     * Adds controls specific to this filter in the form.
     * @param object $mform a MoodleForm object to setup
     */
    public function setupform(&$mform) {
        $objs = array();
        $objs['select'] = $mform->createElement('select', $this->_name.'_op', null, $this->getOperators());
        $objs['text'] = $mform->createElement('text', $this->_name, null);
        $objs['select']->setLabel(get_string('limiterfor', 'filters', $this->_label));
        $objs['text']->setLabel(get_string('valuefor', 'filters', $this->_label));
        $objs['selectscale'] = $mform->createElement('select', $this->_name.'_scale', null, $this->get_size());
        $grp =& $mform->addElement('group', $this->_name.'_grp', $this->_label, $objs, '', false);
        $mform->setType($this->_name, PARAM_RAW);
        if ($this->_advanced) {
            $mform->setAdvanced($this->_name.'_grp');
        }
    }

    /**
     * Retrieves data from the form data
     * @param object $formdata data submited with the form
     * @return mixed array filter data or false when filter not set
     */
    public function check_data($formdata) {
        $field    = $this->_name;
        $operator = $field.'_op';
        $sacale   = $field.'_scale';
        if (array_key_exists($operator, $formdata)) {
            if ($formdata->$field == '') {
                // No data - no change except for empty filter.
                return false;
            }
            // If field value is set then use it, else it's null.
            $fieldvalue = null;
            if (isset($formdata->$field)) {
                $fieldvalue = $formdata->$field;
            }
            return array('operator' => (int)$formdata->$operator, 'value' => (int)$fieldvalue, 'scale' => $formdata->$sacale);
        }

        return false;
    }

    /**
     * Returns the condition to be used with SQL where
     * @param array $data filter settings
     * @return array sql string and $params
     */
    public function get_sql_filter($data) {
        $operator = $data['operator'];
        $scale    = $data['scale'];
        $value    = $this->value_to_bytes($scale, $data['value']);
        $field    = $this->_field;
        $res = '';

        if ($value === '') {
            return '';
        }

        switch($operator) {
            case 0: // More than.
                $res .= "$field > $value";
                break;
            case 1: // Less than.
                $res .= "$field < $value";
                break;
            case 2: // Equal to.
                $res .= "$field = $value";
                break;
            default:
                return '';
        }
        return array($res, array());
    }
    /**
     * Returns params
     *
     * @param array $data filter settings
     * @return array sql string and $params
     */
    public function get_param_filter($data) {
        $params = array();

        $operator = $data['operator'];
        $scale    = $data['scale'];
        $value    = $this->value_to_bytes($scale, $data['value']);

        if ($value === '') {
            return '';
        }

        switch($operator) {
            case 0: // More than.
                $params = array('operator' => '>', 'value' => $value);
                break;
            case 1: // Less than.
                $params = array('operator' => '<', 'value' => $value);
                break;
            case 2: // Equal to.
                $params = array('operator' => '=', 'value' => $value);
                break;
            default:
                return '';
        }
        return $params;
    }

    /**
     * Returns a human friendly description of the filter used as label.
     * @param array $data filter settings
     * @return string active filter label
     */
    public function get_label($data) {
        $operator = $data['operator'];
        $scale    = $data['scale'];
        $value    = $this->value_to_bytes($scale, $data['value']);

        $operators = $this->getOperators();

        $a = new stdClass();
        $a->label    = $this->_label;
        $a->value    = '"' . s(display_size($value)) . '"';
        $a->operator = $operators[$operator];

        switch ($operator) {
            case 0: // More than.
            case 1: // Ledd than.
            case 2: // Equal to.
                return get_string('textlabel', 'filters', $a);
        }

        return '';
    }
}
