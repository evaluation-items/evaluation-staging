<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Scheme;
use App\Models\Department;
use App\Models\Convener;
use App\Models\Stage;
use Illuminate\Support\Facades\Auth;
use App\Models\Implementation;
use App\Models\Adviser;
use App\Models\Nodal;
use App\Models\User;
use App\Models\Branch;
use Validator;
use Smalot\PdfParser\Parser;
use Illuminate\Support\Facades\Storage;
use URL;
use Response;
use DB;
use Session;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use App\Models\GrFilesList;
use App\Models\NotificationFileList;
use App\Models\BrochureFileList;
use App\Models\PamphletFileList;
use App\Models\CenterStateFiles;
use App\Models\FinancialProgress;
use App\Models\Proposal;
use App\Models\SchemeSend;
use App\Models\Status;
use App\Couchdb\Couchdb;
use App\Models\Aftermeeting;
use App\Models\Districts;
use App\Models\Attachment;
use App\Models\Activitylog;
use App\Models\Taluka;
use App\Models\Sdggoals;
use App\Models\Meetinglog;
use Carbon\Carbon;
use App\Models\CommunicationTopics;
use App\Models\Communication;
use App\Models\Eval_activity_status;
use App\Models\Eval_activity_log;
use App\Models\State;
use Illuminate\Support\Facades\Crypt;
use Mpdf\Mpdf;
use App\Services\PdfSecurityService;
use App\Models\NodalDesignation;


class SchemeController extends Controller {
    public $envirment;
    public function __construct(){
        $this->middleware('auth');
       
        $this->envirment = environmentCheck();
    }

    public function get_count($thing) {
        if($thing == 'proposals') {
            $dept_proposal_count = SchemeSend::where('status_id','21')->where('viewed','0')->orWhere('status_id','24')->count("*");
            $last_proposal_time = SchemeSend::where('status_id','21')->where('viewed','0')->orWhere('status_id','24')->take(1)->orderBy('id','desc')->value('created_at');
            $last_prop = date('d M, Y H:i a',strtotime($last_proposal_time));
            Session::put('last_proposal_time',$last_prop);
            Session::put('proposals',$dept_proposal_count);
            return $dept_proposal_count;
        }
    }

    public function getdistricts(Request $request) {
        $distname = $request->input('district');
        
        if($distname == 1){ //State
            $state = State::active()->select('id','name')->orderBy('name','asc')->get();
            $states = array('state'=>$state);
            return response()->json($states);
        } else if($distname == 2) { //Districts
            $district = Districts::select('dcode','name_e')->orderBy('name_e','asc')->get();
            $districts = array('districts'=>$district);
            return response()->json($districts);
        } else if($distname == 3) { //Taluka
            $district = Districts::orderBy('name_e','asc')->pluck('dcode','name_e');
            $districts = array('district_list'=>$district);
            return response()->json($districts);

        }else if($distname == 5) { //Tribal Districts
            $district = Districts::select('dcode','name_e')->where('is_tribal','1')->orderBy('name_e','asc')->get();
            $districts = array('districts'=>$district);
            return response()->json($districts);
        } else if($distname == 9) { //Costal Districts
            $district = Districts::select('dcode','name_e')->where('is_coastal_area','1')->orderBy('name_e','asc')->get();
            $districts = array('districts'=>$district);
            return response()->json($districts);
        }  else if($distname == 8) {
            $district = Districts::select('dcode','name_e')->where('is_border_area','1')->orWhere('is_international_border','1')->orderBy('name_e','asc')->get();
            $districts = array('districts'=>$district);
            return response()->json($districts);
        }
    }
    public function gettaluka(Request $request){
        if($request->taluka_dcode != null){
            $taluka_list = Taluka::where('dcode',$request->taluka_dcode)->select('tcode','tname_e')->orderBy('tname_e','asc')->get();
            $taluka = array('talukas'=>$taluka_list);
            return response()->json($taluka);
        }else{
            return response()->json(['error','Something went wrong']);
        }
       
    }

    public function editdistricts(Request $request) {
        
        $distname = $request->input('district');
        $scheme_id = $request->input('scheme_id');
       
        if($distname == 1) { //State
            $entered_districts = Scheme::where('scheme_id', $scheme_id)->value('states');
            $dist_arr = json_decode($entered_districts); 
            $state = State::active()->select('id', 'name')->orderBy('name', 'asc')->get();
            $states = array('states' => $state, 'entered_item' => $dist_arr);
            return response()->json($states);
           
        }else if($distname == 2) { //District
            $entered_districts = Scheme::where('scheme_id', $scheme_id)->value('districts');
            $dist_arr = json_decode($entered_districts); 
            $district = Districts::select('dcode', 'name_e')->orderBy('name_e', 'asc')->get();
            $districts = array('districts' => $district, 'entered_values' => $dist_arr);
            return response()->json($districts);
            
        }else if($distname == 3) { //Talukas
            $entered_districts = Scheme::where('scheme_id', $scheme_id)->pluck('talukas','taluka_id');
            $talukaId = $entered_districts->keys()->all();
            if($talukaId != '' || $talukaId != null){
                $taluka = Taluka::where('dcode',$talukaId[0])->select('tcode', 'tname_e')->orderBy('tname_e', 'asc')->get();
                $taluka_id = $talukaId[0];
            }else{
                $taluka = Taluka::select('tcode', 'tname_e')->orderBy('tname_e', 'asc')->get();
                $taluka_id = 0;
            }
            $dist_arr = json_decode($entered_districts->first()); 
            $district_list = Districts::select('dcode','name_e')->orderBy('name_e','asc')->get();
            $talukas = array('talukas' => $taluka, 'entered_values' => $dist_arr,'taluka_id'=>$taluka_id,'district_list' =>$district_list);
           // dd('taluka');

            return response()->json($talukas);
            
        } else if($distname == 9) { //Coastal Districts
            $entered_districts = Scheme::where('scheme_id',$scheme_id)->value('districts');
            $dist_arr = json_decode($entered_districts);
            $district = Districts::select('dcode','name_e')->where('is_coastal_area','1')->orderBy('name_e','asc')->get();
            $districts = array('districts'=>$district,'entered_values'=>$dist_arr);
            return response()->json($districts);
        }
        // else if($distname == 5) {
        //     $entered_districts = Scheme::where('scheme_id',$scheme_id)->value('districts');
        //     $dist_arr = json_decode($entered_districts);
        //     $district = Districts::select('dcode','name_e')->where('is_tribal','1')->orderBy('name_e','asc')->get();
        //     $districts = array('districts'=>$district,'entered_values'=>$dist_arr);
        //     return response()->json($districts);
        // } else if($distname == 6) {
        //     $entered_districts = Scheme::where('scheme_id',$scheme_id)->value('districts');
        //     $dist_arr = json_decode($entered_districts);
        //     $district = Districts::select('dcode','name_e')->where('is_coastal_area','1')->orderBy('name_e','asc')->get();
        //     $districts = array('districts'=>$district,'entered_values'=>$dist_arr);
        //     return response()->json($districts);
        // } else if($distname == 7) {
        //     $entered_districts = Scheme::where('scheme_id',$scheme_id)->value('talukas');
        //     $dist_arr = json_decode($entered_districts);
        //     $district = Taluka::select('tcode','tname_e')->where('is_developing','1')->orderBy('tname_e','asc')->get();
        //     $districts = array('talukas'=>$district,'entered_values'=>$dist_arr);
        //     return response()->json($districts);
        // } else if($distname == 8) {
        //     $entered_districts = Scheme::where('scheme_id',$scheme_id)->value('districts');
        //     $dist_arr = json_decode($entered_districts);
        //     $district = Districts::select('dcode','name_e')->where('is_border_area','1')->orWhere('is_international_border','1')->orderBy('name_e','asc')->get();
        //     $districts = array('districts'=>$district,'entered_values'=>$dist_arr);
        //     return response()->json($districts);
        // } 
    }

    public function index() {
       
        $dept_id = Auth::user()->dept_id;
        $schemelist = Scheme::where('dept_id',$dept_id)->orderBy('schemes.scheme_id','desc')->get();
        $dept_name = Department::where('dept_id',$dept_id)->get();
        $forwarded = SchemeSend::select('*')
                ->whereNOTIn('itransaction.scheme_send.draft_id',function($query){
                    $query->select('draft_id')->from('itransaction.schemes');
                })
                ->get();

        $user_id = auth()->user()->role;

        $user_login = DB::table('users')
            ->leftjoin('public.user_user_role_deptid','user_id','=','users.id')
            ->where('users.id','=',$user_id)
            ->select('users.name','users.id')
            ->get();
        return view('schemes.index',compact('schemelist','user_login','forwarded','dept_name'));
    }

    public function create(Request $request) {
       
        $financial_years = financialyears();
        $goals = Sdggoals::where('status','1')->orderBy('goal_id','asc')->get();
        $user_id = auth()->user();
        $dept = DB::table('users')
                ->leftjoin('public.user_user_role_deptid','user_id','=','users.id')
                ->leftjoin('imaster.departments','imaster.departments.dept_id','=','user_user_role_deptid.dept_id')
                ->where('users.id','=',$user_id->id)
                ->select('departments.dept_name','departments.dept_id')
                ->get();
           
        $department_user       = Department::where('dept_id',$user_id->dept_id)->get();
        $departments           = Department::all();
        $implementations       = Implementation::where('deptid','11')->get();
        $beneficiariesGeoLocal = beneficiariesGeoLocal();
        $year = Commencementyear();
        $units = units();
        $act['userid'] = Auth::user()->id;
        $act['ip'] = $request->ip();
        $act['activity'] = 'Scheme Created';
        $act['officecode'] = Auth::user()->dept_id;
        $act['pagereferred'] = $request->url();
        Activitylog::insert($act);
        $deptId = $user_id->dept_id;
        $designations = NodalDesignation::where(function ($q) use ($deptId) {
            $q->where('is_default', true)
              ->orWhere('dept_id', $deptId);
        })
        ->where('status', true)
        ->orderByDesc('is_default')
        ->pluck('designation_name');
        return view('schemes.create_sample',compact('units','year','departments','department_user','implementations','beneficiariesGeoLocal','dept','financial_years','goals','designations'));
    }

    public function departmentlist(Request $request) {
        $departments = Department::select('dept_id','dept_name')->orderBy('dept_name')->get();
        return response()->json($departments);
    }
 
