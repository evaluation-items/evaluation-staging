<?php
use App\Couchdb\Couchdb;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use App\Models\Stage;
use App\Http\ViewComposers\draftComposer;


if (!function_exists('is_gujarati')) {
    function is_gujarati($text) {
        return preg_match('/[\x{0A80}-\x{0AFF}]/u', $text); // Unicode range for Gujarati
    }
}
  if(!function_exists('makeAvatar')){
       function makeAvatar($fontPath, $dest, $char){
           $path = $dest;
           $image = imagecreate(200,200);
           $red = rand(0,255);
           $green = rand(0,255);
           $blue = rand(0,255);
           imagecolorallocate($image,$red,$green,$blue);
           $textcolor = imagecolorallocate($image,255,255,255);
           imagettftext($image,100,0,50,150,$textcolor,$fontPath,$char);
           imagepng($image,$path);
           imagedestroy($image);
           return $path;
       }
  }

  function beneficiariesGeoLocal() {
    return App\Models\Beneficiaries::all();

    // return [
    //     '1' => 'State',
    //     '2' => 'State Urban Area',
    //     '3' => 'State Rural Area',
    //     '4' => 'District',
    //     '5' => 'Tribal Belt',
    //     '6' => 'Coastal Area',
    //     '7' => 'Developing Taluka',
    //     '8' => 'Border Area'
    // ];
  }
  function units($id = null) {
    if(!is_null($id) || $id != NULL){
        return App\Models\Unit::find($id)->name;
    }else{
        return App\Models\Unit::all();

    }

    // return [
    //     '1' => 'State',
    //     '2' => 'State Urban Area',
    //     '3' => 'State Rural Area',
    //     '4' => 'District',
    //     '5' => 'Tribal Belt',
    //     '6' => 'Coastal Area',
    //     '7' => 'Developing Taluka',
    //     '8' => 'Border Area'
    // ];
  }

  function financialyears() {
    $current_year = date('Y');
    $financialyearlist = array();
    $thisyear = date('y');
    $next_year = $thisyear+1;
    $financialyearlist[] = $current_year.'-'.$next_year;
    for($i=1;$i<64;$i++) {
      $cur_year = $current_year-$i;
      $next = $i-1;
      $next_year = date('y',strtotime("-$next year"));
      $financialyearlist[] = $cur_year.'-'.$next_year;
    }
    return $financialyearlist;
  }

  function Commencementyear(){
    $startingyear = 1961;
    $currentyear = date('Y');
    $year = [];
    while ($startingyear <= $currentyear) {
      $temp = substr($startingyear, -2);
      $nextyear = $temp + 1;
      $finyear = $startingyear . $nextyear;
      $year[] = $startingyear . "-" . $nextyear;
      
      $startingyear++;
    }
    return $year;
  }

  function financialyearshelper() {
    $current_year = date('Y');
    $financialyearlist = array();
    $thisyear = date('y');
    $j=1;
    for($i=2001;$i<$current_year;$i++) {
      $cur_year = $current_year-$j;
      $next = $j-1;
      $next_year = date('y',strtotime("-$next year"));
      $financialyearlist[] = $cur_year.'-'.$next_year;
      $j++;
    }
    return $financialyearlist;
  }

  function schemegoals() {
    return [
        '1' => 'No poverty',
        '2' => 'Zero Hunger',
        '3' => 'Good Health and Well-being',
        '4' => 'Quality Education',
        '5' => 'Gender Equality',
        '6' => 'Clean Water and Sanitation',
        '7' => 'Affordable and Clean Energy',
        '8' => 'Decent Work and Economic Growth',
        '9' => 'Industry, Innovation and Infrastructure',
        '10' => 'Reduced Inequality',
        '11' => 'Sustainable Cities and Communities',
        '12' => 'Responsible Consumption and Production',
        '13' => 'Life Below Water',
        '14' => 'Life on Land',
        '15' => 'Peace and Justice Strong Institutions',
        '16' => 'Test'
    ];
  }
  function department_name($dept_id)
    {
        $id = !is_null($dept_id) ? $dept_id : Auth::user()->dept_id;
        $dept_name = App\Models\Department::where('dept_id',$id)->value('dept_name');

        if(!is_null($dept_name)){
            return $dept_name;
        }else{
            return '-';
        }
    }
    function SchemeName($id)
    {
      if(!is_null($id)){
        $scheme_name = App\Models\Proposal::where('scheme_id',$id)->value('scheme_name');
        if(!is_null($scheme_name)){
          return $scheme_name;
        }else{
            return false;
        }
      }
    }
    function proposal_name($draft_id)
    {
      if(!is_null($draft_id)){
        $scheme_name = App\Models\Proposal::where('draft_id',$draft_id)->value('scheme_name');
        if(!is_null($scheme_name)){
          return $scheme_name;
        }else{
            return false;
        }
      }
    }
    function hod_name($draft_id)
    {
         $value = App\Models\Proposal::where('draft_id',$draft_id)->value('implementing_office');

            if (!$value) {
                return '';
            }
            $list = array_map('trim', explode(',', $value));

            if (is_numeric($list[0])) {
                $names = App\Models\DepartmentHod::whereIn('id', $list)
                    ->pluck('name')
                    ->toArray();
            } else {
                $names = $list;
            }
            // ✅ Convert array to string
            return implode(', ', $names);
    //   if(!is_null($draft_id)){
    //     $hod_name = App\Models\Proposal::where('draft_id',$draft_id)->value('hod_name');
    //     if(!is_null($hod_name)){
    //       return $hod_name;
    //     }else{
    //         return '-';
    //     }
    //   }
    }
    function department_hod_name($id)
    {
      if(!is_null($id)){
        $hod_list = App\Models\DepartmentHod::where('dept_id',$id)->select('id','name')->get();
        if(!is_null($hod_list)){
          return $hod_list;
        }else{
            return '-';
        }
      }
    }
    
    if (!function_exists('getPdfContent')) {
      function getPdfContent($id, $documentName)
      {
        $environment = environmentCheck();
        $url = $environment['url'].$id."/".$documentName;
          return file_get_contents($url);
      }
    }
    function getthefile($id,$scheme) {
      $environment = environmentCheck();
      $id = 'scheme_'.$id;
      $extended = new Couchdb();
      $extended->InitConnection();
      $status = $extended->isRunning();
      $out = $extended->getDocument($environment['database'],$id);
      $arrays = json_decode($out, true);
      if(isset($arrays)) {
          $attachments = $arrays['_attachments'];
      } else {
          return "no data";
      }
     
      foreach($attachments as $attachment_name => $attachment) {
          $at_name[] = $attachment_name;
      }
      if(count($at_name) > 0) {
          foreach($at_name as $atkey=>$atvalue) {
           
              if(strpos($atvalue,$scheme) !== false) {
                   $cont = file_get_contents($environment['url'].$id."/".$atvalue);                 
                  if($cont) {
                 
                      return response($cont)->withHeaders(['Content-type'=>'application/pdf']);
                  } else {
                      return "Error fetching the document. Contact NIC";
                  }
              }else{
                dd('dhgdfj');
              }
          }
      } else {
          return 'Document not found !';
      }

  }
  function getYears()
  {
      $startYear = 2000; // Adjust this to the starting year you want
      $endYear = now()->year; // Current year

      $years = range($endYear, $startYear);

      return $years;
  }
  function getFinancialYear(){
    // Graph related work
    $proposals = App\Models\Proposal::groupBy(['draft_id','scheme_id'])->get();

    $financialYears = [];

    foreach ($proposals as $proposal) {
        $createdAt = Carbon::parse($proposal->created_at);
        $financialYearStart = $createdAt->month >= 4 ? $createdAt->year : $createdAt->year - 1;
        $financialYearEnd = $financialYearStart + 1;
        $financialYear = $financialYearStart . '-' . substr((string)$financialYearEnd, 2);
        
        if (!in_array($financialYear, $financialYears)) {
            $financialYears[] = $financialYear;
        }
    }

    sort($financialYears);
    rsort($financialYears);

    return $financialYears;
  }
  function branch_list($role_id){
    if (!is_null($role_id)) {
        $roleIds = explode(',', $role_id);
        $branchName = App\Models\BranchRole::whereIn('role_id', $roleIds)
            ->with('branch')
            ->first()
            ->branch
            ->name;
        return $branchName;
    } else {
        return '-';
    }
}


  function current_stages($stage_id){
    
    if (!is_null($stage_id)) {
      if (!is_null($stage_id)) {
        $stage = App\Models\Stage::find($stage_id);
    
        $fields = [
            'final_report' => 'Published',
            'eval_cor_date' => 'Evaluation Coordination Committee (ECC)',
            'draft_sent_eval_committee_date' => 'Draft Report sent for Evaluation Coordination Committee (ECC)',
            'dept_eval_committee_datetime' => 'Departmental Evaluation Committee (DEC)',
            'report_draft_sent_hod_date' => 'Draft Report sent for Departmental Evaluation Committee (DEC)',
            'report_draft_hod_date' => 'Inputs on Draft Report received from Implementing Office (HOD)',
            'report_sent_hod_date' => 'Draft Report sent to Implementing Office (HOD) for inputs',
            'report_enddate' => 'Report writing End',
            'report_startdate' => 'Report writing Start',
            'data_statistical_enddate' => 'Data cleaning and Statistical Analysis End',
            'data_statistical_startdate' => 'Data cleaning and Statistical Analysis Start',
            'field_survey_enddate' => 'Field Survey End',
            'field_survey_startdate' => 'Field Survey Start',
            'polot_study_date' => 'Pilot study /Digitization of Survey Forms Completed',
            'study_design_receive_hod_date' => 'Inputs on Study Design and Survey Forms received from Implementing Office (HOD)',
            'study_design_hod_date' => 'Study Design and Survey Forms sent to Implementing Office (HOD) for inputs',
            'study_design_date' => 'Study Design and Schedule Preparation',
            'scheme_hod_date' => 'Additional information / data of the scheme is sought to Implementing Office (HOD)',
            'requisition' => 'Requisition',
            'dropped' => 'Dropped'
        ];


        //  $fields = [
        //     'requisition' => 'Requisition',
        //     'scheme_hod_date' => 'Additional information / data of the scheme is sought to Implementing Office (HOD)',
        //     'study_design_date' => 'Study Design and Schedule Preparation',
        //     'study_design_hod_date' => 'Study Design and Survey Forms sent to Implementing Office (HOD) for inputs',
        //     'study_design_receive_hod_date' => 'Inputs on Study Design and Survey Forms received from Implementing Office (HOD)',
        //     'polot_study_date' => 'Pilot study /Digitization of Survey Forms Completed',
        //     'field_survey_startdate' => 'Field Survey Start',
        //     'field_survey_enddate' => 'Field Survey End',
        //     'data_statistical_startdate' => 'Data cleaning and Statistical Analysis Start',
        //     'data_statistical_enddate' => 'Data cleaning and Statistical Analysis End',
        //     'report_startdate' => 'Report writing Start',
        //     'report_enddate' => 'Report writing End',
        //     'report_sent_hod_date' => 'Draft Report sent to Implementing Office (HOD) for inputs',
        //     'report_draft_hod_date' => 'Inputs on Draft Report received from Implementing Office (HOD)',
        //     'report_draft_sent_hod_date' => 'Draft Report sent for Departmental Evaluation Committee (DEC)',
        //     'dept_eval_committee_datetime' => 'Departmental Evaluation Committee (DEC)',
        //     'draft_sent_eval_committee_date' => 'Draft Report sent for Evaluation Coordination Committee (ECC)',
        //     'eval_cor_date' => 'Evaluation Coordination Committee (ECC)',
        //     'final_report' => 'Published',
        //     'dropped' => 'Dropped'
        // ];
    
       foreach ($fields as $field => $text) {
            if (!empty($stage->$field)) {
                return $text; // return first match (highest priority)
            }
        }

        return 'Requisition sent to Concern Department';
      } else {
          return '-';
      }    
    }
  }
  function current_stage_fields() {
    return [
        'requisition' => 'Requisition',
        'scheme_hod_date' => 'Additional information / data of the scheme is sought to Implementing Office (HOD)',
        'study_design_date' => 'Study Design and Schedule Preparation',
        'study_design_hod_date' => 'Study Design and Survey Forms sent to Implementing Office (HOD) for inputs',
        'study_design_receive_hod_date' => 'Inputs on Study Design and Survey Forms received from Implementing Office (HOD)',
        'polot_study_date' => 'Pilot study /Digitization of Survey Forms Completed',
        'field_survey_startdate' => 'Field Survey Start',
        'field_survey_enddate' => 'Field Survey End',
        'data_statistical_startdate' => 'Data cleaning and Statistical Analysis Start',
        'data_statistical_enddate' => 'Data cleaning and Statistical Analysis End',
        'report_startdate' => 'Report writing Start',
        'report_enddate' => 'Report writing End',
        'report_sent_hod_date' => 'Draft Report sent to Implementing Office (HOD) for inputs',
        'report_draft_hod_date' => 'Inputs on Draft Report received from Implementing Office (HOD)',
        'report_draft_sent_hod_date' => 'Draft Report sent for Departmental Evaluation Committee (DEC)',
        'dept_eval_committee_datetime' => 'Departmental Evaluation Committee (DEC)',
        'draft_sent_eval_committee_date' => 'Draft Report sent for Evaluation Coordination Committee (ECC)',
        'eval_cor_date' => 'Evaluation Coordination Committee (ECC)',
        'final_report' => 'Published',
        'dropped' => 'Dropped'
    ];
    
}
function current_stage_key($stage_id) {
    $stage = App\Models\Stage::find($stage_id);

    $fields = array_keys(current_stage_fields());

    $lastNonNullKey = null;
    foreach ($fields as $field) {
        if (!empty($stage->$field)) {
            $lastNonNullKey = $field;
        }
    }

    return $lastNonNullKey ?? 'requisition';
}

   function StageCount($draft_id){
  
    if(!is_null($draft_id)){

        $proposal_list = App\Models\SchemeSend::where('draft_id',$draft_id)->whereIn('scheme_send.status_id', [25])
                                ->where('scheme_send.forward_btn_show', 1)
                                ->where('scheme_send.forward_id', 1)
                                ->orderByDesc('scheme_send.id')
                                ->distinct()
                                ->get();
       
                               
        $stages_item = App\Models\Stage::where('draft_id', $draft_id)->first();
      
        if(!is_null($stages_item)){
            $get_count = [
                'requisition' => 0, //1
                'study_design_date' => 0, //2
                'study_design_receive_hod_date' => 0, //3
                'polot_study_date' => 0, //4
                'field_survey_startdate' => 0, //5
                'data_entry_level_start' => 0, //6
                'report_startdate' => 0, //7
                'report_draft_hod_date' => 0, //8
                'dept_eval_committee_datetime' => 0, //9
                'eval_cor_date' => 0, //10
                'final_report'=> 0, //11
                'dropped' => 0, //12
            ];

            $get_count_delay = [
              'requisition_delay' => 0,
              'study_design_date_delay' => 0,
              'study_design_receive_hod_date_delay' => 0,
              'polot_study_date_delay' => 0,
              'field_survey_startdate_delay' => 0,
              'data_entry_level_start_delay' => 0,
              'report_startdate_delay' => 0,
              'report_draft_hod_date_delay' => 0,
              'dept_eval_committee_datetime_delay' => 0,
              'eval_cor_date_delay' => 0,
              'final_report_delay' => 0,
              'dropped_delay' => 0
          ];
         
            foreach ($proposal_list as $key => $proposal_data) {
               
                    //Requisition
                    $date = (!is_null($proposal_data->evaluation_sent_date))  ? $proposal_data->evaluation_sent_date : $proposal_data->created_at;
                    $endDate = Carbon::parse($date)->addMonths(1);
                    
                    $diffInDays = Stage::where('draft_id', $proposal_data->draft_id)
                                        ->whereNotNull('requisition')
                                        ->whereBetween('requisition', [$date, $endDate])
                                        ->get()
                                        ->sum(function ($stage) use ($endDate) {
                                            return Carbon::parse($stage->requisition)->diffInDays($endDate);
                                        });
                    $get_count['requisition'] = round($diffInDays);
                    
                    $delayCount = Stage::where('draft_id', $proposal_data->draft_id)
                                        ->whereNotNull('requisition')
                                        ->where('requisition', '>', $endDate)
                                        ->get()
                                        ->sum(function ($stage) use ($endDate) {
                                            $diff = Carbon::parse($stage->requisition)->diffInDays($endDate);
                                            return max(0, $diff); 
                                        });
                    
                    $get_count_delay['requisition_delay'] = round($delayCount);

                    //Study Design Date --> Preparation of study design and questioner
                    $StudyendDate = Carbon::parse($stages_item->requisition)->addMonths(1);
                 
                    $diffInDays1 = Stage::where('draft_id', $proposal_data->draft_id)
                        ->whereNotNull('study_design_date')
                        ->whereBetween('study_design_date', [$stages_item->study_design_date, $StudyendDate])
                        ->get()
                        ->sum(function ($stage) use ($StudyendDate) {
                            return Carbon::parse($stage->study_design_date)->diffInDays($StudyendDate);
                        });
                   
                    $get_count['study_design_date'] = round($diffInDays1);
                    
                    $delayCount1 = Stage::where('draft_id', $proposal_data->draft_id)
                                    ->whereNotNull('study_design_date')
                                    ->where('study_design_date', '>', $StudyendDate)
                                    ->get()
                                    ->sum(function ($stage) use ($StudyendDate) {
                                        return max(0, Carbon::parse($stage->study_design_date)->diffInDays($StudyendDate));
                                    });
                    
                    $get_count_delay['study_design_date_delay'] = round($delayCount1);
                   
                     //Approval from concern department -->Inputs on Study Design and Survey Forms received from Implementing Office (HOD)
                     $StudyrecendDate = Carbon::parse($stages_item->study_design_date)->addMonths(1);
                    
                     $diffInDays2 = Stage::where('draft_id', $proposal_data->draft_id)
                         ->whereNotNull('study_design_receive_hod_date')
                         ->whereBetween('study_design_receive_hod_date', [$stages_item->study_design_receive_hod_date, $StudyrecendDate])
                         ->get()
                         ->sum(function ($stage) use ($StudyrecendDate) {
                             return Carbon::parse($stage->study_design_receive_hod_date)->diffInDays($StudyrecendDate);
                         });
                     
                     $get_count['study_design_receive_hod_date'] = round($diffInDays2);
                     
                     $delayCount2 = Stage::where('draft_id', $proposal_data->draft_id)
                         ->whereNotNull('study_design_receive_hod_date')
                         ->where('study_design_receive_hod_date', '>', $StudyrecendDate)
                         ->get()
                         ->sum(function ($stage) use ($StudyrecendDate) {
                             return max(0, Carbon::parse($stage->study_design_receive_hod_date)->diffInDays($StudyrecendDate));
                         });
                     
                     $get_count_delay['study_design_receive_hod_date_delay'] = round($delayCount2);

                    //Pilot Study Date --> Pilot study and Digitization of Survey Forms Completed
                    $endDate4 = Carbon::parse($stages_item->study_design_receive_hod_date)->addDays(10);
                    
                    $diffInDays3 = Stage::where('draft_id', $proposal_data->draft_id)
                                    ->whereNotNull('polot_study_date')
                                    ->whereBetween('polot_study_date', [$stages_item->polot_study_date, $endDate4])
                                    ->get()
                                    ->sum(function ($stage) use ($endDate4) {
                                        $diff = Carbon::parse($stage->polot_study_date)->diffInDays($endDate4);
                                        return max(0, $diff); 
                                    });
                    
                    $get_count['polot_study_date'] = round($diffInDays3);
                    
                    $delayCount3 = Stage::where('draft_id', $proposal_data->draft_id)
                            ->whereNotNull('polot_study_date')
                            ->where('polot_study_date', '>', $endDate4)
                            ->get()
                            ->sum(function ($stage) use ($endDate4) {
                                $diff = Carbon::parse($stage->polot_study_date)->diffInDays($endDate4);
                                return max(0, $diff); // Return 0 if the difference is negative
                            });
                    
                    $get_count_delay['polot_study_date_delay'] = round($delayCount3);

                    //Filed Work --> Field Survey starts
                    $endDate3 = Carbon::parse($stages_item->polot_study_date)->addMonths(2);
                   // dd($endDate3);
                   
                    $diffInDays4 = Stage::where('draft_id', $proposal_data->draft_id)
                                ->whereNotNull('field_survey_startdate')
                               ->whereBetween('field_survey_startdate', [$stages_item->field_survey_startdate, $endDate3])
                                ->get()
                                ->sum(function ($stage) use ($endDate3) {
                                    $diff = Carbon::parse($stage->field_survey_startdate)->diffInDays($endDate3);
                                    return max(0, $diff); // Return 0 if the difference is negative
                                });
                
                    $get_count['field_survey_startdate'] = round($diffInDays4);
                    
                    $delayCount4 = Stage::where('draft_id', $proposal_data->draft_id)
                                        ->whereNotNull('field_survey_startdate')
                                        ->where('field_survey_startdate', '>', $endDate3)
                                        ->get()
                                        ->sum(function ($stage) use ($endDate3) {
                                            $diff = Carbon::parse($stage->field_survey_startdate)->diffInDays($endDate3);
                                            return max(0, $diff); // Return 0 if the difference is negative
                                        });
                    
                    $get_count_delay['field_survey_startdate_delay'] = round($delayCount4);
                    
                    //Data scrutiny, entry/Validation --> Data Entry Level Start
                    $endDate5 = Carbon::parse($stages_item->field_survey_startdate)->addDays(45);

                    $diffInDays5 = Stage::where('draft_id', $proposal_data->draft_id)
                                ->whereNotNull('data_entry_level_start')
                                ->whereBetween('data_entry_level_start', [$stages_item->data_entry_level_start, $endDate5])
                                ->get()
                                ->sum(function ($stage) use ($endDate5) {
                                    $diff = Carbon::parse($stage->data_entry_level_start)->diffInDays($endDate5);
                                    return max(0, $diff); // Return 0 if the difference is negative
                                });
                
                    $get_count['data_entry_level_start'] = round($diffInDays5);
                    
                    $delayCount5 = Stage::where('draft_id', $proposal_data->draft_id)
                                        ->whereNotNull('data_entry_level_start')
                                        ->where('data_entry_level_start', '>', $endDate5)
                                        ->get()
                                        ->sum(function ($stage) use ($endDate5) {
                                            $diff = Carbon::parse($stage->data_entry_level_start)->diffInDays($endDate5);
                                            return max(0, $diff); // Return 0 if the difference is negative
                                        });
                    
                    $get_count_delay['data_entry_level_start_delay'] = round($delayCount5);

                   //Report writing --> Report writing starts
                    $endDate6 = Carbon::parse($stages_item->data_entry_level_start)->addDays(25);
    
                    $diffInDays6 = Stage::where('draft_id', $proposal_data->draft_id)
                                ->whereNotNull('report_startdate')
                                ->whereBetween('report_startdate', [$stages_item->report_startdate, $endDate6])
                                ->get()
                                ->sum(function ($stage) use ($endDate6) {
                                    $diff = Carbon::parse($stage->report_startdate)->diffInDays($endDate6);
                                    return max(0, $diff); // Return 0 if the difference is negative
                                });
                
                    $get_count['report_startdate'] = round($diffInDays6);
                    
                    $delayCount6 = Stage::where('draft_id', $proposal_data->draft_id)
                                        ->whereNotNull('report_startdate')
                                        ->where('report_startdate', '>', $endDate6)
                                        ->get()
                                        ->sum(function ($stage) use ($endDate6) {
                                            $diff = Carbon::parse($stage->report_startdate)->diffInDays($endDate6);
                                            return max(0, $diff); // Return 0 if the difference is negative
                                        });
                    
                    $get_count_delay['report_startdate_delay'] = round($delayCount6);

                    //Suggestion of concern department on report --> Inputs on Draft Report received from Implementing Office (HOD)
                    $endDate7 = Carbon::parse($stages_item->report_startdate)->addDays(15);
    
                    $diffInDays7 = Stage::where('draft_id', $proposal_data->draft_id)
                                ->whereNotNull('report_draft_hod_date')
                                ->whereBetween('report_draft_hod_date', [$stages_item->report_draft_hod_date, $endDate7])
                                ->get()
                                ->sum(function ($stage) use ($endDate7) {
                                    $diff = Carbon::parse($stage->report_draft_hod_date)->diffInDays($endDate7);
                                    return max(0, $diff); // Return 0 if the difference is negative
                                });
                
                    $get_count['report_draft_hod_date'] = round($diffInDays7);
                    
                    $delayCount7 = Stage::where('draft_id', $proposal_data->draft_id)
                                        ->whereNotNull('report_draft_hod_date')
                                        ->where('report_draft_hod_date', '>', $endDate7)
                                        ->get()
                                        ->sum(function ($stage) use ($endDate7) {
                                            $diff = Carbon::parse($stage->report_draft_hod_date)->diffInDays($endDate7);
                                            return max(0, $diff); // Return 0 if the difference is negative
                                        });
                    
                    $get_count_delay['report_draft_hod_date_delay'] = round($delayCount7);

                    //DEC meeting level
                    $endDate8 = Carbon::parse($stages_item->report_draft_hod_date)->addMonth(1);
                    $diffInDays8 = Stage::where('draft_id', $proposal_data->draft_id)
                                        ->whereNotNull('dept_eval_committee_datetime')
                                        ->whereBetween('dept_eval_committee_datetime', [$stages_item->dept_eval_committee_datetime, $endDate8])
                                        ->get()
                                        ->sum(function ($stage) use ($endDate8) {
                                            $diff = Carbon::parse($stage->dept_eval_committee_datetime)->diffInDays($endDate8);
                                            return max(0, $diff); // Return 0 if the difference is negative
                                        });

                    $get_count['dept_eval_committee_datetime'] = round($diffInDays8);
                        
                    $delayCount8 = Stage::where('draft_id', $proposal_data->draft_id)
                                        ->whereNotNull('dept_eval_committee_datetime')
                                        ->where('dept_eval_committee_datetime', '>', $endDate8)
                                        ->get()
                                        ->sum(function ($stage) use ($endDate8) {
                                            $diff = Carbon::parse($stage->dept_eval_committee_datetime)->diffInDays($endDate8);
                                            return max(0, $diff); // Return 0 if the difference is negative
                                        });
                    
                    $get_count_delay['dept_eval_committee_datetime_delay'] = round($delayCount8);
                   
                     //ECC meeting level
                     $endDate9 = Carbon::parse($stages_item->dept_eval_committee_datetime)->addMonth(1);
                     $diffInDays9 = Stage::where('draft_id', $proposal_data->draft_id)
                                             ->whereNotNull('eval_cor_date')
                                             ->whereBetween('eval_cor_date', [$stages_item->eval_cor_date, $endDate9])
                                             ->get()
                                             ->sum(function ($stage) use ($endDate9) {
                                                 $diff = Carbon::parse($stage->eval_cor_date)->diffInDays($endDate9);
                                                 return max(0, $diff); 
                                             });
     
                     $get_count['eval_cor_date'] = round($diffInDays9);
                    
                    $delayCount9 = Stage::where('draft_id', $proposal_data->draft_id)
                                        ->whereNotNull('eval_cor_date')
                                        ->where('eval_cor_date', '>', $endDate9)
                                        ->get()
                                        ->sum(function ($stage) use ($endDate9) {
                                            $diff = Carbon::parse($stage->eval_cor_date)->diffInDays($endDate9);
                                            return max(0, $diff); 
                                        });
                    
                    $get_count_delay['eval_cor_date_delay'] = round($delayCount9);

                     //Published
                     $endDate10 = Carbon::parse($stages_item->eval_cor_date)->addMonth(1);
                     $diffInDays10 = Stage::where('draft_id', $proposal_data->draft_id)
                                            ->whereNotNull('final_report')
                                            ->whereBetween('final_report', [$stages_item->final_report, $endDate10])
                                            ->get()
                                            ->sum(function ($stage) use ($endDate10) {
                                                $diff = Carbon::parse($stage->final_report)->diffInDays($endDate10);
                                                return max(0, $diff); 
                                            });
    
                    $get_count['final_report'] = round($diffInDays10);
                    
                    $delayCount10 = Stage::where('draft_id', $proposal_data->draft_id)
                                        ->whereNotNull('final_report')
                                        ->where('final_report', '>', $endDate10)
                                        ->get()
                                        ->sum(function ($stage) use ($endDate10) {
                                            $diff = Carbon::parse($stage->final_report)->diffInDays($endDate10);
                                            return max(0, $diff); 
                                        });
                    
                    $get_count_delay['final_report_delay'] = round($delayCount10);

                    //Dropped
                    $endDate11 = Carbon::parse($stages_item->final_report)->addMonth(1);
                    $diffInDays11 = Stage::where('draft_id', $proposal_data->draft_id)
                                        ->whereNotNull('dropped')
                                        ->whereBetween('dropped', [$stages_item->dropped, $endDate11])
                                        ->get()
                                        ->sum(function ($stage) use ($endDate11) {
                                            $diff = Carbon::parse($stage->dropped)->diffInDays($endDate11);
                                            return max(0, $diff); 
                                        });

                    $get_count['dropped'] = round($diffInDays11);
                    
                    $delayCount11 = Stage::where('draft_id', $proposal_data->draft_id)
                                        ->whereNotNull('dropped')
                                        ->where('dropped', '>', $endDate11)
                                        ->get()
                                        ->sum(function ($stage) use ($endDate11) {
                                            $diff = Carbon::parse($stage->dropped)->diffInDays($endDate11);
                                            return max(0, $diff); 
                                        });
                    
                    $get_count_delay['dropped_delay'] = round($delayCount11);
            
            }
            $data = [
              'get_count' =>$get_count,
              'get_count_delay' => $get_count_delay
            ];
            return $data;
        }
    }else{
        return false;
    }
        
   }
   function proposalDatestage($scheme_id){
   
    if(!is_null($scheme_id)){
       $draft_id = App\Models\Proposal::where('scheme_id',$scheme_id)->pluck('draft_id');
       if(!is_null($draft_id)){
           $evaluation_date = App\Models\SchemeSend::where('draft_id',$draft_id)->pluck('evaluation_sent_date');
           if(is_null($evaluation_date[0])){
             $evaluation_date = App\Models\SchemeSend::where('draft_id',$draft_id)->pluck('created_at');
           }
           return $evaluation_date;
       }
    }else{
        return false;
    }
   }
   
   function barchartScheme(){

    $role_id = App\Models\SchemeSend::whereNotNull('team_member_dd')->pluck('draft_id');
    
                
    $scheme_list = App\Models\SchemeSend::select('scheme_send.team_member_dd','proposals.draft_id', 'proposals.scheme_name')->leftJoin('itransaction.proposals', 'scheme_send.draft_id', '=', 'proposals.draft_id')
                                        ->whereNotNull('scheme_send.team_member_dd')
                                        ->whereIn('proposals.status_id', [23,28])
                                        ->whereIn('scheme_send.draft_id',$role_id)
                                        ->orderBy('proposals.scheme_name', 'ASC')
                                      //  ->groupBy('proposals.draft_id', 'proposals.scheme_name') // Group by all selected columns
                                        ->get();
                                        
                                       
            $scheme_name = [];
            foreach($scheme_list as $key=> $sheme_item){

                $user_ids = [];
                if (!is_null($sheme_item->team_member_dd)) {
                    $role = explode(',', $sheme_item->team_member_dd);
                    $user_ids = App\Models\User::whereIn('role', $role)->pluck('id')->toArray();
                }
                if(!empty($user_ids) && in_array(Auth::user()->id, $user_ids)){
                    $scheme_name[$sheme_item->draft_id] = $sheme_item->scheme_name;
                    
                }
            }
    
        return $scheme_name;
    // Proposal::where('status_id','23')->orderBy('scheme_name','ASC')->groupBy('draft_id')->pluck('scheme_name','draft_id');
   }

   function Remarks($scheme_id){
    $remark_msg = App\Models\Stage::where('scheme_id',$scheme_id)->value('polot_study_text');
    if(!is_null($remark_msg)){
        return $remark_msg;
    }else{
        return null;
    }
        
   }
   function environmentCheck(){
    $app_name = Illuminate\Support\Facades\App::environment();
    if ($app_name == 'local')
        {
            $item = [
                'database' => 'gad',
                'url' => "http://nic:nic@127.0.0.1:5984/gad/"
            ];
          
        }else{
            $item = [
                'database' => 'evaluation',
                'url' => "http://niceval:n!c3v@l123@10.10.2.238:5984/evaluation/"
            ];
        }
        return $item;
   }
   function countLibrary($year) {
    $count = 0;
    if(!is_null($year)){
      $count =  App\Models\DigitalProjectLibrary::where('year',$year)->count();
    }
    return $count;
   }
   function implementingOfc($scheme_id)
    {
     
          $value = App\Models\Scheme::where('scheme_id', $scheme_id)
        ->value('implementing_office');

            if (!$value) {
                return '';
            }

            $list = array_map('trim', explode(',', $value));

            if (is_numeric($list[0])) {
                $names = App\Models\DepartmentHod::whereIn('id', $list)
                    ->pluck('name')
                    ->toArray();
            } else {
                $names = $list;
            }

            // ✅ Convert array to string
            return implode(', ', $names);
    }
    function completedStudy($date) {

        $date = \Carbon\Carbon::parse($date);

        // If date is before April → previous FY completed
        if ($date->month < 4) {
            $startYear = $date->year - 2;
            $endYear   = $date->year - 1;
        } else {
            $startYear = $date->year - 1;
            $endYear   = $date->year;
        }

        return $startYear . '-' . substr($endYear, -2);
    }
?>
