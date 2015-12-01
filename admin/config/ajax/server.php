<?php
session_start();

if(!isset($_POST['action'])){
echo "Access Denied" ;
exit();
}

/// Include external scripts from main application
require_once("../../../config/inc/connect.php"); 
require_once("../../../config/fn/lib.php"); 
require_once("../../../config/fn/fn.php"); 

require_once("../fn/admin-fn.php");  ## admin Functions

$action = clean($_POST['action']) ;

// Load users list users.php
if($action == 'Load_List'){
		$LIST = Load_List($page,$type);
		echo '$(function(){'.$ret.'});' ;
		exit();
}

if($action == 'LoginUser'){
	$ret = '' ;
	$username = clean($_POST['username']) ;
	$password = clean($_POST['password']) ;
	$Login = Admin_Login($username , $password) ;
	Admin_Set_Login_Params($password) ; // set session cookie for  login
	if(isset($_COOKIE['ref'])){
		$goto = $_COOKIE['ref'] ;
		unset($_COOKIE['ref']);
	}
	else{
		$goto = 'index.php' ;
	}
		
	if($Login == true){
		$ret = "window.location = '$goto'; " ;
		
		$today = date('Y-m-d H:i:s') ;
        mysql_query("UPDATE std_admin_user SET lastlogin = '$today'");  /// Update last login
		}
		elseif($Login == false){
		$ret = "Modal('Wrong username or password.') " ;	
		}
		else{
			$ret = "Modal('An error occured') " ;	
		}
		
		echo '$(function(){'.$ret.'});' ;
		exit();
}

?>