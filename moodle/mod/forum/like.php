 <?php echo '<p>Your feedback has been registered!</p>'; 
 
	require_once('../../config.php');
	require_once('lib.php');
	require_once($CFG->libdir.'/completionlib.php');

	$reply   = optional_param('reply', 0, PARAM_INT);

	$PAGE->set_url('/mod/forum/like.php', array(
			'reply' => $reply));

	$newmetrics = new stdClass();
	//if the record does not exist, create it
	if(!$newmetrics = $DB->get_record("metrics_posts", array("postid" =>$reply))) {
		$newmetrics->postid    = $reply;
		$newmetrics->param1    = 1.0;
		$newmetrics->param2    = 0.0;
		$newmetrics->param3    = 0.0;
		//always update post value
		$newmetrics->metric     = 0.4 * ($newmetrics->param2 ) + 0.6 * ($newmetrics->param1);
		$newmetrics = $DB->insert_record("metrics_posts", $newmetrics);
	}
	//else, update existing record
	else {
		$newmetrics = $DB->get_record("metrics_posts", array("postid" =>$reply));
		if($newmetrics->param1 == 1.0) {
			$newmetrics->param1    = $newmetrics->param1 - 1.0;
		}
		else {
			$newmetrics->param1    = $newmetrics->param1 + 1.0;
		}
		//always update post value
		$DB->update_record('metrics_posts', $newmetrics);
		$newmetrics->metric     = 0.4 * ($newmetrics->param2 ) + 0.6 * ($newmetrics->param1);
		echo $newmetrics->metric;
		echo $DB->update_record('metrics_posts', $newmetrics);
	}
	
	header('Refresh: 1; URL=http://localhost/moodle/mod/forum/discuss.php?d=1');
?>