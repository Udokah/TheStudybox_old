<?php

/// save answer edits
function save_answer_edit($aid,$edit){
$status = false ;
$q = "UPDATE std_answers SET answer = '$edit' WHERE aid = '$aid'" ;

if(mysql_query($q)){
	$status = true ;
}

return $status ;
}

/// Delete a questions
function Delete_questions($qid){
$status = false ;

$q = mysql_query("SELECT * FROM std_questions WHERE qid = '$qid'");
$r = mysql_fetch_array($q);

if($r['qid']){
extract($r);

$question = HTMLclean($r['question']) ;
$title = HTMLclean($r['title']) ;

/// Move to trash can
if(mysql_query("INSERT INTO std_deleted_questions SET qid='$qid', token='$token', title='$title', question='$question', tags='$tags', views='$views', date='$date', uid='$uid', status='$status', ans_count='$ans_count', vote_count='$vote_count'")){

/// Now do delete
$q2 = "DELETE FROM std_questions WHERE qid = '$qid'" ;

if(mysql_query($q2)){
	/// delete all votes
	mysql_query("DELETE FROM std_qvotes WHERE qid = '$qid'");
	/// delte all notifications
	$link = 'view.php?q='.$qid ;
	mysql_query("DELETE FROM std_notifications WHERE link = '$link'");
	/// delte all followers
	mysql_query("DELETE FROM std_followers WHERE qid = '$qid'");
    $status = true ;
}

}

}

return $status ;
}

/// Delete an answer
function Remove_answer($aid){

$status = false ;
	
$q = mysql_query("SELECT * FROM std_answers WHERE aid = '$aid'") or die(mysql_query());
$r = mysql_fetch_array($q);

if($r['aid']){
extract($r);

$answer = HTMLclean($r['answer']) ;

if(mysql_query("INSERT INTO std_deleted_answers SET aid='$aid', qid='$qid', uid='$uid', token='$token', answer='$answer', vote_counts='$vote_counts', accepted='$accepted', date='$date'") or die(mysql_error())){
	
if(mysql_query("DELETE FROM std_answers WHERE aid = '$aid'")){
	
	$status = true ;
	
mysql_query("DELETE FROM std_avotes WHERE aid = '$aid'") ;
/// delete from notifications
$link = 'view.php?q='.$qid.'&usr='.$uid.'&showanswer=yes&aid='.$aid ;
mysql_query("DELETE FROM std_notifications WHERE link = '$link'") ;
}

}
}

	return $status ;
}

// Generate Keywords and description Meta tags content
function Generate_meta($question,$tags,$title){
// $question contains raw html not decoded

$meta = array();

/// first generate description by converting to plain text
$h2t = new html2text($question); 
$description = $h2t->get_text();
$meta['description'] = $description ; 

/// Now generate keywords
$str = $title.' '.$description.' '.$tags ;
/// Strip all quotations
$str2 = trim( preg_replace( "/[^0-9a-z]+/i", " ", $str ) );
/// Strip multiple white space
$string = trim(preg_replace('!\s+!', ' ', $str2));

// changeall keywords to lower case
$string = strtolower($string);

// replace string with comma
$keyout = explode(" " , $string); // convert to array
$unique = array_unique($keyout) ;   /// remove duplicate values
$keywords = implode(" , " , $unique);  //change to string with comma as delimeter

$meta['keywords'] = $keywords ; 

return $meta ;
}

/// Recover Password
function reset_password($email){
$q = mysql_query("SELECT email FROM std_users WHERE email = '$email'");
$r = mysql_fetch_array($q);

if(isset($r['email'])){
$password = get_random() ;  // create random password
$encpass = sha1($password); // encrypt password
mysql_query("UPDATE std_users SET password = '$encpass' WHERE email = '$email'");

/// Mail user New password
$message = "
<div style= \"margin:10px auto; border:0px solid #ddd; width:100%; height:auto; font-family:Georgia, 'Times New Roman', Times, serif;  \">
<div style= \"text-align:center; \">
<a href= \"http://www.thestudybox.com/ \">
</a></div>

<div style= \"border:1px solid grey; margin:10px auto; min-height:300px; word-wrap:break-word !important; font-size:18px; line-height:30px; letter-spacing:1px; font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; color:#333; padding:15px; \">
<div style= \"
display:block; 
padding:3px;
 \">
<a href= \"http://www.thestudybox.com/ \" style= \"
    display:inline-block;
	width:28px;
	height:24px;
 \"><img src= \"http://www.thestudybox.com/img/logo2.gif\" alt= \"theStudybox\" /></a>
</div>
<br /> <br />
<p>Hello,<br />
Your password has been reset. Log into your account with the password below :</p>
<p>Password: $password</p>
<a href='http://www.thestudybox.com/login.php'>click here to log in</a>
<p>NB: After you login to your account change your password then delete this email.</p>
<p>Best Regards.</p>

</div>

<div style= \"font-size:15px; font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; padding:10px; background-color:#FFCD71; text-align:center; \">&copy;   
<a href= \"http://www.thestudybox.com/ \" style= \"color:#000; text-decoration:none; \">www.thestudybox.com</a></div>

</div>
" ;

$subject = 'Password Recovery :: thestudybox' ;
@mail_user($email,$subject,$message) ;	
$status = true ;

}
else{
	$status = false ;
}
return $status ;
}

// check for complete profile
function check_for_complete_profile($uid){
 $status = 'yes' ;
$q = mysql_query("SELECT avatar,school,course,bio,location FROM std_users WHERE uid = '$uid'")or die(mysql_error());
$r = mysql_fetch_array($q);
foreach($r as $value){
	if($value == ''){
	$status = 'no' ;	
	}
}

return $status ;
}

/// Delete a users image
function remove_image($uid){
$status = '' ;
$q = mysql_query("SELECT avatar FROM std_users WHERE uid = '$uid'");
$r = mysql_fetch_array($q);

if(isset($r['avatar'])){
$avatar = $r['avatar'] ;

/// Delete images
if(file_exists('../../avatars-main/'.$avatar)){
	@unlink('../../avatars-main/'.$avatar);
}
if(file_exists('../../avatars-thumb/'.$avatar)){
	@unlink('../../avatars-thumb/'.$avatar);
}

/// update user image
if(mysql_query("UPDATE std_users SET avatar = '' WHERE uid = '$uid'")){
	$status = true ;
}
else{
$status = false ;
}

}

return $status ;
}

