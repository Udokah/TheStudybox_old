<?php 
session_start();
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
<title>Password Recovery</title>
<meta name="robots" content="noindex, nofollow" />
<script src="js/jquery.js"></script> <!-- Jquery script -->
<script src="js/general.js"></script> <!-- General display script -->

<!--[if IE 9]>
<link href="css/iefix.css" rel="stylesheet" type="text/css" />
<![endif]-->

<link href="css/general.css" rel="stylesheet" type="text/css" />
<link href="css/access.css" rel="stylesheet" type="text/css" />

<script>
$(document).ready(function(){
	 // Ajax loader Effect
	 $(document).ajaxStart(function(){
	 	$('body').prepend('<img src="img/load.gif" alt="Loading" class="ajaxload" style="color:#f00; position:fixed; top:45%; left:50%; z-index:999;">');  
			$(':input').attr('disabled','disabled'); 
		
	}).ajaxStop(function() {
		$('.ajaxload').remove();
		$(':input').removeAttr('disabled');
  });

	
	$('#recovery').submit(function(e){
		e.preventDefault();
		var useremail = $('#useremail').val();
		
		if( useremail == '' ){
			exit();
		}
		
	    var dataSend = { action : 'resetPassword' , email : useremail } ;
		$.ajax({
		url: 'config/ajax/loader.php',
		data: dataSend,
		success: function(returnedData){
		eval(returnedData);
		},
		error: function () {
		Modal('An error occured.');
        }
		});
		
		});
	
});
</script>

</head>
<body>

<div class="container">

<div class="header">
<?php include("config/inc/header.php"); ?>
</div>

<div class="wrapper">

<div class="signup">

<h2>Password Recovery</h2>
<div class="notify"></div>
<form action="#" id="recovery">
<table border=0 cellpadding=3 cellspacing=3>
<td colspan=2>Enter you email address<br>
<input type="text" placeholder="Email Address" id="useremail" /></td>
</tr>
<tr>
<td><input type="submit" value="continue"/></td>
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