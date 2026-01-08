$(function() {
    
    $(document).on('keypress', '.pattern', function(e) {
        const invalidChars = ['<', '>', '/'];
        if (invalidChars.includes(String.fromCharCode(e.which))) {
            e.preventDefault();
        }
    });
	
	
  window.addEventListener('load', function() {
    // jQuery
	  if (window.jQuery) jQuery.fn.jquery = '';

	  // jQuery UI
	  if (window.jQuery && jQuery.ui) jQuery.ui.version = '';

	  // DataTables
	  if (window.jQuery && jQuery.fn.dataTable) jQuery.fn.dataTable.version = '';

	  // FancyBox
	  if (window.jQuery && jQuery.fancybox) jQuery.fancybox.version = '';

	  // Bootstrap
	  if (window.bootstrap && bootstrap.Tooltip) {
		// Bootstrap 5 stores version in bootstrap.Tooltip.VERSION
		bootstrap.Tooltip.VERSION = '';
	  }

     if (localStorage.getItem('wcag-contrast') === 'enabled') {
        applyWCAGContrastTheme();
     }

});
    // $('body').bind('copy paste',function(e) {
        // e.preventDefault();
        // alert('Not allowed');
        // return false; 
    // });

    // $(document).bind("contextmenu",function(e){
		// return false;
    // });

    // document.onkeypress = function (event) {  
        // event = (event || window.event);  
        // if (event.keyCode == 123) {
            // return false;  
        // }  
    // }  
    // document.onmousedown = function (event) {  
        // event = (event || window.event);  
        // if (event.keyCode == 123) {
            // return false;  
        // }  
    // }  
    // document.onkeydown = function (event) {  
        // event = (event || window.event);  
        // if (event.keyCode == 123) {
            // return false;  
        // }  
    // }  

    // window.oncontextmenu = function () {
        // return false;
    // }
    // $(document).keydown(function (event) {
        // if (event.keyCode == 123) {
            // return false;
        // }
        // else if ((event.ctrlKey && event.shiftKey && event.keyCode == 73) || (event.ctrlKey && event.shiftKey && event.keyCode == 74)) {
            // return false;
        // }
    // });
});



