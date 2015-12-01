<?php 
session_start();

if(!isset($_POST['action'])){
echo "Access Denied" ;
exit();
}

require_once("../inc/connect.php"); 
require_once("../fn/lib.php");
require_once("../fn/fn.php");  


/// On KeyUp search for similar posts

if($_POST['action'] == 'FindSimilar'){
	$word = clean($_POST['word']);
	
$q = mysql_query("SELECT DISTINCT qid,title FROM std_questions WHERE title LIKE '%$word%' LIMIT 20");
	
while($r = mysql_fetch_array($q)){
	extract($r);
	echo "<a href=\"view.php?q=$qid\">$title</a>" ;
}

}

##### SAVE EDITED QUESTION ###############
if($_POST['action'] == 'SaveEdits'){

// Check if user logged in
if(!isset($_SESSION['uid'])){
$status = "
var err = '#askForm label' ;
$(err).removeAttr('class');
$(err).addClass('warning').show().css('display','inline-block').html('You Must log in to ask any question'); " ;
echo $status ;
exit();
}

$title = clean($_POST['title']);
$question = HTMLclean($_POST['question']);
$tags = clean($_POST['tags']); // encrypt password
$uid = $_SESSION['uid'] ; // Get user id of the person asking the question
$qid = $_POST['qid'] ;

################# check for similar question  ################
$qc = mysql_query("SELECT title FROM std_questions WHERE title = '$title' AND qid != '$qid'");
$rc = mysql_fetch_array($qc);
if(isset($rc['title'])){
$status = "
var err = '#askForm label' ;
$(err).removeAttr('class');
$(err).addClass('warning').show().css('display','inline-block').html('this question has been asked before !'); " ;
echo $status ;
exit();
}

$q = mysql_query("SELECT title FROM std_questions WHERE question = '$question' AND qid != '$qid'");
$r = mysql_fetch_array($q);
if(isset($r['title'])){
$status = "
var err = '#askForm label' ;
$(err).removeAttr('class');
$(err).addClass('warning').show().css('display','inline-block').html('this question has been asked before !'); " ;
echo $status ;
exit();
}
################# end of check for similar question  ################

$q = "UPDATE std_questions SET title = '$title' , question = '$question', tags = '$tags' WHERE qid = '$qid' " ;

if(mysql_query($q)){

$status = "
var err = '#askForm label' ;
$(err).removeAttr('class');
$(err).addClass('success').show().css('display','inline-block').html('Updated successfully');
window.location = 'view.php?q=$qid' ;" ;
}

else{
$status = "
var err = '#askForm label' ;
$(err).removeAttr('class');
$(err).addClass('error').show().css('display','inline-block').html('Update Failed ! Please try again later.');
" ;
}

echo $status ;

exit();

}


##### POST NEW QUESTION ###############
if($_POST['action'] == 'AskNew'){

// Check if user logged in
if(!isset($_SESSION['uid'])){
$status = "
var err = '#askForm label' ;
$(err).removeAttr('class');
$(err).addClass('warning').show().css('display','inline-block').html('You Must log in to ask any question'); " ;
echo $status ;
exit();
}

$title = clean($_POST['title']);
$question = HTMLclean($_POST['question']);
$tags = clean($_POST['tags']); // encrypt password
$uid = $_SESSION['uid'] ; // Get user id of the person asking the question
$token = get_random() ;

################# check for similar question  ################
$qc = mysql_query("SELECT title FROM std_questions WHERE title = '$title'");
$rc = mysql_fetch_array($qc);
if(isset($rc['title'])){
$status = "
var err = '#askForm label' ;
$(err).removeAttr('class');
$(err).addClass('warning').show().css('display','inline-block').html('this question has been asked before !'); " ;
echo $status ;
exit();
}

$q = mysql_query("SELECT title FROM std_questions WHERE question = '$question'");
$r = mysql_fetch_array($q);
if(isset($r['title'])){
$status = "
var err = '#askForm label' ;
$(err).removeAttr('class');
$(err).addClass('warning').show().css('display','inline-block').html('this question has been asked before !'); " ;
echo $status ;
exit();
}
################# end of check for similar question  ################

$q = "INSERT INTO std_questions SET title = '$title', token = '$token', question = '$question', tags = '$tags', views = '0', uid = '$uid', status = '0' " ;

if(mysql_query($q)){

/// Mail user

$status = "
var err = '#askForm label' ;
$(err).removeAttr('class');
$(err).addClass('success').show().css('display','inline-block').html('Your question has been posted');
window.location = 'view.php?tk=$token' ;" ;
}

else{
$status = "
var err = '#askForm label' ;
$(err).removeAttr('class');
$(err).addClass('error').show().css('display','inline-block').html('Posting Failed ! Please try again later.');
" ;
}

echo $status ;

exit();

}

?>