    public function add_scheme(Request $request) {
        $slide = $request->input('slide'); 
        if($slide == 'first' && Session::has('scheme_id')) {
                // 🔹 Existing Scheme: Update both scheme and proposal
                $scheme_id = Session::get('scheme_id');
                $draft_id = Session::get('draft_id');
                $dept_id = $request->input('dept_id');
                $convener_name = $request->input('convener_name');
                $scheme_name = $request->input('scheme_name');
                $reference_year = $request->input('reference_year');
                $reference_year2 = $request->input('reference_year2');
                $convener_designation = $request->convener_designation;
                $convener_phone = $request->convener_phone;
                $financial_adviser_name = $request->financial_adviser_name;
               // $financial_adviser_designation = $request->financial_adviser_designation;
                $financial_adviser_phone = $request->financial_adviser_phone;

                // 🧩 Evaluation Fields
                $is_evaluation = $request->input('is_evaluation');
                $eval_by_whom = $request->input('eval_by_whom');
                $eval_when = $request->input('eval_when');
                $eval_geographical_coverage_beneficiaries = $request->input('eval_geographical_coverage_beneficiaries');
                $eval_number_of_beneficiaries = $request->input('eval_number_of_beneficiaries');
                $eval_major_recommendation = $request->input('eval_major_recommendation');
                $eval_report_file_name = '';

                if($request->hasFile('eval_upload_report')) {
                    $request->validate([
                        'eval_upload_report' => 'file|mimes:pdf,doc,docx,xls,xlsx',
                    ]);
                    $eval_upload_report = $request->file('eval_upload_report');
                    PdfSecurityService::check($eval_upload_report);
                    $rev = Attachment::where('scheme_id',$scheme_id)->value('couch_rev_id');
                    $extended = new Couchdb();
                    $extended->InitConnection();
                    $status = $extended->isRunning();
                    $doc_id = "scheme_".$scheme_id;
                    $docid = 'eval_report_'.mt_rand('0000000000','9999999999');
                    $path['id'] = $docid;
                    $path['tmp_name'] = $eval_upload_report->getRealPath();
                    $path['extension']  = $eval_upload_report->getClientOriginalExtension();
                    $path['name'] = $doc_id.'_'.$path['id'].'.'.$path['extension'];
                    if(is_null($rev)){
                        $dummy_data = array(
                            'scheme_id' => $doc_id
                        );
                        $out = $extended->createDocument($dummy_data,$this->envirment['database'],$doc_id);
                        $array = json_decode($out, true);
                        $id = $array['id'] ?? null;
                        $rev = $array['rev'] ?? null;
                        $data['scheme_id'] = $scheme_id;
                        $data['couch_doc_id'] = $id;
                        $data['couch_rev_id'] = $rev;
                        $attachment = Attachment::create($data);
                    }
                    $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                    $array = json_decode($out, true);
                    $rev = $array['rev'] ?? null;
                    if(isset($rev)) {
                        Attachment::where('scheme_id',$scheme_id)->update(['couch_rev_id'=>$rev]);
                    }                    
                    $eval_report_file_name = $path['name'];
                }

                $arr = [
                    'dept_id' => $dept_id,
                    'convener_name' => $convener_name,
                    'convener_designation' => $convener_designation,
                    'convener_phone' => $convener_phone,
                    'scheme_name' => $scheme_name,
                    'reference_year' => $reference_year,
                    'reference_year2' => $reference_year2,
                    'financial_adviser_name' => $financial_adviser_name,
                   // 'financial_adviser_designation' => $financial_adviser_designation,
                    'financial_adviser_phone' => $financial_adviser_phone,
                    'is_evaluation' => $is_evaluation,
                    'eval_scheme_bywhom' => $eval_by_whom,
                    'eval_scheme_when' => $eval_when,
                    'eval_scheme_geo_cov_bene' => $eval_geographical_coverage_beneficiaries,
                    'eval_scheme_no_of_bene' => $eval_number_of_beneficiaries,
                    'eval_scheme_major_recommendation' => $eval_major_recommendation,
                    'eval_scheme_report' => $eval_report_file_name
                ];

                Scheme::where('scheme_id',$scheme_id)->update($arr);
                Proposal::where('draft_id',$draft_id)->update($arr);

                return Session::get('scheme_id');

        }else if($slide == 'first' && !Session::has('scheme_id')) {
           
                // 🔹 New Scheme: Insert new record in both tables
                    $dept_id = $request->input('dept_id');
                    $is_evaluation = $request->input('is_evaluation');
                    $eval_by_whom = $request->input('eval_by_whom');
                    $eval_when = $request->input('eval_when');
                    $eval_geographical_coverage_beneficiaries = $request->input('eval_geographical_coverage_beneficiaries');
                    $eval_number_of_beneficiaries = $request->input('eval_number_of_beneficiaries');
                    $eval_major_recommendation = $request->input('eval_major_recommendation');

                    $convener_name = $request->input('convener_name');
                    $convener_designation = $request->input('convener_designation');
                    $convener_phone = $request->input('convener_phone');
                    $convener_mobile = $request->input('convener_mobile');
                    $convener_email = $request->input('convener_email');
                    $scheme_name = $request->input('scheme_name');
                    $scheme_short_name = $request->input('scheme_short_name');
                    $reference_year = $request->input('reference_year');
                    $reference_year2 = $request->input('reference_year2');
                    $financial_adviser_name = $request->input('financial_adviser_name');
                  //  $financial_adviser_designation = $request->input('financial_adviser_designation');
                    $financial_adviser_phone = $request->input('financial_adviser_phone');
                    $financial_adviser_email = $request->input('financial_adviser_email');
                    $financial_adviser_mobile = $request->input('financial_adviser_mobile');

                    // Step 2: insert scheme record first (without file)
                    $scheme =  DB::table('itransaction.schemes')->insert([
                        'dept_id' => $dept_id,
                        'convener_name' => $convener_name,
                        'convener_designation' => $convener_designation,
                        'convener_phone' => $convener_phone,
                        'convener_mobile' => $convener_mobile,
                        'convener_email' => $convener_email,
                        'scheme_name' => $scheme_name,
                        'scheme_short_name' => $scheme_short_name,
                        'reference_year' => $reference_year,
                        'reference_year2' => $reference_year2,
                        'financial_adviser_name' => $financial_adviser_name,
                      //  'financial_adviser_designation' => $financial_adviser_designation,
                        'financial_adviser_phone' => $financial_adviser_phone,
                        'financial_adviser_email' => $financial_adviser_email,
                        'financial_adviser_mobile' => $financial_adviser_mobile,
                        'is_evaluation' => $is_evaluation,
                        'eval_scheme_bywhom' => $eval_by_whom,
                        'eval_scheme_when' => $eval_when,
                        'eval_scheme_geo_cov_bene' => $eval_geographical_coverage_beneficiaries,
                        'eval_scheme_no_of_bene' => $eval_number_of_beneficiaries,
                        'eval_scheme_major_recommendation' => $eval_major_recommendation,
                    ]);

                    $scheme_id = Scheme::orderBy('scheme_id','desc')->take(1)->value('scheme_id');
                    Session::put('scheme_id', $scheme_id); // ✅ store in session

                    // Step 3: handle evaluation file upload (after scheme_id generated)
                    $eval_report_file_name = '';
                    if ($request->hasFile('eval_upload_report')) {
                          $request->validate([
                            'eval_upload_report' => 'file|mimes:pdf,doc,docx,xls,xlsx',
                        ]);
                        $eval_upload_report = $request->file('eval_upload_report');
                        PdfSecurityService::check($eval_upload_report);
                       // $eval_upload_report = $request->file('eval_upload_report');
                        $rev = Attachment::where('scheme_id', $scheme_id)->value('couch_rev_id');
                        $extended = new Couchdb();
                        $extended->InitConnection();
                        $status = $extended->isRunning();
                        $doc_id = "scheme_" . $scheme_id;
                        $docid = 'eval_report_' . mt_rand(1000000000, 9999999999);

                        $path['id'] = $docid;
                        $path['tmp_name'] = $eval_upload_report->getRealPath();
                        $path['extension'] = $eval_upload_report->getClientOriginalExtension();
                        $path['name'] = $doc_id . '_' . $path['id'] . '.' . $path['extension'];
                        if(is_null($rev)){
                            $dummy_data = array(
                                'scheme_id' => $doc_id
                            );
                            $out = $extended->createDocument($dummy_data,$this->envirment['database'],$doc_id);
                            $array = json_decode($out, true);
                            $id = $array['id'] ?? null ?? null;
                            $rev = $array['rev'] ?? null ?? null;
                            $data['scheme_id'] = $scheme_id;
                            $data['couch_doc_id'] = $id;
                            $data['couch_rev_id'] = $rev;
                            $attachment = Attachment::create($data);
                        }
                        $out = $extended->createAttachmentDocument($this->envirment['database'], $doc_id, $rev, $path);
                        $array = json_decode($out, true);
                        $rev = $array['rev'] ?? null ?? null;

                        if ($rev) {
                            Attachment::where('scheme_id', $scheme_id)->update(['couch_rev_id' => $rev]);
                        }

                        $eval_report_file_name = $path['name'];

                        // update the scheme record after upload
                       DB::table('itransaction.schemes')->where('scheme_id', $scheme_id)->update(['eval_scheme_report' => $eval_report_file_name]);
                    }

                    // Step 4: insert proposal linked with scheme_id
                    $proposal = DB::table('itransaction.proposals')->insert([
                        'scheme_id' => $scheme_id,
                        'dept_id' => $dept_id,
                        'convener_name' => $convener_name,
                        'convener_designation' => $convener_designation,
                        'convener_phone' => $convener_phone,
                        'convener_mobile' => $convener_mobile,
                        'convener_email' => $convener_email,
                        'scheme_name' => $scheme_name,
                        'scheme_short_name' => $scheme_short_name,
                        'reference_year' => $reference_year,
                        'reference_year2' => $reference_year2,
                        'financial_adviser_name' => $financial_adviser_name,
                       // 'financial_adviser_designation' => $financial_adviser_designation,
                        'financial_adviser_phone' => $financial_adviser_phone,
                        'financial_adviser_email' => $financial_adviser_email,
                        'financial_adviser_mobile' => $financial_adviser_mobile,
                        'is_evaluation' => $is_evaluation,
                        'eval_scheme_bywhom' => $eval_by_whom,
                        'eval_scheme_when' => $eval_when,
                        'eval_scheme_geo_cov_bene' => $eval_geographical_coverage_beneficiaries,
                        'eval_scheme_no_of_bene' => $eval_number_of_beneficiaries,
                        'eval_scheme_major_recommendation' => $eval_major_recommendation,
                        'eval_scheme_report' => $eval_report_file_name,
                    ]);

                    $draft_id = Proposal::orderBy('draft_id','desc')->take(1)->value('draft_id');

                    Session::put('draft_id', $draft_id); // ✅ store in session

                    // Step 5: add activity log
                    Activitylog::create([
                        'userid' => Auth::user()->id,
                        'ip' => $request->ip(),
                        'activity' => 'Scheme Store By Concern Department',
                        'officecode' => $dept_id,
                        'pagereferred' => $request->url(),
                    ]);


                return response()->json('added successfully');

        }else if($slide == 'third') {
           // dd('third slide data:',$request->all());
            $major_objectives = $request->input('major_objective');
              $scheme_id = Session::get('scheme_id');
               $draft_id = Session::get('draft_id');
            $major_objective_file_name = '';
            if($request->has('major_objective_file')) {
                $request->validate([
                    'major_objective_file' => 'file|mimes:pdf,doc,docx,xls,xlsx',
                ]);
                $major_objective_file = $request->file('major_objective_file');
                PdfSecurityService::check($major_objective_file);
                $rev = Attachment::where('scheme_id',$scheme_id)->value('couch_rev_id');
                $extended = new Couchdb();
                $extended->InitConnection();
                $status = $extended->isRunning();
                $doc_id = "scheme_".$scheme_id;
                $docid = 'major_objective_file'; //.time().'_'.mt_rand('0000000000','9999999999');
                $path['id'] = $docid;
                $path['tmp_name'] = $major_objective_file->getRealPath();
                $path['extension']  = $major_objective_file->getClientOriginalExtension();
                $path['name'] = $doc_id.'_'.$path['id'].'.'.$path['extension'];
                 if(is_null($rev)){
                    $dummy_data = array(
                        'scheme_id' => $doc_id
                    );
                    $out = $extended->createDocument($dummy_data,$this->envirment['database'],$doc_id);
                    $array = json_decode($out, true);
                    $id = $array['id'] ?? null ?? null;
                    $rev = $array['rev'] ?? null ?? null;
                    $data['scheme_id'] = $scheme_id;
                    $data['couch_doc_id'] = $id;
                    $data['couch_rev_id'] = $rev;
                    $attachment = Attachment::create($data);
                }
                $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                $array = json_decode($out, true);
                $rev = $array['rev'] ?? null ?? null;
                if(isset($rev)) {
                    $result = Attachment::where('scheme_id',$scheme_id)->update(['couch_rev_id'=>$rev]);
                }
                $major_objective_file_name = $path['name'];
            }
            $arr = array('major_objective'=>$major_objectives, 'major_objective_file'=>$major_objective_file_name);

            Scheme::where('scheme_id',$scheme_id)->update($arr);
            Proposal::where('draft_id',$draft_id)->update($arr);
            return response()->json('added successfully');
        } else if($slide == 'fourth') {
            
            $major_indicator = $request->input('major_indicator');
            $major_indicator_file_name = '';
            $scheme_id = Session::get('scheme_id');
            $draft_id = Session::get('draft_id');
            if($request->has('major_indicator_file')) {
                $request->validate([
                    'major_indicator_file' => 'file|mimes:pdf,doc,docx,xls,xlsx',
                ]);
                $major_indicator_file = $request->file('major_indicator_file');
                PdfSecurityService::check($major_indicator_file);
                $rev = Attachment::where('scheme_id',$scheme_id)->value('couch_rev_id');
                $extended = new Couchdb();
                $extended->InitConnection();
                $status = $extended->isRunning();
                $doc_id = "scheme_".$scheme_id;
                $docid = 'major_indicator_file'; //.time().'_'.mt_rand('0000000000','9999999999');
                $path['id'] = $docid;
                $path['tmp_name'] = $major_indicator_file->getRealPath();
                $path['extension']  = $major_indicator_file->getClientOriginalExtension();
                $path['name'] = $doc_id.'_'.$path['id'].'.'.$path['extension'];
                if(is_null($rev)){
                    $dummy_data = array(
                        'scheme_id' => $doc_id
                    );
                    $out = $extended->createDocument($dummy_data,$this->envirment['database'],$doc_id);
                    $array = json_decode($out, true);
                    $id = $array['id'] ?? null ?? null;
                    $rev = $array['rev'] ?? null ?? null;
                    $data['scheme_id'] = $scheme_id;
                    $data['couch_doc_id'] = $id;
                    $data['couch_rev_id'] = $rev;
                    $attachment = Attachment::create($data);
                }
                $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                $array = json_decode($out, true);
                $rev = $array['rev'] ?? null;
                if(isset($rev)) {
                    $result = Attachment::where('scheme_id',$scheme_id)->update(['couch_rev_id'=>$rev]);
                }
                $major_indicator_file_name = $path['name'];
            }
            
            $arr = array('major_indicator'=>$major_indicator, 'major_indicator_file'=>$major_indicator_file_name);
            Scheme::where('scheme_id',$scheme_id)->update($arr);
            Proposal::where('draft_id',$draft_id)->update($arr);
            return response()->json('added successfully');
        } else if($slide == 'fifth') {
          //  dd('fifth slide data:',$request->all());
            $data = $request->all();
            $scheme_id = Session::get('scheme_id');
            $draft_id = Session::get('draft_id');
            unset($data['_token']);
            unset($data['slide']);
            //$arr = $data;

            $filename = "";
            if($request->hasFile('next_scheme_overview_file')) {
                $request->validate([
                    'next_scheme_overview_file' => 'file|mimes:pdf,doc,docx,xls,xlsx',
                ]);
                $document = $request->file('next_scheme_overview_file');                
                PdfSecurityService::check($document);
               
                $rev = Attachment::where('scheme_id',$scheme_id)->value('couch_rev_id');
                $extended = new Couchdb();
                $extended->InitConnection();
                $status = $extended->isRunning();
                $doc_id = "scheme_".$scheme_id;
                $docid = 'next_scheme_overview_file'; //.time().'_'.mt_rand('000000000','999999999');
                $path['id'] = $docid;
                $path['tmp_name'] = $document->getRealPath();
                $path['extension']  = $document->getClientOriginalExtension();
                $path['name'] = $doc_id.'_'.$path['id'].'.'.$path['extension'];
                if(is_null($rev)){
                    $dummy_data = array(
                        'scheme_id' => $doc_id
                    );
                    $out = $extended->createDocument($dummy_data,$this->envirment['database'],$doc_id);
                    $array = json_decode($out, true);
                    $id = $array['id'] ?? null;
                    $rev = $array['rev'] ?? null;
                    $data['scheme_id'] = $scheme_id;
                    $data['couch_doc_id'] = $id;
                    $data['couch_rev_id'] = $rev;
                    $attachment = Attachment::create($data);
                }
                $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                $array = json_decode($out, true);
                $rev = $array['rev'] ?? null;
                if(isset($rev)) {
                    $result = Attachment::where('scheme_id',$scheme_id)->update(['couch_rev_id'=>$rev]);
                }                    
                $filename = $path['name'];
            }

            $filetype = "";
            if($request->hasFile('scheme_objective_file')) {
                $request->validate([
                    'scheme_objective_file' => 'file|mimes:pdf,doc,docx,xls,xlsx',
                ]);
                $document = $request->file('scheme_objective_file');             
                PdfSecurityService::check($document);
                $rev = Attachment::where('scheme_id',$scheme_id)->value('couch_rev_id');
                $extended = new Couchdb();
                $extended->InitConnection();
                $status = $extended->isRunning();
                $doc_id = "scheme_".$scheme_id;
                $docid = 'scheme_objective_file'; //.time().'_'.mt_rand('000000000','999999999');
                $path['id'] = $docid;
                $path['tmp_name'] = $document->getRealPath();
                $path['extension']  = $document->getClientOriginalExtension();
                $path['name'] = $doc_id.'_'.$path['id'].'.'.$path['extension'];
                if(is_null($rev)){
                    $dummy_data = array(
                        'scheme_id' => $doc_id
                    );
                    $out = $extended->createDocument($dummy_data,$this->envirment['database'],$doc_id);
                    $array = json_decode($out, true);
                    $id = $array['id'] ?? null;
                    $rev = $array['rev'] ?? null;
                    $data['scheme_id'] = $scheme_id;
                    $data['couch_doc_id'] = $id;
                    $data['couch_rev_id'] = $rev;
                    $attachment = Attachment::create($data);
                }
                $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                $array = json_decode($out, true);
                $rev = $array['rev'] ?? null;
                if(isset($rev)) {
                    $result = Attachment::where('scheme_id',$scheme_id)->update(['couch_rev_id'=>$rev]);
                }                    
                $filetype = $path['name'];
            }

            $filecomponent = "";
            if($request->hasFile('next_scheme_components_file')) {
                $request->validate([
                    'next_scheme_components_file' => 'file|mimes:pdf,doc,docx,xls,xlsx',
                ]);
                $document = $request->file('next_scheme_components_file');
                PdfSecurityService::check($document);
                $rev = Attachment::where('scheme_id',$scheme_id)->value('couch_rev_id');
                $extended = new Couchdb();
                $extended->InitConnection();
                $status = $extended->isRunning();
                $doc_id = "scheme_".$scheme_id;
                $docid = 'next_scheme_components_file'; //.time().'_'.mt_rand('000000000','999999999');
                $path['id'] = $docid;
                $path['tmp_name'] = $document->getRealPath();
                $path['extension']  = $document->getClientOriginalExtension();
                $path['name'] = $doc_id.'_'.$path['id'].'.'.$path['extension'];
                if(is_null($rev)){
                    $dummy_data = array(
                        'scheme_id' => $doc_id
                    );
                    $out = $extended->createDocument($dummy_data,$this->envirment['database'],$doc_id);
                    $array = json_decode($out, true);
                    $id = $array['id'] ?? null;
                    $rev = $array['rev'] ?? null;
                    $data['scheme_id'] = $scheme_id;
                    $data['couch_doc_id'] = $id;
                    $data['couch_rev_id'] = $rev;
                    $attachment = Attachment::create($data);
                }
                $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                $array = json_decode($out, true);
                $rev = $array['rev'] ?? null;
                if(isset($rev)) {
                    $result = Attachment::where('scheme_id',$scheme_id)->update(['couch_rev_id'=>$rev]);
                }                    
                $filecomponent = $path['name'];
            }
            $implementing_office = is_array($request->implementing_office)
                    ? implode(',', $request->implementing_office)
                    : $request->implementing_office;

                // hod_office is JSON array from frontend, decode first
                $hod_office_array = json_decode($request->hod_office, true);

                    $hod_names = [];
                    $hod_emails = [];
                    $hod_mobiles = [];
                    $implementing_office_contact = [];

                    if (is_array($hod_office_array)) {
                        foreach ($hod_office_array as $hod) {
                            $implementing_office_contact[] = $hod['implementing_office_contact'] ?? '';
                            $hod_names[] = $hod['hod_officer_name'] ?? '';
                            $hod_emails[] = $hod['hod_email'] ?? '';
                            $hod_mobiles[] = $hod['hod_mobile'] ?? '';
                        }
                    }

                    // Convert arrays to comma-separated strings
                    $implementing_office_contact = implode(',', $implementing_office_contact);
                    $hod_names_str = implode(',', $hod_names);
                    $hod_emails_str = implode(',', $hod_emails);
                    $hod_mobiles_str = implode(',', $hod_mobiles);

                
                 $arr = [
                    "implementing_office" => $implementing_office,
                    "both_ration" => $request->both_ration,
                    "other_ratio" => $request->other_ratio,
                    "nodal_officer_name" => $request->nodal_officer_name,
                    "nodal_officer_designation" => $request->nodal_officer_designation,
                    "nodal_officer_contact" => $request->nodal_officer_contact,
                    "nodal_officer_mobile" => $request->nodal_officer_mobile,
                    "nodal_officer_email" => $request->nodal_officer_email,
                    "state_ratio" => $request->state_ratio,
                    "center_ratio" => $request->center_ratio,
                    "scheme_overview" => $request->scheme_overview,
                    "scheme_objective" => $request->scheme_objective,
                    "sub_scheme" => $request->sub_scheme,

                   // ✅ HOD-related comma-separated data
                    "hod_officer_name" => $hod_names_str,
                    "hod_email" => $hod_emails_str,
                    "hod_mobile" => $hod_mobiles_str,
                    "implementing_office_contact" => $implementing_office_contact,

                    // ✅ Uploaded files
                    "next_scheme_overview_file" => $filename,
                    "scheme_objective_file" => $filetype,
                    "next_scheme_components_file" => $filecomponent,
                ];
         
          
            Scheme::where('scheme_id',$scheme_id)->update($arr);

            Proposal::where('draft_id',$draft_id)->update($arr);
            return response()->json('added successfully');

        } else if($slide == 'sixth') {
            $data = $request->all();
           
            unset($data['_token']);
            unset($data['slide']);
            $arr = $data;
            $scheme_id = Session::get('scheme_id');
            Scheme::where('scheme_id',$scheme_id)->update($arr);
            $draft_id = Session::get('draft_id');
            Proposal::where('draft_id',$draft_id)->update($arr);
            return response()->json('added successfully');
        } else if($slide == 'seventh') {
          
            $data = $request->all();
            unset($data['_token']);
            unset($data['slide']);
            // $arr = $data;
            $scheme_id = Session::get('scheme_id');
            $draft_id = Session::get('draft_id');

            $filename = "";
            if($request->hasFile('beneficiary_selection_criteria_file')) {
                $request->validate([
                    'beneficiary_selection_criteria_file' => 'file|mimes:pdf,doc,docx,xls,xlsx',
                ]);
                $document = $request->file('beneficiary_selection_criteria_file');
                PdfSecurityService::check($document);
               // $document = $request->file('beneficiary_selection_criteria_file');
                $rev = Attachment::where('scheme_id',$scheme_id)->value('couch_rev_id');
                $extended = new Couchdb();
                $extended->InitConnection();
                $status = $extended->isRunning();
                $doc_id = "scheme_".$scheme_id;
                $docid = 'beneficiary_selection_criteria_file'; //.time().'_'.mt_rand('000000000','999999999');
                $path['id'] = $docid;
                $path['tmp_name'] = $document->getRealPath();
                $path['extension']  = $document->getClientOriginalExtension();
                $path['name'] = $doc_id.'_'.$path['id'].'.'.$path['extension'];
                if(is_null($rev)){
                    $dummy_data = array(
                        'scheme_id' => $doc_id
                    );
                    $out = $extended->createDocument($dummy_data,$this->envirment['database'],$doc_id);
                    $array = json_decode($out, true);
                    $id = $array['id'] ?? null;
                    $rev = $array['rev'] ?? null;
                    $data['scheme_id'] = $scheme_id;
                    $data['couch_doc_id'] = $id;
                    $data['couch_rev_id'] = $rev;
                    $attachment = Attachment::create($data);
                }
                $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                $array = json_decode($out, true);
                $rev = $array['rev'] ?? null;
                if(isset($rev)) {
                    $result = Attachment::where('scheme_id',$scheme_id)->update(['couch_rev_id'=>$rev]);
                }                    
                $filename = $path['name'];
            }

            $arr = [
                "scheme_beneficiary_selection_criteria" => $request->scheme_beneficiary_selection_criteria,
                'beneficiary_selection_criteria_file' => $filename,
            ];

           
            Scheme::where('scheme_id',$scheme_id)->update($arr);
            Proposal::where('draft_id',$draft_id)->update($arr);
            return response()->json('added successfully');
        } else if($slide == 'eighth') {
           
            $filename = '';
            $scheme_id = Session::get('scheme_id');
            $major_benefits_text = $request->input('major_benefits_text');
            if($request->hasFile('major_benefits')) {
                $request->validate([
                    'major_benefits' => 'file|mimes:pdf,doc,docx,xls,xlsx',
                ]);
                $document = $request->file('major_benefits');
                PdfSecurityService::check($document);
               // $document = $request->file('major_benefits');
                $rev = Attachment::where('scheme_id',$scheme_id)->value('couch_rev_id');
                $extended = new Couchdb();
                $extended->InitConnection();
                $status = $extended->isRunning();
                $doc_id = "scheme_".$scheme_id;
                $docid = 'major_benefits'; 
                $path['id'] = $docid;
                $path['tmp_name'] = $document->getRealPath();
                $path['extension']  = $document->getClientOriginalExtension();
                $path['name'] = $doc_id.'_'.$path['id'].'.'.$path['extension'];
                $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                $array = json_decode($out, true);
                $rev = $array['rev'] ?? null;
                if(isset($rev)) {
                    $result = Attachment::where('scheme_id',$scheme_id)->update(['couch_rev_id'=>$rev]);
                }
                $filename = $path['name'];
            }
            $arr = array('major_benefits_text'=>$major_benefits_text,'benefit_to_file'=>$filename);
            $ss = Scheme::where('scheme_id',$scheme_id)->update($arr);
            $draft_id = Session::get('draft_id');
            Proposal::where('draft_id',$draft_id)->update($arr);
            return response()->json('added successfully');
        } else if($slide == 'nineth') {
            $scheme_id = Session::get('scheme_id');
            $draft_id  = Session::get('draft_id');

            $scheme_implementing_procedure = $request->input('scheme_implementing_procedure');
            $implementing_procedure        = $request->input('implementing_procedure');
            $beneficiariesGeoLocal          = $request->input('beneficiariesGeoLocal');
            $otherbeneficiariesGeoLocal     = $request->input('otherbeneficiariesGeoLocal');

            $districts = json_encode($request->input('district_name') ?? []);
            $talukas   = json_encode($request->input('taluka_name') ?? []);
            $states    = json_encode($request->input('state_name') ?? []);
            $taluka_id = $request->taluka_id ?? null;

         //   $geographical_coverage_file     = null;
            $implementing_procedure_file   = null;
            $scheme_implement_file = null;

            /* ================= COUCHDB INIT ================= */
            $extended = new Couchdb();
            $extended->InitConnection();

            $doc_id = "scheme_" . $scheme_id;
            $rev = Attachment::where('scheme_id', $scheme_id)->value('couch_rev_id');

            /* ================= CREATE COUCH DOC IF NEEDED ================= */
            if (!$rev) {
                $out = $extended->createDocument(['scheme_id' => $doc_id], $this->envirment['database'], $doc_id);
                $array = json_decode($out, true);

                Attachment::create([
                    'scheme_id'    => $scheme_id,
                    'couch_doc_id' => $array['id'] ?? null,
                    'couch_rev_id' => $array['rev'] ?? null
                ]);

                $rev = $array['rev'] ?? null;
            }

            /* ================= IMPLEMENTING PROCEDURE FILE ================= */
            if ($request->hasFile('implementing_procedure_file')) {
				$request->validate([
						'implementing_procedure_file' => 'file|mimes:pdf,doc,docx,xls,xlsx',
					]);
                $document = $request->file('implementing_procedure_file');
				PdfSecurityService::check($document);
                $path = [
                    'id'        => 'implementing_procedure_file',
                    'tmp_name'  => $document->getRealPath(),
                    'extension' => $document->getClientOriginalExtension(),
                    'name'      => $doc_id . '_implementing_procedure_file.' . $document->getClientOriginalExtension()
                ];

                $out = $extended->createAttachmentDocument(
                    $this->envirment['database'],
                    $doc_id,
                    $rev,
                    $path
                );

                $array = json_decode($out, true);

                if (!empty($array['rev'])) {
                    $rev = $array['rev'];
                    Attachment::where('scheme_id', $scheme_id)->update(['couch_rev_id' => $rev]);
                }

                $implementing_procedure_file = $path['name'];
            }

            if ($request->hasFile('scheme_implement_file')) {
				$request->validate([
						'scheme_implement_file' => 'file|mimes:pdf,doc,docx,xls,xlsx',
					]);
                $document = $request->file('scheme_implement_file');
				PdfSecurityService::check($document);
                $path = [
                    'id'        => 'scheme_implement_file',
                    'tmp_name'  => $document->getRealPath(),
                    'extension' => $document->getClientOriginalExtension(),
                    'name'      => $doc_id . '_scheme_implement_file.' . $document->getClientOriginalExtension()
                ];

                $out = $extended->createAttachmentDocument(
                    $this->envirment['database'],
                    $doc_id,
                    $rev,
                    $path
                );

                $array = json_decode($out, true);

                if (!empty($array['rev'])) {
                    $rev = $array['rev'];
                    Attachment::where('scheme_id', $scheme_id)->update(['couch_rev_id' => $rev]);
                }

                $scheme_implement_file = $path['name'];
            }

            /* ================= GEOGRAPHICAL COVERAGE FILE ================= */
            // if ($request->hasFile('geographical_coverage')) {
			// 		$request->validate([
			// 			'geographical_coverage' => 'file|mimes:pdf,doc,docx,xls,xlsx',
			// 		]);
            //     // $document = $request->file('geographical_coverage');
                
            //     $document = $request->file('geographical_coverage');
			// 	PdfSecurityService::check($document);
            //     $path = [
            //         'id'        => 'geographical_coverage',
            //         'tmp_name'  => $document->getRealPath(),
            //         'extension' => $document->getClientOriginalExtension(),
            //         'name'      => $doc_id . '_geographical_coverage.' . $document->getClientOriginalExtension()
            //     ];

            //     $out = $extended->createAttachmentDocument(
            //         $this->envirment['database'],
            //         $doc_id,
            //         $rev,
            //         $path
            //     );

            //     $array = json_decode($out, true);

            //     if (!empty($array['rev'])) {
            //         $rev = $array['rev'];
            //         Attachment::where('scheme_id', $scheme_id)->update(['couch_rev_id' => $rev]);
            //     }

            //     $geographical_coverage_file = $path['name'];
            // }

            /* ================= INSERT / UPDATE DATA ================= */
            $arr = [
                'scheme_implementing_procedure' => $scheme_implementing_procedure,
                'implementing_procedure'        => $implementing_procedure,
                'beneficiariesGeoLocal'          => $beneficiariesGeoLocal,
                'districts'                     => $districts,
                'talukas'                       => $talukas,
                'states'                        => $states,
                'taluka_id'                     => $taluka_id,
                'otherbeneficiariesGeoLocal'    => $otherbeneficiariesGeoLocal,
                'implementing_procedure_file'   => $implementing_procedure_file,
                'scheme_implement_file' => $scheme_implement_file,
               // 'geographical_coverage'         => $geographical_coverage_file
            ];

            /* ================= SAVE ================= */
            Scheme::where('scheme_id', $scheme_id)->update($arr);
            Proposal::where('draft_id', $draft_id)->update($arr);

            return response()->json('added successfully');

		   
            // $scheme_id = Session::get('scheme_id');
            // $scheme_implementing_procedure = $request->input('scheme_implementing_procedure');
            // $beneficiariesGeoLocal = $request->input('beneficiariesGeoLocal');
            // $districts = json_encode($request->input('district_name'));
            // $talukas = json_encode($request->input('taluka_name'));
            // $states = json_encode($request->input('state_name'));
            // $taluka_id = (!is_null($request->taluka_id)) ? $request->taluka_id : 'null';
            // $otherbeneficiariesGeoLocal = $request->input('otherbeneficiariesGeoLocal');
            // $filename = '';

            // if($request->hasFile('geographical_coverage')) {
                // $request->validate([
                    // 'geographical_coverage' => 'file|mimes:pdf,doc,docx,xls,xlsx',
                // ]);
                // $document = $request->file('geographical_coverage');
                // PdfSecurityService::check($document);
              // $document = $request->file('geographical_coverage');
                // $rev = Attachment::where('scheme_id',$scheme_id)->value('couch_rev_id');
                // $extended = new Couchdb();
                // $extended->InitConnection();
                // $status = $extended->isRunning();
                // $doc_id = "scheme_".$scheme_id;
                // $docid = 'geographical_coverage'; 
                // $path['id'] = $docid;
                // $path['tmp_name'] = $document->getRealPath();
                // $path['extension']  = $document->getClientOriginalExtension();
                // $path['name'] = $doc_id.'_'.$path['id'].'.'.$path['extension'];
                // $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                // $array = json_decode($out, true);
                // $rev = $array['rev'] ?? null;
                // if(isset($rev)) {
                    // $result = Attachment::where('scheme_id',$scheme_id)->update(['couch_rev_id'=>$rev]);
                // }                    
                // $filename = $path['name'];
            // }

            // $arr = array('scheme_implementing_procedure'=>$scheme_implementing_procedure, 
            // 'beneficiariesGeoLocal'=>$beneficiariesGeoLocal, 
            // 'districts'=>$districts, 
            // 'talukas'=>$talukas,
            // 'taluka_id'=>$taluka_id,
            // 'states'=>$states,
            // 'otherbeneficiariesGeoLocal'=>$otherbeneficiariesGeoLocal,
            // 'geographical_coverage'=>$filename);
            // Scheme::where('scheme_id',$scheme_id)->update($arr);
            // $draft_id = Session::get('draft_id');
            // Proposal::where('draft_id',$draft_id)->update($arr);
            // return response()->json('added successfully');
        } else if($slide == 'tenth') {
         
            $scheme_id = Session::get('scheme_id');
            $coverage_beneficiaries_remarks = $request->input('coverage_beneficiaries_remarks');
            $training_capacity_remarks = $request->input('training_capacity_remarks');
            $iec_activities_remarks = $request->input('iec_activities_remarks');
            $beneficiaries_coverage_file_name = '';
            if($request->has('beneficiaries_coverage')) {
                $request->validate([
                    'beneficiaries_coverage' => 'file|mimes:pdf,doc,docx,xls,xlsx',
                ]);
                $beneficiaries_coverage = $request->file('beneficiaries_coverage');
                PdfSecurityService::check($beneficiaries_coverage);
                //$beneficiaries_coverage = $request->file('beneficiaries_coverage');
                $rev = Attachment::where('scheme_id',$scheme_id)->value('couch_rev_id');
                $extended = new Couchdb();
                $extended->InitConnection();
                $status = $extended->isRunning();
                $doc_id = "scheme_".$scheme_id;
                $docid = 'beneficiaries_coverage'; //.time().'_'.mt_rand('0000000000','9999999999');
                $path['id'] = $docid;
                $path['tmp_name'] = $beneficiaries_coverage->getRealPath();
                $path['extension']  = $beneficiaries_coverage->getClientOriginalExtension();
                $path['name'] = $doc_id.'_'.$path['id'].'.'.$path['extension'];
                if(is_null($rev)){
                    $dummy_data = array(
                        'scheme_id' => $doc_id
                    );
                    $out = $extended->createDocument($dummy_data,$this->envirment['database'],$doc_id);
                    $array = json_decode($out, true);
                    $id = $array['id'] ?? null;
                    $rev = $array['rev'] ?? null;
                    $data['scheme_id'] = $scheme_id;
                    $data['couch_doc_id'] = $id;
                    $data['couch_rev_id'] = $rev;
                    $attachment = Attachment::create($data);
                }
                $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                $array = json_decode($out, true);
                $rev = $array['rev'] ?? null;
                if(isset($rev)) {
                    $result = Attachment::where('scheme_id',$scheme_id)->update(['couch_rev_id'=>$rev]);
                }                    
                $beneficiaries_coverage_file_name = $path['name'];
            }
            $training_file_name = '';
            if($request->has('training')) {
                $training = $request->file('training');
                 $request->validate([
                    'training' => 'file|mimes:pdf,doc,docx,xls,xlsx',
                ]);
                PdfSecurityService::check($training);
                $rev = Attachment::where('scheme_id',$scheme_id)->value('couch_rev_id');
                $extended = new Couchdb();
                $extended->InitConnection();
                $status = $extended->isRunning();
                $doc_id = "scheme_".$scheme_id;
                $docid = 'training'; //.time().'_'.mt_rand('0000000000','9999999999');
                $path['id'] = $docid;
                $path['tmp_name'] = $training->getRealPath();
                $path['extension']  = $training->getClientOriginalExtension();
                $path['name'] = $doc_id.'_'.$path['id'].'.'.$path['extension'];
                $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                $array = json_decode($out, true);
                $rev = $array['rev'] ?? null;
                if(isset($rev)) {
                    $result = Attachment::where('scheme_id',$scheme_id)->update(['couch_rev_id'=>$rev]);
                }                    
                $training_file_name = $path['name'];
            }
            $iec_file_name = '';
            if($request->has('iec_file')) {
                $iec_file = $request->file('iec_file');
                 $request->validate([
                    'iec_file' => 'file|mimes:pdf,doc,docx,xls,xlsx',
                ]);
                PdfSecurityService::check($iec_file);
                $rev = Attachment::where('scheme_id',$scheme_id)->value('couch_rev_id');
                $extended = new Couchdb();
                $extended->InitConnection();
                $status = $extended->isRunning();
                $doc_id = "scheme_".$scheme_id;
                $docid = 'iec'; //.time().'_'.mt_rand('0000000000','9999999999');
                $path['id'] = $docid;
                $path['tmp_name'] = $iec_file->getRealPath();
                $path['extension']  = $iec_file->getClientOriginalExtension();
                $path['name'] = $doc_id.'_'.$path['id'].'.'.$path['extension'];
                $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                $array = json_decode($out, true);
                $rev = $array['rev'] ?? null;
                if(isset($rev)) {
                    $result = Attachment::where('scheme_id',$scheme_id)->update(['couch_rev_id'=>$rev]);
                }                    
                $iec_file_name = $path['name'];
            }
            $arr = array('coverage_beneficiaries_remarks'=>$coverage_beneficiaries_remarks, 'training_capacity_remarks'=>$training_capacity_remarks, 'iec_activities_remarks'=>$iec_activities_remarks,'beneficiaries_coverage'=>$beneficiaries_coverage_file_name,'training'=>$training_file_name,'iec'=>$iec_file_name);
            Scheme::where('scheme_id',$scheme_id)->update($arr);
            $draft_id = Session::get('draft_id');
            Proposal::where('draft_id',$draft_id)->update($arr);
            return response()->json('added successfully');
        } else if($slide == 'eleventh') {
           
            $benefit_to = $request->input('benefit_to');
            $all_convergence = $request->input('all_convergence');
            $arr = array('benefit_to'=>$benefit_to,'all_convergence'=>$all_convergence);
            $scheme_id = Session::get('scheme_id');
            Scheme::where('scheme_id',$scheme_id)->update($arr);
            $draft_id = Session::get('draft_id');
            Proposal::where('draft_id',$draft_id)->update($arr);
            return response()->json('added successfully');
        } else if($slide == 'twelth') {
            $data = $request->all();
            unset($data['_token']);
            unset($data['slide']);

            $scheme_id = Session::get('scheme_id');
            $draft_id = Session::get('draft_id');
            $documents = $request->file();
            $extended = new Couchdb();
            $extended->InitConnection();
            $doc_id = "scheme_".$scheme_id;

            // --- STEP 1: GLOBAL SECURITY CHECK ---
            // We check EVERY file before a single database record is created.
            foreach ($documents as $category => $files) {
                if (is_array($files)) {
                    foreach ($files as $file) {
                        if (!PdfSecurityService::check($file, false)) {
                            return response()->json([
                                'error' => "The file '" . $file->getClientOriginalName() . "' contains scripts and is not allowed."
                            ], 422);
                        }
                    }
                } elseif ($files instanceof \Illuminate\Http\UploadedFile) {
                    if (!PdfSecurityService::check($files, false)) {
                        return response()->json([
                            'error' => "The file '" . $files->getClientOriginalName() . "' contains scripts and is not allowed."
                        ], 422);
                    }
                }
            }

            // --- STEP 2: COUCHDB INITIALIZATION ---
            $fileEntry = Attachment::where('scheme_id', $scheme_id)->first();
            if (!$fileEntry) {
                $out = $extended->createDocument(['scheme_id' => $doc_id], $this->envirment['database'], $doc_id);
                $array = json_decode($out, true);
                $rev = $array['rev'] ?? null;
                $fileEntry = Attachment::create([
                    'scheme_id' => $scheme_id,
                    'couch_doc_id' => $doc_id,
                    'couch_rev_id' => $rev,
                ]);
            }
            $rev = $fileEntry->couch_rev_id;

            // --- STEP 3: PROCESS MULTI-FILE CATEGORIES (SQL + COUCHDB) ---
            $j = 1;
            $categories = [
                'gr' => GrFilesList::class,
                'notification' => NotificationFileList::class,
                'brochure' => BrochureFileList::class,
                'pamphlets' => PamphletFileList::class,
                'otherdetailscenterstate' => CenterStateFiles::class
            ];

            foreach ($categories as $inputName => $modelClass) {
                if ($request->hasFile($inputName)) {
                    $files = $request->file($inputName);
                    foreach ($files as $key => $file) {
                        $file_ext = $file->getClientOriginalExtension();
                        $file_name = "{$doc_id}_{$inputName}_" . ($key + 1) . ".{$file_ext}";

                        $path = [
                            'id' => "{$inputName}_" . ($key + 1),
                            'tmp_name' => $file->getRealPath(),
                            'extension' => $file_ext,
                            'name' => $file_name,
                        ];

                        // Upload to CouchDB using the current $rev
                        $out = $extended->createAttachmentDocument($this->envirment['database'], $doc_id, $rev, $path);
                        $res = json_decode($out, true);
                        
                        if (!empty($res['rev'])) {
                            $rev = $res['rev']; // Update $rev for the next file in loop
                            Attachment::where('scheme_id', $scheme_id)->update(['couch_rev_id' => $rev]);
                        }

                        // Create SQL Record
                        $modelClass::create([
                            'file_name' => $file_name,
                            'scheme_id' => $scheme_id,
                            'created_at' => now(),
                        ]);
                    }
                }
            }

            // --- STEP 4: BENEFICIARY FORM LOGIC ---
            $beneficiaryType = $request->input('beneficiary_filling_form_type');
            $beneficiaryFileName = null;

            if ($beneficiaryType === '0' && $request->hasFile('beneficiary_filling_form')) {
                $ben_file = $request->file('beneficiary_filling_form');
                $file_ext = $ben_file->getClientOriginalExtension();
                $file_name = "{$doc_id}_ben_form_1.{$file_ext}";

                $path = [
                    'id' => 'ben_form_1',
                    'tmp_name' => $ben_file->getRealPath(),
                    'extension' => $file_ext,
                    'name' => $file_name
                ];

                $out = $extended->createAttachmentDocument($this->envirment['database'], $doc_id, $rev, $path);
                $res = json_decode($out, true);
                
                if (!empty($res['rev'])) {
                    $rev = $res['rev'];
                    Attachment::where('scheme_id', $scheme_id)->update(['couch_rev_id' => $rev]);
                }
                $beneficiaryFileName = $file_name;
            }

            // --- STEP 5: FINAL MAIN TABLE UPDATE ---
            $updateData = [
                'beneficiary_filling_form_type' => $beneficiaryType,
                'beneficiary_filling_form' => $beneficiaryFileName,
            ];

            Scheme::where('scheme_id', $scheme_id)->update($updateData);
            Proposal::where('draft_id', $draft_id)->update($updateData);

            return response()->json('added successfully');
        } else if($slide == 'thirteenth') {
           
            $data = $request->all();
            unset($data['_token']);
            unset($data['slide']);
            $arr = $data;
            $scheme_id = Session::get('scheme_id');
            if($scheme_id != '') {
                Scheme::where('scheme_id',$scheme_id)->update($arr);
                $draft_id = Session::get('draft_id');
                Proposal::where('draft_id',$draft_id)->update($arr);
            }
            return response()->json('added successfully');
        } else if($slide == 'fourteenth') {
         
            $tr_array = $request->input('tr_array');
            $fin_progress_remarks = $request->input('financial_progress_remarks');
            $arr = array();
            $scheme_id = Session::get('scheme_id');
            $fn_year = '';
            foreach($tr_array as $tr) {
                $fn_year = $tr['financial_year'];
                $arr[] = array('scheme_id'=>$scheme_id,'financial_year'=>$tr['financial_year'], 'target'=>$tr['target'],'achievement'=>$tr['achievement'], 'allocation'=>$tr['allocation'], 'expenditure'=>$tr['expenditure'],'selection' => $tr['selection']);
            }
            if($fn_year == '' or $scheme_id == '') {

            } else if($scheme_id != '' and $fn_year != '') {
                FinancialProgress::where('scheme_id',$scheme_id)->delete();
                FinancialProgress::insert($arr);
                Scheme::where('scheme_id',$scheme_id)->update(['fin_progress_remarks'=>$fin_progress_remarks]);
                $draft_id = Session::get('draft_id');
                Proposal::where('draft_id',$draft_id)->update(['fin_progress_remarks'=>$fin_progress_remarks]);
                return response()->json('added successfully');
            }
        } 
    }

