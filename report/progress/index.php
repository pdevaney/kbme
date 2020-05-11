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
 * Activity progress reports
 *
 * @package    report
 * @subpackage progress
 * @copyright  2008 Sam Marshall
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');
require_once($CFG->libdir . '/completionlib.php');

define('COMPLETION_REPORT_PAGE', 25);

// Get course
$id = required_param('course',PARAM_INT);
$course = $DB->get_record('course',array('id'=>$id));
if (!$course) {
    print_error('invalidcourseid');
}
$context = context_course::instance($course->id);

// Sort (default lastname, optionally firstname)
$sort = optional_param('sort','',PARAM_ALPHA);
$firstnamesort = $sort == 'firstname';

// CSV format
$format = optional_param('format','',PARAM_ALPHA);
$excel = $format == 'excelcsv';
$csv = $format == 'csv' || $excel;

// Paging
$start   = optional_param('start', 0, PARAM_INT);
$sifirst = optional_param('sifirst', 'all', PARAM_NOTAGS);
$silast  = optional_param('silast', 'all', PARAM_NOTAGS);
// TL-9502
$page    = optional_param('page', 0, PARAM_INT);

// Whether to show extra user identity information
$extrafields = get_extra_user_fields($context);
$leftcols = 1 + count($extrafields);

function csv_quote($value) {
    global $excel;
    if ($excel) {
        return core_text::convert('"'.str_replace('"',"'",$value).'"','UTF-8','UTF-16LE');
    } else {
        return '"'.str_replace('"',"'",$value).'"';
    }
}

$url = new moodle_url('/report/progress/index.php', array('course'=>$id));
if ($sort !== '') {
    $url->param('sort', $sort);
}
if ($format !== '') {
    $url->param('format', $format);
}
// TL-9502
if ($page !== 0) {
    $url->param('page', $page);
}
$PAGE->set_url($url);
$PAGE->set_pagelayout('report');

require_login($course);

// Check basic permission
require_capability('report/progress:view',$context);

// Get group mode
$group = groups_get_course_group($course,true); // Supposed to verify group
if ($group===0 && $course->groupmode==SEPARATEGROUPS) {
    require_capability('moodle/site:accessallgroups',$context);
}

// Get data on activities and progress of all users, and give error if we've
// nothing to display (no users or no activities)
$reportsurl = $CFG->wwwroot.'/course/report.php?id='.$course->id;
$completion = new completion_info($course);
$activities = $completion->get_activities();

// Generate where clause
$where = array();
$where_params = array();

if ($sifirst !== 'all') {
    $where[] = $DB->sql_like('u.firstname', ':sifirst', false);
    $where_params['sifirst'] = $sifirst.'%';
}

if ($silast !== 'all') {
    $where[] = $DB->sql_like('u.lastname', ':silast', false);
    $where_params['silast'] = $silast.'%';
}

// Show completion only for active user enrolments?
$showactiveonly = get_config('report_progress', 'showcompletiononlyactiveenrols');
// Get user match count
$total = $completion->get_num_tracked_users(implode(' AND ', $where), $where_params, $group, $showactiveonly);

// Total user count
$grandtotal = $completion->get_num_tracked_users('', array(), $group, $showactiveonly);

// Get user data
$progress = array();

if ($total) {
    $progress = $completion->get_progress_all(
        implode(' AND ', $where),
        $where_params,
        $group,
        $firstnamesort ? 'u.firstname ASC' : 'u.lastname ASC',
        $csv ? 0 : COMPLETION_REPORT_PAGE,
        $csv ? 0 : $page * COMPLETION_REPORT_PAGE, // TL-9502
        $context,
        $showactiveonly
    );
}

if ($csv && $grandtotal && count($activities)>0) { // Only show CSV if there are some users/actvs

    $shortname = format_string($course->shortname, true, array('context' => $context));
    // Totara: Send the content-disposition header with properly encoded filename.
    require_once($CFG->libdir.'/filelib.php');
    $filename = 'progress.'.
        preg_replace('/[^a-z0-9-]/','_',core_text::strtolower(strip_tags($shortname))).'.csv';
    header(make_content_disposition('attachment', $filename));
    // Unicode byte-order mark for Excel
    if ($excel) {
        header('Content-Type: text/csv; charset=UTF-16LE');
        print chr(0xFF).chr(0xFE);
        $sep="\t".chr(0);
        $line="\n".chr(0);
    } else {
        header('Content-Type: text/csv; charset=UTF-8');
        $sep=",";
        $line="\n";
    }
} else {

    // Navigation and header
    $strreports = get_string("reports");
    $strcompletion = get_string('activitycompletion', 'completion');

    $PAGE->set_title($strcompletion);
    $PAGE->set_heading($course->fullname);
    echo $OUTPUT->header();
    $PAGE->requires->js('/report/progress/textrotate.js');
    $PAGE->requires->js_function_call('textrotate_init', null, true);

    // Handle groups (if enabled)
    groups_print_course_menu($course,$CFG->wwwroot.'/report/progress/?course='.$course->id);
}

