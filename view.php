<?php 
session_start() ;
ob_start("ob_gzhandler"); // compress html output
// In case of a hack attempt
if(!isset($_GET['q']) and !isset($_GET['tk'])){
header("location: index.php");
}
elseif(isset($_GET['q']) and isset($_GET['tk'])){
header("location: index.php");
}


require_once("config/inc/connect.php");
require_once("config/inc/class.html2text.inc");
require_once("config/fn/fn.php");
require_once("config/fn/lib.php");


// If token is set , get question original ID
if(isset($_GET['tk'])){
	$tk = clean($_GET['tk']); 
$q = get_q_id($_GET['tk']);
// If question was not found
if($q == false){
echo "Question not found !" ;
exit();
}
}
else{
$q = clean($_GET['q']);
}

$title = Get_q_data($q,'title') ;  /// get title
$qtags = Get_q_data($q,'tags') ;   /// get question tags

$qc = 'cookies'.$q.'cookies' ;

// Count Views
if(!isset($_SESSION["$qc"])){
$_SESSION["$qc"] = $qc ; 
count_views($q);
}

$questionRaw = Get_q_data($q,'question') ;  // get question with html
$body = html_entity_decode($questionRaw) ; // to be displayed parse html

// generate meta
$meta = Generate_meta($questionRaw,$qtags,$title) ;
 
 // SET SHARING PARAMS
$domain = $_SERVER['SERVER_NAME'];
$url = 'http://'.$domain.$_SERVER['SCRIPT_NAME'].'?';
$url .= $_SERVER['QUERY_STRING'] ; 
?>     
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<link rel="shortcut icon" href="img/icon.gif" />
<title><?php echo $title ; ?> - TheStudybox</title>
<meta name="Keywords" content="<?php echo $meta['keywords']; ?>" />
<meta name="Description" content="<?php echo $meta['description'] ; ?>" />
<meta http-equiv="Content-Language" content="en-US" />

<meta property="og:title" content="<?php echo $title ; ?> - TheStudybox" />
<meta property="og:description" content="<?php echo $meta['description'] ; ?>" />
<meta property="og:type" content="website" />
<meta property="og:url" content="<?php echo $url ; ?>" />
<meta property="og:image" content="http://www.thestudybox.com/img/icon.gif" />

<script src="js/jquery.js"></script>
<script src="js/general.js"></script>
 <script src="js/jquery.form.js"></script>
 <script src="js/niceEdit/nicEdit.js" type="text/javascript"></script>
<!--[if IE 9]>
<link href="css/iefix.css" rel="stylesheet" type="text/css" />
<![endif]-->
<link href="css/general.css" rel="stylesheet" type="text/css" />
<link href="css/view.css" rel="stylesheet" type="text/css" />

<script>
var myEditor ;

bkLib.onDomLoaded(function() {
myEditor = new nicEditor({buttonList : ['bold','italic','ol','ul','subscript','superscript','link','unlink']}).panelInstance('response',{hasPanel : true});
});