/// follow or unfollow a quesition
function follow_or_unfollow($qid,$uid,$type){
	
if($type == 'follow'){
$q = "INSERT INTO std_followers SET qid = '$qid', uid = '$uid'" ;
}
else{
$q = "DELETE FROM std_followers WHERE uid = '$uid' AND qid = '$qid'" ;
}

if(mysql_query($q)){
$status = true ;
}
else{
$status = false ;
}

return $status;
}

/// Load question followership status
function Load_followers($qid){
/// first count how many people follow this question

$followers = array();
$i = 0 ;
$message = '';

$q = mysql_query("SELECT uid FROM std_followers WHERE qid = '$qid'");
while($r = mysql_fetch_array($q)){
	if(isset($r['uid'])){
    $followers[$i] = $r['uid'] ;
	$i++;
	}
}

$count = count($followers);

if($count !== 0){
if($count == 1){
$message = 'A student follows this discussion' ;
}
else{
$message = '$count students follow this discussion' ;
}
}

// If user is logged in
if(isset($_SESSION['uid'])){
$action = "<a href='#' data-action='follow' data-qid='$qid' data-perm='1' class=\"follow\" title=\"You will be notified if any new response is added\">Follow</a>" ;

// if user already follows this question
if(in_array($_SESSION['uid'],$followers)){

$action = "<a href='#' data-action='unfollow' data-qid='$qid' data-perm='1' class=\"unfollow\" title=\"You will no longer be notified of new responses\">Unfollow</a>" ;

if($message !== ''){
	if($count == 1){
$message = 'You follow this discussion' ;
	}
	else{
$deduct = $count - 1 ;
if($deduct == 1){
$message = 'You and one other student follow this discussion ';
}
else{
$message .= 'You and '.$deduct.' students follow this discussion' ;
}
	}
}

}
$asker = Get_q_data($qid,'uid') ;  /// check who asked the question
if($asker == $_SESSION['uid']){  /// if its the user question then no action can be done
$action = '' ;
}
}
// If user is not logged in
else{
$action = "<a href='#' data-action='follow' data-qid='$qid' data-perm='0' class=\"follow\" title=\"You will be notified if any new response is added\">Follow</a>" ;
}

$html = '<p>'.$message.'</p>'.'<p>'.$action.'</p>' ;

return $html ;
}

#####################################################################
#########  THIS PART HOLDS NOTIFICATION FUNCTIONS ###############
##############################################################

//// Mark notification as seen
function Marked_notif_viewed($nid){
/// once any link is visited, mark as read no matter how many notifications
$q = mysql_query("SELECT link FROM std_notifications WHERE nid = '$nid'");
$r = mysql_fetch_array($q);
$link = $r['link'] ;
mysql_query("UPDATE std_notifications SET viewed = '1' WHERE link = '$link'");
}

/* ------------------------------------------------------------- */
/// Load user notifications
function LoadUserNotifications($uid){
	
## step 1.  Fetch Notifications
$notifications = array();
$i = 0 ; //count
$q = mysql_query("SELECT * FROM std_notifications WHERE uid = '$uid' AND viewed = '0' ORDER BY nid DESC");
while($r = mysql_fetch_array($q)){
if(isset($r['nid'])){
//store notifications in array pairs
$key = $r['event'] ;  /// type of notifications
$value = $r['link'] ;  /// Link to notification and Notification id
if(array_key_exists($key, $notifications)){
	array_push( $notifications[$key] , $value ); // add to the end of the array
}
else{
	$notifications[$key] = array($value) ; // create array
}
$i++;
} 
}

$totalNotificationCount = 0 ;

## step 2.  Form html 
$newRespHTHML = $ansVotedUpHTML = $ansVoteddownHTML = $answerAcceptedHTML = $queVotedUpHTML = $queVoteddownHTML = '';
// Treat New Responses to questions
if(isset($notifications['AP'])){
$resCount = count($notifications['AP']);  /// new responses count
$responses = array_count_values($notifications['AP']) ;
$newrespnsecount = count($responses);   // new response count
foreach($responses as $link => $count){
$totalNotificationCount++;
$title = get_q_title_from_notif_link($link);
if($count == 1){
$count = '<b>one new response</b>' ;
}
else{
$count = $count.' <b>new responses</b>' ;
}
$linktitle = "The question '<b>$title...</b>' <br> recieved $count" ;
$split = split_notif_string($link) ;
$href = $split[0] ;
$data_nid = Get_nid_from_link($link) ;

$newRespHTHML .= "<li class='AP'>"."<a href='#' data-href='$href' data-nid='$data_nid' >".$linktitle."</a>".'</li>';
}
}

/// Treat Question Voted up
if(isset($notifications['QU'])){
$queUpVotes = array_count_values($notifications['QU']) ;
$queUpVotecount = count($queUpVotes);  
foreach($queUpVotes as $link => $count){
$totalNotificationCount++;
$title = get_q_title_from_notif_link($link);
if($count == 1){
$count = '<b>one vote-up</b>' ;
}
else{
$count = $count.' <b>new vote-ups</b>' ;
}
$linktitle = "Your question '<b>$title...</b>' <br> recieved $count" ;
$split = split_notif_string($link) ;
$href = $split[0] ;
$data_nid = Get_nid_from_link($link) ;

$queVotedUpHTML .= "<li class='QU'>"."<a href='#' data-href='$href' data-nid='$data_nid' >".$linktitle."</a>".'</li>';
}
}

/// Treat Question Voted down
if(isset($notifications['QD'])){
$quedownVotes = array_count_values($notifications['QD']) ;
$quedownVotecount = count($quedownVotes);  
foreach($quedownVotes as $link => $count){
$totalNotificationCount++;
$title = get_q_title_from_notif_link($link);
if($count == 1){
$count = '<b>one vote-down</b>' ;
}
else{
$count = $count.' <b>new vote-downs</b>' ;
}
$linktitle = "Your question '<b>$title...</b>' <br> recieved $count" ;
$split = split_notif_string($link) ;
$href = $split[0] ;
$data_nid = Get_nid_from_link($link) ;

$queVoteddownHTML .= "<li class='QD'>"."<a href='#' data-href='$href' data-nid='$data_nid' >".$linktitle."</a>".'</li>';
}
}

/// Treat Answer Voted up
if(isset($notifications['AU'])){
$upvotes = array_count_values($notifications['AU']) ;
foreach($upvotes as $link => $dfreq){
$totalNotificationCount++ ;
$title = get_ans_title_from_notif_link($link);
if($dfreq == 1){
$msg = '<b>one vote-up</b>' ;
}
else{
$msg = $dfreq.' <b>new vote-ups</b>' ;
}
$linktitle = "Your answer '<b>$title...</b>' <br> recieved $msg" ;
$split = split_notif_string($link) ;
$href = $split[0] ;
$data_nid = Get_nid_from_link($link) ;

$ansVotedUpHTML .= "<li class='AU'>"."<a href='#' data-href='$href' data-nid='$data_nid' >".$linktitle."</a>".'</li>';
}
}

/// Treat Answers Voted down
if(isset($notifications['AD'])){
$downvotes = array_count_values($notifications['AD']) ; 
foreach($downvotes as $link => $freq){
$totalNotificationCount++;
$title = get_ans_title_from_notif_link($link);
if($freq == 1){
$msg = '<b>one vote-down</b>' ;
}
else{
$msg = $freq.' <b>new vote-downs</b>' ;
}
$linktitle = "Your answer '<b>$title...</b>' <br> recieved $msg" ;
$split = split_notif_string($link) ;
$href = $split[0] ;
$data_nid = Get_nid_from_link($link) ;
$ansVoteddownHTML .= "<li class='AD'>"."<a href='#' data-href='$href' data-nid='$data_nid' >".$linktitle."</a>".'</li>';
}
}

/// Treat Answers Accepted
if(isset($notifications['AA'])){
$answeraccpetedCount = count($notifications['AA']) ;
foreach($notifications['AA'] as $val){
$totalNotificationCount++;
$title = get_ans_title_from_notif_link($val);
$linktitle = "Your answer '<b>$title...</b>' <br> was <b>accepted</b>" ;
$split = split_notif_string($val) ;
$href = $split[0] ;
$data_nid = Get_nid_from_link($val) ;
$answerAcceptedHTML .= "<li class='AA'>"."<a href='#' data-href='$href' data-nid='$data_nid' >".$linktitle." </a>".'</li>';
}
}

$totalNotifications =  $newRespHTHML.$ansVotedUpHTML.$ansVoteddownHTML.$answerAcceptedHTML.$queVotedUpHTML.$queVoteddownHTML  ;

$ret = array($totalNotificationCount,$totalNotifications);

return $ret ;
}