if (count($activities)==0) {
    echo $OUTPUT->container(get_string('err_noactivities', 'completion'), 'errorbox errorboxcontent');
    echo $OUTPUT->footer();
    exit;
}

// If no users in this course what-so-ever
if (!$grandtotal) {
    echo $OUTPUT->container(get_string('err_nousers', 'completion'), 'errorbox errorboxcontent');
    echo $OUTPUT->footer();
    exit;
}

// Build link for paging
$link = $CFG->wwwroot.'/report/progress/?course='.$course->id;
if (strlen($sort)) {
    $link .= '&amp;sort='.$sort;
}
$link .= '&amp;start=';

// Build the the page by Initial bar
$initials = array('first', 'last');
$alphabet = explode(',', get_string('alphabet', 'langconfig'));

$pagingbar = '';
foreach ($initials as $initial) {
    $var = 'si'.$initial;

    $othervar = $initial == 'first' ? 'silast' : 'sifirst';
    $othervar = $$othervar != 'all' ? "&amp;{$othervar}={$$othervar}" : '';

    $pagingbar .= ' <div class="initialbar '.$initial.'initial">';
    $pagingbar .= get_string($initial.'name').':&nbsp;';

    if ($$var == 'all') {
        $pagingbar .= '<strong>'.get_string('all').'</strong> ';
    }
    else {
        $pagingbar .= "<a href=\"{$link}{$othervar}\">".get_string('all').'</a> ';
    }

    foreach ($alphabet as $letter) {
        if ($$var === $letter) {
            $pagingbar .= '<strong>'.$letter.'</strong> ';
        }
        else {
            $pagingbar .= "<a href=\"$link&amp;$var={$letter}{$othervar}\">$letter</a> ";
        }
    }

    $pagingbar .= '</div>';
}

// Do we need a paging bar?
if ($total > COMPLETION_REPORT_PAGE) {
    // TL-9502 - replaced custom coded paging bar with an output component
    $sistrings = array();
    if ($sifirst != 'all') {
        $sistrings[] =  "sifirst={$sifirst}";
    }
    if ($silast != 'all') {
        $sistrings[] =  "silast={$silast}";
    }
    $sistring = !empty($sistrings) ? '&amp;'.implode('&amp;', $sistrings) : '';

    $pagingbarobj = new paging_bar($total, $page, COMPLETION_REPORT_PAGE, $link . $sistring);
    $pagingbar .= $OUTPUT->render($pagingbarobj);
}

// Okay, let's draw the table of progress info,

// Start of table
if (!$csv) {
    print '<br class="clearer"/>'; // ugh

    print $pagingbar;

    if (!$total) {
        echo $OUTPUT->heading(get_string('nothingtodisplay'));
        echo $OUTPUT->footer();
        exit;
    }

    print '<div id="completion-progress-wrapper" class="no-overflow">';
    print '<table id="completion-progress" class="generaltable flexible boxaligncenter" style="text-align:left"><thead><tr style="vertical-align:top">';

    // User heading / sort option
    print '<th scope="col" class="completion-sortchoice">';

    $sistring = "&amp;silast={$silast}&amp;sifirst={$sifirst}";

    if ($firstnamesort) {
        print
            get_string('firstname')." / <a href=\"./?course={$course->id}{$sistring}\">".
            get_string('lastname').'</a>';
    } else {
        print "<a href=\"./?course={$course->id}&amp;sort=firstname{$sistring}\">".
            get_string('firstname').'</a> / '.
            get_string('lastname');
    }
    print '</th>';

    // Print user identity columns
    foreach ($extrafields as $field) {
        echo '<th scope="col" class="completion-identifyfield">' .
                get_user_field_name($field) . '</th>';
    }
} else {
    foreach ($extrafields as $field) {
        echo $sep . csv_quote(get_user_field_name($field));
    }
}

