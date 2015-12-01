<?php 
session_start();
ob_start("ob_gzhandler"); // compress html output
?>     
<!DOCTYPE html>
<html>
<head lang="en-US">
<meta charset="UTF-8">
<link rel="shortcut icon" href="img/icon.gif" />
<title>About - TheStudybox</title>
<meta name="Keywords" content=" about,  questions , answers , follow , students , academics , subjects , maths , english , knowledge " />
<meta name="Description" content="TheStudybox is a free, fast and easy way for students and academic enthusiasts to share knowledge on various academic subjects by asking and answering questions while earning SmartPoints as they expand their knowledge.">
<meta http-equiv="Content-Language" content="en-US">
<script src="js/jquery.js"></script>
<script src="js/general.js"></script>
<script src="js/jquery.visible.min.js"></script>
<!--[if IE 9]>
<link href="css/iefix.css" rel="stylesheet" type="text/css" />
<![endif]-->
<link href="css/general.css" rel="stylesheet" type="text/css" />

<script>
$(document).ready(function() {
	
	$(window).scroll(function(){
	$('.slide').each(function(){
	if($(this).visible()){
	$(this).find('section').show(1000,"swing");
	}
	});
	});
    
});
</script>

</head>
<body>

<div class="container">

<div class="header">
<?php require_once("config/inc/connect.php"); ?>
<?php include("config/fn/fn.php"); ?>
<?php include("config/inc/header.php"); ?>
</div>

<div class="wrapper">

<!--navigation  -->
<nav>
<ul>
<li><a href="questions.php">Questions</a></li>
<li><a href="unanswered.php">Unanswered</a></li>
<li><a href="ask.php">Ask Question</a></li>
<li><a href="notes.php">Notes</a></li>
</ul>
</nav>

<div class="template">

<div class="slide">
<center><img src="img/icon.gif" /><center>
<p>Hi, welcome to theStudybox. It's a free, fast and easy way for students and academic enthusiasts to share knowledge on various academic subjects by asking and answering questions while earning SmartPoints as they expand their knowledge.</p>
</div>

<div class="slide">
<article>
<h2>Ask questions</h2>
<p>You can ask questions on any subject by entering the subject tag. Whether it's a homework, assignment or just studying why not ask a question and let others help you with what they know.</p>
</article>
<section>
<img src="slides/slide1.jpg">
</section>
<div class="clear"></div>
</div>

<div class="slide">
<article>
<h2>Get answers</h2>
<p>When you post a question you will get responses from other students, who wish to share their knowledge of the problem with you. You post a question you get an answer, its that simple</p>
</article>
<section>
<img src="slides/slide2.jpg">
</section>
<div class="clear"></div>
</div>

<div class="slide">
<article>
<h2>Vote answers</h2>
<p>You can vote an answer up if you think it was uselful or down if you think it was not, whether you asked the question or not.</p>
</article>
<section>
<img src="slides/slide3.jpg">
</section>
<div class="clear"></div>
</div>

<div class="slide">
<article>
<h2>Accept Answer</h2>
<p>From all the answers provided under your questions, you can select only one answer as your accepted answer. Any accepted answer will will be indicated.</p>
</article>
<section>
<img src="slides/slide4.jpg">
</section>
<div class="clear"></div>
</div>

<div class="slide">
<article>
<h2>Vote Questions</h2>
<p>You can vote any question you find usefull also.</p>
</article>
<section>
<img src="slides/slide5.jpg">
</section>
<div class="clear"></div>
</div>

<div class="slide">
<article>
<h2>Earn SmartPoints</h2>
<p>By participating actively you can earn SmartPoints and increase your SmartRank.</p>

</article>
<section>
<img src="slides/slide8.jpg" style="width:300px;">
<ul class="list">
<li>Ask question : +20</li>
<li>Question voted up : +30</li>
<li>Post answer : +20</li>
<li>Answer voted up : +20</li>
<li>Answer Accepted : +40</li>
<li>Question voted down : -10</li>
<li>Answer voted down : -10</li>
</ul>
</section>
<div class="clear"></div>
</div>

<div class="slide">
<article>
<h2>Follow up discussions</h2>
<p>If you post an answer to a question and you want to be notified when more answers are posted under that same question, you can follow the disucssion. This action can be undone by unfollowing the discussion.</p>
</article>
<section>
<img src="slides/slide7.jpg">
</section>
<div class="clear"></div>
</div>

<div class="slide">
<article>
<h2>Notifications</h2>
<p>You will be notified when any action is performed on either questions you have asked, questions you follow, or answers you have provided.</p>
</article>
<section>
<img src="slides/slide6.jpg">
</section>
<div class="clear"></div>
</div>

<div class="slide" style="text-align:center">
<p><a href="ask.php">Post a question</a> now or browse our list of <a href="questions.php">questions</a> to see or if there is one you can answer. If you do not have an account, why not <a href="register.php">join</a> our ever expanding community of smart students now.</p>

<p><a href="login.php" class="button">Login</a></p>
or
<p><a href="register.php" class="button">Create an account</a></p>
</div>

</div>

<div class="footer">
<?php include("config/inc/footer.php") ?>
</div>

</div>

</body>
</html> <?php ob_flush(); flush() ; ?>