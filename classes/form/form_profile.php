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
 * short_description
 *
 * long_description
 *
 * @package    package_subpackage
 * @copyright  2023 Jonas Rehkopp jonas.rehkopp@polizei.nrw.de
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 defined('MOODLE_INTERNAL') || die();

 require_once($CFG->libdir . '/formslib.php');

 class form_profile extends moodleform {
    // Add elements to form.
    public function definition() {

        global $USER;
        $mform = $this->_form;

        $mform->addElement('hidden', 'cmid', $this->_customdata['cmid']);
        $mform->setType('cmid', PARAM_INT);

        /*
        $mform->addElement('text', 'name', get_string('name', 'mod_upc'), array('placeholder' => get_string('placeholder_name', 'mod_upc'), 'maxlength' => 255, 'size' => 50));
        // Set type of element.
        $mform->setType('name', PARAM_TEXT);
        // Erforderlich
        $mform->addRule('name', get_string('not_empty', 'mod_upc'), 'required', '', 'client', false, false);
        */

        $filemanageropts = $this->_customdata['filemanageropts'];
        $mform->addElement(
            'filemanager',
            'upcpicture',
            get_string('attachments', 'mod_upc'),
            null,
            $filemanageropts
        );
        $mform->setDefault('upcpicture', $this->_customdata['picture']);

        // Add elements to your form.
        $mform->addElement('textarea', 'description', get_string('description', 'mod_upc'), array('placeholder' => get_string('placeholder_description', 'mod_upc'), 'style' => 'width: 80%;'));
        $mform->setType('description', PARAM_TEXT);
        //if (!empty($this->_customdata['description'])) {
            $mform->setDefault('description', $this->_customdata['description']);
        //}
        $mform->addRule('description', get_string('invalid_description', 'mod_upc'), 'callback', 'validate_description', 'client', false, false);

        $this->add_action_buttons(true, "Speichern");
    }

    // Define the validation callback function.
    function validate_description($value) {
        if (strpos($value, '<') !== false || strpos($value, '>') !== false) {
            return false; // Invalid
        } else {
            return true; // Valid
        }
    }

}