    public function saveLastitem(Request $request){
        $slideItem = $request->save_item;
        if(Session::has('draft_id')){
            $draft_id = Session::get('draft_id');
        }else{
            $draft_id = $request->draft_id;
        }
       
        if($slideItem > 0){
          
            if(!is_null($draft_id)){
                Proposal::where('draft_id',$draft_id)->update(['save_last_item' => $slideItem]);
            }
        }else{
            if(!is_null($draft_id)){
                Proposal::where('draft_id',$draft_id)->update(['save_last_item' => 1]);
            }  
        }
        $act['userid'] = Auth::user()->id;
        $act['ip'] = $request->ip();
        $act['activity'] = 'Scheme Save&Exit By Concern Department';
        $act['officecode'] = Auth::user()->dept_id;
        $act['pagereferred'] = $request->url();
        Activitylog::insert($act);
        return response()->json(['success',true,'redirectUrl'=> route('proposals', ['param' => 'new'])]);
    }

    public function proposaledit($id) {
        $val = Proposal::with(['gr_file','notification_files','brochure_files','pamphlets_files','otherdetailscenterstate_files'])->where('draft_id',Crypt::decrypt($id))->latest()->first();

       //dd($val);
            $scheme_id = Proposal::where('draft_id',Crypt::decrypt($id))->value('scheme_id');
            Session::put('draft_id',Crypt::decrypt($id));
            Session::put('scheme_id',$scheme_id);
            Session::get('scheme_id');
            Session::get('draft_id');
            $financial_years = financialyears();
            $goals = Sdggoals::where('status','1')->orderBy('goal_id','asc')->get();
            $user_id = auth()->user()->id;
            $dept = DB::table('users')
                    ->leftjoin('public.user_user_role_deptid','user_id','=','users.id')
                    ->leftjoin('imaster.departments','imaster.departments.dept_id','=','user_user_role_deptid.dept_id')
                    ->where('users.id','=',$user_id)
                    ->select('departments.dept_name','departments.dept_id')
                    ->get();
                $departments = Department::orderBy('dept_name')->get();
                $implementations = Implementation::all();
                $beneficiariesGeoLocal = beneficiariesGeoLocal();
                $financial_progress = FinancialProgress::where('scheme_id',$scheme_id)->get();
                $gr_files = GrFilesList::where('scheme_id',$scheme_id)->get();
                $notifications = NotificationFileList::where('scheme_id',$scheme_id)->get();
                $brochures = BrochureFileList::where('scheme_id',$scheme_id)->get();
                $pamphlets = PamphletFileList::where('scheme_id',$scheme_id)->get();
            
                $center_state = CenterStateFiles::where('scheme_id',$scheme_id)->get();
                $replace_url = URL::to('/');
                $year = Commencementyear();
                $units = units();
                $hod_office_data = [
                    'hod_officer_name' =>$val->hod_officer_name,
                    'hod_email' => $val->hod_email,
                    'implementing_office_contact' => $val->implementing_office_contact,
                    'hod_mobile' => $val->hod_mobile
                ];
                $deptId = auth()->user()->dept_id;
                $designations = NodalDesignation::where(function ($q) use ($deptId) {
                    $q->where('is_default', true)
                    ->orWhere('dept_id', $deptId);
                })
        ->where('status', true)
        ->orderByDesc('is_default')
        ->pluck('designation_name');
        $attachment_info = Attachment::where('scheme_id', $val->scheme_id)->orderBy('id', 'desc') ->first();       
       // dd($attachment_info);
       // dd($hod_office_data);
        return view('schemes.proposal_edit',compact('attachment_info','units','year','val','departments','implementations','beneficiariesGeoLocal','dept','financial_years','goals','financial_progress','scheme_id','replace_url','gr_files','notifications','brochures','pamphlets','center_state','hod_office_data', 'designations'));
    }

