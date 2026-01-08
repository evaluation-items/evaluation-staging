
(function($){


    // $('#skip_main_content_start').click(function () {
    //     $('html, body').animate({
    //         scrollTop: $("#skip_main_content_end").offset().top
    //     }, 2000);
    // });
    
    $('.org-chart-img').on('click', function(){
        var src = $('.chart_img').attr('src');
        $('#modal_img').attr('src', src);
        $('#chart_modal').modal('show');
    });

    // $('.langsets').click(function (e) {
      
    //     var language=$(this).attr('langid');
  
    //     var ajaxUrl = '/site/changelanguage';
    //     jQuery.ajax({
    //         url: ajaxUrl,
    //         type: 'post',
    //         data: {request: language},
    //         success: function (data) {
    //             location.reload();
    //         }
    //     });
    // });
    $('.skip_main_content_start').click(function () {
        
        $('html, body').animate({
            scrollTop: $("#skip_main_content_end").offset().top
        }, 2000);
    });
    // $('.js-conveyor-example').jConveyorTicker({reverse_elm: true});

    $("#skip_to_top").click(function () {
        $("html, body").animate({scrollTop: 0}, "slow");
        return false;
    });
})(jQuery); 

var section;
var factor = 0.9;
var count = 0;
function getFontSize(el)
{
    var fs = $(el).css('font-size');
    if (!el.originalFontSize)
        el.originalFontSize = fs; //set dynamic property for later reset  
    return  parseFloat(fs);
}
function setFontSize(fact) {
    if (section == null)
        section = $('body').not('.font-size-change').find('*')
                .filter(
                        function () {
                            return  $(this).clone()
                                    .children()
                                    .remove()
                                    .end()
                                    .text().trim().length > 0;
                        }); //filter -> exclude all elements without text

    section.each(function () {
        var newsize = fact ? getFontSize(this) * fact : this.originalFontSize;
        if (newsize)
            $(this).css('font-size', newsize);
    });
}

function resetFont() {
    setFontSize();
    count = 0;
}

function increaseFont() {
    if (count < 1)
    {
        setFontSize(1 / factor);
        count++
    }
}

function decreaseFont() {
    if (count > -1)
    {
        setFontSize(factor);
        count--
    }
}

  function style_change(stylename) {
    
    var ajaxUrl = '/site/changestyle';

    $.ajax({
        url: ajaxUrl,
        type: 'post',
        data: {requestData: stylename},
        success: function (response) {

           //alert();
           location.reload(true);

        }
    });
}
var section;
var factor = 0.9;
var count = 0;
function getFontSize(el)
{
    var fs = $(el).css('font-size');
    if (!el.originalFontSize)
        el.originalFontSize = fs; //set dynamic property for later reset  
    return  parseFloat(fs);
}
function setFontSize(fact) {
    if (section == null)
        section = $('body').not('.font-size-change').find('*')
                .filter(
                        function () {
                            return  $(this).clone()
                                    .children()
                                    .remove()
                                    .end()
                                    .text().trim().length > 0;
                        }); //filter -> exclude all elements without text

    section.each(function () {
        var newsize = fact ? getFontSize(this) * fact : this.originalFontSize;
        if (newsize)
            $(this).css('font-size', newsize);
    });
}

function resetFont() {
    
    setFontSize();
    count = 0;
    $('.font-normal').addClass('active');
    $('.font-minus').removeClass('active');
    $('.font-plus').removeClass('active');
}

function increaseFont() {
    if (count < 1)
    {
        setFontSize(1 / factor);
        count++
    }
    $('.font-plus').addClass('active');
    $('.font-minus').removeClass('active');
    $('.font-normal').removeClass('active');
}

function decreaseFont() {

    if (count > -1)
    {
        setFontSize(factor);
        count--
    }
    $('.font-minus').addClass('active');
    $('.font-plus').removeClass('active');
    $('.font-normal').removeClass('active');
}

