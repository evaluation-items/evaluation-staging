<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\Proposal;
use App\Models\SchemeSend;
use App\Models\Status;
use App\Models\Scheme;
use DB;
use App\Models\Convener;
use App\Models\GrFilesList;
use App\Models\NotificationFileList;
use App\Models\BrochureFileList;
use App\Models\PamphletFileList;
use App\Models\CenterStateFiles;
use App\Models\FinancialProgress;
use App\Models\Implementation;
use App\Models\Adviser;
use App\Models\Nodal;
use App\Couchdb\Couchdb;
use App\Models\Meetinglog;
use App\Models\Aftermeeting;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Attachment;
use App\Mail\MeetingMail;
use App\Jobs\MeetingEMailJob;
use App\Models\Status_log;
use App\Models\Districts;
use App\Models\User_user_role_deptid;
use App\Models\Eval_activity_status;
use App\Models\Eval_activity_log;
use App\Models\Dept_meetings;
use App\Models\Stage;
use App\Models\Teams;
use URL;
use App\Models\Sdggoals;
use App\Models\Taluka;
use Arr;
use App\Models\Activitylog;
use App\Models\CommunicationTopics;
use App\Models\Communication;
use App\Models\MeetingsViewedBy;
use App\Models\Eval_activity_status_dates;
use App\Models\User_user_role;
use App\Models\Subdepartment;
use App\Mail\ForwardProposalMail;
use Illuminate\Support\Facades\Crypt;
use App\Http\ViewComposers\draftComposer;

class ProposalController extends Controller
{
    public function proposalItems($param){
        $dept_id = Auth::user()->dept_id;
        //New Proposal
        if($param == "new"){
            $sentprops = SchemeSend::pluck('draft_id');
            $proposals_new = Proposal::whereNotIn('draft_id',$sentprops)->where('dept_id',$dept_id)->orderBy('draft_id','desc')->get();
            return view('dashboards.proposal.new_proposal',compact('proposals_new'));

        }elseif ($param == "on_going") {
                //Ongoing 
            $ongoing_proposal = Stage::WhereNull('document')->where('dept_id',$dept_id)->get();

            return view('dashboards.proposal.ongoing_proposal',compact('ongoing_proposal'));

        }elseif ($param == "forward") {
            //Forwarded Proposal

            $the_status_id = '23';
            
            $theforwarded = SchemeSend::select('id','draft_id','status_id')->where('status_id',$the_status_id)->where('dept_id',$dept_id)->orderBy('id','desc')->get()->toArray();
            $forwarded_draft_ids = array();
            $statusid = 0;
            foreach($theforwarded as $kfor => $vfor) {
                $forwarded_draft_ids[] = $vfor['id'];
            }
           
            $proposals_forwarded = SchemeSend::leftjoin('itransaction.proposals','itransaction.scheme_send.draft_id','=','itransaction.proposals.draft_id')
                                                ->select('scheme_send.*','proposals.draft_id as prop_draft_id','proposals.dept_id as prop_dept_id','proposals.scheme_name','proposals.scheme_overview','proposals.scheme_objective','proposals.sub_scheme','proposals.status_id as prop_status_id','proposals.scheme_id as scheme_id')
                                                ->whereIn('scheme_send.id',$forwarded_draft_ids)
                                                ->where('scheme_send.dept_id',$dept_id)
                                                ->where('scheme_send.status_id',$the_status_id)
                                                ->orderBy('scheme_send.status_id','asc')
                                                ->orderBy('scheme_send.id','desc')
                                                ->get();

            return view('dashboards.proposal.forward_proposal',compact('proposals_forwarded'));

        }elseif ($param == "return") {
            //Return Proposal

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
     
            return view('dashboards.proposal.return_proposal',compact('proposals_returned'));

        }elseif ($param == "completed") {
            //Completed 
           $complted_proposal = Stage::WhereNotNull('document')->where('dept_id',$dept_id)->get();

           return view('dashboards.proposal.completed_proposal',compact('complted_proposal'));

        }

    }