/* ------------------------------------------------------------- */

function split_notif_string($link){
$first = explode('|',$link);
return $first ;
}

function Get_nid_from_link($link){
$q = mysql_query("SELECT nid FROM std_notifications WHERE link = '$link'");
$r = mysql_fetch_array($q);
return $r['nid'] ;
}

function get_q_title_from_notif_link($link){
$first = explode('&',$link);
$second = explode('=' , $first[0]);
$sentence = Get_q_data($second[1],'title') ;
$string = implode(' ', array_slice(explode(' ', $sentence), 0, 5));
return $string ;
}

function get_ans_title_from_notif_link($link){
$first = explode('&',$link);
$second = explode('=' , $first[3]);
$sentence = Get_ans_data($second[1],'answer') ;
$string = implode(' ', array_slice(explode(' ', $sentence), 0, 5));
return $string ;
}

/* ------------------------------------------------------------- */
//// ADD A NOTIFICATION TO THE DATABASE
function Add_notification($object,$event,$link){
mysql_query("INSERT INTO std_notifications SET uid='$object', event='$event', link='$link'");
}
/* ------------------------------------------------------------- */


/* ------------------------------------------------------------- */
/// Notify when a response to a question is added
/// uid: user that added the answer
/// aid: id of the answer that was added
function NOTIFY_response_added($uid,$aid){

#### FIRST PROCEDURE:  Notify the Person who asked the question first
#step 1.  get question info
$qid = get_qid_from_aid($aid) ;

#step 2.  get the user id who asked the question
$akserID = Get_q_data($qid,'uid') ;

#step 3.  Generate link and event title
$link = "view.php?q=".$qid."&usr=".$uid."&showanswer=yes&aid=".$aid.'#responses' ;
$event = 'AP' ;

#step 4.   Add to database 
Add_notification($akserID,$event,$link);


#### SECOND PROCEDURE: Notify users who follow this discussion
$qr = mysql_query("SELECT uid FROM std_followers WHERE qid = '$qid'");
while($rr = mysql_fetch_array($qr)){
	/// Do not notify person who posted the answer
	/// if he already follows this discussion
	if(isset($rr['uid']) && $rr['uid'] !== $_SESSION['uid']){
$followersID = $rr['uid'] ;	
Add_notification($followersID,$event,$link);
	}
    }

}
/* ------------------------------------------------------------- */


/* ------------------------------------------------------------- */
//// Notify when answer is voted up or down
function NOTIFY_answer_voted($aid,$type){
	
#step 1.  get question info
$qid = get_qid_from_aid($aid) ;

#step 2.  Set condition for up vote or down vote
if($type == 1){
$event = 'AU' ;
}
else{
$event = 'AD' ;
}

#step 3. Get user id of the person who posted the answer
$uid = Get_ans_data($aid,'uid');

#step 3.  Generate link to notification
$link = "view.php?q=".$qid."&usr=".$uid."&showanswer=yes&aid=".$aid.'#responses' ;

#step 4.   Add to database 
Add_notification($uid,$event,$link);

}
/* ------------------------------------------------------------- */


/* ------------------------------------------------------------- */
//// Notify if answer is accepted
function NOTIFY_answer_accepted($aid){

#step 1.  get question info
$qid = get_qid_from_aid($aid) ;

#step 3. Get user id of the person who posted the answer
$uid = Get_ans_data($aid,'uid');

#step 2.  Generate link to notification and event code
$link = "view.php?q=".$qid."&usr=".$uid."&showanswer=yes&aid=".$aid.'#responses' ; 
$event = 'AA' ;

#step 4.   Add to database 
Add_notification($uid,$event,$link);

}
/* ------------------------------------------------------------- */

