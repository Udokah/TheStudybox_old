<?php 
session_start();
ob_start("ob_gzhandler"); // compress html output
?>     
<!DOCTYPE html>
<html>
<head lang="en-US">
<meta charset="UTF-8">
<link rel="shortcut icon" href="img/icon.gif" />
<title>TheStudybox</title>
<meta name="Keywords" content=" questions , answers , follow , students , academics , subjects , maths , english" />
<meta name="Description" content="TheStudybox is a collaborative question & answer site for students and academic enthusiasts aimed at providing an online platform for academic knowledge sharing.">
<meta http-equiv="Content-Language" content="en-US">
<meta property="og:image" content="http://www.thestudybox.com/img/icon.gif" />

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
		$(':input').removeAttr('disabled');
  });
  
  /// Load all questions
  LoadQuestions() ;

  });
  
 function LoadQuestions(){
 var content = "<div class='onerror'><a href='#' disabled >Retrieving questions Please wait <br> <img src='img/loader1.gif'/></a></div>" ;
 $('#loadhere').html(content);
$.ajax({
url: 'config/ajax/loader.php',
data: 'action=LoadHomepageList',
success: function(returnedData){
$('#loadhere').html(returnedData);
$('.wordings').show();
},
error: function () {
 var content2 = "<div class='onerror'><a onclick='LoadQuestions()' href='#'>An Error Occured : Click here to reload questions</a> </div>" ;
$('#loadhere').html(content2);
    }
});
return false;
  }
  
  
</script>
</head>
<body>


<div class="container">

<div class="header">
<?php include("config/inc/connect.php"); ?>
<?php include("config/fn/fn.php"); ?>
<?php include("config/inc/header.php"); ?>
</div>

<div class="wrapper">

<div class="leftbar">

<?php
$show = '' ;
if(isset($_SESSION['uid'])){
	$show = 'display:none;' ;
}
else{
	$show = 'display:;' ;
}
?>

<!-- Description -->
<div class="description" style="<?php echo $show; ?>">
<p><strong>theStudybox </strong> is a question & answer site for students to share knowledge on various academic subjects while earning <strong>SmartPoints &trade;</strong> as they learn.
<p>
<a href='about.php' title='Take the tour' class="tour">Find out how it works</a>
</div>

<!--navigation  -->
<nav>
<ul>
<li><a href="questions.php">Questions</a></li>
<li><a href="unanswered.php">Unanswered</a></li>
<li><a href="ask.php">Ask Question</a></li>
<li><a href="notes.php">Notes</a></li>
</ul>
</nav>


<!-- Top Questions  -->
<div class="questionsroll">
<br>
<h1>Top Questions</h1>

<div id="loadhere"></div>

</div>

<p class="wordings" style="display:none;">
More questions ? view the <a href="questions.php">complete list</a> and see if there's one you can answer, or <a href="ask.php">ask your own</a> and recieve answers from various students.
</p>

</div>

<div class="rightbar">

<form class="search" action="search.php" method="post">
<input type="text" value="Search" onblur="if(this.value=='')this.value='Search'" onfocus="if(this.value=='Search')this.value=''"  placeholder="Search"  autocomplete="on" name="search"/><input type="submit" value="" />
</form>

<div class="social" style="margin-left:50px;">
<iframe src="//www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2Fthestudybox&amp;width=300&amp;height=300&amp;colorscheme=light&amp;show_faces=true&amp;header=false&amp;stream=false&amp;show_border=true&amp;appId=320510254743674" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:300px; height:400px;" allowTransparency="true"></iframe>
</div>

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