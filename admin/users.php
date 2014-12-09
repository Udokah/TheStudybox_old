<?php  
session_start();   
ob_start("ob_gzhandler");  
$PAGE = 'users' ;
require_once('config/inc/header.php');
?>     

<script>
$(document).ready(function() {
$('.tools').hide();    
sessionStorage.clear() ;  /// clear all locally stored search parameters

// call function to Show all users ;
var type = new Array ('all');
Load_List( 0 ,  type ) ; // call

$('.table tr').mouseover(function(){
	$(this).find('.tools').show();
}).mouseleave(function() {
    $(this).find('.tools').hide();
});
});

function Load_List(page,type){
	// type = 'search' and 'all' stored in first value of array
	if(type[0] == 'search'){
	var LoadData = { action : 'Load_List' , string : type[1] , category : type[2]  , page : page , type : type[0] } ;
	}
	else{
	var LoadData = { action : 'Load_List' , page : page  , type : type[0] } ;
	}
	 $('.table caption').html("<img src='../img/loader1.gif' alt='loading' />").show();
     $.ajax({
     data: LoadData,
     success: function(retData){
     eval(retData);
	$('.table caption').html('Search results');
     }
	 });
}
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
<caption>Search results</caption>
<tr><th>name</th><th>school</th><th>course</th><th>location</th><th>email</th><th>last login</th><th></th></tr>

<tr><td>name</td><td>school</td><td>course</td><td>location</td><td>email</td><td>last login</td>
<td>
<ul class='tools'>
<li><a href="#"  class="view" title="view user profile"></a></li>
<li><a href="#"  class="delete" title="delete this user"></a></li>
</ul>
</td>
</tr>

<tr><td>name</td><td>school</td><td>course</td><td>location</td><td>email</td><td>last login</td>
<td>
<ul class='tools'>
<li><a href="#"  class="view" title="view user profile"></a></li>
<li><a href="#"  class="delete" title="delete this user"></a></li>
</ul>
</td>
</tr>

<tr><td>name</td><td>school</td><td>course</td><td>location</td><td>email</td><td>last login</td>
<td>
<ul class='tools'>
<li><a href="#"  class="view" title="view user profile"></a></li>
<li><a href="#"  class="delete" title="delete this user"></a></li>
</ul>
</td>
</tr>

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