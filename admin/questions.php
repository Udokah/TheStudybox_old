<?php  
session_start();   
ob_start("ob_gzhandler");  
$PAGE = 'questions' ;
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

<form class="form">
<ul>
<li>
<input type="text"  id="search" placeholder="search"/>
</li>
<li>
<select>
    <option>name</option>
    <option selected>school</option>
    <option>email</option>
    <option>location</option>
    <option>course</option>
</select>
</li>
<li>
<input type="submit" value='find'  />
</li>
</ul>
</form>

<table class="table">
<tr><th>title</th><th>author</th><th>responses</th><th>views</th><th>date</th><th>status</th><th>votes</th><th>status</th><th></th></tr>

<tr><td><a href='#'>title</a></td><td>author</td><td>responses</td><td>views</td><td>date</td><td>votes</td><td>tags</td><td>closed</td><td>
<ul class='tools'>
<li><a href="#"  class="open" title="click to open question"></a></li>
<!--<li><a href="#"  class="closed" title="click to close question"></a></li>-->
<li><a href="#"  class="delete" title="remove question"></a></li>
</ul>
</td></tr>

<tr><td><a href='#'>title</a></td><td>author</td><td>responses</td><td>views</td><td>date</td><td>votes</td><td>tags</td><td>closed</td><td>
<ul class='tools'>
<li><a href="#"  class="open" title="click to open question"></a></li>
<!--<li><a href="#"  class="closed" title="click to close question"></a></li>-->
<li><a href="#"  class="delete" title="remove question"></a></li>
</ul>
</td></tr>

</table>

<ul class="pages">
<li><a href="#">1</a></li>
<li><a href="#" class="active">2</a></li>
<li><a href="#">3</a></li>
<li><a href="#">4</a></li>
</ul>

</div>
</section>


<?php require_once('config/inc/footer.php'); ?>
<?php ob_flush(); flush() ; ?>