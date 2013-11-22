 <?php echo '<p>Your feedback has been registered!</p>'; 
 
	require_once('../../config.php');
	require_once('lib.php');
	require_once($CFG->libdir.'/completionlib.php');

	$reply   = optional_param('reply', 0, PARAM_INT);

	$PAGE->set_url('/mod/forum/like.php', array(
			'reply' => $reply));

	$newmetrics = new stdClass();
	if(!$newmetrics = $DB->get_record("metrics_posts", array("postid" =>$reply))) {
	$newmetrics->postid      = $reply;
	$newmetrics->param1    = 1.0;
	
	$newmetrics = $DB->insert_record('metrics_posts', $newmetrics);
	}
	else {
	
	$newmetrics = $DB->get_record("metrics_posts", array("postid" =>$reply));
	$newmetrics->param1    = $newmetrics->param1 + 1.0;

	$DB->update_record("metrics_posts", $newmetrics);
	}
?>