/* ------------------------------------------------------------- */
/// Notify if question is voted up or voted down
function NOTIFY_questions_voted($qid,$type){

#step 1.  get the user id who asked the question
$uid = Get_q_data($qid,'uid') ;

#step 2.  Generate link to notification and event code
$link = "view.php?q=".$qid ; 

#step 3.   set event type wether vote up or vote down
if($type == 1){
$event = 'QU' ;
}
else{
$event = 'QD' ;
}

#step 4.   Add to database 
Add_notification($uid,$event,$link);

}
/* ------------------------------------------------------------- */

##############################################################
######### END OF NOTIFICATION FUNCTIONS ######################
##############################################################

function get_qid_from_aid($aid){
$q = mysql_query("SELECT qid FROM std_answers WHERE aid = '$aid'");
$r = mysql_fetch_array($q);
return  $r['qid'];
}

//// Caluculate users level
function USER_LEVEL($uid){
	
/*
ACTIONS              POINTS
Ask question           2
Question Voted Up      3
question voted down   -1
post answer            2
answer voted up        2
answer voted down     -1
answer accepted        4
*/

/// Initialize Variables

$Total = $que_up_vote = $que_down_vote = $ans_up_vote = $ans_down_vote = $showthis = '' ;
$questions = 0 ;  // total questions
$answers = 0 ;      // total answers
$ans_accepted = 0 ; // total accepted answers

/// Get Questions Data
$q = mysql_query("SELECT qid,vote_count FROM std_questions WHERE uid = '$uid' AND status != '2'");
while($r = mysql_fetch_array($q)){
$que_up_vote =  $r['vote_count'] ;  /// How many times question was voted up
$que_down_vote =  count_q_down_votes($r['qid']) ; // count question down votes
$questions++;
}

/// Get Answers Data
$qa = mysql_query("SELECT aid,vote_counts,accepted FROM std_answers WHERE uid = '$uid'");
while($ra = mysql_fetch_array($qa)){

$ans_up_vote =  $ra['vote_counts'] ;  /// How many times answer was voted up
$ans_down_vote =  answer_rating($r['aid'],0) ; // count answer down votes

if($ra['accepted'] == 1 ){
	$ans_accepted++;
}

$answers++;
}

//// Final Calculations
$Total = ($questions * 2) + ($answers * 2) + ($que_up_vote * 2) + ($ans_up_vote * 2) ;
$Total += $ans_accepted * 4 ;
$Total -= $que_down_vote ;
$Total -= $ans_down_vote ;

if($Total < 1){
$showthis = 0 ;
}
else{
	$showthis = $Total.'0' ;
}

$return = array($showthis,$Total) ;

return $return ;
}

/// Get all questions answered by a user 
function Get_que_ans_by_user($uid,$lim){
	global $maxRes ;
	/// first get all questions use answered and store in array
$q = mysql_query("SELECT aid,qid,accepted,date FROM std_answers WHERE uid = '$uid' LIMIT $lim, $maxRes");

$i = 0 ;
$data = array();
while($r = mysql_fetch_array($q)){

$ansup = answer_rating($r['aid'],1);
$ansdown = answer_rating($r['aid'],0);
$date = time_since($r['date']);
$title = Get_q_data($r['qid'],'title') ;
$hover = '' ;

if($r['accepted'] == 1){
$hover = "title = 'This answer was accepted as the correct answer to this question' " ;
}
	
$data[$i] = "
<article ".$hover." >
<b>".$ansup."</b>
<i>".$ansdown."</i>" ;

if($r['accepted'] == 1){
	$data[$i] .= '<span></span>' ;
}

$data[$i] .= "
<a href='view.php?q=".$r['qid']."&usr=$uid&showanswer=yes&aid=".$r['aid']."#responses'>".$title."</a>
<dt>".$date."</dt>
</article>
" ;

$i++;
}

return $data ;
}

/// Get all question asked by a user
function Get_questions_by_user($uid,$lim){
	global $maxRes ;
$q = mysql_query("SELECT qid,title,date FROM std_questions WHERE uid = '$uid' LIMIT $lim, $maxRes");

$i = 0 ;
$data = array();
while($r = mysql_fetch_array($q)){

$upvotes = count_q_votes($r['qid']) ;
$downvotes = count_q_down_votes($r['qid']) ;
$date = time_since($r['date']);

$data[$i] = "
<article>
<b>".$upvotes."</b>
<i>".$downvotes."</i>
<a href='view.php?q=".$r['qid']."' >".$r['title']."</a>
<dt>".$date."</dt>
</article>
" ;

$i++;
}

return $data ;
}

/// Count total questions asked by a user
function count_user_questions($uid){
$q = mysql_query("SELECT COUNT(uid) AS count FROM std_questions WHERE uid = '$uid'") ;
	$r = mysql_fetch_array($q);
	$count = $r['count'] ;
	return $count ;
}

/// count total answers provided by a user
function count_user_answers($uid){
	$q = mysql_query("SELECT COUNT(uid) AS count FROM std_answers WHERE uid = '$uid'") ;
	$r = mysql_fetch_array($q);
	$count = $r['count'] ;
	return $count ;
}

//// Accept answers
function Accept_Answer($aid){
if(mysql_query("UPDATE std_answers SET accepted = 0 WHERE accepted = '1' ")){
mysql_query("UPDATE std_answers SET accepted = 1 WHERE aid = '$aid' ") ;
}
}

/// Update vote counts for each answer
function Update_vote_counts($aid){
$q = mysql_query("SELECT COUNT(vid) AS count FROM std_avotes WHERE aid = '$aid' AND type = '1'");
$r = mysql_fetch_array($q);
$count = $r['count'] ;
mysql_query("UPDATE std_answers SET vote_counts = '$count' WHERE aid = '$aid'");
}

// answer rating
function answer_rating($aid,$type){
$q = mysql_query("SELECT COUNT(vid) as counts FROM std_avotes WHERE aid = '$aid' AND type = '$type'");
$r = mysql_fetch_array($q) ;
extract($r);
return $counts ;
}

// get users name
function get_fullname($uid){
$q = mysql_query("SELECT fullname FROM std_users WHERE uid = '$uid'");
$r = mysql_fetch_array($q) ;
extract($r);
return $fullname ;
}

