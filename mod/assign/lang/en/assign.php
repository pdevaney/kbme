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
 * Strings for component 'assign', language 'en'
 *
 * @package   mod_assign
 * @copyright 2012 NetSpot {@link http://www.netspot.com.au}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['activityoverview'] = 'You have assignments that need attention';
$string['addsubmission'] = 'Add submission';
$string['addattempt'] = 'Allow another attempt';
$string['addnewattempt'] = 'Add a new attempt';
$string['addnewattempt_help'] = 'This will create a new blank submission for you to work on.';
$string['addnewattemptfromprevious'] = 'Add a new attempt based on previous submission';
$string['addnewattemptfromprevious_help'] = 'This will copy the contents of your previous submission to a new submission for you to work on.';
$string['allocatedmarker'] = 'Allocated Marker';
$string['allocatedmarker_help'] = 'Marker allocated to this submission.';
$string['allowsubmissions'] = 'Allow the user to continue making submissions to this assignment.';
$string['allowsubmissionsshort'] = 'Allow submission changes';
$string['allowsubmissionsfromdate'] = 'Allow submissions from';
$string['allowsubmissionsfromdate_help'] = 'If enabled, learners will not be able to submit before this date. If disabled, learners will be able to start submitting right away.';
$string['allowsubmissionsfromdatesummary'] = 'This assignment will accept submissions from <strong>{$a}</strong>';
$string['allowsubmissionsanddescriptionfromdatesummary'] = 'The assignment details and submission form will be available from <strong>{$a}</strong>';
$string['alwaysshowdescription'] = 'Always show description';
$string['alwaysshowdescription_help'] = 'If disabled, the assignment description above will only become visible to learners at the **Allow submissions from** date.';
$string['applytoteam'] = 'Apply grades and feedback to entire group';
$string['assign:addinstance'] = 'Add a new assignment';
$string['assign:exportownsubmission'] = 'Export own submission';
$string['assign:editothersubmission'] = 'Edit another learner\'s submission';
$string['assign:grade'] = 'Grade assignment';
$string['assign:grantextension'] = 'Grant extension';
$string['assign:manageallocations'] = 'Manage markers allocated to submissions';
$string['assign:managegrades'] = 'Review and release grades';
$string['assign:receivegradernotifications'] = 'Receive grader submission notifications';
$string['assign:releasegrades'] = 'Release grades';
$string['assign:revealidentities'] = 'Reveal learner identities';
$string['assign:reviewgrades'] = 'Review grades';
$string['assign:viewblinddetails'] = 'View learner identities when blind marking is enabled';
$string['assign:viewgrades'] = 'View grades';
$string['assign:submit'] = 'Submit assignment';
$string['assign:view'] = 'View assignment';
$string['assignfeedback'] = 'Feedback plugin';
$string['assignfeedbackpluginname'] = 'Feedback plugin';
$string['assignmentisdue'] = 'Assignment is due';
$string['assignmentmail'] = '{$a->grader} has posted some feedback on your
assignment submission for \'{$a->assignment}\'

You can see it appended to your assignment submission:

    {$a->url}';
$string['assignmentmailhtml'] = '<p>{$a->grader} has posted some feedback on your
assignment submission for \'<i>{$a->assignment}</i>\'.</p>
<p>You can see it appended to your <a href="{$a->url}">assignment submission</a>.</p>';
$string['assignmentmailsmall'] = '{$a->grader} has posted some feedback on your
assignment submission for \'{$a->assignment}\' You can see it appended to your submission';
$string['assignmentname'] = 'Assignment name';
$string['assignmentplugins'] = 'Assignment plugins';
$string['assignmentsperpage'] = 'Assignments per page';
$string['assignsubmission'] = 'Submission plugin';
$string['assignsubmissionpluginname'] = 'Submission plugin';
$string['attemptheading'] = 'Attempt {$a->attemptnumber}: {$a->submissionsummary}';
$string['attemptnumber'] = 'Attempt number';
$string['attempthistory'] = 'Previous attempts';
$string['attemptsettings'] = 'Attempt settings';
$string['attemptreopenmethod'] = 'Attempts reopened';
$string['attemptreopenmethod_help'] = 'Determines how learner submission attempts are reopened. The available options are:

* **Never**: The learner submission cannot be reopened.
* **Manually**: The learner submission can be reopened by a trainer.
* **Automatically until pass**: The learner submission is automatically reopened until the learner achieves the grade to pass value set in the gradebook (Gradebook setup section) for this assignment.';
$string['attemptreopenmethod_manual'] = 'Manually';
$string['attemptreopenmethod_none'] = 'Never';
$string['attemptreopenmethod_untilpass'] = 'Automatically until pass';
$string['availability'] = 'Availability';
$string['backtoassignment'] = 'Back to assignment';
$string['batchoperationsdescription'] = 'With selected...';
$string['batchoperationconfirmlock'] = 'Lock all selected submissions?';
$string['batchoperationconfirmgrantextension'] = 'Grant an extension to all selected submissions?';
$string['batchoperationconfirmunlock'] = 'Unlock all selected submissions?';
$string['batchoperationconfirmreverttodraft'] = 'Revert selected submissions to draft?';
$string['batchoperationconfirmaddattempt'] = 'Allow another attempt for selected submissions?';
$string['batchoperationconfirmsetmarkingworkflowstate'] = 'Set marking workflow state for all selected submissions?';
$string['batchoperationconfirmsetmarkingallocation'] = 'Set marking allocation for all selected submissions?';
$string['batchoperationlock'] = 'lock submissions';
$string['batchoperationunlock'] = 'unlock submissions';
$string['batchoperationreverttodraft'] = 'revert submissions to draft';
$string['batchsetallocatedmarker'] = 'Set allocated marker for {$a} selected user(s).';
$string['batchsetmarkingworkflowstateforusers'] = 'Set marking workflow state for {$a} selected user(s).';
$string['blindmarking'] = 'Blind marking';
$string['blindmarking_help'] = 'Blind marking hides the identity of learners from markers. Blind marking settings will be locked once a submission or grade has been made in relation to this assignment.';
$string['changegradewarning'] = 'This assignment has graded submissions and changing the grade will not automatically re-calculate existing submission grades. You must re-grade all existing submissions, if you wish to change the grade.';
$string['choosegradingaction'] = 'Grading action';
$string['choosemarker'] = 'Choose...';
$string['chooseoperation'] = 'Choose operation';
$string['comment'] = 'Comment';
$string['completionsubmit'] = 'Learner must submit to this activity to complete it';
$string['completionusegradewithoutgradeenabled'] = 'To enable this setting, you must select a Grade Type or enable the default Feedback Type ({$a})';
$string['conversionexception'] = 'Could not convert assignment. Exception was: {$a}.';
$string['configshowrecentsubmissions'] = 'Everyone can see notifications of submissions in recent activity reports.';
$string['confirmsubmission'] = 'Are you sure you want to submit your work for grading? You will not be able to make any more changes.';
$string['confirmbatchgradingoperation'] = 'Are you sure you want to {$a->operation} for {$a->count} learners?';
$string['couldnotconvertgrade'] = 'Could not convert assignment grade for user {$a}.';
$string['couldnotconvertsubmission'] = 'Could not convert assignment submission for user {$a}.';
$string['couldnotcreatecoursemodule'] = 'Could not create course module.';
$string['couldnotcreatenewassignmentinstance'] = 'Could not create new assignment instance.';
$string['couldnotfindassignmenttoupgrade'] = 'Could not find old assignment instance to upgrade.';
$string['currentgrade'] = 'Current grade in gradebook';
$string['currentattempt'] = 'This is attempt {$a}.';
$string['currentattemptof'] = 'This is attempt {$a->attemptnumber} ( {$a->maxattempts} attempts allowed ).';
$string['cutoffdate'] = 'Cut-off date';
$string['cutoffdate_help'] = 'If set, the assignment will not accept submissions after this date without an extension.';
$string['cutoffdatevalidation'] = 'The cut-off date cannot be earlier than the due date.';
$string['cutoffdatefromdatevalidation'] = 'Cut-off date must be after the allow submissions from date.';
$string['defaultsettings'] = 'Default assignment settings';
$string['defaultsettings_help'] = 'These settings define the defaults for all new assignments.';
$string['defaultteam'] = 'Default group';
$string['deleteallsubmissions'] = 'Delete all submissions';
$string['description'] = 'Description';
$string['downloadall'] = 'Download all submissions';
$string['download all submissions'] = 'Download all submissions in a zip file.';
$string['duedate'] = 'Due date';
$string['duedate_help'] = 'This is when the assignment is due. Submissions will still be allowed after this date but any assignments submitted after this date are marked as late. To prevent submissions after a certain date set the assignment cut off date.';
$string['duedateno'] = 'No due date';
$string['submissionempty'] = 'Nothing was submitted';
$string['submissionmodified'] = 'You have existing submission data. Please leave this page and try again.';
$string['submissionmodifiedgroup'] = 'The submission has been modified by somebody else. Please leave this page and try again.';
$string['duedatereached'] = 'The due date for this assignment has now passed';
$string['duedatevalidation'] = 'Due date must be after the allow submissions from date.';
$string['editattemptfeedback'] = 'Edit the grade and feedback for attempt number {$a}.';
$string['editingpreviousfeedbackwarning'] = 'You are editing the feedback for a previous attempt. This is attempt {$a->attemptnumber} out of {$a->totalattempts}.';
$string['editsubmission'] = 'Edit submission';
$string['editsubmissionother'] = 'Edit submission for {$a}';
$string['editsubmission_help'] = 'Make changes to your submission.';
$string['editingstatus'] = 'Editing status';
$string['editaction'] = 'Actions...';
$string['eventallsubmissionsdownloaded'] = 'All the submissions are being downloaded.';
$string['eventassessablesubmitted'] = 'A submission has been submitted.';
$string['eventbatchsetmarkerallocationviewed'] = 'Batch set marker allocation viewed';
$string['eventbatchsetworkflowstateviewed'] = 'Batch set workflow state viewed.';
$string['eventextensiongranted'] = 'An extension has been granted.';
$string['eventfeedbackupdated'] = 'Feedback updated';
$string['eventfeedbackviewed'] = 'Feedback viewed';
$string['eventgradingformviewed'] = 'Grading form viewed';
$string['eventgradingtableviewed'] = 'Grading table viewed';
$string['eventidentitiesrevealed'] = 'The identities have been revealed.';
$string['eventmarkerupdated'] = 'The allocated marker has been updated.';
$string['eventrevealidentitiesconfirmationpageviewed'] = 'Reveal identities confirmation page viewed.';
$string['eventstatementaccepted'] = 'The user has accepted the statement of the submission.';
$string['eventsubmissionconfirmationformviewed'] = 'Submission confirmation form viewed.';
$string['eventsubmissioncreated'] = 'Submission created.';
$string['eventsubmissionduplicated'] = 'The user duplicated his submission.';
$string['eventsubmissionformviewed'] = 'Submission form viewed.';
$string['eventsubmissiongraded'] = 'The submission has been graded.';
$string['eventsubmissionlocked'] = 'The submissions have been locked for a user.';
$string['eventsubmissionstatusupdated'] = 'The status of the submission has been updated.';
$string['eventsubmissionstatusviewed'] = 'The status of the submission has been viewed.';
$string['eventsubmissionunlocked'] = 'The submissions have been unlocked for a user.';
$string['eventsubmissionupdated'] = 'Submission updated.';
$string['eventsubmissionviewed'] = 'Submission viewed.';
$string['eventworkflowstateupdated'] = 'The state of the workflow has been updated.';
$string['extensionduedate'] = 'Extension due date';
$string['extensionnotafterduedate'] = 'Extension date must be after the due date';
$string['extensionnotafterfromdate'] = 'Extension date must be after the allow submissions from date';
$string['gradecanbechanged'] = 'Grade can be changed';
$string['gradersubmissionupdatedtext'] = '{$a->username} has updated their assignment submission
for \'{$a->assignment}\' at {$a->timeupdated}

It is available here:

    {$a->url}';
$string['gradersubmissionupdatedhtml'] = '{$a->username} has updated their assignment submission
for <i>\'{$a->assignment}\'  at {$a->timeupdated}</i><br /><br />
It is <a href="{$a->url}">available on the web site</a>.';
$string['gradersubmissionupdatedsmall'] = '{$a->username} has updated their submission for assignment {$a->assignment}.';
$string['gradeuser'] = 'Grade {$a}';
$string['grantextension'] = 'Grant extension';
$string['grantextensionforusers'] = 'Grant extension for {$a} learners';
$string['enabled'] = 'Enabled';
$string['errornosubmissions'] = 'There are no submissions to download';
$string['errorquickgradingvsadvancedgrading'] = 'The grades were not saved because this assignment is currently using advanced grading';
$string['errorrecordmodified'] = 'The grades were not saved because someone has modified one or more records more recently than when you loaded the page.';
$string['feedback'] = 'Feedback';
$string['feedbackavailabletext'] = '{$a->username} has posted some feedback on your
assignment submission for \'{$a->assignment}\'

You can see it appended to your assignment submission:

    {$a->url}';
$string['feedbackavailablehtml'] = '{$a->username} has posted some feedback on your
assignment submission for \'<i>{$a->assignment}</i>\'<br /><br />
You can see it appended to your <a href="{$a->url}">assignment submission</a>.';
$string['feedbackavailablesmall'] = '{$a->username} has given feedback for assignment {$a->assignment}';
$string['feedbackplugins'] = 'Feedback plugins';
$string['feedbackpluginforgradebook'] = 'Feedback plugin that will push comments to the gradebook';
$string['feedbackpluginforgradebook_help'] = 'Only one assignment feedback plugin can push feedback into the gradebook.';
$string['feedbackplugin'] = 'Feedback plugin';
$string['feedbacksettings'] = 'Feedback settings';
$string['filesubmissions'] = 'File submissions';
$string['filter'] = 'Filter';
$string['filternone'] = 'No filter';
$string['filternotsubmitted'] = 'Not submitted';
$string['filterrequiregrading'] = 'Requires grading';
$string['filtersubmitted'] = 'Submitted';
$string['gradedby'] = 'Graded by';
$string['graded'] = 'Graded';
$string['gradedon'] = 'Graded on';
$string['gradebelowzero'] = 'Grade must be greater than or equal to zero.';
$string['gradeabovemaximum'] = 'Grade must be less than or equal to {$a}.';
$string['gradelocked'] = 'This grade is locked or overridden in the gradebook.';
$string['gradeoutof'] = 'Grade out of {$a}';
$string['gradeoutofhelp'] = 'Grade';
$string['gradeoutofhelp_help'] = 'Enter the grade for the learner\'s submission here. You may include decimals.';
$string['gradestudent'] = 'Grade learner: (id={$a->id}, fullname={$a->fullname}). ';
$string['grading'] = 'Grading';
$string['gradingchangessaved'] = 'The grade changes were saved';
$string['gradingmethodpreview'] = 'Grading criteria';
$string['gradingoptions'] = 'Options';
$string['gradingstatus'] = 'Grading status';
$string['gradingstudent'] = 'Grading learner';
$string['gradingsummary'] = 'Grading summary';
$string['hideshow'] = 'Hide/Show';
$string['hiddenuser'] = 'Participant ';
$string['instructionfiles'] = 'Instruction files';
$string['introattachments'] = 'Additional files';
$string['introattachments_help'] = 'Additional files for use in the assignment, such as answer templates, may be added. Download links for the files will then be displayed on the assignment page under the description.';
$string['invalidgradeforscale'] = 'The grade supplied was not valid for the current scale';
$string['invalidfloatforgrade'] = 'The grade provided could not be understood: {$a}';
$string['lastmodifiedsubmission'] = 'Last modified (submission)';
$string['lastmodifiedgrade'] = 'Last modified (grade)';
$string['latesubmissions'] = 'Late submissions';
$string['latesubmissionsaccepted'] = 'Allowed until {$a}';
$string['locksubmissionforstudent'] = 'Prevent any more submissions for learner: (id={$a->id}, fullname={$a->fullname}).';
$string['locksubmissions'] = 'Lock submissions';
$string['manageassignfeedbackplugins'] = 'Manage assignment feedback plugins';
$string['manageassignsubmissionplugins'] = 'Manage assignment submission plugins';
$string['marker'] = 'Marker';
$string['markerfilter'] = 'Marker filter';
$string['markerfilternomarker'] = 'No marker';
$string['markingallocation'] = 'Use marking allocation';
$string['markingallocation_help'] = 'If enabled together with marking workflow, markers can be allocated to particular learners.';
$string['markingworkflow'] = 'Use marking workflow';
$string['markingworkflow_help'] = 'If enabled, marks will go through a series of workflow stages before being released to learners. This allows for multiple rounds of marking and allows marks to be released to all learners at the same time.';
$string['markingworkflowstate'] = 'Marking workflow state';
$string['markingworkflowstate_help'] = 'Possible workflow states may include (depending on your permissions):

* **Not marked**: The marker has not yet started.
* **In marking**: The marker has started but not yet finished.
* **Marking completed**: The marker has finished but might need to go back for checking/corrections.
* **In review**: The marking is now with the trainer in charge for quality checking.
* **Ready for release**: The trainer in charge is satisfied with the marking but wait before giving learners access to the marking.
* **Released**: The learner can access the grades/feedback.';
$string['markingworkflowstateinmarking'] = 'In marking';
$string['markingworkflowstateinreview'] = 'In review';
$string['markingworkflowstatenotmarked'] = 'Not marked';
$string['markingworkflowstatereadyforreview'] = 'Marking completed';
$string['markingworkflowstatereadyforrelease'] = 'Ready for release';
$string['markingworkflowstatereleased'] = 'Released';
$string['maxattempts'] = 'Maximum attempts';
$string['maxattempts_help'] = 'The maximum number of submissions attempts that can be made by a learner. After this number of attempts has been made the learner\'s submission will not be able to be reopened.';
$string['maxgrade'] = 'Maximum grade';
$string['maxgrade'] = 'Maximum Grade';
$string['messageprovider:assign_notification'] = 'Assignment notifications';
$string['modulename'] = 'Assignment';
$string['modulename_help'] = 'The assignment activity module enables a trainer to communicate tasks, collect work, and provide grades and feedback.

Learners can submit any digital content (files), such as word-processed documents, spreadsheets, images, or audio and video clips. Alternatively, or in addition, the assignment may require learners to type text directly into the text editor. An assignment can also be used to remind learners of \'real-world\' assignments they need to complete offline, such as art work, and thus not require any digital content. Learners can submit work individually or as a member of a group.

When reviewing assignments, trainers can leave feedback comments and upload files, such as marked-up learner submissions, documents with comments or spoken audio feedback. Assignments can be graded using a numerical or custom scale or an advanced grading method such as a rubric. Final grades are recorded in the gradebook.';
$string['modulename_link'] = 'mod/assignment/view';
$string['modulenameplural'] = 'Assignments';
$string['moreusers'] = '{$a} more...';
$string['multipleteams'] = 'Member of more than one group';
$string['multipleteamsgrader'] = 'Member of more than one group, so unable to make submissions.';
$string['mysubmission'] = 'My submission: ';
$string['newsubmissions'] = 'Assignments submitted';
$string['noattempt'] = 'No attempt';
$string['nofiles'] = 'No files. ';
$string['nograde'] = 'No grade. ';
$string['nolatesubmissions'] = 'No late submissions accepted. ';
$string['nomoresubmissionsaccepted'] = 'Only allowed for participants who have been granted an extension';
$string['noonlinesubmissions'] = 'This assignment does not require you to submit anything online';
$string['nosavebutnext'] = 'Next';
$string['nosubmission'] = 'Nothing has been submitted for this assignment';
$string['nosubmissionsacceptedafter'] = 'No submissions accepted after ';
$string['noteam'] = 'Not a member of any group';
$string['noteamgrader'] = 'Not a member of any group, so unable to make submissions.';
$string['notgraded'] = 'Not graded';
$string['notgradedyet'] = 'Not graded yet';
$string['notsubmittedyet'] = 'Not submitted yet';
$string['notifications'] = 'Notifications';
$string['nousersselected'] = 'No users selected';
$string['numberofdraftsubmissions'] = 'Drafts';
$string['numberofparticipants'] = 'Participants';
$string['numberofsubmittedassignments'] = 'Submitted';
$string['numberofsubmissionsneedgrading'] = 'Needs grading';
$string['numberofteams'] = 'Groups';
$string['offline'] = 'No online submissions required';
$string['open'] = 'Open';
$string['outof'] = '{$a->current} out of {$a->total}';
$string['overdue'] = '<font color="red">Assignment is overdue by: {$a}</font>';
$string['outlinegrade'] = 'Grade: {$a}';
$string['page-mod-assign-x'] = 'Any assignment module page';
$string['page-mod-assign-view'] = 'Assignment module main and submission page';
$string['participant'] = 'Participant';
$string['pluginadministration'] = 'Assignment administration';
$string['pluginname'] = 'Assignment';
$string['preventsubmissionnotingroup'] = 'Require group to make submission';
$string['preventsubmissionnotingroup_help'] = 'If enabled, users who are not members of a group will be unable to make submissions.';
$string['preventsubmissions'] = 'Prevent the user from making any more submissions to this assignment.';
$string['preventsubmissionsshort'] = 'Prevent submission changes';
$string['previous'] = 'Previous';
$string['quickgrading'] = 'Quick grading';
$string['quickgradingresult'] = 'Quick grading';
$string['quickgradingchangessaved'] = 'The grade changes were saved';
$string['quickgrading_help'] = 'Quick grading allows you to assign grades (and outcomes) directly in the submissions table. Quick grading is not compatible with advanced grading and is not recommended when there are multiple markers.';
$string['reopenuntilpassincompatiblewithblindmarking'] = 'Reopen until pass option is incompatible with blind marking, because the grades are not released to the gradebook until the learner identities are revealed.';
$string['requiresubmissionstatement'] = 'Require that learners accept the submission statement';
$string['requiresubmissionstatement_help'] = 'Require that learners accept the submission statement for all submissions to this assignment.';
$string['requireallteammemberssubmit'] = 'Require all group members submit';
$string['requireallteammemberssubmit_help'] = 'If enabled, all members of the learner group must click the submit button for this assignment before the group submission will be considered as submitted. If disabled, the group submission will be considered as submitted as soon as any member of the learner group clicks the submit button.';
$string['recordid'] = 'Identifier';
$string['revealidentities'] = 'Reveal learner identities';
$string['revealidentitiesconfirm'] = 'Are you sure you want to reveal learner identities for this assignment? This operation cannot be undone. Once the learner identities have been revealed, the marks will be released to the gradebook.';
$string['reverttodraftforstudent'] = 'Revert submission to draft for learner: (id={$a->id}, fullname={$a->fullname}).';
$string['reverttodraft'] = 'Revert the submission to draft status.';
$string['reverttodraftshort'] = 'Revert the submission to draft';
$string['reviewed'] = 'Reviewed';
$string['savechanges'] = 'Save changes';
$string['savegradingresult'] = 'Grade';
$string['saveallquickgradingchanges'] = 'Save all quick grading changes';
$string['savenext'] = 'Save and show next';
$string['scale'] = 'Scale';
$string['sendstudentnotificationsdefault'] = 'Default setting for "Notify learners"';
$string['sendstudentnotificationsdefault_help'] = 'Set the default value for the **Notify learners** checkbox on the grading form.';
$string['sendstudentnotifications'] = 'Notify learners';
$string['sendstudentnotifications_help'] = 'If enabled, learners receive a message about the updated grade or feedback.';
$string['sendnotifications'] = 'Notify graders about submissions';
$string['sendnotifications_help'] = 'If enabled, graders (usually trainers) receive a message whenever a learner submits an assignment, early, on time and late. Message methods are configurable.';
$string['selectlink'] = 'Select...';
$string['selectuser'] = 'Select {$a}';
$string['sendlatenotifications'] = 'Notify graders about late submissions';
$string['sendlatenotifications_help'] = 'If enabled, graders (usually trainers) receive a message whenever a learner submits an assignment late. Message methods are configurable.';
$string['sendsubmissionreceipts'] = 'Send submission receipt to learners';
$string['sendsubmissionreceipts_help'] = 'This switch will enable submission receipts for learners. Learners will receive a notification every time they successfully submit an assignment.';
$string['setmarkingallocation'] = 'Set allocated marker';
$string['setmarkingworkflowstate'] = 'Set marking workflow state';
$string['selectedusers'] = 'Selected users';
$string['setmarkingworkflowstateforlog'] = 'Set marking workflow state : (id={$a->id}, fullname={$a->fullname}, state={$a->state}). ';
$string['setmarkerallocationforlog'] = 'Set marking allocation : (id={$a->id}, fullname={$a->fullname}, marker={$a->marker}). ';
$string['settings'] = 'Assignment settings';
$string['showrecentsubmissions'] = 'Show recent submissions';
$string['status'] = 'Status';
$string['studentnotificationworkflowstateerror'] = 'Marking workflow state must be \'Released\' to notify learners.';
$string['submissioncopiedtext'] = 'You have made a copy of your previous
assignment submission for \'{$a->assignment}\'

You can see the status of your assignment submission:

    {$a->url}';
$string['submissioncopiedhtml'] = '<p>You have made a copy of your previous
assignment submission for \'<i>{$a->assignment}</i>\'.</p>
<p>You can see the status of your <a href="{$a->url}">assignment submission</a>.</p>';
$string['submissioncopiedsmall'] = 'You have copied your previous assignment submission for {$a->assignment}';
$string['submissiondrafts'] = 'Require learners click submit button';
$string['submissiondrafts_help'] = 'If enabled, learners will have to click a **Submit** button to declare their submission as final. This allows learners to keep a draft version of the submission on the system. If this setting is changed from **No** to **Yes** after learners have already submitted those submissions will be regarded as final.';
$string['submissioneditable'] = 'Learner can edit this submission';
$string['submissionnotcopiedinvalidstatus'] = 'The submission was not copied because it has been edited since it was reopened.';
$string['submissionnoteditable'] = 'Learner cannot edit this submission';
$string['submissionnotready'] = 'This assignment is not ready to submit:';
$string['submissionplugins'] = 'Submission plugins';
$string['submissionreceipts'] = 'Send submission receipts';
$string['submissionreceiptothertext'] = 'Your assignment submission for
\'{$a->assignment}\' has been submitted.

You can see the status of your assignment submission:

    {$a->url}';
$string['submissionreceiptotherhtml'] = 'Your assignment submission for
\'<i>{$a->assignment}</i>\' has been submitted.<br /><br />
You can see the status of your <a href="{$a->url}">assignment submission</a>.';
$string['submissionreceiptothersmall'] = 'Your assignment submission for {$a->assignment} has been submitted.';
$string['submissionreceipttext'] = 'You have submitted an
assignment submission for \'{$a->assignment}\'

You can see the status of your assignment submission:

    {$a->url}';
$string['submissionreceipthtml'] = '<p>You have submitted an assignment submission for \'<i>{$a->assignment}</i>\'.</p>
<p>You can see the status of your <a href="{$a->url}">assignment submission</a>.</p>';
$string['submissionreceiptsmall'] = 'You have submitted your assignment submission for {$a->assignment}';
$string['submissionslocked'] = 'This assignment is not accepting submissions';
$string['submissionslockedshort'] = 'Submission changes not allowed';
$string['submissions'] = 'Submissions';
$string['submissionsnotgraded'] = 'Submissions not graded: {$a}';
$string['submissionsclosed'] = 'Submissions closed';
$string['submissionsettings'] = 'Submission settings';
$string['submissionstatement'] = 'Submission statement';
$string['submissionstatement_help'] = 'Assignment submission confirmation statement.';
$string['submissionstatementdefault'] = 'This assignment is my own work, except where I have acknowledged the use of the works of other people.';
$string['submissionstatementacceptedlog'] = 'Submission statement accepted by user {$a}';
$string['submissionstatus_draft'] = 'Draft (not submitted)';
$string['submissionstatusheading'] = 'Submission status';
$string['submissionstatus_marked'] = 'Graded';
$string['submissionstatus_new'] = 'No submission';
$string['submissionstatus_reopened'] = 'Reopened';
$string['submissionstatus_'] = 'No submission';
$string['submissionstatus'] = 'Submission status';
$string['submissionstatus_submitted'] = 'Submitted for grading';
$string['submissionsummary'] = '{$a->status}. Last modified on {$a->timemodified}';
$string['submissionteam'] = 'Group';
$string['submission'] = 'Submission';
$string['submitaction'] = 'Submit';
$string['submitforgrading'] = 'Submit for grading';
$string['submitassignment_help'] = 'Once this assignment is submitted you will not be able to make any more changes.';
$string['submitassignment'] = 'Submit assignment';
$string['submittedearly'] = 'Assignment was submitted {$a} early';
$string['submittedlate'] = 'Assignment was submitted {$a} late';
$string['submittedlateshort'] = '{$a} late';
$string['submitted'] = 'Submitted';
$string['subplugintype_assignsubmission'] = 'Submission plugin';
$string['subplugintype_assignsubmission_plural'] = 'Submission plugins';
$string['subplugintype_assignfeedback'] = 'Feedback plugin';
$string['subplugintype_assignfeedback_plural'] = 'Feedback plugins';
$string['teamsubmission'] = 'Learners submit in groups';
$string['teamsubmission_help'] = 'If enabled learners will be divided into groups based on the default set of groups or a custom grouping. A group submission will be shared among group members and all members of the group will see each others changes to the submission.';
$string['teamsubmissiongroupingid'] = 'Grouping for learner groups';
$string['teamsubmissiongroupingid_help'] = 'This is the grouping that the assignment will use to find groups for learner groups. If not set, the default set of groups will be used.';
$string['textinstructions'] = 'Assignment instructions';
$string['timemodified'] = 'Last modified';
$string['timeremaining'] = 'Time remaining';
$string['ungroupedusers'] = 'The setting \'Require group to make submission\' is enabled and some users are either not a member of any group, or are a member of more than one group, so are unable to make submissions.';
$string['unlocksubmissionforstudent'] = 'Allow submissions for learner: (id={$a->id}, fullname={$a->fullname}).';
$string['unlocksubmissions'] = 'Unlock submissions';
$string['unlimitedattempts'] = 'Unlimited';
$string['unlimitedattemptsallowed'] = 'Unlimited attempts allowed.';
$string['updategrade'] = 'Update grade';
$string['updatetable'] = 'Save and update table';
$string['upgradenotimplemented'] = 'Upgrade not implemented in plugin ({$a->type} {$a->subtype})';
$string['userextensiondate'] = 'Extension granted until: {$a}';
$string['useridlistnotcached'] = 'The grade changes were NOT saved, as it was not possible to determine which submission they were for.';
$string['userswhoneedtosubmit'] = 'Users who need to submit: {$a}';
$string['usergrade'] = 'User grade';
$string['validmarkingworkflowstates'] = 'Valid marking workflow states';
$string['viewbatchsetmarkingworkflowstate'] = 'View batch set marking workflow state page.';
$string['viewbatchmarkingallocation'] = 'View batch set marking allocation page.';
$string['viewfeedback'] = 'View feedback';
$string['viewfeedbackforuser'] = 'View feedback for user: {$a}';
$string['viewfullgradingpage'] = 'Open the full grading page to provide feedback';
$string['viewgradebook'] = 'View gradebook';
$string['viewgradingformforstudent'] = 'View grading page for learner: (id={$a->id}, fullname={$a->fullname}).';
$string['viewgrading'] = 'View/grade all submissions';
$string['viewownsubmissionform'] = 'View own submit assignment page.';
$string['viewownsubmissionstatus'] = 'View own submission status page.';
$string['viewsubmissionforuser'] = 'View submission for user: {$a}';
$string['viewsubmission'] = 'View submission';
$string['viewfull'] = 'View full';
$string['viewsummary'] = 'View summary';
$string['viewsubmissiongradingtable'] = 'View submission grading table.';
$string['viewrevealidentitiesconfirm'] = 'View reveal learner identities confirmation page.';
$string['workflowfilter'] = 'Workflow filter';
$string['submissiontypes'] = 'Submission types';
$string['feedbacktypes'] = 'Feedback types';
$string['groupsubmissionsettings'] = 'Group submission settings';
$string['submissionlog'] = 'Student: {$a->fullname}, Status: {$a->status}';