$(function() {	

localStorage.setItem("EDITMODE", 0); // set editmode to off

/// show tools on hover
$('body').on('mouseenter', 'fieldset' , function(){
	$(this).find('.ansTools').css('visibility','visible');
});
$('body').on('mouseleave', 'fieldset' , function(){
	$(this).find('.ansTools').css('visibility','hidden');
});

////////  #### PROCESS RATING CLICK ####./////////////////
$('body').on('click', '.rateAnswer' , function(e){
e.preventDefault();

var type = $(this).attr('data-type') ;
var img = $(this).attr('data-img') ;

var aid = $(this).parent().attr('data-aid') ;
var action = $(this).parent().attr('data-action') ;
var status = $(this).parent().attr('data-status') ;

//// check if user is not logged in
if(action == 500 ){
Modal("You have to <a href='login.php'>Log in</a> to vote. Don't have an account? <a href='register.php'>Register now</a> ");
exit();
}

// check if user is the one that asked the question
if(action == 101 ){
Modal("You Cannot vote your own answers");
exit();
}

/// ##### When user tries to vote now
/// check if user is trying to double vote an answer i.e vote an answer up and down
if(status == 'vote-up' && type == 'voteDown'){
Modal("You can't vote this answer down, until you undo the 'vote-up'.");
exit();
}

if(status == 'vote-down' && type == 'voteUp'){
Modal("You can't vote this answer up, until you undo the 'vote-down'.");
exit();
}
/// end of double voting checking ////######

/// Condition to undo voting
if(action == 'undo-vote'){
$(this).html("") ; // remove image
undoVote(aid) ;  // undo vote it doesnt matter which as only one vote is on the system
$(this).parent().attr('data-action','vote-open') ;
$(this).parent().attr('data-status','') ;
exit();
}

/// condition to vote now
if(action == 'vote-open'){

var v = '' ;
var content = '' ;
var ud = '' ; /// undo type

/// To vote-up
if(type == 'voteUp'){
v = 1 ;
ud = 'vote-up' ;
content = "<img src='img/voteup2.png' />" ;
}
else{
v = 0 ;
ud = 'vote-down' ;
content = "<img src='img/votedown2.png' />" ;
}

$(this).html(content);
RateAnswer(aid,v);
/// Reset switches
$(this).parent().attr('data-action','undo-vote') ;
$(this).parent().attr('data-status',ud) ;
exit();
}

});
////////  #### PROCESS RATING CLICK ####./////////////////

////////  #### PROCESS ACCEPTANCE CLICK ####./////////////////
$('body').on('click', '.accept' , function(e){
e.preventDefault();

var aid = $(this).parent().attr('data-aid') ;
var stat = $(this).attr('data-switch') ;

if(stat == 0){
Modal("You have to <a href='login.php'>Log in</a> to accept this answer. Don't have an account? <a href='register.php'>Register now</a> ");
exit();
}

var verfiy = confirm("sure you want to accept this answer ?");

if(verfiy === false){
exit();
}

$('.accepted').fadeOut('fast', function(){
$('.avatar-on').removeAttr('class').addClass('avatar-off');
$('#Ans' + aid).find('.avatar-off').removeAttr('class').addClass('avatar-on');
$(this).prev('hr').remove();
$(this).remove();
});

$(this).replaceWith("<span class='accepted' title='This answer has been accepted' >accepted<span> ");
Accept_Answer(aid) ;

});

});

/// Accept Answer
function Accept_Answer(aid){
startLoader();
$.ajax({
url: 'config/ajax/Responses.php',
data: 'action=acceptAnswer' + '&aid=' + aid,
success: function(){
endLoader();
Modal('The answer has been accepted, thanks for your feedback.');
},
error: function() {
endLoader();
Modal('An error occured while accepting the answer') ;
    }
});
}

// Undo Vote
function undoVote(aid){
startLoader();
$.ajax({
url: 'config/ajax/voter.php',
data: 'action=removeVote' + '&aid=' + aid,
error: function() {
endLoader();
Modal('An error occured while removing you vote on the response.') ;
    }
});
endLoader();
}

// Vote question
function VoteQuestion(type){
var vs = Number($('.pg').attr('data-vote'));

if(vs == 0){
Modal("You have to <a href='login.php'>Log in</a> to vote. Don't have an account ? <a href='register.php'>Register now</a> ");
}
else{
var qid = Number($('.pg').attr('data-qid'));
startLoader();
$.ajax({
url: 'config/ajax/voter.php',
data: 'action=votequestion' + '&type=' + type + '&qid=' + qid,
success: function(){
endLoader();
$('.vote').fadeOut('fast', function(){
Modal('Thank you for your feedback.');
});
},
error: function () {
endLoader();
Modal('An error occured while sending feedback.') ;
    }
});
}
}