$(document).ready(function() {
    // $('#example').DataTable( {
        // "ordering": false,
    // });
    $('#demo').sidr();

    $('.multiple-items').slick({
        infinite: true,
        slidesToShow: 5,
        slidesToScroll: 5,
         responsive: [
          {
            breakpoint: 1024,
            settings: {
              slidesToShow: 3,
              slidesToScroll: 3,
              infinite: true,
              dots: false
            }
          },
          {
            breakpoint: 600,
            settings: {
              slidesToShow: 2,
              slidesToScroll: 2
            }
          },
          {
            breakpoint: 480,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1
            }
          }
         
        ]
      });
    $('.autoplay').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 3000,
        responsive: [
          {
            breakpoint: 1024,
            settings: {
              slidesToShow: 3,
              slidesToScroll: 3,
              infinite: true,
              dots: true
            }
          },
          {
            breakpoint: 770 ,
            settings: {
              slidesToShow: 2,
              slidesToScroll: 2
            }
          },
          {
            breakpoint: 481,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1
            }
          }
          
        ]
      });
    $('.autoplay-galley').slick({
        slidesToShow: 2,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 3000,
        infinite: false,
        responsive: [
          {
            breakpoint: 1024,
            settings: {
              slidesToShow: 3,
              slidesToScroll: 3,
              infinite: true,
              dots: true
            }
          },
          {
            breakpoint: 770 ,
            settings: {
              slidesToShow: 2,
              slidesToScroll: 2
            }
          },
          {
            breakpoint: 481,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1
            }
          }
          
        ]
      });

      $('.carousel .vertical .item').each(function(){
        var next = $(this).next();
        if (!next.length) {
            next = $(this).siblings(':first');
        }
        next.children(':first-child').clone().appendTo($(this));

        for (var i=1;i<1;i++) {
        next=next.next();
        if (!next.length) {
            next = $(this).siblings(':first');
        }
        
        next.children(':first-child').clone().appendTo($(this));
        }
    });

    $('#feedback-captcha-image').yiiCaptcha({"refreshUrl": "/pages/captcha?refresh=1", "hashKey": "yiiCaptcha\/default\/captcha"});

    $("#refresh_button").click(function () {
        $("#feedback-captcha-image").trigger("click");
    });

    $('#grievances-captcha-image').yiiCaptcha({"refreshUrl": "/pages/captcha?refresh=1", "hashKey": "yiiCaptcha\/default\/captcha"});

    $("#refresh_buttongriv").click(function () {
        $("#grievances-captcha-image").trigger("click");
    });

    var txt = "";
    var errr = 0;
    var fld = ['visitor_name_msg', 'feedback-email', 'visitor_desi_msg', 'feedback-mobile', 'feedback-captcha'];
    
    $('input').each(function () {
        //  alert('fs');
        var fldid = $(this).attr('id');
        $('#' + fldid).blur(function () {

            chkin(fldid);
        });
    });

    $('#send').on('click', function () {
        errr = 0;
        for (var a = 0; a < fld.length; a++)
        {
            chkin(fld[a]);
        }

        if (errr == 0)
        {
            $('#w0').submit();
        }
        return false;
    });

    $('#sendgriv').on('click', function () {
        errr = 0;
        for (var a = 0; a < fld.length; a++)
        {
            chkin(fld[a]);
        }

        if (errr == 0)
        {
            $('#w0').submit();
        }
        return false;
    });

    var txt = "";
    var errr = 0;

    var fld = ['visitor_name_msg', 'grievances-email', 'visitor_desi_msg', 'grievances-mobile', 'grievances-captcha'];
    
    $('input').each(function () {
        //  alert('fs');
        var fldid = $(this).attr('id');
        $('#' + fldid).blur(function () {

            chkin(fldid);
        });
    });

    $('.pg-msnr-container').magnificPopup({
        tLoading: 'Loading',
        tClose: 'Close',
        delegate: 'a.magnific',
        type: 'image',
        mainClass: 'mfp-img-mobile',
        gallery: {
          enabled: true,
          navigateByImgClick: true,
          tPrev: 'Previous',
          tNext: 'Next',
          tCounter: '%curr% of %total%'
        },
        image: {
          titleSrc: function(item) {
            return item.el.attr('title');
          },
          tError: 'Image not loaded'
        }
      });
      $('a.magnific2').magnificPopup({
        type: 'image',
        mainClass: 'mfp-img-mobile',
        image: {
          tError: 'Image not loaded'
        }
      });
      $('a.magnific3').magnificPopup({
        type: 'iframe',
        mainClass: 'mfp-img-mobile',
        preloader: false,
        fixedContentPos: false,
      });
      $('.paginate_button a').on('click',function()
      {
          $("html, body").animate({scrollTop: $("#myDivrti").offset().top}, "slow");
          return false;
      });
});
                
