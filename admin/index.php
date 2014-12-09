<?php  
session_start();   
ob_start("ob_gzhandler");  
$PAGE = 'dashboard' ;
require_once('config/inc/header.php');
?>     


<section>
<h1><?php echo $PAGE ; ?></h1>
<div class="container">

<div class="box">
<h2></h2>
<ul>
<li>total users<span>29</span></li>
<li>new users (last week)<span>29</span></li>
<li>new users (yesterday)<span>29</span></li>
<li>new users (this month)<span>29</span></li>
</ul>
</div>

<div class="box">
<h2></h2>
<ul>
<li>total questions<span>29</span></li>
<li>total questions (last week)<span>29</span></li>
<li>total questions (yesterday)<span>29</span></li>
<li>total questions (this month)<span>29</span></li>
<li>unanswered questions<span>29</span></li>
<li>answered questions<span>29</span></li>
</ul>
</div>

<div class="box">
<h2></h2>
<ul>
<li>total answers<span>29</span></li>
<li>total answers (yesterday)<span>29</span></li>
<li>total answers (last week)<span>29</span></li>
<li>total answers (this month)<span>29</span></li>
</ul>
</div>

</div>
</section>


<?php require_once('config/inc/footer.php'); ?>
<?php ob_flush(); flush() ; ?>