// Post New response
function postAnswer(answer){
var qid = $('#qid').val();
$('#PostResponse :submit').attr("disabled","disabled");
$('#PostResponse :submit').val("Posting response..");
startLoader();
$.ajax({
url: 'config/ajax/Responses.php',
data: 'action=addResponse' + '&answer=' + answer + '&qid=' + qid,
success: function(Databack){
$('#loadhere').append(Databack);
endLoader();
},
error: function () {
Modal('An error occured while posting your response.') ;
endLoader();
    }
});
$('#PostResponse :submit').removeAttr("disabled") ;
$('#PostResponse :submit').val("Add Response");
}

 function LoadResponses(){
 var content = "<div class='onerror'><a href='#' disabled >Retrieving responses Please wait <br> <img src='img/loader1.gif'/></a></div>" ;
 $('#loadhere').html(content);
 var qid = $('#qid').val();
$.ajax({
url: 'config/ajax/Responses.php',
data: 'action=LoadResponses' + '&qid=' + qid,
success: function(returnedData){
$('#loadhere').html(returnedData);

/*
 hide duplicate answer if user views
 this page with intent of viewing their answers
*/
if($('#UsersAnswer').val() == '1'){
var ans = $('#UsersAnswer').attr('data-hide');
$('#' + ans).remove();
}
},
error: function () {
 var content2 = "<div class='onerror'><a onclick='LoadResponses()' href='#'>An Error Occured : Click here to reload responses</a> </div>" ;
$('#loadhere').html(content2);
    }
});
return false;
  }
  
  ///Load More
   function LoadMore(page){
 var content = "<div class='onerror'><a href='#' disabled >Retrieving more responses Please wait <br> <img src='img/loader1.gif'/></a></div>" ;
 $('#loadhere').append(content);
 var qid = $('#qid').val();
$.ajax({
url: 'config/ajax/Responses.php',
data: 'action=LoadMore' + '&qid=' + qid + '&page=' + page,
success: function(returnedData){
$('#loadhere').append(returnedData);
$('.onerror').remove();
page++ ;
$('.more').attr('data-page',page) ;

/*
 hide duplicate answer if user views
 this page with intent of viewing their answers
*/
if($('#UsersAnswer').val() == '1'){
var ans = $('#UsersAnswer').attr('data-hide');
$('#' + ans).remove();
}
},
error: function () {
 var content2 = "<div class='onerror'><a onclick='LoadMore(" + page + ")' href='#'>An Error Occured : Click here to reload more responses</a> </div>" ;
$('#loadhere').append(content2);
    }
});
return false;
  }
  
  /// ### Rate answer ////////////////
  function RateAnswer(aid,type){
   var qid = $('#qid').val();
  startLoader();
 $.ajax({
url: 'config/ajax/Responses.php',
data: 'action=rateAnswer' + '&aid=' + aid + '&qid=' + qid + '&type=' + type,
success: function(){
endLoader();
},
error: function () {
endLoader();
Modal('An error occured rating the answer') ;
    }
});
  }
  
/// fetch followers
function Get_Followers(qid){
var content = "<div class='onerror'><a href='#' disabled >Loading Please wait <br> <img src='img/loader1.gif'/></a></div>" ;
$('#LdFollowers').html(content);
var sendData = { action : 'LoadFollowers' , qid : qid } ;
$.ajax({
url: 'config/ajax/Responses.php',
data: sendData,
success: function(retData){
$('#LdFollowers').html(retData);
},
error: function () {
 var content2 = "<div class='onerror'><a onclick='Get_Followers(" + qid + ")' href='#'>An Error Occured : Click here to reload followers</a> </div>" ;
$('#LdFollowers').html(content2);
    }
});
}
  
  
  ///////////////////// Bindings

