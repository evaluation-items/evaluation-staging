<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\OdkUser;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Couchdb\Couchdb;
use App\Models\Districts;
use App\Models\Convener;
use App\Models\GrFilesList;
use App\Models\NotificationFileList;
use App\Models\BrochureFileList;
use App\Models\PamphletFileList;
use App\Models\CenterStateFiles;
use App\Models\FinancialProgress;
use App\Models\Implementation;
use App\Models\Adviser;
use App\Models\Scheme;
use App\Models\Nodal;
use App\Models\Proposal;
use App\Models\Meetinglog;
use App\Models\User;
use App\Models\ODK_Project;
use App\Models\Teams;
use App\Models\SchemeSend;
use App\Models\Aftermeeting;
use URL;
use Arr;
use Carbon\Carbon;
use App\Models\Eval_activity_status;
use App\Models\Eval_activity_log;
use App\Models\ActivityApproval;
use App\Models\ReportModule;
use App\Models\Eval_activity_status_dates;
use App\Models\Activitylog;
use App\Models\CommunicationTopics;
use App\Models\BranchRole;
use App\Models\Attachment;
use App\Models\Branch;
use App\Models\Stage;
use Illuminate\Support\Facades\Crypt;
use App\Http\ViewComposers\draftComposer;
use App\Models\Sdggoals;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;

class EvalddController extends Controller {

    public $envirment;
    public function __construct(){
        $this->middleware('auth');
        $this->envirment = environmentCheck();
    }
    

    public function count_all_proposals($param) {
        $dept_id = Auth::user()->dept_id;
        $user_id = auth()->user()->id;
        $user_login = DB::table('users')
                        ->leftjoin('public.user_user_role_deptid','user_id','=','users.id')
                        ->where('users.id','=',$user_id)
                        ->select('users.name','users.id','users.role')
                        ->get();

        if ($param == "new") {

            $count = [];

            if(Auth::check() && Auth::user()->role_manage == 4 && Auth::user()->role == 38 ){ //RO Login

                return SchemeSend::select('scheme_send.*', 'proposals.draft_id', 'proposals.scheme_name', 'proposals.scheme_objective')
                                        ->leftJoin('itransaction.proposals', 'scheme_send.draft_id', '=', 'proposals.draft_id')
                                        ->leftJoin('public.users', 'scheme_send.user_id', '=', 'users.id')
                                        ->WhereNull('scheme_send.team_member_dd')
                                        ->whereIn('scheme_send.status_id', [25])
                                        ->where('scheme_send.forward_btn_show', 1)
                                        ->where('scheme_send.forward_id', 1)
                                        ->orderByDesc('scheme_send.id')
                                        ->distinct()
                                        ->count();
               
                
              }else{
                    $status_id = '25';
                    $role_id = SchemeSend::WhereNotNull('team_member_dd')->pluck('draft_id');
             

                    $proposal = SchemeSend::select('scheme_send.*', 'proposals.draft_id', 'proposals.scheme_name', 'proposals.scheme_objective')
                                        ->leftJoin('itransaction.proposals', 'scheme_send.draft_id', '=', 'proposals.draft_id')
                                        ->leftJoin('public.users', 'scheme_send.user_id', '=', 'users.id')
                                        ->whereNotNull('team_member_dd')
                                        ->whereIn('proposals.draft_id', $role_id)
                                        ->orderBy('proposals.draft_id', 'desc')
                                        ->distinct()
                                        ->get();
               
                    foreach ($proposal as $testdata) {
                        $role = explode(',', $testdata->team_member_dd);
                        $user_ids = User::whereIn('role', $role)->pluck('id')->toArray();
                        if (!empty($user_ids)) {
                            if (in_array(Auth::user()->id, $user_ids)) {
                                $count[Auth::user()->id] = isset($count[Auth::user()->id]) ? $count[Auth::user()->id] + 1 : 1;
                                  //$count[$testdata->user_id] = isset($count[$testdata->user_id]) ? $count[$testdata->user_id] + 1 : 1;
                            }
                        }
                       
                    }
                    
                $userId = Auth::user()->id;
                $total_count = isset($count[$userId]) ? $count[$userId] : 0;

                return $total_count;
            }
           
        }  elseif ($param == "on_going") {
            //Ongoing 
            $on_count = [];
            $ongoing_proposal = Stage::with(['schemeSend' => function ($q) {
                $q->whereNotNull('team_member_dd');
            }])->whereNull('document')->get();   
            foreach ($ongoing_proposal as $prop) {
                $role = explode(',', ($prop->schemeSend->team_member_dd));
                $user_ids = User::whereIn('role', $role)->pluck('id')->toArray();
                if (!empty($user_ids) && in_array(Auth::user()->id, $user_ids)) {
                    $on_count[Auth::user()->id] = isset($on_count[Auth::user()->id]) ? $on_count[Auth::user()->id] + 1 : 1;
                }
            }

            $userId = Auth::user()->id;
            $total_count = isset($on_count[$userId]) ? $on_count[$userId] : 0;
         //  dd($total_count);
            return  $total_count;

        }elseif ($param == "completed") {
           //Completed 
            return Stage::WhereNotNull('document')->count();
        }
        // Default return 0 if $param doesn't match any condition
        return 0;
    }
    

    
    public function index() {
        $new_count = $this->count_all_proposals("new");
        $ongoing_count = $this->count_all_proposals("on_going");
       
        $completed_count = $this->count_all_proposals("completed");
        $scheme_list = barchartScheme();
        $draft_id = [];
        foreach ($scheme_list as $key => $value) {
            $draft_id[] = $key;
        }
     
       draftComposer::setDraftId($draft_id);
        return view('dashboards.eva-dd.index', compact('new_count', 'ongoing_count','completed_count','scheme_list','draft_id'));
    }
    
 

