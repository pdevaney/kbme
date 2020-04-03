<?php
/*
 * This file is part of Totara LMS
 *
 * Copyright (C) 2010 onwards Totara Learning Solutions LTD
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Piers Harding <piers@catalyst.net.nz>
 * @author Valerii Kuznetsov <valerii.kuznetsov@totaralms.com>
 * @package totara_message
 */

namespace totara_message\task;

/**
 * Send, dismiss queued messages.
 */
class update_messages_task extends \core\task\scheduled_task {
    // Age for expiring undismissed alerts - days.
    const TOTARA_MSG_CRON_DISMISS_ALERTS = 30;

    // Age for expiring undismissed tasks - days.
    const TOTARA_MSG_CRON_DISMISS_TASKS = 30;

    /**
     * Get a descriptive name for this task (shown to admins).
     *
     * @return string
     */
    public function get_name() {
        return get_string('updatemessagestask', 'totara_message');
    }

    /**
     * Do the job.
     * Throw exceptions on errors (the job will be retried).
     */
    public function execute() {
        global $DB, $CFG;
        require_once($CFG->dirroot . '/totara/message/lib.php');

        // Dismiss old alerts.
        $time = time() - (self::TOTARA_MSG_CRON_DISMISS_ALERTS * (24 * 60 * 60));
        $msgs = $this->tm_messages_get_by_time('totara_alert', $time);
        $deleted = array();
        foreach ($msgs as $msg) {
            tm_message_dismiss($msg->id);
            // Store message ids for bulk delete.
            if (!in_array($msg->id, $deleted)) {
                $deleted[] = $msg->id;
            }
        }

        // Dismiss old tasks.
        $time = time() - (self::TOTARA_MSG_CRON_DISMISS_TASKS * (24 * 60 * 60));
        $msgs = $this->tm_messages_get_by_time('totara_task', $time);
        foreach ($msgs as $msg) {
            tm_message_dismiss($msg->id);
            // Store message ids for bulk delete.
            if (!in_array($msg->id, $deleted)) {
                $deleted[] = $msg->id;
            }
        }

        // Delete the message records.
        $DB->delete_records_list('message', 'id', $deleted);
        // Tidy up orphaned metadata records - shouldn't be any - but odd things could happen with core messages cron.
        $sql = "SELECT mm.id
                FROM {message_metadata} mm
                LEFT JOIN {message} m ON mm.messageid = m.id
                LEFT JOIN {message_read} mr ON mm.messagereadid = mr.id
                WHERE m.id IS NULL AND mr.id IS NULL";
        $allidstodelete = $DB->get_fieldset_sql($sql);

        if (!empty($allidstodelete)) {
            // We may have really large numbers so split it up into smaller batches.
            $batchidstodelete = array_chunk($allidstodelete, 25000);

            foreach ($batchidstodelete as $idstodelete) {
                list($insql, $params) = $DB->get_in_or_equal($idstodelete);
                $sql = "DELETE
                        FROM {message_metadata}
                        WHERE id {$insql}";
                $DB->execute($sql, $params);
            }
        }

    }
    /**
     * get message ids by time
     *
     * @param string $type - message type
     * @param string $time_created - timecreated before
     * @return array of messages
     */
    public function tm_messages_get_by_time($type, $time_created) {
        global $DB;

        // Select only particular type.
        $processor = $DB->get_record('message_processors', array('name' => $type));
        if (empty($processor)) {
            return false;
        }

        // Hunt for messages.
        $msgs = $DB->get_records_sql("SELECT m.id
                                      FROM ({message} m INNER JOIN  {message_working} w ON m.id = w.unreadmessageid)
                                      WHERE w.processorid = ? AND m.timecreated < ?", array($processor->id, $time_created));
        return $msgs;
    }
}

