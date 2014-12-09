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

if(isset($_COOKIE['ref'])){
$ref = $_COOKIE['ref'] ;
}
else{
$ref = 'questions.php' ;
}

$status = "
var err = '#loginForm tr:first-child label' ;
$(err).removeAttr('class');
$(err).addClass('success').show().css('display','inline-block').html('Login successful');
window.location = '$ref' ; " ;
}
elseif($login == false){
$status = "
var err = '#loginForm tr:first-child label' ;
$(err).removeAttr('class');
$(err).addClass('error').show().css('display','inline-block').html('Invalid email or password !'); 
$('#password').val('')" ;
}
else{
$status = "
var err = '#loginForm tr:first-child label' ;
$(err).removeAttr('class');
$(err).addClass('warning').show().css('display','inline-block').html('Error while authenticating'); " ;
}

echo $status ;

?>