
{{-- @extends(Auth::user()->role_manage == 2 ? 'dashboards.eva-dir.layouts.evaldir-dash-layout' : 'dashboards.eva-dd.layouts.evaldd-dash-layout') --}}
@extends('dashboards.eva-dir.layouts.evaldir-dash-layout' )
@section('title','Detail Report')
@section('content')

<link href="{{asset('plugins/select2/css/select2.min.css')}}" rel="stylesheet" />
  

<style>
  .form-multi-select + .select2-container--default .select2-selection--multiple .select2-selection__choice{
    background-color: #007bff !important; 
    border: 1px solid #fff !important;
    border-right: 1px solid #fff !important;
    color: #fff !important;
  }
  .form-multi-select + .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    color: #fff !important;
  }
  .cspro-items {
    border: 1px solid #ccc;
    background-color: #fff;
    width: 100%;
    padding: 20px;         /* space inside the box */
    margin: 20px 0;        /* vertical space outside the box */
    border-radius: 8px;    /* rounded corners */
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);  /* light shadow for depth */
}
#example1 th {
    white-space: nowrap;
   /* padding: 8px 10px; */
    text-align: center;
    vertical-align: middle;
    line-height: 1.2;
}
#example1 td {
   /* padding: 8px 10px; */
    text-align: center;
    vertical-align: middle;
    line-height: 1.2;
}
  /* Keep the width manageable */
</style>

<div class="content  d-flex flex-column flex-column-fluid" id="kt_content">
  <!--begin::Subheader-->
  <div class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
    <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
      <!--begin::Info-->
      <div class="d-flex align-items-center flex-wrap mr-1">
        <!--begin::Page Heading-->
        <div class="d-flex align-items-baseline flex-wrap mr-5">
          <!--begin::Page Title-->
          <h5 class="text-dark font-weight-bold my-1 mr-5 text-center">{{ __('message.detail_reports') }}</h5>
          <!--end::Page Title-->                  
        </div>
        <!--end::Page Heading-->
      </div>
      <!--end::Info-->
    </div>
  </div> 
  <!--end::Subheader-->
  <!--begin::Entry-->
  <div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container">
      <!--begin::Card-->
      <div class="cspro-items">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="exampleSelect2">{{ __('message.select_case')}}:</label>
              <select class="select-case form-control custom-form-control" id="select-case">
                <option value="">{{ __('message.select_case')}}</option>
                @foreach($case_title as $case_item)
                <option value="{{ $case_item->t_id }}">{{ $case_item->title }}</option>
              @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="exampleSelect1">{{ __('message.select_questions_qptions')}}:</label>
              <select class="form-multi-select form-multi-select1 select-information select2" multiple style="width: 100%;">
                {{-- @foreach($itemLabels as $itemLabel)
                  <option value="{{ $itemLabel->id }}">{{ $itemLabel->item_label }}</option>
                @endforeach --}}
              </select>
            </div>
          </div>
        </div>
      
        <div class="text-center mt-3">
          <button class="btn btn-primary btn-submit" id="btn-submit">{{ __('message.submit') }}</button>
        </div>
      </div>
      <div class="card card-custom gutter-b detail-report" style="display:none;">
          <div class="card-header flex-wrap py-3">
            <div class="card-toolbar">
              <h5>{{ __('message.list_of_detail_report')}}</h5>
            </div>
            <div class="row">
                <div class="col-md-3">
                  <button class="btn btn-xs btn-primary" style="margin-top: 10px;" id="downloadExcel">{{ __('message.download_excel')}}</button>
                </div>
            </div>
          </div>
          <div class="card-body">
              <table id="example1" class="table table-responsive table-bordered table-striped dataTable dtr-inline">
                <thead>
                </thead>
                <tbody>
                </tbody>
            </table>
          </div>
      </div>
      <!--end::Card-->
    </div>
    <!--end::Container-->
  </div>
  <!--end::Entry-->
</div>
<!--end::Content-->
@endsection
<!-- jQuery -->
<script src="{{asset('js/jquery-laest-version.min.js')}}"></script>

