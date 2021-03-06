<?php
ini_set('display_errors', 'On');
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
 * First step page for creating a new badge
 *
 * @package    core
 * @subpackage badges
 * @copyright  2012 onwards Totara Learning Solutions Ltd {@link http://www.totaralms.com/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Yuliya Bozhko <yuliya.bozhko@totaralms.com>
 */

require_once(dirname(dirname(__FILE__)) . '/config.php');
require_once($CFG->libdir . '/badgeslib.php');
require_once($CFG->dirroot . '/badges/edit_form.php');

$type = required_param('type', PARAM_INT);
$courseid = optional_param('id', 0, PARAM_INT);

require_login();

if (empty($CFG->enablebadges)) {
    print_error('badgesdisabled', 'badges');
}

if (empty($CFG->badges_allowcoursebadges) && ($type == BADGE_TYPE_COURSE)) {
    print_error('coursebadgesdisabled', 'badges');
}

$title = get_string('create', 'badges');

if (($type == BADGE_TYPE_COURSE) && ($course = $DB->get_record('course', array('id' => $courseid)))) {
    require_login($course);
    $PAGE->set_context(context_course::instance($course->id));
    $PAGE->set_pagelayout('course');
    $PAGE->set_url('/badges/newbadge.php', array('type' => $type, 'id' => $course->id));
    $PAGE->set_heading($course->fullname . ": " . $title);
    $PAGE->set_title($course->fullname . ": " . $title);
} else {
    $PAGE->set_context(context_system::instance());
    $PAGE->set_pagelayout('admin');
    $PAGE->set_url('/badges/newbadge.php', array('type' => $type));
    $PAGE->set_heading($title);
    $PAGE->set_title($title);
}

require_capability('moodle/badges:createbadge', $PAGE->context);

$PAGE->requires->js('/badges/backpack.js');
$PAGE->requires->js_init_call('check_site_access', null, false);

$fordb = new stdClass();
$fordb->id = null;

$form = new edit_details_form($PAGE->url, array('action' => 'new'));

if ($form->is_cancelled()) {
    redirect(new moodle_url('/badges/index.php', array('type' => $type, 'id' => $courseid)));
} else if ($data = $form->get_data()) {
    // Creating new badge here.
    
if( $data->levelcount > 1 ){
	$bgimage = $form->save_temp_file('image');
	$lvlimage = $form->save_temp_file('lvlimage');
	
	for($i = 0; $i < $data->levelcount; $i++){
	    $fordb->name = $data->name . "-lvl " . $i;
	    $now = time();
	
	    $fordb->description = $data->description;
	    $fordb->timecreated = $now;
	    $fordb->timemodified = $now;
	    $fordb->usercreated = $USER->id;
	    $fordb->usermodified = $USER->id;
	    $fordb->image = 0;
	    $fordb->issuername = $data->issuername;
	    $fordb->issuerurl = $data->issuerurl;
	    $fordb->issuercontact = $data->issuercontact;
	    $fordb->expiredate = ($data->expiry == 1) ? $data->expiredate : null;
	    $fordb->expireperiod = ($data->expiry == 2) ? $data->expireperiod : null;
	    $fordb->type = $type;
	    $fordb->courseid = ($type == BADGE_TYPE_COURSE) ? $courseid : null;
	    $fordb->messagesubject = get_string('messagesubject', 'badges');
	    $fordb->message = get_string('messagebody', 'badges',
	            html_writer::link($CFG->wwwroot . '/badges/mybadges.php', get_string('mybadges', 'badges')));
	    $fordb->attachment = 1;
	    $fordb->notification = BADGE_MESSAGE_NEVER;
	    $fordb->status = BADGE_STATUS_INACTIVE;
		$fordb->lvl = $i;
		$fordb->xp = ($i+1)*$data->xpperlvl;
	    $newid = $DB->insert_record('badge', $fordb, true);
	
		$lvls = $data->levelcount;
		$path_parts = pathinfo($bgimage);
		$bgimagepath = $path_parts['dirname'];
		$bgimageext =  $path_parts['extension'];
		$bgimagefilename = $path_parts['filename'];  
		 
		$bg = imagecreatefromjpeg($bgimage);
		$src = imagecreatefromjpeg($lvlimage);
		$srcsize = getimagesize($lvlimage);
		$imsize = getimagesize($bgimage);
		 
		$newImg = imagecreatetruecolor( $imsize[0], $imsize[1] );
		imagealphablending( $newImg, false);
		imagesavealpha( $newImg, true);
		imagecopy( $newImg, $bg, 0,0,0,0,$imsize[0], $imsize[1]);
		for($x=0;$x<$i;$x++){
			imagecopy( $newImg, $src, $x*20, 80, 0, 0, 20, 20 );
		}
		header('Content-Type: image/jpeg');
		$outfile = $bgimagepath . $bgimagefilename . $i . 'jpg'; 
		imagejpeg( $newImg, $outfile );
	    $newbadge = new badge( $newid );
	    badges_process_badge_image( $newbadge, $outfile );
    }
    imagedestroy($newImg);
	imagedestroy($src);

}
else {
		$now = time();
	
	    $fordb->description = $data->description;
	    $fordb->timecreated = $now;
	    $fordb->timemodified = $now;
	    $fordb->usercreated = $USER->id;
	    $fordb->usermodified = $USER->id;
	    $fordb->image = 0;
	    $fordb->issuername = $data->issuername;
	    $fordb->issuerurl = $data->issuerurl;
	    $fordb->issuercontact = $data->issuercontact;
	    $fordb->expiredate = ($data->expiry == 1) ? $data->expiredate : null;
	    $fordb->expireperiod = ($data->expiry == 2) ? $data->expireperiod : null;
	    $fordb->type = $type;
	    $fordb->courseid = ($type == BADGE_TYPE_COURSE) ? $courseid : null;
	    $fordb->messagesubject = get_string('messagesubject', 'badges');
	    $fordb->message = get_string('messagebody', 'badges',
	            html_writer::link($CFG->wwwroot . '/badges/mybadges.php', get_string('mybadges', 'badges')));
	    $fordb->attachment = 1;
	    $fordb->notification = BADGE_MESSAGE_NEVER;
	    $fordb->status = BADGE_STATUS_INACTIVE;
	
	    $newid = $DB->insert_record('badge', $fordb, true);
	
	    $newbadge = new badge($newid);
	    badges_process_badge_image($newbadge, $form->save_temp_file('image'));
	    // If a user can configure badge criteria, they will be redirected to the criteria page.
    if (has_capability('moodle/badges:configurecriteria', $PAGE->context)) {
        redirect(new moodle_url('/badges/criteria.php', array('id' => $newid)));
    }
    redirect(new moodle_url('/badges/overview.php', array('id' => $newid)));
}
}



echo $OUTPUT->header();
echo $OUTPUT->box('', 'notifyproblem hide', 'check_connection');

$form->display();

echo $OUTPUT->footer();


//================Michaels Add Stars to badge Image to rep levels =======================
function lvlImageMake($bgimage, $lvlimage, $margin = 5, $lvls ) { 
// Create image instances
$path_parts = pathinfo($bgimage);
$bgimagepath = $path_parts['dirname'];
$bgimageext =  $path_parts['extension'];
$bgimagefilename = $path_parts['filename'];  
  
$src = imagecreatefromjpeg($lvlimage);
$srcsize = getimagesize($bgimage);
$dest = imagecreatefromjpeg($bgimage);
$bgsize = getimagesize($bgimage);
// Copy
for ($i=0; $i < $lvls; $i++) { 
imagecopy($dest, $src, $bgsize[0]-$srcsize[0]*x-$margin, $bgsize[1]-$margin, 0, 0, $srcsize[0], $srcsize[1]);
// Output and free from memory
header('Content-Type: image/jpeg');
$outfile = $bgimagepath . $bgimagefilename . $i . 'jpg'; 
imagejpeg($dest, $outfile);

}

imagedestroy($dest);
imagedestroy($src);
} 

//================Michaels Add Stars to badge Image to rep levels =======================


