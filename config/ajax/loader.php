<?php 
session_start();
if(!isset($_POST['action'])){
echo "Access Denied" ;
exit();
}

require_once("../inc/configurations.php"); 
require_once("../inc/connect.php"); 
require_once("../fn/lib.php"); 
require_once("../fn/fn.php"); 

$action = clean($_POST['action']) ;

/// Save Edited Answer
if($action == 'saveEditsAnswer'){
	$aid = $_POST['aid'] ;
	$edit = HTMLclean($_POST['edit']) ;
	
	// get qid of answer
	$qid = Get_ans_data($aid,'qid') ;
	
$q = mysql_query("SELECT answer FROM std_answers WHERE answer='$edit' AND qid='$qid' AND aid != '$aid'");
$r = mysql_fetch_array($q);
if($r['answer']){
    $ret = "
	var restore = localStorage.getItem(\"RESTORE\") ;
	$('.EDIT-DIV').html(restore);
	$('.EDIT-DIV').removeClass('EDIT-DIV');
	Modal('Notice: A similar answer already exist under this question')" ;
	echo '$(function(){'.$ret.'});' ;
	exit();	
}
	
	$SaveEdit = save_answer_edit($aid,$edit);
	
	if($SaveEdit == true){
	$ret = "
	        $('.EDIT-DIV').html(localStorage.getItem('NewEdits'));
			$('.EDIT-DIV').removeClass('EDIT-DIV');
		    Modal('Edits have been saved');
			 " ;
	}
	else{
	$ret = "
	var restore = localStorage.getItem(\"RESTORE\") ;
	$('.EDIT-DIV').html(restore);
	$('.EDIT-DIV').removeClass('EDIT-DIV');
	Modal('Error occured: edit not saved');" ;
	}
	
	echo '$(function(){'.$ret.'});' ;
	exit();
}

//// Delete answer
if($action == 'removeAnswer'){
	$aid = $_POST['aid'] ;
    
	/// first check if user is logged in Get_ans_data($id,$data)
	if(!isset($_SESSION['uid'])){ # user not logged in
	$ret = "Modal(\"You have to <a href='login.php'>Log in</a> to delete this answer. Don't have an account? <a href='register.php'>Register now</a>\")" ;
	}
	else{   # user is looged in
	
	// check if user posted the answer
	$author = Get_ans_data($aid,'uid') ; // get person that posted the answer
	if( $author !==  $_SESSION['uid']){
	$ret = "Modal('You do not have enough priviledges to delete this answer, please vote it down if you want it to be removed.')" ;
	}
	else{  # user posted this answer
	
	$doDelete = Remove_answer($aid);
	
	if($doDelete == true){
	$ret = "$('#Ans$aid').css('background','pink');
	        $('#Ans$aid').fadeOut('fast', function(){
			 $(this).remove();
			 Modal('Answer has been deleted successfully');
		   });" ;
	}
	else{
		$ret = "Modal('Error occured: Answer not deleted')" ;
	}
	
	}
	
	}
	
	echo '$(function(){'.$ret.'});' ;
	
	exit();
}

//// Delete question
if($action == 'deletequestions'){
	$qid = $_POST['qid'] ;

/// check if any answer has been posted under this question
$q = mysql_query("SELECT COUNT(aid) as count FROM std_answers WHERE qid = '$qid'");
$r = mysql_fetch_array($q);
extract($r);

if($count > 0){
 $ret = "Modal('This question cannot be deleted because it already has answers');" ;
 echo '$(function(){'.$ret.'});' ;  exit();
}
	
$doDelete = Delete_questions($qid) ;

if($doDelete == true){
	$ret = "alert('this question has been deleted'); window.location='questions.php';" ;
}
else{
	$ret = "Modal('Unable delete question');" ;
}
	echo '$(function(){'.$ret.'});' ;
	
	exit();
}

/// Save edit
if($action == 'savequestionEdits'){
	
$body = HTMLclean($_POST['edit']) ;
$qid = $_POST['qid'] ;
	
$q = "UPDATE std_questions SET question = '$body' WHERE qid = '$qid'" ;

if(mysql_query($q)){
	$ret = "Modal('Edit has been saved');" ;
}
else{
	$ret = "Modal('Unable to save edits');$('#myArea1').html(Oldhtml);" ;
}
	echo '$(function(){'.$ret.'});' ;
	exit();
}

//// Password reovery
if($action == 'resetPassword'){

$email = clean($_POST['email']);
$reset = reset_password($email);
$ret = "" ;

if($reset == true){
	$ret = "
	$('#recovery').fadeOut('fast', function(){
	var content = 'Password recovery was successful, check your email address to continue.' ;
	$('.notify').html(content).addClass('success');	
	});
	" ;
}
else{
	$ret = "
    var content = 'Password recovery was not completed.' ;
	$('.notify').html(content).addClass('error');
	" ;
	}
	
	echo '$(function(){'.$ret.'});' ;
	exit();
}



if($action == 'removeImage'){
	$uid = clean($_SESSION['uid']) ;
	$delete = remove_image($uid);
	if($delete == false){
	$status = "Modal('Unable to remove image')" ;
	}
	else{
	$status = "$('#preview').html('')" ;
	}
	
	echo '$(function(){'.$status.'});' ;
    exit();
}

/// Mark Notifications as seen
if($action == 'MarkViewedNotification'){
	$nid = clean($_POST['nid']) ;
	Marked_notif_viewed($nid);
	exit();
}

//// Load Notifications
if($action == 'LoadUserNotifications'){
	$uid = clean($_POST['uid']) ;
$Notifications = LoadUserNotifications($uid) ;

$count = $Notifications[0] ;
$html = $Notifications[1] ;

$ret = "
$('#NotificationCount').html('$count').css('visibility','visible');
$('#notifynav').html(\"$html\");
" ;

if($count == 0){
$ret = "
$('#NotificationCount').css('visibility','hidden');
$('#notifynav').html(\"<li><a href='' class='false' onclick='return false' >no notification</a></li>\");
" ;
}

echo '$(function(){'.$ret.'});' ;
exit();
}

//// Load questions answered by a user
if($action == 'LoadAnsQueByUser'){

if(isset($_POST['uid'])){
		$uid = $_POST['uid'] ;
	}
	else{
	$uid = $_SESSION['uid'] ;
	}

$lim = clean($_POST['lim']);
$Data = Get_que_ans_by_user($uid,$lim) ;
$return =  '' ;

foreach($Data as $value){
$return .= $value ;
}

$lim++;
$next = $maxRes * $lim ;

if($maxRes <= count($Data)){
$return .= "<div class='more' align='center'><input type='button' value='show more' onclick=\"LoadAnsQue($next)\" /></div>" ;
}

echo $return ;

	exit();
}

/// Load questions asked by a user
if($action == 'LoadQuestionsByUser'){
	
	if(isset($_POST['uid'])){
		$uid = $_POST['uid'] ;
	}
	else{
	$uid = $_SESSION['uid'] ;
	}

$lim = clean($_POST['lim']);
$Data = Get_questions_by_user($uid,$lim) ;
$return =  '' ;

foreach($Data as $value){
$return .= $value ;
}

$lim++;
$next = $maxRes * $lim ;

if($maxRes <= count($Data)){
$return .= "<div class='more' align='center'><input type='button' value='show more' onclick=\"LoadQuestions($next)\" /></div>" ;
}

echo $return ;

	exit();
}

// Load questions for Home page
if($action == 'LoadHomepageList'){
$qlist = fetch_home_list() ; // get all questions

$data = '' ;
foreach($qlist as $value){
$data .=  $value ;
}

$count = count($qlist);

if($data == ''){
	$data = 'No questions yet.' ;
}

if($count <= $maxRes){  // if result is less than expected then end has reached
$data .= "
<script>
$(function() {
$('.next').css('visibility','hidden');
 });
 </script>
" ;
}
echo $data ;

exit();
}

// Load questions for Questions Page
if($action == 'LoadFullList'){
$qlist = fetch_questions_list(0,$maxRes) ; 

$data = '' ;

foreach($qlist as $value){
$data .=  $value ;
}

if($data == ''){
	$data = 'No questions yet.' ;
}

$count = count($qlist);
if($count < $maxRes){  // if result is less than expected then end has reached
$data .= "
<script>
$(function() {
$('.next').css('visibility','hidden');
 });
 </script>
" ;
}

echo $data;

}

// Load Previous question list 
if($action == 'LoadPrev'){

$current = $_POST['current'] ;
$current-- ;
$current-- ;

$next =  $maxRes * $current  ;

$qlist = fetch_questions_list($next,$maxRes) ; 
foreach($qlist as $value){
echo $value ;
}
}

// Load Next question list 
if($action == 'LoadNext'){

$current = $_POST['current'] ;
$next = $maxRes * $current ;

$qlist = fetch_questions_list($next,$maxRes) ; 

$data = '' ;

foreach($qlist as $value){
$data .=  $value ;
}

$count = count($qlist);
if($count < $maxRes){  // if result is less than expected then end has reached
$data .= "
<script>
$(function() {
$('.next').css('visibility','hidden');
 });
 </script>
" ;
}

echo $data;
}



?>