$(window).on('load', function() { 
    //$('#MyPopup3').modal('show');
    // $('#myModal').on('hidden.bs.modal', function () {
    //         $('#MyPopup3').modal('show');
    // });

    $('#MyPopup3').on('hidden.bs.modal', function () {
            $('#MyPopup4').modal('show');
    });
    
    // $('#myModal').modal('show');

    $('.flexslider').flexslider({
        animation: "fade",
        start: function(slider){
          $('body').removeClass('loading');
        }
      });

});
  
function blockSpecialChar(e) {
    var k;
    document.all ? k = e.keyCode : k = e.which;
    return ((k > 64 && k < 91) || (k > 96 && k < 123) || k == 8 || k == 0 || k == 32 || (k >= 48 && k <= 57));
}

function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

    function validateEmail($email) {
       
        var emailReg = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
        return emailReg.test($email);
        return false;
    }

    function chkin(field)
    {
        var errr = 0;
        var fldval = $('#' + field).val();
        var fldval = $.trim($('#' + field).val());

        if (!fldval)
        {
            var lbl = $('#' + field).parent().children('.control-label').html();
            //var labl = $.trim(lbl);
            var labl=$.trim(lbl.replace('<span class="red">*</span>',''));
            add_se(field, labl + " Should not Blank", 'error');
            errr++;
        } else
        {
            if (field == "feedback-email")
            {

                var filter = new RegExp(/^(\w+\.)*(\w+)@(\w+\.)+([a-zA-Z]{2,3})+$/);
                var error = 1;

                if (!filter.test(fldval))
                {
                    add_se(field, "Enter Valid Email Address", 'error');
                    errr++;
                } else
                {
                    //alert(fldval);

                    var arr = fldval.split('.');
                    //alert(a[0]);

                    var occurrences = {};
                    for (var i = 0, j = arr.length; i < j; i++) {
                        occurrences[arr[i]] = (occurrences[arr[i]] || 0) + 1;
                        if (occurrences[arr[i]] > 1) {
                            error++;
                        } else {
                            error = 0;
                        }
                    }

                    if (error >= 1) {
                        add_se(field, "Enter Valid Email Address", 'error');
                        errr++;
                    } else {
                        error = 0;
                        add_se(field, "", 'success');
                    }


                }

            } else if (field == "feedback-address") {
                var feed = $('#feedback-address').val();

                if (feed.length > 50)
                {

                    add_se(field, "Address must be less than 50 characters.", 'error');
                    errr++;
                } else
                {

                    add_se(field, "", 'success');
                }

            } else if (field == "visitor_name_msg")
            {
                var nameReg = new RegExp(/^[A-Z a-z]+$/);

                if (!nameReg.test(fldval))
                {

                    add_se(field, "Name must be characters.", 'error');
                    errr++;
                } else
                {

                    add_se(field, "", 'success');
                }
            } else if (field == "feedback-city")
            {
                var nameReg = new RegExp(/^[A-Z a-z]+$/);

                if (!nameReg.test(fldval))
                {

                    add_se(field, "City must be characters.", 'error');
                    errr++;
                } else
                {

                    add_se(field, "", 'success');
                }
            } else if (field == "feedback-state")
            {
                var nameReg = new RegExp(/^[A-Z a-z]+$/);

                if (!nameReg.test(fldval))
                {

                    add_se(field, "State must be characters.", 'error');
                    errr++;
                } else
                {

                    add_se(field, "", 'success');
                }
            } else if (field == "feedback-country")
            {
                var nameReg = new RegExp(/^[A-Z a-z]+$/);

                if (!nameReg.test(fldval))
                {

                    add_se(field, "Country must be characters.", 'error');
                    errr++;
                } else
                {

                    add_se(field, "", 'success');
                }
            } else if (field == "feedback-pincode")
            {
                var pinReg = new RegExp(/^[-+]?[0-9]+$/);

                if (!pinReg.test(fldval))
                {

                    add_se(field, "Pincode must be numbers.", 'error');
                    errr++;
                } else
                {

                    add_se(field, "", 'success');
                }
            } else if (field == "feedback-mobile")
            {
                var pinReg = new RegExp(/^[-+]?[0-9]+$/);

                if (!pinReg.test(fldval) || fldval.length != 10)
                {

                    add_se(field, "Mobile Number must be numbers and exactly equal to 10 digit.", 'error');
                    errr++;
                } else
                {

                    add_se(field, "", 'success');
                }
            } else
            {
                add_se(field, "", 'success');
            }
        }
    }

    function add_se(feld, message = "", type)
    {

        if (type == "error")
        {
            $('#' + feld).parent().removeClass('has-success');
            $('#' + feld).parent().addClass('has-error');
            $('#' + feld).parent().children('.help-block').html(message);
        } else
        {
            $('#' + feld).parent().removeClass('has-error');
            $('#' + feld).parent().addClass('has-success');
            $('#' + feld).parent().children('.help-block').html('');
        }
    }

    function validateEmail($email) {
        //var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        //var emailReg = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        var emailReg = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
        return emailReg.test($email);
        return false;
    }

    function chkin(field)
    {
        var errr = 0;
        var fldval = $('#' + field).val();
        var fldval = $.trim($('#' + field).val());

        if (!fldval)
        {
            var lbl = $('#' + field).parent().children('.control-label').html();
            var labl = $.trim(lbl);

            add_se(field, labl + " Should not Blank", 'error');
            errr++;
        } else
        {
            if (field == "grievances-email")
            {
                var filter = new RegExp(/^(\w+\.)*(\w+)@(\w+\.)+([a-zA-Z]{2,3})+$/);
                var error = 1;

                if (!filter.test(fldval))
                {
                    add_se(field, "Enter Valid Email Address", 'error');
                    errr++;
                } else
                {
                    //alert(fldval);

                    var arr = fldval.split('.');
                    //alert(a[0]);

                    var occurrences = {};
                    for (var i = 0, j = arr.length; i < j; i++) {
                        occurrences[arr[i]] = (occurrences[arr[i]] || 0) + 1;
                        if (occurrences[arr[i]] > 1) {
                            error++;
                        } else {
                            error = 0;
                        }
                    }

                    if (error >= 1) {
                        add_se(field, "Enter Valid Email Address", 'error');
                        errr++;
                    } else {
                        error = 0;
                        add_se(field, "", 'success');
                    }


                }

            } else if (field == "visitor_name_msg")
            {
                var nameReg = new RegExp(/^[A-Z a-z]+$/);

                if (!nameReg.test(fldval))
                {

                    add_se(field, "Name must be characters.", 'error');
                    errr++;
                } else
                {

                    add_se(field, "", 'success');
                }
            }else if (field == "grievances-mobile")
            {
                var pinReg = new RegExp(/^[-+]?[0-9]+$/);

                if (!pinReg.test(fldval) || fldval.length != 10)
                {

                    add_se(field, "Mobile Number must be numbers and exactly equal to 10 digit.", 'error');
                    errr++;
                } else
                {

                    add_se(field, "", 'success');
                }
            } else
            {
                add_se(field, "", 'success');
            }
        }
    }

    function add_se(feld, message = "", type)
    {

        if (type == "error")
        {
            $('#' + feld).parent().removeClass('has-success');
            $('#' + feld).parent().addClass('has-error');
            $('#' + feld).parent().children('.help-block').html(message);
        } else
        {
            $('#' + feld).parent().removeClass('has-error');
            $('#' + feld).parent().addClass('has-success');
            $('#' + feld).parent().children('.help-block').html('');
        }
    }
                                         