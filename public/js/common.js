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

    $(document).on('input', '.mobile_number', function () {

    let value = this.value;

    // Convert Gujarati digits to English
    value = value.replace(/[૦-૯]/g, function(d) {
        return '૦૧૨૩૪૫૬૭૮૯'.indexOf(d);
    });

    // Remove non-numeric
    value = value.replace(/[^0-9]/g, '');

    this.value = value.slice(0, 10);

    let $this = $(this);
    let $group = $this.closest('.form-group');
    $group.find('.mobile-error').remove();

    if (value.length > 0) {

        if (!/^[6-9]/.test(value)) {
            $this.addClass('is-invalid');
            $this.after('<div class="text-danger mobile-error">Mobile number must start with 6, 7, 8, or 9</div>');
        }
        else if (value.length < 10) {
            $this.addClass('is-invalid');
            $this.after('<div class="text-danger mobile-error">Please enter 10 digit mobile number</div>');
        }
        else {
            $this.removeClass('is-invalid');
        }
    } else {
        $this.removeClass('is-invalid');
    }

    toggleNextButton();
});
function toggleNextButton() {
    if ($('.is-invalid').length > 0) {
        $('.btn-success').prop('disabled', true);
    } else {
        $('.btn-success').prop('disabled', false);
    }
}
const emailInput = document.getElementById('inputEmail');

    // Only run if the element actually exists on the current page
    if (emailInput) {
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                // If someone removes 'readonly', put it back immediately
                if (mutation.type === "attributes" && !emailInput.hasAttribute('readonly')) {
                    emailInput.setAttribute('readonly', 'readonly');
                }
            });
        });

        observer.observe(emailInput, { attributes: true });
    }
});



