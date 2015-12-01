<?php 
session_start();
if(!isset($_POST['action']) or $_POST['action'] !== 'LoginUser'){
echo "Access Denied" ;
exit();
}

require_once("../inc/connect.php"); 
require_once("../fn/lib.php"); 
require_once("../fn/fn.php"); 

$email = clean($_POST['email']);
$password = sha1(clean($_POST['password'])); // encrypt password

$login = User_Login($email , $password) ;

if($login == true){
  date_default_timezone_set('Africa/Lagos'); // CDT
       $lastlogin = date('Y-m-d H:i:s') ; // get current time
	   mysql_query("UPDATE std_users SET lastlogin = '$lastlogin' WHERE email = '$email'");
$status = "
var err = '#loginForm #error';
$(err).removeAttr('class');
$(err).addClass('success').show().css('display','inline-block').html('Login successful');
$('#nextSubmit').submit(); " ;
}
elseif($login == false){
$ref = $_SERVER['HTTP_REFERER'] ;
setcookie("ref", $ref, time()+900);
$status = "
window.location = 'login.php?email=$email' ; " ;
}
else{
$status = "
var err = '#loginForm #error';
$(err).removeAttr('class');
$(err).addClass('error').show('slide').css('display','inline-block').html('Error Occured'); " ;
}

echo $status ;


?>