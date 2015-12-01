<script type="text/javascript">
 /* var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-43858404-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();*/
</script>
<a href="index.php"><img src="img/logo2.gif" /></a>

<script>

</script>

<?php
if(isset($_SESSION['uid'])){
$fullname = $_SESSION['fullname'] ;

$na = explode(' ',$fullname);

$show = "
<ul class=\"usertools\">
<li class=\"first\"><a href=\"profile.php\">hello ".$na[0]." !</a></li>
<li><a href=\"javascript:void(0);\"><span id='NotificationCount'></span> Notifications</a>
<ul id=\"notifynav\" class=\"subnav\">

</ul>
</li>
<li class=\"account\"><a href=\"javascript:void(0);\">Account</a>
<ul class=\"subnav\">
<li class=\"edit\"><a href=\"profile.php\">Profile</a></li>
<li class=\"last\"><a href=\"config/logout.php\">Logout</a></li>
</ul>
</li>
</ul>
" ;

$userid = $_SESSION['uid'] ;
$GetNotifications = "
<script>
$(function() {
	LOAD_NOTIFICATIONS($userid);
});
</script>
" ;

echo $GetNotifications ;

$complete = check_for_complete_profile($_SESSION['uid']);

if($complete !== 'yes'){
echo "<div class='nag'>
<h2><a href='#' class='close' >x</a></h2>
<p>Hi ".$na[0].", your profile is not yet complete. <br> <a href='profile.php'>click here to complete it now</a></p>
</div>" ;
}

}
else{
$show = "
<form id=\"nextSubmit\" action=\"config/user_login.php\" method=\"post\">
<input type='hidden' value='login' /> 
</form>
<form id=\"loginForm\" action=\"logmein.php\" method=\"post\">
<label style=\"position:absolute; display:none; Left:60%;\" id=\"error\" class=\"success\">here is so success notification</label>
<table border=0 cellspacing=0 cellpadding=0>
<tr>
<td><label><b class=\"hide\">Email</b></label></td>
<td><label><b class=\"hide\">Password</b></label></td>
<td>&nbsp;</td>
</tr>
<tr>
<td><input type=\"text\" maxlength=\"40\" data-alert=\"Email field is empty !\" name=\"email\" placeholder=\"Email\" id=\"email\" autocomplete=\"on\" class=\"req\" /></td>
<td><input type=\"password\" maxlength=\"15\" data-alert=\"Password field is empty !\"  placeholder=\"Password\" name=\"password\" id=\"password\" class=\"req\" /></td>
<td><input type=\"submit\" value=\"login\" /></td>
</tr>
<tr>
<td align=left><a href=\"password_recovery.php\">i forgot my password</a></td>
<td><a href=\"register.php\">Create an account</a></td>
<td></td>
</tr>
</table>
</form>
" ;
}

echo $show ;
?>

<div class="clear"></div>