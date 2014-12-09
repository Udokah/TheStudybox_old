<?php 
session_start(); 
ob_start("ob_gzhandler");
if(!isset($_SESSION['uid'])){
	header("location: index.php");
}

?>     
<!DOCTYPE html>
<html>
<head lang="en-US">
<meta charset="UTF-8">
<link rel="shortcut icon" href="img/icon.gif" />
<meta name="robots" content="noindex, nofollow" />
<title>Profile</title>
<script src="js/lib.js"></script> 
<script src="js/jquery.js"></script> <!-- Jquery script -->
<script src="js/profile.js"></script> <!-- General display script -->
<script src="js/jquery.form.js"></script>

<!--[if IE 9]>
<link href="css/iefix.css" rel="stylesheet" type="text/css" />
<![endif]-->

<link href="css/general.css" rel="stylesheet" type="text/css" />
<link href="css/profile.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">
function LoadQuestions(lim){
startLoader();
var DataToSend = { lim : lim , action : 'LoadQuestionsByUser' } ;
$.ajax({
url: 'config/ajax/loader.php',
data: DataToSend,
success: function(returnedData){
	endLoader();
$('.load-questions-here .more').remove();
$('.load-questions-here').append(returnedData);
},
error: function () {
endLoader();
 var content2 = "<div class='onerror'><a onclick='LoadQuestions(" + lim + ")' href='#'>An Error Occured : Click here to reload questions</a> </div>" ;
$('.load-questions-here').html(content2);
$('.load-questions-here .onerror').remove();
    }
});
return false;  
}

function LoadAnsQue(lim){
	startLoader();
var DataToSend = { lim : lim , action : 'LoadAnsQueByUser' } ;
$.ajax({
url: 'config/ajax/loader.php',
data: DataToSend,
success: function(returnedData){
	endLoader();
$('.load-ans-here .more').remove();
$('.load-ans-here').append(returnedData);
},
error: function () {
	endLoader();
 var content2 = "<div class='onerror'><a onclick='LoadAnsQue(" + lim + ")' href='#'>An Error Occured : Click here to reload</a> </div>" ;
$('.load-ans-here').html(content2);
$('.load-ans-here .onerror').remove();
    }
});
return false;  
}

