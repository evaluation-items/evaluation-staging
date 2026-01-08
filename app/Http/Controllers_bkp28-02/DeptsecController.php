<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Proposal;
use App\Models\SchemeSend;
use App\Models\Status;
use App\Models\Scheme;
use App\Models\Convener;
use App\Models\Nodal;
use App\Models\Districts;
use App\Models\Implementation;
use App\Models\Adviser;
use App\Models\FinancialProgress;
use DB;
use URL;
use Hash;
use App\Couchdb\Couchdb;
use Validator;
use App\Models\User_user_role_deptid;
use Session;
use App\Models\Activitylog;
use Illuminate\Support\Arr;
use App\Models\Dept_meetings;
use Carbon\Carbon;
use App\Models\Attachment;
use Mail;
use App\Mail\UserMail;
use App\Jobs\UserMailJob;
use App\Models\Sdggoals;
use App\Models\Taluka;
use App\Models\GrFilesList;
use App\Models\NotificationFileList;
use App\Models\BrochureFileList;
use App\Models\PamphletFileList;
use App\Models\CenterStateFiles;
use App\Models\CommunicationTopics;
use App\Models\Communication;
use App\Models\Eval_activity_status;
use App\Models\Eval_activity_log;
use App\Models\Meetinglog;
use App\Models\Aftermeeting;


class DeptsecController extends Controller {

