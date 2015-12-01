<?php 
session_start() ;

require_once("config/inc/connect.php"); 
require_once("config/fn/lib.php"); 
require_once("config/fn/fn.php"); 

$loggedIn = 'No' ;
if(isset($_SESSION['uid'])){
$loggedIn = 'Yes' ;
}

?>     
<!DOCTYPE html>
<html>
<head lang="en-US">
<meta charset="UTF-8">
<link rel="shortcut icon" href="img/icon.gif" />
<title>Notes - TheStudybox</title>
<script src="js/jquery.js"></script>
<script src="js/jquery.form.js"></script>
<script src="js/lib.js"></script>
<script src="js/general.js"></script>
<!--[if IE 9]>
<link href="css/iefix.css" rel="stylesheet" type="text/css" />
<![endif]-->
<link href="css/general.css" rel="stylesheet" type="text/css" />

<style type="text/css">

.notes{
width:100%;
margin-top:20px;
margin-bottom:30px;
}

.notes tr th{
text-align:left;
padding:5px;
background-color:#ccc;
color:#000;
font-weight:normal;
font-size:16px;
}

.notes tr td{
padding:6px;
font-size:15px;
border-bottom:1px solid #ddd;
color:#333;
}

.notes tr:nth-child(odd) {
    background-color: #eee;
}

.notes tr td:first-child {
font-size:12px;
width:70px;
}

.notes tr td:last-child{
width:70px;
}

.notes tr td a{
color:blue;
text-decoration:underline;
}

