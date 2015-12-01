<?php  
session_start();   
ob_start("ob_gzhandler");  
// $title = $_GET['q'] ;
$PAGE = 'settings'  ;
require_once('config/inc/header.php');
?>     

<script>
$(document).ready(function() {
$('.tools').hide();    

$('.table tr').mouseover(function(){
	$(this).find('.tools').show();
}).mouseleave(function() {
    $(this).find('.tools').hide();
});

});
</script>

<section>
<h1><?php echo $PAGE ; ?></h1>
<div class="container">

<div class="glassBox">
<table>
<tr><td><input type="text" placeholder="New username" /></td></tr>
<tr><td><input type="password" placeholder="Password to save" /></td></tr>
<tr><td><input type="submit" value='change username' /></td></tr>
</table>
</div>

<div class="glassBox">
<table>
<tr><td><input type="password" placeholder="New password" /></td></tr>
<tr><td><input type="password" placeholder="Confirm new password" /></td></tr>
<tr><td><input type="password" placeholder="Password to save" /></td></tr>
<tr><td><input type="submit" value='change password' /></td></tr>
</table>
</div>

</div>
</section>


<?php require_once('config/inc/footer.php'); ?>
<?php ob_flush(); flush() ; ?>