/////////////////// Fetch all responses to an answer
function Load_Responses($lim,$maxRes,$qid,$loadtype){
	$profile = $q = $dMode = '' ;
	$classImg = 'avatar-off' ;

if($loadtype == 1){  /// if 1 then load only users answer
$qry = "SELECT * FROM std_answers WHERE qid = '$qid' AND aid = '$maxRes'" ;	
$putid = 0 ;  // No id so it wont be hidden
}
else{ /// load all answers
$qry = "SELECT * FROM std_answers WHERE qid = '$qid' ORDER BY accepted DESC , vote_counts DESC LIMIT $lim,$maxRes" ;
$putid = 1 ;  /// put id so duplicate can be removed
}

$q = mysql_query($qry);

$data = array() ;
$i = 1 ;
while($r = mysql_fetch_array($q)){
extract($r);

$ansup = answer_rating($aid,1);
$ansdown = answer_rating($aid,0);

$htmlAnswer = html_entity_decode($answer) ;

if(isset($_SESSION['uid']) AND $uid == $_SESSION['uid']){
$name = "<i style=\"text-transform:capitalize\">me</i>" ;
$profile = "profile.php" ;
$data_edit = 1 ;
}
else{
$name = get_fullname($uid);
$profile = "viewprofile.php?user=".$r['uid'] ;
$data_edit = 0 ;
}

$time = time_since($date) ;

if($accepted == 1){
$accepted = "<span class='accepted' title='This answer has been accepted' >accepted<span> " ;
$classImg = 'avatar-on' ;
}
else{
$classImg = 'avatar-off' ;
// if user is logged in, check if the person is the one who asked the question
if(isset($_SESSION['uid'])){

$q_asker = get_q_stats($qid) ;
$asker = $q_asker['user'];

if($asker == 'You asked this question'){

////if answer was posted by user
if($_SESSION['uid'] == $uid){
$accepted = "<span><span>" ;
}
else{
$accepted = "<hr><a href='#' class='accept' title='Accept this answer as the correct answer' data-switch='1' ></a>
<span><span>" ;
}

}
else{
$accepted = "<span><span>" ;
}
///  when user is not logged in 
}
else{
$accepted = "<hr><a href='#' class='accept' title='Accept this answer as the correct answer' data-switch='0' ></a>
<span><span>" ;
}


}

/// title for vote icons
$titleup = "Vote this answer up if you find it useful" ;
$titledw = "Vote down this answer if you find it irrelevant" ;
$upImg = $dwImg = $data_action = $data_status = '' ;
$currentVote = 0 ;

// If user is logged
if(isset($_SESSION['uid'])){
$loggedUser = $_SESSION['uid'] ;
// check if this user has voted this answer before
$check_voter = mysql_query("SELECT vid,type FROM std_avotes WHERE uid = '$loggedUser' AND aid = '$aid'") or die(mysql_error());
$check_r = mysql_fetch_array($check_voter);
$votetype = $check_r['type'] ;

/// If the user has voted this answer before
if(isset($check_r['vid'])){

$titleup = $titledw = 'Undo this vote' ;

$data_action = 'undo-vote' ;

// # If answer is voted up
if($votetype == 1){
$upImg = "<img src='img/voteup2.png' alt='' />"  ; 
$data_status = 'vote-up' ;
}
else{
$dwImg = "<img src='img/votedown2.png' alt='' />" ; 
$data_status = 'vote-down' ;
}

}
/// If user has not voted before he is open to vote
else{
$data_action = 'vote-open' ;  /// open to vote
}

// check if its the user that posted the answer
if($_SESSION['uid'] == $uid){
$data_action = 101 ;  /// the user posted this answer so no voting can be done
}
/// get users id if logged in

}
/// when user is not logged in
else{
$data_action = 500 ;  // not logged in
$data_status = 0 ; /// not active // no voting can be done
$data_edit = 500 ;  /// not logged in to edit answer
}


// Remains getting students level .////####### <------
$smartrank = USER_LEVEL($uid) ;
$avatar = Get_u_data($uid,'avatar');
$data[$i]= "<fieldset" ;

if($putid == 1){
$data[$i] .= " id='Ans$aid' " ;
}
else{
$data[$i] .= " id='Ans$aid' title='Now viewing' style=\"background-color:#F6F6F6;\""; 
}

if($avatar !== ''){
/// when loaded with ajax
if(file_exists('../../avatars-thumb/'.$avatar)){
$thumbnail = "<img alt=' ' src='avatars-thumb/$avatar' />" ;
}
else{
// when called from view page
if(file_exists('avatars-thumb/'.$avatar)){
$thumbnail = "<img alt=' ' src='avatars-thumb/$avatar' />" ;
}
else{
$thumbnail = ' ' ;
}
}
}else{
	$thumbnail = ' ' ;
}

/* 
condition to be set if answer can be deleted
answer can only be deleted if it has not been accepted
or does not have any vote 
*/

if($accepted !== 1 and $ansup == 0){
$dMode = 1 ;
}
else{
$dMode = 0 ;
}

$data[$i] .= ">
<div class='votes' data-aid='$aid' data-action = '$data_action' data-status = '$data_status' >
<span>Rating</span>
<b>$ansup</b>

<a href='#' data-type='voteUp' title='$titleup' class='voteup rateAnswer'>$upImg</a>

<a href='#' data-type='voteDown' title='$titledw' class='votedown rateAnswer'>$dwImg</a>

<b>$ansdown</b>
$accepted
</div>
<div class='answer' id='edit-$aid'>
$htmlAnswer
</div>

<div class='poster'>
<a href='".$profile."' class='$classImg'>".$thumbnail."</a>
<b><span class='smartrank2' data-title='Smart Rank'>".$smartrank[0]."</span></b>
<span>$name</span>
<label>$time</label>
</div>
<div class='clear'></div>
<div class='ansTools' >
<ul>
<li><a href='#' class='edit' data-edit='$data_edit' data-aid='$aid' >Edit</a></li>
<li><a href='#' class='delete' data-mode='$dMode' data-aid='$aid' >Delete</a></li>
<li><a href='#' class='save' data-aid='$aid' >Save Edits</a></li>
<li><a href='#' class='cancel' data-aid='$aid' >Cancel</a></li>
</ul>
</div>
</fieldset>
" ;

$i++;
}

return $data;
}

