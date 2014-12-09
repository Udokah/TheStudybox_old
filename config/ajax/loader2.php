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

if($action == 'ChangePassword'){
	
$oldpassword = sha1(clean($_POST['oldpass']));
$newpassword = sha1(clean($_POST['newpass']));
$uid = $_SESSION['uid'] ;	

$qp = mysql_query("SELECT password FROM std_users WHERE uid = '$uid' AND password = '$oldpassword'") ;
$rp = mysql_fetch_array($qp);

if(isset($rp['password'])){
/// change password if old password is correct
$qup = "UPDATE std_users SET password = '$newpassword' WHERE uid = '$uid'" ;

if(mysql_query($qup)){
	$status = "
$('#changePassForm label').addClass('success').show().css('display','block').html('password has been changed succesfully').delay(3000).fadeOut('slow', function(){
	$(this).removeAttr('class');
	});
$('#changePassForm .cancel').click();
 " ;
}
else{
	$status = "
$('#changePassForm label').addClass('error').show().css('display','block').html('error, password was not changed').delay(3000).fadeOut('slow', function(){
	$(this).removeAttr('class');
	});
 " ;
}
}
else{
	$status = "
$('#changePassForm label').addClass('error').show().css('display','block').html('incorrect password').delay(3000).fadeOut('slow', function(){
	$(this).removeAttr('class');
	});
 " ;
}

echo '$(function(){'.$status.'});' ;
	
	exit();
}

/// Change email address
if($action == 'ChangeEmailAddress'){
$email = clean($_POST['email']);
$uid = $_SESSION['uid'] ;

// check if email has been used before
if($qe = mysql_query("SELECT email FROM std_users WHERE email='$email' AND uid != '$uid'")){
$re = mysql_fetch_array($qe);
if(isset($re['email'])){
$status = "
$('.changeEmail label').addClass('error').show().css('display','block').html('this email has already been used').delay(3000).fadeOut('slow', function(){
	$(this).removeAttr('class');
	});
 " ;
echo '$(function(){'.$status.'});' ;
exit();
}
}

$q = "UPDATE std_users SET email = '$email' WHERE uid = '$uid'" ;	

$return = "" ;
		
if(mysql_query($q)){
	$return = "
$('.changeEmail label').addClass('success').show().css('display','block').html('changes has been saved').delay(3000).fadeOut('slow', function(){
	$(this).removeAttr('class');
	});
		$('.changeEmail .req').each(function() {
		 var content = $(this).val();
		 $(this).attr('data-value',content);
            $(this).attr('disabled','disabled');
        });
		
			$('.changeEmail .save').hide();
			$('.changeEmail .cancel').hide();
			$('.changeEmail .edit').show();
	" ;
}
else{
$return = "
$('.changeEmail label').addClass('error').show().css('display','block').html('error, changes were not saved').delay(3000).fadeOut('slow', function(){
	$(this).removeAttr('class');
	});
$('.changeEmail .cancel').click();	
		" ;
}

echo '$(function(){'.$return.'});' ;

exit();
}

/// save user profile edit
if($action == 'SaveProfileEdit'){
	
	$fullname = clean($_POST['fullname']);
	$school = clean($_POST['school']);
	$course = clean($_POST['course']);
	$location = clean($_POST['location']);
	$bio = clean($_POST['bio']);
	$uid = $_SESSION['uid'] ;

$q = "UPDATE std_users SET fullname = '$fullname', school = '$school', course = '$course', location = '$location', bio = '$bio' WHERE uid = '$uid' " ;

$return = "" ;
		
if(mysql_query($q)){
	$return = "
$('#profileForm label').addClass('success').show().css('display','block').html('changes has been saved').delay(3000).fadeOut('slow', function(){
	$(this).removeAttr('class');
	});
		$('#profileForm .req').each(function() {
		 var content = $(this).val();
		 $(this).attr('data-value',content);
            $(this).attr('disabled','disabled');
				if($(this).val() == ''){
				$(this).val('-----') ;
			}
        });
		
			$('#profileForm .save').hide();
			$('#profileForm .cancel').hide();
			$('#profileForm .edit').show();
	" ;
}
else{
$return = "
$('#profileForm label').addClass('error').show().css('display','block').html('error, changes were not saved').delay(3000).fadeOut('slow', function(){
	$(this).removeAttr('class');
	});
$('#profileForm .cancel').click();	
		" ;
}

echo '$(function(){'.$return.'});' ;

	exit();
}


// Load questions for Questions Page
if($action == 'LoadFullList'){
$qlist = fetch_Unquestions_list(0,$maxRes) ; 

$data = '' ;
foreach($qlist as $value){
$data .=  $value ;
}

if($data == ''){
	$data = 'No questions yet.' ;
}

$count = count($qlist);
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
}

// Load Previous question list 
if($action == 'LoadPrev'){

$current = $_POST['current'] ;
$current-- ;
$current-- ;

$next =  $maxRes * $current  ;

$qlist = fetch_Unquestions_list($next,$maxRes) ; 
foreach($qlist as $value){
echo $value ;
}
}

// Load Next question list 
if($action == 'LoadNext'){

$current = $_POST['current'] ;
$next = $maxRes * $current ;

$qlist = fetch_Unquestions_list($next,$maxRes) ; 

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