.upload-btn{
margin-top:10px;
margin-bottom:10px;
font-family:Tahoma, Geneva, sans-serif;
display:inline-block;
font-size:15px;
 background-color: #d14836;
   text-shadow: 0 1px rgba(0,0,0,0.1);
  background-image: -webkit-gradient(linear,left top,left bottom,from(#dd4b39),to(#d14836));
  background-image: -webkit-linear-gradient(top,#dd4b39,#d14836);
  background-image: -moz-linear-gradient(top,#dd4b39,#d14836);
  background-image: -ms-linear-gradient(top,#dd4b39,#d14836);
  background-image: -o-linear-gradient(top,#dd4b39,#d14836);
  background-image: linear-gradient(top,#dd4b39,#d14836);
color:#FFF;
padding:8px 20px;
-webkit-transition: background 250ms ease-out;
-moz-transition: background 250ms ease-out;
-o-transition: background 250ms ease-out;
}

.upload-btn:hover{
  text-shadow: 0 1px rgba(0,0,0,0.3);
  background-color: #c53727;
  background-image: -webkit-gradient(linear,left top,left bottom,from(#dd4b39),to(#c53727));
  background-image: -webkit-linear-gradient(top,#dd4b39,#c53727);
  background-image: -moz-linear-gradient(top,#dd4b39,#c53727);
  background-image: -ms-linear-gradient(top,#dd4b39,#c53727);
  background-image: -o-linear-gradient(top,#dd4b39,#c53727);
  background-image: linear-gradient(top,#dd4b39,#c53727);
}

.upload-btn:active {
  background-color: #b0281a;
  background-image: -webkit-gradient(linear,left top,left bottom,from(#dd4b39),to(#b0281a));
  background-image: -webkit-linear-gradient(top,#dd4b39,#b0281a);
  background-image: -moz-linear-gradient(top,#dd4b39,#b0281a);
  background-image: -ms-linear-gradient(top,#dd4b39,#b0281a);
  background-image: -o-linear-gradient(top,#dd4b39,#b0281a);
  background-image: linear-gradient(top,#dd4b39,#b0281a);
  -webkit-box-shadow: inset 0 1px 2px rgba(0,0,0,0.3);
  -moz-box-shadow: inset 0 1px 2px rgba(0,0,0,0.3);
  box-shadow: inset 0 1px 2px rgba(0,0,0,0.3);
  color: #fff
  }

.navPages a{
display:inline-block;
padding:4px 10px ;
font-size:12px;
color:#111;
border:1px solid #ddd;
margin:6px;
font-weight:bold;
background-color:#ddd;
-webkit-transition: background 250ms ease-out;
-moz-transition: background 250ms ease-out;
-o-transition: background 250ms ease-out;
}

.navPages a:hover , .navPages a.selected{
background-color:#fff;
}

.noteForm{
display:inline-block;
margin-top:5px;
border:1px solid #ddd;
padding:3px;
}

.noteForm input[type=text]{
padding:5px;
width:400px;
font-size:16px;
border:none;
}

.noteForm input[type=submit]{
border:none;
padding:5px 30px;
font-size:16px;
font-weight:bold;
background-color:#eee;
text-transform:uppercase;
-webkit-transition: background 250ms ease-out;
-moz-transition: background 250ms ease-out;
-o-transition: background 250ms ease-out;
}

.noteForm input[type=submit]:hover{
background-color:#ddd;
}

.noteForm input[type=submit]:focus{
background-color:#ccc;
}

.LoadingImg{
display:none;
margin-top:20px;
margin-left:20px;
}

#uploadNote{
background-color:#ecf0f1;
padding:4px;
border:1px solid #bdc3c7;
display:none;
}

#uploadNote table{
width:100%;
}

#uploadNote table tr td{
padding:3px;
color:#333;
font-size:16px;
}

#uploadNote input[type=text]{
padding:4px;
font-size:15px;
}

#uploadNote input[type=submit]{
padding:5px 20px;
font-size:13px;
background-color:#2ecc71;
border:1px solid #27ae60;
color:#fff;
text-transform:uppercase;
-webkit-transition: background 250ms ease-out;
-moz-transition: background 250ms ease-out;
-o-transition: background 250ms ease-out;
}

#uploadNote input[type=submit]:hover{
background-color:#27ae60;
}

#uploadNote input[type=submit]:focus{
background-color:green;
}

#uploadNote #title{
width:230px;
}

#uploadNote #course{
width:170px;
}

#uploadNote #level{
width:50px;
text-align:center;
}

.know{
display:inline-block;
color:#333;
font-size:15px;
margin-top:10px;
margin-left:8px;
}

.srH1{
display:none;
}

.all{
display:none;
color:blue;
text-decoration:underline;
font-size:15px;
margin-top:10px;
}

.remove{
font-size:12px;
color:red !important;
}

</style>
<script>

// function to reset form
jQuery.fn.reset = function () {
  $(this).each (function() { this.reset(); });
}

/// Load Files
function LoadFiles(page){
    var searchMode = $('#searchMode').val();
	var dataToSend = { action : 'LoadFiles' , page : page , searchMode : searchMode } ;
	
	showLD(); /// show loader
	$.ajax({
	url: 'addon-notes/ajax-server.php',
	data: dataToSend,
	success: function(returnedData){
	hideLD() ;
	eval(returnedData);
	},
	error: function (){
	hideLD();
    Modal('An error occured, re-trying');
	LoadFiles(page) ;
	}
	});
}

function showLD(){
$('.questionsroll').find(':submit').attr('disabled','disabled').css('opacity','0.8');
$('.LoadingImg').show();
}

function hideLD(){
$('.questionsroll').find(':submit').removeAttr('disabled').css('opacity','1');
$('.LoadingImg').hide();
}

    $(document).ready(function(){ 
	
	LoadFiles(0) // Load Files
	$('.know').hide();
	
	$('.noteForm').on('submit', function(e){
	e.preventDefault();
	var s = $.trim($('#sbox').val());
	if( s == '' ){
	alert('Enter a search term !');
	exit();
	}
	
	// check if user is logged in
	var loggedIn = $('#loggedIn').val();
	if( loggedIn == 'No'){
	Modal("You have to <a href='login.php'>Log in</a> to continue. Don't have an account? <a href='register.php'>Register now</a> ");	
	hideLD() ;
	exit();
	}
	
	$('#searchMode').val(s);  //turn on search mode
	$('.srH1').show();
	$('.all').show();
	LoadFiles(0) ;  // call search function.
	});
	
	// Delete a Note
	$('body').on('click', '.remove' , function(e){
	e.preventDefault();
	var verify = confirm('sure you want to delete this note ?');
	if( verify === true){
	var filename = $(this).attr('href');
	var dataToSend = { action : 'delete-file' , filename : filename } ;
	$.post('addon-notes/ajax-server.php', dataToSend);
	$(this).parent('td').parent('tr').fadeOut('fast', function(){
	$(this).remove();
	});
	}
	});
	
	/// pagination links 
	$('body').on('click', '.navPages a' , function(e){
	e.preventDefault();
	var page = Number($(this).attr('href'));
	$('.navPages a').each(function(){
	$(this).removeAttr('class');
	});
	$(this).addClass('selected');
	LoadFiles(page) ;
	});
	
	$('.upload-btn').click(function(e){
	e.preventDefault();
	$('#uploadNote').slideToggle('fast') ;
	$('.know').fadeToggle('fast');
	});
	
	$('#uploadNote').submit(function(e){
	e.preventDefault();
    options = {
			beforeSubmit:	request,
			success:   postSuccess,  // post-submit callback 
			error: postError,
			timeout:   1000000 
 		     };
		
		showLD() ; /// show loading
		$(this).ajaxSubmit(options);
		
		function postSuccess(responseData){
		eval(responseData);
		hideLD() ;
		}
		
		function postError(){
		alert('Upload failed, might be connectivity issues. try again later');
		hideLD() ;
		}
		
		function request(){
		$('#uploadNote .req').each(function(){
		if($(this).val() == ''){
		Modal( $(this).attr('data-alert') );
		hideLD() ;
		exit();
		}
		});
		
		if(!isInt($('#level').val())){
		Modal( $('#level').attr('data-alert') );
		hideLD() ;
		exit();
		}
		
	    var pattern = new RegExp(/(doc|docx|pdf|ppt)$/i);
		if(!pattern.test($('#file').val())) {
		err = 'Only Microsoft Word, PDf and PowerPoint files are allowed!';
		$('#file').val(''); 
		hideLD() ;
		Modal(err);
		exit();
		}
		
	// check if user is logged in
	var loggedIn = $('#loggedIn').val();
	if( loggedIn == 'No'){
	Modal("You have to <a href='login.php'>Log in</a> to continue. Don't have an account? <a href='register.php'>Register now</a> ");	
	hideLD() ;
	exit();
	}
		
		}
		
  });
	
});

</script>

    <?php 
	// If user is not logged in , prevent download
	if(!isset($_SESSION['uid'])){
    echo "
	<script>
	$(document).ready(function(){ 
	$('body').on('click', '#ldhere a' , function(e){
	e.preventDefault();
	Modal(\"You have to <a href='login.php'>Log in</a> to download this Note. Don't have an account? <a href='register.php'>Register now</a> \");	
	hideLD() ;
	exit();
	});
	});
	</script>" ;
    }
	?>
</head>
<body>

<div class="container">

<div class="header">
<?php include("config/inc/header.php"); ?>
</div>

<script>
var orig_Mybasefunction = window.LOAD_NOTIFICATIONS;
window.LOAD_NOTIFICATIONS = function(){
return false ;
}
</script>

<div class="wrapper">

<div class="leftbar">

<!--navigation  -->
<nav>
<ul>
<li><a href="questions.php" >Questions</a></li>
<li><a href="unanswered.php">Unanswered</a></li>
<li><a href="ask.php">Ask Question</a></li>
<li><a href="notes.php" class="current" >Notes</a></li>
</ul>
</nav>

<!-- Top Questions  -->
<div class="questionsroll">
<h1>Notes</h1>

<input type="hidden" id="loggedIn" value="<?php echo $loggedIn ; ?>" />

<a href='notes.php' class='all'>See All</a><br />
<form class="noteForm">
<input type="text" id='sbox' placeholder="Search for notes here" /><input type="submit" value="find" />
<input type="hidden" id="searchMode" value="OFF" />
</form><img class="LoadingImg" src="addon-notes/img/loader.gif" />

<div>
<a href="#" class='upload-btn'>Upload New</a>
<span class="know">Max file size: 9mb.<br />Any Uploaded Note will deleted after 21 days.</span>
</div>

<form id="uploadNote" action="addon-notes/file_uploader.php" method="POST" >
<input type="hidden" name="action" id="action" value="upload-notes" />

<table>
<tr><td>Title</td><td>Course</td><td>Level</td></tr>
<tr>
<td><input type="text" class="req" data-alert="No title entered" id="title" name="title" placeholder="Note title here" maxlength="70" /></td>
<td><input type="text" class="req" data-alert="enter course name" id="course" name="course" placeholder="Course here" maxlength="50" /></td>
<td>
<input type="text" class="req" maxlength="3" data-alert="invalid level, Must be numerical" name="level" id="level" placeholder="Level" /></td>
</tr>
<tr><td colspan="2"><input type="file" data-alert="Please select a document to upload" class="req" name="files" id="file" /></td><td><input type="submit" value="Upload It" /></td></tr>
</table>
</form>

<table class="notes">
<tr><th colspan=5><span class='srH1'>Search Results</span></th></tr>
<tr><th>Date</th><th>Title</th><th>Course</th><th>Level</th><th></th></tr>
<tbody id="ldhere">
</tbody>
</table>

<img class="LoadingImg" src="addon-notes/img/loader.gif" />

<div class="navPages">

</div>

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