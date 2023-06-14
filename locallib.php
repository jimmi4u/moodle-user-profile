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
 * locallib of mod_upc.
 *
 * @package     mod_upc
 * @copyright   2023 Simon Schaudt  and others
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

function get_image_link($cmid, $itemid) {
    $fs = get_file_storage();
    $files = $fs->get_area_files(
        $cmid,
        'mod_upc',
        'upcpicture',
        $itemid // USERID
    );

    $imagefile = false;
    foreach ($files as $file) {
        if ($file->get_filename() !== '.') {
            $imagefile = $file;
            break;
        }
    }

    if ($imagefile) {
        return moodle_url::make_pluginfile_url(
            $imagefile->get_contextid(),
            $imagefile->get_component(),
            $imagefile->get_filearea(),
            $imagefile->get_itemid(),
            $imagefile->get_filepath(),
            $imagefile->get_filename()
        );
    }

    return "";
}