    public function proposallist($param) {
        $dept_id = Auth::user()->dept_id;
        $user_id = auth()->user()->role;
           $user_login = DB::table('users')
                           ->leftjoin('public.user_user_role_deptid','user_id','=','users.id')
                           ->where('users.id','=',$user_id)
                           ->select('users.name','users.id','users.role')
                           ->get();
           $roles = range(26, 38);

        $dd = User::select('id', 'name')->whereIn('role', $roles)->get();
        $branch_list = Branch::get();

           if($param == "new"){ 
              //New Proposal
              if(Auth::check() && Auth::user()->role_manage == 4 && Auth::user()->role == 38 ){ //RO Login
            
                $role_id = SchemeSend::WhereNotNull('team_member_dd')->pluck('draft_id');

                $proposalsQuery = SchemeSend::select('scheme_send.*', 'proposals.draft_id', 'proposals.scheme_name', 'proposals.scheme_objective')
                                            ->leftJoin('itransaction.proposals', 'scheme_send.draft_id', '=', 'proposals.draft_id')
                                            ->leftJoin('public.users', 'scheme_send.user_id', '=', 'users.id')
                                            ->whereNull('scheme_send.team_member_dd')
                                            ->whereIn('scheme_send.status_id', [25])
                                            ->where('scheme_send.forward_btn_show', 1)
                                            ->where('scheme_send.forward_id', 1);
        
                  
                    $proposalsQuery->orWhere(function($query) use ($role_id) {
                        $query->whereNotNull('team_member_dd')
                            ->whereIn('proposals.draft_id', $role_id);
                           // ->where('scheme_send.approved', 0);
                    });
        
                $proposals = $proposalsQuery->orderByDesc('scheme_send.id')->distinct()->get();
        
              }else{ //DD Login
               
                $role_id = SchemeSend::WhereNotNull('team_member_dd')->pluck('draft_id');
                
       
                $proposals = SchemeSend::select('scheme_send.*', 'proposals.draft_id', 'proposals.scheme_name', 'proposals.scheme_objective')
                                            ->leftJoin('itransaction.proposals', 'scheme_send.draft_id', '=', 'proposals.draft_id')
                                            ->leftJoin('public.users', 'scheme_send.user_id', '=', 'users.id')
                                            ->whereNotNull('team_member_dd')
                                            ->where('scheme_send.approved', 1)
                                            ->whereIn('proposals.draft_id', $role_id)
                                            ->orderBy('proposals.draft_id', 'desc')
                                            ->distinct()
                                            ->get();
                 
                }
               
               return view('dashboards.eva-dd.proposal.new_proposal',compact('proposals','branch_list'));
   
           }elseif ($param == "on_going") {
               //Ongoing 
               $ongoing_proposal = Stage::with(['schemeSend' => function ($q) {
                                        $q->whereNotNull('team_member_dd');
                                    }])->whereNull('document')->get();   
                        
   
               return view('dashboards.eva-dd.proposal.ongoing_proposal',compact('ongoing_proposal'));
   
   
           }elseif ($param == "completed") {
               //Completed 
               $complted_proposal = Stage::WhereNotNull('document')->get();
              return view('dashboards.eva-dd.proposal.completed_proposal',compact('complted_proposal'));
   
           }elseif ($param == "tranfered") {
            //Transfered 
              //   DB::enableQueryLog();
            $transfer_proposal = SchemeSend::whereNotNull('scheme_send.team_member_dd')
                                ->orderByDesc('scheme_send.id')
                                ->distinct()
                                ->get();
                //dd(DB::getQueryLog($transfer_proposal));
              //  dd($transfer_proposal->count());
            return view('dashboards.eva-dd.proposal.transfer_proposal',compact('transfer_proposal','branch_list'));

        }
    }
	