    public $envirment;
    public function __construct(){
        $this->middleware('auth');
        $this->requesturi = \Request::getRequestUri();
        $this->currenturl = \URL::current();
        $this->replace_url = str_replace($this->requesturi, '', $this->currenturl);
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

    public function index() {
        $this->get_count('proposals');
        $proposals = array();
        if(Auth::user()->role == 8) {
            $dept_id = Auth::user()->dept_id;
            $dept_name = Department::where('dept_id',$dept_id)->value('dept_name');
            $the_status_id = '21';
            $proposal_list = SchemeSend::leftjoin('itransaction.proposals','itransaction.scheme_send.draft_id','=','itransaction.proposals.draft_id')
                    ->select('scheme_send.id','proposals.draft_id','proposals.dept_id','proposals.scheme_name','proposals.nodal_officer_name','proposals.convener_name')
                    ->where('scheme_send.status_id',$the_status_id)
                    ->where('scheme_send.dept_id',$dept_id)
                    ->orWhere('scheme_send.status_id','26')
                    ->orWhere('scheme_send.status_id','24')
                    ->orderBy('scheme_send.id','desc')
                    ->get();
            if($proposal_list->isNotEmpty()) {
                foreach($proposal_list as $key=>$val) {
                    $proposals[] = array('send_id'=>$val->id,'draft_id'=>$val->draft_id,'dept_id'=>$val->dept_id,'scheme_name'=>$val->scheme_name,'convener_name'=>$val->convener_name,'nodal_name'=>$val->nodal_officer_name);
                }
            }
        }
        $replace_url = URL::to('/');
        return view('dashboards.dept-sec.index',compact('proposals','replace_url','dept_name'));
    }

    public function frwdgad(Request $request) {
        $this->get_count('proposals');
        $data = $request->all();

        $data['created_at']  = date('Y-m-d h:i:s');
        $d_id = $data['draft_id'];
        $s_id = $data['status_id'];
        $proposal = DB::table('itransaction.proposals')->where('draft_id','=',$d_id)
                    ->update(['status_id'=> $s_id]);

        $ins = SchemeSend::create($data);

        if($ins){
            $res = json_encode(['status=>1']);
        } else {
            $res = json_encode(['status=>0']);
        }
        return $res;
    }
    public function proposallist() {
        $this->get_count('proposals');
        $the_status_id = '23';
        $dept_id = Auth::user()->dept_id;
        $theforwarded = SchemeSend::select('id','draft_id','status_id')->where('status_id',$the_status_id)->where('dept_id',$dept_id)->orderBy('id','desc')->get();
        $forwarded_draft_ids = array();
        $statusid = 0;
        foreach($theforwarded as $kfor => $vfor) {
            if($vfor->status_id !== $statusid) {
                $statusid = $vfor->status_id;
                $forwarded_draft_ids[] = SchemeSend::where('id',$vfor->id)->value('id');
            }
        }
        $proposals_forwarded = SchemeSend::leftjoin('itransaction.proposals','itransaction.scheme_send.draft_id','=','itransaction.proposals.draft_id')
                ->select('scheme_send.*','proposals.draft_id as prop_draft_id','proposals.dept_id as prop_dept_id','proposals.scheme_name','proposals.scheme_overview','proposals.scheme_objective','proposals.sub_scheme','proposals.status_id as prop_status_id')
                ->whereIn('scheme_send.id',$forwarded_draft_ids)
                ->where('scheme_send.dept_id',$dept_id)
                ->where('scheme_send.status_id',$the_status_id)
                ->orderBy('scheme_send.status_id','asc')
                ->orderBy('scheme_send.id','desc')
                ->get();

        $sentprops = SchemeSend::pluck('draft_id');
        $proposals_new = Proposal::whereNotIn('draft_id',$sentprops)->where('dept_id',$dept_id)->orderBy('draft_id','desc')->get();

        $status_id_return = '24';
        $thereturned = SchemeSend::select('id','draft_id','status_id')->where('status_id',$status_id_return)->orWhere('status_id','26')->where('dept_id',$dept_id)->where('forward_btn_show','1')->orderBy('id','desc')->get();
        $returned_draft_ids = array();
        $statusid = 0;
        foreach($thereturned as $kfor => $vfor) {
            if($vfor->status_id !== $statusid) {
                $statusid = $vfor->status_id;
                $returned_draft_ids[] = SchemeSend::where('id',$vfor->id)->value('id');
            }
        }
        $proposals_returned = SchemeSend::leftjoin('itransaction.proposals','itransaction.scheme_send.draft_id','=','itransaction.proposals.draft_id')
                    ->select('scheme_send.*','proposals.draft_id as prop_draft_id','proposals.dept_id as prop_dept_id','proposals.scheme_name','proposals.scheme_overview','proposals.scheme_objective','proposals.sub_scheme', 'proposals.status_id as prop_status_id')
                    ->whereIn('scheme_send.id',$returned_draft_ids)
                    ->orderBy('scheme_send.id','desc')
                    ->get();
        return view('dashboards.dept-sec.proposals',compact('proposals_new','proposals_forwarded','proposals_returned','the_status_id','status_id_return'));
    }

    public function proposaldetail($draft_id,$send_id) {
        $goals = Sdggoals::where('status','1')->orderBy('goal_id','desc')->get();
        $proposal_list = Proposal::where('draft_id',$draft_id)->get();
        $replace_url = URL::to('/');
        $dept_name = '';
        $convener = array();
        $major_objectives = array();
        $major_indicators = array();
        $imdept = array();
        $adviseris = array();
        $major_indicator_hod = array();
        $financial_progress = array();
        $district_ids = array();
        $taluka_ids = array();
        $dept_name = array();
        $conv_dept_ids = array();
        $conv_dept_remarks = array();
        $entered_goals = array();
        $beneficiariesGeoLocal = beneficiariesGeoLocal();
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
            $financial_progress = FinancialProgress::where('scheme_id',$scheme_id)->get();
            $bencovfile = $this->getthefilecount($scheme_id,'_beneficiaries_coverage');
            $trainingfile = $this->getthefilecount($scheme_id,'_training');
            $iecfile = $this->getthefilecount($scheme_id,'_iec_');
            $eval_report = $this->getthefilecount($scheme_id,'_eval_report');
            $gr_files = $this->getthefilecount($scheme_id,'_gr');
            $notification_files = $this->getthefilecount($scheme_id,'_notification');
            $brochure_files = $this->getthefilecount($scheme_id,'_brochure');
            $pamphlets_files = $this->getthefilecount($scheme_id,'_pamphlets');
            $otherdetailscenterstate_files = $this->getthefilecount($scheme_id,'_otherdetailscenterstate');

            if($value->districts != 'null' or $value->districts != '') {
                // $district_ids = explode(',',$value->districts);
                $district_ids = json_decode($value->districts);
            }
            if($value->talukas != 'null' or $value->talukas != '') {
                $taluka_ids = json_decode($value->talukas);
            }
            if($value->all_convergence) {
                $all_convergence = json_decode($value->all_convergence);
            }
            if($value->is_sdg) {
                $entered_goals = json_decode($value->is_sdg);
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
        SchemeSend::where('id',$send_id)->update(['viewed'=>'1']);
        $this->get_count('proposals');
        return view('dashboards.dept-sec.proposaldetail',compact('proposal_list','dept_name','major_objectives','major_indicators','imdept','major_indicator_hod','financial_progress','replace_url','scheme_id','beneficiariesGeoLocal','bencovfile','trainingfile','iecfile','district_names','dept_names','taluka_names','conv_dept_remarks','goals','entered_goals','eval_report','gr_files','notification_files','brochure_files','pamphlets_files','otherdetailscenterstate_files','the_convergence'));
    }

    public function newproposaldetail($draft_id) {
        $goals = Sdggoals::where('status','1')->orderBy('goal_id','desc')->get();
        $proposal_list = Proposal::where('draft_id',$draft_id)->get();
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
                // $district_ids = explode(',',$value->districts);
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
            $iecfile = $this->getthefilecount($scheme_id,'_iec_');
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
        return view('dashboards.dept-sec.newproposaldetail',compact('proposal_list','dept_name','major_objectives','major_indicators','imdept','major_indicator_hod','financial_progress','replace_url','scheme_id','beneficiariesGeoLocal','bencovfile','trainingfile','iecfile','district_names','dept_names','taluka_names','conv_dept_remarks','goals','entered_goals','eval_report','gr_files','notification_files','brochure_files','pamphlets_files','otherdetailscenterstate_files','the_convergence'));
    }
    public function frwdtogad(Request $request) {
        $this->get_count('proposals');
        $validate = Validator::make($request->all(),[
            'remarks' => 'string|nullable',
            'draft_id' => 'numeric|required',
            'send_id' => 'numeric|nullable'
        ]);
        if($validate->fails()) {
            return redirect()->back()->withError($validate->errors());
        } else {
            $data = $request->all();
            $user_id = Auth()->user()->id;
            $dept_id = Auth::user()->dept_id;
            $send_id = $data['send_id'];
            $data['created_at'] = date('Y-m-d h:i:s');
            $d_id = $data['draft_id'];
            $data['status_id'] = '23';
            $data['user_id'] = Auth::user()->id;
            $data['created_by'] = Auth::user()->name;
            $data['dept_id'] = $dept_id;
            $s_id = '23';
            unset($data['_token']);
            unset($data['send_id']);
            $proposal = DB::table('itransaction.proposals')->where('draft_id','=',$d_id)->update(['status_id'=> $s_id]);
            if($send_id != '') {
                SchemeSend::where('draft_id',$d_id)->update(['forward_btn_show'=>0]);
            }
            SchemeSend::insertGetId($data);
            $act['userid'] = Auth::user()->id;
            $act['ip'] = $request->ip();
            $act['activity'] = 'Dept Sec. forwarded to GAD.';
            $act['officecode'] = $dept_id;
            $act['pagereferred'] = $request->url();
            $act = Activitylog::insert($act);
            return redirect()->back()->with('forward_to_gad_success','Proposal sent successfully to GAD');
        }
    }

    public function returntoia(Request $request) {
        $this->get_count('proposals');
        $validate = Validator::make($request->all(),[
            'remarks' => 'string|nullable',
            'draft_id' => 'numeric|required'
            // 'send_id' => 'numeric|required'
        ]);
        if($validate->fails()) {
            return redirect()->back()->withError($validate->errors());
        } else {
            $data = $request->all();
            // dd($data);
            $user_id = Auth()->user()->id;
            // $dept_id = User_user_role_deptid::where('id',$user_id)->value('dept_id');
            $dept_id = Auth::user()->id;
            // $send_id = $data['send_id'];
            $data['created_at'] = date('Y-m-d h:i:s');
            $d_id = $data['draft_id'];
            $data['status_id'] = '26';
            $data['user_id'] = $dept_id;
            $data['created_by'] = Auth::user()->name;
            $data['dept_id'] = $dept_id;
            $s_id = '26';
            unset($data['_token']);
            // unset($data['send_id']);
            $proposal = DB::table('itransaction.proposals')->where('draft_id','=',$d_id)->update(['status_id'=> $s_id]);
            // $ins = SchemeSend::where('id',$send_id)->update($data);
            SchemeSend::where('draft_id',$d_id)->update(['forward_btn_show'=>0]);
            SchemeSend::insert($data);
            $act['userid'] = \Auth::user()->id;
            $act['ip'] = $request->ip();
            $act['activity'] = 'Dept Sec. return to IA Officer.';
            $act['officecode'] = $dept_id;
            $act['pagereferred'] = $request->url();
            $act = Activitylog::insert($act);
            return redirect()->back()->with('forward_to_gad_success','Proposal sent successfully to GAD');
        }
    }
	
    public function proposaledit($id) {
        $proposal = Proposal::where('draft_id',$id)->get();
        $scheme_id = Proposal::where('draft_id',$id)->value('scheme_id');
        Session::put('draft_id',$id);
        Session::put('scheme_id',$scheme_id);
        Session::get('scheme_id');
        Session::get('draft_id');
        $financial_years = financialyears();
        $goals = Sdggoals::where('status','1')->orderBy('goal_id','desc')->get();
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
        return view('dashboards.dept-sec.proposal_edit',compact('proposal','departments','implementations','beneficiariesGeoLocal','dept','financial_years','goals','financial_progress','scheme_id','replace_url','gr_files','notifications','brochures','pamphlets','center_state'));
    }
	
    public function proposalupdate(Request $request) {
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
            $arr = array('dept_id'=>$dept_id,'convener_name'=>$convener_name,'scheme_name'=>$scheme_name,'reference_year'=>$reference_year,'reference_year2'=>$reference_year2);
            Scheme::where('scheme_id',$scheme_id)->update($arr);
            Proposal::where('draft_id',$draft_id)->update($arr);
            return response()->json('updated successfully');
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
            unset($data['_token']);
            unset($data['slide']);
            $arr = $data;
            $scheme_id = Session::get('scheme_id');
            Scheme::where('scheme_id',$scheme_id)->update($arr);
            $draft_id = Session::get('draft_id');
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
            $arr = $data;
           
            $scheme_id = Session::get('scheme_id');
            Scheme::where('scheme_id',$scheme_id)->update($arr);
            $draft_id = Session::get('draft_id');
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
                $docid = 'major_benefits'; //.time().'_'.mt_rand('000000000','999999999');
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

                $arr = array('major_benefits_text'=>$major_benefits_text,'benefit_to_file'=>$filename);
                Scheme::where('scheme_id',$scheme_id)->update($arr);
                $draft_id = Session::get('draft_id');
                Proposal::where('draft_id',$draft_id)->update($arr);
            } else {
                $arr = array('major_benefits_text'=>$major_benefits_text);
                Scheme::where('scheme_id',$scheme_id)->update($arr);
                $draft_id = Session::get('draft_id');
                Proposal::where('draft_id',$draft_id)->update($arr);
            }
            return response()->json('added successfully');
        } else if($slide == 'eighth') {
            $scheme_id = Session::get('scheme_id');
            $scheme_implementing_procedure = $request->input('scheme_implementing_procedure');
            $beneficiariesGeoLocal = $request->input('beneficiariesGeoLocal');
            $districts = json_encode($request->input('district_name'));
            $talukas = json_encode($request->input('taluka_name'));
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
                $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                $array = json_decode($out, true);
                $rev = $array['rev'];
                if(isset($rev)) {
                    $result = Attachment::where('scheme_id',$scheme_id)->update(['couch_rev_id'=>$rev]);
                }                    
                $filename = $path['name'];

                $arr = array('scheme_implementing_procedure'=>$scheme_implementing_procedure, 'beneficiariesGeoLocal'=>$beneficiariesGeoLocal, 'districts'=>$districts, 'talukas'=>$talukas, 'otherbeneficiariesGeoLocal'=>$otherbeneficiariesGeoLocal,'geographical_coverage'=>$filename);
                Scheme::where('scheme_id',$scheme_id)->update($arr);
                $draft_id = Session::get('draft_id');
                Proposal::where('draft_id',$draft_id)->update($arr);
            } else {
                $arr = array('scheme_implementing_procedure'=>$scheme_implementing_procedure, 'beneficiariesGeoLocal'=>$beneficiariesGeoLocal, 'districts'=>$districts, 'talukas'=>$talukas, 'otherbeneficiariesGeoLocal'=>$otherbeneficiariesGeoLocal);
                Scheme::where('scheme_id',$scheme_id)->update($arr);
                $draft_id = Session::get('draft_id');
                Proposal::where('draft_id',$draft_id)->update($arr);
            }
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

                $arr = array('iec'=>$beneficiaries_coverage_file_name);
                Scheme::where('scheme_id',$scheme_id)->update($arr);
                $draft_id = Session::get('draft_id');
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
                $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                $array = json_decode($out, true);
                $rev = $array['rev'];
                if(isset($rev)) {
                    $result = Attachment::where('scheme_id',$scheme_id)->update(['couch_rev_id'=>$rev]);
                }                    
                $training_file_name = $path['name'];

                $arr = array('iec'=>$training_file_name);
                Scheme::where('scheme_id',$scheme_id)->update($arr);
                $draft_id = Session::get('draft_id');
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
                $docid = 'iec';//.time().'_'.mt_rand('0000000000','9999999999');
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

                $arr = array('iec'=>$iec_file_name);
                Scheme::where('scheme_id',$scheme_id)->update($arr);
                $draft_id = Session::get('draft_id');
                Proposal::where('draft_id',$draft_id)->update($arr);
            }
            $arr = array('coverage_beneficiaries_remarks'=>$coverage_beneficiaries_remarks, 'training_capacity_remarks'=>$training_capacity_remarks, 'iec_activities_remarks'=>$iec_activities_remarks);
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
                    $arr = array('file_name'=>$file_name,'scheme_id'=>$scheme_id);
                    GrFilesList::insert($arr);
                }
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
            
            return response()->json('added successfully');
        } else if($slide == 'twelth') {
            $data = $request->all();
            unset($data['_token']);
            unset($data['slide']);
            $arr = $data;
            $scheme_id = Session::get('scheme_id');
            $draft_id = Session::get('draft_id');
            if($scheme_id != '') {
                Scheme::where('scheme_id',$scheme_id)->update($arr);
                Proposal::where('draft_id',$draft_id)->update($arr);
            }
            return response()->json('added successfully');
        } else if($slide == 'thirteenth') {
            $tr_array = $request->input('tr_array');
            $fin_progress_remarks = $request->input('financial_progress_remarks');
            $arr = array();
            $scheme_id = Session::get('scheme_id');
            $draft_id = Session::get('draft_id');
            $fn_year = '';
            foreach($tr_array as $tr) {
                $fn_year = $tr['financial_year'];
                $arr[] = array('scheme_id'=>$scheme_id,'financial_year'=>$tr['financial_year'], 'target'=>$tr['target'],'achievement'=>$tr['achievement'], 'allocation'=>$tr['allocation'], 'expenditure'=>$tr['expenditure'], 'units'=>$tr['units']);
            }

            if($fn_year == '' or $scheme_id == '' or $draft_id == '') {

            } else if($scheme_id != '' and $fn_year != '' and $draft_id != '') {
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
            $draft_id = Session::get('draft_id');
            $eval_report_file_name = '';

            if($request->hasFile('eval_upload_report')) {
                $eval_upload_report = $request->file('eval_upload_report');
                $rev = Attachment::where('scheme_id',$scheme_id)->value('couch_rev_id');
                $extended = new Couchdb();
                $extended->InitConnection();
                $status = $extended->isRunning();
                $doc_id = "scheme_".$scheme_id;
                $docid = 'eval_report'; //.mt_rand('0000000000','9999999999');
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

            $arr = array('is_evaluation'=>$is_evaluation, 'eval_scheme_bywhom'=>$eval_by_whom, 'eval_scheme_when'=>$eval_when, 'eval_scheme_geo_cov_bene'=>$eval_geographical_coverage_beneficiaries, 'eval_scheme_no_of_bene'=>$eval_number_of_beneficiaries, 'eval_scheme_major_recommendation'=>$eval_major_recommendation);
            
            if($scheme_id != '') {
                Scheme::where('scheme_id',$scheme_id)->update($arr);
                Proposal::where('draft_id',$draft_id)->update($arr);
            }
            return response()->json('added successfully');
        }
    }

    public function schemes() {
        $this->get_count('proposals');
        $schemes = Scheme::orderBy('scheme_id','desc')->get();
        return view('dashboards.dept-sec.schemes',compact('schemes'));
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
        $iecfile = $this->getthefilecount($scheme_id,'_iec_');
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
                // $district_ids = explode(',',$value->districts);
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
        return view('dashboards.dept-sec.schemedetail',compact('schemes','dept_name','major_objectives','major_indicators','imdept','major_indicator_hod','financial_progress','replace_url','scheme_id','beneficiariesGeoLocal','bencovfile','trainingfile','iecfile','district_names','dept_names','taluka_names','conv_dept_remarks','goals','entered_goals','eval_report','gr_files','notification_files','brochure_files','pamphlets_files','otherdetailscenterstate_files','the_convergence'));
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
        return view('dashboards.dept-sec.meetings',compact('meetings','attendees','schemelist','departments','implementations','aftermeeting','replace_url'));
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

    public function getthefile($id,$scheme) {
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

    public function getthefilecount($id,$scheme) {
        $id = 'scheme_'.$id;
        $extended = new Couchdb();
        $extended->InitConnection();
        $status = $extended->isRunning();
        $out = $extended->getDocument($this->envirment['database'],$id);
        $arrays = json_decode($out, True);

        if(array_key_exists('error', $arrays)) {
            return 'no data';
        }

        $at_name = array();
        $countfiles = array();
        $attachments = array();

        if(isset($arrays)) {
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
        return $countfiles;
    }


    public function listconveners(){
        $user_deptid = auth()->user()->dept_id;
        $conveners_list = Convener::where('dept_id',$user_deptid)->get();
        return view('dashboards.dept-sec.conveners',compact('conveners_list'));
    }

    public function nodallist(){
        $user_deptid = auth()->user()->dept_id;
        $nodals_list = Nodal::where('deptid',$user_deptid)->orderBy('nodalid','desc')->get();
        return view('dashboards.dept-sec.nodal',compact('nodals_list'));
    }

    public function addNodal() {
        $user_dept = Auth()->user()->dept_id;
        $dept_name = Department::where('imaster.departments.dept_id','=',$user_dept)
                     ->select('imaster.departments.dept_name','imaster.departments.dept_id')
                     ->get();
        return view('dashboards.dept-sec.addnodal',compact('dept_name'));
    }

    public function saveNodal(Request $request){
        
        $validate = Validator::make($request->all(),[
            'deptid'=>'required|numeric',
            'hod_name'=>'required|string',
            'implementation'=>'required|numeric',
            'nodal_name'=>'required|string',
            'designation'=>'required|in:JS,DS,AS',
            'phone_no'=>'nullable|numeric|digits_between:5,20',
            'mobile'=>'required|numeric|digits:10',
            'email'=>'required|email',
            'address'=>'nullable|max:200',
        ]);
        if($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate->errors());
        } else {
            $deptid = $request->input('deptid');
            $hod_name = $request->input('hod_name');
            $imid = $request->input('implementation');
            $nodal_name = $request->input('nodal_name');
            $designation = $request->input('designation');
            $phone_no = $request->input('phone_no');
            $mobile = $request->input('mobile');
            $email = $request->input('email');
            $address = $request->input('address');
            $entered_by = Auth::user()->name;

            $act['userid'] = \Auth::user()->id;
            $act['ip'] = $request->ip();
            $act['activity'] = 'Added new Nodal Officer.';
            $act['officecode'] = $deptid;
            $act['pagereferred'] = $request->url();
            $act = Activitylog::insert($act);

            $arr = array(
                    'deptid'=>$deptid,
                    'hod_name'=>$hod_name,
                    'imid'=>$imid,
                    'nodal_name'=>$nodal_name,
                    'designation'=>$designation,
                    'phone_no'=>$phone_no,
                    'mobile'=>$mobile,
                    'email'=>$email,
                    'address'=>$address,
                    'entered_by'=>$entered_by
            );
            Nodal::create($arr);

            $name = $request->input('nodal_name');
            $email = $request->input('email');
            $department = $request->input('deptid');
            $role = 11;
            $pass = '123456789';
            $password = Hash::make($pass);
            $created_at = Carbon::now();
            $arr_user = array('name'=>$name,'email'=>$email,'dept_id'=>$department,'role'=>$role,'password'=>$password,'created_at'=>$created_at);
            $ifemail = User::select('email')->where('email',$email)->get();
            if($ifemail->isEmpty()) {
                $user_id = User::insertGetId($arr_user);
                User_user_role_deptid::insert(['user_id'=>$user_id, 'user_role_id'=>$role,'dept_id'=>$department]);
                $details=array('subject'=>'Scheme Evaluation','email'=>$email,'password'=>$pass);
            }

            return redirect()->route('deptsec.nodallist')->withSuccess('Nodal Officer added successfully');
        }
    }

    public function conveneradd(){
        $user_dept = Auth()->user()->dept_id;
        $dept_name = Department::where('imaster.departments.dept_id','=',$user_dept)
                     ->select('imaster.departments.dept_name','imaster.departments.dept_id')
                     ->get();
        return view('dashboards.dept-sec.addconvener',compact('dept_name'));
    }

    
    public function saveConvener(Request $request){
        $validate = Validator::make($request->all(),[
            'dept_id'=>'required|numeric',
            'convener_name'=>'required|max:100',
            'convener_designation'=>'required|max:100',
            'convener_phone_no'=>'nullable|numeric|digits_between:5,20',
            'convener_mobile_no'=>'required|numeric|digits:10',
            'convener_email'=>'required|email',
            'convener_address'=>'nullable|max:300'
        ]);
        if($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate->errors());
        } else {
            $dept_id = $request->input('dept_id');
            $convener_name = ucfirst($request->input('convener_name'));
            $convener_designation = ucfirst($request->input('convener_designation'));
            $convener_phone_no = $request->input('convener_phone_no');
            $convener_mobile_no = $request->input('convener_mobile_no');
            $convener_email = strtolower($request->input('convener_email'));
            $convener_address = $request->input('convener_address');
            $created_by = Auth::user()->name;

            $act['userid'] = \Auth::user()->id;
            $act['ip'] = $request->ip();
            $act['activity'] = 'Added new Convener Officer.';
            $act['officecode'] = $dept_id;
            $act['pagereferred'] = $request->url();
            $act = Activitylog::insert($act);
            $arr = array(
                    'dept_id'=>$dept_id,
                    'convener_name'=>$convener_name,
                    'convener_designation'=>$convener_designation,
                    'convener_phone_no'=>$convener_phone_no,
                    'convener_mobile_no'=>$convener_mobile_no,
                    'convener_email'=>$convener_email,
                    'convener_address'=>$convener_address,
                    'created_by'=> $created_by
            );
            Convener::create($arr);
            return redirect()->route('deptsec.listconveners')->withSuccess('Convener added successfully');
        }
    }

    // Adviser
    public function listadvisers(){
        $user_deptid = auth()->user()->dept_id;
        $advisers_list = Adviser::where('deptid',$user_deptid)->get();
        return view('dashboards.dept-sec.adviser',compact('advisers_list'));
    }

    public function adviseradd(){
        $user_dept = Auth()->user()->dept_id;
        $dept_name = Department::where('imaster.departments.dept_id','=',$user_dept)
                     ->select('imaster.departments.dept_name','imaster.departments.dept_id')
                     ->get();
        return view('dashboards.dept-sec.addadviser',compact('dept_name'));
    }

    public function saveAdviser(Request $request){
        $validate = Validator::make($request->all(),[
            'deptid'=>'required|numeric',
            'adviser_name'=>'required|max:50',
            'adviser_designation'=>'required|max:100',
            'adviser_phone_no'=>'nullable|numeric|digits_between:6,20',
            'adviser_mobile'=>'required|numeric|digits:10',
            'adviser_email'=>'required|email|max:100',
            'imid'=>'required|numeric'
        ]);
        if($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate->errors());
        } else {
            $deptid = $request->input('deptid');
            $adviser_name = $request->input('adviser_name');
            $adviser_designation = $request->input('adviser_designation');
            $adviser_phone_no = $request->input('adviser_phone_no');
            $adviser_mobile = $request->input('adviser_mobile');
            $adviser_email = $request->input('adviser_email');
            $created_by = \Auth::user()->name;
            $imid = $request->input('imid');

            $act['userid'] = \Auth::user()->id;
            $act['ip'] = $request->ip();
            $act['activity'] = 'Added new Adviser Officer.';
            $act['officecode'] = $deptid;
            $act['pagereferred'] = $request->url();
            $act = Activitylog::insert($act);
            $arr = array(
                    'deptid'=>$deptid,
                    'adviser_name'=>$adviser_name,
                    'adviser_designation'=>$adviser_designation,
                    'adviser_phone_no'=>$adviser_phone_no,
                    'adviser_mobile'=>$adviser_mobile,
                    'adviser_email'=>$adviser_email,
                    'imid'=>$imid,
                    'created_by'=> $created_by
            );

            $name = $request->input('adviser_name');
            $email = $request->input('adviser_email');
            $department = $request->input('deptid');
            $role = 13;
            $pass = '123456789';
            $password = Hash::make($pass);
            $created_at = Carbon::now();
            $arr_user = array('name'=>$name,'email'=>$email,'dept_id'=>$department,'role'=>$role,'password'=>$password,'created_at'=>$created_at);
            $ifemail = User::where('email',$email)->get();
            if($ifemail->isEmpty()) {
                $user_id = User::insertGetId($arr_user);
                User_user_role_deptid::insert(['user_id'=>$user_id, 'user_role_id'=>$role,'dept_id'=>$department]);
                $details=array('subject'=>'Scheme Evaluation','email'=>$email,'password'=>$pass);
            }
            Adviser::create($arr);
            return redirect()->route('deptsec.listadvisers')->withSuccess('Adviser added successfully');
        }
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
        return view('dashboards.dept-sec.communication',compact('topics','studies','communication_arr'));
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


}