$(document).ready(function(){

// when counter is clicked
$('.vote a.pg').on('click', function(e){
e.preventDefault();
var msg = $(this).attr('data-title');
Modal(msg) ;
});

// Vote yes to question
$('.vote a.yes').on('click', function(e){
e.preventDefault();
VoteQuestion(1);
});

// Vote no to question
$('.vote a.no').on('click', function(e){
e.preventDefault();
VoteQuestion(0);
});

// Animate box
$('#response').on('focus', function(){
$(this).animate({ height: '100px' } , '50');
$(this).css('background' , '#FFF');
})

$('#response').on('blur', function(){
if($(this).val() == ''){
$(this).animate({ height: '40px' } , '50');
$(this).css('background' , '#F7F7F7');
}
else{
$(this).css('background' , '#FFF');
}
})



// To post response
$('#PostResponse :submit').on('click', function(e){
e.preventDefault();
var ps = Number($('#pstat').val()); // Post status yes or no

// if not logged in
if(ps == 0){
Modal("You have to <a href='login.php'>Log in</a> to post an answer. Don't have an account? <a href='register.php'>Register now</a> ");
}
else{
var answer = $.trim(myEditor.instanceById('response').getContent());
var len = answer.length ; 
if(len < 10){
Modal('Your answer does not meet our quality standard.') ;
}
else{
$(this).attr('disabled','disabled');
postAnswer(answer);
}
}

});

// To Load more answers
$('#showmoreanswers').on('click', function(e){
e.preventDefault();
var page = Number($(this).attr('data-page')) ;
LoadMore(page) ;
});


/// Perform followership
$('body').on('click', '#LdFollowers a' , function(e){
e.preventDefault();

var permission = Number($(this).attr('data-perm')) ;
var action = $(this).attr('data-action') ;
var qid = Number($(this).attr('data-qid')) ;
var verify = confirm('Are you sure you want to ' + action + ' this discussion');

if(verify === true){
if(permission == 0){
Modal("You have to <a href='login.php'>Log in</a> to follow this question. Don't have an account? <a href='register.php'>Register now</a> ");
exit();
}

var dataSend = { action : 'DoFollowership' , qid : qid , type : action } ;

$.post('config/ajax/Responses.php', dataSend ,function(dataBack){
eval(dataBack);
Get_Followers(qid) ;
  });
}

});

/// Load Responses
LoadResponses() ;

// Delete question
$('.userActions .delete').click(function(e){
	e.preventDefault();
   var verify = confirm('sure you want to delete this question ?');
  
  if(verify === true){
	var qid = $('#qid').val();
	var delEdit = { action : 'deletequestions' , qid : qid } ;
	$.post('config/ajax/loader.php', delEdit , function(dataBack) {
    eval(dataBack);
    });
  }
});


////// Answer inline editing
// Edit
var editAns ;
$('body').on('click', '.ansTools .edit' , function(e){
	e.preventDefault();
	
		var editable = $(this).attr('data-edit');
	
	if(editable == 500){
Modal("You have to <a href='login.php'>Log in</a> to edit this question. Don't have an account? <a href='register.php'>Register now</a> ");
exit();
	}
	else if(editable == 0){
Modal("You do not have enough priviledges to edit this question.");
exit();
	}
	
	var checkEdit = Number(localStorage.getItem("EDITMODE"));
	if(checkEdit != 0){
	Modal('You are already editing an answer, editing more than one answer at a time is not allowed');
	exit();
	}
	
var aid = $(this).attr('data-aid');
var editArea = 'edit-' + aid ;

var restoreHtml = $('#' + editArea).html();  // get old html
		 editAns = new nicEditor({buttonList : ['bold','italic','ul','ol','subscript','superscript','link','unlink']},{maxHeight : 100}).panelInstance(editArea,{hasPanel : true});
		  
		  $(this).hide();
		  var usertools = $(this).parent('li').parent('ul');
		  usertools.find('.delete').hide();
		  usertools.find('.save').show();
		  usertools.find('.cancel').show();
		  
		  usertools.parent('div').prev('div').prev('div').hide();
		  
		  localStorage.setItem("EDITMODE", 1); // switch on EditMode
		  localStorage.setItem("RESTORE", restoreHtml); // switch on EditMode
		  localStorage.setItem("aid", aid); // store id of current answer

});

/// Cancel Editing
$('body').on('click', '.ansTools .cancel' , function(e){
e.preventDefault();
var aid = $(this).attr('data-aid');
var editArea = 'edit-' + aid ;

editAns.removeInstance(editArea); // remove instance from div
editAns = null ;

var restore = localStorage.getItem("RESTORE");
$('#' + editArea).html(restore);
		  $(this).hide();
		  var usertools = $(this).parent('li').parent('ul');
		  usertools.parent('div').prev('div').prev('div').show();
		  usertools.find('.delete').show();
		  usertools.find('.edit').show();
		  usertools.find('.save').hide();
          localStorage.setItem("EDITMODE", 0); // switch off EditMode
		  localStorage.removeItem('aid') ;
		  localStorage.removeItem('RESTORE') ;
});

/// Deleting Content
$('body').on('click', '.ansTools .delete' , function(e){
	e.preventDefault();
	startLoader();
if($(this).attr('data-mode') != 1){
Modal('This answer already has up-votes or has been accepted, it can no longer be deleted, if you want it removed please vote it down.');
endLoader();
exit();
}

	var aid = Number($(this).attr('data-aid'));
	var delAns = { action : 'removeAnswer' , aid : aid } ;
	$.post('config/ajax/loader.php', delAns , function(dataBack) {
    eval(dataBack);
	endLoader();
    });
});

/// Save content
$('body').on('click', '.ansTools .save' , function(e){
	e.preventDefault();
	var aid = $(this).attr('data-aid');
	
	var editArea = 'edit-' + aid ;
    $('#' + editArea).addClass('EDIT-DIV');  /// add class to edit div
	var NewEdits = nicEditors.findEditor(editArea).getContent();
	
	if(NewEdits.length < 10){
		Modal('Your new answer does not meet our quality standards.');
		exit();
	}
	
	localStorage.setItem("NewEdits", NewEdits); // store old edit
	
	var saveEdits = { action : 'saveEditsAnswer' , aid : aid , edit : NewEdits} ;
	
    startLoader();
	
	$.post('config/ajax/loader.php', saveEdits , function(dataBack) {
	editAns.removeInstance(editArea); // remove instance from div
    editAns = null ;
    endLoader();
    eval(dataBack);
	      localStorage.removeItem('NewEdits') ;
		  localStorage.setItem("EDITMODE", 0); // switch off EditMode
		  localStorage.removeItem('aid') ;
		  localStorage.removeItem('RESTORE') ;
    });
	$(this).hide();
		  var usertools = $(this).parent('li').parent('ul');
		  usertools.parent('div').prev('div').prev('div').show();
		  usertools.find('.delete').show();
		  usertools.find('.edit').show();
		  usertools.find('.cancel').hide();

});

});

