	
	// All effect should happen after all Ajax element has fully Loaded
	$(document).ajaxStop(function() {
// Custom Tool Tip   creation
// remove all original title and replace with data-title attr
$('body *').each(function(){
	var attr = $(this).attr('title');
	if (typeof attr !== 'undefined' && attr !== false) {
		$(this).removeAttr('title');
		$(this).attr('data-title',attr) ;
   }
});

// Custome Checkbox Creation
// remove all check box and replace with additional style
$('body *').each(function(){
	
	
});
  });