// Activities
$formattedactivities = array();
foreach($activities as $activity) {
    $datepassed = $activity->completionexpected && $activity->completionexpected <= time();
    $datepassedclass = $datepassed ? 'completion-expired' : '';

    if ($activity->completionexpected) {
        $datetext=userdate($activity->completionexpected,get_string('strftimedate','langconfig'));
    } else {
        $datetext='';
    }

    // Some names (labels) come URL-encoded and can be very long, so shorten them
    $displayname = format_string($activity->name, true, array('context' => $activity->context));

    if ($csv) {
        print $sep.csv_quote($displayname).$sep.csv_quote($datetext);
    } else {
        $shortenedname = shorten_text($displayname);
        print '<th scope="col" class="'.$datepassedclass.'">'.
            '<a href="'.$CFG->wwwroot.'/mod/'.$activity->modname.
            '/view.php?id='.$activity->id.'" title="' . s($displayname) . '" class="activity">'.
            '<span class="completion-activityname">'.$shortenedname.'</span>'.
            $OUTPUT->flex_icon('mod_' . $activity->modname . '|icon', array('alt' => s(get_string('modulename', $activity->modname)))) .
            '</a>';
        if ($activity->completionexpected) {
            print '<div class="completion-expected"><span>'.$datetext.'</span></div>';
        }
        print '</th>';
    }
    $formattedactivities[$activity->id] = (object)array(
        'datepassedclass' => $datepassedclass,
        'displayname' => $displayname,
    );
}

if ($csv) {
    print $line;
} else {
    print '</tr></thead><tbody>';
}

// Row for each user
foreach($progress as $user) {
    // User name
    if ($csv) {
        print csv_quote(fullname($user));
        foreach ($extrafields as $field) {
            echo $sep . csv_quote($user->{$field});
        }
    } else {
        print '<tr><th scope="row"><a href="'.$CFG->wwwroot.'/user/view.php?id='.
            $user->id.'&amp;course='.$course->id.'">'.fullname($user).'</a></th>';
        foreach ($extrafields as $field) {
            echo '<td>' . s($user->{$field}) . '</td>';
        }
    }

    // Progress for each activity
    foreach($activities as $activity) {

        // Get progress information and state
        if (array_key_exists($activity->id,$user->progress)) {
            $thisprogress=$user->progress[$activity->id];
            $state=$thisprogress->completionstate;
            if (!empty($thisprogress->timecompleted)) {
                $date = userdate($thisprogress->timecompleted);
            } else if (!empty($thisprogress->timemodified)) {
                $date = userdate($thisprogress->timemodified);
            } else {
                $date = '';
            }
        } else {
            $state=COMPLETION_INCOMPLETE;
            $date='';
        }

        // Work out how it corresponds to an icon
        switch($state) {
            case COMPLETION_INCOMPLETE : $completiontype='n'; break;
            case COMPLETION_COMPLETE : $completiontype='y'; break;
            case COMPLETION_COMPLETE_PASS : $completiontype='pass'; break;
            case COMPLETION_COMPLETE_FAIL : $completiontype='fail'; break;
        }

        $completionicon='completion-'.
            ($activity->completion==COMPLETION_TRACKING_AUTOMATIC ? 'auto' : 'manual').
            '-'.$completiontype;

        $describe = get_string('completion-' . $completiontype, 'completion');
        $a=new StdClass;
        $a->state=$describe;
        $a->date=$date;
        $a->user=fullname($user);
        $a->activity = $formattedactivities[$activity->id]->displayname;
        $fulldescribe=get_string('progress-title','completion',$a);

        if ($csv) {
            print $sep.csv_quote($describe).$sep.csv_quote($date);
        } else {
            $icon = $OUTPUT->flex_icon("core|i/" . $completionicon, array('alt' => $fulldescribe));
            echo '<td class="completion-progresscell ' . $formattedactivities[$activity->id]->datepassedclass . '">' . $icon . '</td>';
        }
    }

    if ($csv) {
        print $line;
    } else {
        print '</tr>';
    }
}

if ($csv) {
    exit;
}
print '</tbody></table>';
print '</div>';
print $pagingbar;

print '<ul class="progress-actions"><li><a href="index.php?course='.$course->id.
    '&amp;format=csv">'.get_string('csvdownload','completion').'</a></li>
    <li><a href="index.php?course='.$course->id.'&amp;format=excelcsv">'.
    get_string('excelcsvdownload','completion').'</a></li></ul>';

echo $OUTPUT->footer();

