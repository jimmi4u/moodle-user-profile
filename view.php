<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Prints an instance of mod_upc.
 *
 * @package     mod_upc
 * @copyright   2023 Simon Schaudt  and others
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__.'/../../config.php');
require_once(__DIR__.'/lib.php');
require_once(__DIR__.'/locallib.php');

global $USER, $DB;

// Course module id.
$id = optional_param('id', 0, PARAM_INT);

// Activity instance id.
$u = optional_param('u', 0, PARAM_INT);

if ($id) {
    $cm = get_coursemodule_from_id('upc', $id, 0, false, MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $moduleinstance = $DB->get_record('upc', array('id' => $cm->instance), '*', MUST_EXIST);
} else {
    $moduleinstance = $DB->get_record('upc', array('id' => $u), '*', MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $moduleinstance->course), '*', MUST_EXIST);
    $cm = get_coursemodule_from_instance('upc', $moduleinstance->id, $course->id, false, MUST_EXIST);
}

require_login($course, true, $cm);

$modulecontext = context_module::instance($cm->id);

//$event = \mod_upc\event\course_module_viewed::create(array(
//    'objectid' => $moduleinstance->id,
//    'context' => $modulecontext
//));
//$event->add_record_snapshot('course', $course);
//$event->add_record_snapshot('upc', $moduleinstance);
//$event->trigger();

$PAGE->set_url('/mod/upc/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($moduleinstance->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($modulecontext);

echo $OUTPUT->header();

$formlink = 'edit_profile.php?cmid=' . $cm->id;

$context = context_module::instance($cm->id);

$hascard = $DB->get_record(
    'userdata',
    array(
        'userid' => $USER->id,
        'activityid' => $context->instanceid
        )
);
$formlink_delete = 'form_delete.php?cmid=' . $cm->id . '&userid=' . $USER->id;

$records = $DB->get_records('userdata', array('activityid' => $context->instanceid));

$getheading = $DB->get_field('upc', 'customdesc', array('id' => $cm->instance), '*', MUST_EXIST);

// Show my own card first
if ($hascard && count($records) > 1) {
    unset($records[$hascard->id]);
    array_unshift($records, $hascard);
}

echo '<div class="row">';

// Hide the add-functionality if I already have a card
if (!$hascard) {
    $newcarddata = array('form_link' => $formlink);
    echo $OUTPUT->render_from_template('mod_upc/new_card', $newcarddata);
}

foreach ($records as $record) {
    $user = $DB->get_record('user', array('id' => $record->userid));
    $templatesettingscard = (object)[
        'url' => get_image_link($context->id, $record->userid),
        'text' => $record->textfield,
        'name' => $user->firstname . ' ' . $user->lastname,
        'its_my_card' => $user->id === $USER->id,
        'form_link' => $formlink,
        'customheading' => $getheading,
    ];
    echo $OUTPUT->render_from_template('mod_upc/card', $templatesettingscard);
}

foreach ($records as $record) {
    $user = $DB->get_record('user', array('id' => $record->userid));
    $templatesettingscard = (object)[
        'url' => get_image_link($context->id, $record->userid),
        'text' => $record->textfield,
        'name' => $user->firstname . ' ' . $user->lastname,
        'its_my_card' => $user->id === $USER->id,
        'form_link' => $formlink,
        'form_link_delete' => $formlink_delete
    ];
    echo $OUTPUT->render_from_template('mod_upc/card', $templatesettingscard);
}

echo '</div>';

echo $OUTPUT->footer();
