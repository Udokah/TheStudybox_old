<?php 

if(!isset($_POST['action']) or $_POST['action'] !== 'RegUser'){
echo "Access Denied" ;
exit();
}

require_once("../inc/connect.php"); 
require_once("../fn/lib.php"); 
require_once("../fn/fn.php"); 

$fullname = clean($_POST['fullname']);
$email = clean($_POST['email']);
$password = sha1(clean($_POST['password'])); // encrypt password

// check if email has been used before
if($q = mysql_query("SELECT email FROM std_users WHERE email = '$email'")){
$r = mysql_fetch_array($q);
if(isset($r['email'])){
$status = "
var err = '#regForm label' ;
$(err).removeAttr('class');
$(err).addClass('error').show().css('display','inline-block').html('The email address has already been used.');
$('#email').css('border','1px solid red'); " ;
echo $status ;
exit();
}
}

if(mysql_query("INSERT INTO std_users SET fullname = '$fullname' , email = '$email' , password = '$password' "))
{

Send_Registeration_Mail($fullname,$email) ;

$status = "
var content = \"<br><p><label class='success'>You account has been successfully created !</label></p><br><p><a href ='login.php'> Click here </a> to Login to your account.</p><hr>\" ;
$('.signup h2').html('Registeration Successful');
$('#regForm').html(content);
" ;
}

else{
$status = "
var err = '#regForm label' ;
$(err).removeAttr('class');
$(err).addClass('error').show().css('display','inline-block').html('Registeration Failed ! Please try again later.');
" ;
}

echo $status ;

exit();




?>