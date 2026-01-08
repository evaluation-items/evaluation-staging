
{{-- @extends(Auth::user()->role_manage == 2 ? 'dashboards.eva-dir.layouts.evaldir-dash-layout' : 'dashboards.eva-dd.layouts.evaldd-dash-layout') --}}
@extends('dashboards.eva-dir.layouts.evaldir-dash-layout' )
@section('title','Detail Report')
@section('content')
<link href="{{asset('css/select2.min.css')}}" rel="stylesheet" />

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
          <h5 class="text-dark font-weight-bold my-1 mr-5 text-center">Detail Report</h5>
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
              <label for="exampleSelect2">Select Case:</label>
              <select class="select-case form-control custom-form-control" id="select-case" onchange="caseOnchange(this.value)">
                <option value="">Select Case</option>
                @foreach($case_title as $case_item)
                <option value="{{ $case_item->t_id }}">{{ $case_item->title }}</option>
              @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="exampleSelect1">Select Questions Options:</label>
              <select class="form-multi-select form-multi-select1 select-information select2" multiple style="width: 100%;">
                {{-- @foreach($itemLabels as $itemLabel)
                  <option value="{{ $itemLabel->id }}">{{ $itemLabel->item_label }}</option>
                @endforeach --}}
              </select>
            </div>
          </div>
        </div>
      
        <div class="text-center mt-3">
          <button class="btn btn-primary btn-submit" id="btn-submit">Submit</button>
        </div>
      </div>
      <div class="card card-custom gutter-b detail-report" style="display:none;">
          <div class="card-header flex-wrap py-3">
            <div class="card-toolbar">
              <h5>List of Detail Report</h5>
            </div>
          </div>
          <div class="card-body col-md-12 table-responsive">
            <table id="example1" class="table table-bordered table-striped dataTable dtr-inline">
                <thead id="table-head">
                </thead>
                <tbody id="table-body">
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
<!-- jQuery -->
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<script src="{{asset('js/select2.min.js')}}"></script>
<script>
  function formatCheckbox(option) {
    if (!option.id) return option.text;
    const isSelected = $(option.element).prop('selected');
    const checkbox = `<input type="checkbox" ${isSelected ? 'checked' : ''} style="margin-right: 8px; transform: scale(1.3);" />`;
    return $(` <span>${checkbox}${option.text}</span>`);
  }

  $(document).ready(function () {
    $('#example1').DataTable({
        paging: true,
        searching: true,
        info: true,
    });

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
	alert('hi');     
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
                options: []
                };
            }
            groupedOptions[item.question_id].options.push({
                values: item.values,
                options: item.options
            });
            });

            Object.values(groupedOptions).forEach(function(group) {
            const optgroup = $(`<optgroup label="${group.label}"></optgroup>`);

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
      var selectedItems = [];

        var titleId = $('#select-case').val();
      // Combine Select Information and Select Fields
        $('.select-information option:selected').each(function () {
          const item = extractLabelAndCaseId($(this).val());
          if (item.question_label) {
            selectedItems.push(item);
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
                t_id:titleId
            },
           
            success: function(response) {
    // Show the table
    $('.detail-report').css('display','block');
    $('#table-head').empty();
    $('#table-body').empty();

    const headers = [];
    const groupedRows = {}; // Group answers by question_label

    // Loop through each item in the response
    response.forEach(item => {
        const questionLabel = item.question.question;

        // Add the question to the headers if it's not already present
        if (!headers.includes(questionLabel)) {
            headers.push(questionLabel);
        }

        // Initialize answer group for this question if not already done
        if (!groupedRows[questionLabel]) {
            groupedRows[questionLabel] = [];
        }

        // Loop through the answers array and store them for this question
        item.answers.forEach(answer => {
            // Add the answer to the grouped rows
            groupedRows[questionLabel].push(answer.answers); // Access the answer field directly
        });
    });

    // Append headers to the table
    headers.forEach(header => {
        $('#table-head').append(`<th>${header}</th>`);
    });

    // Find the maximum number of answers for any question
    const totalRows = Math.max(...headers.map(header => groupedRows[header].length));

    // Loop to generate table rows based on the number of answers
    for (let i = 0; i < totalRows; i++) {
        let row = '<tr>';
        let hasDataInRow = false; // Flag to track if there's any data in the row

        headers.forEach(header => {
            const answers = groupedRows[header] || [];
            const answer = answers[i] || ''; // Get answer for the current row

            // Check if the answer is not empty
            if (answer.trim() !== '') {
                hasDataInRow = true; // Mark that this row has data
            }

            row += `<td>${answer}</td>`; // Display the answer in a new row
        });

        // Only append the row if it contains data
        if (hasDataInRow) {
            $('#table-body').append(row);
        }
    }
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
  });

</script>


@endsection
