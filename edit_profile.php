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

$cmid = optional_param('cmid', 0, PARAM_INT);

$context = context_system::instance();
$PAGE->set_context($context);

$PAGE->set_url(new moodle_url('/mod/upc/edit_profile.php', ['cmid' => $cmid]));

$PAGE->set_pagelayout('standard');

$PAGE->set_title(get_string('pluginname', 'mod_upc'));
$PAGE->set_heading(get_string('pluginname', 'mod_upc'));


$cm = get_coursemodule_from_id('upc', $cmid, 0, false, MUST_EXIST);
$context = context_module::instance($cm->id);

$filemanageropts = array(
    'subdirs' => 0,
    'maxbytes' => 26214400,
    'areamaxbytes' => 26214400,
    'maxfiles' => 1,
    'context' => $context,
    'accepted_types' => array('.jpg', '.jpeg', '.png'),
);

$itemid = $USER->id;

$draftupcpicture = file_get_submitted_draft_itemid('upcpicture');
file_prepare_draft_area($draftupcpicture, $context->id, 'mod_upc', 'upcpicture', $itemid, $filemanageropts);

$check_data = $DB->get_record('userdata', ['userid' => $USER->id, 'activityid' => $context->instanceid]);
if (empty($check_data->textfield)) {
    $description = '';
} else {
    $description = $check_data->textfield;
}

$customdesc = $DB->get_field('upc', 'customdesc', array('id' => $cm->instance), '*', MUST_EXIST);

$customdata = array(
    'newcard' => empty($check_data),
    'filemanageropts' => $filemanageropts,
    'cmid' => $cmid,
    'picture' => $draftupcpicture,
    'description' => $description,
    'customdesc' => $customdesc
);
$mform = new form_profile(null, $customdata);


// Form processing and displaying is done here.
if ($mform->is_cancelled()) {
    redirect(new moodle_url('/mod/upc/view.php', array('id' => $cmid)));
} else if ($fromform = $mform->get_data()) {

    file_save_draft_area_files($draftupcpicture, $context->id, 'mod_upc', 'upcpicture', $itemid, $filemanageropts);

    if (empty($check_data)) {
        $DB->insert_record('userdata', ['usermodified' => $USER->id, 'userid' => $USER->id, 'activityid' => $context->instanceid, 'timecreated' => time(), 'timemodified' => time(), 'textfield' => $fromform->description]);
    } else {
        $check_data->usermodified = $USER->id;
        $check_data->timemodified = time();
        $check_data->textfield = $fromform->description;
        $DB->update_record('userdata', $check_data);
    }
    
    redirect(new moodle_url('/mod/upc/view.php', array('id' => $cmid)));

} else {

    echo $OUTPUT->header();
    // Display the form.
    $mform->display();

    echo $OUTPUT->footer();
}
