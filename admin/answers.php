<?php  
session_start();   
ob_start("ob_gzhandler");  
$PAGE = 'answers' ;
require_once('config/inc/header.php');
?>     


<section>
<h1><?php echo $PAGE ; ?></h1>
<div class="container">

<div class="box">
<ul>
<li>total users<span>29</span></li>
<li>new users (last week)<span>29</span></li>
<li>new users (today)<span>29</span></li>
<li>new users (this month)<span>29</span></li>
</ul>
</div>

<div class="box">
<ul>
<li>total users<span>29</span></li>
<li>new users (last week)<span>29</span></li>
<li>new users (today)<span>29</span></li>
<li>new users (this month)<span>29</span></li>
</ul>
</div>

<div class="box">
<ul>
<li>total users<span>29</span></li>
<li>new users (last week)<span>29</span></li>
<li>new users (today)<span>29</span></li>
<li>new users (this month)<span>29</span></li>
</ul>
</div>

</div>
</section>


<?php require_once('config/inc/footer.php'); ?>
<?php ob_flush(); flush() ; ?>