//// Fetch most resent questions
function fetch_home_list(){

$q = mysql_query("SELECT * FROM std_questions ORDER BY qid DESC , ans_count DESC LIMIT 30") or die(mysql_error()) ;

$i = 0 ; // Initialize counter for array 
$data = array();

while($r = mysql_fetch_array($q)){

extract($r);

$answers = total_answers($qid) ;

if($answers == 0){
$answers = '<b>0</b><label>answers</label>' ;
}
elseif($answers == 1){
$answers = '<b>1</b><label>answer</label>' ;
}
else{
$answers = '<b>'.$answers.'</b><label>answers</label>' ;
}

$doTag = explode(",",$tags);
$sTags = '' ;
foreach($doTag as $value){
$sTags .= " <a href=\"tags.php?tag=$value\">$value</a>" ;
}

// count votes
$votes = count_q_votes($qid) ;
if($votes == 1){
$votes = '1 vote' ;
}
else{
$votes = "$votes votes" ;
}

// set views tense
if($views == 1){
$views = '1 view' ;
}
else{
$views = $views.' views' ;
}

$askedby = Get_u_data($uid,'fullname') ;

$time = time_since($date);

$data[$i] = "<fieldset class=\"question\">

<div class=\"answercount\">
$answers
</div>

<div class=\"qtitle\">
<a href=\"view.php?q=$qid\">$title</a>
<div class=\"tags\">$sTags</div>
<div class=\"info\"><span class=\"votes\">$votes</span><span class=\"views\">$views</span></div>
</div>

<div class=\"poster\">
<label>asked by</label>
<span>$askedby</span>
<b>$time</b>
</div>

<div class=\"clear\"></div>
</fieldset>
" ;

$i++;
}

return $data ;
}


/// Search For questions
function search_questions($limit,$size){

if(empty($limit)){
$lim = 0 ;
}
else{
$lim = $limit ;
}

if(isset($_SESSION['search'])){
$search = clean($_SESSION['search']) ;
$where = "WHERE title LIKE '%$search%' OR tags LIKE '%$search%' OR question LIKE '%$search%' " ;
}
else{
$where = '' ;
}

$q = mysql_query("SELECT DISTINCT * FROM std_questions $where ORDER BY qid DESC , ans_count DESC LIMIT $lim,$size") or die(mysql_error()) ;

$i = 0 ; // Initialize counter for array 

while($r = mysql_fetch_array($q)){

extract($r);

$answers = total_answers($qid) ;

if($answers == 0){
$answers = '<b>0</b><label>answers</label>' ;
}
elseif($answers == 1){
$answers = '<b>1</b><label>answer</label>' ;
}
else{
$answers = '<b>'.$answers.'</b><label>answers</label>' ;
}

$doTag = explode(",",$tags);
$sTags = '' ;
foreach($doTag as $value){
$sTags .= " <a href=\"tags.php?tag=$value\">$value</a>" ;
}

// count votes
$votes = count_q_votes($qid) ;
if($votes == 1){
$votes = '1 vote' ;
}
else{
$votes = "$votes votes" ;
}

// set views tense
if($views == 1){
$views = '1 view' ;
}
else{
$views = $views.' views' ;
}

$askedby = Get_u_data($uid,'fullname') ;

$time = time_since($date);

$data[$i] = "<fieldset class=\"question\">

<div class=\"answercount\">
$answers
</div>

<div class=\"qtitle\">
<a href=\"view.php?q=$qid\">$title</a>
<div class=\"tags\">$sTags</div>
<div class=\"info\"><span class=\"votes\">$votes</span><span class=\"views\">$views</span></div>
</div>

<div class=\"poster\">
<label>asked by</label>
<span>$askedby</span>
<b>$time</b>
</div>

<div class=\"clear\"></div>
</fieldset>
" ;

$i++;
}

if(!isset($data)){
$data = '' ;
}

return $data ;
}

//// Fetch all subject related tagged questions 
function Tags_que_Load($limit,$size){

if(empty($limit)){
$lim = 0 ;
}
else{
$lim = $limit ;
}

if(isset($_SESSION['tag'])){
$tag = $_SESSION['tag'] ;
$where = "WHERE tags LIKE '%$tag%' " ;
}
else{
$where = '' ;
}

$q = mysql_query("SELECT DISTINCT * FROM std_questions $where ORDER BY vote_count DESC , ans_count DESC LIMIT $lim,$size") ;

$i = 0 ; // Initialize counter for array 

while($r = mysql_fetch_array($q)){

extract($r);

$answers = total_answers($qid) ;

if($answers == 0){
$answers = '<b>0</b><label>answers</label>' ;
}
elseif($answers == 1){
$answers = '<b>1</b><label>answer</label>' ;
}
else{
$answers = '<b>'.$answers.'</b><label>answers</label>' ;
}

$doTag = explode(",",$tags);
$sTags = '' ;
foreach($doTag as $value){
$sTags .= " <a href=\"tags.php?tag=$value\">$value</a>" ;
}

// count votes
$votes = count_q_votes($qid) ;
if($votes == 1){
$votes = '1 vote' ;
}
else{
$votes = "$votes votes" ;
}

// set views tense
if($views == 1){
$views = '1 view' ;
}
else{
$views = $views.' views' ;
}

$askedby = Get_u_data($uid,'fullname') ;

$time = time_since($date);

$data[$i] = "<fieldset class=\"question\">

<div class=\"answercount\">
$answers
</div>

<div class=\"qtitle\">
<a href=\"view.php?q=$qid\">$title</a>
<div class=\"tags\">$sTags</div>
<div class=\"info\"><span class=\"votes\">$votes</span><span class=\"views\">$views</span></div>
</div>

<div class=\"poster\">
<label>asked by</label>
<span>$askedby</span>
<b>$time</b>
</div>

<div class=\"clear\"></div>
</fieldset>
" ;

$i++;
}

if(!isset($data)){
$data = '' ;
}

return $data ;
}

