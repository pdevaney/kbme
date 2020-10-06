<?php

namespace local_sftp\task;

/**
 * Clean up deleted users still assigned to appraisals.
 */
class sftp_report extends \core\task\scheduled_task {

    /**
     * Get a descriptive name for this task (shown to admins).
     *
     * @return string
     */
    public function get_name() {
        return get_string('pluginname', 'local_sftp');
    }

    /**
     * Periodic cron cleanup.
     */
    public function execute() {
        \core_php_time_limit::raise(0);
        raise_memory_limit(MEMORY_EXTRA);

        \local_sftp\lib::export();
    }
}
