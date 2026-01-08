
{{-- @extends(Auth::user()->role_manage == 2 ? 'dashboards.eva-dir.layouts.evaldir-dash-layout' : 'dashboards.eva-dd.layouts.evaldd-dash-layout') --}}
@extends('dashboards.eva-dir.layouts.evaldir-dash-layout' )
@section('title','Detail Report')
@section('content')

<link href="{{asset('plugins/select2/css/select2.min.css')}}" rel="stylesheet" />
  

<style>
    [hover-tooltip] {
  
  position: relative;
  cursor: default;
  
  &:hover {
    &::before {
      content: attr(hover-tooltip);
      font-size: 14px;
      text-align: center;
      position: absolute;
      display: block;
      left: 50%;
      min-width: 150px;
      max-width: 200px;
      bottom: calc(100% + #{10px});
      transform: translate(-50%);
      animation: fade-in 300ms ease;
      background: rgba(39, 39, 39, 1);
      border-radius: 4px;
      padding: 10px;
      color: #ffffff;
      z-index: 1;
    }
    
    &::after {
      content: '';
      position: absolute;
      display: block;
      left: 50%;
      width: 0;
      height: 0;
      bottom: calc(100% + #{10px - 4px});
      margin-left: - 6px/2;
      border: 1px solid black;
      border-color: rgba(39, 39, 39, 1) transparent transparent transparent;
      border-width: 4px 6px 0;
      animation: fade-in 300ms ease;
      z-index: 1;
    }
  }
}

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
          <div class="card-body table-responsive">
              <table id="example1" class="table table-bordered table-striped  dtr-inline">
                  <thead></thead>
                  <tbody></tbody>
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
let dataTable; // Global variable to store DataTable instance

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



// $('#btn-submit').on('click', function () {
//     var titleId = $('#select-case').val();
//     var questionIds = [];
//     var selectedItems = {};

//     $('.select-information optgroup').each(function () {
//         const questionId = $(this).data('question_id');
//         const selectedOptions = $(this).find('option:selected');

//         if (selectedOptions.length > 0) {
//             questionIds.push(questionId);
//             selectedItems[questionId] = [];
//             selectedOptions.each(function () {
//                 selectedItems[questionId].push($(this).val());
//             });
//         }
//     });

//     if (!selectedItems || Object.keys(selectedItems).length === 0) {
//         alert('Please select at least one field.');
//         return;
//     }

//     $('.detail-report').css('display', 'block');

//     if ($.fn.DataTable.isDataTable('#example1')) {
//         dataTable.destroy();
//         $('#example1').empty().html(`<thead><tr></tr></thead><tbody></tbody>`);
//     }

//     dataTable = $('#example1').DataTable({
//         processing: true,
//         serverSide: true,
//         ajax: {
//             url: "{{ route('evaldir.case-field-filtter') }}",
//             type: 'POST',
//             data: function (d) {
//                 d._token = '{{ csrf_token() }}';
//                 d.t_id = titleId;
//                 d.selected_items = selectedItems;
//                 d.question_ids = questionIds;
//             },
//             dataSrc: function (json) {
//                 console.log("Server response:", json);

//                 if (!json.columns || !Array.isArray(json.columns)) {
//                     alert("Invalid response: missing columns");
//                     return [];
//                 }

//                 // rebuild thead dynamically
//                 let thead = $('#example1 thead tr');
//                 thead.empty();
//                 json.columns.forEach(col => {
//                     thead.append(`<th>${col.title}</th>`);
//                 });

//                 return json.data;
//             }
//         },
//         // ✅ build columns dynamically at init
//         columns: (function () {
//             // empty until ajax runs, but DataTables needs structure
//             return [];
//         })(),
//         initComplete: function (settings, json) {
//             if (json && json.columns) {
//                 // replace DataTable columns after ajax first call
//                 let newCols = json.columns.map(col => ({
//                     title: col.title,
//                     data: col.data ?? null,
//                     defaultContent: 'No data'
//                 }));
//                 dataTable.clear().destroy();
//                 dataTable = $('#example1').DataTable({
//                     processing: true,
//                     serverSide: true,
//                     ajax: settings.ajax,
//                     columns: newCols,
//                     pageLength: 10
//                 });
//             }
//         },
//         pageLength: 10
//     });
// });



$('#btn-submit').on('click', function () {
    var titleId = $('#select-case').val();
    var questionIds = [];
    var selectedItems = {};

    // Collect selected items
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

    // if (!selectedItems || Object.keys(selectedItems).length === 0) {
    //     alert('Please select at least one field.');
    //     return;
    // }

    $('.detail-report').css('display', 'block');

    // Destroy old DataTable
    if ($.fn.DataTable.isDataTable('#example1')) {
        $('#example1').DataTable().clear().destroy();
        $('#example1 thead').empty();
    }

    $.ajax({
        url: "{{ route('evaldir.case-field-filtter') }}",
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            t_id: titleId,
            selected_items: selectedItems,
            question_ids: questionIds
        },
        success: function (json) {
            console.log("Server response:", json);

            if (!json || !json.data || !json.columns) {
                alert("Invalid response from server");
                return;
            }

            // build <thead>
            const thead = $('#example1 thead');
            thead.empty().append('<tr></tr>');
            json.columns.forEach(col => {
                thead.find('tr').append(`<th>${col.title}</th>`);
            });

            // initialize DataTable with correct columns
           let rowsCount = json.data.length;
            console.log("Rows count:", rowsCount);
          $('#example1').DataTable({
              destroy: true,
              data: json.data,
              columns: json.columns,
            //  pageLength: rowsCount <= 200 ? -1 : 25,
            //  lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, "All"] ]
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