//// Function to get all unanswered posts
function fetch_Unquestions_list($limit,$size){
	
	$data = array() ;

if(empty($limit)){
$lim = 0 ;
}
else{
$lim = $limit ;
}

$q = mysql_query("SELECT * FROM std_questions WHERE ans_count = 0 ORDER BY vote_count DESC , qid DESC LIMIT $lim,$size") or die(mysql_error());

$i = 0 ; // Initialize counter for array 

while($r = mysql_fetch_array($q)){

extract($r);

$answers = total_answers($qid) ;

if($answers == 0){
$answers = '<b>0</b><label>answers</label>' ;
}
elseif($answers == 1){
$answers = '<b>1</b><label>answer</label>' ;
}
else{
$answers = '<b>'.$answers.'</b><label>answers</label>' ;
}

$doTag = explode(",",$tags);
$sTags = '' ;
foreach($doTag as $value){
$sTags .= " <a href=\"tags.php?tag=$value\">$value</a>" ;
}

// count votes
$votes = count_q_votes($qid) ;
if($votes == 1){
$votes = '1 vote' ;
}
else{
$votes = "$votes votes" ;
}

// set views tense
if($views == 1){
$views = '1 view' ;
}
else{
$views = $views.' views' ;
}

$askedby = Get_u_data($uid,'fullname') ;

$time = time_since($date);

$data[$i] = "<fieldset class=\"question\">

<div class=\"answercount\">
".$answers."
</div>

<div class=\"qtitle\">
<a href=\"view.php?q=".$qid."\">$title</a>
<div class=\"tags\">".$sTags."</div>
<div class=\"info\"><span class=\"votes\">$votes</span><span class=\"views\">".$views."</span></div>
</div>

<div class=\"poster\">
<label>asked by</label>
<span>".$askedby."</span>
<b>".$time."</b>
</div>

<div class=\"clear\"></div>
</fieldset>
" ;

$i++;
}

return $data ;
}

//// Function to get all newest post
function fetch_questions_list($limit,$size){
	
	$data = array();

if(empty($limit)){
$lim = 0 ;
}
else{
$lim = $limit ;
}
// fetch questions by latest posts
$q = mysql_query("SELECT * FROM std_questions ORDER BY qid DESC , ans_count DESC LIMIT $lim,$size") ;

$i = 0 ; // Initialize counter for array 

while($r = mysql_fetch_array($q)){

extract($r);

$answers = total_answers($qid) ;

if($answers == 0){
$answers = '<b>0</b><label>answers</label>' ;
}
elseif($answers == 1){
$answers = '<b>1</b><label>answer</label>' ;
}
else{
$answers = '<b>'.$answers.'</b><label>answers</label>' ;
}

$doTag = explode(",",$tags);
$sTags = '' ;
foreach($doTag as $value){
$sTags .= " <a href=\"tags.php?tag=$value\">$value</a>" ;
}

// count votes
$votes = count_q_votes($qid) ;
if($votes == 1){
$votes = '1 vote' ;
}
else{
$votes = "$votes votes" ;
}

// set views tense
if($views == 1){
$views = '1 view' ;
}
else{
$views = $views.' views' ;
}

$askedby = Get_u_data($uid,'fullname') ;

$time = time_since($date);

$data[$i] = "<fieldset class=\"question\">

<div class=\"answercount\">
$answers
</div>

<div class=\"qtitle\">
<a href=\"view.php?q=$qid\">$title</a>
<div class=\"tags\">$sTags</div>
<div class=\"info\"><span class=\"votes\">$votes</span><span class=\"views\">$views</span></div>
</div>

<div class=\"poster\">
<label>asked by</label>
<span>$askedby</span>
<b>$time</b>
</div>

<div class=\"clear\"></div>
</fieldset>
" ;

$i++;
}

return $data ;
}

// create voter bars
function create_voter($qid){

$stat = count_q_votes($qid) ; // count votes given

// if logged in
if(isset($_SESSION['uid'])){
$uid = $_SESSION['uid'] ;

// check if user has voted before
$q = mysql_query("SELECT COUNT(vid) as counts FROM std_qvotes WHERE qid = '$qid' AND uid = '$uid'")or die(mysql_error());
$r = mysql_fetch_array($q);
$count = $r['counts'] ;

// if user has not voted before  show vote tools
if($count == 0){
$ret = "
Did you find this questions usefull ?<br>
<div class=\"pages\">
<a href='#' class=\"yes\" title=\"Yes i found it usefull\">Yes</a>
<a href='#' class=\"pg\" data-vote=\"1\" data-qid=\"$qid\" title=\"$stat student(s) found this question helpful\" >
$stat</a>
<a href='#'  class=\"no\" title=\"No it didnt help\" >No</a>
</div>
" ;
}
elseif($stat == 1 AND $count == 1){
$ret = '<strong>You found this question useful</strong>' ;
}
elseif($stat > 1 AND $count == 1){
$stat-- ;
if($stat == 1){
$ret = "<strong>You and $stat other student found this question useful</strong>" ;
}
else{
$ret = "<strong>You and $stat other students found this question useful</strong>" ;
}
}

}
else{  // not logged in
$ret = "
Did you find this questions usefull ?<br>
<div class=\"pages\">
<a href='#' class=\"yes\" title=\"Yes i found it usefull\">Yes</a>
<a href='#' class=\"pg\" data-vote=\"0\" title=\"$stat student(s) found this question helpful\" >
$stat</a>
<a href='#'  class=\"no\" title=\"No it didnt help\" >No</a>
</div>
" ;
}

if(!isset($ret)){
if($stat == 1){
$ret = "One student found this question useful" ;
}
else{
$ret = "$stat students found this question useful" ;
}
}

return $ret ;
}

// Increment views when a question is viewed
function count_views($qid){
$q = mysql_query("SELECT views as counts FROM std_questions WHERE qid = '$qid'");
$r = mysql_fetch_array($q);
$counts = $r['counts'] ;
$counts++ ;
mysql_query("UPDATE std_questions SET views = '$counts' WHERE qid = '$qid'") or die (mysql_error());
}