    public function get_prop_status(Request $request) {
        $id = $request->input('id');
        $role = Auth::user()->role;
        $jd_role = 14;
        $dd_role = 3;
        $ro_role = 4;

        $status_ids = ActivityApproval::where('study_id',$id)->where(function($query) {
                    $query->where('user_role',14)
                                ->orWhere('user_role',3)
                                ->orWhere('user_role',4);
        })->pluck('status_id');


        $eval_activities = Eval_activity_log::select('eval_activity_log.id','eval_activity_log.activity_name','eval_activity_log.sub_activity_name','activity_approval.approval','activity_approval.approve_reject_by','activity_approval.last_study_id')->leftjoin('itransaction.activity_approval','eval_activity_log.id','=','activity_approval.status_id')->where('activity_approval.study_id',$id)->whereIn('eval_activity_log.id',$status_ids)->orderBy('activity_approval.id')->get();

        $checked_activity = Eval_activity_log::leftjoin('itransaction.activity_approval','eval_activity_log.id','=','activity_approval.status_id')->select('eval_activity_log.id','eval_activity_log.activity_name','eval_activity_log.sub_activity_name','eval_activity_log.under_sub_activity_name','activity_approval.status_id as activity_status','activity_approval.approval','activity_approval.approve_reject_by','activity_approval.last_study_id')->where('activity_approval.study_id',$id)->whereIn('eval_activity_log.id',$status_ids)->where('activity_approval.status_id','!=',null)->orderBy('activity_approval.id','desc')->take(1)->get();

        $user_role = 4;
        $joint_dir_role = 14;
        $approved = Eval_activity_status::where('study_id',$id)->where('approval','1')->orderBy('id','desc')->take(1)->value('status_id');
        $approval_rejected = ActivityApproval::where('user_role',$joint_dir_role)->where('approval','2')->where('study_id',$id)->where('last_study_id','1')->value('status_id');
        $pending_for_approval = ActivityApproval::where('user_role',$user_role)->where('approval','0')->where('study_id',$id)->where('last_study_id','1')->value('status_id');
        $arr = array('eval_activities'=>$eval_activities,'checked_activity'=>$checked_activity,'id'=>$id,'pending_for_approval'=>$pending_for_approval,'approval_rejected'=>$approval_rejected,'approved'=>$approved);
        return response()->json($arr);
    }

    public function savestatus(Request $request) {
        $validate = Validator::make($request->all(),[
            'status_id' => 'required|numeric',
            'study_id' => 'required|numeric',
            'thenum' => 'required|numeric'
        ]);
        if($validate->fails()) {
            return response()->json('savestatus validation failed');
        } else {
            $study_id = $request->input('study_id');
            $dept_id = Proposal::where('draft_id',$study_id)->value('dept_id');
            $status_id = $request->input('status_id');
            $approve_reject = $request->input('thenum'); // 1=approve, 2=reject
            $grant_approval_if = ActivityApproval::select('id')->where('study_id',$study_id)->where('status_id',$status_id)->where('last_study_id','1')->get();
            if($grant_approval_if->isNotEmpty() and ($approve_reject == 1 OR $approve_reject == 2)) {
                ActivityApproval::where('study_id',$study_id)->update(['last_study_id'=>'0']);
                $status = new ActivityApproval;
                $status->status_id = $status_id;
                $status->user_id = Auth::user()->id;
                $status->user_role = Auth::user()->role;
                $status->study_id = $study_id;
                $status->dept_id = $dept_id;
                $status->approval = $approve_reject;
                $status->approve_reject_by = 2;
                $status->save();
                $act['userid'] = Auth::user()->id;
                $act['ip'] = $request->ip();
                $act['activity'] = 'DD send to change study activity status';
                $act['officecode'] = $dept_id;
                $act['pagereferred'] = $request->url();
                Activitylog::insert($act);
                $arr = array('approval'=>1);
            } else {
                $arr = array('approval'=>0);
            }
            return response()->json($arr);
        }
    }