</script>
</head>
<body>

<div class="container">

<div class="header">
<?php 
include("config/inc/header.php"); 
$qvotes = count_q_votes($q); // count question votes
$res_count = total_answers($q) ;
?>
</div>

<div class="wrapper">

<div class="leftbar">

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
<div class="questionsview">
<a href="" class="title"><?php echo $title ; ?></a>

<div class="questionbody">

<div class='bodyArea' ><?php echo $body ;?></div>

<div class="userActions">
<ul>
<li><a href="edit.php?q=<?php echo $q; ?>" class="edit">Edit</a></li>
<li><a href="#" class="delete">Delete</a></li>
</ul>
</div>


<div class="share">
<?php
// Facebook Sharer
$fb = "http://www.facebook.com/sharer/sharer.php?u=".$url ;
$twLink = "http://twitter.com/intent/tweet?text=".$title.' '.$url ;
$tw = substr($twLink, 0, 137).'...' ;  /// shorten for twitter
$li = "http://www.linkedin.com/shareArticle?mini=true&url=".$url ;
?>
Share this question<br>
<a href="<?php echo $fb ; ?>"> 
<img src="img/facebook.png" align='top' title="share on Facebook" /> 
</a>

<a href="<?php echo $li ; ?>"> 
<img src="img/linkedin.png" align='top' title="share on LinkedIn" /> 
</a>

<a href="<?php echo $tw ; ?>">
<img src="img/twitter.png" align='top' title="Tweet this" />
</a>

</div>

<div class="clear"></div>
</div>

<div class="vote" style="font-size:16px;">
<?php
$voteBar = create_voter($q);
echo $voteBar ;

if(isset($_SESSION['uid'])){
$fullname = $_SESSION['fullname'] ;
$pstat = 1 ;
}
else{
$fullname = '' ;
$pstat = 0 ;
}
?>
</div>

