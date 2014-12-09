<?php
session_start();
if(!isset($_POST['action'])){
echo "Access Denied" ;
exit();
}


require_once("../inc/configurations.php");  /// configurations
require_once("../inc/connect.php"); 
require_once("../fn/lib.php"); 
require_once("../fn/fn.php"); 

$action = clean($_POST['action']) ;

/// do followership i.e eithere follow or unfollow a discussion
if($action == 'DoFollowership'){

$qid = clean($_POST['qid']) ;
$type = clean($_POST['type']) ;
$uid = $_SESSION['uid'] ;

$perform = follow_or_unfollow($qid,$uid,$type);

if($type == 'unfollow' && $perform == true){
$ret = "
Get_Followers($qid);
Modal(\"You have unfollowed this discussion, you will no longer be notified if new responses are added\");
" ;
}
elseif($type == 'follow' && $perform == true){
$ret = "
Get_Followers($qid);
Modal(\"You are now following this discussion, you will be notified whenever new responses are added.\");
" ;
}
elseif($perform == false){
$ret = "
Get_Followers($qid);
Modal(\"an error occured\");
" ;
}

echo '$(function() {'.$ret.'});' ;
}



/// Load Followers
if($action == 'LoadFollowers'){
$qid = clean($_POST['qid']) ;
$followers = Load_followers($qid);
echo $followers ;
exit();
}

/// Accept Answer
if($action == 'acceptAnswer'){
$aid = clean($_POST['aid']) ;
Accept_Answer($aid) ;
#### ADD NOTIFICATIONS #####
NOTIFY_answer_accepted($aid) ;
exit();
}

// Rate answers
if($action == 'rateAnswer'){

$aid = clean($_POST['aid']) ;
$qid = clean($_POST['qid']) ;
$type = clean($_POST['type']) ;
$uid = clean($_SESSION['uid']) ;

if($type == 'voteUp'){
$type = 1 ;
}
elseif($type == 'voteDown'){
$type = 0 ;
}

$q = "INSERT INTO std_avotes SET aid = '$aid', uid = '$uid', type = '$type' " ;

if(mysql_query($q)){
	
### ADD NOTIFICATIONS  ####
NOTIFY_answer_voted($aid,$type);

Update_vote_counts($aid);  /// Update vote counts
}

exit();
}

// Load More responses
if($action == 'LoadMore'){

$qid = clean($_POST['qid']) ;
$page = clean($_POST['page']) ;

$next = $page * $maxRes ; // cal next page

$data = Load_Responses($next,$maxRes,$qid,0);
$sendback = '' ;
$count = count($data) ;

if($count == 0){
$sendback .= "
<script>
$(function() {
$('#showmoreanswers').css('visibility','hidden');
Modal('there are no more responses') ;
 });
 </script>
" ;
}
else{
foreach($data as $value){
$sendback .= $value ;
}
}

if($count < $maxRes){
$sendback .= "
<script>
$(function() {
$('#showmoreanswers').css('visibility','hidden');
 });
 </script>
" ;
}

echo $sendback ;
exit();
}

/// Load responses
if($action == 'LoadResponses'){
$qid = clean($_POST['qid']) ;
$data = Load_Responses(0,$maxRes,$qid,0);
$sendback = '' ;
$count = count($data) ;

if($count == 0){
$sendback = "<strong class='noresponse'>No responses yet.</strong>" ;
$sendback .= "
<script>
$(function() {
$('#showmoreanswers').css('visibility','hidden');

 });
 </script>
" ;
}
else{
foreach($data as $value){
$sendback .= $value ;
}
}

if($count < $maxRes){
$sendback .= "
<script>
$(function() {
$('#showmoreanswers').css('visibility','hidden');
 });
 </script>
" ;
}

echo $sendback ;
exit();
}

/// Add response
if($action == 'addResponse'){

$answer = HTMLclean($_POST['answer']) ; // parse html
$anshtml = html_entity_decode($answer) ; /// reform html
$qid = clean($_POST['qid']) ;
$uid = clean($_SESSION['uid']) ;
$token = get_random().time() ; 

/// Check for duplicate answers
$check = mysql_query("SELECT COUNT(qid) as counts FROM std_answers WHERE answer = '$answer' AND qid = '$qid'");
$rcheck = mysql_fetch_array($check);
extract($rcheck);

/// If answer has not been added before
if($counts == 0){
$q = "INSERT INTO std_answers SET qid = '$qid' , uid = '$uid' , token = '$token' , answer = '$answer'" ;
if(mysql_query($q)){

// update the question answer count
$update = total_answers($qid) ;
mysql_query("UPDATE std_questions SET ans_count = '$update' WHERE qid = '$qid'");

// get the question back with token
$qs = mysql_query("SELECT * FROM std_answers WHERE token = '$token'");
$qr = mysql_fetch_array($qs);
extract($qr);

$questionsAsker = Get_q_data($qid,'uid') ;
$answerposter = $qr['uid'] ;

// No notifiy is user answers own question
if($questionsAsker !== $answerposter ){
#### ADD NOTIFICATION ###
NOTIFY_response_added($uid,$aid);
}

$switch = 4 ;
$time = time_since ($date) ;
$name = $_SESSION['fullname'] ;
$title = 'You cannot vote your ownd answer' ;
$data_action = 101 ;  // not logged in
$data_status = 0 ; /// not active // no voting can be done

$avatar = Get_u_data($uid,'avatar');
$thumbnail = ' ' ;
if($avatar !== ''){
/// when loaded with ajax
if(file_exists('../../avatars-thumb/'.$avatar)){
$thumbnail = "<img alt=' ' src='avatars-thumb/$avatar' />" ;
}
}

$smartrank = USER_LEVEL($uid) ;

$data = "
<fieldset>
<div class='votes' data-action = '$data_action' data-status = '$data_status' >
<span>Rating</span>
<b>0</b>

<a href='#' data-type='voteup' data-img='voteup2.png'  title='$title' class='voteup rateAnswer'></a>

<a href='#' data-type='votedown' data-img='votedown2.png' title='$title' class='votedown rateAnswer'></a>

<b>0</b>
</div>
<div class='answer'>
<p>$anshtml</p>
</div>

<div class='poster'>
<a href='profile.php' class='avatar-off'>".$thumbnail."</a>
<span><i>Me</i></span>
<b><span class='smartrank2' data-title='Smart Rank'>".$smartrank[0]."</span></b>
<label>$time</label>
</div>
<div class='clear'></div>
</fieldset>
<script>
$(function() {
$('.nicEdit-main').html('');
$('.noresponse').fadeOut('slow') ;
 });
</script>
" ;

echo $data ;
}
else{
echo "
<script>
$(function() {
Modal('Unable to post response, please retry.') ;
 });
</script>
" ;
}
}
// If answer has been added before
else{
echo "
<script>
$(function() {
Modal('You cannot post duplicate answers, this answer has been posted before.') ;
 });
</script>
" ;
exit();
}

exit();
}

?>