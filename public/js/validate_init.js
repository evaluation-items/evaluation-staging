$(function(){
    jQuery.validator.setDefaults({
        highlight: function(element) {
            if($(element).closest('.input-group').length) {
				$(element).closest('.input-group').addClass('has-error');
            }
            else {
                $(element).parent().addClass('has-error');
            }
        },
        unhighlight: function(element) {
            if($(element).closest('.input-group').length) {
				$(element).closest('.input-group').removeClass('has-error');
            }
            else {
                $(element).parent().removeClass('has-error');
            }
        },
        errorElement: 'span',
        errorClass: 'help-block',
        errorPlacement: function(error, element) {
            if (element.hasClass('select2-multiple')){
                error.insertAfter(element.next('.select2-container'));
            }
            else if(element.hasClass('select2') ) {
                error.insertAfter(element.next('.select2-container'));
            }
            else if (element.attr("type") == "checkbox") {
                error.insertAfter( element.parent() )
            }
            else if (element.attr("type") == "radio") {
                error.insertAfter( element.parent() )
            }
            else if(element.closest('.input-group').length) {
				if(element.closest('.input-group').parent().find('.help-block').length)
				{
					element.closest('.input-group').parent().find('.help-block').remove();
				}
                error.insertAfter(element.closest('.input-group'));
            }
            else if (element.attr("name") == "amount_field" ) {
                $("#amount_error").html(`<div class="help-block">${error[0].innerHTML}</div>`);
            }
            else {
				if(element.parent('.has-error').find('.help-block').length)
				{
					element.parent('.has-error').find('.help-block').remove();
				}
				error.insertAfter(element);
            }
        },
        onfocusout: false
    });

    $.validator.addMethod('filesize', function(value, element, param) {
        return this.optional(element) || (element.files[0].size <= param)
    });

    $("#forwardScheme").validate({
        // Define validation rules
        rules: {
                 remarks: { required: true },
        },
        messages: {
            remarks: {  required: "Please enter remarks"},
        },
        submitHandler: function (form) {
            form.submit();
        }
    });
    $("#returnScheme").validate({
        // Define validation rules
        rules: {
                 remarks: { required: true },
        },
        messages: {
            remarks: {  required: "Please enter remarks"},
        },
        submitHandler: function (form) {
            form.submit();
        }
    });
    $("#forwardSchemeEvl").validate({
        // Define validation rules
        rules: {
                 remarks: { required: true },
        },
        messages: {
            remarks: {  required: "Please enter remarks"},
        },
        submitHandler: function (form) {
            form.submit();
        }
    });
    $("#assignDDFrm").validate({
        // Define validation rules
        rules: {
            remarks: {
                required: true
            },
            "team_member_dd[]": {
                required: true
            },
            branch:{
                required: true
            }
        },
        messages: {
            remarks: {
                required: "Please enter remarks"
            },
            "team_member_dd[]": {
                required: "Please select a team member"
            },
            branch:{
                   required: "Please select branch"
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });
    $("#frmDept").validate({
        // Define validation rules
        rules: {
            dept_name: {
                required: true
            }
        },
        messages: {
            dept_name: {
                required: "Please enter department name"
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    $("#frmSubdept").validate({
        // Define validation rules
        rules: {
            dept_id: {
                required: true
            },
            name: {
                required: true
            }
        },
        messages: {
            dept_id: { required: "Please select department "},
            name: { required: "Please enter department name" }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });
    $("#frmDepthod").validate({
        // Define validation rules
        rules: {
            dept_id: {
                required: true
            },
            name: {
                required: true
            }
        },
        messages: {
            dept_id: { required: "Please select department "},
            name: { required: "Please enter hod name" }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });
    
    $("#Odkusers").validate({
        // Define validation rules
        rules: {
            email: {
                required: true
            },
            password: { 
                required: true,
                minlength: 20

            } , 
            confirm_password: { 
                equalTo: "#password"
            }

        },
        messages: {
            email:{required:"Enter the email address"},
            password: {
                required: "Enter the password",
                minlength: "The password must contain at least 20 characters"
            },
            confirmpassword: {
                equalTo: "The two passwords must match"
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });
    $("#userRole").validate({
        // Define validation rules
        rules: {
            rolename: {
                required: true
            }

        },
        messages: {
            rolename:{required:"Enter Role name"},
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    $(document).ready(function(){
        setTimeout(function(){
            $('.alert-success').fadeOut('slow');
        }, 4000);
    });

    // Automatically hide error message after 5 seconds
    $(document).ready(function(){
        setTimeout(function(){
            $('.alert-danger').fadeOut('slow');
        }, 4000);
    });

    $("#frmUser").validate({
        // Define validation rules
        rules: {
            dept_id: {
                required: true
            },
            email: {
                required: true
            },
            role: {
                required: true
            }
        },
        messages: {
            dept_id: { required: "Please select department "},
            role: { required: "Please select role "},
            email: { required: "Please enter email" }
        },
        submitHandler: function (form) {
            form.submit();
        }
        
    });
    $("#frmaddUser").validate({
        rules: {
            dept_id: {
                required: true
            },
            email: {
                required: true
            },
            role: {
                required: true
            },            
            password: { 
                required: true,
                maxlength: 20

            } , 
            confirm_password: { 
                equalTo: "#password"
            }
        },
        messages: {
            dept_id: { required: "Please select department "},
            role: { required: "Please select role "},
            email: { required: "Please enter email" },
            password: {
                required: "Enter the password",
                maxlength: "The password must contain at least 20 characters"
            },
            confirm_password: {
                equalTo: "The two passwords must match"
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
        
    });
     $("#Statefrm").validate({
        rules: {
            name: {
                required: true
            }
        },
        messages: {
            name: { required: "Please Enter State Name "},
            
        },
        submitHandler: function (form) {
            form.submit();
        }
        
    });
    $("#Districtfrm").validate({
        rules: {
            state_id:{
                required:true
            },
            dcode:{
                required:true
            },
            name_e: {
                required: true
            }
        },
        messages: {
            state_id: {required: "Please select state name"},
            dcode: {required: "Please Enter District code"},
            name_e: { required: "Please Enter District Name "},
            
        },
        submitHandler: function (form) {
            form.submit();
        }
        
    });
    $("#talukaFrm").validate({
        rules: {
            state_id:{
                required:true
            },
            dcode: {
                required: true
            },
            tname_e:{
                required:true
            }
        },
        messages: {
            state_id: {required: "Please select state name"},
            dcode: { required: "Please select District Name "},
            tname_e: { required: "Please enter Taluka name"}
            
        },
        submitHandler: function (form) {
            form.submit();
        }
        
    });
    $("#villageFrm").validate({
        rules: {
            dcode:{
                required:true
            },
            tcode: {
                required: true
            },
            vname_e:{
                required:true
            }
        },
        messages: {
            dcode: {required: "Please select District name"},
            tcode: { required: "Please select Taluka Name "},
            tname_e: { required: "Please enter Village name"}
            
        },
        submitHandler: function (form) {
            form.submit();
        }
        
    });
    
    $("#unitFrm").validate({
        rules: {
            name:{
                required:true
            }
        },
        messages: {
            name: { required: "Please enter unit name"}
            
        },
        submitHandler: function (form) {
            form.submit();
        }
        
    });
    $("#beneficiariesFrm").validate({
        rules: {
            name:{
                required:true
            }
        },
        messages: {
            name: { required: "Please enter beneficiaries name"}
            
        },
        submitHandler: function (form) {
            form.submit();
        }
        
    });
    $("#frmAdvertiesment").validate({
        rules: {
            name:{
                required:true
            }
        },
        messages: {
            name: { required: "Please enter Text"}
            
        },
        submitHandler: function (form) {
            form.submit();
        }
    });
    $("#frmPublication").validate({
        rules: {
            dept_id:{
                required:true
            },
            // branch_name:{
            //     required:true
            // },
            year:{
                required:true
            },
            pdf_document:{
                required: function () {
                    return $("#existing_file").val() === ""; // Only required if no existing file
                }
    
            }
        },
        messages: {
            // branch_name: { required: "Please Enter Branch Name"},
            dept_id: {required: "Please select department"},
            year: { required: "Please Enter Year"},
            pdf_document : { required: "Please Upload Document"}
            
        },
        submitHandler: function (form) {
            form.submit();
        }
    });
    
    $("#frmProject").validate({
        rules: {
            study_name:{
                required:true
            },
            dept_id:{
                required:true
            },
            year:{
                required:true
            },
            org_name:{
                required:true
            },
            "upload_file[]":{
                required: function () {
                    return $("#existing_file").val() === ""; // Only required if no existing file
                },
                extension: "pdf",
            },
        },
        messages: {
            study_name: { required: "Please enter Study Name"},
            dept_id: {required:'Please select department'},
            year:{required: "Please select Year"},
            org_name:{ required: "Please enter Organization Name"},
            "upload_file[]":{required: "please select any PDF file."}
            
        },
        submitHandler: function (form) {
            form.submit();
        }
    });
    
    
    
    
});
$(document).ready(function () {
    // COncern Department Completed Proposal DataTable
var con_datatable =  $('.custom_concern_dept_complete_table').DataTable({
      columns: [
        { data: 'DT_RowIndex' },
        { data: 'final_report' },
        { data: 'scheme_name' },
        { data: 'hod_name' },
        { data: 'report_published_date' },
        { data: 'actions' }
        ],
        columnDefs: [
            { type: 'date', targets: 4 } 
        ]
    });
    $('#year_select, #department_select').change(function() {
      concerndeptUpdateTable();
   });

    var datatable =  $('.custom_complete_table').DataTable({
      columns: [
        { data: 'DT_RowIndex' },
        { data: 'scheme_name' },
        { data: 'department_name' },
        { data: 'published_date' },
        { data: 'actions' }
        ],
        columnDefs: [
            { type: 'date', targets: 3 } 
        ]
    });
    $('#year_select, #department_select, #deputyDirector_select').change(function() {
      updateTable();
   });

  
function updateTable() {
  // Get selected values
var selectedYear = $('.year_items select').val();
var selectedDepartment = $('.department_items select').val();
var selectedDeputyDirector = $('.dd_user_items select').val();

    // Make an AJAX request to fetch updated data
        if (typeof config !== 'undefined' && config.routes.zone) {

                    $.ajax({
                        url: config.routes.zone,
                        method: 'POST',
                        headers: {
                            'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                        },
                        data: {
                            year: selectedYear,
                            department: selectedDepartment,
                            deputyDirector: selectedDeputyDirector,
                        },
                        success: function(response) {
                            // Clear existing rows in the table
                            datatable.clear();

                            // Add new rows to the table
                            datatable.rows.add(response.data);

                            // Redraw the table
                            datatable.draw();
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });

        } else {
            console.log("Config is not defined or config.routes.zone is not set");
        }
}

// Initial table update
updateTable();
concerndeptUpdateTable();

$(document).on('click', '.report_data', function() {
    $('#open_modal').modal('show');
    var pdf = $(this).attr('data-url-pdf');
    var excel = $(this).attr('data-url-excel');
  
    $('#pdfDownload').data('url', pdf);
    $('#excelDownload').data('url', excel);
});

$('input[name="report_item"]').on('click', function() {
    if ($(this).is(':checked')) {
        if ($(this).val() == 0) {
            var pdfUrl = $('#pdfDownload').data('url');
            $('#pdfDownload').attr('href', pdfUrl).show();
            $('#excelDownload').hide();
        } else {
            var excelUrl = $('#excelDownload').data('url'); // Ensure you have set the data-url for excelDownload
            $('#excelDownload').attr('href', excelUrl).show();
            $('#pdfDownload').hide();
        }
    }
});
$('#pdfDownload, #excelDownload').on('click', function() {
    $('#open_modal').modal('hide');
    setTimeout(function(){
        location.reload();
    }, 4000);
});
function concerndeptUpdateTable() {
  // Get selected values
var selectedYear = $('.year_items select').val();
var selectedDepartment = $('.department_select select').val();

    // Make an AJAX request to fetch updated data
        if (typeof config !== 'undefined' && config.routes.con_dept_zone) {

            $.ajax({
                url: config.routes.con_dept_zone,
                method: 'POST',
                headers: {
                    'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                },
                data: {
                    year: selectedYear,
                    department_hod_name: selectedDepartment,
                    // deputyDirector: selectedDeputyDirector,
                },
                success: function(response) {
                    // Clear existing rows in the table
                    con_datatable.clear();

                    // Add new rows to the table
                    con_datatable.rows.add(response.data);

                    // Redraw the table
                    con_datatable.draw();
                },
                error: function(error) {
                    console.log(error);
                }
            });

} else {
    console.log("Config is not defined or config.routes.zone is not set");
}
}
});
