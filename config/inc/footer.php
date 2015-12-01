
<?php 
if(isset($_SESSION['uid'])){
$display = 'display:none' ;
}
else{
$display = '' ;
}
?>

<ul>
<li><a href="about.php">About</a></li>
<li style="<?php echo $display ?>"><a href="register.php">Create an account</a></li>
<li>&copy; <?php echo date("Y") ; ?> thestudybox</li>
<li> <img src="img/contact.png" align="top" style="margin-top:3px" /></li>
</ul>
<script src="js/customize.js"></script> <!-- My customizations -->