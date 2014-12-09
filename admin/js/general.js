
$(function() {	
$.ajaxSetup({
type: 'POST',
url: 'config/ajax/server.php',
timeout: 5000,
cache: false,
});
});

// Call Modal 
function Modal(msg){
var html = "<div class='modal'><a href='#' class='close' title='close'>x</a><span></span></div>" ;
$('body').append(html);
$('.modal').find('span').html(msg);
$('.modal').fadeIn('fast');
}

// Bind function to close modal box
$(document).ready(function(){
$('body').on('click', '.modal .close' , function(e){
e.preventDefault();
$('.modal').fadeOut('fast', function(){
$(this).remove();
});
});
});