    public function count_all_proposals($param) {
        $dept_id = Auth::user()->dept_id;
    
        if ($param == "new") {
            $sentprops = SchemeSend::pluck('draft_id');
            return Proposal::whereNotIn('draft_id', $sentprops)
                ->where('dept_id', $dept_id)
                ->count();
        } elseif ($param == "forward") {
            $the_status_id = '23';
            $theforwarded = SchemeSend::select('id', 'draft_id', 'status_id')
                                    ->where('status_id', $the_status_id)
                                    ->where('dept_id', $dept_id)
                                    ->orderBy('id', 'desc')
                                    ->get()
                                    ->toArray();
            $forwarded_draft_ids = array();
            foreach ($theforwarded as $kfor => $vfor) {
                $forwarded_draft_ids[] = $vfor['id'];
            }
            return SchemeSend::whereIn('id', $forwarded_draft_ids)
                ->where('dept_id', $dept_id)
                ->where('status_id', $the_status_id)
                ->count();
        } elseif ($param == "on_going") {
            // Assuming Stage model is used for ongoing proposals
            return Stage::whereNull('document')->where('dept_id',$dept_id)->count();
        }elseif ($param == "return") {
            //Return Proposal
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
          return  SchemeSend::leftjoin('itransaction.proposals','itransaction.scheme_send.draft_id','=','itransaction.proposals.draft_id')
                                                ->select('scheme_send.*','proposals.draft_id as prop_draft_id','proposals.dept_id as prop_dept_id','proposals.scheme_name','proposals.scheme_overview','proposals.scheme_objective','proposals.sub_scheme', 'proposals.status_id as prop_status_id')
                                                ->whereIn('scheme_send.id',$returned_draft_ids)
                                                ->orderBy('scheme_send.id','desc')
                                                ->count();
     
        }elseif ($param == "completed") {
            //Completed 
          return Stage::WhereNotNull('document')->where('dept_id',$dept_id)->count();
        }
    
        // Default return 0 if $param doesn't match any condition
        return 0;
    }
    

    
    public function dashboard() {
        $new_count = $this->count_all_proposals("new");
        $forward_count = $this->count_all_proposals("forward");
        $ongoing_count = $this->count_all_proposals("on_going");
        $return_count = $this->count_all_proposals("return");
        $completed_count = $this->count_all_proposals("completed");
      //  DB::enableQuerylog();
         $sentprops = SchemeSend::pluck('draft_id');
        
        $scheme_list = Proposal::where('dept_id',Auth::user()->dept_id)->orderBy('draft_id','desc')->pluck('scheme_name','draft_id',);
     //dd(DB::getQuerylog($scheme_list));
        //  $scheme_list = barchartScheme();
        $draft_id = [];
        foreach ($scheme_list as $key => $value) {
            $draft_id[] = $key;
        }
        draftComposer::setDraftId($draft_id);
        return view('dashboard', compact('new_count', 'forward_count', 'ongoing_count','return_count','completed_count','scheme_list','draft_id'));
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
    public function forwardtodept(Request $request){
        $validate = Validator::make($request->all(),[
            'remarks' => 'string|nullable',
            'draft_id' => 'required',
            'send_id' => 'required'
        ]);
        if($validate->fails()) {
           return redirect()->back()->withError($validate->errors());
        } else {
            $data = [];
            $user_id = Auth()->user()->id;
            $dept_id = Auth::user()->dept_id;
            $send_id = Crypt::decrypt($request->send_id);
            $data['created_at'] = date('Y-m-d h:i:s');
            $d_id = Crypt::decrypt($request->draft_id);
            $data['status_id'] = '23';
            $data['user_id'] = Auth::user()->id;
            $data['created_by'] = Auth::user()->name;
            $data['dept_id'] = $dept_id;
            $data['draft_id'] = $d_id;
            $data['remarks'] = $request->remarks;
            $data['forward_id'] = 1;
            $s_id = '23';
            $update = SchemeSend::where('draft_id',$d_id)->latest()->first();
           
            if($request->send_id != null) {
                unset($data['_token']);
                unset($request->send_id);
                Proposal::where('draft_id','=',$d_id)->update(['status_id'=> $s_id]);
                SchemeSend::where('draft_id',$d_id)->update(['forward_btn_show'=>1,'forward_id'=>1]);
            } else {
                unset($request->send_id);
                unset($data['_token']);
                Proposal::where('draft_id',$d_id)->update(['status_id'=>$s_id]);
            }
            if($update){
                 $update->update($data);
            }else{
                SchemeSend::insert($data);
            }
            $email = "liltosopsu@gufum.com";
           // $email = ["direvl@gujarat.gov.in","ds-plan-gad@gujarat.gov.in"];
            $details = [
                'scheme_name' =>  (!is_null($d_id)) ? Proposal::where('draft_id','=',$d_id)->value('scheme_name') : '-' ,
                'department' => (!is_null(Auth::user()->dept_id)) ? department_name(Auth::user()->dept_id) : '-',
                'hod_name' => (!is_null($d_id)) ? Proposal::where('draft_id','=',$d_id)->value('hod_name') : '-'
            ];
            Mail::to($email)->send(new ForwardProposalMail($details));

            return redirect()->back()->withSuccess('Proposal successfully sent to GAD.');
        }
    }
}
