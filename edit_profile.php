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

 
require_once('../../config.php');
require_once($CFG->dirroot. '/mod/upc/classes/form/form_profile.php');

$context = context_system::instance();
$PAGE->set_context($context);

$PAGE->set_url(new moodle_url('/mod/upc/edit_profile.php'));

$PAGE->set_pagelayout('standard');

$PAGE->set_title(get_string('pluginname', 'mod_upc'));
$PAGE->set_heading(get_string('pluginname', 'mod_upc'));

echo $OUTPUT->header();

// Instantiate the myform form from within the plugin.
$mform = new form_profile();

// Form processing and displaying is done here.
if ($mform->is_cancelled()) {
} else if ($fromform = $mform->get_data()) {
} else {
    // Display the form.
    $mform->display();
}

echo $OUTPUT->footer();