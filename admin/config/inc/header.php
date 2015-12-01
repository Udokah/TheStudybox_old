<!DOCTYPE html>
<html>
<head lang="en-US">
<meta charset="UTF-8">
<link rel="shortcut icon" href="../img/icon.gif" />
<meta name="robots" content="noindex, nofollow" />
<title><?php echo $PAGE ; ?> - Administration Panel</title>
<script src="../js/jquery.js"></script> <!-- use Old jquery from main app -->
<script src="js/general.js"></script>
<script src="plugins/jquery.nicescroll.min.js"></script>
<script src="plugins/nicescroll.plus.js"></script>
<link href="css/general.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">
  $(document).ready(
   function(){ 
   $("html").niceScroll({styler:"fb",cursorcolor:"#66af33"});
   });
</script>

<?php

if($PAGE == 'settings' ){
	$act = 'active' ;
}
else{
		$act = '' ;
}
?>

</head>
<body>

<div style="display:none;">
<?php
/// Include external scripts from main application
require_once("../config/inc/connect.php"); 
require_once("../config/fn/lib.php"); 
require_once("../config/fn/fn.php"); 
require_once("config/fn/admin-fn.php");  ## admin Functions

$ref = $_SERVER['REQUEST_URI'] ;
$JScode = "window.location = 'login.php'" ; 
$lastlogin = '' ;

if(!isset($_SESSION['token'])){
	setcookie ("ref", "$ref ", time()+300);  // expires in 5mins
	echo '<script>$(function(){'.$JScode.'})</script>;' ;
	exit();
}
else{
	$token = $_SESSION['token'] ;
	$q = mysql_query("SELECT lastlogin FROM std_admin_user WHERE password = '$token'");
	$r = mysql_fetch_array($q);
	if(isset($r['lastlogin'])){
		$lastlogin = time_since ($_SESSION['lastlogin']) ;  /// get last login
	}
	else{
	setcookie ("ref", "$ref ", time()+300);  // expires in 5mins
	echo '<script>$(function(){'.$JScode.'})</script>;' ;
	exit();
	}
}

?>
</div>

<div class="wrapper">
<header>
<ul>
<li class="logo" ><img src="../img/logo2.gif"  alt="thestudybox" /></li>
<li><a href="config/logout.php" class="logout" title="logout"><img src='img/logout.png'alt='logout' /></a></li>
<li><a href="settings.php" class="<?php echo $act ; ?> settings" title="settings"><img src='img/settings.png' alt="settings" /></a></li>
</ul>
<div class="clear"></div>
</header>

<div class="main">

<nav>
<ul>
<li><aside>Last login: <?php echo $lastlogin ; ?></aside></li>
<?php 
$pages = array('dashboard','users','questions');

foreach($pages as $current){
	
if($current == $PAGE){
		$cls = 'active '.$PAGE  ;
	}
	else{
		$cls = $current  ;
	}
	
	if($current  == 'dashboard'){
		$link = 'index.php' ;
	}
	else{
		$link = $current.'.php';
	}
	
	echo "<li><a href='$link' class='$cls'>$current</a></li>" ;
}

?>
</ul>
</nav>