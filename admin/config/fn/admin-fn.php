<?php

/// User Statistics

function Admin_user_stat($type){
	
	if($type == 'total'){
		$WHERE = '' ;
	}
	elseif($type == 'New-last-week'){
		$WHERE = 'WHERE ' ;
	}
	
	/*  FORM QUERY FOR DATE TIME SELECTION */

$q = mysql_query("SELECT COUNT(uid) AS counts $WHERE ");
$r = mysql_fetch_array($q);
return $r['counts'] ;
}

/// Authenticate Admin login
function Admin_Login($username , $password){
	$password = sha1($password);
if($q = mysql_query("SELECT lastlogin FROM std_admin_user WHERE username = '$username' AND password = '$password'") or die(mysql_error())){
$r = mysql_fetch_array($q);
if(isset($r['lastlogin'])){ $status = true ; $_SESSION['lastlogin'] = $r['lastlogin'] ; }  else{ $status = false ; } 
} 
else{ $status = 'error' ; 
}

return $status;
}

// Create Login Parameters
Function Admin_Set_Login_Params($password){
$_SESSION['token'] = sha1($password) ;
}

// Destroy Login Parameters
Function Admin_Unset_Login_Params(){
unset($_SESSION['token']);
unset($_SESSION['lastlogin']);
}

?>