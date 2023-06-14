<?php
require_once('../../config.php');

$cmid = required_param('cmid', PARAM_INT);

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/mod/upc/form_del.php'));
$PAGE->set_pagelayout('standard');
$PAGE->set_title($SITE->fullname);
$PAGE->set_heading(get_string('pluginname', 'mod_upc'));
echo $OUTPUT->header();


echo $OUTPUT->confirm(
    get_string('deleteconfirmmessage', 'mod_upc'),
    new moodle_url('/mod/upc/delete.php', [
        'cmid'  => $cmid
    ]),
    new moodle_url('/mod/upc/view.php', ['id' => $cmid])
);



echo $OUTPUT->footer();