// Get related quesitons
function related_questions($q){
// Get questions info
$Fq = mysql_query("SELECT title,question,tags FROM std_questions WHERE qid = '$q'");
$Fr = mysql_fetch_array($Fq);
extract($Fr);

$question = clean($question) ;

$where="WHERE title LIKE '%$title%' OR tags LIKE '%$tags%' OR question LIKE '%$question%'" ;

$sq = mysql_query("SELECT DISTINCT qid,title,ans_count FROM std_questions $where ORDER BY ans_count DESC LIMIT 15 ") or die(mysql_error());
$data = '' ;
while($sr = mysql_fetch_array($sq)){
extract($sr);

// Avoid repitition of questions
if($qid !== $q){
$data .= "<a href='view.php?q=$qid' title='$ans_count  answers' >$title</a>" ;
}

}
return $data ;
}

/// Function to get question stats
function get_q_stats($q){
$q = mysql_query("SELECT tags,views,date,uid,status FROM std_questions WHERE qid = '$q'") ;
$r = mysql_fetch_array($q) ;

extract($r);

$data['tags'] = $tags ;
$data['views'] = $views ;

if(isset($_SESSION['uid']) and $uid == $_SESSION['uid']){
$data['user'] = 'You asked this question' ;
}
else{
$data['user'] = Get_u_data($uid,'fullname') ;
}

if($status == 0){
$data['status'] = 'OPEN' ;
}
elseif($status == 1){
$data['status'] = 'CLOSED' ;
}

$data['date'] = time_since($date);

return $data ;
}

// function to count a questions total votes positive votes
function count_q_votes($qid){
$q = mysql_query("SELECT COUNT(vid) AS counts FROM std_qvotes WHERE qid = '$qid' AND type = '1'")or die(mysql_error()) ;
$r = mysql_fetch_array($q) ;
extract($r);
return $counts;
}

// function to count a question total votes negative votes
function count_q_down_votes($qid){
$q = mysql_query("SELECT COUNT(vid) AS counts FROM std_qvotes WHERE qid = '$qid' AND type = '0'")or die(mysql_error()) ;
$r = mysql_fetch_array($q) ;
extract($r);
return $counts;
}

// function to count total answers of a question
function total_answers($qid){
$q = mysql_query("SELECT COUNT(aid) AS counts FROM std_answers WHERE qid = '$qid' ") ;
$r = mysql_fetch_array($q) ;
extract($r);
return $counts;
}

// function to get user data
function Get_u_data($id,$data){
$query = mysql_query("SELECT $data FROM std_users WHERE uid = '$id'");
$displaydata = mysql_fetch_array($query);
extract($displaydata);
if(isset($$data)){
return $$data ;
}
else{
return $$data.' Not found' ;
}
}


//// Get All users Data
function Student_profile($uid){
	$q = mysql_query("SELECT avatar,fullname,school,course,bio,location,email FROM std_users WHERE     uid = '$uid'");
	$r = mysql_fetch_array($q) ;
	return $r ;
}

// function Parts of a question
function Get_q_data($id,$data){
$query = mysql_query("SELECT $data FROM std_questions WHERE qid = '$id'");
$displaydata = mysql_fetch_array($query);
extract($displaydata);
if(isset($$data)){
return $$data ;
}
else{
return $$data.' Not found' ;
}
}

// function Parts of an answer
function Get_ans_data($id,$data){
$query = mysql_query("SELECT $data FROM std_answers WHERE aid = '$id'");
$displaydata = mysql_fetch_array($query);
extract($displaydata);
if(isset($$data)){
return $$data ;
}
else{
return $$data.' Not found' ;
}
}

// Get question ID from token
function get_q_id($token){
$q = mysql_query("SELECT qid FROM std_questions WHERE token = '$token'");
$r = mysql_fetch_array($q);
extract($r);
if(isset($r['qid'])){
$st = $r['qid'] ;
}
else{
$st = false ;
}
return $st ;
}

/// Authenticate user login
function User_Login($email , $password){
if($q = mysql_query("SELECT uid,fullname,email FROM std_users WHERE email = '$email' AND password = '$password'")){
$r = mysql_fetch_array($q);
if(isset($r['email'])){

extract($r);
Set_Login_Params($uid,$email,$fullname) ; // Set login params

$status = true ;
}
else{
$status = false ;
}
}
else{
$status = 'error' ;
}
return $status;
}

// Create Login Parameters
Function Set_Login_Params($uid,$email,$fullname){
$_SESSION['uid'] = $uid ;
$_SESSION['email'] = $email ;
$_SESSION['fullname'] = $fullname ;
}

Function Unset_Login_Params(){
unset($_SESSION['uid']);
unset($_SESSION['email']);
unset($_SESSION['fullname']);

if(isset($_SESSION['search'])){
unset($_SESSION['search']);
}

if(isset($_SESSION['tag'])){
unset($_SESSION['tag']);
}
}


function Send_Registeration_Mail($fullname,$email){
	
$message = "
<div style= \"margin:10px auto; border:0px solid #ddd; width:100%; height:auto; font-family:Georgia, 'Times New Roman', Times, serif;  \">
<div style= \"text-align:center; \">
<a href= \"http://www.thestudybox.com/ \">
</a></div>


<div style= \"border:1px solid grey; margin:10px auto; min-height:300px; word-wrap:break-word !important; font-size:18px; line-height:30px; letter-spacing:1px; font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; color:#333; padding:15px; \">
<div style= \"
display:block; 
padding:3px;
 \">
<a href= \"http://www.thestudybox.com/ \" style= \"
    display:inline-block;
	width:28px;
	height:24px;
 \"><img src= \"http://www.thestudybox.com/img/logo2.gif\" alt= \"theStudybox\" /></a>
</div>
<br /> <br />
<p>Hello $fullname,<br />
Welcome to theStudybox, <br />
Your account was successfully created. You can now <a href=\"http://www.thestudybox.com/login.php\">sign in</a> to your account using your email address and password with which you used to create the account. 
<a href=\"http://www.thestudybox.com/login.php\">Click here to login</a>
</p>
<p>You can send us an email at: hello at thestudybox.com</p>
<p>Best Regards.</p>

</div>

<div style= \"font-size:15px; font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; padding:10px; background-color:#FFCD71; text-align:center; \">&copy;   
<a href= \"http://www.thestudybox.com/ \" style= \"color:#000; text-decoration:none; \">www.thestudybox.com</a></div>

</div>
" ;

$subject = 'New Account Created :: thestudybox.com' ;
@mail_user($email,$subject,$message) ;	
}


?>