    public function proposaldetail($draft_id) {
     
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
        return view('dashboards.eva-dd.proposaldetail',compact('proposal_list','dept_name','major_objectives','major_indicators','imdept','major_indicator_hod','financial_progress','replace_url','scheme_id','beneficiariesGeoLocal','bencovfile','trainingfile','iecfile','district_names','dept_names','taluka_names','conv_dept_remarks','goals','entered_goals','eval_report','gr_files','notification_files','brochure_files','pamphlets_files','otherdetailscenterstate_files','the_convergence'));
    }


    public function meetings() {
        $schemes = SchemeSend::select('proposals.draft_id','proposals.scheme_name','proposals.scheme_id')->leftjoin('itransaction.proposals','scheme_send.draft_id','=','proposals.draft_id')->where('scheme_send.status_id','25')->orderBy('scheme_send.id','desc')->get();
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
        $user_id = Auth::user()->id;
        $user_role_id = Auth::user()->role;
        $departments = Department::orderBy('dept_name','asc')->get();
        $meetings = Meetinglog::where('user_id',$user_id)->where('user_role_id',$user_role_id)->orderBy('mid','desc')->get();
        $attendees = User::select('id','name')->where('role','3')->orWhere('role','4')->get();
        $implementations = Implementation::all();
        $aftermeeting = Aftermeeting::orderBy('amid','desc')->get();
        $replace_url = URL::to('/');
        return view('dashboards.eva-dd.meetings',compact('meetings','attendees','schemelist','departments','implementations','aftermeeting','replace_url'));
    }

