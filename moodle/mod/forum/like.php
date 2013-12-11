 <?php echo '<p>Your feedback has been registered!</p>'; 
 
	require_once('../../config.php');
	require_once('lib.php');
	require_once($CFG->libdir.'/completionlib.php');

	$reply   = optional_param('reply', 0, PARAM_INT);
	$user 	 = optional_param('user', 0, PARAM_INT);

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
		$newmetrics->metric     = 0;
		//$newmetrics->metric     = 0.5 * ($newmetrics->param2 ) + 0.5 * ($newmetrics->param1) + 0.5 * (time() - $post->created);
		$newmetrics = $DB->insert_record("metrics_posts", $newmetrics);
		
		//update XP
			$userxp = new stdClass();
				$userxp = $DB->get_record('class_student_xp_table', array('userid' => $user ));
				if(!$userxp) {
					$userxp->userid = $user;
					$userxp->xp = 250;
					$DB->insert_record("class_student_xp_table", $userxp);
				}
				else {
					$userxp->xp = $userxp->xp + 250;
					$DB->update_record('class_student_xp_table', $userxp);
				}
	}
	//else, update existing record
	else {
		$newmetrics = $DB->get_record("metrics_posts", array("postid" =>$reply));
		//unlike
		if($newmetrics->param1 == 1.0) {
			$newmetrics->param1    = $newmetrics->param1 - 1.0;
			if($newmetrics->param3 != 0) {
				$newmetrics->param3 = $newmetrics->param3 - 1;
			}
		}
		//like
		else {
			//update XP
			$userxp = new stdClass();
				$userxp = $DB->get_record('class_student_xp_table', array('userid' => $user ));
				if(!$userxp) {
					$userxp->userid = $user;
					$userxp->xp = 250;
					$DB->insert_record("class_student_xp_table", $userxp);
				}
				else {
					$userxp->xp = $userxp->xp + 250;
					$DB->update_record('class_student_xp_table', $userxp);
				}
		
			$newmetrics->param1    = $newmetrics->param1 + 1.0;
			$newmetrics->param3    = $newmetrics->param3 + 1.0;
		}
		//always update post value
		$DB->update_record('metrics_posts', $newmetrics);
		$newmetrics->metric     = 0.5 * ($newmetrics->param2 ) + 0.5 * ($newmetrics->param1) + 0.5 * (time() - $post->created);
	}
	
	header('Location: ' . $_SERVER['HTTP_REFERER']);
?>