<?php  session_start();   ob_start("ob_gzhandler");  ?>     
<!DOCTYPE html>
<html>
<head lang="en-US">
<meta charset="UTF-8">
<link rel="shortcut icon" href="../img/icon.gif" />
<meta name="robots" content="noindex, nofollow" />
<title>Login - Administration Panel</title>
<script src="../js/jquery.js"></script> <!-- use Old jquery from main app -->
<script src="js/general.js"></script>
<link href="css/general.css" rel="stylesheet" type="text/css" />

<script>
$(document).ready(function() {
	
$('.loginForm').submit(function(e){
	e.preventDefault();
	$(' .req').each(function(){
	if($(this).val() == ''){
	Modal($(this).attr('data-alert'));
	exit();
	}
	 });
	
	var username = $.trim($('#username').val());
	var password = $.trim($('#password').val());
	
	 var sendData = { action : 'LoginUser' , username : username , password : password } ;
     $.ajax({
     data: sendData,
     success: function(retData){
     eval(retData);
     }
	 });
	
});
   
});
</script>

</head>
<body class="bg">
<div class="wrapper">
<header>
<img src="../img/logo2.gif" alt="thestudybox" />
</header>

<div class="main">

<div class="glassBox">
<h2>Administration</h2>
<form class="loginForm"  id="LoginForm" action="index.php" method="post">
<table>
<tr>
<td align="center"><span class="notifyBar"></span></td>
</tr>
<tr>
<td><input type="text" id="username" class="req" maxlength="15" data-alert='enter username'  placeholder="Username" /></td>
</tr>
<tr>
<td><input type="password" id="password" class="req" maxlength="15" data-alert='enter password'  placeholder="Password" /></td>
</tr>
<tr>
<td ><input type="submit" value="Login"  /></td>
</tr>
</table>
</form> 
</div>

</div>

<footer>
&copy; <?php echo date('Y'); ?> TheStudybox 
</footer>
</div>

</body>
</html> 
<?php ob_flush(); flush() ; ?>