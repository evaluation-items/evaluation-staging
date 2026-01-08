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
            $user_id = Auth()->user()->id;
            $dept_id = Auth::user()->id;
            $data['created_at'] = date('Y-m-d h:i:s');
            $d_id = $data['draft_id'];
            $data['status_id'] = '26';
            $data['user_id'] = $dept_id;
            $data['created_by'] = Auth::user()->name;
            $data['dept_id'] = $dept_id;
            $s_id = '26';
            unset($data['_token']);
            $proposal = DB::table('itransaction.proposals')->where('draft_id','=',$d_id)->update(['status_id'=> $s_id]);
            SchemeSend::where('draft_id',$d_id)->update(['forward_btn_show'=>0]);
            SchemeSend::insert($data);
           
            return redirect()->back()->with('forward_to_gad_success','Proposal sent successfully to GAD');
        }
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

    // public function schemes() {
    //     $this->get_count('proposals');
    //     $schemes = Scheme::orderBy('scheme_id','desc')->get();
    //     return view('dashboards.dept-sec.schemes',compact('schemes'));
    // }

    
    // public function getthefilecount($id,$scheme) {
    //     $id = 'scheme_'.$id;
    //     $extended = new Couchdb();
    //     $extended->InitConnection();
    //     $status = $extended->isRunning();
    //     $out = $extended->getDocument($this->envirment['database'],$id);
    //     $arrays = json_decode($out, True);

    //     if(array_key_exists('error', $arrays)) {
    //         return 'no data';
    //     }

    //     $at_name = array();
    //     $countfiles = array();
    //     $attachments = array();

    //     if(isset($arrays)) {
    //         $attachments = $arrays['_attachments'];
    //     } else {
    //         return "no data";
    //     }
    //     foreach($attachments as $attachment_name => $attachment) {
    //         $at_name[] = $attachment_name;
    //     }

    //     if(count($at_name) > 0) {
    //         $k = 1;
    //         foreach($at_name as $atkey=>$atvalue) {
    //             if(strpos($atvalue,$scheme) !== false) {
    //                 $countfiles[] = $k;
    //                 $k++;
    //             }
    //         }
    //     }
    //     return $countfiles;
    // }

}