<?php 
session_start() ;
ob_start("ob_gzhandler");
if(isset($_SESSION['uid'])){
header("location: index.php");
}
?>     
<!DOCTYPE html>
<html>
<head lang="en-US">
<meta charset="UTF-8">
<link rel="shortcut icon" href="img/icon.gif" />
<title>Login - TheStudybox</title>
<meta name="Keywords" content=" about,  questions , answers , follow , students , academics , subjects , maths , english , knowledge , login, create account" />
<meta name="Description" content="Login to the study box, or create an account if you dont have one.">
<meta http-equiv="Content-Language" content="en-US">
<script src="js/jquery.js"></script> <!-- Jquery script -->
<script src="js/general.js"></script> <!-- General display script -->

<!--[if IE 9]>
<link href="css/iefix.css" rel="stylesheet" type="text/css" />
<![endif]-->

<link href="css/general.css" rel="stylesheet" type="text/css" />
<link href="css/access.css" rel="stylesheet" type="text/css" />

<!-- On page Load place focus on full name textbox -->
<script type="text/javascript">
 // Ajax loader Effect
	$(document).ajaxStart(function(){
		$('body').prepend('<img src="img/load.gif" alt="Loading" class="ajaxload" style="color:#f00; position:fixed; top:45%; left:50%; z-index:999;">');
		
		// Disable all links and buttons while ajax loads     
		       $(':input').attr('disabled','disabled');
		
	}).ajaxStop(function() {
		$('.ajaxload').remove();
		$(':input').removeAttr('disabled');
  });
  
  
$(document).ready(function(){
$('#email').focus() ;

// On Form submit
$('#loginForm').on('submit',function(e){
e.preventDefault();

var err = '#loginForm tr:first-child label';
$(err).removeAttr('class');

$('.req').each(function() {
	
	var content = $.trim($(this).val());
	var msg = $(this).attr('data-alert');
	
	if(content == ''){
		$(err).addClass('error').show().css('display','inline-block').html(msg);
		$(this).focus();
		exit();
	}
});

var email = $('#email').val();
var password = $('#password').val();
var dataString = 'type=login' + '&email=' + email + '&password=' + password ;

/// Ajax Process ///
$.ajax({
url: 'config/ajax/Main_login.php',
data: 'action=LoginUser&' + dataString,
success: function(returnedData){
eval(returnedData);
},
error: function () {
var err = '#regForm label' ;
$(err).removeAttr('class');
$(err).addClass('error').show().css('display','inline-block').html('677: Error Occured while registering. Retry');
    }
});
//// End of Ajax Process

});

});
</script>
</head>
<body>

<div class="container">

<div class="header">
<a href="index.php"><img src="img/logo2.gif" /></a>
<div class="clear"></div>
</div>

<div class="wrapper">

<div class="signup">

<h2>Login  <a href="register.php">or create an account, it's Free !</a></h2>

<?php
$display = 'none' ;
if(isset($_GET['email']) and !empty($_GET['email'])){
$email = $_GET['email'] ;
$display = 'inline-block' ;
}
else{
$email = '' ;
}
?>

<form action="#" method="post" id="loginForm">
<table border=0 cellpadding=3 cellspacing=3>
<tr>
<td style="height:30px;"><label style="display:<?php echo $display ?>" class="error">invalid email or password !</label></td>
</tr>
<tr>
<td><b class="hide">Email:</b><input type="text" maxlength="40" data-alert="Email field is empty !" name="email" placeholder="Email" id="email" value="<?php echo $email ?>" autocomplete="on" class="req" /></td>
</tr>
<tr>
<td><b class="hide">Password:</b><input type="password" maxlength="15" data-alert="Password field is empty !"  placeholder="Password" name="password" id="password" class="req" /></td>
</tr>
<tr>
<td><label><input type="checkbox" name="remember" />remember me</label> <input style="width:150px;" type="submit" value="Sign in"/></td>
</tr>
<tr>
<td><a href="password_recovery.php">i forgot my password</a></td>
</tr>
</table>
</form>
</div>

</div>

<div class="footer">
<?php include("config/inc/footer.php") ?>
</div>


</div>

</body>
</html> <?php ob_flush(); flush() ; ?>