$(document).ready(function(){
	
	//// When Profile is to be edited
	$('#profileForm .edit').click(function(e){
		e.preventDefault();
		$('#profileForm .req').each(function() {
            $(this).removeAttr('disabled');
			if($(this).val() == '-----'){
				$(this).val('') ;
			}
        });
		 $(this).fadeOut('fast',function(){
			 $('#profileForm .save').show();
			  $('#profileForm .cancel').show();
			 });
	});
	
	//// When profile edit is canceled
		$('#profileForm .cancel').click(function(e){
		e.preventDefault();
		$('#profileForm .req').each(function() {
		 var content = $(this).attr('data-value');
		 $(this).val(content);
            $(this).attr('disabled','disabled');
			if($(this).val() == ''){
				$(this).val('-----') ;
			}
        });
		 $(this).fadeOut('fast',function(){
			 $('#profileForm .save').hide();
			  $('#profileForm .edit').show();
			 });
	});
	
	
   /// When edit is to be saved
   		$('#profileForm .save').click(function(e){
		e.preventDefault();
	var fullname = $('#fullname').val();
	var school = $('#school').val();
	var course = $('#course').val();
	var location = $('#location').val();
	var bio = $('#bio').val(); 
	
	if(fullname.length < 3){
$('#profileForm label').addClass('error').show().css('display','block').html('enter a valid name').delay(3000).fadeOut('slow', function(){
	$(this).removeAttr('class');
	});
	$('#fullname').focus();
	exit();
	}
	
var DataToSend = { fullname : fullname , school : school , course : course , location : location , bio : bio , action : 'SaveProfileEdit' } ;
startLoader() ;
// Send to ajax
$.ajax({
url: 'config/ajax/loader2.php',
data: DataToSend,
success: function(returnedData){
	endLoader();
eval(returnedData);
},
error: function () {
	endLoader();
$('#profileForm label').addClass('error').show().css('display','block').html('an error occured, changes were not saved').delay(3000).fadeOut('slow', function(){
	$(this).removeAttr('class');
	});
$('#profileForm .cancel').click();
    }
});
	
	});
	
	
	// change email settings
	$('.changeEmail .edit').click(function(e){
		e.preventDefault();
	$('.changeEmail').find('.req').removeAttr('disabled');
	$(this).hide();
	$('.changeEmail .save').fadeIn('fast');
	$('.changeEmail .cancel').fadeIn('fast');
	});
	
	/// Cancel email change
	$('.changeEmail .cancel').click(function(e){
		e.preventDefault();
	var email = $('.changeEmail').find('.req') ;
	var content = email.attr('data-value');
	email.val(content);
	email.attr('disabled','disabled');
	$(this).hide();
	$('.changeEmail .save').fadeOut('fast');
	$('.changeEmail .edit').fadeIn('fast');
	});
	
	/// Save email change
	$('.changeEmail .save').click(function(e){
	e.preventDefault();
	var email = $('.changeEmail').find('.req') ;
	var content = email.val();
	if(content == ''){
$('.changeEmail label').addClass('error').show().css('display','block').html('Email field is empty').delay(3000).fadeOut('slow', function(){
	$(this).removeAttr('class');
	});
		exit();
	}
	
	if(!Valid_email(content)){
$('.changeEmail label').addClass('error').show().css('display','block').html('invalid email address').delay(3000).fadeOut('slow', function(){
	$(this).removeAttr('class');
	});
		exit();
	}
	
var DataToSend = { email : content , action : 'ChangeEmailAddress' } ;
startLoader();
// Send to ajax
$.ajax({
url: 'config/ajax/loader2.php',
data: DataToSend,
success: function(returnedData){
	endLoader();
eval(returnedData);
},
error: function () {
	endLoader();
$('.changeEmail label').addClass('error').show().css('display','block').html('an error occured, changes were not saved').delay(3000).fadeOut('slow', function(){
	$(this).removeAttr('class');
	});
$('.changeEmail .cancel').click();
    }
});
	});
	
	
	/// change password
	$('#changePassForm .edit').click(function(e){
		
	e.preventDefault();
	$(this).fadeOut('fast', function(){
	$('.InputForm').fadeIn('slow', function(){
	$('#Oemail').focus();
	$('#changePassForm .save').fadeIn('fast');
	$('#changePassForm .cancel').fadeIn('fast');
	});
	});
	
	});
	
	
	/// cancel password change
	$('#changePassForm .cancel').click(function(e){
	e.preventDefault();
	$(this).fadeOut('fast');
	$('.InputForm').fadeOut('slow', function(){
	$('#changePassForm .save').hide();
	$('#changePassForm .edit').show();
	$('#changePassForm .req').each(function() {
		 $(this).val('');
    });
	});
	});
	
		/// save password changes
	$('#changePassForm .save').click(function(e){
	e.preventDefault();
	$('#changePassForm .req').each(function(){
		if($(this).val() == ''){
var msg = $(this).attr('data-alert') ;
$('#changePassForm label').addClass('warning').show().css('display','inline-block').html(msg).delay(3000).fadeOut('slow', function(){
	$(this).removeAttr('class');
	});
	$(this).focus();
	exit();
		}
    });
	
	var oldpass = $.trim($('#Opass').val());
	var newpass = $.trim($('#Npass').val());
	var cnpass = $.trim($('#Cpass').val());
	
	if(newpass.length < 5){
 $('#changePassForm label').addClass('warning').show().css('display','inline-block').html('Password should be more than 5 characters').delay(3000).fadeOut('slow', function(){
	$(this).removeAttr('class');
	});
	exit();
	}
	
	if(newpass != cnpass){
 $('#changePassForm label').addClass('warning').show().css('display','inline-block').html('Passwords do not match').delay(3000).fadeOut('slow', function(){
	$(this).removeAttr('class');
	});
	$('#Npass').val('');
	$('#Cpass').val('');
    exit();
	}
	
var DataToSend = { oldpass : oldpass , newpass : cnpass , action : 'ChangePassword' } ;
startLoader();
// Send to ajax
$.ajax({
url: 'config/ajax/loader2.php',
data: DataToSend,
success: function(returnedData){
endLoader();
eval(returnedData);
},
error: function () {
	endLoader();
$('#changePassForm label').addClass('error').show().css('display','block').html('an error occured, changes were not saved').delay(3000).fadeOut('slow', function(){
	$(this).removeAttr('class');
	});
$('#changePassForm .cancel').click();
    }
});	
	
	});

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
 
    /// Avatar Image
    $('.avatar').hover(function(){
	$(this).find('ul').show();
	}).mouseleave(function(){
	$(this).find('ul').hide();
	});
	
   $('.avatar .remove').click(function(e){
   e.preventDefault();
   var DataToSend = { action : 'removeImage'} ;
   startLoader();
   // Send to ajax
$.ajax({
url: 'config/ajax/loader.php',
data: DataToSend,
success: function(returnedData){
	endLoader();
eval(returnedData);
}
});	
   
   });

   $('.avatar .change').click(function(e){
   e.preventDefault();
   var uploadForm = "<form class='imgupload' enctype='multipart/form-data' method='post' action='config/uploadImage.php' >select Image<input type='file' name='avatar' class='imgfile' /><input type='hidden' name='action' value='UploadAvatar' /><input type='submit' id='uplBut' value='Upload' /></form>"
   
   Modal(uploadForm);
   });	

/// Upload New Image
$('body').on('click', '#uplBut' , function(e){
e.preventDefault();

/// Validate uploaded images
		 $('.imgfile').each(function() {
			
		/// if empty
		  if($(this).val() == ''){
		  alert('no image selected');
		  exit();
		  }
		  
		   /// Check if file is an image
			pattern = new RegExp(/(jpg|png|jpeg|gif)$/i);
			if(!pattern.test($(this).val())) {
			var err = 'Only JPG, PNG or GIF files are allowed!';
			alert(err);
			exit();
			}
			
			/* Check Image file limit Max:3MB 3145728 i.e error here i.e sucks !
			if(this.files[0].size > 4){
			alert("the image exceeded the file size limit");
		    exit();
			}
			*/
		 });
		 
$('#preview').html("<img src='img/load.gif' alt='Loading'/>");
$('.imgupload').ajaxForm({
	delegation: true , 
	success: function(data){
		    $('#preview').html(data);
		    },
	error: function(){
		   window.location = 'profile.php' ; // reload page on error
		    },
	}).submit();
$('.modal a.close').click();

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



<div class="userprofile">

<div class="navigation">
<ul>
<li> <a href="#Personal" class="active">Personal</a> </li>
<li> <a href="#Security">Security</a> </li>
<li> <a href="#Questions">Questions</a> </li>
<li> <a href="#Answers">Answers</a> </li>
</ul>
</div>

<?php
$qc = count_user_questions($_SESSION['uid']) ;
$ac = count_user_answers($_SESSION['uid']) ;
$profile = Student_profile($_SESSION['uid']);

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

<div class="preview">
<aside>
<h2>Profile</h2>
<section class="avatar">
<ul>
<li><a href="#" class="remove">remove</a></li>
<li><a href="#" class="change">change</a></li>
</ul>
<div id='preview'><?php echo $aviImage ; ?></div>
</section>
</aside>


<div id="Personal" class="box" >
<h2>Personal Information</h2>

<form action="#" id="profileForm" >
<table>
<caption><label style="display:none">here is a success notification</label></caption>
<tr>
<td>Name</td>
<td><input type="text" disabled="disabled" value="<?php echo $profile['fullname'] ?>" id="fullname" maxlength="40" class="req" data-value="<?php echo $profile['fullname'] ?>" /></td>
</tr>

<tr>
<td>School</td>
<td><input type="text" placeholder="whats the name of your institution of study?" disabled="disabled" value="<?php echo $profile['school'] ?>" id="school" maxlength="50" class="req"  data-value="<?php echo $profile['school'] ?>" /></td>
</tr>

<tr>
<td>Studying</td>
<td><input type="text" placeholder="what are you studying ?" disabled="disabled" value="<?php echo $profile['course'] ?>" id="course" maxlength="60" class="req" data-value="<?php echo $profile['course'] ?>" /></td>
</tr>

<tr>
<td>Location</td>
<td><input type="text" id="location" placeholder="where can one find you ?" disabled="disabled" value="<?php echo $profile['location'] ?>" data-value="<?php echo $profile['location'] ?>" maxlength="100" class="req" /></td>
</tr>

<tr>
<td>Smart Rank:</td>
<td><span class="smartrank" data-title="A higher value indicates a high level of smartness" ><?php 
$sr = USER_LEVEL($_SESSION['uid']) ;
echo $sr[0] ;
?></span>
</td>
</tr>

<tr>
<td>Bio</td>
<td><textarea id="bio" placeholder="In a few words how would you describe yourself ?" disabled="disabled" maxlength="500" data-value="<?php echo $profile['bio'] ?>" class="req" ><?php echo $profile['bio'] ?></textarea></td>
</tr>
</table>

<table>
<tr>
<td><input type="button" class="save" value="Save"/></td> 
<td><input type="button" class="edit" value="edit"/></td>
<td><input type="button" class="cancel" value="Cancel"/> </td>
</tr>
</table>
</form>
</div>

<div id="Security" class="box" >
<h2>Account Settings<div class="clear"></div></h2>
<table class="changeEmail">
<caption style="display:block; height:30px;"><label></label></caption>
<tr>
<td>
<input type="text" placeholder="You must enter an email address" disabled="disabled" value="<?php echo $profile['email'] ?>" id="email" maxlength="40" data-value="<?php echo $profile['email'] ?>" class="req" />
</td>
<td>
<input type="button" class="edit" value="Change Access Email"/> 
<input type="button" class="save" value="Save"/>
<input type="button" class="cancel" value="Cancel"/>
</td>
</tr>
</table>

<br/>

<form id="changePassForm">
<label></label>
<table class="InputForm">
<tr>
<td>Old password</td>
<td><input type="password" data-alert="Enter your old password" autocomplete="off" value="" id="Opass" maxlength="50" class="req" /></td>
</tr>
<tr>
<td>New password</td>
<td><input type="password" value="" data-alert="Enter a new password" id="Npass" maxlength="50" class="req" /></td>
</tr>
<tr>
<td>confirm new password</td>
<td><input type="password" value="" data-alert="re-enter the new password" id="Cpass" maxlength="50" class="req" /></td>
</tr>
</table>

<table>
<tr>
<td colspan="2" align="center">
<input type="button" class="edit" value="Change Password"/>
<input type="button" class="save" value="Save"/>
<input type="button" class="cancel" value="Cancel"/> 
</td>
</tr>
</table>
</form>

</div>

<div id="Questions">

<div class="box">

<h2>Questions you have asked  <span>Total : <?php echo $qc; ?></span><div class="clear"></div></h2>

<div class="load-questions-here"></div>

</div>
</div>

<div id="Answers">
<div class="box">

<h2>Answers you have provided<span>Total : <?php echo $ac ; ?></span><div class="clear"></div></h2>

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