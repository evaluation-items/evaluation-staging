<!DOCTYPE html>
<html>
<head>
    <title>Final Report PDF</title>

    <style>
      @font-face {
            font-family: 'Noto Sans Gujarati';
            src: url('{{ storage_path('fonts/NotoSansGujarati-Regular.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'Arial';
            src: url('{{ storage_path('fonts/Arial-Regular.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        body {
            font-family: 'Arial', sans-serif;
        }

        .gujarati-text {
            font-family: 'Noto Sans Gujarati', sans-serif;
        }
        h1 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .containers {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .deptName, .complted, .schemeName {
            flex: 1;
            text-align: left;
        }

        .complted {
            text-align: center;
        }

        .schemeName {
            text-align: right;
        }
    </style>
</head>

<body>
  @php
  use Carbon\Carbon;
    $i = 1;
  @endphp
    <h1>Final Report</h1>
      <div class="container">
    
          <!--begin: Wizard-->
          <div class="containers">
            <div class="deptName">
              <label><strong>Department Name</strong></label>
              <span>{{ department_name($data['dept_id'])}}</span>
            </div>
            @if(!is_null($data['final_report']))
              <div class="complted">
                <label><strong>Stages Completed</strong></label>
                <span>{{(!empty($data['final_report']) ?  Carbon::Parse($data['final_report'])->format('M d Y') : "" )}}</span>
              </div>
            @endif
         
            <div class="schemeName">
              <label><strong>Scheme Name</strong></label>
              <span class="{{ is_gujarati($scheme_data) ? 'gujarati-text' : '' }}">{{$scheme_data}}</span>
            </div>
          </div>
         
          <div class="wizard wizard-4" id="kt_wizard_v4" data-wizard-state="step-first" data-wizard-clickable="true">
            <div class="card card-custom card-shadowless rounded-top-0">
              <div class="card-body p-10">
               
                <div class="row table-responsive">
                  <table width="100%" class="table table-bordered table-hover table-stripped">
                    <tr>
                      <th width="50%">Proposal Date</th>
                      <td width="25%">
                          {{ (!empty($data['proposal_date']) ? Carbon::Parse($data['proposal_date'])->format('d-m-Y') : '') }}
                      </td>
                      <td width="25%"></td>
                    </tr>
                    <tr>
                      <th width="50%">Additional information / data of the scheme is sought to Implementing Office (HOD)</th>
                      <td width="25%">
                          {{ ((!empty($data['scheme_hod_date'])) ? Carbon::Parse($data['scheme_hod_date'])->format('d-m-Y') : "") }}
                      </td>
                      <td width="25%">
                        {{ ((!empty($data['scheme_hod_text'])) ? $data['scheme_hod_text'] : "") }}
                      </td>
                    </tr>
                    <tr>
                      <th width="50%">Requisition</th>
                      <td width="25%">
                          {{ ((!empty($data['requisition'])) ? Carbon::Parse($data['requisition'])->format('d-m-Y') : "") }}
                      </td>
                      <td width="25%">
                        {{ ((!empty($data['requisition_text'])) ? $data['requisition_text'] : "") }}
                      </td>
                    </tr>
                    <tr>
                      <th width="50%">Study Design and Schedule Preparation</th>
                      <td width="25%">
                        {{ (!empty($data['study_design_date']) ? Carbon::Parse($data['study_design_date'])->format('d-m-Y') : "" ) }}
                      </td>
                      <td width="25%">
                        {{ ((!empty($data['study_design_text'])) ? $data['study_design_text'] : "") }}
                      </td>
                    </tr>
                
                    <tr>
                      <th width="50%">Inputs on Study Design and Survey Forms received from Implementing Office (HOD)</th>
                      <td width="25%">
                        {{ (!empty($data['study_design_receive_hod_date']) ? Carbon::Parse($data['study_design_receive_hod_date'])->format('d-m-Y') : "" ) }}
                      </td>
                      <td width="25%">
                        {{ ((!empty($data['study_design_receive_hod_text'])) ? $data['study_design_receive_hod_text'] : "") }}
                      </td>
                    </tr>
                    <tr>
                      <th width="50%"> Pilot study and Digitization of Survey Forms Completed</th>
                      <td width="25%">
                        {{ (!empty($data['polot_study_date']) ? Carbon::Parse($data['polot_study_date'])->format('d-m-Y') : "" ) }}
                      </td>
                      <td width="25%">
                        {{ ((!empty($data['polot_study_text'])) ? $data['polot_study_text'] : "") }}
                      </td>
                    </tr>
                    <tr>
                      <th width="50%">Field Survey</th>
                      <td width="25%">
                        {{(!empty($data['field_survey_startdate']) ? Carbon::Parse($data['field_survey_startdate'])->format('d-m-Y') : "" ) }} 
                      </td>
                      <td width="25%">
                        {{ ((!empty($data['field_survey_text'])) ? $data['field_survey_text'] : "") }}
                      </td>
                    </tr>
                  
                    <tr>
                      <th width="50%">Data cleaning and Statistical Analysis</th>
                      <td width="25%">
                        {{ (!empty($data['data_statistical_startdate']) ? Carbon::Parse($data['data_statistical_startdate'])->format('d-m-Y') : "" ) }}
                      </td>
                      <td width="25%">
                        {{ ((!empty($data['data_statistical_text'])) ? $data['data_statistical_text'] : "") }}
                      </td>
                    </tr>
                    <tr>
                      <th width="50%">Data Entry Level</th>
                      <td width="25%">
                        {{ (!empty($data['data_entry_level_start']) ? Carbon::Parse($data['data_entry_level_start'])->format('d-m-Y') : "" ) }}
                      </td>
                      <td width="25%">
                        {{ ((!empty($data['data_entry_level_start_text'])) ? $data['data_entry_level_start_text'] : "") }}
                      </td>
                    </tr>
                    
                    <tr>
                      <th width="50%">Report writing</th>
                      <td width="25%">
                        {{ (!empty($data['report_startdate']) ? Carbon::Parse($data['report_startdate'])->format('d-m-Y') : "" ) }}
                      </td>
                      <td width="25%">
                        {{ ((!empty($data['report_text'])) ? $data['report_text'] : "") }}
                      </td>
                    </tr>
                   
                    <tr>
                      <th width="50%">Inputs on Draft Report received from Implementing Office (HOD)</th>
                      <td width="25%">
                        {{ (!empty($data['report_draft_hod_date']) ? Carbon::Parse($data['report_draft_hod_date'])->format('d-m-Y') : "" ) }}
                      </td>
                      <td width="25%">{{!empty($data['report_draft_hod_text']) ? $data['report_draft_hod_text'] : ""}}</td>
                    </tr>

                   
                    <tr>
                      <th width="50%">Date of Departmental Evaluation Committee (DEC)</th>
                      <td width="25%">{{(!empty($data['dept_eval_committee_datetime']) ? Carbon::Parse($data['dept_eval_committee_datetime'])->format('d-m-Y H:i A') : '')}}</td>
                      <td width="25%">{{!empty($data['dept_eval_committee_text']) ? $data['dept_eval_committee_text'] : ""}}</td>
                    </tr>

                    <tr>
                        <th width="50%">Date of Evaluation Coordination Committee (ECC)</th>
                        <td width="25%">{{(!empty($data['eval_cor_date']) ?  Carbon::Parse($data['eval_cor_date'])->format('d-m-Y H:I A') : '')}}</td>
                        <td width="25%">{{!empty($data['eval_cor_text']) ? $data['eval_cor_text'] : ""}}</td>
                    </tr>
                    
                    <tr>
                      <th width="50%">Final Report</th>
                      <td width="25%">{{(!empty($data['final_report']) ?  Carbon::Parse($data['final_report'])->format('d-m-Y') : "" )}}</td>
                      <td width="25%">
                        @if(!empty($data['document']))  
                            {{$data['document']}}
                           {{-- <a href="{{ route("stages.get_the_file",[$data['scheme_id'],$data['document']]) }}" target="_blank" title="{{ $data['document'] }}">  <i class="fas fa-file-pdf fa-2x" style="color:red;"></i></a> --}}
                        @else
                        No Report Found
                        @endif
                      </td>
                    </tr>
                    <tr>
                      <th width="50%">Dropped</th>
                      <td width="25%">{{(!empty($data['dropped']) ?  Carbon::Parse($data['dropped'])->format('d-m-Y') : '')}}</td>
                      <td width="25%">{{!empty($data['dropped_text']) ? $data['dropped_text'] : ""}}</td>
                    </tr>

                  </table>
                 
                </div>
              </div>
            </div>
          </div>
      
      </div>
    
   
   <script>
    var is_chrome = function () { return Boolean(window.chrome); }
    if(is_chrome){
        window.print();
        //  setTimeout(function(){window.close();}, 10000);
        //  give them 10 seconds to print, then close
    }else{
        window.print();
    }
    </script>
</body>
</html>
