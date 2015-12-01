<?php 
session_start() ; 
ob_start("ob_gzhandler"); // compress html output
if(!isset($_GET['user']) or $_GET['user'] == ''){
	header("location: index.php") ;
}
?>
<!DOCTYPE html>
<html>
<head lang="en-US">
<meta charset="UTF-8">
<link rel="shortcut icon" href="img/icon.gif" />
<title>Profile</title>
<meta name="robots" content="noindex, nofollow" />
<script src="js/lib.js"></script> 
<script src="js/jquery.js"></script> <!-- Jquery script -->
<script src="js/general.js"></script> <!-- General display script -->

<!--[if IE 9]>
<link href="css/iefix.css" rel="stylesheet" type="text/css" />
<![endif]-->

<link href="css/general.css" rel="stylesheet" type="text/css" />
<link href="css/profile.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">

 // Ajax loader Effect

function LoadQuestions(lim){
	var uid = $('#userid').val();
var DataToSend = { lim : lim , uid : uid , action : 'LoadQuestionsByUser' } ;
startLoader();
$.ajax({
url: 'config/ajax/loader.php',
data: DataToSend,
success: function(returnedData){
$('.load-questions-here .more').remove();
$('.load-questions-here').append(returnedData);
},
error: function () {
 var content2 = "<div class='onerror'><a onclick='LoadQuestions(" + lim + ")' href='#'>An Error Occured : Click here to reload questions</a> </div>" ;
$('.load-questions-here').html(content2);
$('.load-questions-here .onerror').remove();
    }
});
endLoader();
return false;  
}

function LoadAnsQue(lim){
startLoader();
	var uid = $('#userid').val();
var DataToSend = { lim : lim , uid : uid , action : 'LoadAnsQueByUser' } ;
$.ajax({
url: 'config/ajax/loader.php',
data: DataToSend,
success: function(returnedData){
$('.load-ans-here .more').remove();
$('.load-ans-here').append(returnedData);
},
error: function () {
 var content2 = "<div class='onerror'><a onclick='LoadAnsQue(" + lim + ")' href='#'>An Error Occured : Click here to reload</a> </div>" ;
$('.load-ans-here').html(content2);
$('.load-ans-here .onerror').remove();
    }
});
endLoader();
return false;  
}

  </script>
  
  <script type="text/javascript">
$(document).ready(function(){
    $('.navigation a').click(function(e){
	e.preventDefault();
    var get = $(this).attr('href');
	var current = $('.active').attr('href');
	
	if($(this).hasClass('active')){
	return false
	}
	else{
	
	$('.navigation a').each(function(){
	$(this).removeAttr('class');
	});
	
	$(this).addClass('active');
	$(current).hide();
	$(get).fadeIn('fast');
	
	if(get == '#Questions'){
	
	/// prevent double loading
	if(!$(get).hasClass('LOADED')){
	$(get).addClass('LOADED');
	LoadQuestions(0) ;
	}
	
	}
	else if(get == '#Answers'){
	
	/// prevent double loading
	if(!$(get).hasClass('LOADED')){
	$(get).addClass('LOADED');
	LoadAnsQue(0) ;
	}
	
	}
	
	}
		
	});
	
});
</script>

</head>
<body>

<div class="container">

<div class="header">
<?php require_once("config/inc/connect.php"); ?>
<?php require_once("config/fn/fn.php"); ?>
<?php require_once("config/inc/header.php"); ?>
<?php require_once("config/fn/lib.php"); ?>
</div>

<div class="wrapper">

<?php
$uid = clean($_GET['user']) ;
?>

<!--navigation  -->
<nav>
<ul>
<li><a href="questions.php">Questions</a></li>
<li><a href="unanswered.php">Unanswered</a></li>
<li><a href="ask.php">Ask Question</a></li>
<li><a href="notes.php">Notes</a></li>
</ul>
</nav>



<div class="userprofile">

<div class="navigation">
<ul>
<li> <a href="#Personal" class="active">Personal</a> </li>
<li> <a href="#Questions">Questions</a> </li>
<li> <a href="#Answers">Answers</a> </li>
</ul>
</div>

<?php
$qc = count_user_questions($uid) ;
$ac = count_user_answers($uid) ;
$profile = Student_profile($uid);

if(!isset($profile['fullname'])){
	exit();
}

foreach($profile as $key => $value){
	if($value == ''){
	$profile[$key] = '-----' ;	
	}
}

$avatar = $profile['avatar'] ;
if(file_exists('avatars-main/'.$avatar)){
$aviImage = "<img src='avatars-main/$avatar' alt='Avatar' />" ;
}
else{
$aviImage = '' ;	
}
?>

<input type="hidden" id="userid" value="<?php echo $uid ?>" />

<div class="preview">
<aside>
<h2><?php echo $profile['fullname'] ?></h2>
<section class="avatar">
<div id='preview'><?php echo $aviImage ; ?></div>
</section>
</aside>


<div id="Personal">

<form action="#" id="profileForm" >
<table>
<caption><label style="display:none">here is a success notification</label></caption>
<tr>
<td>Email</td>
<td><input type="text" id="email" disabled="disabled" value="<?php echo $profile['email'] ?>" /></td>
</tr>

<tr>
<td>School</td>
<td><input type="text" disabled="disabled" value="<?php echo $profile['school'] ?>" id="school"  /></td>
</tr>

<tr>
<td>Studying</td>
<td><input type="text" disabled="disabled" value="<?php echo $profile['course'] ?>" id="course" /></td>
</tr>

<tr>
<td>Location</td>
<td>
<input type="text" id="location" disabled="disabled" value="<?php echo $profile['location'] ?>" /></td>
</tr>

<tr>
<td>Smart Rank:</td>
<td><span class="smartrank" data-title="A higher value indicates a high level of smartness" >
<?php 
$sr = USER_LEVEL($uid) ;
echo $sr[0] ;
?></span>
</td>
</tr>

<tr>
<td>Bio</td>
<td><textarea id="bio" disabled="disabled"><?php echo $profile['bio'] ?></textarea></td>
</tr>
</table>
</form>
</div>

<div id="Questions">

<div class="box">

<h2>Questions asked  <span>Total : <?php echo $qc; ?></span><div class="clear"></div></h2>

<div class="load-questions-here"></div>

</div>
</div>

<div id="Answers">
<div class="box">

<h2>Answers provided<span>Total : <?php echo $ac ; ?></span><div class="clear"></div></h2>

<div class="load-ans-here">
</div>

</div>

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