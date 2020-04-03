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
 * Output rendering for the plugin.
 *
 * @package     tool_task
 * @copyright   2014 Damyon Wiese
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Implements the plugin renderer
 *
 * @copyright 2014 Damyon Wiese
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class tool_task_renderer extends plugin_renderer_base {
    /**
     * This function will render one beautiful table with all the scheduled tasks.
     *
     * @param \core\task\scheduled_task[] $tasks - list of all scheduled tasks.
     * @return string HTML to output.
     */
    public function scheduled_tasks_table($tasks) {
        global $CFG;

        if (!empty($CFG->debugallowscheduledtaskoverride)) {
            $nextcronallaction = new moodle_url('/admin/tool/task/scheduledtasks.php',
                array('nextcronall' => 1, 'sesskey' => sesskey()));
            $nextcronalltitle = get_string('nextcronall', 'tool_task');
            $nextcronallhtml = html_writer::start_tag('div',
                array('class' => 'tool-task-next-cron-all-link'));
            $nextcronallhtml .= html_writer::start_tag('form',
                array('action' => $nextcronallaction, 'method' => 'post'));
            $nextcronallhtml .= html_writer::empty_tag('input',
                array('type' => 'submit', 'name' => 'submit', 'value' => $nextcronalltitle));
            $nextcronallhtml .= html_writer::end_tag('form');
            $nextcronallhtml .= html_writer::end_tag('div');
        } else {
            $nextcronallhtml = "";
        }

        $table = new html_table();
        $table->head  = array(get_string('name'),
                              get_string('component', 'tool_task'),
                              get_string('edit'),
                              get_string('lastruntime', 'tool_task'),
                              get_string('nextruntime', 'tool_task'),
                              get_string('taskscheduleminute', 'tool_task'),
                              get_string('taskschedulehour', 'tool_task'),
                              get_string('taskscheduleday', 'tool_task'),
                              get_string('taskscheduledayofweek', 'tool_task'),
                              get_string('taskschedulemonth', 'tool_task'),
                              get_string('faildelay', 'tool_task'),
                              get_string('default', 'tool_task'));
        if (!empty($CFG->debugallowscheduledtaskoverride)) {
            $table->head[] = get_string('nextcron', 'tool_task');
        }
        $table->attributes['class'] = 'admintable generaltable';
        $data = array();
        $yes = get_string('yes');
        $no = get_string('no');
        $never = get_string('never');
        $asap = get_string('asap', 'tool_task');
        $disabledstr = get_string('taskdisabled', 'tool_task');
        $plugindisabledstr = get_string('plugindisabled', 'tool_task');
        foreach ($tasks as $task) {
            $customised = $task->is_customised() ? $no : $yes;
            if (empty($CFG->preventscheduledtaskchanges)) {
                $configureurl = new moodle_url('/admin/tool/task/scheduledtasks.php', array('action'=>'edit', 'task' => get_class($task)));
                $editlink = $this->action_icon($configureurl, new pix_icon('t/edit', get_string('edittaskschedule', 'tool_task', $task->get_name())));
            } else {
                $editlink = $this->render(new pix_icon('t/locked', get_string('scheduledtaskchangesdisabled', 'tool_task')));
            }

            $namecell = new html_table_cell($task->get_name() . "\n" . html_writer::tag('span', '\\'.get_class($task), array('class' => 'task-class')));
            $namecell->header = true;

            $component = $task->get_component();
            $plugininfo = null;
            list($type, $plugin) = core_component::normalize_component($component);
            if ($type === 'core') {
                $componentcell = new html_table_cell(get_string('corecomponent', 'tool_task'));
            } else {
                if ($plugininfo = core_plugin_manager::instance()->get_plugin_info($component)) {
                    $plugininfo->init_display_name();
                    $componentcell = new html_table_cell($plugininfo->displayname);
                } else {
                    $componentcell = new html_table_cell($component);
                }
            }

            $lastrun = $task->get_last_run_time() ? userdate($task->get_last_run_time()) : $never;
            $nextrun = $task->get_next_run_time();
            $disabled = false;
            if ($plugininfo && $plugininfo->is_enabled() === false && !$task->get_run_if_component_disabled()) {
                $disabled = true;
                $nextrun = $plugindisabledstr;
            } else if ($task->get_disabled()) {
                $disabled = true;
                $nextrun = $disabledstr;
            } else if ($nextrun > time()) {
                $nextrun = userdate($nextrun);
            } else {
                $nextrun = $asap;
            }

            $rowitems = array(
                        $namecell,
                        $componentcell,
                        new html_table_cell($editlink),
                        new html_table_cell($lastrun),
                        new html_table_cell($nextrun),
                        new html_table_cell($task->get_minute()),
                        new html_table_cell($task->get_hour()),
                        new html_table_cell($task->get_day()),
                        new html_table_cell($task->get_day_of_week()),
                        new html_table_cell($task->get_month()),
                        new html_table_cell($task->get_fail_delay()),
                        new html_table_cell($customised));

            if (!empty($CFG->debugallowscheduledtaskoverride)) {
                $nextcronurl = new moodle_url('/admin/tool/task/scheduledtasks.php',
                    array('nextcron' => get_class($task), 'sesskey' => sesskey()));
                $nextcronlink = $this->action_icon($nextcronurl, new pix_icon('t/reload',
                    get_string('nextcrontask', 'tool_task', $task->get_name())));
                $rowitems[] = new html_table_cell($nextcronlink);
            }

            $row = new html_table_row($rowitems);

            if ($disabled) {
                $row->attributes['class'] = 'disabled';
            }
            $data[] = $row;
        }
        $table->data = $data;
        return $nextcronallhtml . html_writer::table($table);
    }
}