<br>

<div class="responses" id="responses">
<h2>Responses</h2>


<?php 
if(isset($_GET['showanswer']) && $_GET['showanswer'] == 'yes'){
$userAnswer = Load_Responses( "" , $_GET['aid'] , $_GET['q'] , 1 ) ;
foreach($userAnswer as $value){
	echo $value ;
}
/// get id to stop answer from repeating when fetched with ajax
$i = 1 ;
$d = 'Ans'.$_GET['aid'] ;
}
else{
$i = 0 ;
$d = ''; 
}
?>
<input type="hidden" id="UsersAnswer" data-hide="<?php echo $d; ?>" value="<?php echo $i ; ?>" />
<div id="loadhere">
</div>


<div class="pages" style="text-align:center;">
<a href='#' style="width:200px" data-page="1" id="showmoreanswers" class="more">show more answers</a>
</div>

</div>


<div class="pages" id="LdFollowers" >

</div>




<div class="PostAnswer">
<h3>Add Your Response</h3>
<div id="myNicPanel" style="width: 600px;"></div>

<form method="post" action="#" id="PostResponse">
<p><textarea placeholder="<?php echo $fullname ; ?> Write your response here" id="response" maxlength="2000" style="" /></textarea></p><br>

<input type="hidden" id="pstat" value="<?php echo $pstat ; ?>" />
<input type="hidden" id="qid" value="<?php echo $q ; ?>" />
<p><input type="submit" value="Add Response"/></p>
</form>
</div>

</div>

</div>

<div class="relatedbar">

<?php
$qStat = get_q_stats($q);
$tags = "<a href='tags.php?tag=".$qStat['tags']."'>".$qStat['tags']."</a>" ;
$quesPoster = Get_q_data($q,'uid') ;
if(isset($_SESSION['uid'])){
$profileLink = "<a href='profile.php'>You asked this question</a>" ;

if($quesPoster == $_SESSION['uid']){
$profileLink = "<a href='profile.php'>You asked this question</a>" ;
}
else{
$profileLink = "<a href='viewprofile.php?user=$quesPoster'>".$qStat['user']."</a>" ;
echo "
<script>
$(function() {
	$('.userActions').remove();
});
</script>
" ;	
}
}
else{
$profileLink = "<a href='viewprofile.php?user=$quesPoster'>".$qStat['user']."</a>" ;
echo "
<script>
$(function() {
	$('.userActions').remove();
});
</script>
" ;		
}


?>

<div class="stats">
<h4>Question Stats</h4>
<table>
<tr><td class="title"></td><td><?php echo $profileLink ; ?></td></tr>
<tr><td class="title">Date</td><td><?php echo $qStat['date'] ; ?></td></tr>
<tr><td class="title">Views</td><td><?php echo $qStat['views'] ; ?></td></tr>
<tr><td class="title">Responses</td><td><?php echo $res_count ; ?> </td></tr>
<tr><td class="title">Tags</td><td class="tags"><?php echo $tags ; ?></td></tr>
<tr><td class="title">Votes</td><td><?php echo $qvotes ; ?> </td></tr>
<tr><td class="title">Status</td><td><?php echo $qStat['status'] ; ?></td></tr>
</table>
</div>

<!-- Long margin here for advert space -->
<?php
/// Ask user to follow question if
/*
:: At least an answer
:: is open not closed or discarded
*/
if($res_count > 0 && $qStat['status'] == 'OPEN'){
$getFollowers = "
<script>
$(function() {
	Get_Followers($q);
});
</script>
" ;
echo $getFollowers ;
}
?>

<?php
/// If questions is closed or discarded then no posting of answers
$hideResponse = "" ;
if($qStat['status'] !== 'OPEN'){
$hideResponse = "
<script>
$(function() {
	$('.PostAnswer').remove();
});
</script>
" ;
}
echo $hideResponse ;
?>

<div class="relatedquestions">
<h4>Related Questions</h4>
<?php
$getRelated = related_questions($q);
if($getRelated == ''){
echo "No related questions yet" ;
}
else{
echo $getRelated ;
}
?>
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