    public function getdepartment(Request $request) {
        $draft_id = $request->input('draft_id');
        $scheme_dept = Proposal::select('scheme_id','dept_id')->where('draft_id',$draft_id)->get();
        $scheme_name = '';
        $dept_name = '';
        foreach($scheme_dept as $key=>$val) {
            $scheme_name = Scheme::where('scheme_id',$val->scheme_id)->value('scheme_name');
            $dept_name = Department::where('dept_id',$val->dept_id)->value('dept_name');
        }
        $arr = array('scheme_name'=>$scheme_name,'dept_name'=>$dept_name);
        return response()->json($arr);
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
            // 'meeting_minutes' => 'required|mimes:docx,pdf,xlsx'
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
            // $attendees = json_encode($request->input('attendees'));
            $date = $request->input('date');
            $time = $request->input('time');
            // $venue = $request->input('venue');
            $created_at = Carbon::now();
            $attendees = json_encode(array('users'=>$request->input('attendees')));
            // print_r(json_encode($ar));
            // die();

            $attendees_emails = User::whereIn('id',$request->input('attendees'))->pluck('email');

            $details = array('draft_id'=>$draft_id, 'dept_id'=>$dept_id,'scheme_name'=>$scheme_name,'subject'=>$subject,'description'=>$subject,'description'=>$description,'venue'=>$venue,'chairperson'=>$chairperson,'attendees'=>$attendees,'date'=>$date,'time'=>$time,'created_at'=>$created_at);

            // return $details;

            $path = array();
            $document = $request->file();
            $rev = '';
            $flname = '';
            // echo "<pre>"; print_r($details); die();
            foreach($document as $dkey=>$dval) {
                $doc_id= 'scheme_'.$scheme_id;
                $extended = new Couchdb();
                $extended->InitConnection();
                $status = $extended->isRunning();
                $file = Attachment::where('couch_doc_id',$doc_id)->first();
                $file_data = json_decode($file,true);
                $rev = $file_data['couch_rev_id'];
                // return $file_data;
                $path['id'] = $doc_id;
                $path['tmp_name'] = $dval->getRealPath();
                $path['extension']  = $dval->getClientOriginalExtension();
                $path['name'] = $doc_id.'_setmeeting'.'.'.$path['extension'];
                $flname = $path['name'];
                $details['filename'] = $flname;
                // echo $rev; die();
                $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);

                // echo "<pre>";
                // print_r($out);
                // echo $rev;
                // die();

                $array = json_decode($out, true);

                // echo "<pre>";
                // print_r($array);
                // die();

                if(array_key_exists('error',$array)) {
                    echo $rev;
                    print_r($array);
                    die();
                } else {
                    $id = $array['id'];
                    $rev = $array['rev'];
                    // $data['scheme_id'] = $scheme_id;
                    // $data['couch_doc_id'] = $id;
                    $data['couch_rev_id'] = $rev;
                    $attachment = Attachment::where('scheme_id',$scheme_id)->update($data);
                    // Mail::to($attendees_emails)->send(new MeetingMail($details,$path));
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
                    return redirect()->back()->withSuccess('Email Sent & Meeting Schedule successfully Created!');
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
                // foreach($details as $akey=>$aval) {
                    $att = json_decode($details['attendees'],true);
                    foreach($att as $attkey=>$attval) {
                        $attendees[] = $attval;
                    }
                // }
                $dept_id = $detaildata[0]['dept_id'];
                if(count($attendees)) {
                    $attendees_ids = $attendees[0];
                    $attendees_emails = User::whereIn('id',$attendees_ids)->pluck('email');
                    // Mail::to($attendees_emails)->send(new MeetingMail($details));
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
        // $fmeeting = Meetinglog::join('itransaction.schemes','meetinglogs.draft_id','=','schemes.scheme_id')
        //                 ->join('imaster.departments','departments.dept_id','=','meetinglogs.dept_id')
        //                 ->where('meetinglogs.mid',$mid)
        //                 ->get();
        $fmeeting = Meetinglog::leftjoin('itransaction.proposals','meetinglogs.draft_id','=','proposals.draft_id')
                        ->leftjoin('imaster.departments','departments.dept_id','=','meetinglogs.dept_id')
                        ->where('meetinglogs.mid',$mid)
                        ->get();
        // dd($fmeeting);
        $attendees_table_id = User::select('id')->where('role','3')->orWhere('role','4')->get();
        $att_arr = array();
        if($attendees_table_id->isNotEmpty()) {
            $attendees_toarray = $attendees_table_id->toArray();
            foreach($attendees_toarray as $at_tokey => $at_toval) {
                $att_arr[] = $at_toval['id'];
            }
        }
        // dd($att_arr);
        $attendees_ids = array();
        if($fmeeting->isNotEmpty()) {
            $ss = json_decode($fmeeting[0]['attendees']);
            // dd($ss->users);
            foreach($ss->users as $skey=>$sval) {
                if(in_array($sval,$att_arr)) {
                    $attendees_ids[] = $sval;
                }
            }
        }
        // dd($attendees_ids);
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
            // $attendees = json_encode($request->input('attendees'));
            $date = $request->input('date');
            $time = $request->input('time');
            $venue = $request->input('venue');
            $created_at = Carbon::now();
            $attendees = json_encode(array('users'=>$request->input('attendees')));
            // print_r(json_encode($ar));
            // die();

            $attendees_emails = User::whereIn('id',$request->input('attendees'))->pluck('email');

            $details = array('filesare'=>'multiple','draft_id'=>$draft_id,'scheme_id'=>$scheme_id,'dept_id'=>$dept_id,'scheme_name'=>$scheme_name,'subject'=>$subject,'description'=>$subject,'description'=>$description,'venue'=>$venue,'chairperson'=>$chairperson,'attendees'=>$attendees,'date'=>$date,'time'=>$time, 'mid'=>$mid, 'created_at'=>$created_at);
            $flname = array();
            $path = array();
            $i = 0;
            $details['filename'] = array();
            if($request->hasFile('meeting_minutes')) {
                $meeting_minutes = $request->file('meeting_minutes');
                $rev = '';
            // foreach($document as $dkey=>$dval) {
                $doc_id= 'scheme_'.$scheme_id;
                $extended = new Couchdb();
                $extended->InitConnection();
                $status = $extended->isRunning();
                $file = Attachment::where('couch_doc_id',$doc_id)->first();
                $file_data = json_decode($file,true);
                $rev = $file_data['couch_rev_id'];
                // return $file_data;
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
            // foreach($document as $dkey=>$dval) {
                $doc_id= 'scheme_'.$scheme_id;
                $extended = new Couchdb();
                $extended->InitConnection();
                $status = $extended->isRunning();
                $file = Attachment::where('couch_doc_id',$doc_id)->first();
                $file_data = json_decode($file,true);
                $rev = $file_data['couch_rev_id'];
                // return $file_data;
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
            // Mail::to($attendees_emails)->send(new MeetingMail($details,$flname));
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

    public function meetingevents(Request $request) {
        $id = Auth::user()->id;
        $date = date('Y-m-01 00:00:00');
        $nextmonth = date('m') + 1;
        if($nextmonth < 10) {
            $nextmonth = '0'.$nextmonth;
            $nextdate = date("Y-$nextmonth-01");
        }
        $lastdatedays = date("t",strtotime($nextdate));
        $lastdate = date("Y-$nextmonth-$lastdatedays 23:59:59");
        $between = array($date,$lastdate);
        $meetings = Meetinglog::select('subject','date','time','attendees')->whereBetween('created_at',$between)->get();
        $meetingsare = array();
        foreach($meetings as $m => $k) {
            $att = json_decode($k->attendees);
            $all_attendees = array();
            $i = 0;
            foreach($att as $countat => $at) {
                if(in_array($id,$at)) {
                    $datetime = $k->date.' '.$k->time;
                    $meetingsare[] = array('subject'=>$k->subject,'datetime'=>strtotime($datetime));
                }
                $i++;
            }
        }
        return response()->json($meetingsare);
    }


    public function odk_project_list(){
        $project_list = ODK_Project::get();
        return view('dashboards.eva-dd.project_details',compact('project_list'));
    }

    public function allforms(Request $request,$id){
        $url = 'https://gujevl.gujarat.gov.in/v1/sessions';
        $user_list = OdkUser::where('status', true)->latest()->first();
        $decrypt_pass = base64_decode($user_list->encrypt_password);
        $postData = array(
            'email' =>  $user_list->email,
            'password' =>  $decrypt_pass
        );
        $ch = curl_init($url);
        $jsonData = json_encode($postData);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Accept-Language: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
     
        $response_json = curl_exec($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($response_json, 0, $header_size);
        $body = substr($response_json, $header_size);

        curl_close($ch);
        $response = json_decode($body, true);
      
        $token_get = $response['token'];
        $authorization = "Authorization: Bearer ".$token_get;
        $header = substr($header,8,4);
        $header = trim($header);
        $head = "200";
        $list_project_forms = 'https://gujevl.gujarat.gov.in/v1/projects/'.$id.'/forms/';
        $ch = curl_init($list_project_forms);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER,array ('Content-Type: application/json','Accept-Language: application/json',$authorization));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);	
        $response_json1 = curl_exec($ch);
        curl_close($ch);
        $selected_project_forms = json_decode($response_json1, true);
        //first Delete the entry if there are available
        ODK_Project_form::where('project_id',$id)->delete();

        foreach ($selected_project_forms as $key => $form_details) {
            ODK_Project_form::create([
                'project_id' => $form_details['projectId'],
                'name' => $form_details['name'],
                'xml_form_id' => $form_details['xmlFormId'],
                'state' =>true,
                'created_at' => $form_details['createdAt'],
                'updated_at' => $form_details['updatedAt'],
                'published_at' => $form_details['publishedAt']
            ]);
        }
       
        $p_id = $id;
        $form_details = ODK_Project_form::where('project_id','=',$p_id)->get();
       
        return view('dashboards.eva-dd.project_details',compact('form_details'));
    }



    public function schemes() {
        $activity_status = Eval_activity_status::pluck('study_id');
         $collection_schemes = Scheme::orderBy('scheme_id','desc')->get()->toArray();
         $collection = collect($collection_schemes);
         $schemes = $collection->unique();
 
        $schemes->values()->all();
    
        return view('dashboards.eva-dd.schemes',compact('schemes'));
    }

    public function schemedetail($scheme_id) {
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
        $iecfile = $this->getthefilecount($scheme_id,'_iec_file');
        $district_ids = array();
        foreach($schemes as $key=>$value) {
            $dept_name = Department::where('dept_id',$value->dept_id)->value('dept_name');
            $convener = Convener::where('con_id',$value->con_id)->get();
            $major_objectives = $value->major_objective;
            $major_indicators = $value->major_indicator;
            $imdept = Implementation::where('id',$value->im_id)->get();
            $adviseris = Adviser::where('adviser_id',$value->adviser_id)->get();
            $major_indicator_hod = $value->major_indicator_hod;
            $financial_progress = FinancialProgress::where('scheme_id',$value->scheme_id)->get();
            if($value->districts) {
                $district_ids = explode(',',$value->districts);
            }
        }
        $district_names = array();
        if(count($district_ids) > 0) {
            foreach($district_ids as $dkey=>$dval) {
                $district_names[] = Districts::where('dcode',$dval)->value('name_e');
            }
        }
        return view('dashboards.eva-dd.schemedetail',compact('schemes','dept_name','convener','major_objectives','major_indicators','imdept','adviseris','major_indicator_hod','financial_progress','replace_url','scheme_id','beneficiariesGeoLocal','bencovfile','trainingfile','iecfile','district_names'));
    }

    public function communication() {
        $userid = Auth::user()->id;
        $teams = Teams::orderBy('team_id','desc')->get();
        $proposal_ids = array();
        foreach($teams as $tkey => $tval) {
            $team_user_arr = json_decode($tval->team_member_dd);
            $team_count = 0;
            for($k=0;$k<count($team_user_arr);$k++) {
                $team_count++;
            }
            if($team_count > 0) {
                for($i=0;$i<$team_count;$i++) {
                    if(in_array($userid, $team_user_arr)) {
                        $proposal_ids[] = $tval->study_id;
                    }
                }
            }
        }
        $studies = Proposal::select('draft_id','scheme_name')->whereIn('draft_id',$proposal_ids)->orderBy('scheme_name')->get();
        $topics = CommunicationTopics::orderBy('id','desc')->get();
        $communication = Communication::select('communication.*','proposals.scheme_name','communication_topics.topic as topic_name', 'departments.dept_name')->leftjoin('itransaction.proposals','proposals.draft_id','=','communication.study_id')->leftjoin('imaster.communication_topics','communication.topic_id','=','communication_topics.id')->whereIn('communication.study_id',$proposal_ids)->orderBy('communication.id','desc')->leftjoin('imaster.departments','communication.dept_id','=','departments.dept_id')->get();
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
            $date = date('d F, Y',strtotime($val->created_at));
            $arr = array('study_id'=>$study_id,'topic_id'=>$topic_id,'topic_text'=>$topic_text,'remarks'=>$remarks,'document_count'=>$document_count,'document'=>$document,'dept_id'=>$dept_id,'scheme_id'=>$scheme_id,'scheme_name'=>$scheme_name,'topic_name'=>$topic_name,'dept_name'=>$dept_name,'file_type'=>$file_type,'date'=>$date);
            $communication_arr[] = $arr;
        }
        return view('dashboards.eva-dd.communication',compact('topics','studies','communication_arr'));
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
                $topic_id = '';
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
      //  dd($arrays);
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
        // return $at_name;
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
	
    public function profile(){
        return view('dashboards.eva-dd.profile');
    }
    public function updateInfo(Request $request){
        $validator = \Validator::make($request->all(),[
            'name'=>'required',
            'email'=> 'required|email|unique:users,email,'.Auth::user()->id,
           // 'favoritecolor'=>'required',
        ]);

        if(!$validator->passes()){
            return response()->json(['status'=>0,'error'=>$validator->errors()->toArray()]);
        }else{
             $query = User::find(Auth::user()->id)->update([
                  'name'=>$request->name,
                  'email'=>$request->email,
                 // 'favoriteColor'=>$request->favoritecolor,
                  'phone'=>$request->phone
             ]);

             if(!$query){
                 return response()->json(['status'=>0,'msg'=>'Something went wrong.']);
             }else{
                 return response()->json(['status'=>1,'msg'=>'Your profile info has been update successfuly.']);
             }
        }
   }

public function updatePicture(Request $request){
    $path = 'users/images/';
    $file = $request->file('admin_image');
    $new_name = 'UIMG_'.date('Ymd').uniqid().'.jpg';

    //Upload new image
    $upload = $file->move(public_path($path), $new_name);
    
    if( !$upload ){
        return response()->json(['status'=>0,'msg'=>'Something went wrong, upload new picture failed.']);
    }else{
        //Get Old picture
        $oldPicture = User::find(Auth::user()->id)->getAttributes()['picture'];

        if( $oldPicture != '' ){
            if( \File::exists(public_path($path.$oldPicture))){
                \File::delete(public_path($path.$oldPicture));
            }
        }

        //Update DB
        $update = User::find(Auth::user()->id)->update(['picture'=>$new_name]);

        if( !$upload ){
            return response()->json(['status'=>0,'msg'=>'Something went wrong, updating picture in db failed.']);
        }else{
            return response()->json(['status'=>1,'msg'=>'Your profile picture has been updated successfully']);
        }
    }
}

public function changePassword(Request $request){
        //Validate form
        $validator = \Validator::make($request->all(),[
            'oldpassword'=>[
                'required', function($attribute, $value, $fail){
                    if( !\Hash::check($value, Auth::user()->password) ){
                        return $fail(__('The current password is incorrect'));
                    }
                },
                'min:8',
                'max:30'
            ],
            'newpassword'=>'required|min:8|max:30',
            'cnewpassword'=>'required|same:newpassword'
        ],[
            'oldpassword.required'=>'Enter your current password',
            'oldpassword.min'=>'Old password must have atleast 8 characters',
            'oldpassword.max'=>'Old password must not be greater than 30 characters',
            'newpassword.required'=>'Enter new password',
            'newpassword.min'=>'New password must have atleast 8 characters',
            'newpassword.max'=>'New password must not be greater than 30 characters',
            'cnewpassword.required'=>'ReEnter your new password',
            'cnewpassword.same'=>'New password and Confirm new password must match'
        ]);

        if( !$validator->passes() ){
            return response()->json(['status'=>0,'error'=>$validator->errors()->toArray()]);
        }else{
            
        $update = User::find(Auth::user()->id)->update(['password'=>\Hash::make($request->newpassword)]);

        if( !$update ){
            return response()->json(['status'=>0,'msg'=>'Something went wrong, Failed to update password in db']);
        }else{
            return response()->json(['status'=>1,'msg'=>'Your password has been changed successfully']);
        }
        }
    
}

public function sendschemetodd(Request $request) {
       
    // dd($request->all());
     $validate = Validator::make($request->all(),[
         'remarks' => 'string|required',
     ]);
     if($validate->fails()) {
         return redirect()->back()->withError($validate->errors());
     } else {

        $draft_id = Crypt::decrypt($request->draft_id);
        $dept_id = Crypt::decrypt($request->dept_id);
        $id = Crypt::decrypt($request->send_id);

        $update = SchemeSend::where('draft_id',$draft_id)->where('dept_id',$dept_id)->where('id',$id)->latest()->first();
        
         // Assuming you have a specific Branch instance, for example, retrieved from the form data
         $branch = Branch::find($request->branch);

         // Access the related role IDs using the defined relationship
         $roleIds = $branch->roles->pluck('id')->toArray();

         if($update){
             $update->update([
                 'user_id' => Auth::user()->id,
                 'created_by' => Auth::user()->name,
                 'remarks' => (!is_null($request->remarks)) ? $request->remarks : null,
                 'team_member_dd' => (!is_null($roleIds)) ? implode(',', $roleIds)  : null,
             ]);
         }else{
             SchemeSend::create([
                 'draft_id' => (!is_null($draft_id)) ? $draft_id : null,
                 'dept_id' => (!is_null($dept_id)) ? $dept_id : null,
                 'user_id' => Auth::user()->id,
                 'created_by' => Auth::user()->name,
                 'remarks' => (!is_null($request->remarks)) ? $request->remarks : null,
                 'team_member_dd' => (!is_null($roleIds)) ? implode(',', $roleIds)  : null,
             ]);
         }
        
         return redirect()->back()->withSuccess('Proposal sent successfully to DD');
     }
}

public function branch(Request $request) {
   
    $role_id = Crypt::decrypt($request->input('role_id'));

    if (!is_null($role_id)) {
        $roleIds = explode(',', $role_id);
        $selected_branch = BranchRole::whereIn('role_id', $roleIds)->value('branch_id');

        return response()->json(['branch_id' => $selected_branch]);
    } else {
        return response()->json(['error' => 'Role ID not provided'], 400);
    }
}
public function updateBranch(Request $request) {
   
    $id = Crypt::decrypt($request->id);
    if(!is_null($id)){
        $scheme_send = SchemeSend::find($id);
  
        // Assuming you have a specific Branch instance, for example, retrieved from the form data
        $branch = Branch::find($request->branch);

        // Access the related role IDs using the defined relationship
        $roleIds = $branch->roles->pluck('id')->toArray();

    
        $scheme_send->update([ 
            'user_id' => Auth::user()->id,
            'created_by' => Auth::user()->name,
            'team_member_dd' => (!is_null($roleIds)) ? implode(',', $roleIds)  : null,
        ]);
        return redirect()->back()->with(['success' =>'Branch update successfully']);

    }else{
        return redirect()->back()->with(['error' =>'Something went wrong']);
    }
       
    //}
}
public function downloadStagereport()
{
    try {

        $report_item = Stage::with(['schemeSend' => function ($q) {
            $q->whereNotNull('team_member_dd');
        }])->whereNull('document')->get();  

        $current_date = now()->format('d-m-Y');

        $pdf = PDF::loadView('dashboards.stage.pdf.stage-report', compact('report_item'))
                    ->setPaper('a4', 'landscape');

        return $pdf->download('stage-detail-report-' . $current_date . '.pdf');
    } catch (Exception $e) {
        return redirect()->back()->with(['error' => $e->getMessage()]);
    }
}


}