<?php
// This file is part of the sftp plugin for Moodle
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
 * @package    local_sftp
 * @copyright  Solin
 * @author     Bartosz, Solin
 * @license    
 */

$string['pluginname'] = 'Export reports to SFTP';
$string['plugintitle'] = 'Register';
$string['sftp:groupowner'] = 'A user that can control groups';
$string['sftp:manage'] = 'A user that can control the sftp plugin';

// Settings.

$string['sftp_location'] = 'SFTP location';
$string['sftp_location_desc'] = '';

$string['sftp_username'] = 'SFTP user name';
$string['sftp_username_desc'] = '';

$string['sftp_password'] = 'SFTP password';
$string['sftp_password_desc'] = '';

$string['sftp_destination_folder'] = 'SFTP destination folder';
$string['sftp_destination_folder_desc'] = '';

$string['store_csv_filepath'] = 'Server folder to store CSV files';
$string['store_csv_filepath_desc'] = '';

$string['store_csv_keep_copy'] = 'How many recent report files should be kept';
$string['store_csv_keep_copy_desc'] = '';

$string['email_for_errors'] = 'E-mail address to receive errors';
$string['email_for_errors_desc'] = '';

$string['retry_time'] = 'Retry time';
$string['retry_time_desc'] = 'Time in minutes. Zero means no retry.';

$string['time'] = 'Schedule time';
$string['time_desc'] = '';

$string['time_value'] = 'Value';
$string['time_value_desc'] = '';

$string['time_daily'] = 'Daily';
$string['time_weekly'] = 'Weekly';
$string['time_monthly'] = 'Monthly';
$string['time_hourly'] = 'Every X hours';
$string['time_minutely'] = 'Every X minutes';

$string['reports'] = 'Reports';
$string['reports_desc'] = 'Choose which reports will be stored on the folder and on the SFTP acocunt';

$string['connection_status'] = 'Connection status: ';
$string['connected'] = 'Connected';
$string['login_failed'] = 'Login failed';
$string['upload_file_success'] = 'upload test file succeeded';
$string['upload_file_failed'] = 'upload test file failed - please check destination folder permissions';

// Events.

$string['email_subject'] = 'Reports to SFTP - errors occured';
$string['sftp_missing_config'] = 'Missing SFTP connection settings';
$string['sftp_connection_error'] = 'SFTP connection failed';
$string['report_not_stored'] = 'Report file not stored';
$string['report_not_send'] = 'Report file not send';

$string['event_report_send'] = 'Report sent';
$string['event_report_not_send'] = 'FAIL - report not sent';
$string['event_report_store'] = 'Report stored';
$string['event_report_not_store'] = 'FAIL - report not stored';