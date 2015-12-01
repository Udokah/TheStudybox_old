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
<title>Create Account - TheStudybox</title>
<meta name="Keywords" content=" about,  questions , answers , follow , students , academics , subjects , maths , english , knowledge, ask question , create account , register " />
<meta name="Description" content="Register or Create an account now and join our expanding community of smart students.">
<meta http-equiv="Content-Language" content="en-US">
<script src="js/jquery.js"></script> <!-- Jquery script -->
<script src="js/general.js"></script> <!-- General display script -->
<script src="js/lib.js"></script> 

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
$('#fullname').focus() ;

$('#regForm').on('submit',function(e){
e.preventDefault();
var err = '#regForm label';
$(err).removeAttr('class');

$('.req').each(function() {
	
	var content = $.trim($(this).val());
	var len = $(this).val().length ; 
	var msg = $(this).attr('data-alert');
	
	if(content == ''){
		$(err).addClass('error').show().css('display','inline-block').html(msg);
		$(this).focus();
		exit();
	}
	
	if($(this).attr('id') == 'email' && !Valid_email(content)){
		$(err).addClass('warning').show().css('display','inline-block').html('invalid email address');
		exit();
	}
	
	if( $(this).attr('id') == 'fullname' && len < 3 ){
	$(err).addClass('warning').show().css('display','inline-block').html('Fullname is less than required');
	exit();
	}
    
	if( $(this).attr('id') == 'password' && len < 5 ){
$(err).addClass('warning').show().css('display','inline-block').html('Password should be more than 5 characters');
	exit();
	}

});

$(err).hide(); // hide error message

/// get all inputs
var fullname = $.trim($('#fullname').val()) ;
var email = $.trim($('#email').val()) ;
var password = $.trim($('#password').val()) ;

var dataString = 'fullname=' +  fullname + '&email=' + email + '&password=' + password ;

/// Ajax Process ///
$.ajax({
url: 'config/ajax/reg_new_user.php',
data: 'action=RegUser&' + dataString,
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
<a href="index.php" disabled ><img src="img/logo2.gif" /></a>
<div class="clear"></div>
</div>

<div class="wrapper">

<div class="signup">

<h2>Create an account <small>It's free, quick and easy !</small></h2>

<form action="#" method="post" id="regForm">
<table border=0 cellpadding=3 cellspacing=3>
<tr>
<td colspan=2 style="height:30px;"><label style="display:none" >here is so success notification</label></td>
</tr>
<tr>
<td><b class="hide">Full Name</b><input type="text" class="req" data-alert="Fullname field is empty" maxlength="40" name="fullname" placeholder="Full Name" id="fullname" autocomplete="off"  /></td>
<td><span>Enter your first and last names here e.g Jane Doe</span></td>
</tr>
<tr>
<td><b class="hide">Email</b><input type="text" class="req" data-alert="Email address field is empty" maxlength="40" name="email" placeholder="Email" id="email" autocomplete="off"  /></td>
<td><span>A valid email address is required here, e.g student@yourmail.com</span></td>
</tr>
<tr>
<td><b class="hide">Password</b><input type="password" class="req" data-alert="Password field is empty" maxlength="15"  placeholder="Password" name="password" id="password" autocomplete="off"  /></td>
<td><span class="passcounter">Enter a secure password; should be from 6 - 15 characters.</span></td>
</tr>
<tr>
<td><input type="submit" value="Create my account"/></td>
<td></td>
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