<?php 
session_start();
ob_start("ob_gzhandler"); // compress html output
?>     
<!DOCTYPE html>
<html>
<head lang="en-US">
<meta charset="UTF-8">
<link rel="shortcut icon" href="img/icon.gif" />
<title>404 - TheStudybox</title>
<script src="js/jquery.js"></script> <!-- Jquery script -->
<script src="js/general.js"></script> <!-- General display script -->

<!--[if IE 9]>
<link href="css/iefix.css" rel="stylesheet" type="text/css" />
<![endif]-->

<link href="css/general.css" rel="stylesheet" type="text/css" />
<link href="css/access.css" rel="stylesheet" type="text/css" />

</head>
<body>

<div class="container">

<div class="header">
<?php include("config/inc/header.php"); ?>
</div>

<div class="wrapper">

<div class="signup">

<h2>Error <small>an error occured</small></h2>

<form action="login.php" method="post">
<table border=0 cellpadding=3 cellspacing=3>
<tr>
<td colspan=2 style="height:30px;"><label class="error">You cant view this page</label></td>
</tr>
<tr>
<td><input type="submit" value="continue"/></td>
<td><!-- Loading image here --></td>
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