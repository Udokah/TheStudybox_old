

$(function() {	
$.ajaxSetup({
type: 'POST',
timeout: 1000000,
cache: false,
});

//// show Nag Notification
$('.nag').fadeIn(2000);
$('.nag').find('.close').click(function(e){
	e.preventDefault();
	$('.nag').fadeOut('fast');
	});

// remove all original title and replace with data-title attr
$('body').each(function(){
	
	var attr = $(this).attr('title');
	
	if (typeof attr !== 'undefined' && attr !== false) {
		$(this).removeAttr('title');
		$(this).attr('data-title',attr) ;
   }
	
});
  
  });
 

//// NAVIGATIONS SHOW AND HIDE ////
$(document).ready(function(){

var navbar = ".usertools li" ;
$(navbar).on('click', function(){
$(this).find('.subnav').fadeToggle('fast');
});

$('.subnav').on('mouseleave', function(e){
$(this).slideUp('fast');
});

});

//// FUNCTION FOR SIGN-UP HOVER SPAN ////
$(document).ready(function(){

var myElement = '.signup .req' ;

$(myElement).on('focus', function(){
$(this).closest('td').next('td').find('span').fadeIn(500).css('display','inline-block') ;
});

$(myElement).on('blur', function(){
$(this).closest('td').next('td').find('span').fadeOut(500) ;
});

// Restore default content to password help span
$('#password').on('blur', function(){
var defaultcontent = "Enter a secure password; should be from 6 - 10 characters." ;
$('.passcounter').hide().css('background','#FFAB0F').html(defaultcontent);
});

});

//// FUNCTION FOR CALCULATING PASSWORD STRENTH
$(document).ready(function(){

var myElement = '.signup table tr #password' ;

$(myElement).on('keyup', function(){

var passLen = $(this).val().length; // Get the length of input

if(passLen > 0 && passLen < 7){
var strength = "Weak"
var color = "#910000" ;
}
else if(passLen > 5 && passLen < 10){
var strength = "Moderate"
var color = "#005F9D" ;
}
else if(passLen > 9 && passLen < 16){
var strength = "Strong"
var color = "#006200" ;
}
else if(passLen == 0){
var strength = "Weak"
var color = "#910000" ;
}


$('.passcounter').css('background',color).html('Password Strength: ' + strength).fadeIn(500) ;
});

});


// On Form submit
$(document).ready(function(){
$('#loginForm').on('submit',function(e){
e.preventDefault();

var err = '#loginForm #error';
$(err).removeAttr('class');

$('#loginForm .req').each(function() {
	
	var content = $.trim($(this).val());
	var msg = $(this).attr('data-alert');
	
	if(content == ''){
		$(err).addClass('error').show('slide').css('display','inline-block').html(msg).delay(1000).fadeOut(1000);
		$(this).focus();
		exit();
	}
});

var email = $('#email').val();
var password = $('#password').val();
var dataString = 'type=login' + '&email=' + email + '&password=' + password ;

// Ajax Process ///
$.ajax({
url: 'config/ajax/Home_login.php',
data: 'action=LoginUser&' + dataString,
success: function(returnedData){
eval(returnedData);
},
error: function () {
var err = '#regForm #error' ;
$(err).removeAttr('class');
$(err).addClass('error').show().css('display','inline-block').html('677: Error Occured while. Retry');
    }
});
//// End of Ajax Process

});
});

function LOAD_NOTIFICATIONS(uid){
$(document).ready(function() {
    var sendData = { action : 'LoadUserNotifications' , uid : uid } ;
$.ajax({
url: 'config/ajax/loader.php',
cache: false,
data: sendData,
success: function(retData){
eval(retData);
}
});
});
setTimeout('LOAD_NOTIFICATIONS(' + uid + ')', 10000 );
//Recheck every 10 secs
}

//// When a notification is clicked // first mark as viewed in DB
$(document).ready(function(){
	$('body').on('click', '#notifynav li a' , function(e){
    e.preventDefault();
	
	if(!$(this).hasClass('false')){
	var href = $(this).attr('data-href');
	var nid = $(this).attr('data-nid');
	var dataSend = { action : 'MarkViewedNotification' , nid : nid } ;
	$.ajax({
    url: 'config/ajax/loader.php',
	cache: false,
    data: dataSend,
    success: function(){
	window.location = href ; /// redirect users
     }
    });
	}
	});


// Animate Search box
$('.rightbar .search input[type=text]').on('focus', function(){
$(this).animate({ width: '230px' } , '50');
})

$('.rightbar .search input[type=text]').on('blur', function(){
if($(this).val() == '' || $(this).val() == 'Search' ){
$(this).animate({ width: '100px' } , '50');
}
})


// Bind function to close modal box
$('body').on('click', '.modal a.close' , function(e){
e.preventDefault();
$('.modal').fadeOut('fast', function(){
$(this).remove();
});
});

});

function startLoader(){
	$('body').prepend('<img src="img/load.gif" alt="Loading" class="ajaxload" style="color:#f00; position:fixed; top:45%; left:50%; z-index:999;">'); 
}

function endLoader(){
	$('.ajaxload').remove();
}


// Call Modal and add text
function Modal(msg){
var html = "<div class='modal'><a href='#' class='close' title='close'>x</a><span></span></div>" ;
$('body').append(html);
$('.modal').find('span').html(msg);
$('.modal').fadeIn('fast');
}