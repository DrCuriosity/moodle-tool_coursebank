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
 * Strings for tool_coursestore
 *
 * @package    tool_coursestore
 * @author     Ghada El-Zoghbi <ghada@catalyst-au.net>
 * @copyright  2015 Catalys IT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Course Store';
$string['backupfailed'] = 'Failed sending backup %s.';
$string['sendcoursebackups'] = 'External course backups';
$string['noaccesstofeature'] = 'Sorry, only admin or CLI has access to this feature.';
$string['settingspage'] = 'Configuration';
$string['settings_header'] = 'Course Store configuration options';
$string['settings_url'] = 'Target URL';
$string['settings_url_desc'] = 'Location of the target backup server.';
$string['settings_chunksize'] = 'Chunk size (kB)';
$string['settings_chunksize_desc'] = 'Size (in Kilobytes) of individual backup chunks to be sent to the backup server.';
$string['settings_timeout'] = 'Web service time out';
$string['settings_timeout_desc'] = 'Time out (in seconds) for individual HTTP requests.';
$string['settings_conntimeout'] = 'Connection time out';
$string['settings_conntimeout_desc'] = 'Time out (in seconds) for HTTP connection attempts.';
$string['settings_enable'] = 'Active';
$string['settings_enable_desc'] = 'Enable or disable sending of course backups.';
$string['settings_enablestring'] = 'Enable';
$string['settings_disablestring'] = 'Disable';
$string['settings_maxhttprequests'] = 'HTTP request limit';
$string['settings_maxhttprequests_desc'] = 'Number of unsuccessful HTTP requests to make before giving up on an individual file chunk.' ;
$string['settings_maxattempts'] = 'Maximum transfer attempts';
$string['settings_maxattempts_desc'] = 'Maximum number of times to attempt to continue a transfer before marking the transfer as failed.';
$string['conncheckfail'] = 'Connection error. Please confirm that your course bank settings and network configuration are correct.';
$string['connchecksuccess'] = 'Connection check passed!';
$string['speedtestsuccess'] = 'Connection speed test passed!';
$string['speedtestfail'] = 'Connection error. Please confirm that your course bank settings and network configuration are correct.';
$string['speedtestslow'] = 'Outbound transfers are very slow. The test transfer speed was approximately ';
$string['connchecktitle'] = 'Connection check';
$string['speedtesttitle'] = 'Connection speed test';
$string['backupsummary'] = 'Backups summary';
$string['statuserror'] = 'Error';
$string['statusinprogress'] = 'Transfer in progress';
$string['statusnotstarted'] = 'Transfer pending';
$string['statusfinished'] = 'Transfer complete';
// Error codes.
$string['ERROR_TIMEOUT']              = 'The connection has timed out.';
$string['ERROR_MAX_ATTEMPTS_REACHED'] = 'Maximum attempts reached.';
// etc...