    public function scheme_update(Request $request) {
        $slide = $request->input('slide');
        if($slide == 'first') {
            $scheme_id = $request->input('scheme_id');
            $draft_id = $request->input('draft_id');
            Session::put('scheme_id',$scheme_id);
            Session::put('draft_id',$draft_id);

             // Common Input Fields
                $dept_id  = $request->input('dept_id');
                $convener_name = $request->input('convener_name');
                $convener_designation = $request->input('convener_designation');
                $convener_phone = $request->input('convener_phone');
                $convener_mobile = $request->input('convener_mobile');
                $convener_email = $request->input('convener_email');
                $scheme_name = $request->input('scheme_name');
                $scheme_short_name = $request->input('scheme_short_name');
                $reference_year = $request->input('reference_year');
                $reference_year2 = $request->input('reference_year2');
                $financial_adviser_name = $request->input('financial_adviser_name');
               // $financial_adviser_designation = $request->input('financial_adviser_designation');
                $financial_adviser_phone = $request->input('financial_adviser_phone');
                $financial_adviser_email = $request->input('financial_adviser_email');
                $financial_adviser_mobile = $request->input('financial_adviser_mobile');

                // Evaluation fields
                $is_evaluation = $request->input('is_evaluation');
                $eval_by_whom = $request->input('eval_by_whom');
                $eval_when = $request->input('eval_when');
                $eval_geographical_coverage_beneficiaries = $request->input('eval_geographical_coverage_beneficiaries');
                $eval_number_of_beneficiaries = $request->input('eval_number_of_beneficiaries');
                $eval_major_recommendation = $request->input('eval_major_recommendation');

                // Prepare array for update
                $updateData = [
                    'dept_id' => $dept_id,
                    'convener_name' => $convener_name,
                    'convener_designation' => $convener_designation,
                    'convener_phone' => $convener_phone,
                    'convener_mobile' => $convener_mobile,
                    'convener_email' => $convener_email,
                    'scheme_name' => $scheme_name,
                    'scheme_short_name' => $scheme_short_name,
                    'reference_year' => $reference_year,
                    'reference_year2' => $reference_year2,
                    'financial_adviser_name' => $financial_adviser_name,
                   // 'financial_adviser_designation' => $financial_adviser_designation,
                    'financial_adviser_phone' => $financial_adviser_phone,
                    'financial_adviser_email' => $financial_adviser_email,
                    'financial_adviser_mobile' => $financial_adviser_mobile,
                    'is_evaluation' => $is_evaluation,
                    'eval_scheme_bywhom' => $eval_by_whom,
                    'eval_scheme_when' => $eval_when,
                    'eval_scheme_geo_cov_bene' => $eval_geographical_coverage_beneficiaries,
                    'eval_scheme_no_of_bene' => $eval_number_of_beneficiaries,
                    'eval_scheme_major_recommendation' => $eval_major_recommendation,
                ];

                if ($request->hasFile('eval_upload_report')) {
                    $request->validate([
                        'eval_upload_report' => 'required|file|mimes:pdf,doc,docx,xls,xlsx',
                    ]);
                    
                    $eval_upload_report = $request->file('eval_upload_report');
                    PdfSecurityService::check($eval_upload_report);
                   
                    $rev = Attachment::where('scheme_id', $scheme_id)->value('couch_rev_id');

                    // Initialize CouchDB
                    $extended = new Couchdb();
                    $extended->InitConnection();
                    $extended->isRunning();
                    $doc_id = "scheme_" . $scheme_id;
                    $docid = 'eval_report_' . mt_rand(1000000000, 9999999999);
                    $path['id'] = $docid;
                    $path['tmp_name'] = $eval_upload_report->getRealPath();
                    $path['extension'] = $eval_upload_report->getClientOriginalExtension();
                    $path['name'] = $doc_id . '_' . $path['id'] . '.' . $path['extension'];
                    if (is_null($rev)) {
                        $dummy_data = ['scheme_id' => $doc_id];
                        $out = $extended->createDocument($dummy_data, $this->envirment['database'], $doc_id);
                        $array = json_decode($out, true);
                        $id = $array['id'] ?? null ?? null;
                        $rev = $array['rev'] ?? null ?? null;

                        Attachment::create([
                            'scheme_id' => $scheme_id,
                            'couch_doc_id' => $id,
                            'couch_rev_id' => $rev,
                        ]);
                    }
                    $out = $extended->createAttachmentDocument($this->envirment['database'], $doc_id, $rev, $path);
                    $array = json_decode($out, true);
                    $rev = $array['rev'] ?? null ?? null;

                    if ($rev) {
                        Attachment::where('scheme_id', $scheme_id)->update(['couch_rev_id' => $rev]);
                    }

                    $eval_report_file_name = $path['name'];
                    // ✅ Update the scheme & proposal with new file name
                    Scheme::where('scheme_id', $scheme_id)->update(['eval_scheme_report' => $eval_report_file_name]);
                    Proposal::where('draft_id', $draft_id)->update(['eval_scheme_report' => $eval_report_file_name]);
                }
            
            Scheme::where('scheme_id',$scheme_id)->update($updateData);
            Proposal::where('draft_id',$draft_id)->update($updateData);
            return response()->json('updated successfully');
            
        } else if($slide == 'third') {
            $major_objectives = $request->input('major_objective');
           // $major_objectives_json = is_array($major_objectives) ? json_encode($major_objectives) : $major_objectives; 
            if (Session::has('scheme_id') && Session::has('draft_id')) {
                $scheme_id = Session::get('scheme_id');
                $draft_id = Session::get('draft_id');
            } else {
                $scheme_id = $request->scheme_id;
                $draft_id = $request->draft_id;
            }

            $major_objective_file_name = null;

            // ✅ Handle new file upload (optional)
            if ($request->hasFile('major_objective_file')) {
                $request->validate([
                        'major_objective_file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx',
                ]);
                $major_objective_file = $request->file('major_objective_file');
                PdfSecurityService::check($major_objective_file);

        
                $rev = Attachment::where('scheme_id', $scheme_id)->value('couch_rev_id');

                // Initialize CouchDB
                $extended = new Couchdb();
                $extended->InitConnection();
                $extended->isRunning();

                $doc_id = "scheme_" . $scheme_id;
                $docid = 'major_objective_file';
                $path['id'] = $docid;
                $path['tmp_name'] = $major_objective_file->getRealPath();
                $path['extension'] = $major_objective_file->getClientOriginalExtension();
                $path['name'] = $doc_id . '_' . $path['id'] . '.' . $path['extension'];

                // If CouchDB record doesn’t exist, create it first
                if (is_null($rev)) {
                    $dummy_data = ['scheme_id' => $doc_id];
                    $out = $extended->createDocument($dummy_data, $this->envirment['database'], $doc_id);
                    $array = json_decode($out, true);
                    $id = $array['id'] ?? null ?? null;
                    $rev = $array['rev'] ?? null ?? null;

                    if ($id && $rev) {
                        Attachment::create([
                            'scheme_id' => $scheme_id,
                            'couch_doc_id' => $id,
                            'couch_rev_id' => $rev,
                        ]);
                    }
                }

                // Upload file to CouchDB
                $out = $extended->createAttachmentDocument($this->envirment['database'], $doc_id, $rev, $path);
                $array = json_decode($out, true);
                $rev = $array['rev'] ?? null ?? null;

                if ($rev) {
                    Attachment::where('scheme_id', $scheme_id)->update(['couch_rev_id' => $rev]);
                }

                $major_objective_file_name = $path['name'];
            }

            // ✅ Prepare update array
            $updateData = [
                'major_objective' => $major_objectives,
            ];

            // ✅ Add file name only if a new file uploaded
            if ($major_objective_file_name) {
                $updateData['major_objective_file'] = $major_objective_file_name;
            }
            Scheme::where('scheme_id',$scheme_id)->update($updateData);
            Proposal::where('draft_id',$draft_id)->update($updateData);
            return response()->json('updated successfully');
        } else if($slide == 'fourth') {
            
                $major_indicator = $request->input('major_indicator');

                // Ensure proper JSON encoding for array-type input
                // $major_indicator_json = is_array($major_indicator)
                //     ? json_encode($major_indicator)
                //     : $major_indicator;

                if (Session::has('scheme_id') && Session::has('draft_id')) {
                    $scheme_id = Session::get('scheme_id');
                    $draft_id = Session::get('draft_id');
                } else {
                    $scheme_id = $request->scheme_id;
                    $draft_id = $request->draft_id;
                }

                $major_indicator_file_name = null;

                // ✅ Handle file upload (if a new one provided)
                if ($request->hasFile('major_indicator_file')) {
                    $request->validate([
                        'major_indicator_file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx',
                    ]);
                    
                    $major_indicator_file = $request->file('major_indicator_file');
                    PdfSecurityService::check($major_indicator_file);

                    // Get current CouchDB revision ID
                    $rev = Attachment::where('scheme_id', $scheme_id)->value('couch_rev_id');

                    // Initialize CouchDB connection
                    $extended = new Couchdb();
                    $extended->InitConnection();
                    $extended->isRunning();

                    $doc_id = "scheme_" . $scheme_id;
                    $docid = 'major_indicator_file';

                    // Prepare attachment metadata
                    $path['id'] = $docid;
                    $path['tmp_name'] = $major_indicator_file->getRealPath();
                    $path['extension'] = $major_indicator_file->getClientOriginalExtension();
                    $path['name'] = $doc_id . '_' . $path['id'] . '.' . $path['extension'];
                    if (is_null($rev)) {
                        $dummy_data = ['scheme_id' => $doc_id];
                        $out = $extended->createDocument($dummy_data, $this->envirment['database'], $doc_id);
                        $array = json_decode($out, true);
                        $id = $array['id'] ?? null ?? null;
                        $rev = $array['rev'] ?? null ?? null;

                        Attachment::create([
                            'scheme_id' => $scheme_id,
                            'couch_doc_id' => $id,
                            'couch_rev_id' => $rev,
                        ]);
                    }
                    // Upload file to CouchDB
                    $out = $extended->createAttachmentDocument($this->envirment['database'], $doc_id, $rev, $path);
                    $array = json_decode($out, true);
                    $rev = $array['rev'] ?? null ?? null;

                    // Update CouchDB revision in local DB
                    if ($rev) {
                        Attachment::where('scheme_id', $scheme_id)->update(['couch_rev_id' => $rev]);
                    }

                    // Save filename to DB
                    $major_indicator_file_name = $path['name'];
                }

                // ✅ Prepare data for update
                $updateData = [
                    'major_indicator' => $major_indicator,
                ];

                // ✅ Add file only if a new one uploaded
                if ($major_indicator_file_name) {
                    $updateData['major_indicator_file'] = $major_indicator_file_name;
                }
            Scheme::where('scheme_id',$scheme_id)->update($updateData);
            Proposal::where('draft_id',$draft_id)->update($updateData);
            return response()->json('updated successfully');

        } else if($slide == 'fifth') {
           
            $data = $request->all();
            unset($data['_token']);
            unset($data['slide']);
            if(Session::has('scheme_id') && Session::has('draft_id')){
                $scheme_id = Session::get('scheme_id');
                $draft_id = Session::get('draft_id');
            }else{
                $scheme_id = $request->scheme_id;
                $draft_id = $request->draft_id;
            }
           
           $filename = "";
            if($request->hasFile('next_scheme_overview_file')) {
                    $request->validate([
                        'next_scheme_overview_file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx',
                    ]);
                    
                    $document = $request->file('next_scheme_overview_file');
                    PdfSecurityService::check($document);
              
                $rev = Attachment::where('scheme_id',$scheme_id)->value('couch_rev_id');
                $extended = new Couchdb();
                $extended->InitConnection();
                $status = $extended->isRunning();
                $doc_id = "scheme_".$scheme_id;
                $docid = 'next_scheme_overview_file'; //.time().'_'.mt_rand('000000000','999999999');
                $path['id'] = $docid;
                $path['tmp_name'] = $document->getRealPath();
                $path['extension']  = $document->getClientOriginalExtension();
                $path['name'] = $doc_id.'_'.$path['id'].'.'.$path['extension'];
                if(is_null($rev)){
                    $dummy_data = array(
                        'scheme_id' => $doc_id
                    );
                    $out = $extended->createDocument($dummy_data,$this->envirment['database'],$doc_id);
                    $array = json_decode($out, true);
                    $id = $array['id'] ?? null;
                    $rev = $array['rev'] ?? null;
                    $data['scheme_id'] = $scheme_id;
                    $data['couch_doc_id'] = $id;
                    $data['couch_rev_id'] = $rev;
                    $attachment = Attachment::create($data);
                }
                $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                $array = json_decode($out, true);
                $rev = $array['rev'] ?? null;
                if(isset($rev)) {
                    $result = Attachment::where('scheme_id',$scheme_id)->update(['couch_rev_id'=>$rev]);
                }                    
                $filename = $path['name'];
            }else{
                $filename = $request->existing_next_scheme_overview_file ?? null;
            }

            $filetype = "";
            if($request->hasFile('scheme_objective_file')) {
                    $request->validate([
                        'scheme_objective_file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx',
                    ]);
                    
                    $document = $request->file('scheme_objective_file');
                    PdfSecurityService::check($document);
               
                $rev = Attachment::where('scheme_id',$scheme_id)->value('couch_rev_id');
                $extended = new Couchdb();
                $extended->InitConnection();
                $status = $extended->isRunning();
                $doc_id = "scheme_".$scheme_id;
                $docid = 'scheme_objective_file'; //.time().'_'.mt_rand('000000000','999999999');
                $path['id'] = $docid;
                $path['tmp_name'] = $document->getRealPath();
                $path['extension']  = $document->getClientOriginalExtension();
                $path['name'] = $doc_id.'_'.$path['id'].'.'.$path['extension'];
                if(is_null($rev)){
                    $dummy_data = array(
                        'scheme_id' => $doc_id
                    );
                    $out = $extended->createDocument($dummy_data,$this->envirment['database'],$doc_id);
                    $array = json_decode($out, true);
                    $id = $array['id'] ?? null;
                    $rev = $array['rev'] ?? null;
                    $data['scheme_id'] = $scheme_id;
                    $data['couch_doc_id'] = $id;
                    $data['couch_rev_id'] = $rev;
                    $attachment = Attachment::create($data);
                }
                $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                $array = json_decode($out, true);
                $rev = $array['rev'] ?? null;
                if(isset($rev)) {
                    $result = Attachment::where('scheme_id',$scheme_id)->update(['couch_rev_id'=>$rev]);
                }                    
                $filetype = $path['name'];
            }else{
                $filetype = $request->existing_scheme_objective_file ?? null;
            }

            $filecomponent = "";
            if($request->hasFile('next_scheme_components_file')) {
                    $request->validate([
                        'next_scheme_components_file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx',
                    ]);
                    
                    $document = $request->file('next_scheme_components_file');
                    PdfSecurityService::check($document);
                
                $rev = Attachment::where('scheme_id',$scheme_id)->value('couch_rev_id');
                $extended = new Couchdb();
                $extended->InitConnection();
                $status = $extended->isRunning();
                $doc_id = "scheme_".$scheme_id;
                $docid = 'next_scheme_components_file'; //.time().'_'.mt_rand('000000000','999999999');
                $path['id'] = $docid;
                $path['tmp_name'] = $document->getRealPath();
                $path['extension']  = $document->getClientOriginalExtension();
                $path['name'] = $doc_id.'_'.$path['id'].'.'.$path['extension'];
                if(is_null($rev)){
                    $dummy_data = array(
                        'scheme_id' => $doc_id
                    );
                    $out = $extended->createDocument($dummy_data,$this->envirment['database'],$doc_id);
                    $array = json_decode($out, true);
                    $id = $array['id'] ?? null;
                    $rev = $array['rev'] ?? null;
                    $data['scheme_id'] = $scheme_id;
                    $data['couch_doc_id'] = $id;
                    $data['couch_rev_id'] = $rev;
                    $attachment = Attachment::create($data);
                }
                $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                $array = json_decode($out, true);
                $rev = $array['rev'] ?? null;
                if(isset($rev)) {
                    $result = Attachment::where('scheme_id',$scheme_id)->update(['couch_rev_id'=>$rev]);
                }                    
                $filecomponent = $path['name'];
            }else{
                $filecomponent = $request->existing_next_scheme_components_file ?? null;
            }

            // ---------------------------------------------------------
            // ✅ Process HOD Array (same as insert)
            // ---------------------------------------------------------
            $hod_office_array = json_decode($request->hod_office, true);
            $implementing_office_contact = $hod_names = $hod_emails = $hod_mobiles = [];

            if (is_array($hod_office_array)) {
                foreach ($hod_office_array as $hod) {
                    $implementing_office_contact[] = $hod['implementing_office_contact'] ?? '';
                    $hod_names[] = $hod['hod_officer_name'] ?? '';
                    $hod_emails[] = $hod['hod_email'] ?? '';
                    $hod_mobiles[] = $hod['hod_mobile'] ?? '';
                }
            }

            $implementing_office_contact = implode(',', $implementing_office_contact);
            $hod_names_str = implode(',', $hod_names);
            $hod_emails_str = implode(',', $hod_emails);
            $hod_mobiles_str = implode(',', $hod_mobiles);

            // ---------------------------------------------------------
            // ✅ Process Implementing Office (array → comma separated)
            // ---------------------------------------------------------
            $implementing_office = is_array($request->implementing_office)
                ? implode(',', $request->implementing_office)
                : $request->implementing_office;

            // ---------------------------------------------------------
            // ✅ Prepare Update Data
            // ---------------------------------------------------------
            $arr = [
                "implementing_office" => $implementing_office,
                "both_ration" => $request->both_ration,
                "other_ratio" => $request->other_ratio,
                "nodal_officer_name" => $request->nodal_officer_name,
                "nodal_officer_designation" => $request->nodal_officer_designation,
                "nodal_officer_contact" => $request->nodal_officer_contact,
                "nodal_officer_mobile" => $request->nodal_officer_mobile,
                "nodal_officer_email" => $request->nodal_officer_email,
                "state_ratio" => $request->state_ratio,
                "center_ratio" => $request->center_ratio,
                "scheme_overview" => $request->scheme_overview,
                "scheme_objective" => $request->scheme_objective,
                "sub_scheme" => $request->sub_scheme,

                // ✅ HOD-related fields
                "hod_officer_name" => $hod_names_str,
                "hod_email" => $hod_emails_str,
                "hod_mobile" => $hod_mobiles_str,
                "implementing_office_contact" => $implementing_office_contact,

                // ✅ Uploaded files
                "next_scheme_overview_file" => $filename,
                "scheme_objective_file" => $filetype,
                "next_scheme_components_file" => $filecomponent,
            ];

          
                Scheme::where('scheme_id',$scheme_id)->update($arr);
                Proposal::where('draft_id',$draft_id)->update($arr);
            return response()->json('updated successfully');
        } else if($slide == 'sixth') {
            $data = $request->all();
            unset($data['_token']);
            unset($data['slide']);
            $arr = [
                "commencement_year" => $request->commencement_year,
                "scheme_status" => $request->scheme_status,
                "is_sdg" => $request->is_sdg
            ];
            if(Session::has('scheme_id') && Session::has('draft_id')){
                $scheme_id = Session::get('scheme_id');
                $draft_id = Session::get('draft_id');
            }else{
                $scheme_id = $request->scheme_id;
                $draft_id = $request->draft_id;
            }
            Scheme::where('scheme_id',$scheme_id)->update($arr);
            Proposal::where('draft_id',$draft_id)->update($arr);
            return response()->json('updated successfully');
        } else if($slide == 'seventh') {
            $data = $request->all();
            
            unset($data['_token']);
            unset($data['slide']);
            if(Session::has('scheme_id') && Session::has('draft_id')){
                $scheme_id = Session::get('scheme_id');
                $draft_id = Session::get('draft_id');
            }else{
                $scheme_id = $request->scheme_id;
                $draft_id = $request->draft_id;
            }
            $filename = "";
            if($request->hasFile('beneficiary_selection_criteria_file')) {
                  $request->validate([
                        'beneficiary_selection_criteria_file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx',
                    ]);
                    
                    $document = $request->file('beneficiary_selection_criteria_file');
                    PdfSecurityService::check($document);
                $rev = Attachment::where('scheme_id',$scheme_id)->value('couch_rev_id');
                $extended = new Couchdb();
                $extended->InitConnection();
                $status = $extended->isRunning();
                $doc_id = "scheme_".$scheme_id;
                $docid = 'beneficiary_selection_criteria_file'; //.time().'_'.mt_rand('000000000','999999999');
                $path['id'] = $docid;
                $path['tmp_name'] = $document->getRealPath();
                $path['extension']  = $document->getClientOriginalExtension();
                $path['name'] = $doc_id.'_'.$path['id'].'.'.$path['extension'];
                if(is_null($rev)){
                    $dummy_data = array(
                        'scheme_id' => $doc_id
                    );
                    $out = $extended->createDocument($dummy_data,$this->envirment['database'],$doc_id);
                    $array = json_decode($out, true);
                    $id = $array['id'] ?? null;
                    $rev = $array['rev'] ?? null;
                    $data['scheme_id'] = $scheme_id;
                    $data['couch_doc_id'] = $id;
                    $data['couch_rev_id'] = $rev;
                    $attachment = Attachment::create($data);
                }
                $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                $array = json_decode($out, true);
                $rev = $array['rev'] ?? null;
                if(isset($rev)) {
                    $result = Attachment::where('scheme_id',$scheme_id)->update(['couch_rev_id'=>$rev]);
                }                    
                $filename = $path['name'];
            }else{
                $filename = $request->existing_beneficiary_selection_criteria_file ?? null;
            }

            $arr = [
                "scheme_beneficiary_selection_criteria" => $request->scheme_beneficiary_selection_criteria,
                'beneficiary_selection_criteria_file' => $filename,
            ];
           
            // return $arr; die();
           
            Scheme::where('scheme_id',$scheme_id)->update($arr);
            Proposal::where('draft_id',$draft_id)->update($arr);
            return response()->json('updated successfully');
        } else if($slide == 'eighth') {
          
            $filename = '';
            if(Session::has('scheme_id') && Session::has('draft_id')){
                $scheme_id = Session::get('scheme_id');
                $draft_id = Session::get('draft_id');
            }else{
                $scheme_id = $request->scheme_id;
                $draft_id = $request->draft_id;
            }
            $major_benefits_text = $request->input('major_benefits_text');
            if($request->hasFile('major_benefits')) {
                 $request->validate([
                        'major_benefits' => 'required|file|mimes:pdf,doc,docx,xls,xlsx',
                    ]);
                    
                $document = $request->file('major_benefits');
                PdfSecurityService::check($document);
                $rev = Attachment::where('scheme_id',$scheme_id)->value('couch_rev_id');
                $extended = new Couchdb();
                $extended->InitConnection();
                $status = $extended->isRunning();
                $doc_id = "scheme_".$scheme_id;
                $docid = 'major_benefits'; //.time().'_'.mt_rand('000000000','999999999');
                $path['id'] = $docid;
                $path['tmp_name'] = $document->getRealPath();
                $path['extension']  = $document->getClientOriginalExtension();
                $path['name'] = $doc_id.'_'.$path['id'].'.'.$path['extension'];
                if(is_null($rev)){
                    $dummy_data = array(
                        'scheme_id' => $doc_id
                    );
                    $out = $extended->createDocument($dummy_data,$this->envirment['database'],$doc_id);
                    $array = json_decode($out, true);
                    $id = $array['id'] ?? null;
                    $rev = $array['rev'] ?? null;
                    $data['scheme_id'] = $scheme_id;
                    $data['couch_doc_id'] = $id;
                    $data['couch_rev_id'] = $rev;
                    $attachment = Attachment::create($data);
                }
                $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                $array = json_decode($out, true);
                $rev = $array['rev'] ?? null;
                if(isset($rev)) {
                    $result = Attachment::where('scheme_id',$scheme_id)->update(['couch_rev_id'=>$rev]);
                }                    
                $filename = $path['name'];

                $arr = array('major_benefits_text'=>$major_benefits_text,'benefit_to_file'=>$filename);
                Scheme::where('scheme_id',$scheme_id)->update($arr);
              
                Proposal::where('draft_id',$draft_id)->update($arr);
            } else {
                $arr = array('major_benefits_text'=>$major_benefits_text);
                Scheme::where('scheme_id',$scheme_id)->update($arr);
                Proposal::where('draft_id',$draft_id)->update($arr);
            }
            return response()->json('updated successfully');
        } else if($slide == 'nineth') {
			$scheme_id = Session::get('scheme_id', $request->scheme_id);
            $draft_id  = Session::get('draft_id', $request->draft_id);

            $scheme_implementing_procedure = $request->scheme_implementing_procedure;
            $beneficiariesGeoLocal= $request->beneficiariesGeoLocal;
            $otherbeneficiariesGeoLocal = $request->otherbeneficiariesGeoLocal;
            $implementing_procedure = $request->implementing_procedure;

            $districts = json_encode($request->district_name ?? []);
            $talukas   = json_encode($request->taluka_name ?? []);
            $states    = json_encode($request->state_name ?? []);
            $taluka_id = $request->taluka_id ?? null;

            $implementing_procedure_file = null;
            $scheme_implement_file = null;

            /* ================= FILE UPLOAD ================= */
            if ($request->hasFile('implementing_procedure_file')) {
				$request->validate([
                        'implementing_procedure_file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx',
                    ]);
                $document = $request->file('implementing_procedure_file');
				PdfSecurityService::check($document);
                $rev = Attachment::where('scheme_id', $scheme_id)->value('couch_rev_id');

                $extended = new Couchdb();
                $extended->InitConnection();

                $doc_id = "scheme_" . $scheme_id;
                $docid  = 'implementing_procedure_file';

                $path = [
                    'id'        => $docid,
                    'tmp_name'  => $document->getRealPath(),
                    'extension' => $document->getClientOriginalExtension(),
                    'name'      => $doc_id . '_' . $docid . '.' . $document->getClientOriginalExtension()
                ];

                if (!$rev) {
                    $out = $extended->createDocument(['scheme_id' => $doc_id], $this->envirment['database'], $doc_id);
                    $array = json_decode($out, true);

                    Attachment::create([
                        'scheme_id'    => $scheme_id,
                        'couch_doc_id' => $array['id'] ?? null,
                        'couch_rev_id' => $array['rev'] ?? null
                    ]);

                    $rev = $array['rev'] ?? null;
                }

                $out = $extended->createAttachmentDocument(
                    $this->envirment['database'],
                    $doc_id,
                    $rev,
                    $path
                );

                $array = json_decode($out, true);

                if (!empty($array['rev'])) {
                    Attachment::where('scheme_id', $scheme_id)
                        ->update(['couch_rev_id' => $array['rev']]);
                }

                $implementing_procedure_file = $path['name'];
            }

            if ($request->hasFile('scheme_implement_file')) {
				$request->validate([
                        'scheme_implement_file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx',
                    ]);
                $document = $request->file('scheme_implement_file');
				PdfSecurityService::check($document);
                $rev = Attachment::where('scheme_id', $scheme_id)->value('couch_rev_id');

                $extended = new Couchdb();
                $extended->InitConnection();

                $doc_id = "scheme_" . $scheme_id;
                $docid  = 'scheme_implement_file';

                $path = [
                    'id'        => $docid,
                    'tmp_name'  => $document->getRealPath(),
                    'extension' => $document->getClientOriginalExtension(),
                    'name'      => $doc_id . '_' . $docid . '.' . $document->getClientOriginalExtension()
                ];

                if (!$rev) {
                    $out = $extended->createDocument(['scheme_id' => $doc_id], $this->envirment['database'], $doc_id);
                    $array = json_decode($out, true);

                    Attachment::create([
                        'scheme_id'    => $scheme_id,
                        'couch_doc_id' => $array['id'] ?? null,
                        'couch_rev_id' => $array['rev'] ?? null
                    ]);

                    $rev = $array['rev'] ?? null;
                }

                $out = $extended->createAttachmentDocument(
                    $this->envirment['database'],
                    $doc_id,
                    $rev,
                    $path
                );

                $array = json_decode($out, true);

                if (!empty($array['rev'])) {
                    Attachment::where('scheme_id', $scheme_id)
                        ->update(['couch_rev_id' => $array['rev']]);
                }

                $scheme_implement_file = $path['name'];
            }

           // $filename = '';

            // if($request->hasFile('geographical_coverage')) {
				
			// 		$request->validate([
            //             'geographical_coverage' => 'required|file|mimes:pdf,doc,docx,xls,xlsx',
            //         ]);
                    
            //      $document = $request->file('geographical_coverage');
            //      PdfSecurityService::check($document);
                       
            //             $rev = Attachment::where('scheme_id',$scheme_id)->value('couch_rev_id');
            //             $extended = new Couchdb();
            //             $extended->InitConnection();
            //             $status = $extended->isRunning();
            //             $doc_id = "scheme_".$scheme_id;
            //             $docid = 'geographical_coverage'; //.time().'_'.mt_rand('000000000','999999999');
            //             $path['id'] = $docid;
            //             $path['tmp_name'] = $document->getRealPath();
            //             $path['extension']  = $document->getClientOriginalExtension();
            //             $path['name'] = $doc_id.'_'.$path['id'].'.'.$path['extension'];
            //             if(is_null($rev)){
            //                 $dummy_data = array(
            //                     'scheme_id' => $doc_id
            //                 );
            //                 $out = $extended->createDocument($dummy_data,$this->envirment['database'],$doc_id);
            //                 $array = json_decode($out, true);
            //                 $id = $array['id'] ?? null;
            //                 $rev = $array['rev'] ?? null;
            //                 $data['scheme_id'] = $scheme_id;
            //                 $data['couch_doc_id'] = $id;
            //                 $data['couch_rev_id'] = $rev;
            //                 $attachment = Attachment::create($data);
            //             }
            //             $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
            //             $array = json_decode($out, true);
            //             $rev = $array['rev'] ?? null;
            //             if(isset($rev)) {
            //                 $result = Attachment::where('scheme_id',$scheme_id)->update(['couch_rev_id'=>$rev]);
            //             }                    
            //             $filename = $path['name'];
            // }
            /* ================= UPDATE ================= */

            $arr = [
                'scheme_implementing_procedure' => $scheme_implementing_procedure,
                'beneficiariesGeoLocal'         => $beneficiariesGeoLocal,
                'districts'                     => $districts,
                'states'                        => $states,
                'talukas'                       => $talukas,
                'taluka_id'                     => $taluka_id,
                'otherbeneficiariesGeoLocal'    => $otherbeneficiariesGeoLocal,
                'implementing_procedure'        => $implementing_procedure,
               // 'implementing_procedure_file'   => $implementing_procedure_file,
               // 'geographical_coverage'         => $filename
            ];
                if ($implementing_procedure_file) {
                    $arr['implementing_procedure_file'] = $implementing_procedure_file;
                }else{
                    $arr['implementing_procedure_file'] = $request->existing_implementing_procedure_file ?? null;
                }

                if ($scheme_implement_file) {
                    $arr['scheme_implement_file'] = $scheme_implement_file;
                }else{
                    $arr['scheme_implement_file'] = $request->existing_scheme_implement_file ?? null;
                }

                // if ($filename) {
                //     $arr['geographical_coverage'] = $filename;
                // }
            Scheme::where('scheme_id', $scheme_id)->update($arr);
            Proposal::where('draft_id', $draft_id)->update($arr);

            return response()->json('updated successfully');
			
            // if(Session::has('scheme_id') && Session::has('draft_id')){
                // $scheme_id = Session::get('scheme_id');
                // $draft_id = Session::get('draft_id');
            // }else{
                // $scheme_id = $request->scheme_id;
                // $draft_id = $request->draft_id;
            // }
            // $scheme_implementing_procedure = $request->input('scheme_implementing_procedure');
            // $beneficiariesGeoLocal = $request->input('beneficiariesGeoLocal');
            // $districts = json_encode($request->input('district_name'));
            // $talukas = json_encode($request->input('taluka_name'));
            // $states = json_encode($request->input('state_name'));
            // $taluka_id = isset($request->taluka_id) ? $request->taluka_id : 'null';
           
            // $otherbeneficiariesGeoLocal = $request->input('otherbeneficiariesGeoLocal');
            // $filename = '';

            // if($request->hasFile('geographical_coverage')) {
                // $request->validate([
                        // 'geographical_coverage' => 'required|file|mimes:pdf,doc,docx,xls,xlsx',
                    // ]);
                    
                // $document = $request->file('geographical_coverage');
                // PdfSecurityService::check($document);
                // $rev = Attachment::where('scheme_id',$scheme_id)->value('couch_rev_id');
                // $extended = new Couchdb();
                // $extended->InitConnection();
                // $status = $extended->isRunning();
                // $doc_id = "scheme_".$scheme_id;
                // $docid = 'geographical_coverage'; //.time().'_'.mt_rand('000000000','999999999');
                // $path['id'] = $docid;
                // $path['tmp_name'] = $document->getRealPath();
                // $path['extension']  = $document->getClientOriginalExtension();
                // $path['name'] = $doc_id.'_'.$path['id'].'.'.$path['extension'];
                // if(is_null($rev)){
                    // $dummy_data = array(
                        // 'scheme_id' => $doc_id
                    // );
                    // $out = $extended->createDocument($dummy_data,$this->envirment['database'],$doc_id);
                    // $array = json_decode($out, true);
                    // $id = $array['id'] ?? null;
                    // $rev = $array['rev'] ?? null;
                    // $data['scheme_id'] = $scheme_id;
                    // $data['couch_doc_id'] = $id;
                    // $data['couch_rev_id'] = $rev;
                    // $attachment = Attachment::create($data);
                // }
                // $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                // $array = json_decode($out, true);
                // $rev = $array['rev'] ?? null;
                // if(isset($rev)) {
                    // $result = Attachment::where('scheme_id',$scheme_id)->update(['couch_rev_id'=>$rev]);
                // }                    
                // $filename = $path['name'];

                // $arr = array(
                    // 'scheme_implementing_procedure'=>$scheme_implementing_procedure, 
                    // 'beneficiariesGeoLocal'=>$beneficiariesGeoLocal, 
                    // 'districts'=>$districts,
                    // 'states'=>$states,
                    // 'talukas'=>$talukas, 
                    // 'taluka_id' =>$taluka_id,
                    // 'otherbeneficiariesGeoLocal'=>$otherbeneficiariesGeoLocal,
                    // 'geographical_coverage'=>$filename
                // );
              
                // Scheme::where('scheme_id',$scheme_id)->update($arr);
                // Proposal::where('draft_id',$draft_id)->update($arr);
            // } else {
                // $arr = array(
                    // 'scheme_implementing_procedure'=>$scheme_implementing_procedure, 
                    // 'beneficiariesGeoLocal'=>$beneficiariesGeoLocal, 
                    // 'districts'=>$districts,
                    // 'states'=>$states,
                    // 'talukas'=>$talukas, 
                    // 'taluka_id' =>$taluka_id,
                    // 'otherbeneficiariesGeoLocal'=>$otherbeneficiariesGeoLocal,
                // );
             
                // Scheme::where('scheme_id', $scheme_id)->update($arr);
                // Proposal::where('draft_id',$draft_id)->update($arr);
            // }
            // return response()->json('updated successfully');
        } else if($slide == 'tenth') {
           
            if(Session::has('scheme_id') && Session::has('draft_id')){
                $scheme_id = Session::get('scheme_id');
                $draft_id = Session::get('draft_id');
            }else{
                $scheme_id = $request->scheme_id;
                $draft_id = $request->draft_id;
            }
            
            $coverage_beneficiaries_remarks = $request->input('coverage_beneficiaries_remarks');
            $training_capacity_remarks = $request->input('training_capacity_remarks');
            $iec_activities_remarks = $request->input('iec_activities_remarks');
            $beneficiaries_coverage_file_name = '';
            if($request->has('beneficiaries_coverage')) {
                $request->validate([
                        'beneficiaries_coverage' => 'required|file|mimes:pdf,doc,docx,xls,xlsx',
                    ]);
                    
                $beneficiaries_coverage = $request->file('beneficiaries_coverage');
                PdfSecurityService::check($beneficiaries_coverage);
                $rev = Attachment::where('scheme_id',$scheme_id)->value('couch_rev_id');
             
                $extended = new Couchdb();
                $extended->InitConnection();
                $status = $extended->isRunning();
                $doc_id = "scheme_".$scheme_id;
                $docid = 'beneficiaries_coverage'; //.time().'_'.mt_rand('0000000000','9999999999');
                $path['id'] = $docid;
                $path['tmp_name'] = $beneficiaries_coverage->getRealPath();
                $path['extension']  = $beneficiaries_coverage->getClientOriginalExtension();
                $path['name'] = $doc_id.'_'.$path['id'].'.'.$path['extension'];
                if(is_null($rev)){
                    $dummy_data = array(
                        'scheme_id' => $doc_id
                    );
                    $out = $extended->createDocument($dummy_data,$this->envirment['database'],$doc_id);
                    $array = json_decode($out, true);
                    $id = $array['id'] ?? null;
                    $rev = $array['rev'] ?? null;
                    $data['scheme_id'] = $scheme_id;
                    $data['couch_doc_id'] = $id;
                    $data['couch_rev_id'] = $rev;
                    $attachment = Attachment::create($data);
                }

                $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                $array = json_decode($out, true);
                $rev = $array['rev'] ?? null;
                if(isset($rev)) {
                    $result = Attachment::where('scheme_id',$scheme_id)->update(['couch_rev_id'=>$rev]);
                }                    
                $beneficiaries_coverage_file_name = $path['name'];

                $arr = array('beneficiaries_coverage'=>$beneficiaries_coverage_file_name);
                Scheme::where('scheme_id',$scheme_id)->update($arr);
               
                Proposal::where('draft_id',$draft_id)->update($arr);
            }
            $training_file_name = '';
            if($request->has('training')) {
                $training = $request->file('training');
                $rev = Attachment::where('scheme_id',$scheme_id)->value('couch_rev_id');
                $extended = new Couchdb();
                $extended->InitConnection();
                $status = $extended->isRunning();
                $doc_id = "scheme_".$scheme_id;
                $docid = 'training'; //.time().'_'.mt_rand('0000000000','9999999999');
                $path['id'] = $docid;
                $path['tmp_name'] = $training->getRealPath();
                $path['extension']  = $training->getClientOriginalExtension();
                $path['name'] = $doc_id.'_'.$path['id'].'.'.$path['extension'];
                if(is_null($rev)){
                    $dummy_data = array(
                        'scheme_id' => $doc_id
                    );
                    $out = $extended->createDocument($dummy_data,$this->envirment['database'],$doc_id);
                    $array = json_decode($out, true);
                    $id = $array['id'] ?? null;
                    $rev = $array['rev'] ?? null;
                    $data['scheme_id'] = $scheme_id;
                    $data['couch_doc_id'] = $id;
                    $data['couch_rev_id'] = $rev;
                    $attachment = Attachment::create($data);
                }
                $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                $array = json_decode($out, true);
                $rev = $array['rev'] ?? null;
                if(isset($rev)) {
                    $result = Attachment::where('scheme_id',$scheme_id)->update(['couch_rev_id'=>$rev]);
                }                    
                $training_file_name = $path['name'];

                $arr = array('training'=>$training_file_name);
                Scheme::where('scheme_id',$scheme_id)->update($arr);
                
                Proposal::where('draft_id',$draft_id)->update($arr);
            }
            $iec_file_name = '';
            if($request->has('iec_file')) {
                $iec_file = $request->file('iec_file');
                $rev = Attachment::where('scheme_id',$scheme_id)->value('couch_rev_id');
                $extended = new Couchdb();
                $extended->InitConnection();
                $status = $extended->isRunning();
                $doc_id = "scheme_".$scheme_id;
                $docid = 'iec'; //.time().'_'.mt_rand('0000000000','9999999999');
                $path['id'] = $docid;
                $path['tmp_name'] = $iec_file->getRealPath();
                $path['extension']  = $iec_file->getClientOriginalExtension();
                $path['name'] = $doc_id.'_'.$path['id'].'.'.$path['extension'];
                if(is_null($rev)){
                    $dummy_data = array(
                        'scheme_id' => $doc_id
                    );
                    $out = $extended->createDocument($dummy_data,$this->envirment['database'],$doc_id);
                    $array = json_decode($out, true);
                    $id = $array['id'] ?? null;
                    $rev = $array['rev'] ?? null;
                    $data['scheme_id'] = $scheme_id;
                    $data['couch_doc_id'] = $id;
                    $data['couch_rev_id'] = $rev;
                    $attachment = Attachment::create($data);
                }
                $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                $array = json_decode($out, true);
                $rev = $array['rev'] ?? null;
                if(isset($rev)) {
                    $result = Attachment::where('scheme_id',$scheme_id)->update(['couch_rev_id'=>$rev]);
                }                    
                $iec_file_name = $path['name'];

                $arr = array('iec'=>$iec_file_name);
                Scheme::where('scheme_id',$scheme_id)->update($arr);
              
                Proposal::where('draft_id',$draft_id)->update($arr);
            }
            $arr = array('coverage_beneficiaries_remarks'=>$coverage_beneficiaries_remarks, 'training_capacity_remarks'=>$training_capacity_remarks, 'iec_activities_remarks'=>$iec_activities_remarks);
            Scheme::where('scheme_id',$scheme_id)->update($arr);
            Proposal::where('draft_id',$draft_id)->update($arr);
            return response()->json('updated successfully');
        } else if($slide == 'eleventh') {
            
            $benefit_to = $request->input('benefit_to');
            $all_convergence = $request->input('all_convergence');
            $arr = array('benefit_to'=>$benefit_to,'all_convergence'=>$all_convergence);
            if(Session::has('scheme_id') && Session::has('draft_id')){
                $scheme_id = Session::get('scheme_id');
                $draft_id = Session::get('draft_id');
            }else{
                $scheme_id = $request->scheme_id;
                $draft_id = $request->draft_id;
            }
             Scheme::where('scheme_id',$scheme_id)->update($arr);
             Proposal::where('draft_id',$draft_id)->update($arr);
            return response()->json('updated successfully');
        } else if($slide == 'twelth'){
            if(Session::has('scheme_id') && Session::has('draft_id')){
                $scheme_id = Session::get('scheme_id');
                $draft_id = Session::get('draft_id');
            } else {
                $scheme_id = $request->scheme_id;
                $draft_id = $request->draft_id;
            }

            $doc_id = "scheme_" . $scheme_id;
            $extended = new Couchdb();
            $extended->InitConnection();

            // 1. Initialize or Get CouchDB Attachment Record
            $fileEntry = Attachment::where('scheme_id', $scheme_id)->first();
            if (!$fileEntry) {
                $out = $extended->createDocument(['scheme_id' => $doc_id], $this->envirment['database'], $doc_id);
                $array = json_decode($out, true);
                $fileEntry = Attachment::create([
                    'scheme_id' => $scheme_id,
                    'couch_doc_id' => $doc_id,
                    'couch_rev_id' => $array['rev'] ?? null,
                ]);
            }
            $rev = $fileEntry->couch_rev_id;

            // 2. Define all multi-file fields
            $fileCategories = [
                'gr' => GrFilesList::class,
                'notification' => NotificationFileList::class,
                'brochure' => BrochureFileList::class,
                'pamphlets' => PamphletFileList::class,
                'otherdetailscenterstate' => CenterStateFiles::class 
            ];

            // --- STEP 1: SECURITY CHECK (DO NOT REMOVE) ---
            // We check EVERY file from EVERY category before starting any database operations
            foreach ($fileCategories as $inputName => $model) {
                if ($request->hasFile($inputName)) {
                    foreach ($request->file($inputName) as $file) {
                        if ($file->getClientOriginalExtension() == 'pdf') {
                            if (!PdfSecurityService::check($file, false)) {
                                return response()->json([
                                    'error' => "Security Alert: The file '" . $file->getClientOriginalName() . "' contains scripts and is not allowed."
                                ], 422);
                            }
                        }
                    }
                }
            }

            // Check Beneficiary Form Security
            if ($request->input('beneficiary_filling_form_type') === '0' &&     $request->hasFile('beneficiary_filling_form')) {
                foreach ($request->file('beneficiary_filling_form') as $ben_file) {
                    if ($ben_file->getClientOriginalExtension() == 'pdf') {
                        if (!PdfSecurityService::check($ben_file, false)) {
                            return response()->json([
                                'error' => "Security Alert: The Beneficiary Form '" . $ben_file->getClientOriginalName() . "' contains scripts."
                            ], 422);
                        }
                    }
                }
            }

            // --- STEP 2: PROCESSING ---
            foreach ($fileCategories as $inputName => $model) {
                
                if($request->hasFile($inputName)) {
                    // Validate basic requirements
                    $request->validate(["$inputName.*" => 'file|mimes:pdf,docx,xlsx']);
                    
                    // Clean up old SQL records for this category
                  // $model::where('scheme_id', $scheme_id)->delete();
                    
                    foreach($request->file($inputName) as $key => $file) {
                        $file_ext = $file->getClientOriginalExtension();
                        $file_name = $doc_id . '_' . $inputName . '_' . ($key + 1) . '.' . $file_ext;
                        
                        $path = [
                            'id' => $inputName . '_' . ($key + 1),
                            'tmp_name' => $file->getRealPath(),
                            'extension' => $file_ext,
                            'name' => $file_name
                        ];
                        
                        $out = $extended->createAttachmentDocument($this->envirment['database'], $doc_id, $rev, $path);
                        $res = json_decode($out, true);
                        
                        if (!empty($res['rev'])) {
                            $rev = $res['rev']; // Update revision for next file
                        }

                        // Save to SQL
                        $model::create([
                            'file_name' => $file_name,
                            'scheme_id' => $scheme_id,
                            'created_at' => now(),
                        ]);
                    }
                }
            }

            // 3. Handle Beneficiary Filling Form (Multi-file support)
            $beneficiaryType = $request->input('beneficiary_filling_form_type');
            if ($beneficiaryType === '0' && $request->hasFile('beneficiary_filling_form')) {
                $request->validate(['beneficiary_filling_form.*' => 'file|mimes:pdf,docx,xlsx']);
                
                $beneficiaryFileNames = [];
                foreach($request->file('beneficiary_filling_form') as $key => $ben_file) {
                    $file_ext = $ben_file->getClientOriginalExtension();
                    $file_name = $doc_id . '_ben_form_' . ($key + 1) . '.' . $file_ext;
                    
                    $path = [
                        'id' => 'ben_form_' . ($key + 1),
                        'tmp_name' => $ben_file->getRealPath(),
                        'extension' => $file_ext,
                        'name' => $file_name
                    ];
                    
                    $out = $extended->createAttachmentDocument($this->envirment['database'], $doc_id, $rev, $path);
                    $res = json_decode($out, true);
                    if (!empty($res['rev'])) {
                        $rev = $res['rev'];
                    }
                    $beneficiaryFileNames[] = $file_name;
                }
                $arr['beneficiary_filling_form'] = implode(',', $beneficiaryFileNames);
            } else {
                $arr['beneficiary_filling_form'] = $request->existing_beneficiary_filling_form;
            }

            // 4. Update Final Revision and Main Tables
            Attachment::where('scheme_id', $scheme_id)->update(['couch_rev_id' => $rev]);

            $arr['beneficiary_filling_form_type'] = $beneficiaryType;
            Scheme::where('scheme_id', $scheme_id)->update($arr);
            Proposal::where('draft_id', $draft_id)->update($arr);

            return response()->json('updated successfully');
      
        }else if($slide == 'thirteenth') {
            $data = $request->all();
            unset($data['_token']);
            unset($data['slide']);
            $arr =[
                "major_indicator_hod" => $request->major_indicator_hod,
            ];
            if(Session::has('scheme_id') && Session::has('draft_id')){
                $scheme_id = Session::get('scheme_id');
                $draft_id = Session::get('draft_id');
            }else{
                $scheme_id = $request->scheme_id;
                $draft_id = $request->draft_id;
            }
            if($scheme_id != '') {
                Scheme::where('scheme_id',$scheme_id)->update($arr);
                Proposal::where('draft_id',$draft_id)->update($arr);
            }
            return response()->json('updated successfully');
        } else if($slide == 'fourteenth') {
            $tr_array = $request->input('tr_array');
            $fin_progress_remarks = $request->input('financial_progress_remarks');
            $arr = array();
            if(Session::has('scheme_id') && Session::has('draft_id')){
                $scheme_id = Session::get('scheme_id');
                $draft_id = Session::get('draft_id');
            }else{
                $scheme_id = $request->scheme_id;
                $draft_id = $request->draft_id;
            }
            $fn_year = '';
            foreach($tr_array as $tr) {
                $fn_year = $tr['financial_year'];
                $arr[] = array('scheme_id'=>$scheme_id,'financial_year'=>$tr['financial_year'], 'target'=>$tr['target'],'achievement'=>$tr['achievement'], 'allocation'=>$tr['allocation'], 'expenditure'=>$tr['expenditure'],'selection' => $tr['selection']);
            }

            if($fn_year == '' or $scheme_id == '' or $draft_id == '') {

            } else if($scheme_id != '' and $fn_year != '' and $draft_id != '') {
                FinancialProgress::where('scheme_id',$scheme_id)->delete();
                FinancialProgress::insert($arr);
                Scheme::where('scheme_id',$scheme_id)->update(['fin_progress_remarks'=>$fin_progress_remarks]);
                Proposal::where('draft_id',$draft_id)->update(['fin_progress_remarks'=>$fin_progress_remarks]);
                return response()->json('added successfully');
            }
        } 
        $act['userid'] = Auth::user()->id;
        $act['ip'] = $request->ip();
        $act['activity'] = 'Scheme Update By Concern Department';
        $act['officecode'] = Auth::user()->dept_id;
        $act['pagereferred'] = $request->url();
        Activitylog::insert($act);
    }

