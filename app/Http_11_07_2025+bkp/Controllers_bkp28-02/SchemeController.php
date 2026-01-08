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
use Illuminate\Support\Facades\Storage;
use URL;
use Response;
use DB;
use Session;
use Illuminate\Support\Facades\Schema;
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

           
        }else if($distname == 5) {
            $district = Districts::select('dcode','name_e')->where('is_tribal','1')->orderBy('name_e','asc')->get();
            $districts = array('districts'=>$district);
            return response()->json($districts);
        } else if($distname == 6) {
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
          //  dd('state');
            return response()->json($states);
           
        }else if($distname == 2) { //District
            $entered_districts = Scheme::where('scheme_id', $scheme_id)->value('districts');
            $dist_arr = json_decode($entered_districts); 
            $district = Districts::select('dcode', 'name_e')->orderBy('name_e', 'asc')->get();
            $districts = array('districts' => $district, 'entered_values' => $dist_arr);
          //  dd('dist');
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

    public function create() {
       
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
        return view('schemes.create',compact('units','year','departments','department_user','implementations','beneficiariesGeoLocal','dept','financial_years','goals'));
    }

    public function departmentlist(Request $request) {
        $departments = Department::select('dept_id','dept_name')->orderBy('dept_name')->get();
        return response()->json($departments);
    }
 
    public function getNodal(Request $request){
        $data['nodals'] = Nodal::where('imid',$request->imid)->get(['nodalid','nodal_name']);
        return response()->json($data);
    }

    public function getConvener(Request $request){
        $data['conveners'] = Convener::where('con_id',$request->conid)->get(['convener_name','convener_designation','convener_phone_no','convener_mobile_no','convener_email']);
        return response()->json($data);
    }

    public function add_scheme(Request $request) {
        $slide = $request->input('slide');
        if($slide == 'first' and Session::has('scheme_id')) {
            $scheme_id = Session::get('scheme_id');
            $draft_id = Session::get('draft_id');
            $dept_id = $request->input('dept_id');
            $convener_name = $request->input('convener_name');
            $scheme_name = $request->input('scheme_name');
            $reference_year = $request->input('reference_year');
            $reference_year2 = $request->input('reference_year2');
            $convener_designation = $request->convener_designation;
            $convener_phone = $request->convener_phone;
            $convener_phone = $request->convener_phone;
            $financial_adviser_name = $request->financial_adviser_name;
            $financial_adviser_designation = $request->financial_adviser_designation;
            $financial_adviser_phone = $request->financial_adviser_phone;
            $arr = array('dept_id'=>$dept_id,'convener_name'=>$convener_name,'convener_designation'=>$convener_designation,'convener_phone'=> $convener_phone,'scheme_name'=>$scheme_name,'reference_year'=>$reference_year,'reference_year2'=>$reference_year2,
            'financial_adviser_name'=>$financial_adviser_name,
            'financial_adviser_designation' =>$financial_adviser_designation,
            'financial_adviser_phone'=>$financial_adviser_phone);

            Scheme::where('scheme_id',$scheme_id)->update($arr);
            Proposal::where('draft_id',$draft_id)->update($arr);
            return Session::get('scheme_id');
        } else if($slide == 'first' and !Session::has('scheme_id')) {
            
            $dept_id = $request->input('dept_id');
            $convener_name = $request->input('convener_name');
            $scheme_name = $request->input('scheme_name');
            $reference_year = $request->input('reference_year');
            $reference_year2 = $request->input('reference_year2');
            $convener_designation = $request->convener_designation;
            $convener_phone = $request->convener_phone;
            $financial_adviser_name = $request->financial_adviser_name;
            $financial_adviser_designation = $request->financial_adviser_designation;
            $financial_adviser_phone = $request->financial_adviser_phone;
            $arr = array('dept_id'=>$dept_id,
            'convener_name'=>$convener_name,
            'convener_designation'=>$convener_designation,
            'convener_phone'=> $convener_phone,'scheme_name'=>$scheme_name,
            'reference_year'=>$reference_year,'reference_year2'=>$reference_year2,
            'financial_adviser_name'=>$financial_adviser_name,
            'financial_adviser_designation' =>$financial_adviser_designation,
            'financial_adviser_phone'=>$financial_adviser_phone);
           // dd($arr);
            Scheme::insert($arr);
            $scheme_id = Scheme::orderBy('scheme_id','desc')->take(1)->value('scheme_id');
            $arr['scheme_id'] = $scheme_id;
            Session::put('scheme_id',$scheme_id);
            Proposal::insert($arr);
            $draft_id = Proposal::orderBy('draft_id','desc')->take(1)->value('draft_id');
            Session::put('draft_id',$draft_id);
            $extended = new Couchdb();
            $extended->InitConnection();
            $status = $extended->isRunning();
            $doc_id = "scheme_".$scheme_id;
            $dummy_data = array(
                'scheme_id' => $doc_id
            );
            $out = $extended->createDocument($dummy_data,$this->envirment['database'],$doc_id);
            $array = json_decode($out, true);
            $id = $array['id'];
            $rev = $array['rev'];
            $data['scheme_id'] = $scheme_id;
            $data['couch_doc_id'] = $id;
            $data['couch_rev_id'] = $rev;
            $attachment = Attachment::create($data);
            return response()->json('added successfully');
        } else if($slide == 'second') {
            $major_objectives = $request->input('major_objective');
            $arr = array('major_objective'=>$major_objectives);
            $scheme_id = Session::get('scheme_id');
            Scheme::where('scheme_id',$scheme_id)->update($arr);
            $draft_id = Session::get('draft_id');
            Proposal::where('draft_id',$draft_id)->update($arr);
            return response()->json('added successfully');
        } else if($slide == 'third') {
            $major_indicator = $request->input('major_indicator');
            $arr = array('major_indicator'=>$major_indicator);
            $scheme_id = Session::get('scheme_id');
            Scheme::where('scheme_id',$scheme_id)->update($arr);
            $draft_id = Session::get('draft_id');
            Proposal::where('draft_id',$draft_id)->update($arr);
            return response()->json('added successfully');
        } else if($slide == 'fourth') {
            $data = $request->all();
            $scheme_id = Session::get('scheme_id');
            $draft_id = Session::get('draft_id');
            unset($data['_token']);
            unset($data['slide']);
            //$arr = $data;

            $filename = "";
            if($request->hasFile('next_scheme_overview_file')) {
                $document = $request->file('next_scheme_overview_file');
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
                    $id = $array['id'];
                    $rev = $array['rev'];
                    $data['scheme_id'] = $scheme_id;
                    $data['couch_doc_id'] = $id;
                    $data['couch_rev_id'] = $rev;
                    $attachment = Attachment::create($data);
                }
                $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                $array = json_decode($out, true);
                $rev = $array['rev'];
                if(isset($rev)) {
                    $result = Attachment::where('scheme_id',$scheme_id)->update(['couch_rev_id'=>$rev]);
                }                    
                $filename = $path['name'];
            }else{
                $filename = $request->existing_next_scheme_overview_file;
            }

            $filetype = "";
            if($request->hasFile('scheme_objective_file')) {
                $document = $request->file('scheme_objective_file');
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
                    $id = $array['id'];
                    $rev = $array['rev'];
                    $data['scheme_id'] = $scheme_id;
                    $data['couch_doc_id'] = $id;
                    $data['couch_rev_id'] = $rev;
                    $attachment = Attachment::create($data);
                }
                $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                $array = json_decode($out, true);
                $rev = $array['rev'];
                if(isset($rev)) {
                    $result = Attachment::where('scheme_id',$scheme_id)->update(['couch_rev_id'=>$rev]);
                }                    
                $filetype = $path['name'];
            }else{
                $filetype = $request->existing_scheme_objective_file;
            }

            $filecomponent = "";
            if($request->hasFile('next_scheme_components_file')) {
                $document = $request->file('next_scheme_components_file');
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
                    $id = $array['id'];
                    $rev = $array['rev'];
                    $data['scheme_id'] = $scheme_id;
                    $data['couch_doc_id'] = $id;
                    $data['couch_rev_id'] = $rev;
                    $attachment = Attachment::create($data);
                }
                $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                $array = json_decode($out, true);
                $rev = $array['rev'];
                if(isset($rev)) {
                    $result = Attachment::where('scheme_id',$scheme_id)->update(['couch_rev_id'=>$rev]);
                }                    
                $filecomponent = $path['name'];
            }else{
                $filecomponent = $request->existing_next_scheme_components_file;
            }

            $arr = [
               // "both_ratio_type" =>  $request->both_ratio_type,
                "implementing_office_contact" =>  $request->implementing_office_contact,
                'implementing_office_contact_type'=> $request->implementing_office_contact_type,
                "both_ration" => $request->both_ration,
                "implementing_office" => $request->implementing_office,
                "nodal_officer_name" => $request->nodal_officer_name,
                "nodal_officer_designation" => $request->nodal_officer_designation,
                "state_ratio" => $request->state_ratio,
                "center_ratio" => $request->central_ratio,
                "hod_name" => $request->hod_name,
                "scheme_overview" => $request->scheme_overview,
                "scheme_objective" => $request->scheme_objective,
                "sub_scheme" => $request->sub_scheme,
                'next_scheme_overview_file' => $filename,
                'next_scheme_components_file' =>$filecomponent,
                'scheme_objective_file'=> $filetype
            ];
          
            Scheme::where('scheme_id',$scheme_id)->update($arr);

            Proposal::where('draft_id',$draft_id)->update($arr);
            return response()->json('added successfully');
        } else if($slide == 'fifth') {
            $data = $request->all();
           
            unset($data['_token']);
            unset($data['slide']);
            $arr = $data;
            $scheme_id = Session::get('scheme_id');
            Scheme::where('scheme_id',$scheme_id)->update($arr);
            $draft_id = Session::get('draft_id');
            Proposal::where('draft_id',$draft_id)->update($arr);
            return response()->json('added successfully');
        } else if($slide == 'sixth') {
            $data = $request->all();
            unset($data['_token']);
            unset($data['slide']);
            // $arr = $data;
            $scheme_id = Session::get('scheme_id');
            $draft_id = Session::get('draft_id');

            $filename = "";
            if($request->hasFile('beneficiary_selection_criteria_file')) {
                $document = $request->file('beneficiary_selection_criteria_file');
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
                    $id = $array['id'];
                    $rev = $array['rev'];
                    $data['scheme_id'] = $scheme_id;
                    $data['couch_doc_id'] = $id;
                    $data['couch_rev_id'] = $rev;
                    $attachment = Attachment::create($data);
                }
                $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                $array = json_decode($out, true);
                $rev = $array['rev'];
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
        } else if($slide == 'seventh') {
            $filename = '';
            $scheme_id = Session::get('scheme_id');
            $major_benefits_text = $request->input('major_benefits_text');
            if($request->hasFile('major_benefits')) {
                $document = $request->file('major_benefits');
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
                $rev = $array['rev'];
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
        } else if($slide == 'eighth') {
            $scheme_id = Session::get('scheme_id');
            $scheme_implementing_procedure = $request->input('scheme_implementing_procedure');
            $beneficiariesGeoLocal = $request->input('beneficiariesGeoLocal');
            $districts = json_encode($request->input('district_name'));
            $talukas = json_encode($request->input('taluka_name'));
            $states = json_encode($request->input('state_name'));
            $taluka_id = (!is_null($request->taluka_id)) ? $request->taluka_id : 'null';
            $otherbeneficiariesGeoLocal = $request->input('otherbeneficiariesGeoLocal');
            $filename = '';

            if($request->hasFile('geographical_coverage')) {
                $document = $request->file('geographical_coverage');
                $rev = Attachment::where('scheme_id',$scheme_id)->value('couch_rev_id');
                $extended = new Couchdb();
                $extended->InitConnection();
                $status = $extended->isRunning();
                $doc_id = "scheme_".$scheme_id;
                $docid = 'geographical_coverage'; 
                $path['id'] = $docid;
                $path['tmp_name'] = $document->getRealPath();
                $path['extension']  = $document->getClientOriginalExtension();
                $path['name'] = $doc_id.'_'.$path['id'].'.'.$path['extension'];
                $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                $array = json_decode($out, true);
                $rev = $array['rev'];
                if(isset($rev)) {
                    $result = Attachment::where('scheme_id',$scheme_id)->update(['couch_rev_id'=>$rev]);
                }                    
                $filename = $path['name'];
            }

            $arr = array('scheme_implementing_procedure'=>$scheme_implementing_procedure, 
            'beneficiariesGeoLocal'=>$beneficiariesGeoLocal, 
            'districts'=>$districts, 
            'talukas'=>$talukas,
            'taluka_id'=>$taluka_id,
            'states'=>$states,
            'otherbeneficiariesGeoLocal'=>$otherbeneficiariesGeoLocal,
            'geographical_coverage'=>$filename);
            Scheme::where('scheme_id',$scheme_id)->update($arr);
            $draft_id = Session::get('draft_id');
            Proposal::where('draft_id',$draft_id)->update($arr);
            return response()->json('added successfully');
        } else if($slide == 'nineth') {
            $scheme_id = Session::get('scheme_id');
            $coverage_beneficiaries_remarks = $request->input('coverage_beneficiaries_remarks');
            $training_capacity_remarks = $request->input('training_capacity_remarks');
            $iec_activities_remarks = $request->input('iec_activities_remarks');
            $beneficiaries_coverage_file_name = '';
            if($request->has('beneficiaries_coverage')) {
                $beneficiaries_coverage = $request->file('beneficiaries_coverage');
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
                $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                $array = json_decode($out, true);
                $rev = $array['rev'];
                if(isset($rev)) {
                    $result = Attachment::where('scheme_id',$scheme_id)->update(['couch_rev_id'=>$rev]);
                }                    
                $beneficiaries_coverage_file_name = $path['name'];
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
                $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                $array = json_decode($out, true);
                $rev = $array['rev'];
                if(isset($rev)) {
                    $result = Attachment::where('scheme_id',$scheme_id)->update(['couch_rev_id'=>$rev]);
                }                    
                $training_file_name = $path['name'];
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
                $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                $array = json_decode($out, true);
                $rev = $array['rev'];
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
        } else if($slide == 'tenth') {
            $benefit_to = $request->input('benefit_to');
            $all_convergence = $request->input('all_convergence');
            $arr = array('benefit_to'=>$benefit_to,'all_convergence'=>$all_convergence);
            $scheme_id = Session::get('scheme_id');
            Scheme::where('scheme_id',$scheme_id)->update($arr);
            $draft_id = Session::get('draft_id');
            Proposal::where('draft_id',$draft_id)->update($arr);
            return response()->json('added successfully');
        } else if($slide == 'eleventh') {
            $data = $request->all();
            unset($data['_token']);
            unset($data['slide']);
            $scheme_id = Session::get('scheme_id');
            $documents = $request->file();
            $extended = new Couchdb();
            $extended->InitConnection();
            $status = $extended->isRunning();
            $doc_id = "scheme_".$scheme_id;

            $test_file_array = array();

            foreach($documents as $docid => $document) {
                if(is_array($document)) {
                    $multifiles = array();
                    $j = 1;
                    foreach($document as $docidis => $documentis) {
                        $file = Attachment::where('scheme_id',$scheme_id)->first();
                        $file_data = json_decode($file,true);
                        $rev = $file_data['couch_rev_id'];

                        $path['id'] = $docid;
                        $path['tmp_name'] = $documentis->getRealPath();
                        $path['extension']  = $documentis->getClientOriginalExtension();
                        $path['name'] = $doc_id.'_'.$path['id'].'_'.$j.'.'.$path['extension'];
                        $the_doc_id = $path['id'];
                        $multifiles[$the_doc_id] = $path['name'];
                        $test_file_array[] = $path['name'];
                        $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                        $array = json_decode($out, true);
                        $rev = $array['rev'];
                        if(isset($rev)) {
                            $result = Attachment::where('scheme_id',$scheme_id)->update(['couch_rev_id'=>$rev]);
                        }
                        $j++;
                    }
                } else {
                    $file = Attachment::where('scheme_id',$scheme_id)->first();
                    $file_data = json_decode($file,true);
                    $rev = $file_data['couch_rev_id'];

                    $path['id'] = $docid;
                    $path['tmp_name'] = $document->getRealPath();
                    $path['extension']  = $document->getClientOriginalExtension();
                    $path['name'] = $doc_id.'_'.$path['id'].'.'.$path['extension'];
                    $test_file_array[] = $path['name'];
                    $the_doc_id = $path['id'];
                    $singlefiles[$the_doc_id] = $path['name'];
                    $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                    $array = json_decode($out, true);
                    $rev = $array['rev'];
                    if(isset($rev)){    
                        $result = Attachment::where('scheme_id',$scheme_id)->update(['couch_rev_id'=>$rev]);
                    }                    
                }
            }

            if($request->hasFile('gr')) {
                $gr_files = $request->file('gr');
                foreach($gr_files as $grkey => $gr_val) {
                    $file_ext = $gr_val->getClientOriginalExtension();
                    $file_name = $doc_id.'_'.'gr_'.++$grkey.'.'.$file_ext;
                    $arr[] = array('file_name'=>$file_name,'scheme_id'=>$scheme_id,'created_at'=>date('Y-m-d H:i:s'),'gr_date'=> $request->gr_date,'gr_number'=>$request->gr_number);
                    // GrFilesList::insert($arr);
                    DB::table('itransaction.gr_files_list')->insert($arr);
                }
            }else{
                $arr[] = array('scheme_id'=>$scheme_id,'created_at'=>date('Y-m-d H:i:s'),'gr_date'=> $request->gr_date,'gr_number'=>$request->gr_number);
                DB::table('itransaction.gr_files_list')->insert($arr);
            }
            // return $arr;
            if($request->hasFile('notification')) {
                $notification_files = $request->file('notification');
                foreach($notification_files as $notificationkey => $notification_val) {
                    $file_ext = $notification_val->getClientOriginalExtension();
                    $file_name = $doc_id.'_'.'notification_'.++$notificationkey.'.'.$file_ext;
                    $arr = array('file_name'=>$file_name,'scheme_id'=>$scheme_id,'created_at'=>date('Y-m-d H:i:s'));
                    DB::table('itransaction.notification_files_list')->insert($arr);
                    // NotificationFileList::insert($arr);
                }
            }

            if($request->hasFile('brochure')) {
                $brochure_files = $request->file('brochure');
                foreach($brochure_files as $brochurekey => $brochure_val) {
                    $file_ext = $brochure_val->getClientOriginalExtension();
                    $file_name = $doc_id.'_'.'brochure_'.++$brochurekey.'.'.$file_ext;
                    $arr = array('file_name'=>$file_name,'scheme_id'=>$scheme_id,'created_at'=>date('Y-m-d H:i:s'));
                    DB::table('itransaction.brochure_file_list')->insert($arr);
                    // BrochureFileList::insert($arr);
                }
            }

            if($request->hasFile('pamphlets')) {
                $pamphlets_files = $request->file('pamphlets');
                foreach($pamphlets_files as $pamphletskey => $pamphlets_val) {
                    $file_ext = $pamphlets_val->getClientOriginalExtension();
                    $file_name = $doc_id.'_'.'pamphlets_'.++$pamphletskey.'.'.$file_ext;
                    $arr = array('file_name'=>$file_name,'scheme_id'=>$scheme_id,'created_at'=>date('Y-m-d H:i:s'));
                    DB::table('itransaction.pamphlet_file_list')->insert($arr);
                    // PamphletFileList::insert($arr);
                }
            }
            
            if($request->hasFile('otherdetailscenterstate')) {
                $otherdetailscenterstate_files = $request->file('otherdetailscenterstate');
                foreach($otherdetailscenterstate_files as $otherdetailscenterstatekey => $otherdetailscenterstate_val) {
                    $file_ext = $otherdetailscenterstate_val->getClientOriginalExtension();
                    $file_name = $doc_id.'_'.'otherdetailscenterstate_'.++$otherdetailscenterstatekey.'.'.$file_ext;
                    $arr = array('file_name'=>$file_name,'scheme_id'=>$scheme_id,'created_at'=>date('Y-m-d H:i:s'));
                    DB::table('itransaction.center_state_file_list')->insert($arr);
                    // CenterStateFiles::insert($arr);
                }
            }
            
            return response()->json('added successfully');
        } else if($slide == 'twelth') {
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
        } else if($slide == 'thirteenth') {
            $tr_array = $request->input('tr_array');
            $fin_progress_remarks = $request->input('financial_progress_remarks');
            $arr = array();
            $scheme_id = Session::get('scheme_id');
            $fn_year = '';
            foreach($tr_array as $tr) {
                $fn_year = $tr['financial_year'];
                $arr[] = array('scheme_id'=>$scheme_id,'financial_year'=>$tr['financial_year'], 'target'=>$tr['target'],'achievement'=>$tr['achievement'], 'allocation'=>$tr['allocation'], 'expenditure'=>$tr['expenditure'],'selection' => $tr['selection'],'items'=>$tr['items']);
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
        } else if($slide == 'fourteenth') {
            $is_evaluation = $request->input('is_evaluation');
            $eval_by_whom = $request->input('eval_by_whom');
            $eval_when = $request->input('eval_when');
            $eval_geographical_coverage_beneficiaries = $request->input('eval_geographical_coverage_beneficiaries');
            $eval_number_of_beneficiaries = $request->input('eval_number_of_beneficiaries');
            $eval_major_recommendation = $request->input('eval_major_recommendation');
            $scheme_id = Session::get('scheme_id');
            $eval_report_file_name = '';

            if($request->hasFile('eval_upload_report')) {
                $eval_upload_report = $request->file('eval_upload_report');
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
                $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                $array = json_decode($out, true);
                $rev = $array['rev'];
                if(isset($rev)) {
                    $result = Attachment::where('scheme_id',$scheme_id)->update(['couch_rev_id'=>$rev]);
                }                    
                $eval_report_file_name = $path['name'];
            }

            $arr = array('is_evaluation'=>$is_evaluation, 'eval_scheme_bywhom'=>$eval_by_whom, 'eval_scheme_when'=>$eval_when, 'eval_scheme_geo_cov_bene'=>$eval_geographical_coverage_beneficiaries, 'eval_scheme_no_of_bene'=>$eval_number_of_beneficiaries, 'eval_scheme_major_recommendation'=>$eval_major_recommendation,'eval_scheme_report'=>$eval_report_file_name);
            
            if($scheme_id != '') {
                Scheme::where('scheme_id',$scheme_id)->update($arr);
                $draft_id = Session::get('draft_id');
                Proposal::where('draft_id',$draft_id)->update($arr);
            }
            return response()->json('added successfully');
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
        return response()->json(['success',true,'redirectUrl'=> route('proposals', ['param' => 'new'])]);
    }
    public function proposaledit($id) {
        $val = Proposal::with(['gr_file','notification_files','brochure_files','pamphlets_files','otherdetailscenterstate_files'])->where('draft_id',Crypt::decrypt($id))->latest()->first();
     //   dd($val);
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
        return view('schemes.proposal_edit',compact('units','year','val','departments','implementations','beneficiariesGeoLocal','dept','financial_years','goals','financial_progress','scheme_id','replace_url','gr_files','notifications','brochures','pamphlets','center_state'));
    }

    public function scheme_update(Request $request) {
        $slide = $request->input('slide');
        if($slide == 'first') {
           
            $scheme_id = $request->input('scheme_id');
            $draft_id = $request->input('draft_id');
            Session::put('scheme_id',$scheme_id);
            Session::put('draft_id',$draft_id);
            $dept_id = $request->input('dept_id');
            $convener_name = $request->input('convener_name');
            $scheme_name = $request->input('scheme_name');
            $reference_year = $request->input('reference_year');
            $reference_year2 = $request->input('reference_year2');
            $convener_designation = $request->convener_designation;
            $convener_phone = $request->convener_phone;
            $financial_adviser_name = $request->financial_adviser_name;
            $financial_adviser_designation = $request->financial_adviser_designation;
            $financial_adviser_phone = $request->financial_adviser_phone;
            $arr = array('dept_id'=>$dept_id,'convener_name'=>$convener_name,'convener_designation'=>$convener_designation,'convener_phone'=> $convener_phone,'scheme_name'=>$scheme_name,'reference_year'=>$reference_year,'reference_year2'=>$reference_year2,
            'financial_adviser_name'=>$financial_adviser_name,
            'financial_adviser_designation' =>$financial_adviser_designation,
            'financial_adviser_phone'=>$financial_adviser_phone);
            
            Scheme::where('scheme_id',$scheme_id)->update($arr);
            Proposal::where('draft_id',$draft_id)->update($arr);
            return response()->json('updated successfully');
        } else if($slide == 'second') {
            $major_objectives = $request->input('major_objective');
            $arr = array('major_objective'=>$major_objectives);
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
        } else if($slide == 'third') {
            $major_indicator = $request->input('major_indicator');
            $arr = array('major_indicator'=>$major_indicator);
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
        } else if($slide == 'fourth') {
           
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
                $document = $request->file('next_scheme_overview_file');
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
                    $id = $array['id'];
                    $rev = $array['rev'];
                    $data['scheme_id'] = $scheme_id;
                    $data['couch_doc_id'] = $id;
                    $data['couch_rev_id'] = $rev;
                    $attachment = Attachment::create($data);
                }
                $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                $array = json_decode($out, true);
                $rev = $array['rev'];
                if(isset($rev)) {
                    $result = Attachment::where('scheme_id',$scheme_id)->update(['couch_rev_id'=>$rev]);
                }                    
                $filename = $path['name'];
            }else{
                $filename = $request->existing_next_scheme_overview_file;
            }

            $filetype = "";
            if($request->hasFile('scheme_objective_file')) {
                $document = $request->file('scheme_objective_file');
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
                    $id = $array['id'];
                    $rev = $array['rev'];
                    $data['scheme_id'] = $scheme_id;
                    $data['couch_doc_id'] = $id;
                    $data['couch_rev_id'] = $rev;
                    $attachment = Attachment::create($data);
                }
                $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                $array = json_decode($out, true);
                $rev = $array['rev'];
                if(isset($rev)) {
                    $result = Attachment::where('scheme_id',$scheme_id)->update(['couch_rev_id'=>$rev]);
                }                    
                $filetype = $path['name'];
            }else{
                $filetype = $request->existing_scheme_objective_file;
            }

            $filecomponent = "";
            if($request->hasFile('next_scheme_components_file')) {
                $document = $request->file('next_scheme_components_file');
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
                    $id = $array['id'];
                    $rev = $array['rev'];
                    $data['scheme_id'] = $scheme_id;
                    $data['couch_doc_id'] = $id;
                    $data['couch_rev_id'] = $rev;
                    $attachment = Attachment::create($data);
                }
                $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                $array = json_decode($out, true);
                $rev = $array['rev'];
                if(isset($rev)) {
                    $result = Attachment::where('scheme_id',$scheme_id)->update(['couch_rev_id'=>$rev]);
                }                    
                $filecomponent = $path['name'];
            }else{
                $filecomponent = $request->existing_next_scheme_components_file;
            }

            $arr = [
               // "both_ratio_type" =>  $request->both_ratio_type,
                "implementing_office_contact" =>  $request->implementing_office_contact,
                'implementing_office_contact_type'=> $request->implementing_office_contact_type,
                "both_ration" => $request->both_ration,
                "implementing_office" => $request->implementing_office,
                "nodal_officer_name" => $request->nodal_officer_name,
                "nodal_officer_designation" => $request->nodal_officer_designation,
                "state_ratio" => $request->state_ratio,
                "center_ratio" => $request->center_ratio,
                "hod_name" => $request->hod_name,
                "scheme_overview" => $request->scheme_overview,
                "scheme_objective" => $request->scheme_objective,
                "sub_scheme" => $request->sub_scheme,
                'next_scheme_overview_file' => $filename,
                'next_scheme_components_file' =>$filecomponent,
                'scheme_objective_file'=> $filetype
            ];
           
            Scheme::where('scheme_id',$scheme_id)->update($arr);
            Proposal::where('draft_id',$draft_id)->update($arr);
            return response()->json('updated successfully');
        } else if($slide == 'fifth') {
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
        } else if($slide == 'sixth') {
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
                $document = $request->file('beneficiary_selection_criteria_file');
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
                    $id = $array['id'];
                    $rev = $array['rev'];
                    $data['scheme_id'] = $scheme_id;
                    $data['couch_doc_id'] = $id;
                    $data['couch_rev_id'] = $rev;
                    $attachment = Attachment::create($data);
                }
                $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                $array = json_decode($out, true);
                $rev = $array['rev'];
                if(isset($rev)) {
                    $result = Attachment::where('scheme_id',$scheme_id)->update(['couch_rev_id'=>$rev]);
                }                    
                $filename = $path['name'];
            }else{
                $filename = ($request->existing_beneficiary_selection_criteria_file != "undefined") ? $request->existing_beneficiary_selection_criteria_file : null;
            }

            $arr = [
                "scheme_beneficiary_selection_criteria" => $request->scheme_beneficiary_selection_criteria,
                'beneficiary_selection_criteria_file' => $filename,
            ];
           
            // return $arr; die();
           
            Scheme::where('scheme_id',$scheme_id)->update($arr);
            Proposal::where('draft_id',$draft_id)->update($arr);
            return response()->json('updated successfully');
        } else if($slide == 'seventh') {
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
                $document = $request->file('major_benefits');
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
                    $id = $array['id'];
                    $rev = $array['rev'];
                    $data['scheme_id'] = $scheme_id;
                    $data['couch_doc_id'] = $id;
                    $data['couch_rev_id'] = $rev;
                    $attachment = Attachment::create($data);
                }
                $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                $array = json_decode($out, true);
                $rev = $array['rev'];
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
        } else if($slide == 'eighth') {
          
            if(Session::has('scheme_id') && Session::has('draft_id')){
                $scheme_id = Session::get('scheme_id');
                $draft_id = Session::get('draft_id');
            }else{
                $scheme_id = $request->scheme_id;
                $draft_id = $request->draft_id;
            }
            $scheme_implementing_procedure = $request->input('scheme_implementing_procedure');
            $beneficiariesGeoLocal = $request->input('beneficiariesGeoLocal');
            $districts = json_encode($request->input('district_name'));
            $talukas = json_encode($request->input('taluka_name'));
            $states = json_encode($request->input('state_name'));
            $taluka_id = isset($request->taluka_id) ? $request->taluka_id : 'null';
           
            $otherbeneficiariesGeoLocal = $request->input('otherbeneficiariesGeoLocal');
            $filename = '';

            if($request->hasFile('geographical_coverage')) {
                $document = $request->file('geographical_coverage');
                $rev = Attachment::where('scheme_id',$scheme_id)->value('couch_rev_id');
                $extended = new Couchdb();
                $extended->InitConnection();
                $status = $extended->isRunning();
                $doc_id = "scheme_".$scheme_id;
                $docid = 'geographical_coverage'; //.time().'_'.mt_rand('000000000','999999999');
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
                    $id = $array['id'];
                    $rev = $array['rev'];
                    $data['scheme_id'] = $scheme_id;
                    $data['couch_doc_id'] = $id;
                    $data['couch_rev_id'] = $rev;
                    $attachment = Attachment::create($data);
                }
                $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                $array = json_decode($out, true);
                $rev = $array['rev'];
                if(isset($rev)) {
                    $result = Attachment::where('scheme_id',$scheme_id)->update(['couch_rev_id'=>$rev]);
                }                    
                $filename = $path['name'];

                $arr = array(
                    'scheme_implementing_procedure'=>$scheme_implementing_procedure, 
                    'beneficiariesGeoLocal'=>$beneficiariesGeoLocal, 
                    'districts'=>$districts,
                    'states'=>$states,
                    'talukas'=>$talukas, 
                    'taluka_id' =>$taluka_id,
                    'otherbeneficiariesGeoLocal'=>$otherbeneficiariesGeoLocal,
                    'geographical_coverage'=>$filename
                );
              
                Scheme::where('scheme_id',$scheme_id)->update($arr);
                Proposal::where('draft_id',$draft_id)->update($arr);
            } else {
                $arr = array(
                    'scheme_implementing_procedure'=>$scheme_implementing_procedure, 
                    'beneficiariesGeoLocal'=>$beneficiariesGeoLocal, 
                    'districts'=>$districts,
                    'states'=>$states,
                    'talukas'=>$talukas, 
                    'taluka_id' =>$taluka_id,
                    'otherbeneficiariesGeoLocal'=>$otherbeneficiariesGeoLocal,
                );
               
                Scheme::where('scheme_id', $scheme_id)->update($arr);
                Proposal::where('draft_id',$draft_id)->update($arr);
            }
            return response()->json('updated successfully');
        } else if($slide == 'nineth') {
           
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
                $beneficiaries_coverage = $request->file('beneficiaries_coverage');
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
                    $id = $array['id'];
                    $rev = $array['rev'];
                    $data['scheme_id'] = $scheme_id;
                    $data['couch_doc_id'] = $id;
                    $data['couch_rev_id'] = $rev;
                    $attachment = Attachment::create($data);
                }

                $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                $array = json_decode($out, true);
                $rev = $array['rev'];
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
                    $id = $array['id'];
                    $rev = $array['rev'];
                    $data['scheme_id'] = $scheme_id;
                    $data['couch_doc_id'] = $id;
                    $data['couch_rev_id'] = $rev;
                    $attachment = Attachment::create($data);
                }
                $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                $array = json_decode($out, true);
                $rev = $array['rev'];
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
                    $id = $array['id'];
                    $rev = $array['rev'];
                    $data['scheme_id'] = $scheme_id;
                    $data['couch_doc_id'] = $id;
                    $data['couch_rev_id'] = $rev;
                    $attachment = Attachment::create($data);
                }
                $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                $array = json_decode($out, true);
                $rev = $array['rev'];
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
        } else if($slide == 'tenth') {
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
        } else if($slide == 'eleventh') {
          
            if(Session::has('scheme_id') && Session::has('draft_id')){
                $scheme_id = Session::get('scheme_id');
                $draft_id = Session::get('draft_id');
            }else{
                $scheme_id = $request->scheme_id;
                $draft_id = $request->draft_id;
            }
            $data = $request->all();
            unset($data['_token']);
            unset($data['slide']);
           
            $documents = $request->file();
            $extended = new Couchdb();
            $extended->InitConnection();
            $status = $extended->isRunning();
            $doc_id = "scheme_".$scheme_id;

            foreach($documents as $docid => $document) {
                if(is_array($document)) {
                    $multifiles = array();
                    $j = 1;
                    foreach($document as $docidis => $documentis) {
                        $file = Attachment::where('scheme_id',$scheme_id)->first();
                        $file_data = json_decode($file,true);
                        $rev = $file_data['couch_rev_id'];
                        $path['id'] = $docid;
                        $path['tmp_name'] = $documentis->getRealPath();
                        $path['extension']  = $documentis->getClientOriginalExtension();
                        $path['name'] = $doc_id.'_'.$path['id'].'_'.$j.'.'.$path['extension'];
                        $the_doc_id = $path['id'];
                        $multifiles[$the_doc_id] = $path['name'];
                        if(is_null($rev)){
                            $dummy_data = array(
                                'scheme_id' => $doc_id
                            );
                            $out = $extended->createDocument($dummy_data,$this->envirment['database'],$doc_id);
                            $array = json_decode($out, true);
                            $id = $array['id'];
                            $rev = $array['rev'];
                            $data['scheme_id'] = $scheme_id;
                            $data['couch_doc_id'] = $id;
                            $data['couch_rev_id'] = $rev;
                            $attachment = Attachment::create($data);
                        }
                        $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                        $array = json_decode($out, true);
                        $rev = $array['rev'];
                        if(isset($rev)) {
                            $result = Attachment::where('scheme_id',$scheme_id)->update(['couch_rev_id'=>$rev]);
                        }
                        $j++;
                    }
                } else {
                    $file = Attachment::where('scheme_id',$scheme_id)->first();
                    $file_data = json_decode($file,true);
                    $rev = $file_data['couch_rev_id'];

                    $path['id'] = $docid;
                    $path['tmp_name'] = $document->getRealPath();
                    $path['extension']  = $document->getClientOriginalExtension();
                    $path['name'] = $doc_id.'_'.$path['id'].'.'.$path['extension'];
                    $the_doc_id = $path['id'];
                    $singlefiles[$the_doc_id] = $path['name'];
                    if(is_null($rev)){
                        $dummy_data = array(
                            'scheme_id' => $doc_id
                        );
                        $out = $extended->createDocument($dummy_data,$this->envirment['database'],$doc_id);
                        $array = json_decode($out, true);
                        $id = $array['id'];
                        $rev = $array['rev'];
                        $data['scheme_id'] = $scheme_id;
                        $data['couch_doc_id'] = $id;
                        $data['couch_rev_id'] = $rev;
                        $attachment = Attachment::create($data);
                    }
                    $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                    $array = json_decode($out, true);
                    $rev = $array['rev'];
                    if(isset($rev)){    
                        $result = Attachment::where('scheme_id',$scheme_id)->update(['couch_rev_id'=>$rev]);
                    }                    
                }
            }

            if($request->hasFile('gr')) {
                $gr_files = $request->file('gr');
                GrFilesList::where('scheme_id',$scheme_id)->delete();
                foreach($gr_files as $grkey => $gr_val) {
                    $file_ext = $gr_val->getClientOriginalExtension();
                    $file_name = $doc_id.'_'.'gr_'.++$grkey.'.'.$file_ext;
                    $arr = array('file_name'=>$file_name,'scheme_id'=>$scheme_id,'updated_at'=>date('Y-m-d H:i:s'),'gr_date'=> $request->gr_date,'gr_number'=>$request->gr_number);
                    GrFilesList::insert($arr);
                }
            }else{
                $arr = array('scheme_id'=>$scheme_id,'updated_at'=>date('Y-m-d H:i:s'),'gr_date'=> $request->gr_date,'gr_number'=>$request->gr_number);
                GrFilesList::where('scheme_id',$scheme_id)->update($arr);
            }
            if($request->hasFile('notification')) {
                $notification_files = $request->file('notification');
                NotificationFileList::where('scheme_id',$scheme_id)->delete();
                foreach($notification_files as $notificationkey => $notification_val) {
                    $file_ext = $notification_val->getClientOriginalExtension();
                    $file_name = $doc_id.'_'.'notification_'.++$notificationkey.'.'.$file_ext;
                    $arr = array('file_name'=>$file_name,'scheme_id'=>$scheme_id);
                    NotificationFileList::insert($arr);
                }
            }
            if($request->hasFile('brochure')) {
                $brochure_files = $request->file('brochure');
                BrochureFileList::where('scheme_id',$scheme_id)->delete();
                foreach($brochure_files as $brochurekey => $brochure_val) {
                    $file_ext = $brochure_val->getClientOriginalExtension();
                    $file_name = $doc_id.'_'.'brochure_'.++$brochurekey.'.'.$file_ext;
                    $arr = array('file_name'=>$file_name,'scheme_id'=>$scheme_id);
                    BrochureFileList::insert($arr);
                }
            }
            if($request->hasFile('pamphlets')) {
                $pamphlets_files = $request->file('pamphlets');
                PamphletFileList::where('scheme_id',$scheme_id)->delete();
                foreach($pamphlets_files as $pamphletskey => $pamphlets_val) {
                    $file_ext = $pamphlets_val->getClientOriginalExtension();
                    $file_name = $doc_id.'_'.'pamphlets_'.++$pamphletskey.'.'.$file_ext;
                    $arr = array('file_name'=>$file_name,'scheme_id'=>$scheme_id);
                    PamphletFileList::insert($arr);
                }
            }
            if($request->hasFile('otherdetailscenterstate')) {
                $otherdetailscenterstate_files = $request->file('otherdetailscenterstate');
                CenterStateFiles::where('scheme_id',$scheme_id)->delete();
                foreach($otherdetailscenterstate_files as $otherdetailscenterstatekey => $otherdetailscenterstate_val) {
                    $file_ext = $otherdetailscenterstate_val->getClientOriginalExtension();
                    $file_name = $doc_id.'_'.'otherdetailscenterstate_'.++$otherdetailscenterstatekey.'.'.$file_ext;
                    $arr = array('file_name'=>$file_name,'scheme_id'=>$scheme_id);
                    CenterStateFiles::insert($arr);
                }
            }
            
            return response()->json('updated successfully');
        } else if($slide == 'twelth') {
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
        } else if($slide == 'thirteenth') {
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
                $arr[] = array('scheme_id'=>$scheme_id,'financial_year'=>$tr['financial_year'], 'target'=>$tr['target'],'achievement'=>$tr['achievement'], 'allocation'=>$tr['allocation'], 'expenditure'=>$tr['expenditure'],'selection' => $tr['selection'],'items'=>$tr['items']);
            }

            if($fn_year == '' or $scheme_id == '' or $draft_id == '') {

            } else if($scheme_id != '' and $fn_year != '' and $draft_id != '') {
                FinancialProgress::where('scheme_id',$scheme_id)->delete();
                FinancialProgress::insert($arr);
                Scheme::where('scheme_id',$scheme_id)->update(['fin_progress_remarks'=>$fin_progress_remarks]);
                Proposal::where('draft_id',$draft_id)->update(['fin_progress_remarks'=>$fin_progress_remarks]);
                return response()->json('added successfully');
            }
        } else if($slide == 'fourteenth') {
            $is_evaluation = $request->input('is_evaluation');
            $eval_by_whom = $request->input('eval_by_whom');
            $eval_when = $request->input('eval_when');
            $eval_geographical_coverage_beneficiaries = $request->input('eval_geographical_coverage_beneficiaries');
            $eval_number_of_beneficiaries = $request->input('eval_number_of_beneficiaries');
            $eval_major_recommendation = $request->input('eval_major_recommendation');
            if(Session::has('scheme_id') && Session::has('draft_id')){
                $scheme_id = Session::get('scheme_id');
                $draft_id = Session::get('draft_id');
            }else{
                $scheme_id = $request->scheme_id;
                $draft_id = $request->draft_id;
            }
            $eval_report_file_name = '';

            if($request->hasFile('eval_upload_report')) {
                $eval_upload_report = $request->file('eval_upload_report');
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
                $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                $array = json_decode($out, true);
                $rev = $array['rev'];
                if(isset($rev)) {
                    $result = Attachment::where('scheme_id',$scheme_id)->update(['couch_rev_id'=>$rev]);
                }                    
                $eval_report_file_name = $path['name'];
                if($scheme_id != '') {
                    $arr = array('eval_scheme_report'=>$eval_report_file_name);
                    Scheme::where('scheme_id',$scheme_id)->update($arr);
                    Proposal::where('draft_id',$draft_id)->update($arr);
                }
            }

            if($is_evaluation == 'Y') {
                $arr = array('is_evaluation'=>$is_evaluation, 'eval_scheme_bywhom'=>$eval_by_whom, 'eval_scheme_when'=>$eval_when, 'eval_scheme_geo_cov_bene'=>$eval_geographical_coverage_beneficiaries, 'eval_scheme_no_of_bene'=>$eval_number_of_beneficiaries, 'eval_scheme_major_recommendation'=>$eval_major_recommendation);
            } else {
                $arr = array('is_evaluation'=>'N', 'eval_scheme_bywhom'=>'', 'eval_scheme_when'=>null, 'eval_scheme_geo_cov_bene'=>'', 'eval_scheme_no_of_bene'=>'', 'eval_scheme_major_recommendation'=>'');
            }
            
            if($scheme_id != '') {
                Scheme::where('scheme_id',$scheme_id)->update($arr);
                Proposal::where('draft_id',$draft_id)->update($arr);
            }
            return response()->json('updated successfully');
        }
    }


    public function onreload(Request $request) {
        Session::forget('scheme_id');
        Session::forget('draft_id');
        return response()->json('success');
    }


    public function store(Request $request) {

        $validate = Validator::make($request->all(),[
            'dept_id' => 'required|numeric',
            'con_id' => 'required|numeric',
            'scheme_name' => 'required|string|between:10,250|regex:/^[a-zA-Z0-9\_\-\:\.\s]+$/u',
        ]);
        if($validate->fails()) {
            $state_ratio = $request->input('state_ratio');
            $center_ratio = $request->input('center_ratio');
            if($state_ratio + $center_ratio != '100') {
                return redirect()->back()->with('state_center_ratio_error','State and center ratios must be 100 %')->withInput()->withErrors($validate->errors());
            }
            return redirect()->back()->withInput()->withErrors($validate->errors());
        } else {

           

            $state_ratio = $request->input('state_ratio');
            $center_ratio = $request->input('center_ratio');
            if($state_ratio + $center_ratio != '100') {
                return redirect()->back()->with('state_center_ratio_error','State and center ratios must be 100 %');
            }

            $district_codes = '';
            if($request->input('district_name')) {
                $district_codes = implode(',',$request->input('district_name'));
            }
            $taluka_codes = '';
            if($request->input('taluka_name')) {
                $taluka_codes = implode(',',$request->input('taluka_name'));
            }

            $schemenameforfiles = str_replace(' ','_',$request->input('scheme_name'));

            $dept_id = Auth::user()->dept_id;    //$request->input('dept_id');
            $con_id = $request->input('con_id');
            $scheme_name = $request->input('scheme_name');
            $priority = $request->input('priority');
            $reference_year = $request->input('reference_year');
            $major_objectives = json_encode($request->input('major_objective'));
            $major_indicators = json_encode($request->input('major_indicator'));
            $im_id = $request->input('im_id');
            $nodal_id = $request->input('nodal_id');
            $adviser_id = $request->input('adviser_id');
            $sponser_state = $request->input('state_ratio');
            $sponser_central = $request->input('center_ratio');
            $scheme_overview = $request->input('scheme_overview');
            $scheme_objective = $request->input('scheme_objective');
            $sub_scheme = $request->input('sub_scheme');
            $commencement_year = $request->input('commencement_year');
            $scheme_status = $request->input('scheme_status');
            $is_sdg = json_encode($request->input('sustainable_goals'));
            $scheme_beneficiary_selection_criteria = json_encode($request->input('beneficiary_selection_criteria'));
            $implementation_year = $request->input('implementation_year');
            $coverage_beneficiaries_remarks = $request->input('coverage_beneficiaries_remarks');
            $training_capacity_remarks = $request->input('training_capacity_remarks');
            $iec_activities_remarks = $request->input('iec_activities_remarks');
            $major_benefits_text = json_encode($request->input('major_benefits_text'));

            $major_benefits_name = '';
            if($request->hasFile('major_benefits')) {
                $major_benefits = $request->file('major_benefits');
                $major_benefits_name = 'major_benefits_'.$schemenameforfiles.'_'.mt_rand('000000000000000','999999999999999').".".$major_benefits->extension();
            }

            $scheme_implementing_procedure = $request->input('scheme_implementing_procedure');
            $beneficiariesGeoLocal = $request->input('beneficiariesGeoLocal');
            $otherbeneficiariesGeoLocal = $request->input('otherbeneficiariesGeoLocal');
            $geographical_coverage_name = '';
            if($request->hasFile('geographical_coverage')) {
                $geographical_coverage = $request->file('geographical_coverage');
                $geographical_coverage_name = 'geographical_coverage_'.$schemenameforfiles.'_'.mt_rand('000000000000000','999999999999999').".".$geographical_coverage->extension();
            }
            $beneficiaries_coverage_name = '';
            if($request->hasFile('beneficiaries_coverage')) {
                $beneficiaries_coverage = $request->file('beneficiaries_coverage');
                $beneficiaries_coverage_name = 'beneficiaries_coverage_'.$schemenameforfiles.'_'.mt_rand('000000000000000','999999999999999').".".$beneficiaries_coverage->extension();
            }
            $training_name = '';
            if($request->hasFile('training')) {
                $training = $request->file('training');
                $training_name = 'training_'.$schemenameforfiles.'_'.mt_rand('000000000000000','999999999999999').".".$training->extension();
            }
            $iec_name = '';
            if($request->hasFile('iec_file')) {
                $iec = $request->file('iec_file');
                $iec_name = 'iec_'.$schemenameforfiles.'_'.mt_rand('000000000000000','999999999999999').".".$iec->extension();
            }

            $convergencewithotherscheme = $request->input('convergencewithotherscheme');
            $convergence_dept_ids = json_encode($request->input('convergence_dept_id'));
            $convergence_text = json_encode($request->input('convergence_text'));

            $benefit_to = $request->input('benefit_to');

            $gr_names = array();
            if($request->hasFile('gr')) {
                $gr = $request->file('gr');
                foreach($gr as $key=>$value) {
                    $gr_name = 'gr_'.$schemenameforfiles.'_'.mt_rand('000000000000000','999999999999999').".".$value->extension();
                    $gr_names[] = $gr_name;
                }
            }

            $notification_names = array();
            if($request->hasFile('notification')) {
                $notification = $request->file('notification');
                foreach($notification as $key=>$value) {
                    $notification_name = 'notification_'.$schemenameforfiles.'_'.mt_rand('000000000000000','999999999999999').".".$value->extension();
                    $notification_names[] = $notification_name;
                }
            }

            $brochures = array();
            if($request->hasFile('brochure')) {
                $brochure = $request->file('brochure');
                foreach($brochure as $key=>$value) {
                    $brochure_name = 'brochure_'.$schemenameforfiles.'_'.mt_rand('000000000000000','999999999999999').".".$value->extension();
                    $brochures[] = $brochure_name;
                }
            }

            $pamphletsare = array();
            if($request->hasFile('pamphlets')) {
                $pamphlets = $request->file('pamphlets');
                foreach($pamphlets as $key=>$value) {
                    $pamphlets_name = 'pamphlets_'.$schemenameforfiles.'_'.mt_rand('000000000000000','999999999999999').".".$value->extension();
                    $pamphletsare[] = $pamphlets_name;
                }
            }

            $other_details_center_states = array();
            if($request->hasFile('otherdetailscenterstate')) {
                $other_details_center_state = $request->file('otherdetailscenterstate');
                foreach($other_details_center_state as $key=>$value) {
                    $other_details_center_state_name = 'other_details_of_scheme_'.$schemenameforfiles.'_'.mt_rand('000000000000000','999999999999999').".".$value->extension();
                    $other_details_center_states[] = $other_details_center_state_name;
                }
            }

            $eval_upload_report_name = '';
            if($request->hasFile('eval_upload_report')) {
                $eval_upload_report = $request->file('eval_upload_report');
                $eval_upload_report_name = 'eval_upload_report_'.$schemenameforfiles.'_'.mt_rand('000000000000000','999999999999999').".".$eval_upload_report->extension();
            }

            $major_indicator_hod = json_encode($request->input('major_indicator_hod'));
            $financial_progress = $request->input('financial_progress');

            $is_evaluation = $request->input('is_evaluation');
            $eval_by_whom = $request->input('eval_by_whom');
            $eval_when = $request->input('eval_when');
            $eval_geographical_coverage_beneficiaries = $request->input('eval_geographical_coverage_beneficiaries');
            $eval_nummber_of_beneficiaries = $request->input('eval_nummber_of_beneficiaries');
            $eval_major_recommendation = $request->input('eval_major_recommendation');

            $inputarr = array(
                            'dept_id'=>$dept_id,
                            'con_id'=>$con_id,
                            'scheme_name'=>$scheme_name,
                            'priority'=>$priority,
                            'reference_year'=>$reference_year,
                            'major_objective'=>$major_objectives,
                            'major_indicator'=>$major_indicators,
                            'im_id'=>$im_id,
                            'nodal_id'=>$nodal_id,
                            'adviser_id'=>$adviser_id,
                            'state_ratio'=>$sponser_state,
                            'center_ratio'=>$sponser_central,
                            'scheme_overview'=>$scheme_overview,
                            'scheme_objective'=>$scheme_objective,
                            'beneficiariesGeoLocal'=>$beneficiariesGeoLocal,
                            'districts'=>$district_codes,
                            'otherbeneficiariesGeoLocal'=>$otherbeneficiariesGeoLocal,
                            'sub_scheme'=>$sub_scheme,
                            'commencement_year'=>$commencement_year,
                            'scheme_status'=>$scheme_status,
                            'is_sdg'=>$is_sdg,
                            'scheme_beneficiary_selection_criteria'=>$scheme_beneficiary_selection_criteria,
                            'benefit_to_file'=>$major_benefits_name,
                            'major_benefits_text' => $major_benefits_text,
                            'scheme_implementing_procedure'=>$scheme_implementing_procedure,
                            'geographical_coverage'=>$geographical_coverage_name,
                            'beneficiaries_coverage'=>$beneficiaries_coverage_name,
                            'implementation_year'=>$implementation_year,
                            'training'=>$training_name,
                            'iec'=>$iec_name,
                            'benefit_to'=>$benefit_to,
                            'convergencewithotherscheme'=>$convergencewithotherscheme,
                            'convergence_dept_ids'=>$convergence_dept_ids,
                            'convergence_text'=>$convergence_text,
                            'major_indicator_hod'=>$major_indicator_hod,
                            'is_evaluation'=>$is_evaluation,
                            'entered_by'=>Auth::user()->name,
                            'entry_date'=>date('Y-m-d H:i:s'),
                            'coverage_beneficiaries_remarks'=>$coverage_beneficiaries_remarks,
                            'training_capacity_remarks'=>$training_capacity_remarks,
                            'iec_activities_remarks'=>$iec_activities_remarks,
                            'eval_scheme_bywhom'=>$eval_by_whom,
                            'eval_scheme_when'=>$eval_when,
                            'eval_scheme_geo_cov_bene'=>$eval_geographical_coverage_beneficiaries,
                            'eval_scheme_no_of_bene'=>$eval_nummber_of_beneficiaries,
                            'eval_scheme_major_recommendation'=>$eval_major_recommendation,
                            'eval_scheme_report'=>$eval_upload_report_name
                        );
  
            Scheme::insert($inputarr);
            $schemeid = Scheme::orderBy('scheme_id','desc')->take(1)->value('scheme_id');

            $schmeid = $schemeid;
            $extended = new Couchdb();
            $extended->InitConnection();
            $status = $extended->isRunning();
            $doc_id = "scheme_".$schemeid;
            $dummy_data = array(
                'scheme_id' => $doc_id
            );
            $out = $extended->createDocument($dummy_data,$this->envirment['database'],$doc_id);
            $array = json_decode($out, true);
            $id = $array['id'];
            $rev = $array['rev'];
            $data['scheme_id'] = $schemeid;
            $data['couch_doc_id'] = $id;
            $data['couch_rev_id'] = $rev;

            $attachment = Attachment::create($data);

            $multifiles = array();
            $singlefiles = array();
            $documents = $request->file();

            foreach($documents as $docid => $document) {
                if(is_array($document)) {
                    $j = 1;
                    foreach($document as $docidis => $documentis) {
                        $file = Attachment::where('scheme_id',$schmeid)->first();
                        $file_data = json_decode($file,true);
                        $rev = $file_data['couch_rev_id'];

                        $path['id'] = $docid;
                        $path['tmp_name'] = $documentis->getRealPath();
                        $path['extension']  = $documentis->getClientOriginalExtension();
                        $path['name'] = $doc_id.'_'.$path['id'].'_'.$j.'.'.$path['extension'];
                        $multifiles[] = $path;
                        $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                        $array = json_decode($out, true);
                        $rev = $array['rev'];
                        if(isset($rev)) {
                            $result = Attachment::where('scheme_id',$schmeid)->update(['couch_rev_id'=>$rev]);
                        }
                        $j++;
                    }
                } else {
                    $file = Attachment::where('scheme_id',$schmeid)->first();
                    $file_data = json_decode($file,true);
                    $rev = $file_data['couch_rev_id'];

                    $path['id'] = $docid;
                    $path['tmp_name'] = $document->getRealPath();
                    $path['extension']  = $document->getClientOriginalExtension();
                    $path['name'] = $doc_id.'_'.$path['id'].'.'.$path['extension'];
                    $singlefiles[] = $path;
                    $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                    $array = json_decode($out, true);
                    $rev = $array['rev'];
                    if(isset($rev)){    
                        $result = Attachment::where('scheme_id',$schmeid)->update(['couch_rev_id'=>$rev]);
                    }                    
                }
            }
            $act['userid'] = \Auth::user()->id;
            $act['ip'] = $request->ip();
            $act['activity'] = 'Scheme Entry';
            $act['officecode'] = $dept_id;
            $act['pagereferred'] = $request->url();
            $act = Activitylog::insert($act);
            $arr = array();
            foreach($financial_progress as $k=>$v) {
                $arr[] = array('scheme_id'=>$schemeid,'financial_year'=>$v['financial_year'],'units'=>$v['units'],'target'=>$v['target'],'achievement'=>$v['achivement'],'allocation'=>$v['allocation'],'expenditure'=>$v['expenditure']);
            }
            if(!empty($arr)) {
                FinancialProgress::where('scheme_id',$scheme_id)->delete();
                FinancialProgress::insert($arr);
            }

            $gr_files = array();
            foreach($gr_names as $key=>$value) {
                $gr_files[] = array('scheme_id'=>$schmeid,'file_name'=>$value);
            }
            if(!empty($gr_files)) {
                GrFilesList::where('scheme_id',$scheme_id)->delete();
                GrFilesList::insert($gr_files);
            }

            $notification_files = array();
            foreach($notification_names as $key => $value) {
                $notification_files[] = array('scheme_id'=>$schmeid,'file_name'=>$value);
            }
            if(!empty($notification_files)) {
                NotificationFileList::where('scheme_id',$scheme_id)->delete();
                NotificationFileList::insert($notification_files);
            }

            $brochures_files = array();
            foreach($brochures as $key => $value) {
                $brochures_files[] = array('scheme_id'=>$schmeid,'file_name'=>$value);
            }
            if(!empty($brochures_files)) {
                BrochureFileList::where('scheme_id',$scheme_id)->delete();
                BrochureFileList::insert($brochures_files);
            }

            $pamphlet_files = array();
            foreach($pamphletsare as $key => $value) {
                $pamphlet_files[] = array('scheme_id'=>$schmeid,'file_name'=>$value);
            }
            if(!empty($pamphlet_files)) {
                PamphletFileList::where('scheme_id',$scheme_id)->delete();
                PamphletFileList::insert($pamphlet_files);
            }

            $other_details_center_states_files = array();
            foreach($other_details_center_states as $key => $value) {
                $other_details_center_states_files[] = array('scheme_id'=>$schmeid,'file_name'=>$value);
            }
            if(!empty($other_details_center_states_files)) {
                CenterStateFiles::where('scheme_id',$scheme_id)->delete();
                CenterStateFiles::insert($other_details_center_states_files);
            }

            return redirect()->to('schemes');
        }
    }

    public function schemeupdate(Request $request) {
        $validate = Validator::make($request->all(),[
            'the_id' => 'required|numeric',
            'dept_id' => 'required|numeric',
            'con_id' => 'required|numeric',
            'scheme_name' => 'required|string|between:10,250|regex:/^[a-zA-Z0-9\_\-\:\.\s]+$/u',
            'priority' => 'required|numeric|max:10',
            'reference_year' => 'required|string',
            'major_objective.*.*' => 'required|string|regex:/^[a-zA-Z0-9\s]+$/u',
            'major_indicator.*.*' => 'required|string|regex:/^[a-zA-Z0-9\s]+$/u',
            'im_id' => 'required|numeric',
            'nodal_id' => 'required|numeric',
            'adviser_id' => 'required|numeric',
            'state_ratio' => 'required|numeric',
            'center_ratio' => 'required|numeric',
            'scheme_overview' => 'required|string',
            'scheme_objective' => 'required|string',
            'sub_scheme' => 'required|string',
            'commencement_year' => 'required|string',
            'scheme_status' => 'required|in:Y,N',
            'sustainable_goals' => 'required|array',
            'beneficiary_selection_criteria' => 'required|array',
            'major_benefits' => 'nullable|max:51200|mimes:docx,pdf,xlsx',
            'major_benefits_text' => 'required|max:2000|array',
            'scheme_implementing_procedure' => 'required|string',
            'beneficiariesGeoLocal' => 'required|numeric',
            'geographical_coverage' => 'nullable|max:51200|mimes:docx,pdf,xlsx',
            'coverage_beneficiaries_remarks' => 'required|max:300',
            'beneficiaries_coverage' => 'nullable|max:51200|mimes:docx,pdf,xlsx',
            'implementation_year' => 'required|digits:4|integer|min:1900|max:'.(date('Y')+20),
            'training' => 'nullable|max:51200|mimes:docx,pdf,xlsx',
            'training_capacity_remarks' => 'required|max:300',
            'iec_file' => 'nullable|max:51200|mimes:docx,pdf,xlsx',
            'iec_activities_remarks' => 'required|max:300',
            'benefit_to' => 'required|string|max:12',
            'convergencewithotherscheme' => 'required|string',
            'convergence_dept_id' => 'nullable|array',
            'convergence_text' => 'nullable|array',
            'gr.*' => 'required|max:51200|mimes:docx,pdf,xlsx',
            'notification.*' => 'required|mimes:docx,pdf,xlsx',
            'brochure.*' => 'required|max:51200|mimes:docx,pdf,xlsx',
            'pamphlets.*' => 'required|max:51200|mimes:docx,pdf,xlsx',
            'otherdetailscenterstate.*' => 'required|max:51200|mimes:docx,pdf,xlsx',
            'major_indicator_hod.*.*' => 'required|string|between:1,250|regex:/^[a-zA-Z0-9\s]+$/u',
            'financial_progress.*.*' => 'required|string',
            'is_evaluation' => 'required|in:Y,N',
            'eval_by_whom' => 'nullable|string|max:100',
            'eval_when' => 'nullable|date_format:Y-m-d',
            'eval_geographical_coverage_beneficiaries' => 'nullable|string|max:300',
            'eval_number_of_beneficiaries' => 'nullable|numeric',
            'eval_major_recommendation' => 'nullable|max:300',
            'eval_upload_report' => 'nullable|max:2000:mimes:docx,pdf,xlsx'
        ]);
        if($validate->fails()) {
            $state_ratio = $request->input('state_ratio');
            $center_ratio = $request->input('center_ratio');
            if($state_ratio + $center_ratio != '100') {
                return redirect()->back()->with('state_center_ratio_error','State and center ratios must be 100 %')->withInput()->withErrors($validate->errors());
            }
            return redirect()->back()->withInput()->withErrors($validate->errors());
        } else {
            $scheme_id = $request->input('the_id');
            $state_ratio = $request->input('state_ratio');
            $center_ratio = $request->input('center_ratio');
            if($state_ratio + $center_ratio != '100') {
                return redirect()->back()->with('state_center_ratio_error','State and center ratios must be 100 %');
            }

            $district_codes = '';
            if($request->input('district_name')) {
                $district_codes = implode(',',$request->input('district_name'));
            }

            $taluka_codes = '';
            if($request->input('taluka_name')) {
                $taluka_codes = implode(',',$request->input('taluka_name'));
            }

            $schemenameforfiles = str_replace(' ','_',$request->input('scheme_name'));
            $dept_id = $request->input('dept_id');
            $con_id = $request->input('con_id');
            $scheme_name = $request->input('scheme_name');
            $priority = $request->input('priority');
            $reference_year = $request->input('reference_year');
            $major_objectives = json_encode($request->input('major_objective'));
            $major_indicators = json_encode($request->input('major_indicator'));
            $im_id = $request->input('im_id');
            $nodal_id = $request->input('nodal_id');
            $adviser_id = $request->input('adviser_id');
            $sponser_state = $request->input('state_ratio');
            $sponser_central = $request->input('center_ratio');
            $scheme_overview = $request->input('scheme_overview');
            $scheme_objective = $request->input('scheme_objective');
            $sub_scheme = $request->input('sub_scheme');
            $commencement_year = $request->input('commencement_year');
            $scheme_status = $request->input('scheme_status');
            $is_sdg = json_encode($request->input('sustainable_goals'));
            $scheme_beneficiary_selection_criteria = json_encode($request->input('beneficiary_selection_criteria'));
            $implementation_year = $request->input('implementation_year');
            $coverage_beneficiaries_remarks = $request->input('coverage_beneficiaries_remarks');
            $training_capacity_remarks = $request->input('training_capacity_remarks');
            $iec_activities_remarks = $request->input('iec_activities_remarks');
            $major_benefits_text = json_encode($request->input('major_benefits_text'));

            $major_benefits_name = '';
            if($request->hasFile('major_benefits')) {
                $major_benefits = $request->file('major_benefits');
                $major_benefits_name = 'major_benefits_'.$schemenameforfiles.'_'.mt_rand('000000000000000','999999999999999').".".$major_benefits->extension();
            }
            $scheme_implementing_procedure = $request->input('scheme_implementing_procedure');
            $beneficiariesGeoLocal = $request->input('beneficiariesGeoLocal');
            $otherbeneficiariesGeoLocal = $request->input('otherbeneficiariesGeoLocal');
            $geographical_coverage_name = '';
            if($request->hasFile('geographical_coverage')) {
                $geographical_coverage = $request->file('geographical_coverage');
                $geographical_coverage_name = 'geographical_coverage_'.$schemenameforfiles.'_'.mt_rand('000000000000000','999999999999999').".".$geographical_coverage->extension();
            }
            $beneficiaries_coverage_name = '';
            if($request->hasFile('beneficiaries_coverage')) {
                $beneficiaries_coverage = $request->file('beneficiaries_coverage');
                $beneficiaries_coverage_name = 'beneficiaries_coverage_'.$schemenameforfiles.'_'.mt_rand('000000000000000','999999999999999').".".$beneficiaries_coverage->extension();
            }
            $training_name = '';
            if($request->hasFile('training')) {
                $training = $request->file('training');
                $training_name = 'training_'.$schemenameforfiles.'_'.mt_rand('000000000000000','999999999999999').".".$training->extension();
            }
            $iec_name = '';
            if($request->hasFile('iec_file')) {
                $iec = $request->file('iec_file');
                $iec_name = 'iec_'.$schemenameforfiles.'_'.mt_rand('000000000000000','999999999999999').".".$iec->extension();
            }

            $convergencewithotherscheme = $request->input('convergencewithotherscheme');
            $convergence_dept_ids = json_encode($request->input('convergence_dept_id'));
            $convergence_text = json_encode($request->input('convergence_text'));

            $benefit_to = $request->input('benefit_to');

            $gr_names = array();
            if($request->hasFile('gr')) {
                $gr = $request->file('gr');
                foreach($gr as $key=>$value) {
                    $gr_name = 'gr_'.$schemenameforfiles.'_'.mt_rand('000000000000000','999999999999999').".".$value->extension();
                    $gr_names[] = $gr_name;
                }
            }

            $notification_names = array();
            if($request->hasFile('notification')) {
                $notification = $request->file('notification');
                foreach($notification as $key=>$value) {
                    $notification_name = 'notification_'.$schemenameforfiles.'_'.mt_rand('000000000000000','999999999999999').".".$value->extension();
                    $notification_names[] = $notification_name;
                }
            }

            $brochures = array();
            if($request->hasFile('brochure')) {
                $brochure = $request->file('brochure');
                foreach($brochure as $key=>$value) {
                    $brochure_name = 'brochure_'.$schemenameforfiles.'_'.mt_rand('000000000000000','999999999999999').".".$value->extension();
                    $brochures[] = $brochure_name;
                }
            }

            $pamphletsare = array();
            if($request->hasFile('pamphlets')) {
                $pamphlets = $request->file('pamphlets');
                foreach($pamphlets as $key=>$value) {
                    $pamphlets_name = 'pamphlets_'.$schemenameforfiles.'_'.mt_rand('000000000000000','999999999999999').".".$value->extension();
                    $pamphletsare[] = $pamphlets_name;
                }
            }

            $other_details_center_states = array();
            if($request->hasFile('otherdetailscenterstate')) {
                $other_details_center_state = $request->file('otherdetailscenterstate');
                foreach($other_details_center_state as $key=>$value) {
                    $other_details_center_state_name = 'other_details_of_scheme_'.$schemenameforfiles.'_'.mt_rand('000000000000000','999999999999999').".".$value->extension();
                    $other_details_center_states[] = $other_details_center_state_name;
                }
            }

            $eval_upload_report_name = '';
            if($request->hasFile('eval_upload_report')) {
                $eval_upload_report = $request->file('eval_upload_report');
                $eval_upload_report_name = 'eval_upload_report_'.$schemenameforfiles.'_'.mt_rand('000000000000000','999999999999999').".".$eval_upload_report->extension();
            }

            $major_indicator_hod = json_encode($request->input('major_indicator_hod'));
            $financial_progress = $request->input('financial_progress');
            $is_evaluation = $request->input('is_evaluation');

            $eval_by_whom = $request->input('eval_by_whom');
            $eval_when = $request->input('eval_when');
            $eval_geographical_coverage_beneficiaries = $request->input('eval_geographical_coverage_beneficiaries');
            $eval_nummber_of_beneficiaries = $request->input('eval_nummber_of_beneficiaries');
            $eval_major_recommendation = $request->input('eval_major_recommendation');

            $inputarr = array(
                            'dept_id'=>$dept_id,
                            'con_id'=>$con_id,
                            'scheme_name'=>$scheme_name,
                            'priority'=>$priority,
                            'reference_year'=>$reference_year,
                            'major_objective'=>$major_objectives,
                            'major_indicator'=>$major_indicators,
                            'im_id'=>$im_id,
                            'nodal_id'=>$nodal_id,
                            'adviser_id'=>$adviser_id,
                            'state_ratio'=>$sponser_state,
                            'center_ratio'=>$sponser_central,
                            'scheme_overview'=>$scheme_overview,
                            'scheme_objective'=>$scheme_objective,
                            'beneficiariesGeoLocal'=>$beneficiariesGeoLocal,
                            'districts'=>$district_codes,
                            'talukas'=>$taluka_codes,
                            'otherbeneficiariesGeoLocal'=>$otherbeneficiariesGeoLocal,
                            'sub_scheme'=>$sub_scheme,
                            'commencement_year'=>$commencement_year,
                            'scheme_status'=>$scheme_status,
                            'is_sdg'=>$is_sdg,
                            'scheme_beneficiary_selection_criteria'=>$scheme_beneficiary_selection_criteria,
                            'benefit_to_file'=>$major_benefits_name,
                            'major_benefits_text' => $major_benefits_text,
                            'scheme_implementing_procedure'=>$scheme_implementing_procedure,
                            'geographical_coverage'=>$geographical_coverage_name,
                            'beneficiaries_coverage'=>$beneficiaries_coverage_name,
                            'implementation_year'=>$implementation_year,
                            'training'=>$training_name,
                            'iec'=>$iec_name,
                            'benefit_to'=>$benefit_to,
                            'convergencewithotherscheme'=>$convergencewithotherscheme,
                            'convergence_dept_ids'=>$convergence_dept_ids,
                            'convergence_text'=>$convergence_text,
                            'major_indicator_hod'=>$major_indicator_hod,
                            'is_evaluation'=>$is_evaluation,
                            'entered_by'=>Auth::user()->name,
                            'entry_date'=>date('Y-m-d H:i:s'),
                            'coverage_beneficiaries_remarks'=>$coverage_beneficiaries_remarks,
                            'training_capacity_remarks'=>$training_capacity_remarks,
                            'iec_activities_remarks'=>$iec_activities_remarks,
                            'eval_scheme_bywhom'=>$eval_by_whom,
                            'eval_scheme_when'=>$eval_when,
                            'eval_scheme_geo_cov_bene'=>$eval_geographical_coverage_beneficiaries,
                            'eval_scheme_no_of_bene'=>$eval_nummber_of_beneficiaries,
                            'eval_scheme_major_recommendation'=>$eval_major_recommendation,
                            'eval_scheme_report'=>$eval_upload_report_name
                        );


            Scheme::where('scheme_id',$scheme_id)->update($inputarr);

            $schmeid = $scheme_id;
            $extended = new Couchdb();
            $extended->InitConnection();
            $status = $extended->isRunning();
            $doc_id = "scheme_".$schmeid;
            $dummy_data = array(
                'scheme_id' => $doc_id
            );

            $multifiles = array();
            $singlefiles = array();
            $documents = $request->file();

            foreach($documents as $docid => $document) {
                if(is_array($document)) {
                    $j = 1;
                    foreach($document as $docidis => $documentis) {
                        $file = Attachment::where('scheme_id',$schmeid)->first();
                        $file_data = json_decode($file,true);
                        $rev = $file_data['couch_rev_id'];

                        $path['id'] = $docid;
                        $path['tmp_name'] = $documentis->getRealPath();
                        $path['extension']  = $documentis->getClientOriginalExtension();
                        $path['name'] = $doc_id.'_'.$path['id'].'_'.$j.'.'.$path['extension'];
                        $multifiles[] = $path;
                        $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                        $array = json_decode($out, true);
                        $rev = $array['rev'];
                        if(isset($rev)) {
                            $result = Attachment::where('scheme_id',$schmeid)->update(['couch_rev_id'=>$rev]);
                        }
                        $j++;
                    }
                } else {
                    $file = Attachment::where('scheme_id',$schmeid)->first();
                    $file_data = json_decode($file,true);
                    $rev = $file_data['couch_rev_id'];

                    $path['id'] = $docid;
                    $path['tmp_name'] = $document->getRealPath();
                    $path['extension']  = $document->getClientOriginalExtension();
                    $path['name'] = $doc_id.'_'.$path['id'].'.'.$path['extension'];
                    $singlefiles[] = $path;
                    $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                    $array = json_decode($out, true);
                    $rev = $array['rev'];
                    if(isset($rev)){    
                        $result = Attachment::where('scheme_id',$schmeid)->update(['couch_rev_id'=>$rev]);
                    }                    
                }
            }
            $act['userid'] = \Auth::user()->id;
            $act['ip'] = $request->ip();
            $act['activity'] = 'Scheme Entry';
            $act['officecode'] = $dept_id;
            $act['pagereferred'] = $request->url();
            $act = Activitylog::insert($act);
            $arr = array();
            foreach($financial_progress as $k=>$v) {
                $arr[] = array('scheme_id'=>$schmeid,'financial_year'=>$v['financial_year'],'units'=>$v['units'],'target'=>$v['target'],'achievement'=>$v['achivement'],'allocation'=>$v['allocation'],'expenditure'=>$v['expenditure']);
            }
            if(!empty($arr)) {
                FinancialProgress::where('scheme_id',$scheme_id)->delete();
                FinancialProgress::insert($arr);
            }

            $gr_files = array();
            foreach($gr_names as $key=>$value) {
                $gr_files[] = array('scheme_id'=>$schmeid,'file_name'=>$value);
            }
            if(!empty($gr_files)) {
                GrFilesList::where('scheme_id',$scheme_id)->delete();
                GrFilesList::insert($gr_files);
            }

            $notification_files = array();
            foreach($notification_names as $key => $value) {
                $notification_files[] = array('scheme_id'=>$schmeid,'file_name'=>$value);
            }
            if(!empty($notification_files)) {
                NotificationFileList::where('scheme_id',$scheme_id)->delete();
                NotificationFileList::insert($notification_files);
            }

            $brochures_files = array();
            foreach($brochures as $key => $value) {
                $brochures_files[] = array('scheme_id'=>$schmeid,'file_name'=>$value);
            }
            if(!empty($brochures_files)) {
                BrochureFileList::where('scheme_id',$scheme_id)->delete();
                BrochureFileList::insert($brochures_files);
            }

            $pamphlet_files = array();
            foreach($pamphletsare as $key => $value) {
                $pamphlet_files[] = array('scheme_id'=>$schmeid,'file_name'=>$value);
            }
            if(!empty($pamphlet_files)) {
                PamphletFileList::where('scheme_id',$scheme_id)->delete();
                PamphletFileList::insert($pamphlet_files);
            }

            $other_details_center_states_files = array();
            foreach($other_details_center_states as $key => $value) {
                $other_details_center_states_files[] = array('scheme_id'=>$schmeid,'file_name'=>$value);
            }
            if(!empty($other_details_center_states_files)) {
                CenterStateFiles::where('scheme_id',$scheme_id)->delete();
                CenterStateFiles::insert($other_details_center_states_files);
            }

            return redirect()->to('schemes');
        }
    }

    public function edit(){
        //
    }

    public function update(Request $request){
        //
    }

    public function show(){
        //
    }

   

    public function requestproposal() {
        $user = \Auth::user();
        $schemes = Scheme::where('dept_id','=',$user->dept_id)
                   ->select('scheme_id','scheme_name')->orderBy('scheme_id','desc')->get();
        return view('schemes.request_proposal',compact('schemes'));
    }

    public function schemedata() {
        $schemedata = Scheme::orderBy('scheme_id','desc')->get();
        return view('schemes.schemedata',compact('schemedata'));
    }

    public function createproposal(Request $request) {
        $validate = Validator($request->all(),[
            'scheme_id'=>'required',
        ],[
            'scheme_id.required' => 'Scheme is required',
           
        ]);
        if($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate->errors());
        } else {
            $replace_url = URL::to('/');
            $scheme_id = $request->input('scheme_id');
            $priority = $request->input('priority');
            $duration = $request->input('timeforeval');
            $schemedata = Scheme::where('scheme_id',$scheme_id)->get();
            $get5yearback = FinancialProgress::where('scheme_id',$scheme_id)->get();
            $beneficiariesGeoLocal = beneficiariesGeoLocal();

            $thefinancialarray = array();
            foreach($get5yearback as $key=>$value) {
                    $thefinancialarray[] = $value;
               
            }


            $dept_name='';
            $is_evaluation = 'No';
            foreach($schemedata as $key=>$value) {
                $dept_name = Department::where('dept_id',$value->dept_id)->value('dept_name');
                $is_evaluation = $value->is_evaluation;
            }

            $convener = '';
            $district_ids = array();
            foreach($schemedata as $key=>$value) {
                $convener = Convener::where('con_id',$value->con_id)->get();
                if($value->districts) {
                    $district_ids = json_decode($value->districts);
                }
            }
            $district_names = array();
            if(!empty($district_ids)) {
                foreach($district_ids as $dists) {
                    $district_name = Districts::select('dcode','name_e')->where('dcode',$dists)->get();
                    $district_names[] = $district_name->toArray();
                }
            }
            $thefinancialarray_count = count($thefinancialarray);
            $major_hod_indicators = array();
            $taluka_ids = array();
            foreach($schemedata as $key=>$value) {
                $major_hod_indicators = $value->major_indicator_hod;
                if($value->talukas != 'null') {
                    $taluka_ids = json_decode($value->talukas);
                }
              
            }
            $talukaids = array();
            if(!empty($taluka_ids)) {
                foreach($taluka_ids as $tal) {
                    $talukaids[] = $tal;
                }
            }
            $talukas = Taluka::select('tcode','tname_e')->whereIn('tcode',$taluka_ids)->get();

            $grfiles = $this->getthefilecount($scheme_id,'_gr');
            $notificationfiles = $this->getthefilecount($scheme_id,'_notification');
            $brochurefiles = $this->getthefilecount($scheme_id,'_brochure');
            $pamphletfiles = $this->getthefilecount($scheme_id,'_pamphlets');
            $centerstatefiles = $this->getthefilecount($scheme_id,'_otherdetailscenterstate');
            $eval_report = $this->getthefilecount($scheme_id,'_eval_upload_report');

            return view('schemes.create_proposal',compact('scheme_id','schemedata','dept_name','convener','priority','duration','thefinancialarray','beneficiariesGeoLocal','thefinancialarray_count','major_hod_indicators','replace_url','grfiles','notificationfiles','brochurefiles','pamphletfiles','centerstatefiles','district_names','talukas','is_evaluation','eval_report'));
        }
    }

    public function scheme_edit($id) {
        $scheme = Scheme::where('scheme_id',$id)->get();

        $financial_years = financialyears();
        $goals = Sdggoals::where('status','1')->orderBy('goal_id','desc')->get();
        $user_id = auth()->user()->role;
        $dept = DB::table('users')
                ->leftjoin('public.user_user_role_deptid','user_id','=','users.id')
                ->leftjoin('imaster.departments','imaster.departments.dept_id','=','user_user_role_deptid.dept_id')
                ->where('users.id','=',$user_id)
                ->select('departments.dept_name','departments.dept_id')
                ->get();
        $departments = Department::orderBy('dept_name')->get();
        $conveners = Convener::all();
        $implementations = Implementation::all();
        $advisers = Adviser::all();
        $beneficiariesGeoLocal = beneficiariesGeoLocal();
        $financial_progress = FinancialProgress::where('scheme_id',$id)->get();
        
        return view('schemes.scheme_edit',compact('scheme','departments','conveners','implementations','advisers','beneficiariesGeoLocal','dept','financial_years','goals','financial_progress'));
    }

    public function downloadpdf(Request $request) {
        $filetype = $request->get('filetype');
        $scheme_id = $request->get('scheme');
        $thefilename = Scheme::where('scheme_id',$scheme_id)->value('service_maintenance_community');
        if($thefilename == '') {
            return response()->json('File note found');
        } else {
          
            $path = public_path('storage/schemes/'.$thefilename);
            $headers = array(
              'Content-Type: application/pdf',
            );
            return response()->download($path,'community.pdf',$headers);
        }
    }

    public function submitdepts(Request $request) {
        $scheme_id = $request->input('scheme_id');
        $dept_id = $request->input('dept_id');
        $con_id = $request->input('con_id');

        $scheme_name = $request->input('scheme_name');
        $reference_year = $request->input('reference_year');
        $major_objectives = json_encode($request->input('major_objective'));
        $major_indicators = json_encode($request->input('major_indicator'));
        $im_id = $request->input('im_id');

        $nodal_id = $request->input('nodal_id');
        $adviser_id = $request->input('adviser_id');
        $center_ratio = $request->input('center_ratio');
        $state_ratio = $request->input('state_ratio');
        $scheme_overview = $request->input('scheme_overview');

        $scheme_objective = $request->input('scheme_objective');
        $financial_year = $request->input('financial_year');
    
        $sub_scheme = $request->input('sub_scheme');
        $commencement_year = $request->input('commencement_year');
        $scheme_status = $request->input('scheme_status');
        $is_sdg = $request->input('is_sdg');
        $scheme_beneficiary_selection_criteria = $request->input('scheme_beneficiary_selection_criteria');
        $scheme_implementing_procedure = $request->input('scheme_implementing_procedure');
        $major_indicator_hod = json_encode($request->input('major_indicator_hod'));
        $is_evaluation = $request->input('is_evaluation');
        $in_draft = $request->input('in_draft');
        $eval_scheme_bywhom = $request->input('eval_scheme_bywhom');
        $eval_scheme_when = $request->input('eval_scheme_when');
        $eval_scheme_geo_cov_bene = $request->input('eval_scheme_geo_cov_bene');
        $eval_scheme_no_of_bene = $request->input('eval_scheme_no_of_bene');
        $eval_scheme_major_recommendation = $request->input('eval_scheme_major_recommendation');
        $eval_scheme_report = $request->input('eval_scheme_report');

        $entry_date = date('Y-m-d H:i:s');
        $entered_by = $request->input('entered_by');
        $updated_on = '';
        $updated_by = Auth::user()->id;
        $convergencewithotherscheme = $request->input('convergencewithotherscheme');
        $beneficiariesGeoLocal = $request->input('beneficiariesGeoLocal');
        $districts = '';
        if($beneficiariesGeoLocal == '4') {
            $districts = Scheme::where('scheme_id',$scheme_id)->value('districts');
        }
        $talukas = '';
        if($beneficiariesGeoLocal == '8') {
            $talukas = Scheme::where('scheme_id',$scheme_id)->value('talukas');
        }
        $otherbeneficiariesGeoLocal = $request->input('otherbeneficiariesGeoLocal');

        $geographical_coverage = $request->input('geographical_coverage');
        $beneficiaries_coverage = $request->input('beneficiaries_coverage');
        $training = $request->input('training');
        $iec = $request->input('iec');
        $benefit_to = $request->input('benefit_to');
        $priority = $request->input('priority');
        $sender_email = Auth::user()->email;
        $durationforcompletion = $request->input('durationforcompletion');
        $evaldoneinpast = $request->input('evaldoneinpast');

        $arr = array(
                    '_token'=>csrf_token(),
                    'dept_id'=>$dept_id,
                    'con_id'=>$con_id,
                    'scheme_name'=>$scheme_name,
                    'priority'=>$priority,
                    'reference_year'=>$reference_year,
                    'major_objective'=>$major_objectives,
                    'major_indicator'=>$major_indicators,
                    'im_id'=>$im_id,
                    'nodal_id'=>$nodal_id,
                    'adviser_id'=>$adviser_id,
                    'center_ratio'=>$center_ratio,
                    'state_ratio'=>$state_ratio,
                    'scheme_overview'=>$scheme_overview,
                    'scheme_objective'=>$scheme_objective,
                    'sub_scheme'=>$sub_scheme,
                    'commencement_year'=>$commencement_year,
                    'scheme_status'=>$scheme_status,
                    'is_sdg'=>$is_sdg,
                    'scheme_beneficiary_selection_criteria'=>$scheme_beneficiary_selection_criteria,
                    'scheme_implementing_procedure'=>$scheme_implementing_procedure,
                    'major_indicator_hod'=>$major_indicator_hod,
                    'is_evaluation'=>$is_evaluation,
                    'in_draft'=>$in_draft,
                    'entry_date'=>$entry_date,
                    'entered_by'=>$entered_by,
                    'updated_on'=>date('Y-m-d H:i:s'),
                    'updated_by'=>$updated_by,
                    'convergencewithotherscheme'=>$convergencewithotherscheme,
                    'scheme_id'=>$scheme_id,
                    'otherbeneficiariesGeoLocal'=>$otherbeneficiariesGeoLocal,
                    'geographical_coverage'=>$geographical_coverage,
                    'beneficiaries_coverage'=>$beneficiaries_coverage,
                    'districts'=>$districts,
                    'talukas'=>$talukas,
                    'training'=>$training,
                    'iec'=>$iec,
                    'benefit_to'=>$benefit_to,
                    'sender_email'=>$sender_email,
                    'created_at'=>date('Y-m-d H:i:s'),
                    'beneficiariesGeoLocal'=>$beneficiariesGeoLocal,
                    'financial_year'=>$financial_year,
                    'eval_scheme_bywhom'=>$eval_scheme_bywhom,
                    'eval_scheme_when'=>$eval_scheme_when,
                    'eval_scheme_geo_cov_bene'=>$eval_scheme_geo_cov_bene,
                    'eval_scheme_no_of_bene'=>$eval_scheme_no_of_bene,
                    'eval_scheme_major_recommendation'=>$eval_scheme_major_recommendation,
                    'eval_scheme_report'=>$eval_scheme_report
                );
        Proposal::create($arr);
        return redirect()->route('schemes.proposals');
    }

    public function listproposals() {
        $this->get_count('proposals');
        $the_status_id = '23';
        $dept_id = Auth::user()->dept_id;
        $proposals_forwarded = SchemeSend::leftjoin('itransaction.proposals','itransaction.scheme_send.draft_id','=','itransaction.proposals.draft_id')
                ->select('scheme_send.*','proposals.draft_id as prop_draft_id','proposals.dept_id as prop_dept_id','proposals.scheme_name','proposals.scheme_overview','proposals.scheme_objective','proposals.sub_scheme','proposals.status_id as prop_status_id')
                ->where('scheme_send.forward_btn_show','1')
                ->where('scheme_send.dept_id',$dept_id)
                ->where('scheme_send.status_id',$the_status_id)
                ->orderBy('scheme_send.status_id','asc')
                ->orderBy('scheme_send.id','desc')
                ->get();

        $sentprops = SchemeSend::pluck('draft_id');
        $proposals_new = Proposal::whereNotIn('draft_id',$sentprops)->where('dept_id',$dept_id)->orderBy('draft_id','desc')->get();

        $status_id_return = '24';
        $proposals_returned = SchemeSend::leftjoin('itransaction.proposals','itransaction.scheme_send.draft_id','=','itransaction.proposals.draft_id')
                    ->select('scheme_send.*','proposals.draft_id as prop_draft_id','proposals.dept_id as prop_dept_id','proposals.scheme_name','proposals.scheme_overview','proposals.scheme_objective','proposals.sub_scheme', 'proposals.status_id as prop_status_id')
                    ->where('scheme_send.status_id',$status_id_return)
                    ->orWhere('scheme_send.status_id','26')
                    ->where('scheme_send.dept_id',$dept_id)
                    ->where('scheme_send.forward_btn_show','1')
                    ->orderBy('scheme_send.id','desc')
                    ->get();
        return view('schemes.proposals',compact('proposals_new','proposals_forwarded','proposals_returned','the_status_id','status_id_return'));
    }

    public function newproposaldetail($draft_id) {
        $goals = Sdggoals::where('status','1')->orderBy('goal_id','desc')->get();
        $proposal_list = Proposal::with(['gr_file','notification_files','brochure_files','pamphlets_files','otherdetailscenterstate_files'])->where('draft_id',Crypt::decrypt($draft_id))->get();
      
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
        return view('schemes.newproposaldetail',compact('proposal_list','dept_name','major_objectives','major_indicators','imdept','major_indicator_hod','financial_progress','replace_url','scheme_id','beneficiariesGeoLocal','bencovfile','trainingfile','iecfile','district_names','dept_names','taluka_names','conv_dept_remarks','goals','entered_goals','eval_report','gr_files','notification_files','brochure_files','pamphlets_files','otherdetailscenterstate_files','the_convergence'));
    }

    public function proposaldetail($draft_id) {
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
        return view('schemes.proposaldetail',compact('proposal_list','dept_name','major_objectives','major_indicators','imdept','major_indicator_hod','financial_progress','replace_url','scheme_id','beneficiariesGeoLocal','bencovfile','trainingfile','iecfile','district_names','dept_names','taluka_names','conv_dept_remarks','goals','entered_goals','eval_report','gr_files','notification_files','brochure_files','pamphlets_files','otherdetailscenterstate_files','the_convergence'));
    }

  

    public function schemedetail($scheme_id) {
        $goals = Sdggoals::where('status','1')->orderBy('goal_id','desc')->get();
        $schemes = Scheme::where('scheme_id',$scheme_id)->get();
        $replace_url = URL::to('/');
        $dept_name = '';
        $convener = array();
        $major_objectives = array();
        $imdept = array();
        $adviseris = array();
        $major_indicator_hod = array();
        $financial_progress = array();
        $beneficiariesGeoLocal = beneficiariesGeoLocal();
        $bencovfile = $this->getthefilecount($scheme_id,'_beneficiaries_coverage');
        $trainingfile = $this->getthefilecount($scheme_id,'_training');
        $iecfile = $this->getthefilecount($scheme_id,'_iec');
        $eval_report = $this->getthefilecount($scheme_id,'_eval_report_');
        $gr_files = $this->getthefilecount($scheme_id,'_gr');
        $notification_files = $this->getthefilecount($scheme_id,'_notification');
        $brochure_files = $this->getthefilecount($scheme_id,'_brochure');
        $pamphlets_files = $this->getthefilecount($scheme_id,'_pamphlets');
        $otherdetailscenterstate_files = $this->getthefilecount($scheme_id,'_otherdetailscenterstate');
        $district_ids = array();
        $taluka_ids = array();
        $dept_name = array();
        $conv_dept_ids = array();
        $conv_dept_remarks = array();
        $entered_goals = array();
        $all_convergence = array();
        foreach($schemes as $key=>$value) {
            $dept_name = Department::where('dept_id',$value->dept_id)->value('dept_name');
            $major_objectives = $value->major_objective;
            $major_indicators = $value->major_indicator;
            $imdept = Implementation::where('id',$value->im_id)->get();
            $major_indicator_hod = $value->major_indicator_hod;
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
        return view('schemes.schemedetail',compact('schemes','dept_name','major_objectives','major_indicators','imdept','major_indicator_hod','financial_progress','replace_url','scheme_id','beneficiariesGeoLocal','bencovfile','trainingfile','iecfile','district_names','dept_names','taluka_names','conv_dept_remarks','goals','entered_goals','eval_report','gr_files','notification_files','brochure_files','pamphlets_files','otherdetailscenterstate_files','the_convergence'));
    }

    public function meetings() {
        $user_id = Auth::user()->id;
        $user_role_id = Auth::user()->role;
        $schemes = SchemeSend::select('proposals.draft_id','proposals.scheme_name','proposals.scheme_id')->leftjoin('itransaction.proposals','scheme_send.draft_id','=','proposals.draft_id')->where('scheme_send.status_id','24')->orWhere('scheme_send.status_id','26')->where('scheme_send.forward_btn_show','1')->orderBy('scheme_send.id','desc')->get();
        $schemelist = array();
        $i = 0;
        $j = 0;
        foreach($schemes as $sc) {
            if($j == 0) {
                $schemelist[] = array('draft_id'=>$sc->draft_id,'scheme_name'=>$sc->scheme_name,'scheme_id'=>$sc->scheme_id);
            } else {
                $plucked_arr = Arr::pluck($schemelist,'draft_id');
                if(!in_array($sc->draft_id,$plucked_arr)) {
                    $schemelist[] = array('draft_id'=>$sc->draft_id,'scheme_name'=>$sc->scheme_name,'scheme_id'=>$sc->scheme_id);
                    $i++;
                }
            }
            $j++;
        }
        $departments = Department::orderBy('dept_name','asc')->get();
        $meetings = Meetinglog::where('user_id',$user_id)->where('user_role_id',$user_role_id)->orderBy('mid','desc')->get();
        $attendees = User::select('id','name')->where('role','3')->orWhere('role','4')->get();
        $implementations = Implementation::all();
        $aftermeeting = Aftermeeting::orderBy('amid','desc')->get();
        $replace_url = URL::to('/');
        return view('schemes.meetings',compact('meetings','attendees','schemelist','departments','implementations','aftermeeting','replace_url'));
    }

    public function addmeeting(Request $request) {
        $validate = Validator::make($request->all(),[
            'draft_id' => 'required|numeric',
            'subject' => 'required|string|max:100',
            'description' => 'required|string|max:990',
            'chairperson' => 'required|string|max:100',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'venue' => 'required|max:50',
            'attendees' => 'required',
            'document' => 'required|mimes:docx,pdf,xlsx',
        ]);
        if($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate->errors());
        } else {
            if($request->input('venue') == '' and $request->input('venue_of_meeting_text') == '') {
                return redirect()->back();
            }
            $venue = '';
            if($request->input('venue_of_meeting_text') == '') {
                $venue = $request->input('venue');
            }  else {
                $venue = $request->input('venue_of_meeting_text');
            }
            $draft_id = $request->input('draft_id');
            $scheme_id = Proposal::where('draft_id',$draft_id)->value('scheme_id');
            $dept_id = Proposal::where('draft_id',$draft_id)->value('dept_id');
            $scheme_name = Scheme::where('scheme_id',$draft_id)->value('scheme_name');
            $subject = $request->input('subject');
            $description = $request->input('description');
            $chairperson = $request->input('chairperson');
            $date = $request->input('date');
            $time = $request->input('time');
            $created_at = Carbon::now();
            $attendees = $request->input('attendees');
        

            $attendees_emails = User::whereIn('id',$request->input('attendees'))->pluck('email');

            $details = array('draft_id'=>$draft_id, 'dept_id'=>$dept_id,'scheme_name'=>$scheme_name,'subject'=>$subject,'description'=>$subject,'description'=>$description,'venue'=>$venue,'chairperson'=>$chairperson,'attendees'=>$attendees,'date'=>$date,'time'=>$time,'created_at'=>$created_at);

            $path = array();
            $document = $request->file();
            $rev = '';
            $flname = '';
            foreach($document as $dkey=>$dval) {
                $doc_id= 'scheme_'.$scheme_id;
                $extended = new Couchdb();
                $extended->InitConnection();
                $status = $extended->isRunning();
                $file = Attachment::where('couch_doc_id',$doc_id)->first();
                $file_data = json_decode($file,true);
                $rev = $file_data['couch_rev_id'];
                $path['id'] = $doc_id;
                $path['tmp_name'] = $dval->getRealPath();
                $path['extension']  = $dval->getClientOriginalExtension();
                $path['name'] = $doc_id.'_setmeeting_'.$path['extension'];
                $flname = $path['name'];
                $details['filename'] = $flname;
                $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                $array = json_decode($out, true);
                if(array_key_exists('error',$array)) {
                    echo $rev;
                    print_r($array);
                    die();
                } else {
                    $id = $array['id'];
                    $rev = $array['rev'];
                    $data['couch_rev_id'] = $rev;
                    $attachment = Attachment::where('scheme_id',$scheme_id)->update($data);
                }
            }
            $user_id = Auth::user()->id;
            $user_role_id = Auth::user()->role;
            $insertQRY = array('draft_id'=>$draft_id,'scheme_id'=>$scheme_id,'dept_id'=>$dept_id,'subject'=>$subject,'description'=>$subject,'description'=>$description,'chairperson'=>$chairperson,'attendees'=>$attendees,'date'=>$date,'time'=>$time, 'venue'=>$venue, 'filename'=>$flname,'created_at'=>$created_at,'user_id'=>$user_id,'user_role_id'=>$user_role_id);
            if(isset($rev)) {
                $result = Attachment::where('couch_doc_id',$doc_id)->update(['couch_rev_id'=>$rev]);
                if($result) {
                    Meetinglog::insert($insertQRY);
                    $act['userid'] = Auth::user()->id;
                    $act['ip'] = $request->ip();
                    $act['activity'] = 'Meeting added by Eval Director';
                    $act['officecode'] = $dept_id;
                    $act['pagereferred'] = $request->url();
                    Activitylog::insert($act);
                    return redirect()->back()->withSuccess('Email Sent & Meeting Schedule successfully Created !');
                } else {
                    return redirect()->back()->withError('Error: Meeting 402, Contact NIC.');
                }
            } else {
                return redirect()->back()->withError('Error: Meeting 402, Contact NIC.');
            }

            return redirect()->back();
        }
    }

    public function postpone_meeting(Request $request) {
        $validate = Validator::make($request->all(),[
            'postpone_mid' => 'required|numeric',
            'postpone_date' => 'required|date',
            'postpone_time' => 'required|date_format:H:i'
        ]);
        if($validate->fails($validate->errors())) {
            return redirect()->back()->withInput()->withErrors($validate->errors());
        } else {
            $mid = $request->get('postpone_mid');
            $date = $request->get('postpone_date');
            $time = $request->get('postpone_time');
            $thetime = array('date'=>$date,'time'=>$time);
            Meetinglog::where('mid',$mid)->update($thetime);

            $data = Meetinglog::where('mid',$mid)->get();
            $detaildata = $data->toArray();
            $dept_id = 0;
            if(count($detaildata) > 0) {
                $details = $detaildata[0];
                $scheme_id = $detaildata[0]['draft_id'];
                $scheme_name = Scheme::where('scheme_id',$scheme_id)->value('scheme_name');
                $details['venue'] = Meetinglog::where('draft_id',$scheme_id)->value('venue');
                $details['scheme_name'] = $scheme_name;
                $attendees = array();
                    $att = json_decode($details['attendees'],true);
                    foreach($att as $attkey=>$attval) {
                        $attendees[] = $attval;
                    }
                $dept_id = $detaildata[0]['dept_id'];
                if(count($attendees)) {
                    $attendees_ids = $attendees[0];
                    $attendees_emails = User::whereIn('id',$attendees_ids)->pluck('email');
                }
            }
            $act['userid'] = Auth::user()->id;
            $act['ip'] = $request->ip();
            $act['activity'] = 'Meeting updated by Eval Director';
            $act['officecode'] = $dept_id;
            $act['pagereferred'] = $request->url();
            Activitylog::insert($act);
            return redirect()->back()->with('postpone_msg','Meeting postponed successfully !!!!!');
        }
    }

    public function finishmeeting(Request $request) {
        $mid = $request->get('mid');
      
        $fmeeting = Meetinglog::leftjoin('itransaction.proposals','meetinglogs.draft_id','=','proposals.draft_id')
                        ->leftjoin('imaster.departments','departments.dept_id','=','meetinglogs.dept_id')
                        ->where('meetinglogs.mid',$mid)
                        ->get();
        $attendees_table_id = User::select('id')->where('role','3')->orWhere('role','4')->get();
        $att_arr = array();
        if($attendees_table_id->isNotEmpty()) {
            $attendees_toarray = $attendees_table_id->toArray();
            foreach($attendees_toarray as $at_tokey => $at_toval) {
                $att_arr[] = $at_toval['id'];
            }
        }
        $attendees_ids = array();
        if($fmeeting->isNotEmpty()) {
            $ss = json_decode($fmeeting[0]['attendees']);
            foreach($ss->users as $skey=>$sval) {
                if(in_array($sval,$att_arr)) {
                    $attendees_ids[] = $sval;
                }
            }
        }
        $attendees = User::select('id','name')->whereIn('id',$attendees_ids)->get();
        $arr = array('mid'=>$mid,'fmeeting'=>$fmeeting,'attendees'=>$attendees);
        return response()->json($arr);
    }

    public function updatemeeting(Request $request) {
        $validate = Validator::make($request->all(),[
            'mid' => 'required|numeric',
            'draft_id' => 'required|numeric',
            'subject' => 'required|string|max:100',
            'description' => 'required|string|max:990',
            'chairperson' => 'required|string|max:100',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'venue' => 'required|string',
            'attendees' => 'required',
            'meeting_minutes' => 'required|mimes:pdf,docx,xlsx',
            'meeting_attendance' => 'required|mimes:pdf,docx,xlsx'
        ]);
        if($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate->errors());
        } else {
            $mid = $request->input('mid');
            $draft_id = $request->input('draft_id');
            $dept_id = Proposal::where('draft_id',$draft_id)->value('dept_id');
            $scheme_id = Proposal::where('draft_id',$draft_id)->value('scheme_id');
            $scheme_name = Scheme::where('scheme_id',$scheme_id)->value('scheme_name');
            $subject = $request->input('subject');
            $description = $request->input('description');
            $chairperson = $request->input('chairperson');
            $date = $request->input('date');
            $time = $request->input('time');
            $venue = $request->input('venue');
            $created_at = Carbon::now();
            $attendees = json_encode(array('users'=>$request->input('attendees')));
       
            $attendees_emails = User::whereIn('id',$request->input('attendees'))->pluck('email');

            $details = array('filesare'=>'multiple','draft_id'=>$draft_id,'scheme_id'=>$scheme_id,'dept_id'=>$dept_id,'scheme_name'=>$scheme_name,'subject'=>$subject,'description'=>$subject,'description'=>$description,'venue'=>$venue,'chairperson'=>$chairperson,'attendees'=>$attendees,'date'=>$date,'time'=>$time, 'mid'=>$mid, 'created_at'=>$created_at);
            $flname = array();
            $path = array();
            $i = 0;
            $details['filename'] = array();
            if($request->hasFile('meeting_minutes')) {
                $meeting_minutes = $request->file('meeting_minutes');
                $rev = '';
                $doc_id= 'scheme_'.$scheme_id;
                $extended = new Couchdb();
                $extended->InitConnection();
                $status = $extended->isRunning();
                $file = Attachment::where('couch_doc_id',$doc_id)->first();
                $file_data = json_decode($file,true);
                $rev = $file_data['couch_rev_id'];
                $path['id'] = $doc_id;
                $path['tmp_name'] = $meeting_minutes->getRealPath();
                $path['extension']  = $meeting_minutes->getClientOriginalExtension();
                $path['name'] = $doc_id.'_'.'meeting_minutes_'.$mid.'_aftermeeting'.'.'.$path['extension'];
                $flname[] = $path['name'];
                $details['filename'][$i] = $path['name'];

                $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                $array = json_decode($out, true);
                if(array_key_exists('error',$array)) {
                    echo 'rev error <br>';
                    $rev = '';
                    print_r($array);
                    die();
                } else {
                    $rev = $array['rev'];
                    $result = Attachment::where('couch_doc_id',$doc_id)->update(['couch_rev_id'=>$rev]);
                }
                $i++;
            }
            if($request->hasFile('meeting_attendance')) {
                $meeting_attendance = $request->file('meeting_attendance');
                $rev = '';
                $doc_id= 'scheme_'.$scheme_id;
                $extended = new Couchdb();
                $extended->InitConnection();
                $status = $extended->isRunning();
                $file = Attachment::where('couch_doc_id',$doc_id)->first();
                $file_data = json_decode($file,true);
                $rev = $file_data['couch_rev_id'];
          
                $path['id'] = $doc_id;
                $path['tmp_name'] = $meeting_attendance->getRealPath();
                $path['extension']  = $meeting_attendance->getClientOriginalExtension();
                $path['name'] = $doc_id.'_'.'meeting_attendance_'.$mid.'_aftermeeting'.'.'.$path['extension'];
                $flname[] = $path['name'];
                $details['filename'][$i] = $path['name'];

                $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                $array = json_decode($out, true);
                if(array_key_exists('error',$array)) {
                    echo 'rev error <br>';
                    $rev = '';
                    print_r($array);
                    die();
                } else {
                    $rev = $array['rev'];
                    $result = Attachment::where('couch_doc_id',$doc_id)->update(['couch_rev_id'=>$rev]);
                }
                $i++;
            }
            $theflnames = implode(',',$flname);
            $insertQRY = array('draft_id'=>$draft_id, 'scheme_id'=>$scheme_id, 'dept_id'=>$dept_id, 'subject'=>$subject, 'description'=>$subject, 'description'=>$description, 'chairperson'=>$chairperson, 'attendees'=>$attendees, 'date'=>$date, 'time'=>$time, 'venue'=>$venue, 'mid'=>$mid, 'filename'=>$theflnames, 'created_at'=>$created_at);
            if(isset($rev)) {
                Aftermeeting::insert($insertQRY);
                Meetinglog::where('mid',$mid)->update(['status'=>'1']);
                $act['userid'] = Auth::user()->id;
                $act['ip'] = $request->ip();
                $act['activity'] = 'Meeting conducted by Eval Director';
                $act['officecode'] = $dept_id;
                $act['pagereferred'] = $request->url();
                Activitylog::insert($act);
                return redirect()->back()->withSuccess('Email Sent & Meeting Schedule successfully Created !');
            } else {
                return redirect()->back()->withError('Error: Meeting 402, Contact NIC.');
            }
        }
        return redirect()->back();
    }

    public function communication() {
        $dept_id = Auth::user()->dept_id;
        $get_study_ids = Eval_activity_status::distinct('study_id')->pluck('study_id');
        $studies = Proposal::select('draft_id','scheme_name')->whereIn('draft_id',$get_study_ids)->where('proposals.dept_id',$dept_id)->orderBy('scheme_name')->get();
        $topics = CommunicationTopics::orderBy('id','desc')->get();
        $communication = Communication::select('communication.*','proposals.scheme_name','communication_topics.topic as topic_name', 'departments.dept_name')->leftjoin('itransaction.proposals','proposals.draft_id','=','communication.study_id')->leftjoin('imaster.communication_topics','communication.topic_id','=','communication_topics.id')->orderBy('communication.id','desc')->leftjoin('imaster.departments','communication.dept_id','=','departments.dept_id')->where('communication.dept_id',$dept_id)->get();
        $communication_arr = array();
        foreach($communication as $key => $val) {
            $study_id = $val->study_id;
            $topic_id = '';
            if($val->topic_id != 0) {
                $topic_id = $val->topic_id;
            }
            $topic_text = $val->topic_text;
            $remarks = $val->remarks;
            $document = $val->document;
            $document_count = $this->getthefilecount($val->scheme_id,$document);
            $file_type = '';
            if($document_count > 0) {
                $file_type = pathinfo($document, PATHINFO_EXTENSION);
            }
            $dept_id = $val->dept_id;
            $scheme_id = $val->scheme_id;
            $scheme_name = $val->scheme_name;
            $topic_name = $val->topic_name;
            $dept_name = $val->dept_name;
            $arr = array('study_id'=>$study_id,'topic_id'=>$topic_id,'topic_text'=>$topic_text,'remarks'=>$remarks,'document_count'=>$document_count,'document'=>$document,'dept_id'=>$dept_id,'scheme_id'=>$scheme_id,'scheme_name'=>$scheme_name,'topic_name'=>$topic_name,'dept_name'=>$dept_name,'file_type'=>$file_type);
            $communication_arr[] = $arr;
        }
        return view('schemes.communication',compact('topics','studies','communication_arr'));
    }

    public function addcommunication(Request $request) {
        $validate = Validator::make($request->all(),[
            'study_id' => 'required|numeric',
            'topic_id' => 'nullable|numeric',
            'topic_text' => 'nullable|string',
            'remarks' => 'required|string|max:500',
            'document' => 'nullable|mimes:docx,xlsx,pdf'
        ]);
        if($validate->fails()) {
            return response()->json('validation_error');
        } else {
            $study_id = $request->input('study_id');
            $scheme_id = Proposal::where('draft_id',$study_id)->value('scheme_id');
            $dept_id = Proposal::where('draft_id',$study_id)->value('dept_id');
            if($request->input('topic_id') != 0) {
                $topic_id = $request->input('topic_id');
            } else {
                $topic_id = 0;
            }
            $user_id = Auth::user()->id;
            $user_role = Auth::user()->role;
            $commu = new Communication;
            $commu->study_id = $study_id;
            $commu->topic_id = $topic_id;
            $commu->topic_text = $request->input('topic_text');
            $commu->remarks = $request->input('remarks');
            $commu->dept_id = $dept_id;
            $commu->scheme_id = $scheme_id;
            $commu->user_id = $user_id;
            $commu->user_role = $user_role;
            $commu->save();
            $last_id = $commu->id;
            if($request->hasFile('document')) {
                $document = $request->file('document');
                $rev = Attachment::where('scheme_id',$scheme_id)->value('couch_rev_id');
                $extended = new Couchdb();
                $extended->InitConnection();
                $status = $extended->isRunning();
                $doc_id = "scheme_".$scheme_id;
                $docid = 'communication_userid_'.Auth::user()->id.'_role_'.Auth::user()->role.'_id_'.$last_id.'_file';
                $path['id'] = $docid;
                $path['tmp_name'] = $document->getRealPath();
                $path['extension']  = $document->getClientOriginalExtension();
                $path['name'] = $doc_id.'_'.$path['id'].'.'.$path['extension'];
                $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                $array = json_decode($out, true);
                $rev = $array['rev'];
                if(isset($rev)) {
                    $result = Attachment::where('scheme_id',$scheme_id)->update(['couch_rev_id'=>$rev]);
                }                    
                $filename_exp = explode('scheme_'.$scheme_id.'_',$path['name']);
                $filename = $filename_exp[1];
                Communication::where('id',$last_id)->update(['document'=>$filename]);
            }
            $act['userid'] = Auth::user()->id;
            $act['ip'] = $request->ip();
            $act['activity'] = 'Communication Added by Eval Director';
            $act['officecode'] = Auth::user()->dept_id;
            $act['pagereferred'] = $request->url();
            Activitylog::insert($act);

            return response()->json('successfully_saved');
        }
    }


    public function getthefile($id,$scheme) {
        $id = 'scheme_'.Crypt::decrypt($id);
        $extended = new Couchdb();
        $extended->InitConnection();
        $status = $extended->isRunning();
        $out = $extended->getDocument($this->envirment['database'],$id);
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
                    $cont = file_get_contents($this->envirment['url'].$id."/".$atvalue);
                    if($cont) {
                        return response($cont)->withHeaders(['Content-type'=>'application/pdf']);
                    } else {
                        return "Error fetching the document. Contact NIC";
                    }
                }
            }
        } else {
            return 'Document not found !';
        }
    }

    public function getmeetingfile($id,$scheme) {
        $id = 'scheme_'.$id;
        $extended = new Couchdb();
        $extended->InitConnection();
        $status = $extended->isRunning();
        $out = $extended->getDocument($this->envirment['database'],$id);
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
            $filename = Meetinglog::where('mid',$id)->value('filename');
            if($filename == '') {
                $filename = Aftermeeting::where('amid',$id)->value('filename');
            }
            foreach($at_name as $atkey=>$atvalue) {
                if(strpos($atvalue,$filename) !== false) {
                    $cont = file_get_contents($this->envirment['url'].$id."/".$atvalue);
                    if($cont) {
                        return response($cont)->withHeaders(['Content-type'=>'application/pdf']);
                    } else {
                        return "Error fetching the document. Contact NIC";
                    }
                }
            }
        } else {
            return 'Document not found !';
        }
    }

    public function getthefilecount($id,$scheme) {
        $id = 'scheme_'.$id;
        $extended = new Couchdb();
        $extended->InitConnection();
        $status = $extended->isRunning();
        $out = $extended->getDocument($this->envirment['database'],$id);
        $arrays = json_decode($out, true);

        if(array_key_exists('error', $arrays)) {
            return 'no data';
        }

        $at_name = array();
        $countfiles = array();
        $attachments = array();

        if(isset($arrays) and array_key_exists('_attachments',$arrays)) {
            $attachments = $arrays['_attachments'];
        } else {
            return "no data";
        }
        foreach($attachments as $attachment_name => $attachment) {
            $at_name[] = $attachment_name;
        }

        if(count($at_name) > 0) {
            $k = 1;
            foreach($at_name as $atkey=>$atvalue) {
                if(strpos($atvalue,$scheme) !== false) {
                    $countfiles[] = $k;
                    $k++;
                }
            }
        }
        if(count($countfiles) > 0) {
            return $countfiles;
        } else {
            return 'no data';
        }
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
                if(!empty($item->document)){
                    $action .= '<button class="btn btn-xs btn-info report_data"  data-url-excel="'.route('stages.download_excel', $item->id).'" data-url-pdf= "'.route('stages.downalod',$item->id).'" style="display: inline-block">Stage Report Download</button>
                              <a class="btn btn-xs btn-info" href="'.route('stages.get_the_file',[Crypt::encrypt($item->scheme_id),$item->document]).'" target="_blank" title="'.$item->document.'"> View Document</a>';
                }else{
                    $action .= '<button class="btn btn-xs btn-info report_data"  data-url-excel="'.route('stages.download_excel', $item->id).'" data-url-pdf= "'.route('stages.downalod',$item->id).'" style="display: inline-block">Stage Report Download</button>';
                }
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
    public function destory($draft_id)
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
    


}