<script>
  function formatCheckbox(option) {
    if (!option.id) return option.text;
    const isSelected = $(option.element).prop('selected');
    const checkbox = `<input type="checkbox" ${isSelected ? 'checked' : ''} style="margin-right: 8px; transform: scale(1.3);" />`;
    return $(` <span>${checkbox}${option.text}</span>`);
  }

  $(document).ready(function () {
    
    $('.form-multi-select1').select2({
      closeOnSelect: false,
      placeholder: "Select option(s)",
      allowClear: true,
      templateResult: formatCheckbox,
      templateSelection: function (data) {
        return data.text;
      }
    }).on("select2:select", function (e) {
      // Trigger redraw to update checkbox state
      $(this).trigger("change.select2");
    }).on("select2:unselect", function (e) {
      // Trigger redraw to update checkbox state
      $(this).trigger("change.select2");
    });



    $('#select-case').on('change',function(){
      var title_id = $(this).val();
      caseOnchange(title_id);
    });

   function caseOnchange(title_id){
      
      $.ajax({
        type: "POST",
        url: "{{ route('evaldir.case-filtter') }}",
        dataType: "json", // was "html" - change this
        data: {
          id: title_id,
          _token: '{{ csrf_token() }}'
        },
        success: function(response){
          if (response !== "") {
            $('.select-information').empty();
            let groupedOptions = {};

            response.question_option.forEach(function(item) {
            if (!groupedOptions[item.question_id]) {
                groupedOptions[item.question_id] = {
                label: item.question,
                questionId: item.question_id,
                options: []
                };
            }
            groupedOptions[item.question_id].options.push({
                values: item.values,
                options: item.options
            });
            });

            Object.values(groupedOptions).forEach(function(group) {
            const optgroup = $(`<optgroup label="${group.label}" data-question_id="${group.questionId}"></optgroup>`);

            group.options.forEach(function(opt) {
                optgroup.append(`<option value="${opt.values}">${opt.options}</option>`);
            });

            $('.select-information').append(optgroup);
            });
          }
        }
      });
	  }

    // Handle Submit button click
    $('#btn-submit').on('click', function () {
  
        var titleId = $('#select-case').val();

        var questionIds = [];
        var selectedItems = {};

        // Loop through each optgroup
        $('.select-information optgroup').each(function() {
            const questionId = $(this).data('question_id');
            const selectedOptions = $(this).find('option:selected');

            if (selectedOptions.length > 0) {
                questionIds.push(questionId); // Collect all question IDs
                
                selectedItems[questionId] = []; // Initialize array for each question_id

                selectedOptions.each(function() {
                    selectedItems[questionId].push($(this).val());
                });
            }
        });

      // Now check after populating
      if (!selectedItems || selectedItems.length === 0) {
        alert('Please select at least one field.');
        return;
      }
        // Make AJAX request to fetch data based on selected items
        $.ajax({
            type: 'POST',
            url: "{{ route('evaldir.case-field-filtter') }}",  // Your route here
            data: {
                _token: '{{ csrf_token() }}',  // CSRF token
                selected_items: selectedItems,
                t_id:titleId,
                question_ids:questionIds
            },
           
            success: function(response) {
                  // Show card
              $('.detail-report').css('display', 'block');
            
              $('.card-body').html(response.html);
          
              // Initialize again
              $('#example1').DataTable({
                  responsive: true,
                  autoWidth: true,
                  pageLength: 10,
                  scrollY: '400px',
                  scrollCollapse: true,
                  scroller: true,
                  scrollX: true,
                  stateSave: false,
              });

            }
        });
    });
    
    function extractLabelAndCaseId(value) {
      const parts = value.split(',');
      return {
        question_label: parts[0]?.trim(),
        case_id: parts[1]?.trim()
      };
    }

    $('#downloadExcel').click(function() {
      var searchVal = $('.dataTables_filter').find('input').val();

    // if (searchVal) {
        var titleId = $('#select-case').val();
        var questionIds = [];
        var selectedItems = {};

        $('.select-information optgroup').each(function () {
            const questionId = $(this).data('question_id');
            const selectedOptions = $(this).find('option:selected');

            if (selectedOptions.length > 0) {
                questionIds.push(questionId);
                selectedItems[questionId] = [];

                selectedOptions.each(function () {
                    selectedItems[questionId].push($(this).val());
                });
            }
        });

    // Construct a form dynamically
    var form = $('<form>', {
        method: 'POST',
        action: '{{ route("export.cspro.answers") }}'
    });

    form.append($('<input>', {
        type: 'hidden',
        name: '_token',
        value: '{{ csrf_token() }}'
    }));

    form.append($('<input>', {
        type: 'hidden',
        name: 't_id',
        value: titleId
    }));

    form.append($('<input>', {
        type: 'hidden',
        name: 'search',
        value: searchVal
    }));

    // Add question_ids
    questionIds.forEach(function (qid) {
        form.append($('<input>', {
            type: 'hidden',
            name: 'question_ids[]',
            value: qid
        }));
    });

    // Add selected_items
    for (const [qid, values] of Object.entries(selectedItems)) {
        values.forEach(function (val) {
            form.append($('<input>', {
                type: 'hidden',
                name: `selected_items[${qid}][]`,
                value: val
            }));
        });
    }

    $('body').append(form);
    form.submit();
// } else {
//     alert('Please enter a search term before downloading the Excel file.');
// }

      
    });
  });
  
</script>


