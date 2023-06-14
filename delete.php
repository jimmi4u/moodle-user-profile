<?php
require_once('../../config.php');

$cmid = required_param('cmid', PARAM_INT);
global $DB;

$result = $DB->get_record('userdata', ['userid' => $USER->id, 'activityid' => $cmid]);

$msg = "";
try {
    $delete = $DB->delete_records('userdata', ['id' => $result->id]);
    $msg = get_string('success', 'mod_upc');
} catch (Exception $ex) {
    $msg = get_string('failed', 'mod_upc');
}

$url = new moodle_url('/mod/upc/view.php', ['id' => $cmid]);
$message = $msg;
redirect($url, $message);
