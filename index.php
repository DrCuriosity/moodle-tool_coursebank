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
 * Main page for user-facing download interface
 *
 * @package    tool_coursebank
 * @author     Adam Riddell <adamr@catalyst-au.net>
 * @author     Dmitrii Metelkin <adamr@catalyst-au.net>*
 * @copyright  2015 Catalyst IT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->dirroot.'/admin/tool/coursebank/locallib.php');
require_once($CFG->dirroot.'/admin/tool/coursebank/filters/lib.php');
require_once($CFG->dirroot.'/admin/tool/coursebank/lib.php');

defined('MOODLE_INTERNAL') || die;

$download     = optional_param('download', 0, PARAM_INT);
$file         = optional_param('file', 0, PARAM_ALPHANUMEXT);
$sort         = optional_param('sort', 'coursefullname', PARAM_ALPHANUM);
$dir          = optional_param('dir', 'ASC', PARAM_ALPHA);
$page         = optional_param('page', 0, PARAM_INT);
$perpage      = optional_param('perpage', 50, PARAM_INT);

$context = context_system::instance();
require_login(null, false);
require_capability('tool/coursebank:view', $context);

admin_externalpage_setup('tool_coursebank_download');

$params = array(
    'download' => $download,
    'file' => $file,
    'sort' => $sort,
    'dir' => $dir,
    'page' => $page,
    'perpage' => $perpage
);
//$url = new moodle_url('/admin/tool/coursebank/download.php', $params);
$url = new moodle_url('/admin/tool/coursebank/index.php', $params);
$urltarget = get_config('tool_coursebank', 'url');
$wsman = new coursebank_ws_manager($urltarget);
$sesskey = tool_coursebank::get_session();

// Downloading.
if ($download == 1 and !empty($file)) {
    require_capability('tool/coursebank:download', $context);
    $dlresponse = $wsman->get_backup($sesskey, $file, true);
    $errorurl = $url . "?sort=$sort&amp;dir=$dir&amp;page=$page&amp;perpage=$perpage";

    $error = tool_coursebank_error::is_response_backup_download_error($dlresponse);
    coursebank_logging::log_backup_download($dlresponse, $file);

    if (!$error) {
        redirect($dlresponse->body->url, '', 0);
    } else {
        print_error('errordownloading', 'tool_coursebank', $errorurl);
    }
}

$PAGE->set_url($url);
$PAGE->set_context($context);

$header = get_string('downloadsummary', 'tool_coursebank');
$PAGE->set_title($header);
echo $OUTPUT->header();
echo $OUTPUT->heading($header);

$filterparams = array(
        'coursefullname' => 0,
        'backupfilename' => 1,
        'filesize' => 1,
        'filetimemodified' => 1
);
$filtering = new coursebank_filtering('download', $filterparams);
$extraparams = $filtering->get_param_filter();

$response = $wsman->get_downloads($sesskey, $extraparams, $sort, $dir, $page, $perpage);
if ($response->httpcode != $wsman::WS_HTTP_OK or isset($response->body->error)) {
    $error = true;
}

$count = $wsman->get_downloadcount($sesskey);
if ($response->httpcode != $wsman::WS_HTTP_OK or isset($count->body->error)) {
    $error = true;
}

$renderer = $PAGE->get_renderer('tool_coursebank');

if ($error) {
    echo $OUTPUT->notification(get_string('errorgetdownloadlist', 'tool_coursebank'), 'notifyproblem');
    $returnurl = new moodle_url($CFG->wwwroot.'/admin/settings.php', array('section' => 'coursebank_settings'));
    echo $renderer->single_button(
            $returnurl,
            get_string('return', 'tool_coursebank'),
            'get'
    );
} else {
    $filtering->display_add();
    $filtering->display_active();
    echo $renderer->course_bank_downloads($response->body, $count->body->backupcount, $sort, $dir, $page, $perpage);
    echo $OUTPUT->paging_bar($count->body->backupcount, $page, $perpage, $url);
}

echo $OUTPUT->footer();
