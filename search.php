<?php 
session_start() ;
ob_start("ob_gzhandler");
if(!isset($_POST['search']) or empty($_POST['search'])){
header("location: index.php");
}
else{
require_once("config/inc/connect.php");
require_once("config/fn/lib.php");
include("config/fn/fn.php"); 
$search = clean($_POST['search']) ;
$_SESSION['search'] = $search ;
}
?>    
<!DOCTYPE html>
<html>
<head lang="en-US">
<meta charset="UTF-8">
<link rel="shortcut icon" href="img/icon.gif" />
<meta name="robots" content="noindex, nofollow" />
<title><?php echo $search ?> - TheStudybox</title>
<script src="js/jquery.js"></script>
<script src="js/general.js"></script>
<!--[if IE 9]>
<link href="css/iefix.css" rel="stylesheet" type="text/css" />
<![endif]-->
<link href="css/general.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">
 $(function() {
 // Ajax loader Effect
	$(document).ajaxStart(function(){
		// Disable all links and buttons while ajax loads     
		       $(':input').attr('disabled','disabled');
		
	}).ajaxStop(function() {
		$('.ajaxload').remove();
		$(':input').removeAttr('disabled');
  });
  
  /// Load all questions
  LoadQuestions() ;
  });
  
   function LoadQuestions(){
 var content = "<div class='onerror'><a href='#' disabled >Retrieving questions Please wait <br> <img src='img/loader1.gif'/></a></div>" ;
 $('#loadhere').html(content);
$.ajax({
url: 'config/ajax/search.php',
data: 'action=LoadFullList',
success: function(returnedData){
$('#loadhere').html(returnedData);
$('.pages').show();
$('.prev').css('visibility','hidden');
},
error: function () {
 var content2 = "<div class='onerror'><a onclick='reLoad()' href='#'>An Error Occured : Click here to reload questions</a> </div>" ;
$('#loadhere').html(content2);
    }
});
  }
  
  function reLoad(){
  LoadQuestions() ;
  return false ;
  }
  
  $(document).ready(function(){

  /// When next is clicked
  $('.next').on('click', function(e){
  e.preventDefault();
  LoadNext();
  });
  
  /// When previous is clicked
  $('.prev').on('click', function(e){
  e.preventDefault();
  LoadPrev();
  });
  
    $('.pg').on('click', function(e){
  e.preventDefault();
  });
  
  });
  
    function LoadPrev(){
 var current = Number($('.pg').attr('data-page')) ;
 $('.pg').html("<img src='img/load2.gif' />")
  $.ajax({
url: 'config/ajax/search.php',
data: 'action=LoadPrev&' + 'current=' + current,
success: function(returnedData){
$('#loadhere').fadeOut('fast', function(){
$(this).html(returnedData).fadeIn('fast');
current-- ;
$('.pg').attr('data-page',current);
$('.pg').html(current);

$('.next').css('visibility','');

// if limit is reached
if(current == 1){
$('.pages').show();
$('.prev').css('visibility','hidden');
}

});
},
error: function(){
 var content2 = "<div class='onerror'><a onclick='LoadPrev(" + current + ")' href='#'>An Error Occured : Click here to reload questions</a> </div>" ;
$('#loadhere').html(content2);
    }
});
return false ;
  }
 
  function LoadNext(){
 var current = Number($('.pg').attr('data-page')) ;
 $('.pg').html("<img src='img/load2.gif' />")
  $.ajax({
url: 'config/ajax/search.php',
data: 'action=LoadNext&' + 'current=' + current,
success: function(returnedData){
$('#loadhere').fadeOut('fast', function(){
$(this).html(returnedData).fadeIn('fast');
current++ ;
$('.pg').attr('data-page',current);
$('.pg').html(current);
$('.prev').css('visibility','');
}) ;
},
error: function () {
 var content2 = "<div class='onerror'><a onclick='LoadNext(" + current + ")' href='#'>An Error Occured : Click here to reload questions</a> </div>" ;
$('#loadhere').html(content2);
    }
});
return false ;
  }
 
</script>

</head>
<body>

<div class="container">

<div class="header">
<?php include("config/inc/header.php"); ?>
</div>

<div class="wrapper">

<div class="leftbar">

<!--navigation  -->
<nav>
<ul>
<li><a href="questions.php" >Questions</a></li>
<li><a href="unanswered.php">Unanswered</a></li>
<li><a href="ask.php">Ask Question</a></li>
<li><a href="notes.php">Notes</a></li>
</ul>
</nav>

<!-- Top Questions  -->
<div class="questionsroll">
<h1>Search Results For "<?php echo $search ?>"</h1>

<div class="pages" style="display:none;">
<a href='#' class="prev">&laquo; Previous</a>
<a href='#' disabled class="pg" data-page="1" >1</a>
<a href='#'  class="next" >Next &raquo; </a>
</div>

<div id="loadhere">
</div>

</div>

<div class="pages" style="display:none;">
<a href='#' class="prev">&laquo; Previous</a>
<a href='#' class="pg" data-page="1" >1</a>
<a href='#'  class="next" >Next &raquo; </a>
</div>

</div>

<div class="rightbar">

<form class="search" action="search.php" method="post">
<input type="text" name="search"  placeholder="Search"  autocomplete="off"/><input type="submit" value="" />
</form>

<!-- ADVERTS WILL APPEAR HERE IN THE NEAR FUTURE -->
<div class="adspace">
</div>


</div>

<div class="clear"></div>
</div>

<div class="footer">
<?php include("config/inc/footer.php") ?>
</div>


</div>

</body>
</html> <?php ob_flush(); flush() ; ?>