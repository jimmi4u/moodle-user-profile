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

$PAGE->set_url(new moodle_url('/mod/upc/edit_profile.php'));

$PAGE->set_pagelayout('standard');

$PAGE->set_title(get_string('pluginname', 'mod_upc'));
$PAGE->set_heading(get_string('pluginname', 'mod_upc'));

echo $OUTPUT->header();


$filemanageropts = array(
    'subdirs' => 0,
    'maxbytes' => 26214400,
    'areamaxbytes' => 26214400,
    'maxfiles' => 1,
    'context' => $context,
    'accepted_types' => array('.jpg', '.jpeg', '.png'),
);

//$cm = get_coursemodule_from_id('upc', $cmid, 0, false, MUST_EXIST);
//$context = context_module::instance($cm->id);
$context = context_system::instance();

$itemid = $USER->id;

$customdata = array(
    'filemanageropts' => $filemanageropts
);

// Instantiate the myform form from within the plugin.
$mform = new form_profile();
$mform->set_data($data);

$draftupcpicture = file_get_submitted_draft_itemid('upcpicture');
file_prepare_draft_area($draftupcpicture, $context->id, 'mod_upc', 'upcpicture', $itemid, $filemanageropts);

$data = new stdClass();
$data->upcpicture = $draftupcpicture;
$mform->set_data($data);

// Form processing and displaying is done here.
if ($mform->is_cancelled()) {

} else if ($fromform = $mform->get_data()) {

    $text = file_save_draft_area_files($draftupcpicture, $context->id, 'mod_upc', 'upcpicture', $itemid, $filemanageropts);

} else {

//    $fs = get_file_storage();
//    $files = $fs->get_area_files(
//        $context->id,
//        'mod_upc',
//        'upcpicture',
//        $itemid // USERID
//    );
//
//    foreach ($files as $file) {
//        if ($file->get_filename() !== '.') {
//            $imagefile = $file;
//        }
//    }
//
//    if ($imagefile) {
//        $url = moodle_url::make_pluginfile_url(
//            $imagefile->get_contextid(),
//            $imagefile->get_component(),
//            $imagefile->get_filearea(),
//            $imagefile->get_itemid(),
//            $imagefile->get_filepath(),
//            $imagefile->get_filename()
//        );
//        echo '<img src="' . $url . '">';
//    }


    // Display the form.
    $mform->display();
}

echo $OUTPUT->footer();