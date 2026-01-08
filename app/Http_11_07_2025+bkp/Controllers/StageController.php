<?php

namespace App\Http\Controllers;

use App\Models\Stage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Implementation;
use Illuminate\Support\Facades\Auth;
use App\Models\SchemeSend;
use App\Models\Proposal;
use App\Models\Activitylog;
use DB;
use URL;
use App\Models\User_user_role_deptid;
use App\Models\Department;
use Session;
use App\Models\Sdggoals;
use App\Models\Attachment;
use App\Models\Scheme;
use App\Models\SurveySchedule;
use App\Couchdb\Couchdb;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;
use function App\Helpers\PdfHelper\getPdfContent;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SummaryReport;
use App\Exports\ExcelReport;
use Illuminate\Support\Facades\Crypt;


class StageController extends Controller
{
    public $envirment;
    public function __construct(){
        $this->envirment = environmentCheck();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $name =  $scheme_id = "";
        if($request->scheme_id){
            $scheme_id = $request->scheme_id;
            $name = 'scheme_id';
        }else{
            $scheme_id =  $request->draft_id;
            $name ='draft_id';
        }
        $stages = Stage::where($name, $scheme_id)->latest()->first();
     
        if($stages){
            $data = Proposal::where($name,$scheme_id)->latest()->first();
            return view('dashboards.stage.create',compact('data','stages'));
        }else{
            $data = Proposal::where($name,$scheme_id)->latest()->first();
            return view('dashboards.stage.create',compact('data'));
        }
    
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       // dd('store');
        $input = $request->all();
       
        $input['user_id'] = Auth::user()->id;
        $input['scheme_id'] = $request->scheme_id;
        $input['dept_id'] = $request->dept_id;
        $input['draft_id'] = $request->draft_id;
        $input['proposal_date'] = (!empty($request->proposal_date)) ?  Carbon::parse($request->proposal_date)->format('Y-m-d') : null;
        $input['requisition'] =  (!empty($request->requisition)) ? Carbon::parse($request->requisition)->format('Y-m-d') : null;
        $input['scheme_hod_date'] = (!empty($request->scheme_hod_date)) ? Carbon::parse($request->scheme_hod_date)->format('Y-m-d') : null;
        $input['study_design_date'] = (!empty($request->study_design_date)) ? Carbon::parse($request->study_design_date)->format('Y-m-d') : null;
        $input['study_design_hod_date'] = (!empty($request->study_design_hod_date)) ? Carbon::parse($request->study_design_hod_date)->format('Y-m-d') : null;
        $input['study_design_receive_hod_date'] = (!empty($request->study_design_receive_hod_date)) ? Carbon::parse($request->study_design_receive_hod_date)->format('Y-m-d') : null;
        $input['polot_study_date'] = (!empty($request->polot_study_date)) ? Carbon::parse($request->polot_study_date)->format('Y-m-d') : null;
        $input['field_survey_startdate'] = (!empty($request->field_survey_startdate)) ? Carbon::parse($request->field_survey_startdate)->format('Y-m-d') : null;
        $input['field_survey_enddate'] = (!empty($request->field_survey_enddate)) ? Carbon::parse($request->field_survey_enddate)->format('Y-m-d') : null;
        $input['data_statistical_startdate'] = (!empty($request->data_statistical_startdate)) ? Carbon::parse($request->data_statistical_startdate)->format('Y-m-d') : null;
        $input['data_statistical_enddate'] = (!empty($request->data_statistical_enddate)) ? Carbon::parse($request->data_statistical_enddate)->format('Y-m-d') : null;
        $input['report_startdate'] = (!empty($request->report_startdate)) ? Carbon::parse($request->report_startdate)->format('Y-m-d') : null;
        $input['report_enddate'] = (!empty($request->report_enddate)) ? Carbon::parse($request->report_enddate)->format('Y-m-d') : null;
        $input['report_sent_hod_date'] = (!empty($request->report_sent_hod_date)) ? Carbon::parse($request->report_sent_hod_date)->format('Y-m-d') : null;
        $input['report_sent_text'] = (!empty($request->report_sent_text)) ? $request->report_sent_text : null;       
        $input['report_draft_hod_date'] = (!empty($request->report_draft_hod_date)) ? Carbon::parse($request->report_draft_hod_date)->format('Y-m-d') : null;
        $input['report_draft_hod_text'] = (!empty($request->report_draft_hod_text)) ? $request->report_draft_hod_text : null;
        $input['report_draft_sent_hod_date'] = (!empty($request->report_draft_sent_hod_date)) ? Carbon::parse($request->report_draft_sent_hod_date)->format('Y-m-d') : null;
        $input['report_draft_sent_hod_text'] = (!empty($request->report_draft_sent_hod_text)) ? $request->report_draft_sent_hod_text : null;
        $input['dept_eval_committee_datetime'] = (!empty($request->dept_eval_committee_datetime)) ? Carbon::parse($request->dept_eval_committee_datetime)->format('Y-m-d H:i:s') : null;
        $input['draft_sent_eval_committee_date'] = (!empty($request->draft_sent_eval_committee_date)) ? Carbon::parse($request->draft_sent_eval_committee_date)->format('Y-m-d') : null;
        $input['survey_review'] = (!empty($request->field_survey_startdate)) ? 1 : 0;
        $input['eval_cor_date'] = (!empty($request->eval_cor_date)) ?   Carbon::parse($request->eval_cor_date)->format('Y-m-d H:i:s') : null;
        $input['final_report'] = (!empty($request->final_report)) ? Carbon::parse($request->final_report)->format('Y-m-d') : null;
        $input['dropped'] = (!empty($request->dropped)) ? Carbon::parse($request->dropped)->format('Y-m-d') : null;

        $input['data_entry_level_start'] = (!empty($request->data_entry_level_start)) ? Carbon::parse($request->data_entry_level_start)->format('Y-m-d') : null;
        $input['data_entry_level_start_text'] = (!empty($request->data_entry_level_start_text)) ? $request->data_entry_level_start_text : null;
       
        $input['data_entry_level_end'] = (!empty($request->data_entry_level_end)) ? Carbon::parse($request->data_entry_level_end)->format('Y-m-d') : null;
        $input['data_entry_level_end_text'] = (!empty($request->data_entry_level_end_text)) ? $request->data_entry_level_end_text : null;

        if($request->hasFile('document')) {
            $document = $request->file('document');
            $rev = Attachment::where('scheme_id',$stages->scheme_id)->value('couch_rev_id');
            $doc_id = "scheme_".$stages->scheme_id;
            $docid = 'document'; 
            $path['id'] = $docid;
            $extended = new Couchdb();
            $extended->InitConnection();
            $status = $extended->isRunning();
            $path['tmp_name'] = $document->getRealPath();
            $path['extension']  = $document->getClientOriginalExtension();
            $path['name'] = $doc_id.'_'.$path['id'].'.'.$path['extension'];
            if(is_null($rev)){
                $dummy_data = array(
                    'scheme_id' => $doc_id
                );
                $out = $extended->createDocument($dummy_data, $this->envirment['database'],$doc_id);
                $array = json_decode($out, true);
                $id = $array['id'];
                $rev = $array['rev'];
                $data['scheme_id'] = $stages->scheme_id;
                $data['couch_doc_id'] = $id;
                $data['couch_rev_id'] = $rev;
                $attachment = Attachment::create($data);
            }
            $out = $extended->createAttachmentDocument( $this->envirment['database'],$doc_id,$rev,$path);
            $array = json_decode($out, true);
            $rev = $array['rev'];
            if(isset($rev)) {
                $result = Attachment::where('scheme_id',$request->scheme_id)->update(['couch_rev_id'=>$rev]);
            }                    
            $input['document'] = $path['name'];
        }

        Stage::create($input);
        $act['userid'] = Auth::user()->id;
        $act['ip'] = $request->ip();
        $act['activity'] = 'Stage Stored';
        $act['officecode'] = Auth::user()->dept_id;
        $act['pagereferred'] = $request->url();
        Activitylog::insert($act);
        return redirect()->back()->withSuccess('Stage created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Stage  $stage
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        $role = Auth::user()->role_manage;
   
        // Define default layout
        $layout = 'dashboards.proposal.layouts.sidebar';
        if ($role == 3 || $role == 4 || $role == 5 || $role == 6 || $role == 7) {
            $layout = 'dashboards.eva-dd.layouts.evaldd-dash-layout';
        }elseif ($role == 2) {
             $layout = 'dashboards.eva-dir.layouts.evaldir-dash-layout';
        }elseif ($role == 1) {
          $layout = 'dashboards.gad-sec.layouts.gadsec-dash-layout';
        }

        if(!empty($id)){

            $stages = Stage::where('scheme_id',$id)->latest()->first();
            $scheme_name = Proposal::select('scheme_name')->where('scheme_id',$id)->latest()->first();
            $stages_completed = Stage::where('scheme_id',$id)->whereNotNull('document')->latest()->first();
            return view('dashboards.stage.view',compact('stages','scheme_name','stages_completed','layout'));
        }
        Stage::create($input);
        $act['userid'] = Auth::user()->id;
        $act['ip'] = $request->ip();
        $act['activity'] = 'Stage View Page';
        $act['officecode'] = Auth::user()->dept_id;
        $act['pagereferred'] = $request->url();
        Activitylog::insert($act);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Stage  $stage
     * @return \Illuminate\Http\Response
     */
    
    public function edit($id)
    {
        $data = Stage::find($id);
     
        return view('dashboards.stage.create',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Stage  $stage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $stages = Stage::find($id);
      
        $input = $request->all();
        $input['user_id'] = Auth::user()->id;
        $input['scheme_id'] = $request->scheme_id;
        $input['dept_id'] = $request->dept_id;
        $input['draft_id'] = $request->draft_id;

      
        if (isset($request->proposal_date) && $request->has('proposal_date')) {
            $input['proposal_date'] = Carbon::parse($request->proposal_date)->format('Y-m-d');
        }
        
        if (isset($request->requisition) && $request->has('requisition')) {
            $input['requisition'] = Carbon::parse($request->requisition)->format('Y-m-d');
        }
        
        if (isset($request->scheme_hod_date) && $request->has('scheme_hod_date')) {
            $input['scheme_hod_date'] = Carbon::parse($request->scheme_hod_date)->format('Y-m-d');
        }
        
        if (isset($request->study_design_date) && $request->has('study_design_date')) {
            $input['study_design_date'] = Carbon::parse($request->study_design_date)->format('Y-m-d');
        }
        
        if (isset($request->study_design_hod_date) && $request->has('study_design_hod_date')) {
            $input['study_design_hod_date'] = Carbon::parse($request->study_design_hod_date)->format('Y-m-d');
        }
        
        if (isset($request->study_design_receive_hod_date) && $request->has('study_design_receive_hod_date')) {
            $input['study_design_receive_hod_date'] = Carbon::parse($request->study_design_receive_hod_date)->format('Y-m-d');
        }

        if (isset($request->polot_study_date) && $request->has('polot_study_date')) {
            $input['polot_study_date'] = Carbon::parse($request->polot_study_date)->format('Y-m-d');
        }

        if (isset($request->field_survey_startdate) && $request->has('field_survey_startdate')) {
            $input['field_survey_startdate'] = Carbon::parse($request->field_survey_startdate)->format('Y-m-d');
            // $input['survey_review'] = 1 ;
        }

        if (isset($request->field_survey_enddate) && $request->has('field_survey_enddate')) {
            $input['field_survey_enddate'] = Carbon::parse($request->field_survey_enddate)->format('Y-m-d');
        }

        if (isset($request->data_statistical_startdate) && $request->has('data_statistical_startdate')) {
            $input['data_statistical_startdate'] = Carbon::parse($request->data_statistical_startdate)->format('Y-m-d');
        }

        if (isset($request->data_statistical_enddate) && $request->has('data_statistical_enddate')) {
            $input['data_statistical_enddate'] = Carbon::parse($request->data_statistical_enddate)->format('Y-m-d');
        }
       
        if (isset($request->report_startdate) && $request->has('report_startdate')) {
            $input['report_startdate'] = Carbon::parse($request->report_startdate)->format('Y-m-d');
        }
       
        if (isset($request->report_enddate) && $request->has('report_enddate')) {
            $input['report_enddate'] = Carbon::parse($request->report_enddate)->format('Y-m-d');
        }

        if (isset($request->report_sent_hod_date) && $request->has('report_sent_hod_date')) {
            $input['report_sent_hod_date'] = Carbon::parse($request->report_sent_hod_date)->format('Y-m-d');
        }

        if (isset($request->report_sent_text) && $request->has('report_sent_text')) {
            $input['report_sent_text'] = $request->report_sent_text;
        }

        if (isset($request->report_draft_hod_date) && $request->has('report_draft_hod_date')) {
            $input['report_draft_hod_date'] = Carbon::parse($request->report_draft_hod_date)->format('Y-m-d');
        }

        if (isset($request->report_draft_hod_text) && $request->has('report_draft_hod_text')) {
            $input['report_draft_hod_text'] = $request->report_draft_hod_text;
        }

        if (isset($request->report_draft_hod_text) && $request->has('report_draft_hod_text')) {
            $input['report_draft_hod_text'] = $request->report_draft_hod_text;
        }

        if (isset($request->report_draft_sent_hod_date) && $request->has('report_draft_sent_hod_date')) {
            $input['report_draft_sent_hod_date'] = Carbon::parse($request->report_draft_sent_hod_date)->format('Y-m-d');
        }

        if (isset($request->report_draft_sent_hod_text) && $request->has('report_draft_sent_hod_text')) {
            $input['report_draft_sent_hod_text'] = $request->report_draft_sent_hod_text;
        }

        if (isset($request->dept_eval_committee_datetime) && $request->has('dept_eval_committee_datetime')) {
            $input['dept_eval_committee_datetime'] = Carbon::parse($request->dept_eval_committee_datetime)->format('Y-m-d');
        }

        if (isset($request->draft_sent_eval_committee_date) && $request->has('draft_sent_eval_committee_date')) {
            $input['draft_sent_eval_committee_date'] = Carbon::parse($request->draft_sent_eval_committee_date)->format('Y-m-d');
        }

        if (isset($request->eval_cor_date) && $request->has('eval_cor_date')) {
            $input['eval_cor_date'] = Carbon::parse($request->eval_cor_date)->format('Y-m-d');
        }

        if (isset($request->final_report) && $request->has('final_report')) {
            $input['final_report'] = Carbon::parse($request->final_report)->format('Y-m-d');
        }

        if (isset($request->dropped) && $request->has('dropped')) {
            $input['dropped'] = Carbon::parse($request->dropped)->format('Y-m-d');
        }

        if (isset($request->data_entry_level_start) && $request->has('data_entry_level_start')) {
            $input['data_entry_level_start'] = Carbon::parse($request->data_entry_level_start)->format('Y-m-d');
        }

        if (isset($request->data_entry_level_start_text) && $request->has('data_entry_level_start_text')) {
            $input['data_entry_level_start_text'] = $request->data_entry_level_start_text;
        }

        if (isset($request->data_entry_level_end) && $request->has('data_entry_level_end')) {
            $input['data_entry_level_end'] = Carbon::parse($request->data_entry_level_end)->format('Y-m-d');
        }

        if (isset($request->data_entry_level_end_text) && $request->has('data_entry_level_end_text')) {
            $input['data_entry_level_end_text'] = $request->data_entry_level_end_text;
        }

        

        // $document_file_name = '';
        // if($request->has('document')) {
        //     $beneficiaries_coverage = $request->file('document');
        //     $rev = Attachment::where('scheme_id',$stages->scheme_id)->value('couch_rev_id');
           
        //     $extended = new Couchdb();
        //     $extended->InitConnection();
        //     $status = $extended->isRunning();
        //     $doc_id = "scheme_".$stages->scheme_id;
        //     $docid = 'document'; 
        //     $path['id'] = $docid;
        //     $path['tmp_name'] = $beneficiaries_coverage->getRealPath();
        //     $path['extension']  = $beneficiaries_coverage->getClientOriginalExtension();
        //     $path['name'] = $doc_id.'_'.$path['id'].'.'.$path['extension'];
        //     if(is_null($rev)){
        //         $dummy_data = array(
        //             'scheme_id' => $doc_id
        //         );
        //         $out = $extended->createDocument($dummy_data, $this->envirment['database'],$doc_id);
        //         $array = json_decode($out, true);
        //         $id = $array['id'];
        //         $rev = $array['rev'];
        //         $data['scheme_id'] = $stages->scheme_id;
        //         $data['couch_doc_id'] = $id;
        //         $data['couch_rev_id'] = $rev;
        //         $attachment = Attachment::create($data);
        //     }
        //     $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
        //     $array = json_decode($out, true);
        //     //$rev = $array['rev'];
        //     if(isset($rev)) {
        //         $result = Attachment::where('scheme_id',$stages->scheme_id)->update(['couch_rev_id'=>$rev]);
        //     }                    
        //     $input['document'] = $path['name'];
        // }

        if($request->hasFile('document')) {
            $document = $request->file('document');
            $rev = Attachment::where('scheme_id',$stages->scheme_id)->value('couch_rev_id');
            $doc_id = "scheme_".$stages->scheme_id;
            $docid = 'document'; 
            $path['id'] = $docid;
            $path['tmp_name'] = $document->getRealPath();
            $path['extension']  = $document->getClientOriginalExtension();
            $path['name'] = $doc_id.'_'.$path['id'].'.'.$path['extension'];
            $extended = new Couchdb();
            $extended->InitConnection();
            $status = $extended->isRunning();
            if(is_null($rev)){
                $dummy_data = array(
                    'scheme_id' => $doc_id
                );
                $out = $extended->createDocument($dummy_data, $this->envirment['database'],$doc_id);
                $array = json_decode($out, true);
                $id = $array['id'];
                $rev = $array['rev'];
                $data['scheme_id'] = $stages->scheme_id;
                $data['couch_doc_id'] = $id;
                $data['couch_rev_id'] = $rev;
                $attachment = Attachment::create($data);
            }
            $out = $extended->createAttachmentDocument( $this->envirment['database'],$doc_id,$rev,$path);
            $array = json_decode($out, true);
            $rev = $array['rev'];
            if(isset($rev)) {
                $result = Attachment::where('scheme_id',$request->scheme_id)->update(['couch_rev_id'=>$rev]);
            }                    
            $input['document'] = $path['name'];
        }
       // dd($input);
        $stages->update($input);
        Stage::create($input);
        $act['userid'] = Auth::user()->id;
        $act['ip'] = $request->ip();
        $act['activity'] = 'Stage Updated';
        $act['officecode'] = Auth::user()->dept_id;
        $act['pagereferred'] = $request->url();
        Activitylog::insert($act);
        return redirect()->back()->withSuccess('Stage update successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Stage  $stage
     * @return \Illuminate\Http\Response
     */
    public function destroy(Stage $stage)
    {
        //
    }
    public function downloadPdf($id){
        $data = Stage::findOrFail($id); 
        $scheme_data = SchemeName($data['scheme_id']);
        $pdf = PDF::loadView('dashboards.stage.pdf.view', compact('data','scheme_data'))->setPaper('a4', 'landscape'); // Pass data to the view
        return $pdf->download($scheme_data.'.pdf'); // Download the PDF file
    }
    
    public function downloadExcel($id){
        if ($id) {
            try {
                $data = Stage::findOrFail($id); 
                $scheme_data = SchemeName($data['scheme_id']);
    
                return Excel::download(new ExcelReport($data), $scheme_data.'.xlsx');
    
            } catch (DecryptException $e) {
                return response()->json(['error' => 'Invalid encrypted data'], 400);
            }
        }
    }    

//    public function surveyData(Request $request){
       
//             try{
//                 $data = [
//                     'user_id' => Auth::user()->id,
//                     'scheme_id' => $request->scheme_id,
//                     'dept_id' => $request->dept_id,
//                     'draft_id' => $request->draft_id,
//                     'scheme_name' => (!is_null($request->scheme_name)) ? $request->scheme_name : null,
//                     'total_scheme' => (!is_null($request->total_scheme)) ? $request->total_scheme : null,
//                 ];
              
//                 // Save data to the database
//                 if (!empty($data['scheme_name'])) {
//                     foreach ($data['scheme_name'] as $key => $schemeName) {
//                         $totalScheme = !is_null($data['total_scheme'][$key]) ? $data['total_scheme'][$key] : 0;
              
//                         SurveySchedule::create([
//                             'user_id' => $data['user_id'],
//                             'scheme_id' => $data['scheme_id'],
//                             'dept_id' => $data['dept_id'],
//                             'draft_id' => $data['draft_id'],
//                             'scheme_name' => $schemeName,
//                             'total_scheme' => $totalScheme,
//                         ]);
//                     }
//                 }
//                 return response()->json(['status' => '0' ,'success'=>'Survey Schedule create successfully.']);
//             }catch(Exception $e){
//                 return response()->json(['status' => '1' ,'error'=> $e->getMessage()]);
//             }
           

//    }
   public function labelItem(Request $request){
    if($request->year){

        //split Year
        $startedYear = explode('-',$request->year);
      
        $startDate = $startedYear[0] . '-04-01';
         // Set the end date to April 1st of the next year
        $endDate = ($startedYear[0] + 1). '-03-31';


        $schemes = Proposal::where('status_id',23)->whereBetween('created_at', [$startDate, $endDate])->get();
            
           
        $carry_scheme = Proposal::where('status_id',23)->whereNotBetween('created_at', [$startDate, $endDate])->get();

        $new_scheme_counts = [
            'requisition' => 0, //1
            'study_design_date' => 0, //2
            'polot_study_date' => 0, //3
            'field_survey_startdate' => 0, //4
            'data_entry_level_start' => 0, //5
            'report_startdate' => 0, //6
            'report_sent_hod_date' => 0, //7
            'dept_eval_committee_datetime' => 0, //8
            'eval_cor_date' => 0, //9
            'final_report' => 0, //10
            'dropped' => 0 //11
        ];
        $carry_forward_scheme_counts = [
                'requisition' => 0, //1
                'study_design_date' => 0, //2
                'polot_study_date' => 0, //3
                'field_survey_startdate' => 0, //4
                'data_entry_level_start' => 0, //5
                'report_startdate' => 0, //6
                'report_sent_hod_date' => 0, //7
                'dept_eval_committee_datetime' => 0, //8
                'eval_cor_date' => 0, //9
                'final_report' => 0, //10
                'dropped' => 0 //11
        ];

        foreach ($carry_scheme as $key => $carry_scheme_items) {
                    $carry_forward_scheme_counts['requisition'] = Stage::whereNotNull('requisition')->whereNotBetween('requisition', [$startDate, $endDate])
                    ->count(); 

                    $carry_forward_scheme_counts['study_design_date'] = Stage::whereNotNull('study_design_date')->whereNotBetween('study_design_date', [$startDate, $endDate])
                                    ->count(); 

                    $carry_forward_scheme_counts['polot_study_date'] = Stage::whereNotNull('polot_study_date')->whereNotBetween('polot_study_date', [$startDate, $endDate])
                                ->count(); 

                    $carry_forward_scheme_counts['field_survey_startdate'] = Stage::whereNotNull('field_survey_startdate')->whereNotBetween('field_survey_startdate', [$startDate, $endDate])
                                        ->count(); 

                    $carry_forward_scheme_counts['data_entry_level_start'] = Stage::whereNotNull('data_entry_level_start')->whereNotBetween('data_entry_level_start', [$startDate, $endDate])
                                        ->count();

                    $carry_forward_scheme_counts['report_startdate'] = Stage::whereNotNull('report_startdate')->whereNotBetween('report_startdate', [$startDate, $endDate])
                                    ->count(); 

                    $carry_forward_scheme_counts['report_sent_hod_date'] = Stage::whereNotNull('report_sent_hod_date')->whereNotBetween('report_sent_hod_date', [$startDate, $endDate])
                                        ->count();

                    $carry_forward_scheme_counts['dept_eval_committee_datetime'] = Stage::whereNotNull('dept_eval_committee_datetime')->whereNotBetween('dept_eval_committee_datetime', [$startDate, $endDate])
                                                ->count();


                    $carry_forward_scheme_counts['eval_cor_date'] = Stage::whereNotNull('eval_cor_date')->whereNotBetween('eval_cor_date', [$startDate, $endDate])
                                ->count();

                    $carry_forward_scheme_counts['final_report'] = Stage::whereNotNull('final_report')->whereNotBetween('final_report', [$startDate, $endDate])
                                                            ->count();

                    $carry_forward_scheme_counts['dropped'] = Stage::whereNotNull('dropped')->whereNotBetween('dropped', [$startDate, $endDate])
                                                                ->count(); 
        }
       
        foreach ($schemes as $scheme) {
            
            $new_scheme_counts['requisition'] = Stage::whereBetween('requisition', [$startDate, $endDate])->whereNotNull('requisition')
            ->count(); 

                $new_scheme_counts['study_design_date'] = Stage::whereBetween('study_design_date', [$startDate, $endDate])->whereNotNull('study_design_date')
                            ->count(); 

                $new_scheme_counts['polot_study_date'] = Stage::whereBetween('polot_study_date', [$startDate, $endDate])->whereNotNull('polot_study_date')
                            ->count(); 

                $new_scheme_counts['field_survey_startdate']  = Stage::whereBetween('field_survey_startdate', [$startDate, $endDate])->whereNotNull('field_survey_startdate')
                            ->count(); 

                $new_scheme_counts['data_entry_level_start'] = Stage::whereBetween('data_entry_level_start', [$startDate, $endDate])->whereNotNull('data_entry_level_start')
                                ->count(); 

                $new_scheme_counts['report_startdate'] = Stage::whereBetween('report_startdate', [$startDate, $endDate])->whereNotNull('report_startdate')
                            ->count(); 

                $new_scheme_counts['report_sent_hod_date'] = Stage::whereNotNull('report_sent_hod_date')->whereBetween('report_sent_hod_date', [$startDate, $endDate])
                            ->count();

                $new_scheme_counts['dept_eval_committee_datetime'] = Stage::whereNotNull('dept_eval_committee_datetime')->whereBetween('dept_eval_committee_datetime', [$startDate, $endDate])
                            ->count();

                $new_scheme_counts['eval_cor_date'] = Stage::whereNotNull('eval_cor_date')->whereBetween('eval_cor_date', [$startDate, $endDate])
                            ->count();

                $new_scheme_counts['final_report'] = Stage::whereNotNull('final_report')->whereBetween('final_report', [$startDate, $endDate])
                            ->count();

                $new_scheme_counts['dropped'] = Stage::whereBetween('dropped', [$startDate, $endDate])->whereNotNull('dropped')
                    ->count(); 
                
        }

        return response()->json([$new_scheme_counts,$carry_forward_scheme_counts]);

          
    }else{
        return false;
    }
     
   }
   public function schemeCount(){
    $count = Proposal::where('status_id',23)->count(); 
    return response()->json(['count' => $count]);
   }
   
   public function stageCount(){

            $currentYear = date('Y');
         
            if(date('m') > 3){
                $finyear = date('Y') ;
            }else{
                $finyear = date('Y') - 1;
            }
        
            $startDate = $finyear . '-04-01';
            $endDate = ($finyear + 1). '-03-31';

            //Query is select scheme_name,scheme_id,created_at from "itransaction"."proposals" where "status_id" = 23 and "created_at" between '2024-03-31' and '2025-04-01'
            $schemes = Proposal::where('status_id',23)->whereBetween('created_at', [$startDate, $endDate])->get();
            
           
            //Query is select scheme_name,scheme_id,created_at from "itransaction"."proposals" where "status_id" = 23 and "created_at" not between '2024-03-31' and '2025-04-01'
            $carry_scheme = Proposal::where('status_id',23)->whereNotBetween('created_at', [$startDate, $endDate])->get();
          
            $new_scheme_counts = [
                'requisition' => 0, //1
                'study_design_date' => 0, //2
                'polot_study_date' => 0, //3
                'field_survey_startdate' => 0, //4
                'data_entry_level_start' => 0, //5
                'report_startdate' => 0, //6
                'report_sent_hod_date' => 0, //7
                'dept_eval_committee_datetime' => 0, //8
                'eval_cor_date' => 0, //9
                'final_report' => 0, //10
                'dropped' => 0 //11
            ];
            $carry_forward_scheme_counts = [
                'requisition' => 0, //1
                'study_design_date' => 0, //2
                'polot_study_date' => 0, //3
                'field_survey_startdate' => 0, //4
                'data_entry_level_start' => 0, //5
                'report_startdate' => 0, //6
                'report_sent_hod_date' => 0, //7
                'dept_eval_committee_datetime' => 0, //8
                'eval_cor_date' => 0, //9
                'final_report' => 0, //10
                'dropped' => 0 //11
            ];

            foreach ($carry_scheme as $key => $carry_scheme_items) {
                $carry_forward_scheme_counts['requisition'] = Stage::whereNotNull('requisition')->whereNotBetween('requisition', [$startDate, $endDate])
                                                                ->count(); 

                $carry_forward_scheme_counts['study_design_date'] = Stage::whereNotNull('study_design_date')->whereNotBetween('study_design_date', [$startDate, $endDate])
                                                                    ->count(); 

                $carry_forward_scheme_counts['polot_study_date'] = Stage::whereNotNull('polot_study_date')->whereNotBetween('polot_study_date', [$startDate, $endDate])
                                                                ->count(); 
                
                $carry_forward_scheme_counts['field_survey_startdate'] = Stage::whereNotNull('field_survey_startdate')->whereNotBetween('field_survey_startdate', [$startDate, $endDate])
                                                                         ->count(); 

                $carry_forward_scheme_counts['data_entry_level_start'] = Stage::whereNotNull('data_entry_level_start')->whereNotBetween('data_entry_level_start', [$startDate, $endDate])
                                                                        ->count();

                $carry_forward_scheme_counts['report_startdate'] = Stage::whereNotNull('report_startdate')->whereNotBetween('report_startdate', [$startDate, $endDate])
                                                                    ->count(); 

                $carry_forward_scheme_counts['report_sent_hod_date'] = Stage::whereNotNull('report_sent_hod_date')->whereNotBetween('report_sent_hod_date', [$startDate, $endDate])
                                                                        ->count();

                $carry_forward_scheme_counts['dept_eval_committee_datetime'] = Stage::whereNotNull('dept_eval_committee_datetime')->whereNotBetween('dept_eval_committee_datetime', [$startDate, $endDate])
                                                                                ->count();
            

                $carry_forward_scheme_counts['eval_cor_date'] = Stage::whereNotNull('eval_cor_date')->whereNotBetween('eval_cor_date', [$startDate, $endDate])
                                                                ->count();

                $carry_forward_scheme_counts['final_report'] = Stage::whereNotNull('final_report')->whereNotBetween('final_report', [$startDate, $endDate])
                        ->count();

                $carry_forward_scheme_counts['dropped'] = Stage::whereNotNull('dropped')->whereNotBetween('dropped', [$startDate, $endDate])
                    ->count(); 
            }

            foreach ($schemes as $scheme) {
                    
                $new_scheme_counts['requisition'] = Stage::whereBetween('requisition', [$startDate, $endDate])->whereNotNull('requisition')
                                ->count(); 

                $new_scheme_counts['study_design_date'] = Stage::whereBetween('study_design_date', [$startDate, $endDate])->whereNotNull('study_design_date')
                                ->count(); 

                $new_scheme_counts['polot_study_date'] = Stage::whereBetween('polot_study_date', [$startDate, $endDate])->whereNotNull('polot_study_date')
                                ->count(); 

                $new_scheme_counts['field_survey_startdate']  = Stage::whereBetween('field_survey_startdate', [$startDate, $endDate])->whereNotNull('field_survey_startdate')
                                ->count(); 
                
                $new_scheme_counts['data_entry_level_start'] = Stage::whereBetween('data_entry_level_start', [$startDate, $endDate])->whereNotNull('data_entry_level_start')
                                    ->count(); 

                $new_scheme_counts['report_startdate'] = Stage::whereBetween('report_startdate', [$startDate, $endDate])->whereNotNull('report_startdate')
                                ->count(); 

                $new_scheme_counts['report_sent_hod_date'] = Stage::whereNotNull('report_sent_hod_date')->whereBetween('report_sent_hod_date', [$startDate, $endDate])
                                ->count();

                $new_scheme_counts['dept_eval_committee_datetime'] = Stage::whereNotNull('dept_eval_committee_datetime')->whereBetween('dept_eval_committee_datetime', [$startDate, $endDate])
                                ->count();

                $new_scheme_counts['eval_cor_date'] = Stage::whereNotNull('eval_cor_date')->whereBetween('eval_cor_date', [$startDate, $endDate])
                                ->count();

                $new_scheme_counts['final_report'] = Stage::whereNotNull('final_report')->whereBetween('final_report', [$startDate, $endDate])
                                ->count();

                $new_scheme_counts['dropped'] = Stage::whereBetween('dropped', [$startDate, $endDate])->whereNotNull('dropped')
                        ->count(); 
                    
            }
           

            return response()->json([$new_scheme_counts,$carry_forward_scheme_counts]);
   }
   
   public function donutCount($draft_id){
 
    $draft_id = base64_decode($draft_id);
  
    if(!is_null($draft_id) && $draft_id != "null"){
       
        $proposal_list = SchemeSend::where('draft_id',$draft_id)->whereIn('scheme_send.status_id', [25])
                                ->where('scheme_send.forward_btn_show', 1)
                                ->where('scheme_send.forward_id', 1)
                                ->orderByDesc('scheme_send.id')
                                ->distinct()
                                ->get();
                               
        $stages_item = Stage::where('draft_id', $draft_id)->first();
    
        if(!is_null($stages_item)){
            
            $get_count = [
                'requisition' => 0, //1
                'requisition_delay' => 0,
                'study_design_date' => 0, //2
                'study_design_date_delay' => 0,
                'study_design_receive_hod_date' => 0, //3
                'study_design_receive_hod_date_delay' => 0,
                'polot_study_date' => 0, //4
                'polot_study_date_delay' => 0,
                'field_survey_startdate' => 0, //5
                'field_survey_startdate_delay' => 0,
                'data_entry_level_start' => 0, //6
                'data_entry_level_start_delay' => 0,
                'report_startdate' => 0, //7
                'report_startdate_delay' => 0,
                'report_draft_hod_date' => 0, //8
                'report_draft_hod_date_delay' => 0,
                'dept_eval_committee_datetime' => 0, //9
                'dept_eval_committee_datetime_delay' => 0,
                'eval_cor_date' => 0, //10
                'eval_cor_date_delay' => 0,
                'final_report'=> 0, //11
                'final_report_delay' => 0,
                'dropped' => 0, //12
                'dropped_delay' => 0

            ];
            foreach ($proposal_list as $key => $proposal_data) {

                    //Requisition
                    $endDate = Carbon::parse($proposal_data->created_at)->addMonths(1);

                    $diffInDays = Stage::where('draft_id', $proposal_data->draft_id)
                                        ->whereNotNull('requisition')
                                        ->whereBetween('requisition', [$proposal_data->created_at, $endDate])
                                        ->get()
                                        ->sum(function ($stage) use ($endDate) {
                                            return Carbon::parse($stage->requisition)->diffInDays($endDate);
                                        });
                    $get_count['requisition'] += round($diffInDays);
                    
                    $delayCount = Stage::where('draft_id', $proposal_data->draft_id)
                                ->whereNotNull('requisition')
                                ->where('requisition', '>', $endDate)
                                ->get()
                                ->sum(function ($stage) use ($endDate) {
                                    $diff = Carbon::parse($stage->requisition)->diffInDays($endDate);
                                    return max(0, $diff); 
                                });
                    
                    $get_count['requisition_delay'] += round($delayCount);

                    //Study Design Date --> Preparation of study design and questioner
                    $StudyendDate = Carbon::parse($stages_item->requisition)->addMonths(1);
                    
                    $diffInDays1 = Stage::where('draft_id', $proposal_data->draft_id)
                        ->whereNotNull('study_design_date')
                        ->whereBetween('study_design_date', [$stages_item->study_design_date, $StudyendDate])
                        ->get()
                        ->sum(function ($stage) use ($StudyendDate) {
                            return Carbon::parse($stage->study_design_date)->diffInDays($StudyendDate);
                        });
                   
                    $get_count['study_design_date'] += round($diffInDays1);
                    
                    $delayCount1 = Stage::where('draft_id', $proposal_data->draft_id)
                        ->whereNotNull('study_design_date')
                        ->where('study_design_date', '>', $StudyendDate)
                        ->get()
                        ->sum(function ($stage) use ($StudyendDate) {
                            return max(0, Carbon::parse($stage->study_design_date)->diffInDays($StudyendDate));
                        });
                    
                    $get_count['study_design_date_delay'] += round($delayCount1);

                     //Approval from concern department -->Inputs on Study Design and Survey Forms received from Implementing Office (HOD)
                     $StudyrecendDate = Carbon::parse($stages_item->study_design_date)->addMonths(1);
                     
                     $diffInDays2 = Stage::where('draft_id', $proposal_data->draft_id)
                         ->whereNotNull('study_design_receive_hod_date')
                         ->whereBetween('study_design_receive_hod_date', [$stages_item->study_design_receive_hod_date, $StudyrecendDate])
                         ->get()
                         ->sum(function ($stage) use ($StudyrecendDate) {
                             return Carbon::parse($stage->study_design_receive_hod_date)->diffInDays($StudyrecendDate);
                         });
                     
                     $get_count['study_design_receive_hod_date'] += round($diffInDays2);
                     
                     $delayCount2 = Stage::where('draft_id', $proposal_data->draft_id)
                         ->whereNotNull('study_design_receive_hod_date')
                         ->where('study_design_receive_hod_date', '>', $StudyrecendDate)
                         ->get()
                         ->sum(function ($stage) use ($StudyrecendDate) {
                             return max(0, Carbon::parse($stage->study_design_receive_hod_date)->diffInDays($StudyrecendDate));
                         });
                     
                     $get_count['study_design_receive_hod_date_delay'] += round($delayCount2);

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
                    
                    $get_count['polot_study_date'] += round($diffInDays3);
                    
                    $delayCount3 = Stage::where('draft_id', $proposal_data->draft_id)
                            ->whereNotNull('polot_study_date')
                            ->where('polot_study_date', '>', $endDate4)
                            ->get()
                            ->sum(function ($stage) use ($endDate4) {
                                $diff = Carbon::parse($stage->polot_study_date)->diffInDays($endDate4);
                                return max(0, $diff); // Return 0 if the difference is negative
                            });
                    
                    $get_count['polot_study_date_delay'] += round($delayCount3);

                    //Filed Work --> Field Survey starts
                    $endDate3 = Carbon::parse($stages_item->polot_study_date)->addMonths(2);
                   
                    $diffInDays4 = Stage::where('draft_id', $proposal_data->draft_id)
                                ->whereNotNull('field_survey_startdate')
                               ->whereBetween('field_survey_startdate', [$stages_item->field_survey_startdate, $endDate3])
                                ->get()
                                ->sum(function ($stage) use ($endDate3) {
                                    $diff = Carbon::parse($stage->field_survey_startdate)->diffInDays($endDate3);
                                    return max(0, $diff); // Return 0 if the difference is negative
                                });
                
                    $get_count['field_survey_startdate'] += round($diffInDays4);
                    
                    $delayCount4 = Stage::where('draft_id', $proposal_data->draft_id)
                                        ->whereNotNull('field_survey_startdate')
                                        ->where('field_survey_startdate', '>', $endDate3)
                                        ->get()
                                        ->sum(function ($stage) use ($endDate3) {
                                            $diff = Carbon::parse($stage->field_survey_startdate)->diffInDays($endDate3);
                                            return max(0, $diff); // Return 0 if the difference is negative
                                        });
                    
                    $get_count['field_survey_startdate_delay'] += round($delayCount4);
                    
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
                
                    $get_count['data_entry_level_start'] += round($diffInDays5);
                    
                    $delayCount5 = Stage::where('draft_id', $proposal_data->draft_id)
                                        ->whereNotNull('data_entry_level_start')
                                        ->where('data_entry_level_start', '>', $endDate5)
                                        ->get()
                                        ->sum(function ($stage) use ($endDate5) {
                                            $diff = Carbon::parse($stage->data_entry_level_start)->diffInDays($endDate5);
                                            return max(0, $diff); // Return 0 if the difference is negative
                                        });
                    
                    $get_count['data_entry_level_start_delay'] += round($delayCount5);

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
                
                    $get_count['report_startdate'] += round($diffInDays6);
                    
                    $delayCount6 = Stage::where('draft_id', $proposal_data->draft_id)
                                        ->whereNotNull('report_startdate')
                                        ->where('report_startdate', '>', $endDate6)
                                        ->get()
                                        ->sum(function ($stage) use ($endDate6) {
                                            $diff = Carbon::parse($stage->report_startdate)->diffInDays($endDate6);
                                            return max(0, $diff); // Return 0 if the difference is negative
                                        });
                    
                    $get_count['report_startdate_delay'] += round($delayCount6);

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
                
                    $get_count['report_draft_hod_date'] += round($diffInDays7);
                    
                    $delayCount7 = Stage::where('draft_id', $proposal_data->draft_id)
                                        ->whereNotNull('report_draft_hod_date')
                                        ->where('report_draft_hod_date', '>', $endDate7)
                                        ->get()
                                        ->sum(function ($stage) use ($endDate7) {
                                            $diff = Carbon::parse($stage->report_draft_hod_date)->diffInDays($endDate7);
                                            return max(0, $diff); // Return 0 if the difference is negative
                                        });
                    
                    $get_count['report_draft_hod_date_delay'] += round($delayCount7);

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

                    $get_count['dept_eval_committee_datetime'] += round($diffInDays8);
                        
                    $delayCount8 = Stage::where('draft_id', $proposal_data->draft_id)
                                        ->whereNotNull('dept_eval_committee_datetime')
                                        ->where('dept_eval_committee_datetime', '>', $endDate8)
                                        ->get()
                                        ->sum(function ($stage) use ($endDate8) {
                                            $diff = Carbon::parse($stage->dept_eval_committee_datetime)->diffInDays($endDate8);
                                            return max(0, $diff); // Return 0 if the difference is negative
                                        });
                    
                    $get_count['dept_eval_committee_datetime_delay'] += round($delayCount8);
                   
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
     
                     $get_count['eval_cor_date'] += round($diffInDays9);
                    
                    $delayCount9 = Stage::where('draft_id', $proposal_data->draft_id)
                                        ->whereNotNull('eval_cor_date')
                                        ->where('eval_cor_date', '>', $endDate9)
                                        ->get()
                                        ->sum(function ($stage) use ($endDate9) {
                                            $diff = Carbon::parse($stage->eval_cor_date)->diffInDays($endDate9);
                                            return max(0, $diff); 
                                        });
                    
                    $get_count['eval_cor_date_delay'] += round($delayCount9);

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
    
                    $get_count['final_report'] += round($diffInDays10);
                    
                    $delayCount10 = Stage::where('draft_id', $proposal_data->draft_id)
                                        ->whereNotNull('final_report')
                                        ->where('final_report', '>', $endDate10)
                                        ->get()
                                        ->sum(function ($stage) use ($endDate10) {
                                            $diff = Carbon::parse($stage->final_report)->diffInDays($endDate10);
                                            return max(0, $diff); 
                                        });
                    
                    $get_count['final_report_delay'] += round($delayCount10);

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

                    $get_count['dropped'] += round($diffInDays11);
                    
                    $delayCount11 = Stage::where('draft_id', $proposal_data->draft_id)
                                        ->whereNotNull('dropped')
                                        ->where('dropped', '>', $endDate11)
                                        ->get()
                                        ->sum(function ($stage) use ($endDate11) {
                                            $diff = Carbon::parse($stage->dropped)->diffInDays($endDate11);
                                            return max(0, $diff); 
                                        });
                    
                    $get_count['dropped_delay'] += round($delayCount11);
            
            }
            return response()->json($get_count);
        }else{
            return response()->json(['error' => "This scheme doesn't provide valid information on stages"]);
        }
    }else{
        return response()->json(['error' => "This department doesn't have any schemes"]);
    }
        
   }

   public function detailReport(){
        $proposal_list = Proposal::where('status_id',23)->get();
        return view('report.bar-report',compact('proposal_list'));
   }

   public function summaryReport($draft_id = null){
    if ($draft_id) {
        try {
            $decrypted_draft_id_str = Crypt::decryptString($draft_id);

            $draft_ids = explode(',', $decrypted_draft_id_str);

            return Excel::download(new SummaryReport($draft_ids), 'evaluation-summary-report.xlsx');

        } catch (DecryptException $e) {
            return response()->json(['error' => 'Invalid encrypted data'], 400);
        }
    }else{
        dd('select the scheme');
    }
    
 
   }
}