    public function deleteFile(Request $request) {
        $id = $request->id;             // The Primary Key (ID) of the file record (e.g., 25)
        $couchId = $request->couch_id;   // The CouchDB Document ID (e.g., "scheme_33")
        $filename = $request->filename; // The actual filename (e.g., "scheme_33_gr_1.pdf")
        $type = strtolower(trim($request->type));

        try {
            $extended = new Couchdb();
            $extended->InitConnection();

            // 1. Fetch the absolute latest revision from CouchDB to prevent 409 Conflicts
            $docResponse = $extended->getDocument($this->envirment['database'], $couchId);
            $document = json_decode($docResponse, true);

            if (isset($document['_rev'])) {
                // 2. Delete the specific attachment from CouchDB
                $path = sprintf('/%s/%s/%s?rev=%s',  $this->envirment['database'], $couchId, $filename, $document['_rev']);
                
                $delete_response = $extended->executeCustomDelete($path);
                $resArray = json_decode($delete_response, true);

                if (isset($resArray['ok']) && $resArray['ok']) {
                    $newRev = $resArray['rev']; // New revision after attachment is gone

                    // 3. REMOVE FROM DATABASE (Specific File List)
                    $fileModelMap = [
                        'gr'           => GrFilesList::class,
                        'notification' => NotificationFileList::class,
                        'brochure'     => BrochureFileList::class,
                        'pamphlets'    => PamphletFileList::class,
                        'otherdetails' => CenterStateFiles::class,
                    ];

                    if (!isset($fileModelMap[$type])) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Invalid file type'
                        ]);
                    }
                    
                    if (array_key_exists($type, $fileModelMap)) {
                        $fileModel = $fileModelMap[$type];

                        $deleted = $fileModel::where('id', $id)->delete();

                        if ($deleted == 0) {
                            return response()->json([
                                    'success' => false,
                                    'message' => 'Record not found in database'
                                ]);

                        }
                    }
                    // 4. UPDATE MASTER ATTACHMENT TABLE
                    // This keeps your CouchDB metadata in sync for the next operation
                    \DB::table('itransaction.attachments')
                        ->where('couch_doc_id', $couchId)
                        ->update(['couch_rev_id' => $newRev]);

                    return response()->json(['success' => true]);
                }
            }
            return response()->json(['success' => false, 'message' => 'CouchDB Error', 'details' => $resArray]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function onreload(Request $request) {
        Session::forget('scheme_id');
        Session::forget('draft_id');
        return response()->json('success');
    }

    public function newproposaldetail($draft_id,Request $request) {
        $goals = Sdggoals::where('status','1')->orderBy('goal_id','desc')->get();
       
        //$proposal_list = Proposal::with(['gr_file','notification_files','brochure_files','pamphlets_files','otherdetailscenterstate_files'])->where('draft_id',Crypt::decrypt($draft_id))->get();
        $proposal_list = Proposal::with([
            'gr_file',
            'notification_files',
            'brochure_files',
            'pamphlets_files',
            'otherdetailscenterstate_files'
        ])->where('draft_id', Crypt::decrypt($draft_id))->get();
      
        $replace_url = URL::to('/');
        $dept_name = '';
       // $major_objectives = array();
        $imdept = array();
       // $major_indicator_hod = array();
        $financial_progress = array();
        $beneficiariesGeoLocal = beneficiariesGeoLocal();
        $district_ids = array();
        $taluka_ids = array();
        $dept_name = array();
        $conv_dept_ids = array();
        $conv_dept_remarks = array();
        $entered_goals = array();
        $bencovfile = 'no data';
        $trainingfile = 'no data';
        $iecfile = 'no data';
        $eval_report = 'no data';
        $gr_files = 'no data';
        $notification_files = 'no data';
        $brochure_files = 'no data';
        $pamphlets_files = 'no data';
        $otherdetailscenterstate_files = 'no data';
        $all_convergence = array();
        foreach($proposal_list as $key=>$value) {
            $scheme_id = $value->scheme_id;
            $dept_name = Department::where('dept_id',$value->dept_id)->value('dept_name');
          
            $imdept = Implementation::where('id',$value->im_id)->get();
           
            $financial_progress = FinancialProgress::where('scheme_id',$value->scheme_id)->get();
            if($value->districts != 'null' or $value->districts != '') {
                $district_ids = json_decode($value->districts);
            }
            if($value->talukas != 'null' or $value->talukas != '') {
                $taluka_ids = json_decode($value->talukas);
            }
         
            if($value->is_sdg) {
                $entered_goals = json_decode($value->is_sdg);
            }
            if($value->all_convergence) {
                $all_convergence = json_decode($value->all_convergence);
            }
            $bencovfile = $this->getthefilecount($scheme_id,'_beneficiaries_coverage');
            $trainingfile = $this->getthefilecount($scheme_id,'_training');
            $iecfile = $this->getthefilecount($scheme_id,'_iec');
            $eval_report = $this->getthefilecount($scheme_id,'_eval_report_');
            $gr_files = $this->getthefilecount($scheme_id,'_gr_');
           
            $notification_files = $this->getthefilecount($scheme_id,'_notification');
            $brochure_files = $this->getthefilecount($scheme_id,'_brochure');
            $pamphlets_files = $this->getthefilecount($scheme_id,'_pamphlets');
            $otherdetailscenterstate_files = $this->getthefilecount($scheme_id,'_otherdetailscenterstate');
        }
        $the_convergence = array();
        if(!empty($all_convergence)) {
            foreach($all_convergence as $kc => $vc) {
                $dept_name = Department::where('dept_id',$vc->dept_id)->value('dept_name');
                if($dept_name == '') {
                    $dept_name = 'no department';
                }
                $the_convergence[] = array('dept_name'=>$dept_name,'remarks'=>$vc->dept_remarks);
            }
        }
        $district_names = array();
        if(!empty($district_ids)) {
            foreach($district_ids as $dkey=>$dval) {
                $district_names[] = Districts::where('dcode',$dval)->value('name_e');
            }
        }
        $taluka_names = array();
        if(!empty($taluka_ids)) {
            foreach($taluka_ids as $dkey=>$dval) {
                $taluka_names[] = Taluka::where('tcode',$dval)->value('tname_e');
            }
        }
        $dept_names = array();
        if(count($conv_dept_ids) > 0) {
            foreach($conv_dept_ids as $convkey=>$conval) {
                $dept_is = Department::select('dept_id','dept_name')->where('dept_id',$conval)->get();
                $dept_names[] = $dept_is->toArray();
            }
        }
        $act['userid'] = Auth::user()->id;
        $act['ip'] = $request->ip();
        $act['activity'] = 'Scheme Detail Page.';
        $act['officecode'] = Auth::user()->dept_id;
        $act['pagereferred'] = $request->url();
        Activitylog::insert($act);
        return view('schemes.newproposaldetail',compact('proposal_list','dept_name','imdept','financial_progress','replace_url','scheme_id','beneficiariesGeoLocal','bencovfile','trainingfile','iecfile','district_names','dept_names','taluka_names','conv_dept_remarks','goals','entered_goals','eval_report','gr_files','notification_files','brochure_files','pamphlets_files','otherdetailscenterstate_files','the_convergence'));
    }

    public function proposaldetail($draft_id,Request $request) {
        $goals = Sdggoals::where('status','1')->orderBy('goal_id','desc')->get();
        $proposal_list = Proposal::where('draft_id',Crypt::decrypt($draft_id))->get();
        $replace_url = URL::to('/');
        $dept_name = '';
        $major_objectives = array();
        $imdept = array();
        $major_indicator_hod = array();
        $financial_progress = array();
        $beneficiariesGeoLocal = beneficiariesGeoLocal();
        $district_ids = array();
        $taluka_ids = array();
        $dept_name = array();
        $conv_dept_ids = array();
        $conv_dept_remarks = array();
        $entered_goals = array();
        $bencovfile = 'no data';
        $trainingfile = 'no data';
        $iecfile = 'no data';
        $eval_report = 'no data';
        $gr_files = 'no data';
        $notification_files = 'no data';
        $brochure_files = 'no data';
        $pamphlets_files = 'no data';
        $otherdetailscenterstate_files = 'no data';
        $all_convergence = array();
        foreach($proposal_list as $key=>$value) {
            $scheme_id = $value->scheme_id;
            $dept_name = Department::where('dept_id',$value->dept_id)->value('dept_name');
            if($value->major_objective) {
                $major_objectives = json_decode($value->major_objective);
            }
            if($value->major_indicator) {
                $major_indicators = json_decode($value->major_indicator);
            }
            $imdept = Implementation::where('id',$value->im_id)->get();
            if($value->major_indicator_hod) {
                $major_indicator_hod = json_decode($value->major_indicator_hod);
            }
            $financial_progress = FinancialProgress::where('scheme_id',$value->scheme_id)->get();
            if($value->districts != 'null' or $value->districts != '') {
                $district_ids = json_decode($value->districts);
            }
            if($value->talukas != 'null' or $value->talukas != '') {
                $taluka_ids = json_decode($value->talukas);
            }
            if($value->is_sdg) {
                $entered_goals = json_decode($value->is_sdg);
            }
            if($value->all_convergence) {
                $all_convergence = json_decode($value->all_convergence);
            }
            $bencovfile = $this->getthefilecount($scheme_id,'_beneficiaries_coverage');
            $trainingfile = $this->getthefilecount($scheme_id,'_training');
            $iecfile = $this->getthefilecount($scheme_id,'_iec');
            $eval_report = $this->getthefilecount($scheme_id,'_eval_report_');
            $gr_files = $this->getthefilecount($scheme_id,'_gr_');
            $notification_files = $this->getthefilecount($scheme_id,'_notification');
            $brochure_files = $this->getthefilecount($scheme_id,'_brochure');
            $pamphlets_files = $this->getthefilecount($scheme_id,'_pamphlets');
            $otherdetailscenterstate_files = $this->getthefilecount($scheme_id,'_otherdetailscenterstate');
        }
        $the_convergence = array();
        if(!empty($all_convergence)) {
            foreach($all_convergence as $kc => $vc) {
                $dept_name = Department::where('dept_id',$vc->dept_id)->value('dept_name');
                if($dept_name == '') {
                    $dept_name = 'no department';
                }
                $the_convergence[] = array('dept_name'=>$dept_name,'remarks'=>$vc->dept_remarks);
            }
        }
        $district_names = array();
        if(!empty($district_ids)) {
            foreach($district_ids as $dkey=>$dval) {
                $district_names[] = Districts::where('dcode',$dval)->value('name_e');
            }
        }
        $taluka_names = array();
        if(!empty($taluka_ids)) {
            foreach($taluka_ids as $dkey=>$dval) {
                $taluka_names[] = Taluka::where('tcode',$dval)->value('tname_e');
            }
        }
        $dept_names = array();
        if(count($conv_dept_ids) > 0) {
            foreach($conv_dept_ids as $convkey=>$conval) {
                $dept_is = Department::select('dept_id','dept_name')->where('dept_id',$conval)->get();
                $dept_names[] = $dept_is->toArray();
            }
        }
        $act['userid'] = Auth::user()->id;
        $act['ip'] = $request->ip();
        $act['activity'] = 'Scheme Created';
        $act['officecode'] = Auth::user()->dept_id;
        $act['pagereferred'] = $request->url();
        Activitylog::insert($act);
        return view('schemes.proposaldetail',compact('proposal_list','dept_name','major_objectives','major_indicators','imdept','major_indicator_hod','financial_progress','replace_url','scheme_id','beneficiariesGeoLocal','bencovfile','trainingfile','iecfile','district_names','dept_names','taluka_names','conv_dept_remarks','goals','entered_goals','eval_report','gr_files','notification_files','brochure_files','pamphlets_files','otherdetailscenterstate_files','the_convergence'));
    }

    public function getthefile($id,$scheme) {
        $id = 'scheme_'.Crypt::decrypt($id);
       
        $extended = new Couchdb();
        $extended->InitConnection();
        $status = $extended->isRunning();
        $out = $extended->getDocument($this->envirment['database'],$id);
        $arrays = json_decode($out, true);
        // dd($arrays);
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
                    $cont = file_get_contents($this->envirment['url'].$id."/".$atvalue);
                    if($cont) {
                        //return response($cont)->withHeaders(['Content-type'=>'application/pdf']);
						return response($cont)->withHeaders([
								'Content-Type'        => 'application/pdf',
								'Content-Disposition' => 'attachment; filename="'.$atvalue.'"',
								'X-Content-Type-Options' => 'nosniff',
								'X-Frame-Options' => 'DENY'
						]);
                    } else {
                        return "Error fetching the document. Contact NIC";
                    }
                }
            }
        } else {
            return 'Document not found !';
        }
    }

    public function getthefilecount($id, $scheme) {
       
            $id = 'scheme_' . $id;
            $extended = new Couchdb();
            $extended->InitConnection();
            
            // 1. Fetch data
            $out = $extended->getDocument($this->envirment['database'], $id);
            $arrays = json_decode($out, true);

            // 2. Safety Check: Ensure $arrays is an array and doesn't contain a CouchDB error
            if (!is_array($arrays) || isset($arrays['error'])) {
                return 'no data';
            }

            // 3. Safety Check: Ensure _attachments exists
            if (!isset($arrays['_attachments']) || !is_array($arrays['_attachments'])) {
                return "no data";
            }

            $countfiles = array();
            $k = 1;

            // 4. Combine logic into a single loop for efficiency
            foreach ($arrays['_attachments'] as $attachment_name => $attachment_info) {
                if (strpos($attachment_name, $scheme) !== false) {
                    $countfiles[] = $k;
                    $k++;
                }
            }
 
            return (count($countfiles) > 0) ? $countfiles : 'no data';
    }

    public function customItems(Request $request)
    {

        try {
            $currentYear = date('Y');
            if(date('m') > 3){
                $finyear = date('Y') ;
            }else{
                $finyear = date('Y') - 1;
            }
            $year = $request->year;
            $department_name = $request->department;
            $dd_user = $request->deputyDirector;
           
            $query = Stage::query();
        
            if (!is_null($year) && $year != 1) {
                    //split Year
                    $startedYear = explode('-',$year);
                
                    $startDate = $startedYear[0] . '-04-01';
                    // Set the end date to April 1st of the next year
                    $endDate = ($startedYear[0] + 1). '-03-31';

                    $query->whereBetween('final_report', [$startDate, $endDate]);
                }
                if (!is_null($department_name)) {
                $query->whereHas('department', function ($query) use ($department_name) {
                    $query->where('dept_id', $department_name);
                });
            }
            $user_ids = "";
            if (!is_null($dd_user)) {
              
                $role = explode(',', $dd_user);
                $user_ids = User::whereIn('role', $role)->pluck('id')->toArray();
              
                if(!is_null($user_ids)){
                    $query->whereHas('users', function ($query) use ($user_ids) {
                        $query->whereIn('user_id', $user_ids);
                    });
                }
            }
            // Set the start date to March 31st of the current year
            $startFDate = $finyear . '-04-01';
            // Set the end date to April 1st of the next year
            $endFDate = ($finyear + 1). '-03-31';
            
            if(!is_null($year) && $year != 1){
                if(Auth::user()->login_user == 1){
                    $query->whereNotNull('document')->where('dept_id',Auth::user()->dept_id);
                }else{
                    $query->whereNotNull('document');
                }
            }else{
                if(Auth::user()->login_user == 1){
                    $query->whereBetween('final_report', [$startFDate, $endFDate])->whereNotNull('document')->where('dept_id',Auth::user()->dept_id);
                }else{
                    $query->whereBetween('final_report', [$startFDate, $endFDate])->whereNotNull('document');
                }
            }
           
           
            
            // <a href="' . route('stages.downalod', $item->id) . '" class="btn btn-xs btn-info" style="display: inline-block">Stage Report Download</a>
           
            $completedProposals = $query->with(['department', 'users'])->get();
         
            $data = $completedProposals->map(function ($item, $key) {
                $action = "";
               if (!empty($item->document)) {
                            $action .= '<a class="btn btn-xs btn-info" 
                                        href="' . route('stages.get_the_file', [Crypt::encrypt($item->scheme_id), $item->document]) . '" 
                                        target="_blank" 
                                        title="' .e($item->document) .'">'.__('message.view_document').'</a>';
                        } 
                        $action .= '<a class="btn btn-xs btn-info" href="'. route('stages.downalod', $item->id) . '" >'
                                        . __('message.stage_report_download') .
                                    '</a>';
                        

                return [
                    'DT_RowIndex' => $key + 1,
                    'scheme_name' => SchemeName($item->scheme_id),
                    'department_name' => department_name($item->dept_id),
                    'published_date' => date('d-M-y', strtotime($item->final_report)),
                    'actions' => $action
                ];
            });
        
            return response()->json(['data' => $data]);
        
        } catch (\Exception $e) {
            // Log or handle the exception
            return response()->json(['error' => $e->getMessage()], 500);
        }
        

      
    }

    public function customdeptItems(Request $request)
    {

        try {
            $currentYear = date('Y');
            if(date('m') > 3){
                $finyear = date('Y') ;
            }else{
                $finyear = date('Y') - 1;
            }
            $year = $request->year;
            $department_hod_name = $request->department_hod_name;
          
           
            $query = Stage::query();
        
              /* =======================
                Financial Year Filter
                ======================= */
                if (!is_null($year) && $year != 1) {

                    $startedYear = explode('-', $year);

                    $startDate = $startedYear[0] . '-04-01';
                    $endDate   = ($startedYear[0] + 1) . '-03-31';

                    $query->whereBetween('final_report', [$startDate, $endDate]);
                }

                /* =======================
                Department HOD Filter
                ======================= */
                if (!is_null($department_hod_name)) {

                    $query->whereHas('department.hods', function ($q) use ($department_hod_name) {
                        $q->where('name', $department_hod_name);
                       // ->where('status', 1); // optional
                    });
                }

                // Set the start date to March 31st of the current year
                $startFDate = $finyear . '-04-01';
                // Set the end date to April 1st of the next year
                $endFDate = ($finyear + 1). '-03-31';
                
                if(!is_null($year) && $year != 1){
                    if(Auth::user()->login_user == 1){
                        $query->whereNotNull('document')->where('dept_id',Auth::user()->dept_id);
                    }else{
                        $query->whereNotNull('document');
                    }
                }else{
                    if(Auth::user()->login_user == 1){
                        $query->whereBetween('final_report', [$startFDate, $endFDate])->whereNotNull('document')->where('dept_id',Auth::user()->dept_id);
                    }else{
                        $query->whereBetween('final_report', [$startFDate, $endFDate])->whereNotNull('document');
                    }
                }
           
        
            $completedProposals = $query->with(['department.hods'])->get();
         
            $data = $completedProposals->map(function ($item, $key) {
                $action = '';

                // Always show download button
                        $action .= '<a class="btn btn-xs btn-info" href="' . route('stages.downalod', $item->id) . '"  style="display: inline-block">'
                                        . __('message.stage_report_download') .
                                    '</a>';
                    if (!empty($item->document)) {
                            $action .= ' <a class="btn btn-xs btn-info"
                                            href="' . route('stages.get_the_file', [
                                                Crypt::encrypt($item->scheme_id),
                                                $item->document
                                            ]) . '"
                                            target="_blank"
                                            title="' . e($item->document) . '">'
                                            . __('message.final_report') .
                                        '</a>';
                    }


                return [
                    'DT_RowIndex' => $key + 1,
                    'final_report' => completedStudy($item->final_report),
                    'scheme_name' => SchemeName($item->scheme_id),
                    'hod_name' => hod_name($item->draft_id),
                    'report_published_date' => date('d-M-y',strtotime($item->final_report)),
                    'actions' => $action
                ];
            });
        
            return response()->json(['data' => $data]);
        
        } catch (\Exception $e) {
            // Log or handle the exception
            return response()->json(['error' => $e->getMessage()], 500);
        }
        

      
    }

    public function destory(Request $request,$draft_id)
    {
        try {
            $decodedId = base64_decode($draft_id);
            
            if (!is_null($decodedId)) {
                $scheme_id = Proposal::where('draft_id', $decodedId)->value('scheme_id');
    
                if ($scheme_id) {
                    // Batch processing to delete from Proposal table
                    Proposal::where('scheme_id', $scheme_id)->chunk(100, function ($proposals) {
                        foreach ($proposals as $proposal) {
                            $proposal->delete();
                        }
                    });
    
                    // Batch processing to delete from Scheme table
                    Scheme::where('scheme_id', $scheme_id)->chunk(100, function ($schemes) {
                        foreach ($schemes as $scheme) {
                            $scheme->delete();
                        }
                    });
                    $act['userid'] = Auth::user()->id;
                    $act['ip'] = $request->ip();
                    $act['activity'] = 'Scheme Deleted By Concern Department';
                    $act['officecode'] = Auth::user()->dept_id;
                    $act['pagereferred'] = $request->url();
                    Activitylog::insert($act);
                    return response()->json(['success' => true, 'message' => 'Proposal deleted successfully.']);
                } else {
                    return response()->json(['success' => false, 'message' => 'Scheme ID not found.']);
                }
            } else {
                return response()->json(['success' => false, 'message' => 'Invalid draft ID.']);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Decryption failed or something went wrong.', 'error' => $e->getMessage()]);
        }
    }
    
    public function uploadToCouchAndSql(Request $request) 
    {
        $schemeId = $request->input('scheme_id');
        $tableName = $request->input('target_table');
        $fieldName = $request->input('target_column');
        
        if (!$request->hasFile('upload_file')) {
            return ["status" => false, "message" => "No file uploaded"];
        }

        $document = $request->file('upload_file');
        $extended = new Couchdb();
        $extended->InitConnection();
        $dbName = $this->envirment['database'];
        $doc_id = "scheme_" . $schemeId;

        // --- STEP 1: Get the absolute LATEST revision from CouchDB ---
        $currentDoc = $extended->getDocument($dbName, $doc_id);
        $currentDocArray = json_decode($currentDoc, true);

        if (isset($currentDocArray['error']) && $currentDocArray['error'] == 'not_found') {
            // Document doesn't exist at all, create it
            $dummy_data = ['scheme_id' => $doc_id];
            $out = $extended->createDocument($dummy_data, $dbName, $doc_id);
            $res = json_decode($out, true);
            $rev = $res['rev'];

            // Save initial record to local SQL
            Attachment::updateOrCreate(
                ['scheme_id' => $schemeId],
                ['couch_doc_id' => $doc_id, 'couch_rev_id' => $rev]
            );
        } else {
            // Use the latest _rev directly from CouchDB to avoid "Conflict"
            $rev = $currentDocArray['_rev'];
        }

        // --- STEP 2: Prepare Path ---
        $path = [
            'id'        => $fieldName,
            'tmp_name'  => $document->getRealPath(),
            'extension' => $document->getClientOriginalExtension(),
            'name'      => $doc_id . '_' . $fieldName . '.' . $document->getClientOriginalExtension()
        ];

        // --- STEP 3: Upload Attachment ---
        $out = $extended->createAttachmentDocument($dbName, $doc_id, $rev, $path);
        $array = json_decode($out, true);

        if (isset($array['rev'])) {
            $newRev = $array['rev'] ?? null;

            // Update local SQL Attachment tracking
            Attachment::where('scheme_id', $schemeId)->update(['couch_rev_id' => $newRev]);

            // Update your Target Table
            DB::table($tableName)->where('scheme_id', $schemeId)->update([
                $fieldName => $path['name']
            ]);

            return ["status" => true, "file_name" => $path['name']];
        }                    

        return ["status" => false, "message" => "Upload failed", "couch_response" => $out];
    }

    public function beneficiariesDetails(Request $request)
    {
      
        $distname = $request->input('beneficiariesGeoLocal');
        $scheme_id = $request->input('scheme_id');
       
        if($distname == 1) { //State
            $entered_districts = Scheme::where('scheme_id', $scheme_id)->value('states');
            $dist_arr = json_decode($entered_districts); 
            $state = State::active()->select('id', 'name')->orderBy('name', 'asc')->get();
            $states = array('states' => $state, 'entered_item' => $dist_arr);
            return response()->json($states);
           
        }else if($distname == 2) { //District
            $entered_districts = Scheme::where('scheme_id', $scheme_id)->value('districts');
            $dist_arr = json_decode($entered_districts); 
            $district = Districts::select('dcode', 'name_e')->whereIn('dcode',$dist_arr)->orderBy('name_e', 'asc')->get();
            $districts = array('districts' => $district, 'entered_values' => $dist_arr);
            return response()->json($districts);
            
        }else if($distname == 3) { //Talukas
            $entered_districts = Scheme::where('scheme_id', $scheme_id)->pluck('talukas','taluka_id');
            $taluka_id = $entered_districts->keys()->first(); // 2
            $taluka_list = json_decode($entered_districts->first(), true); 

            if (!empty($taluka_id) && !empty($taluka_list)) {

            $taluka = Taluka::where('dcode', $taluka_id)
                    ->whereIn('tcode', $taluka_list)
                    ->select('tcode', 'tname_e')
                    ->orderBy('tname_e', 'asc')
                    ->get();

            } else {
                $taluka = collect();
                $taluka_id = 0;
            }

            $dist_arr = json_decode($entered_districts->first()); 
            $district_list = Districts::select('dcode','name_e')->orderBy('name_e','asc')->get();
            $talukas = array('talukas' => $taluka, 'entered_values' => $dist_arr,'taluka_id'=>$taluka_id,'district_list' =>$district_list);
           // dd('taluka');
            return response()->json($talukas);
            
        } else if($distname == 9) { //Coastal Districts
            $entered_districts = Scheme::where('scheme_id',$scheme_id)->value('districts');
            $dist_arr = json_decode($entered_districts);
            $district = Districts::select('dcode','name_e')->where('is_coastal_area','1')->orderBy('name_e','asc')->get();
            $districts = array('districts'=>$district,'entered_values'=>$dist_arr);
            return response()->json($districts);
        }
        // else if($distname == 5) {
        //     $entered_districts = Scheme::where('scheme_id',$scheme_id)->value('districts');
        //     $dist_arr = json_decode($entered_districts);
        //     $district = Districts::select('dcode','name_e')->where('is_tribal','1')->orderBy('name_e','asc')->get();
        //     $districts = array('districts'=>$district,'entered_values'=>$dist_arr);
        //     return response()->json($districts);
        // } else if($distname == 6) {
        //     $entered_districts = Scheme::where('scheme_id',$scheme_id)->value('districts');
        //     $dist_arr = json_decode($entered_districts);
        //     $district = Districts::select('dcode','name_e')->where('is_coastal_area','1')->orderBy('name_e','asc')->get();
        //     $districts = array('districts'=>$district,'entered_values'=>$dist_arr);
        //     return response()->json($districts);
        // } else if($distname == 7) {
        //     $entered_districts = Scheme::where('scheme_id',$scheme_id)->value('talukas');
        //     $dist_arr = json_decode($entered_districts);
        //     $district = Taluka::select('tcode','tname_e')->where('is_developing','1')->orderBy('tname_e','asc')->get();
        //     $districts = array('talukas'=>$district,'entered_values'=>$dist_arr);
        //     return response()->json($districts);
        // } else if($distname == 8) {
        //     $entered_districts = Scheme::where('scheme_id',$scheme_id)->value('districts');
        //     $dist_arr = json_decode($entered_districts);
        //     $district = Districts::select('dcode','name_e')->where('is_border_area','1')->orWhere('is_international_border','1')->orderBy('name_e','asc')->get();
        //     $districts = array('districts'=>$district,'entered_values'=>$dist_arr);
        //     return response()->json($districts);
        // } 
    }
  
    public function downloadFinalReportPdf($scheme_id)
    {
        $scheme_id = Crypt::decrypt($scheme_id);

        $proposal = Proposal::with([
            'gr_file',
            'notification_files',
            'brochure_files',
            'pamphlets_files',
            'otherdetailscenterstate_files'
        ])->where('scheme_id', $scheme_id)->firstOrFail();
        dd([
            'gr_file' => $proposal->gr_file->pluck('id')->all(),
            'notification_files' => $proposal->notification_files->pluck('id')->all(),
            'brochure_files' => $proposal->brochure_files->pluck('id')->all(),
            'pamphlets_files' => $proposal->pamphlets_files->pluck('id')->all(),
            'otherdetailscenterstate_files' => $proposal->otherdetailscenterstate_files->pluck('id')->all(),
        ]);

        $dept_name = 'Agriculture & Co-Operation Department';

        $html = view('pdf.final-report', compact(
            'proposal',
            'dept_name',
        
        ))->render();

        $mpdf = new \Mpdf\Mpdf([
            'tempDir' => storage_path('app/mpdf'),
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'default_font' => 'notosansgujarati'
        ]);

        $mpdf->WriteHTML($html);

        return response(
            $mpdf->Output($proposal->scheme_name . '.pdf', 'D')
        )->header('Content-Type', 'application/pdf');
    }

}
?>