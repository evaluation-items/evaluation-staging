<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;
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
use Mail;
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
use Illuminate\Support\Facades\Crypt;


class GadsecController extends Controller {

    public $envirment;
    public function __construct() {
        $this->middleware('auth');
        $this->envirment = environmentCheck();
    }

    public function getcalendardata(Request $request) {
        $start_date = date('Y-m-01');
        $end_date = date('Y-m-t');

        $data = Meetinglog::whereDate('date', '>=', $start_date)
                   ->whereDate('end',   '<=', $end_date)
                   ->get(['mid as id', 'subject as title', 'date as start', 'time as end']);
         return response()->json($data);
    }

    public function count_all_proposals($param) {
        $dept_id = Auth::user()->dept_id;
    
        if ($param == "new") {
            $status_id = '23';
           return SchemeSend::leftjoin('imaster.departments','scheme_send.dept_id','=','departments.dept_id')->select('scheme_send.*','proposals.draft_id as prop_draft_id','proposals.dept_id as prop_dept_id','proposals.scheme_name','proposals.scheme_overview','proposals.scheme_objective','proposals.sub_scheme','proposals.status_id as prop_status_id','departments.dept_name')
                                        ->leftjoin('itransaction.proposals','scheme_send.draft_id','=','proposals.draft_id')
                                        ->where('scheme_send.status_id',$status_id)
                                        ->where('scheme_send.forward_btn_show','1')
                                        ->orderBy('scheme_send.status_id')
                                        ->orderBy('scheme_send.id','desc')
                                        ->count();
        } elseif ($param == "forward") {
            $the_status_id = '23';
           return SchemeSend::leftjoin('itransaction.proposals','itransaction.scheme_send.draft_id','=','itransaction.proposals.draft_id')
                                ->select('scheme_send.*','proposals.draft_id as prop_draft_id','proposals.dept_id as prop_dept_id','proposals.scheme_name','proposals.scheme_overview','proposals.scheme_objective','proposals.sub_scheme','proposals.status_id as prop_status_id')
                                ->where('scheme_send.forward_btn_show','1')
                                ->where('scheme_send.dept_id',$dept_id)
                                ->where('scheme_send.status_id',$the_status_id)
                                ->orderBy('scheme_send.status_id','asc')
                                ->orderBy('scheme_send.id','desc')
                                ->count();
        } elseif ($param == "on_going") {
            // Assuming Stage model is used for ongoing proposals
            return Stage::WhereNull('document')->count();
        }elseif ($param == "return") {
            //Return Proposal
           return $returned_proposals = SchemeSend::leftjoin('imaster.departments','scheme_send.dept_id','=','departments.dept_id')->select('scheme_send.*','proposals.draft_id as prop_draft_id','proposals.dept_id as prop_dept_id','proposals.scheme_name','proposals.scheme_overview','proposals.scheme_objective','proposals.sub_scheme','proposals.status_id as prop_status_id','departments.dept_name')
                        ->leftjoin('itransaction.proposals','scheme_send.draft_id','=','proposals.draft_id')
                        ->whereIn('scheme_send.status_id',['24','26','27'])
                        ->where('scheme_send.forward_btn_show','1')
                        ->orderBy('scheme_send.status_id')
                        ->orderBy('scheme_send.id','desc')
                        ->count();
     
        }elseif ($param == "completed") {
            //Completed 
          return Stage::WhereNotNull('document')->count();
        }elseif ($param = "approved") {
            return SchemeSend::leftjoin('imaster.departments','scheme_send.dept_id','=','departments.dept_id')->select('scheme_send.*','proposals.draft_id as prop_draft_id','proposals.dept_id as prop_dept_id','proposals.scheme_name','proposals.scheme_overview','proposals.scheme_objective','proposals.sub_scheme','proposals.status_id as prop_status_id','departments.dept_name')
                                    ->leftjoin('itransaction.proposals','scheme_send.draft_id','=','proposals.draft_id')
                                    ->where('scheme_send.status_id','25')
                                    ->orderBy('scheme_send.status_id')
                                    ->orderBy('scheme_send.id','desc')
                                    ->count();

                 
        }
    
        // Default return 0 if $param doesn't match any condition
        return 0;
    }
    

    
    public function index() {
        $new_count = $this->count_all_proposals("new");
        $forward_count = $this->count_all_proposals("forward");
        $ongoing_count = $this->count_all_proposals("on_going");
        $return_count = $this->count_all_proposals("return");
        $completed_count = $this->count_all_proposals("completed");
        $approved_count = $this->count_all_proposals("approved");

        return view('dashboards.gad-sec.index', compact('new_count', 'forward_count', 'ongoing_count','return_count','completed_count','approved_count'));
    }
   

    public function approvedproposals() {
        $proposals_to_approve = Eval_activity_status::where('approved_by','1')->pluck('study_id');
        $proposals = Proposal::select('proposals.*','departments.dept_id','departments.dept_name')->leftjoin('imaster.departments','proposals.dept_id','=','departments.dept_id')->whereIn('proposals.draft_id',$proposals_to_approve)->orderBy('proposals.draft_id','desc')->get();
        return $proposals;
    }

    public function pendingapprovalproposals() {
        $proposals = SchemeSend::select('scheme_send.*','proposals.scheme_name','proposals.scheme_id','proposals.scheme_objective','proposals.scheme_overview','proposals.commencement_year','departments.dept_id','departments.dept_name')->leftjoin('itransaction.proposals','scheme_send.draft_id','=','proposals.draft_id')->leftjoin('imaster.departments','scheme_send.dept_id','=','departments.dept_id')->orderBy('scheme_send.id','desc')->get();
        return $proposals;
    }

    public function gaddashboard() {
        $get_year = date('Y');
        $last_year = $get_year-1;
        $fn_years = financialyears();
        $the_month = date('m');
        if($the_month < 4) {
            $show_date = '01-Apr-'.$last_year;
        } else {
            $show_date = '01-Apr-'.$get_year;
        }
        return view('dashboards.gad-sec.gad_dashboard',compact('last_year','show_date','fn_years'));
    }

    public function withfinyear(Request $request) {
        $get_year = date('Y');
        $last_year = $get_year-1;
        $main_menu = Eval_activity_log::groupBy('group_id_dashboard')->orderBy('group_id_dashboard','desc')->pluck('group_id_dashboard');
        $get_after_dept_name = array();
        $get_meet_dept_name = array();
        $published = 0;
        $ecc = 0;
        $dept_meetings = 0;
        $get_dept_meet_dept_name = array();
        $get_scheme_name = array();
        $understanding_schemes_count = 0;
        $get_scheme_name = array();
        $main_activity_names = array();
        $dept_or_scheme_list = array();
        $dept_or_scheme_count = array();
        $total_scheme_count = array();

        $the_first_year = $request->get('first_year');
        $the_second_year = $request->get('second_year');
        $first_year_is = explode('-',$the_first_year);
        $first_year = $first_year_is[0];
        $second_year_is = explode('-',$the_second_year);
        $second_year = $second_year_is[0];
        $last_fin_year = date('Y-m-d H:i:s',strtotime($first_year.'-04-01 00:00:00'));
        $current_fin_year = date('Y-m-d H:i:s',strtotime($second_year.'-03-31 11:59:59'));
        $the_month = date('m');
        $the_year = date('Y');
        if($the_month > 4 && $the_year <= $second_year) {
            $current_fin_year = date('Y-m-d H:i:s');
        }
        $date_between = array($last_fin_year,$current_fin_year);
        $team_current_study_ids = Teams::distinct('study_id')->whereBetween('created_at',$date_between)->pluck('study_id');

        $previous_year = $first_year - 1;
        $very_previous_date = date('Y-m-d H:i:s',strtotime($previous_year.'-04-01 00:00:00'));
        $previous_date = date('Y-m-d H:i:s',strtotime($first_year.'-03-31 23:59:59'));
        $previous_date_between = array($very_previous_date,$previous_date);
        $team_previous_study_ids = Teams::distinct('study_id')->whereBetween('created_at',$previous_date_between)->pluck('study_id');

        //start number of proposals received
            $studies_of_previous_year = Eval_activity_status::distinct('study_id')->whereBetween('created_at',$previous_date_between)->whereIn('study_id',$team_previous_study_ids)->where('status_id','!=',null)->groupBy('study_id')->count();
        //end number of proposals received

       
        //start new studies of the current year
            $studies_of_the_current_year = Eval_activity_status::distinct('study_id')->where('current_status','1')->whereBetween('created_at',$date_between)->whereIn('study_id',$team_current_study_ids)->where('status_id','!=',null)->whereNotIn('status_id',[31,32,33])->count('*');
        
        //start 
            $completed_studies = Eval_activity_status::distinct('study_id')->where('current_status','1')->where('approval','1')->whereBetween('created_at',$date_between)->whereIn('study_id',$team_current_study_ids)->where('status_id','!=',null)->where('status_id','26')->count('*');
        //end 

        //start 
            $total_studies = $studies_of_previous_year + $studies_of_the_current_year + $completed_studies;
        //end 

        $s = 0;
        foreach($main_menu as $mk => $mv) {

            if($mv == '8') {
                $rep_status_id = array('31','32','33');
                $team_study_ids = Teams::whereBetween('created_at',$date_between)->orderBy('team_id','desc')->pluck('study_id');
                $report_writing = Eval_activity_status::where('current_status','1')->whereBetween('created_at',$date_between)->whereIn('status_id',$rep_status_id)->whereIn('study_id',$team_study_ids)->where('status_id','!=',null)->where('approval','1')->count('*');
                if($report_writing > 0) {
                    $main_activity_names[] = Eval_activity_log::where('group_id_dashboard',$mv)->value('activity_dashboard_name');
                    $dept_or_scheme_list[$main_activity_names[$s]] = array();
                    $arr_count = array();
                    $ar = array();
                    $total_scheme_count[$s] = array_sum($arr_count);
                    if($report_writing > 0) {
                        $rep_writ = Eval_activity_status::distinct('study_id')->whereBetween('created_at',$date_between)->whereIn('status_id',$rep_status_id)->whereIn('study_id',$team_study_ids)->where('status_id','!=',null)->where('approval','1')->where('current_status','1')->pluck('study_id');
                        $the_arr = Proposal::distinct('scheme_id')->whereIn('draft_id',$rep_writ)->pluck('dept_id');
                        $depts = Department::select('dept_id','dept_name')->whereIn('dept_id',$the_arr)->get();
                        foreach($depts as $dk => $dv) {
                            $ar[] = array('dept_id'=>$dv->dept_id, 'dept_name'=>$dv->dept_name,'modal_type'=>'report_writing', 'marginTop'=>'0');
                            $arr_count[] = Proposal::whereBetween('created_at',$date_between)->where('dept_id',$dv->dept_id)->whereIn('draft_id',$rep_writ)->count('*');
                        }
                        $dept_or_scheme_list[$main_activity_names[$s]] = $ar;
                        $dept_or_scheme_count[$main_activity_names[$s]] = $arr_count;
                        $total_scheme_count[$s] = array_sum($arr_count);
                    }
                    $s++;
                }
            } else if($mv == '7') {
                $rep_status_id = array('30');
                $team_study_ids = Teams::distinct('study_id')->whereBetween('created_at',$date_between)->pluck('study_id');
                $report_writing = Eval_activity_status::where('current_status','1')->whereBetween('created_at',$date_between)->whereIn('study_id',$team_study_ids)->whereIn('status_id',$rep_status_id)->whereIn('study_id',$team_study_ids)->where('status_id','!=',null)->where('approval','1')->count('*');
                if($report_writing > 0) {
                    $main_activity_names[] = Eval_activity_log::where('group_id_dashboard',$mv)->value('activity_dashboard_name');
                    $dept_or_scheme_list[$main_activity_names[$s]] = array();
                    $arr_count = array();
                    $ar = array();
                    $total_scheme_count[$s] = array_sum($arr_count);
                    if($report_writing > 0) {
                        $rep_writ = Eval_activity_status::distinct('study_id')->whereBetween('created_at',$date_between)->whereIn('status_id',$rep_status_id)->whereIn('study_id',$team_study_ids)->where('status_id','!=',null)->where('approval','1')->where('current_status','1')->pluck('study_id');
                        $the_arr = Proposal::distinct('scheme_id')->whereIn('draft_id',$rep_writ)->pluck('dept_id');
                        $depts = Department::select('dept_id','dept_name')->whereIn('dept_id',$the_arr)->get();
                        foreach($depts as $dk => $dv) {
                            $ar[] = array('dept_id'=>$dv->dept_id, 'dept_name'=>$dv->dept_name,'modal_type'=>'report_writing', 'marginTop'=>'0');
                            $arr_count[] = Proposal::whereBetween('created_at',$date_between)->where('dept_id',$dv->dept_id)->whereIn('draft_id',$rep_writ)->count('*');
                        }
                        $dept_or_scheme_list[$main_activity_names[$s]] = $ar;
                        $dept_or_scheme_count[$main_activity_names[$s]] = $arr_count;
                        $total_scheme_count[$s] = array_sum($arr_count);
                    }
                    $s++;
                }
            } else if($mv == '6') {
                $rep_status_id = array('29');
                $team_study_ids = Teams::whereBetween('created_at',$date_between)->orderBy('team_id','desc')->pluck('study_id');
                $report_writing = Eval_activity_status::where('current_status','1')->whereBetween('created_at',$date_between)->whereIn('status_id',$rep_status_id)->whereIn('study_id',$team_study_ids)->where('status_id','!=',null)->where('approval','1')->count('*');
                if($report_writing > 0) {
                    $main_activity_names[] = Eval_activity_log::where('group_id_dashboard',$mv)->value('activity_dashboard_name');
                    $dept_or_scheme_list[$main_activity_names[$s]] = array();
                    $arr_count = array();
                    $ar = array();
                    $total_scheme_count[$s] = array_sum($arr_count);
                    if($report_writing > 0) {
                        $rep_writ = Eval_activity_status::distinct('study_id')->whereBetween('created_at',$date_between)->whereIn('status_id',$rep_status_id)->whereIn('study_id',$team_study_ids)->where('status_id','!=',null)->where('approval','1')->where('current_status','1')->pluck('study_id');
                        $the_arr = Proposal::distinct('scheme_id')->whereIn('draft_id',$rep_writ)->pluck('dept_id');
                        $depts = Department::select('dept_id','dept_name')->whereIn('dept_id',$the_arr)->get();
                        foreach($depts as $dk => $dv) {
                            $ar[] = array('dept_id'=>$dv->dept_id, 'dept_name'=>$dv->dept_name,'modal_type'=>'report_writing', 'marginTop'=>'0');
                            $arr_count[] = Proposal::whereBetween('created_at',$date_between)->where('dept_id',$dv->dept_id)->whereIn('draft_id',$rep_writ)->count('*');
                        }
                        $dept_or_scheme_list[$main_activity_names[$s]] = $ar;
                        $dept_or_scheme_count[$main_activity_names[$s]] = $arr_count;
                        $total_scheme_count[$s] = array_sum($arr_count);
                    }
                    $s++;
                }
            } else if($mv == '4') {
                $log_status_ids = array('22','23','24','25','26','27','28');
                $team_study_ids = Teams::whereBetween('created_at',$date_between)->orderBy('team_id','desc')->pluck('study_id');
                $impact_analysis = Eval_activity_status::where('current_status','1')->where('status_id','!=',null)->whereBetween('created_at',$date_between)->whereIn('status_id',$log_status_ids)->whereIn('study_id',$team_study_ids)->where('approval','1')->count('*');
                if($impact_analysis > 0) {
                    $main_activity_names[] = Eval_activity_log::where('group_id_dashboard',$mv)->value('activity_dashboard_name');
                    $dept_or_scheme_list[$main_activity_names[$s]] = array();
                    $arr_count = array();
                    $ar = array();
                    $total_scheme_count[$s] = array_sum($arr_count);
                    if($impact_analysis > 0) {
                        $rep_writ = Eval_activity_status::distinct('study_id')->where('current_status','1')->where('status_id','!=',null)->whereBetween('created_at',$date_between)->whereIn('status_id',$log_status_ids)->whereIn('study_id',$team_study_ids)->where('approval','1')->pluck('study_id');
                        $the_arr = Proposal::distinct('scheme_id')->whereIn('draft_id',$rep_writ)->pluck('dept_id');
                        $depts = Department::select('dept_id','dept_name')->whereIn('dept_id',$the_arr)->get();
                        foreach($depts as $dk => $dv) {
                            $ar[] = array('dept_id'=>$dv->dept_id, 'dept_name'=>$dv->dept_name,'modal_type'=>'report_writing', 'marginTop'=>'0');
                            $arr_count[] = Proposal::whereBetween('created_at',$date_between)->where('dept_id',$dv->dept_id)->whereIn('draft_id',$rep_writ)->count('*');
                        }
                        $dept_or_scheme_list[$main_activity_names[$s]] = $ar;
                        $dept_or_scheme_count[$main_activity_names[$s]] = $arr_count;
                        $total_scheme_count[$s] = array_sum($arr_count);
                    }
                    $s++;
                }
            } else if($mv == '3') {
                $log_status_ids = array('19','20','21');
                $team_study_ids = Teams::whereBetween('created_at',$date_between)->orderBy('team_id','desc')->pluck('study_id');
                $impact_analysis = Eval_activity_status::where('current_status','1')->whereBetween('created_at',$date_between)->whereIn('status_id',$log_status_ids)->whereIn('study_id',$team_study_ids)->where('status_id','!=',null)->where('approval','1')->count('*');
                if($impact_analysis > 0) {
                    $main_activity_names[] = Eval_activity_log::where('group_id_dashboard',$mv)->value('activity_dashboard_name');
                    $dept_or_scheme_list[$main_activity_names[$s]] = array();
                    $arr_count = array();
                    $ar = array();
                    $total_scheme_count[$s] = array_sum($arr_count);
                    if($impact_analysis > 0) {
                        $rep_writ = Eval_activity_status::distinct('study_id')->where('current_status','1')->whereBetween('created_at',$date_between)->whereIn('status_id',$log_status_ids)->whereIn('study_id',$team_study_ids)->where('status_id','!=',null)->where('approval','1')->pluck('study_id');
                        $the_arr = Proposal::distinct('scheme_id')->whereIn('draft_id',$rep_writ)->pluck('dept_id');
                        $depts = Department::select('dept_id','dept_name')->whereIn('dept_id',$the_arr)->get();
                        foreach($depts as $dk => $dv) {
                            $ar[] = array('dept_id'=>$dv->dept_id, 'dept_name'=>$dv->dept_name,'modal_type'=>'report_writing', 'marginTop'=>'0');
                            $arr_count[] = Proposal::whereBetween('created_at',$date_between)->where('dept_id',$dv->dept_id)->whereIn('draft_id',$rep_writ)->count('*');
                        }
                        $dept_or_scheme_list[$main_activity_names[$s]] = $ar;
                        $dept_or_scheme_count[$main_activity_names[$s]] = $arr_count;
                        $total_scheme_count[$s] = array_sum($arr_count);
                    }
                    $s++;
                }
            } else if($mv == '2') {
                $log_status_ids = array('7','8','9','10','11','12','13','14','15','16','17','18');
                $team_study_ids = Teams::whereBetween('created_at',$date_between)->orderBy('team_id','desc')->pluck('study_id');
                $sample_frame = Eval_activity_status::where('current_status','1')->whereBetween('created_at',$date_between)->whereIn('status_id',$log_status_ids)->whereIn('study_id',$team_study_ids)->where('status_id','!=',null)->where('approval','1')->count('*');
                if($sample_frame > 0) {
                    $main_activity_names[] = Eval_activity_log::where('group_id_dashboard',$mv)->orderBy('id')->take(1)->value('activity_dashboard_name');
                    $dept_or_scheme_list[$main_activity_names[$s]] = array();
                    $arr_count = array();
                    $ar = array();
                    $total_scheme_count[$s] = array_sum($arr_count);
                    if($sample_frame > 0) {
                        $rep_writ = Eval_activity_status::distinct('study_id')->where('current_status','1')->whereBetween('created_at',$date_between)->whereIn('status_id',$log_status_ids)->whereIn('study_id',$team_study_ids)->where('status_id','!=',null)->where('approval','1')->pluck('study_id');
                        $the_arr = Proposal::distinct('scheme_id')->whereIn('draft_id',$rep_writ)->pluck('dept_id');
                        $depts = Department::select('dept_id','dept_name')->whereIn('dept_id',$the_arr)->get();
                        foreach($depts as $dk => $dv) {
                            $ar[] = array('dept_id'=>$dv->dept_id, 'dept_name'=>$dv->dept_name,'modal_type'=>'report_writing', 'marginTop'=>'0');
                            $arr_count[] = Proposal::whereBetween('created_at',$date_between)->where('dept_id',$dv->dept_id)->whereIn('draft_id',$rep_writ)->count('*');
                        }
                        $dept_or_scheme_list[$main_activity_names[$s]] = $ar;
                        $dept_or_scheme_count[$main_activity_names[$s]] = $arr_count;
                        $total_scheme_count[$s] = array_sum($arr_count);
                    }
                    $s++;
                }
            } else if($mv == '1') {
                $log_status_ids = array('1','2','3','4','5','6');
                $team_study_ids = Teams::whereBetween('created_at',$date_between)->orderBy('team_id','desc')->pluck('study_id');
                $analysis_qry = Eval_activity_status::where('current_status','1')->whereBetween('created_at',$date_between)->whereIn('status_id',$log_status_ids)->whereIn('study_id',$team_study_ids)->where('status_id','!=',null)->where('approval','1')->count('*');
                if($analysis_qry > 0) {
                    $main_activity_names[] = Eval_activity_log::where('group_id_dashboard',$mv)->value('activity_dashboard_name');
                    $dept_or_scheme_list[$main_activity_names[$s]] = array();
                    $ar = array();
                    $arr_count = array();
                    $total_scheme_count[$s] = array_sum($arr_count);
                    if($analysis_qry > 0) {
                        $rep_writ = Eval_activity_status::distinct('study_id')->where('current_status','1')->whereBetween('created_at',$date_between)->whereIn('status_id',$log_status_ids)->whereIn('study_id',$team_study_ids)->where('status_id','!=',null)->where('approval','1')->pluck('study_id');
                        $the_arr = Proposal::distinct('scheme_id')->whereIn('draft_id',$rep_writ)->pluck('dept_id');
                        $depts = Department::select('dept_id','dept_name')->whereIn('dept_id',$the_arr)->get();
                        foreach($depts as $dk => $dv) {
                            $ar[] = array('dept_id'=>$dv->dept_id, 'dept_name'=>$dv->dept_name,'modal_type'=>'report_writing', 'marginTop'=>'0');
                            $arr_count[] = Proposal::whereBetween('created_at',$date_between)->where('dept_id',$dv->dept_id)->whereIn('draft_id',$rep_writ)->count('*');
                        }
                        $dept_or_scheme_list[$main_activity_names[$s]] = $ar;
                        $dept_or_scheme_count[$main_activity_names[$s]] = $arr_count;
                        $total_scheme_count[$s] = array_sum($arr_count);
                    }
                    $s++;
                }
            }
        }

        $total_dept_or_schemes_slides = count($dept_or_scheme_list) / 3;
        $dept_or_schemes_slides = ceil($total_dept_or_schemes_slides);
        $main_activity_names_count = count($main_activity_names);

        $the_month = date('m');
        if($the_month < 4) {
            $show_date = '01-Apr-'.$last_year;
        } else {
            $show_date = '01-Apr-'.$get_year;
        }
        $the_arr = array('show_date'=>$show_date,'main_activity_names'=>$main_activity_names,'dept_or_scheme_list'=>$dept_or_scheme_list,'dept_or_schemes_slides'=>$dept_or_schemes_slides,'dept_or_scheme_count'=>$dept_or_scheme_count,'total_scheme_count'=>$total_scheme_count,'main_activity_names_count'=>$main_activity_names_count,'studies_of_previous_year'=>$studies_of_previous_year,'studies_of_the_current_year'=>$studies_of_the_current_year,'total_studies'=>$total_studies,'completed_studies'=>$completed_studies);

        return response()->json($the_arr);
    }

    public function in_array_any($needles, $haystack) {
        return !empty(array_intersect($needles, $haystack));
    }

    public function deptdashboardgadstatus(Request $request) {
        $dept_id = $request->get('dept_id');
        $thisid = $request->get('the_id');
        $expthisid = explode('&mav_',$thisid);
        $dept_name = str_replace('_',' ',$expthisid[1]);

        $the_first_year = $request->get('first_fin_year');
        $the_second_year = $request->get('second_fin_year');
        $first_year_is = explode('-',$the_first_year);
        $first_year = $first_year_is[0];
        $second_year_is = explode('-',$the_second_year);
        $second_year = $second_year_is[0];
        $last_fin_year = date('Y-m-d H:i:s',strtotime($first_year.'-04-01 00:00:00'));
        $current_fin_year = date('Y-m-d H:i:s',strtotime($second_year.'-03-31 11:59:59'));
        $the_month = date('m');
        $the_year = date('Y');
        if($the_month > 4 && $the_year <= $second_year) {
            $current_fin_year = date('Y-m-d H:i:s');
        }
        $date_between = array($last_fin_year,$current_fin_year);

        if($dept_id > 0) {
            $the_dept_id = $dept_id;
            $group_id_by_name = Eval_activity_log::where('activity_dashboard_name',$dept_name)->value('group_id_dashboard');
            $get_activity_ids = Eval_activity_log::where('group_id_dashboard',$group_id_by_name)->pluck('id');
            $get_activity_status_ids = Eval_activity_status::distinct('study_id')->whereIn('status_id',$get_activity_ids)->pluck('study_id');
            $activity_ids = $get_activity_ids->toArray();

       
            if(in_array('00',$activity_ids)) {
                $pub_qry = Aftermeeting::select('draft_id','subject','chairperson','date','time')->whereBetween('created_at',$date_between)->where('dept_id',$the_dept_id)->orderBy('amid','desc')->get();
                $data = array();
                foreach($pub_qry as $ek => $ev) {
                    $sch_name = Proposal::where('draft_id',$ev->draft_id)->value('scheme_name');
                    $dateis = date('d M, Y',strtotime($ev->date));
                    $data[] = array('scheme_name'=>$sch_name, 'subject'=>$ev->subject, 'chairperson'=>$ev->chairperson, 'date'=>$dateis, 'time'=>$ev->time);
                }
                return response()->json($data);
         
            } else if(in_array('00',$activity_ids)) {
                $ecc_badge_count = 0;
                $ecc_qry = Meetinglog::select('draft_id','subject','chairperson','date','time')->whereBetween('created_at',$date_between)->where('dept_id',$the_dept_id)->get();
                $data = array();
                foreach($ecc_qry as $ek => $ev) {
                    $sch = Proposal::where('draft_id',$ev->draft_id)->value('scheme_name');
                    $dateis = date('d M, Y',strtotime($ev->date));
                    $data[] = array('scheme_name'=>$sch, 'subject'=>$ev->subject, 'chairperson'=>$ev->chairperson, 'date'=>$dateis, 'time'=>$ev->time);
                }
                return response()->json($data);
          
            } else if(in_array('00',$activity_ids)) {
                $dec_qry = Dept_meetings::select('mid','subject','chairperson','date','time','venue','scheme_id')->whereBetween('created_at',$date_between)->where('dept_id',$the_dept_id)->get();
                $data = array();
                foreach($dec_qry as $dk => $dv) {
                    $decscheme = Scheme::where('scheme_id',$dv->scheme_id)->value('scheme_name');
                    $dateis = date('d M, Y',strtotime($dv->date));
                    $data[] = array('scheme_name'=>$decscheme, 'subject'=>$dv->subject, 'chairperson'=>$dv->chairperson, 'date'=>$dateis, 'time'=>$dv->time);
                }
                return response()->json($data);
            } else if($this->in_array_any(['1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','18','19','20','21','22','23','24','25','26','27','28','29','30','31','32','33'],$activity_ids)) {
              

                $team_study_ids = Teams::whereBetween('created_at',$date_between)->whereIn('study_id',$get_activity_status_ids)->pluck('study_id');
               
                $the_study_ids = Eval_activity_status::join('itransaction.proposals','eval_activity_status.study_id','=','proposals.draft_id')->whereIn('eval_activity_status.status_id',$activity_ids)->whereIn('eval_activity_status.study_id',$team_study_ids)->where('eval_activity_status.current_status','1')->where('eval_activity_status.approval','1')->where('proposals.dept_id',$the_dept_id)->pluck('eval_activity_status.study_id');

                $proposal = Proposal::select('draft_id','scheme_name','entry_date','nodal_officer_name')->whereIn('draft_id',$get_activity_status_ids)->whereBetween('created_at',$date_between)->where('dept_id',$the_dept_id)->whereIn('draft_id',$the_study_ids)->orderBy('draft_id','desc')->get();
                $data = array();
                foreach($proposal as $dk => $dv) {
                    $scheme_name = $dv->scheme_name;
                    $study_date = Eval_activity_status::whereIn('status_id',$activity_ids)->where('study_id',$dv->draft_id)->where('approval','1')->value('created_at');
                    $createdat = date('d M, Y',strtotime($study_date));
                    $data[] = array('scheme_name'=>$scheme_name,'entry_date'=>$createdat,'nodal_name'=>$dv->nodal_officer_name);
                }
                return response()->json($data);
            }
        }
    }

    public function publishedstudies(Request $request) {
       
        $name = $request->get('name');
        $dept_id = $request->get('dept_id');
        $team_leader = $request->get('team_leader');
    

        $the_first_year = $request->get('first_fin_year');
        $the_second_year = $request->get('second_fin_year');
        $first_year_is = explode('-',$the_first_year);
        $first_year = $first_year_is[0];
        $second_year_is = explode('-',$the_second_year);
        $second_year = $second_year_is[0];
        $last_fin_year = date('Y-m-d H:i:s',strtotime($first_year.'-04-01 00:00:00'));
        $current_fin_year = date('Y-m-d H:i:s',strtotime($second_year.'-03-31 23:59:59'));
        $the_month = date('m');
        $the_year = date('Y');
        if($the_month > 4 && $the_year <= $second_year) {
            $current_fin_year = date('Y-m-d H:i:s');
        }
        $date_between = array($last_fin_year,$current_fin_year);

        $group_id_by_name = Eval_activity_log::where('activity_dashboard_name',$name)->value('group_id_dashboard');
        $get_activity_ids = Eval_activity_log::where('group_id_dashboard',$group_id_by_name)->pluck('id');
        $activity_ids = $get_activity_ids->toArray();
       

        return $this->getpublished_studies($activity_ids,$date_between,$dept_id,$team_leader);
    }

    public function getpublished_studies($activity_ids,$date_between,$dept_id,$team_leader) {
        if($this->in_array_any(['31','32','33'],$activity_ids)) {
            $ids = array('31','32','33');
            $team_study_ids = Teams::whereBetween('created_at',$date_between)->orderBy('team_id','desc')->pluck('study_id');
            $study_ids = Eval_activity_status::distinct('study_id')->where('current_status','1')->where('current_status','1')->whereIn('status_id',$ids)->where('approval','1')->whereIn('study_id',$team_study_ids)->whereBetween('created_at',$date_between)->pluck('study_id');
            if($dept_id != '') {
                $get_dept_ids = Proposal::distinct('scheme_id')->whereIn('draft_id',$study_ids)->where('dept_id',$dept_id)->pluck('dept_id');
            } else {
                $get_dept_ids = Proposal::distinct('scheme_id')->whereIn('draft_id',$study_ids)->pluck('dept_id');
            }
            $studies = Proposal::whereIn('draft_id',$study_ids)->whereIn('dept_id',$get_dept_ids)->orderBy('draft_id','desc')->get();
            $study = array();
            foreach($studies as $k => $v) {
                $dd_id = Teams::where('study_id',$v->draft_id)->orderBy('team_id','desc')->value('dd_leader');
                $dd_name = User::where('id',$dd_id)->value('name');
                if($dd_id == '') {
                    $dd_name = 'No Team';
                }
                $scheme_name = Proposal::where('draft_id',$v->draft_id)->value('scheme_name');
                $dept_name = Department::where('dept_id',$v->dept_id)->value('dept_name');
                $study_date = Eval_activity_status::where('current_status','1')->whereIn('status_id',$ids)->where('study_id',$v->draft_id)->where('approval','1')->value('created_at');
                $createdat = date('d M, Y',strtotime($study_date));
                $study[] = array('DD'=>$dd_name,'scheme_name'=>$scheme_name,'dept_name'=>$dept_name,'date'=>$createdat,'remarks'=>$v->remarks);
            }
            return response()->json($study);
        } elseif(in_array('30',$activity_ids)) {
            $ids = array('30');
            $team_study_ids = Teams::whereBetween('created_at',$date_between)->orderBy('team_id','desc')->pluck('study_id');
            $study_ids = Eval_activity_status::distinct('study_id')->where('current_status','1')->whereIn('status_id',$ids)->where('approval','1')->whereIn('study_id',$team_study_ids)->whereBetween('created_at',$date_between)->pluck('study_id');
            if($dept_id != '') {
                $get_dept_ids = Proposal::distinct('scheme_id')->whereIn('draft_id',$study_ids)->where('dept_id',$dept_id)->pluck('dept_id');
            } else {
                $get_dept_ids = Proposal::distinct('scheme_id')->whereIn('draft_id',$study_ids)->pluck('dept_id');
            }
            $studies = Proposal::whereIn('draft_id',$study_ids)->whereIn('dept_id',$get_dept_ids)->orderBy('draft_id','desc')->get();
            $study = array();
            foreach($studies as $k => $v) {
                $dd_id = Teams::where('study_id',$v->draft_id)->orderBy('team_id','desc')->value('dd_leader');
                $dd_name = User::where('id',$dd_id)->value('name');
                if($dd_id == '') {
                    $dd_name = 'No Team';
                }
                $scheme_name = Proposal::where('draft_id',$v->draft_id)->value('scheme_name');
                $dept_name = Department::where('dept_id',$v->dept_id)->value('dept_name');
                $study_date = Eval_activity_status::where('current_status','1')->whereIn('status_id',$ids)->where('study_id',$v->draft_id)->where('approval','1')->value('created_at');
                $createdat = date('d M, Y',strtotime($study_date));
                $study[] = array('DD'=>$dd_name,'scheme_name'=>$scheme_name,'dept_name'=>$dept_name,'date'=>$createdat,'remarks'=>$v->remarks);
            }
            return response()->json($study);
        } elseif(in_array('29',$activity_ids)) {
            $ids = array('29');
            $team_study_ids = Teams::whereBetween('created_at',$date_between)->orderBy('team_id','desc')->pluck('study_id');
            $study_ids = Eval_activity_status::distinct('study_id')->where('current_status','1')->whereIn('status_id',$ids)->where('approval','1')->whereIn('study_id',$team_study_ids)->whereBetween('created_at',$date_between)->pluck('study_id');
            if($dept_id != '') {
                $get_dept_ids = Proposal::distinct('scheme_id')->whereIn('draft_id',$study_ids)->where('dept_id',$dept_id)->pluck('dept_id');
            } else {
                $get_dept_ids = Proposal::distinct('scheme_id')->whereIn('draft_id',$study_ids)->pluck('dept_id');
            }
            $studies = Proposal::whereIn('draft_id',$study_ids)->whereIn('dept_id',$get_dept_ids)->orderBy('draft_id','desc')->get();
            $study = array();
            foreach($studies as $k => $v) {
                $dd_id = Teams::where('study_id',$v->draft_id)->orderBy('team_id','desc')->value('dd_leader');
                $dd_name = User::where('id',$dd_id)->value('name');
                if($dd_id == '') {
                    $dd_name = 'No Team';
                }
                $scheme_name = Proposal::where('draft_id',$v->draft_id)->value('scheme_name');
                $dept_name = Department::where('dept_id',$v->dept_id)->value('dept_name');
                $study_date = Eval_activity_status::where('current_status','1')->whereIn('status_id',$ids)->where('study_id',$v->draft_id)->where('approval','1')->value('created_at');
                $createdat = date('d M, Y',strtotime($study_date));
                $study[] = array('DD'=>$dd_name,'scheme_name'=>$scheme_name,'dept_name'=>$dept_name,'date'=>$createdat,'remarks'=>$v->remarks);
            }
            return response()->json($study);
        } elseif($this->in_array_any(['22','23','24','25','26','27','28'],$activity_ids)) {
            $ids = array('22','23','24','25','26','27','28');
            $team_study_ids = Teams::whereBetween('created_at',$date_between)->orderBy('team_id','desc')->pluck('study_id');
            $study_ids = Eval_activity_status::where('current_status','1')->where('status_id','!=',null)->whereIn('status_id',$ids)->where('approval','1')->whereIn('study_id',$team_study_ids)->whereBetween('created_at',$date_between)->orderBy('id','desc')->pluck('study_id');
            // dd($date_between);
            if($dept_id != '') {
                $get_dept_ids = Proposal::distinct('scheme_id')->whereIn('draft_id',$study_ids)->where('dept_id',$dept_id)->pluck('dept_id');
            } else {
                $get_dept_ids = Proposal::distinct('scheme_id')->whereIn('draft_id',$study_ids)->pluck('dept_id');
            }
            // dd($get_dept_ids);
            $studies = Proposal::whereIn('draft_id',$study_ids)->whereIn('dept_id',$get_dept_ids)->orderBy('draft_id','desc')->get();
            $study = array();
            foreach($studies as $k => $v) {
                $dd_id = Teams::where('study_id',$v->draft_id)->orderBy('team_id','desc')->value('dd_leader');
                $dd_name = User::where('id',$dd_id)->value('name');
                if($dd_id == '') {
                    $dd_name = 'No Team';
                }
                $scheme_name = Proposal::where('draft_id',$v->draft_id)->value('scheme_name');
                $dept_name = Department::where('dept_id',$v->dept_id)->value('dept_name');
                $study_date = Eval_activity_status::where('current_status','1')->whereIn('status_id',$ids)->where('study_id',$v->draft_id)->where('approval','1')->value('created_at');
                $createdat = date('d M, Y',strtotime($study_date));
                $study[] = array('DD'=>$dd_name,'scheme_name'=>$scheme_name,'dept_name'=>$dept_name,'date'=>$createdat,'remarks'=>$v->remarks);
            }
            return response()->json($study);
        } elseif($this->in_array_any(['19','20','21'],$activity_ids)) {
            $ids = array('19','20','21');
            $team_study_ids = Teams::whereBetween('created_at',$date_between)->orderBy('team_id','desc')->pluck('study_id');
            $study_ids = Eval_activity_status::distinct('study_id')->where('current_status','1')->whereIn('status_id',$ids)->where('approval','1')->whereIn('study_id',$team_study_ids)->whereBetween('created_at',$date_between)->pluck('study_id');
            if($dept_id != '') {
                $get_dept_ids = Proposal::distinct('scheme_id')->whereIn('draft_id',$study_ids)->where('dept_id',$dept_id)->pluck('dept_id');
            } else {
                $get_dept_ids = Proposal::distinct('scheme_id')->whereIn('draft_id',$study_ids)->pluck('dept_id');
            }
            $studies = Proposal::whereIn('draft_id',$study_ids)->whereIn('dept_id',$get_dept_ids)->orderBy('draft_id','desc')->get();
            $study = array();
            foreach($studies as $k => $v) {
                $dd_id = Teams::where('study_id',$v->draft_id)->orderBy('team_id','desc')->value('dd_leader');
                $dd_name = User::where('id',$dd_id)->value('name');
                if($dd_id == '') {
                    $dd_name = 'No Team';
                }
                $scheme_name = Proposal::where('draft_id',$v->draft_id)->value('scheme_name');
                $dept_name = Department::where('dept_id',$v->dept_id)->value('dept_name');
                $study_date = Eval_activity_status::where('current_status','1')->whereIn('status_id',$ids)->where('study_id',$v->draft_id)->where('approval','1')->orderBy('id','desc')->take(1)->value('created_at');
                $createdat = date('d M, Y',strtotime($study_date));
                $study[] = array('DD'=>$dd_name,'scheme_name'=>$scheme_name,'dept_name'=>$dept_name,'date'=>$createdat,'remarks'=>$v->remarks);
            }
            return response()->json($study);
        } elseif($this->in_array_any(['7','8','9','10','11','12','13','14','15','16','17','18'],$activity_ids)) {
            $ids = array('7','8','9','10','11','12','13','14','15','16','17','18');
            $team_study_ids = Teams::whereBetween('created_at',$date_between)->orderBy('team_id','desc')->pluck('study_id');
            $study_ids = Eval_activity_status::distinct('study_id')->where('current_status','1')->whereIn('status_id',$ids)->where('approval','1')->whereIn('study_id',$team_study_ids)->whereBetween('created_at',$date_between)->pluck('study_id');
            if($dept_id != '') {
                $get_dept_ids = Proposal::distinct('scheme_id')->whereIn('draft_id',$study_ids)->where('dept_id',$dept_id)->pluck('dept_id');
            } else {
                $get_dept_ids = Proposal::distinct('scheme_id')->whereIn('draft_id',$study_ids)->pluck('dept_id');
            }
            $studies = Proposal::whereIn('draft_id',$study_ids)->whereIn('dept_id',$get_dept_ids)->orderBy('draft_id','desc')->get();
            $study = array();
            foreach($studies as $k => $v) {
                $dd_id = Teams::where('study_id',$v->draft_id)->orderBy('team_id','desc')->value('dd_leader');
                $dd_name = User::where('id',$dd_id)->value('name');
                if($dd_id == '') {
                    $dd_name = 'No Team';
                }
                $scheme_name = Proposal::where('draft_id',$v->draft_id)->value('scheme_name');
                $dept_name = Department::where('dept_id',$v->dept_id)->value('dept_name');
                $study_date = Eval_activity_status::where('current_status','1')->whereIn('status_id',$ids)->where('study_id',$v->draft_id)->where('approval','1')->value('created_at');
                $createdat = date('d M, Y',strtotime($study_date));
                $study[] = array('DD'=>$dd_name,'scheme_name'=>$scheme_name,'dept_name'=>$dept_name,'date'=>$createdat,'remarks'=>$v->remarks);
            }
            return response()->json($study);
        } elseif($this->in_array_any(['1','2','3','4','5','6'],$activity_ids)) {
            $ids = array(1,2,3,4,5,6);
            $team_study_ids = Teams::whereBetween('created_at',$date_between)->orderBy('team_id','desc')->pluck('study_id');
            $study_ids = Eval_activity_status::distinct('study_id')->where('current_status','1')->whereIn('status_id',$ids)->where('approval','1')->whereIn('study_id',$team_study_ids)->whereBetween('created_at',$date_between)->pluck('study_id');
            if($dept_id != '') {
                $get_dept_ids = Proposal::distinct('scheme_id')->whereIn('draft_id',$study_ids)->where('dept_id',$dept_id)->pluck('dept_id');
            } else {
                $get_dept_ids = Proposal::distinct('scheme_id')->whereIn('draft_id',$study_ids)->pluck('dept_id');
            }
            $studies = Proposal::whereIn('draft_id',$study_ids)->whereIn('dept_id',$get_dept_ids)->orderBy('draft_id','desc')->get();
            $study = array();
            foreach($studies as $k => $v) {
                $dd_id = Teams::where('study_id',$v->draft_id)->orderBy('team_id','desc')->value('dd_leader');
                $dd_name = User::where('id',$dd_id)->value('name');
                if($dd_id == '') {
                    $dd_name = 'No Team';
                }
                $scheme_name = Proposal::where('draft_id',$v->draft_id)->value('scheme_name');
                $dept_name = Department::where('dept_id',$v->dept_id)->value('dept_name');
                $study_date = Eval_activity_status::where('current_status','1')->whereIn('status_id',$ids)->where('study_id',$v->draft_id)->where('approval','1')->value('created_at');
                $createdat = date('d M, Y',strtotime($study_date));
                $study[] = array('DD'=>$dd_name,'scheme_name'=>$scheme_name,'dept_name'=>$dept_name,'date'=>$createdat,'remarks'=>$v->remarks);
            }
            return response()->json($study);
        }
    }

    public function evaldashboard() {
        $get_year = date('Y');
        $last_year = $get_year-1;
        $fn_years = financialyears();
        $the_month = date('m');
        if($the_month < 4) {
            $show_date = '01-Apr-'.$last_year;
        } else {
            $show_date = '01-Apr-'.$get_year;
        }
        return view('dashboards.gad-sec.eval_dashboard',compact('last_year','show_date','fn_years'));
    }

    public function eval_withfinyear(Request $request) {
        $finyear = $request->get('finyear');
        $get_year = date('Y');
        $last_year = $get_year-1;
    
        $main_menu = Eval_activity_log::groupBy('group_id')->orderBy('group_id','desc')->pluck('group_id');
   
        $get_after_dept_name = array();
        $get_meet_dept_name = array();
        $published = 0;
        $ecc = 0;
        $dept_meetings = 0;
        $get_dept_meet_dept_name = array();
        $get_scheme_name = array();
        $understanding_schemes_count = 0;
        $get_scheme_name = array();
        $main_activity_names = array();
        $dept_or_scheme_list = array();
        $dept_or_scheme_count = array();
        $total_scheme_count = array();

        $split_finyear = explode('-',$finyear);
       
        $the_year_last = $split_finyear[0];
        $the_last_year = date('Y-m-d H:i:s',strtotime($the_year_last.'-04-01 00:00:00'));
    
        $the_cur_year = date('Y-m-d H:i:s');
     
        $date_between = array($the_last_year,$the_cur_year);

        //start number of proposals received
        $number_of_proposals = Eval_activity_status::distinct('study_id')->whereBetween('created_at',$date_between)->groupBy('study_id')->count();
        //end number of proposals received

        //start pending proposals
        $received_proposals_pending_for_decision = SchemeSend::where('status_id','23')->where('forward_btn_show','1')->whereBetween('created_at',$date_between)->count();
        //end pending proposals

        //start returned proposals for decision
        $returned_proposals_pending_for_decision = SchemeSend::where('status_id','26')->orWhere('status_id','24')->where('forward_btn_show','1')->whereBetween('created_at',$date_between)->count();
        //end returned proposals for decision

        // return $date_between;
        $s = 0;
        foreach($main_menu as $mk => $mv) {

            if($mv == '12') {

                $published = Aftermeeting::whereBetween('created_at',$date_between)->count('*');
                if($published > 0) {
                    $main_activity_names[] = Eval_activity_log::where('group_id',$mv)->value('activity_dashboard_name');
                    $dept_or_scheme_list[$main_activity_names[$s]] = array();
                    $arr_count = array();
                    $ar = array();
                    $total_scheme_count[$s] = array_sum($arr_count);
                    if($published > 0) {
                        $get_meet = Aftermeeting::select('amid','dept_id','draft_id')->orderBy('amid','desc')->whereBetween('created_at',$date_between)->get();
                        foreach($get_meet as $getkey => $getval) {
                            $the_arr = Department::select('dept_id','dept_name')->where('dept_id',$getval->dept_id)->get();
                      
                            foreach($the_arr as $a => $b) {
                                $ar[] = array('dept_id'=>$b->dept_id, 'dept_name'=>$b->dept_name,'modal_type'=>'Publication', 'marginTop'=>'-15px');
                            }
                            $arr_count[] = Aftermeeting::where('dept_id',$getval->dept_id)->count('*');
                        }
                        if(array_sum($arr_count) != 0) {
                            $dept_or_scheme_list[$main_activity_names[$s]] = $ar;
                            $dept_or_scheme_count[$main_activity_names[$s]] = $arr_count;
                            $total_scheme_count[$s] = array_sum($arr_count);
                        }
                    }
                    $s++;
                }

            } else if($mv == '11') {
                $ecc = Meetinglog::whereBetween('created_at',$date_between)->count("*");
                if($ecc > 0) {
                    $main_activity_names[] = Eval_activity_log::where('group_id',$mv)->value('activity_dashboard_name');
                    $dept_or_scheme_list[$main_activity_names[$s]] = array();
                    $arr_count = array();
                    $ar = array();
                    $total_scheme_count[$s] = array_sum($arr_count);
                    if($ecc > 0) {
                        $meet = Meetinglog::distinct()->whereBetween('created_at',$date_between)->select('dept_id')->get();
                        foreach($meet as $mkey=>$mval) {
                            $the_arr = Department::select('dept_id','dept_name')->where('dept_id',$mval->dept_id)->get();
                            foreach($the_arr as $a => $b) {
                                ++$a;
                                $ar[] = array('dept_id'=>$b->dept_id, 'dept_name'=>$b->dept_name,'modal_type'=>'Publication', 'marginTop'=>'-15px');

                            }
                            $arr_count[] = Meetinglog::whereBetween('created_at',$date_between)->where('dept_id',$mval->dept_id)->count('*');
                           
                        }
                       
                        $dept_or_scheme_list[$main_activity_names[$s]] = $ar;
                        $dept_or_scheme_count[$main_activity_names[$s]] = $arr_count;
                        $total_scheme_count[$s] = array_sum($arr_count);
                    }
                    $s++;
                }

            } else if($mv == '10') {
                $dec = Dept_meetings::whereBetween('created_at',$date_between)->count("*");
                if($dec > 0) {
                    $main_activity_names[] = Eval_activity_log::where('group_id',$mv)->value('activity_dashboard_name');
                    $dept_or_scheme_list[$main_activity_names[$s]] = array();
                    $arr_count = array();
                    $ar = array();
                    $total_scheme_count[$s] = array_sum($arr_count);
                    if($dec > 0) {
                        $meet = Dept_meetings::distinct()->select('dept_id')->whereBetween('created_at',$date_between)->get();
                        foreach($meet as $mkey=>$mval) {
                            $the_arr = Department::select('dept_id','dept_name')->where('dept_id',$mval->dept_id)->get();
                            foreach($the_arr as $a => $b) {
                                $ar[] = array('dept_id'=>$b->dept_id, 'dept_name'=>$b->dept_name,'modal_type'=>'Publication', 'marginTop'=>'-15px');
                            }
                            $arr_count[] = Dept_meetings::where('dept_id',$mval->dept_id)->count('*');
                        }
                        $dept_or_scheme_list[$main_activity_names[$s]] = $ar;
                        $dept_or_scheme_count[$main_activity_names[$s]] = $arr_count;
                        $total_scheme_count[$s] = array_sum($arr_count);
                    }
                    $s++;
                }
            } else if($mv == '8') {
                $rep_status_id = array('22');
                $report_writing = Eval_activity_status::whereBetween('created_at',$date_between)->whereIn('status_id',$rep_status_id)->where('approval','1')->count('*');
                if($report_writing > 0) {
                    $main_activity_names[] = Eval_activity_log::where('group_id',$mv)->value('activity_dashboard_name');
                    $dept_or_scheme_list[$main_activity_names[$s]] = array();
                    $arr_count = array();
                    $ar = array();
                    $total_scheme_count[$s] = array_sum($arr_count);
                    if($report_writing > 0) {
                        $rep_writ = Eval_activity_status::distinct('study_id')->whereBetween('created_at',$date_between)->whereIn('status_id',$rep_status_id)->where('approval','1')->pluck('study_id');
                        // $schemesendids = SchemeSend::whereIn('draft_id',$rep_writ)->where('status_id','25')->orderBy('id','desc')->pluck('draft_id');
                        $the_arr = Proposal::distinct('scheme_id')->whereIn('draft_id',$rep_writ)->pluck('dept_id');
                        $depts = Department::select('dept_id','dept_name')->whereIn('dept_id',$the_arr)->get();
                        foreach($depts as $dk => $dv) {
                            $ar[] = array('dept_id'=>$dv->dept_id, 'dept_name'=>$dv->dept_name,'modal_type'=>'report_writing', 'marginTop'=>'0');
                            $arr_count[] = Proposal::where('dept_id',$dv->dept_id)->whereIn('draft_id',$rep_writ)->count('*');
                        }
                        $dept_or_scheme_list[$main_activity_names[$s]] = $ar;
                        $dept_or_scheme_count[$main_activity_names[$s]] = $arr_count;
                        $total_scheme_count[$s] = array_sum($arr_count);
                    }
                    $s++;
                }
            } else if($mv == '7') {
                $log_status_ids = array('19','20','21');
                $impact_analysis = Eval_activity_status::whereBetween('created_at',$date_between)->whereIn('status_id',$log_status_ids)->where('approval','1')->count('*');
                if($impact_analysis > 0) {
                    $main_activity_names[] = Eval_activity_log::where('group_id',$mv)->value('activity_dashboard_name');
                    $dept_or_scheme_list[$main_activity_names[$s]] = array();
                    $arr_count = array();
                    $ar = array();
                    $total_scheme_count[$s] = array_sum($arr_count);
                    if($impact_analysis > 0) {
                        $rep_writ = Eval_activity_status::distinct('study_id')->whereBetween('created_at',$date_between)->whereIn('status_id',$log_status_ids)->where('approval','1')->pluck('study_id');
                        $the_arr = Proposal::distinct('scheme_id')->whereIn('draft_id',$rep_writ)->pluck('dept_id');
                        $depts = Department::select('dept_id','dept_name')->whereIn('dept_id',$the_arr)->get();
                        foreach($depts as $dk => $dv) {
                            $ar[] = array('dept_id'=>$dv->dept_id, 'dept_name'=>$dv->dept_name,'modal_type'=>'report_writing', 'marginTop'=>'0');
                            $arr_count[] = Proposal::where('dept_id',$dv->dept_id)->whereIn('draft_id',$rep_writ)->count('*');
                        }
                        $dept_or_scheme_list[$main_activity_names[$s]] = $ar;
                        $dept_or_scheme_count[$main_activity_names[$s]] = $arr_count;
                        $total_scheme_count[$s] = array_sum($arr_count);
                    }
                    $s++;
                }

            } else if($mv == '6') {
                $log_status_ids = array('18');
                $impact_analysis = Eval_activity_status::whereBetween('created_at',$date_between)->whereIn('status_id',$log_status_ids)->where('approval','1')->count('*');
                if($impact_analysis > 0) {
                    $main_activity_names[] = Eval_activity_log::where('group_id',$mv)->value('activity_dashboard_name');
                    $dept_or_scheme_list[$main_activity_names[$s]] = array();
                    $arr_count = array();
                    $ar = array();
                    $total_scheme_count[$s] = array_sum($arr_count);
                    if($impact_analysis > 0) {
                        $rep_writ = Eval_activity_status::distinct('study_id')->whereBetween('created_at',$date_between)->whereIn('status_id',$log_status_ids)->where('approval','1')->pluck('study_id');
                        $the_arr = Proposal::distinct('scheme_id')->whereIn('draft_id',$rep_writ)->pluck('dept_id');
                        $depts = Department::select('dept_id','dept_name')->whereIn('dept_id',$the_arr)->get();
                        foreach($depts as $dk => $dv) {
                            $ar[] = array('dept_id'=>$dv->dept_id, 'dept_name'=>$dv->dept_name,'modal_type'=>'report_writing', 'marginTop'=>'0');
                            $arr_count[] = Proposal::where('dept_id',$dv->dept_id)->whereIn('draft_id',$rep_writ)->count('*');
                        }
                        $dept_or_scheme_list[$main_activity_names[$s]] = $ar;
                        $dept_or_scheme_count[$main_activity_names[$s]] = $arr_count;
                        $total_scheme_count[$s] = array_sum($arr_count);
                    }
                    $s++;
                }
            } else if($mv == '4') {
                $log_status_ids = array('14','15','16');
                $sample_frame = Eval_activity_status::whereBetween('created_at',$date_between)->whereIn('status_id',$log_status_ids)->where('approval','1')->count('*');
                if($sample_frame > 0) {
                    $main_activity_names[] = Eval_activity_log::where('group_id',$mv)->orderBy('id')->take(1)->value('activity_dashboard_name');
                    $dept_or_scheme_list[$main_activity_names[$s]] = array();
                    $arr_count = array();
                    $ar = array();
                    $total_scheme_count[$s] = array_sum($arr_count);
                    if($sample_frame > 0) {
                        $rep_writ = Eval_activity_status::distinct('study_id')->whereBetween('created_at',$date_between)->whereIn('status_id',$log_status_ids)->where('approval','1')->pluck('study_id');
                        $the_arr = Proposal::distinct('scheme_id')->whereIn('draft_id',$rep_writ)->pluck('dept_id');
                        $depts = Department::select('dept_id','dept_name')->whereIn('dept_id',$the_arr)->get();
                        foreach($depts as $dk => $dv) {
                            $ar[] = array('dept_id'=>$dv->dept_id, 'dept_name'=>$dv->dept_name,'modal_type'=>'report_writing', 'marginTop'=>'0');
                            $arr_count[] = Proposal::where('dept_id',$dv->dept_id)->whereIn('draft_id',$rep_writ)->count('*');
                        }
                        $dept_or_scheme_list[$main_activity_names[$s]] = $ar;
                        $dept_or_scheme_count[$main_activity_names[$s]] = $arr_count;
                        $total_scheme_count[$s] = array_sum($arr_count);
                    }
                    $s++;
                }
            } else if($mv == '3') {
                $log_status_ids = array('10','11','12','13');
                $analysis_qry = Eval_activity_status::whereBetween('created_at',$date_between)->whereIn('status_id',$log_status_ids)->where('approval','1')->count('*');
                
                if($analysis_qry > 0) {
                    $main_activity_names[] = Eval_activity_log::where('group_id',$mv)->value('activity_dashboard_name');
                    $dept_or_scheme_list[$main_activity_names[$s]] = array();
                    $ar = array();
                    $arr_count = array();
                    $total_scheme_count[$s] = array_sum($arr_count);
                    if($analysis_qry > 0) {
                        $rep_writ = Eval_activity_status::distinct('study_id')->whereBetween('created_at',$date_between)->whereIn('status_id',$log_status_ids)->where('approval','1')->pluck('study_id');
                        $the_arr = Proposal::distinct('scheme_id')->whereIn('draft_id',$rep_writ)->pluck('dept_id');
                        $depts = Department::select('dept_id','dept_name')->whereIn('dept_id',$the_arr)->get();
                        foreach($depts as $dk => $dv) {
                            $ar[] = array('dept_id'=>$dv->dept_id, 'dept_name'=>$dv->dept_name,'modal_type'=>'report_writing', 'marginTop'=>'0');
                            $arr_count[] = Proposal::where('dept_id',$dv->dept_id)->whereIn('draft_id',$rep_writ)->count('*');
                        }
                        $dept_or_scheme_list[$main_activity_names[$s]] = $ar;
                        $dept_or_scheme_count[$main_activity_names[$s]] = $arr_count;
                        $total_scheme_count[$s] = array_sum($arr_count);
                    }
                    $s++;
                }
            } else if($mv == '2') {
                $log_status_ids = array('7','8','9');
                $scheme_qry = Eval_activity_status::whereBetween('created_at',$date_between)->whereIn('status_id',$log_status_ids)->where('approval','1')->count('*');
                if($scheme_qry > 0) {
                    $main_activity_names[] = Eval_activity_log::where('group_id',$mv)->value('activity_dashboard_name');
                    $dept_or_scheme_list[$main_activity_names[$s]] = array();
                    $arr_count = array();
                    $total_scheme_count[$s] = array_sum($arr_count);
                    $ar = array();
                    if($scheme_qry > 0) {
                        $rep_writ = Eval_activity_status::distinct('study_id')->whereBetween('created_at',$date_between)->whereIn('status_id',$log_status_ids)->where('approval','1')->pluck('study_id');
                        $the_arr = Proposal::distinct('scheme_id')->whereIn('draft_id',$rep_writ)->pluck('dept_id');
                        $depts = Department::select('dept_id','dept_name')->whereIn('dept_id',$the_arr)->get();
                        foreach($depts as $dk => $dv) {
                            $ar[] = array('dept_id'=>$dv->dept_id, 'dept_name'=>$dv->dept_name,'modal_type'=>'report_writing', 'marginTop'=>'0');
                            $arr_count[] = Proposal::where('dept_id',$dv->dept_id)->whereIn('draft_id',$rep_writ)->count('*');
                        }
                        $dept_or_scheme_list[$main_activity_names[$s]] = $ar;
                        $dept_or_scheme_count[$main_activity_names[$s]] = $arr_count;
                        $total_scheme_count[$s] = array_sum($arr_count);
                    }
                    $s++;
                }
            } else if($mv == '1') {
                $log_status_ids = array('1','2','3','4','5','6');
                $scheme_qry = Eval_activity_status::whereBetween('created_at',$date_between)->whereIn('status_id',$log_status_ids)->where('approval','1')->count('*');
                if($scheme_qry > 0) {
                    $main_activity_names[] = Eval_activity_log::where('group_id',$mv)->value('activity_dashboard_name');
                    $dept_or_scheme_list[$main_activity_names[$s]] = array();
                    $arr_count = array();
                    $total_scheme_count[$s] = array_sum($arr_count);
                    $ar = array();
                    if($scheme_qry > 0) {
                        $rep_writ = Eval_activity_status::distinct('study_id')->whereBetween('created_at',$date_between)->whereIn('status_id',$log_status_ids)->where('approval','1')->pluck('study_id');
                        $the_arr = Proposal::distinct('scheme_id')->whereIn('draft_id',$rep_writ)->pluck('dept_id');
                        $depts = Department::select('dept_id','dept_name')->whereIn('dept_id',$the_arr)->get();
                        foreach($depts as $dk => $dv) {
                            $ar[] = array('dept_id'=>$dv->dept_id, 'dept_name'=>$dv->dept_name,'modal_type'=>'report_writing', 'marginTop'=>'0');
                            $arr_count[] = Proposal::where('dept_id',$dv->dept_id)->whereIn('draft_id',$rep_writ)->count('*');
                        }
                        $dept_or_scheme_list[$main_activity_names[$s]] = $ar;
                        $dept_or_scheme_count[$main_activity_names[$s]] = $arr_count;
                        $total_scheme_count[$s] = array_sum($arr_count);
                    }
                    $s++;
                }
            }
        }

        $total_dept_or_schemes_slides = count($dept_or_scheme_list) / 3;
        $dept_or_schemes_slides = ceil($total_dept_or_schemes_slides);
        $main_activity_names_count = count($main_activity_names);

        $the_month = date('m');
        if($the_month < 4) {
            $show_date = '01-Apr-'.$last_year;
        } else {
            $show_date = '01-Apr-'.$get_year;
        }

        $the_arr = array('show_date'=>$show_date,'main_activity_names'=>$main_activity_names,'dept_or_scheme_list'=>$dept_or_scheme_list,'dept_or_schemes_slides'=>$dept_or_schemes_slides,'dept_or_scheme_count'=>$dept_or_scheme_count,'total_scheme_count'=>$total_scheme_count,'main_activity_names_count'=>$main_activity_names_count,'number_of_proposals'=>$number_of_proposals,'received_proposals_pending_for_decision'=>$received_proposals_pending_for_decision,'returned_proposals_pending_for_decision'=>$returned_proposals_pending_for_decision);

        return response()->json($the_arr);
    }

    public function eval_deptdashboardgadstatus(Request $request) {
        $dept_id = $request->get('dept_id');
        $thisid = $request->get('the_id');
        $expthisid = explode('&mav_',$thisid);
        $dept_name = str_replace('_',' ',$expthisid[1]);
        $the_year = $request->get('the_year');
        $split_finyear = explode('-',$the_year);
       
        $the_year_last = $split_finyear[0];
        $the_last_year = date('Y-m-d H:i:s',strtotime($the_year_last.'-04-01 00:00:00'));
        $the_cur_year = date('Y-m-d H:i:s');
        $date_between = array($the_last_year,$the_cur_year);
       
        if($dept_id > 0) {
           
            $the_dept_id = $dept_id;
            $group_id_by_name = Eval_activity_log::where('activity_dashboard_name',$dept_name)->value('group_id');
            $get_activity_ids = Eval_activity_log::where('group_id',$group_id_by_name)->pluck('id');
            $get_activity_status_ids = Eval_activity_status::distinct('study_id')->whereIn('status_id',$get_activity_ids)->pluck('study_id');
            $activity_ids = $get_activity_ids->toArray();

           
            if(in_array('28',$activity_ids)) {
                $pub_qry = Aftermeeting::select('draft_id','subject','chairperson','date','time')->whereBetween('created_at',$date_between)->where('dept_id',$the_dept_id)->orderBy('amid','desc')->get();
                $data = array();
                foreach($pub_qry as $ek => $ev) {
                    $sch_name = Proposal::where('draft_id',$ev->draft_id)->value('scheme_name');
                    $dateis = date('d M, Y',strtotime($ev->date));
                    $data[] = array('scheme_name'=>$sch_name, 'subject'=>$ev->subject, 'chairperson'=>$ev->chairperson, 'date'=>$dateis, 'time'=>$ev->time);
                }
                return response()->json($data);
            } else if($this->in_array_any(['26','27'],$activity_ids)) {
                $ecc_badge_count = 0;
                $ecc_qry = Meetinglog::select('draft_id','subject','chairperson','date','time')->whereBetween('created_at',$date_between)->where('dept_id',$the_dept_id)->get();
                $data = array();
                foreach($ecc_qry as $ek => $ev) {
                    $sch = Proposal::where('draft_id',$ev->draft_id)->value('scheme_name');
                    $dateis = date('d M, Y',strtotime($ev->date));
                    $data[] = array('scheme_name'=>$sch, 'subject'=>$ev->subject, 'chairperson'=>$ev->chairperson, 'date'=>$dateis, 'time'=>$ev->time);
                }
                return response()->json($data);
            } else if($this->in_array_any(['24','25'],$activity_ids)) {
                $dec_qry = Dept_meetings::select('mid','subject','chairperson','date','time','venue','scheme_id')->whereBetween('created_at',$date_between)->where('dept_id',$the_dept_id)->get();
                $data = array();
                foreach($dec_qry as $dk => $dv) {
                    $decscheme = Scheme::where('scheme_id',$dv->scheme_id)->value('scheme_name');
                    $dateis = date('d M, Y',strtotime($dv->date));
                    $data[] = array('scheme_name'=>$decscheme, 'subject'=>$dv->subject, 'chairperson'=>$dv->chairperson, 'date'=>$dateis, 'time'=>$dv->time);
                }
                return response()->json($data);
            } else if($this->in_array_any(['1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','18','19','20','21','22'],$activity_ids)) {
                $proposal = Proposal::select('scheme_name','entry_date','nodal_id')->whereIn('draft_id',$get_activity_status_ids)->whereBetween('created_at',$date_between)->where('dept_id',$the_dept_id)->get();
                $data = array();
                foreach($proposal as $dk => $dv) {
                    $scheme_name = $dv->scheme_name;
                    $entry_date = date('d M, Y',strtotime($dv->entry_date));
                    $nodal_name = Nodal::where('nodalid',$dv->nodal_id)->value('nodal_name');
                    $data[] = array('scheme_name'=>$scheme_name,'entry_date'=>$entry_date,'nodal_name'=>$nodal_name);
                }
                return response()->json($data);
            }
        }
    }

    public function eval_publishedstudies(Request $request) {
        $name = $request->get('name');
        $finyear = $request->get('the_year');
        $split_finyear = explode('-',$finyear);
        $the_year_last = $split_finyear[0];
        $the_last_year = date('Y-m-d H:i:s',strtotime($the_year_last.'-04-01 00:00:00'));
        $the_cur_year = date('Y-m-d H:i:s');
        $date_between = array($the_last_year,$the_cur_year);

        $group_id_by_name = Eval_activity_log::where('activity_dashboard_name',$name)->value('group_id');
        $get_activity_ids = Eval_activity_log::where('group_id',$group_id_by_name)->pluck('id');
        $activity_ids = $get_activity_ids->toArray();
       
        if(in_array('28',$activity_ids)) {
            $studies = Aftermeeting::whereBetween('created_at',$date_between)->orderBy('amid','asc')->get();
            $study = array();
            foreach($studies as $k => $v) {
                $dd_id = Teams::where('study_id',$v->draft_id)->orderBy('team_id','desc')->value('dd_leader');
                $dd_name = User::where('id',$dd_id)->value('name');
                if($dd_id == '') {
                    $dd_name = 'No Team';
                }
                $scheme_name = Proposal::where('draft_id',$v->draft_id)->value('scheme_name');
                $dept_name = Department::where('dept_id',$v->dept_id)->value('dept_name');
                $createdat = date('d M, Y',strtotime($v->created_at));
                $study[] = array('DD'=>$dd_name,'scheme_name'=>$scheme_name,'HOD'=>'','dept_name'=>$dept_name,'date'=>$createdat,'remarks'=>$v->remarks);
            }
            return response()->json($study);
        } elseif($this->in_array_any(['26','27'],$activity_ids)) {
            $studies = Meetinglog::whereBetween('created_at',$date_between)->orderBy('mid','asc')->get();
            $study = array();
            foreach($studies as $k => $v) {
                $dd_id = Teams::where('study_id',$v->draft_id)->orderBy('team_id','desc')->value('dd_leader');
                $dd_name = User::where('id',$dd_id)->value('name');
                if($dd_id == '') {
                    $dd_name = 'No Team';
                }
                $scheme_name = Proposal::where('draft_id',$v->draft_id)->value('scheme_name');
                $dept_name = Department::where('dept_id',$v->dept_id)->value('dept_name');
                $createdat = date('d M, Y',strtotime($v->created_at));
                $study[] = array('DD'=>$dd_name,'scheme_name'=>$scheme_name,'HOD'=>'','dept_name'=>$dept_name,'date'=>$createdat,'remarks'=>$v->remarks);
            }
            return response()->json($study);
        } elseif($this->in_array_any(['24','25'],$activity_ids)) {
            $studies = Dept_meetings::whereBetween('created_at',$date_between)->orderBy('mid','asc')->get();
            $study = array();
            foreach($studies as $k => $v) {
                $dd_id = Teams::where('study_id',$v->draft_id)->orderBy('team_id','desc')->value('dd_leader');
                $dd_name = User::where('id',$dd_id)->value('name');
                if($dd_id == '') {
                    $dd_name = 'No Team';
                }
                $scheme_name = Proposal::where('draft_id',$v->draft_id)->value('scheme_name');
                $dept_name = Department::where('dept_id',$v->dept_id)->value('dept_name');
                $createdat = date('d M, Y',strtotime($v->created_at));
                $study[] = array('DD'=>$dd_name,'scheme_name'=>$scheme_name,'HOD'=>'','dept_name'=>$dept_name,'date'=>$createdat,'remarks'=>$v->remarks);
            }
            return response()->json($study);
        } elseif(in_array('22',$activity_ids)) {
            $ids = array('22');
            $study_ids = Eval_activity_status::distinct('study_id')->whereBetween('created_at',$date_between)->whereIn('status_id',$ids)->where('approval','1')->pluck('study_id');
            $get_dept_ids = Proposal::distinct('scheme_id')->whereIn('draft_id',$study_ids)->pluck('dept_id');
            $studies = Proposal::whereIn('draft_id',$study_ids)->whereIn('dept_id',$get_dept_ids)->get();
            $study = array();
            foreach($studies as $k => $v) {
                $dd_id = Teams::where('study_id',$v->draft_id)->orderBy('team_id','desc')->value('dd_leader');
                $dd_name = User::where('id',$dd_id)->value('name');
                if($dd_id == '') {
                    $dd_name = 'No Team';
                }
                $scheme_name = Proposal::where('draft_id',$v->draft_id)->value('scheme_name');
                $dept_name = Department::where('dept_id',$v->dept_id)->value('dept_name');
                $createdat = date('d M, Y',strtotime($v->created_at));
                $study[] = array('DD'=>$dd_name,'scheme_name'=>$scheme_name,'HOD'=>'','dept_name'=>$dept_name,'date'=>$createdat,'remarks'=>$v->remarks);
            }
            return response()->json($study);
        } elseif($this->in_array_any(['19','20','21'],$activity_ids)) {
            $ids = array('19','20','21');
            $study_ids = Eval_activity_status::distinct('study_id')->whereIn('status_id',$ids)->where('approval','1')->pluck('study_id');
            $get_dept_ids = Proposal::distinct('scheme_id')->whereIn('draft_id',$study_ids)->pluck('dept_id');
            $studies = Proposal::whereIn('draft_id',$study_ids)->whereIn('dept_id',$get_dept_ids)->get();
            $study = array();
            foreach($studies as $k => $v) {
                $dd_id = Teams::where('study_id',$v->draft_id)->orderBy('team_id','desc')->value('dd_leader');
                $dd_name = User::where('id',$dd_id)->value('name');
                if($dd_id == '') {
                    $dd_name = 'No Team';
                }
                $scheme_name = Proposal::where('draft_id',$v->draft_id)->value('scheme_name');
                $dept_name = Department::where('dept_id',$v->dept_id)->value('dept_name');
                $createdat = date('d M, Y',strtotime($v->created_at));
                $study[] = array('DD'=>$dd_name,'scheme_name'=>$scheme_name,'HOD'=>'','dept_name'=>$dept_name,'date'=>$createdat,'remarks'=>$v->remarks);
            }
            return response()->json($study);
        } elseif(in_array('18',$activity_ids)) {
            $ids = array('18');
            $study_ids = Eval_activity_status::distinct('study_id')->whereBetween('created_at',$date_between)->whereIn('status_id',$ids)->where('approval','1')->pluck('study_id');
            $get_dept_ids = Proposal::distinct('scheme_id')->whereIn('draft_id',$study_ids)->pluck('dept_id');
            $studies = Proposal::whereIn('draft_id',$study_ids)->whereIn('dept_id',$get_dept_ids)->get();
            $study = array();
            foreach($studies as $k => $v) {
                $dd_id = Teams::where('study_id',$v->draft_id)->orderBy('team_id','desc')->value('dd_leader');
                $dd_name = User::where('id',$dd_id)->value('name');
                if($dd_id == '') {
                    $dd_name = 'No Team';
                }
                $scheme_name = Proposal::where('draft_id',$v->draft_id)->value('scheme_name');
                $dept_name = Department::where('dept_id',$v->dept_id)->value('dept_name');
                $createdat = date('d M, Y',strtotime($v->created_at));
                $study[] = array('DD'=>$dd_name,'scheme_name'=>$scheme_name,'HOD'=>'','dept_name'=>$dept_name,'date'=>$createdat,'remarks'=>$v->remarks);
            }
            return response()->json($study);
        } elseif($this->in_array_any(['14','15','16'],$activity_ids)) {
            $ids = Eval_activity_log::where('activity_dashboard_name',$name)->pluck('id');
            $study_ids = Eval_activity_status::distinct('study_id')->whereBetween('created_at',$date_between)->whereIn('status_id',$ids)->where('approval','1')->pluck('study_id');
            $get_dept_ids = Proposal::distinct('scheme_id')->whereIn('draft_id',$study_ids)->pluck('dept_id');
            $studies = Proposal::whereIn('draft_id',$study_ids)->whereIn('dept_id',$get_dept_ids)->get();
            $study = array();
            foreach($studies as $k => $v) {
                $dd_id = Teams::where('study_id',$v->draft_id)->orderBy('team_id','desc')->value('dd_leader');
                $dd_name = User::where('id',$dd_id)->value('name');
                if($dd_id == '') {
                    $dd_name = 'No Team';
                }
                $scheme_name = Proposal::where('draft_id',$v->draft_id)->value('scheme_name');
                $dept_name = Department::where('dept_id',$v->dept_id)->value('dept_name');
                $createdat = date('d M, Y',strtotime($v->created_at));
                $study[] = array('DD'=>$dd_name,'scheme_name'=>$scheme_name,'HOD'=>'','dept_name'=>$dept_name,'date'=>$createdat,'remarks'=>$v->remarks);
            }
            return response()->json($study);
        } elseif($this->in_array_any(['10','11','12','13'],$activity_ids)) {
            $ids = Eval_activity_log::where('activity_dashboard_name',$name)->pluck('id');
            $study_ids = Eval_activity_status::distinct('study_id')->whereBetween('created_at',$date_between)->whereIn('status_id',$ids)->where('approval','1')->pluck('study_id');
            $get_dept_ids = Proposal::distinct('scheme_id')->whereIn('draft_id',$study_ids)->pluck('dept_id');
            $studies = Proposal::whereIn('draft_id',$study_ids)->whereIn('dept_id',$get_dept_ids)->get();
            $study = array();
            foreach($studies as $k => $v) {
                $dd_id = Teams::where('study_id',$v->draft_id)->orderBy('team_id','desc')->value('dd_leader');
                $dd_name = User::where('id',$dd_id)->value('name');
                if($dd_id == '') {
                    $dd_name = 'No Team';
                }
                $scheme_name = Proposal::where('draft_id',$v->draft_id)->value('scheme_name');
                $dept_name = Department::where('dept_id',$v->dept_id)->value('dept_name');
                $createdat = date('d M, Y',strtotime($v->created_at));
                $study[] = array('DD'=>$dd_name,'scheme_name'=>$scheme_name,'HOD'=>'','dept_name'=>$dept_name,'date'=>$createdat,'remarks'=>$v->remarks);
            }
            return response()->json($study);
        } elseif($this->in_array_any(['7','8','9'],$activity_ids)) {
            $ids = Eval_activity_log::where('activity_dashboard_name',$name)->pluck('id');
            $study_ids = Eval_activity_status::distinct('study_id')->whereBetween('created_at',$date_between)->whereIn('status_id',$ids)->where('approval','1')->pluck('study_id');
            $the_arr = Proposal::distinct('scheme_id')->whereIn('draft_id',$study_ids)->pluck('dept_id');
            $studies = Proposal::whereIn('draft_id',$study_ids)->whereIn('dept_id',$the_arr)->get();
            $study = array();
            foreach($studies as $k => $v) {
                $dd_id = Teams::where('study_id',$v->draft_id)->orderBy('team_id','desc')->value('dd_leader');
                $dd_name = User::where('id',$dd_id)->value('name');
                if($dd_id == '') {
                    $dd_name = 'No Team';
                }
                $scheme_name = Proposal::where('draft_id',$v->draft_id)->value('scheme_name');
                $dept_name = Department::where('dept_id',$v->dept_id)->value('dept_name');
                $createdat = date('d M, Y',strtotime($v->created_at));
                $study[] = array('DD'=>$dd_name,'scheme_name'=>$scheme_name,'HOD'=>'','dept_name'=>$dept_name,'date'=>$createdat,'remarks'=>$v->remarks);
            }
            return response()->json($study);
        } elseif($this->in_array_any(['1','2','3','4','5','6'],$activity_ids)) {
            $sample_ids = array(1,2,3,4,5,6);
            $study_ids = Eval_activity_status::distinct('study_id')->whereBetween('created_at',$date_between)->whereIn('status_id',$sample_ids)->where('approval','1')->pluck('study_id');
            $the_arr = Proposal::distinct('scheme_id')->whereIn('draft_id',$study_ids)->pluck('dept_id');
          
            $studies = Proposal::whereIn('draft_id',$study_ids)->whereIn('dept_id',$the_arr)->get();

            $study = array();
            foreach($studies as $k => $v) {
                $dd_id = Teams::where('study_id',$v->draft_id)->orderBy('team_id','desc')->value('dd_leader');
                $dd_name = User::where('id',$dd_id)->value('name');
                if($dd_id == '') {
                    $dd_name = 'No Team';
                }
                $scheme_name = Proposal::where('draft_id',$v->draft_id)->value('scheme_name');
                $dept_name = Department::where('dept_id',$v->dept_id)->value('dept_name');
                $createdat = date('d M, Y',strtotime($v->created_at));
                $study[] = array('DD'=>$dd_name,'scheme_name'=>$scheme_name,'HOD'=>'','dept_name'=>$dept_name,'date'=>$createdat,'remarks'=>$v->remarks);
            }
            return response()->json($study);
        }
    }

    public function profile() {
        return view('dashboards.gad-sec.profile');
    }

    public function settings(){
        return view('dashboards.gad-sec.settings');
    }

    public function schemes() {
        $scheme_received_ids = SchemeSend::distinct('draft_id')->whereIn('status_id',['23','25'])->pluck('draft_id');
        $scheme_received_ids = $scheme_received_ids->toArray();
        $scheme_not_received_ids = SchemeSend::distinct('draft_id')->whereIn('status_id',['24','26'])->pluck('draft_id');
        $scheme_not_received_ids = $scheme_not_received_ids->toArray();
        $arr = array_diff($scheme_received_ids, $scheme_not_received_ids);
      
        $scheme_ids = Proposal::whereIn('draft_id',$arr)->pluck('scheme_id');
        $schemes = Scheme::whereIn('scheme_id',$scheme_ids)->get();
        return view('dashboards.gad-sec.schemes',compact('schemes'));
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
        return view('dashboards.gad-sec.schemedetail',compact('schemes','dept_name','major_objectives','major_indicators','imdept','major_indicator_hod','financial_progress','replace_url','scheme_id','beneficiariesGeoLocal','bencovfile','trainingfile','iecfile','district_names','dept_names','taluka_names','conv_dept_remarks','goals','entered_goals','eval_report','gr_files','notification_files','brochure_files','pamphlets_files','otherdetailscenterstate_files','the_convergence'));
    }

  
    public function proposallist($param) {

        $dept_id = Auth::user()->dept_id;
        $user_id = auth()->user()->role;
        $user_login = DB::table('users')
                        ->leftjoin('public.user_user_role_deptid','user_id','=','users.id')
                        ->where('users.id','=',$user_id)
                        ->select('users.name','users.id','users.role')
                        ->get();
        $user_name = auth()->user()->name;
        //New Proposal
        if($param == "new"){
           
            $status_id = '23';
            $new_proposals = SchemeSend::leftjoin('imaster.departments','scheme_send.dept_id','=','departments.dept_id')->select('scheme_send.*','proposals.draft_id as prop_draft_id','proposals.dept_id as prop_dept_id','proposals.scheme_name','proposals.scheme_overview','proposals.scheme_objective','proposals.sub_scheme','proposals.status_id as prop_status_id','departments.dept_name')
                                        ->leftjoin('itransaction.proposals','scheme_send.draft_id','=','proposals.draft_id')
                                        ->where('scheme_send.status_id',$status_id)
                                        ->where('scheme_send.forward_btn_show','1')
                                        ->orderBy('scheme_send.status_id')
                                        ->orderBy('scheme_send.id','desc')
                                        ->get();
            return view('dashboards.gad-sec.proposal.new_proposal',compact('new_proposals'));

        }elseif ($param == "forward") {
           //Ongoing Or forwad
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
                return view('dashboards.gad-sec.proposal.forward_proposal',compact('proposals_forwarded'));


        }elseif ($param == "on_going") {
            //Ongoing 
            $ongoing_proposal = Stage::with(['schemeSend' => function ($q) {
                                $q->whereNotNull('team_member_dd');
                            }])->whereNull('document')->get();       

            return view('dashboards.gad-sec.proposal.ongoing_proposal',compact('ongoing_proposal'));


        }elseif ($param == "return") {
                //Return
                $returned_proposals = SchemeSend::leftjoin('imaster.departments','scheme_send.dept_id','=','departments.dept_id')->select('scheme_send.*','proposals.draft_id as prop_draft_id','proposals.dept_id as prop_dept_id','proposals.scheme_name','proposals.scheme_overview','proposals.scheme_objective','proposals.sub_scheme','proposals.status_id as prop_status_id','departments.dept_name')
                                                ->leftjoin('itransaction.proposals','scheme_send.draft_id','=','proposals.draft_id')
                                                ->whereIn('scheme_send.status_id',['24','26','27'])
                                                ->where('scheme_send.forward_btn_show','1')
                                                ->orderBy('scheme_send.status_id')
                                                ->orderBy('scheme_send.id','desc')
                                                ->get();
                $ret_pro = array();
                foreach($returned_proposals as $re) {

                    $ret_pro[] = $re->draft_id;
                }
            return view('dashboards.gad-sec.proposal.return_proposal',compact('returned_proposals'));

        }elseif ($param == "approved") {
            $approved_proposals = SchemeSend::leftjoin('imaster.departments','scheme_send.dept_id','=','departments.dept_id')->select('scheme_send.*','proposals.draft_id as prop_draft_id','proposals.dept_id as prop_dept_id','proposals.scheme_name','proposals.scheme_overview','proposals.scheme_objective','proposals.sub_scheme','proposals.status_id as prop_status_id','departments.dept_name')
                                            ->leftjoin('itransaction.proposals','scheme_send.draft_id','=','proposals.draft_id')
                                            ->where('scheme_send.status_id','25')
                                            ->orderBy('scheme_send.status_id')
                                            ->orderBy('scheme_send.id','desc')
                                            ->get();
                      

                    $app_prop = array();
                    foreach($approved_proposals as $ap) {
                            $app_prop[] = $ap->draft_id;
                    }
            return view('dashboards.gad-sec.proposal.approval',compact('approved_proposals'));

        }elseif ($param == "completed") {
            //Completed 
            $complted_proposal = Stage::WhereNotNull('document')->get();

           return view('dashboards.gad-sec.proposal.completed_proposal',compact('complted_proposal'));

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
        return view('dashboards.gad-sec.proposaldetail',compact('proposal_list','dept_name','major_objectives','major_indicators','imdept','major_indicator_hod','financial_progress','replace_url','scheme_id','beneficiariesGeoLocal','bencovfile','trainingfile','iecfile','district_names','dept_names','taluka_names','conv_dept_remarks','goals','entered_goals','eval_report','gr_files','notification_files','brochure_files','pamphlets_files','otherdetailscenterstate_files','the_convergence'));
    }


    public function frwdtoeval(Request $request) {
        
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
            $send_id = Crypt::decrypt($request->send_id);
            $data['created_at'] = date('Y-m-d h:i:s');
            $d_id = Crypt::decrypt($request->draft_id);
            $dept_id = Proposal::where('draft_id',$d_id)->value('dept_id');
           
            $data['status_id'] = '25';
            $data['user_id'] = $user_id;
            $data['created_by'] = Auth::user()->name;
            $data['dept_id'] = $dept_id;
            $data['remarks'] = $request->remarks;
            $data['evaluation_sent_date'] = date('Y-m-d h:i:s');
            $data['forward_btn_show'] = 1;
            $data['forward_id'] = 1;
            $approved_by = '1';
            $s_id = '25';
            $update = SchemeSend::where('draft_id',$d_id)->latest()->first();
 
            if($request->input('send_id') != null) {
                unset($data['_token']);
                unset($request->send_id);
                $created_at = date('Y-m-d H:i:s');
                $arr = array('status_id' => $s_id,'user_id'=>Auth::user()->id,'study_id'=>$d_id,'created_at'=>$created_at,'approved_by'=>1,'approval'=>1);
                Eval_activity_status::insert($arr);
                SchemeSend::where('draft_id',$d_id)->update(['forward_btn_show'=>1]);
            
            } else {
                return redirect()->back()->withError('Sending Proposal is failed');
            }
            if($update){
                $update->update($data);
            }else{
                SchemeSend::insert($data);
            }
            return redirect()->back()->withSuccess('Proposal sent successfully to Evalution Diector');

       }
    }

    public function returntodept(Request $request) {
      
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
            $send_id = Crypt::decrypt($request->send_id);
            $data['created_at'] = date('Y-m-d H:i:s');
            $d_id = Crypt::decrypt($request->draft_id);
            $dept_id = Proposal::where('draft_id', $d_id)->value('dept_id');
            $data['status_id'] = '26';
            $data['user_id'] = Auth::user()->id;
            $data['created_by'] = Auth::user()->name;
            $data['dept_id'] = $dept_id;
            $data['forward_id'] = 0;
            $data['forward_btn_show'] = 1;
            $data['remarks'] = $request->remarks;
            $check =  SchemeSend::where('draft_id',$d_id)->latest()->first();
            
            $s_id = '26';
            unset($data['_token']);
            unset($request->send_id);
            $proposal = DB::table('itransaction.proposals')->where('draft_id','=',$d_id)->update(['status_id'=> $s_id]);
            SchemeSend::where('draft_id',$d_id)->update($data);
            return redirect()->back()->with('forward_to_gad_success','Proposal sent successfully to GAD');
        }
    }
    public function forwardtoeval(Request $request){
       
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
            $send_id = Crypt::decrypt($request->send_id);
            $data['return_eval_date'] = date('Y-m-d H:i:s');
            $d_id = Crypt::decrypt($request->draft_id);
            $dept_id = Proposal::where('draft_id',$d_id)->value('dept_id');
            $data['status_id'] = '25';
            $data['user_id'] = Auth::user()->id;
            $data['created_by'] = Auth::user()->name;
            $data['dept_id'] = $dept_id;
            $data['forward_id'] = 1;
            $data['forward_btn_show'] = 1;
            $data['evaluation_sent_date'] = date('Y-m-d H:i:s');
            $data['remarks'] = $request->remarks;
            $s_id = '28';
            unset($data['_token']);
            unset($request->send_id);
            $proposal = DB::table('itransaction.proposals')->where('draft_id','=',$d_id)->update(['status_id'=> $s_id]);
            $ins = SchemeSend::where('id',$send_id)->update($data);
        
            return redirect()->back()->with('forward_to_gad_success','Proposal sent successfully to Eval');
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

   

    public function meetings() {
        $schemes = SchemeSend::select('proposals.draft_id','proposals.scheme_name','proposals.scheme_id')->leftjoin('itransaction.proposals','scheme_send.draft_id','=','proposals.draft_id')->where('scheme_send.status_id','23')->orderBy('scheme_send.id','desc')->get();
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
        return view('dashboards.gad-sec.meetings',compact('meetings','attendees','schemelist','departments','implementations','aftermeeting','replace_url'));
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
            $attendees = json_encode(array('users'=>$request->input('attendees')));
      

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
                $path['name'] = $doc_id.'_setmeeting'.'.'.$path['extension'];
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

    public function currentmeetingevents(Request $request) {
        $date = date('Y-m-01');
        $lastdatedays = date("t",strtotime($date));
        $month = date('m');
        $lastday = date("Y-$month-$lastdatedays");
        $between = array($date,$lastday);
        $meetings = Meetinglog::select('subject','date','time')->whereBetween('date',$between)->get();
        $meetingsare = array();
        foreach($meetings as $m => $k) {
            $datetime = $k->date.' '.$k->time;
            $meetingsare[] = array('subject'=>$k->subject,'datetime'=>strtotime($datetime));
        }
        return response()->json($meetingsare);
    }

    public function nextmeetingevents(Request $request) {
        $month = date('m') + 1;
        if($month < 10) {
            $month = '0'.$month;
        }
        $date = date("Y-$month-01");
        $last_date = date("Y-m-t",strtotime($date));
        $between = array($date,$last_date);
       
        $meetings = Meetinglog::select('subject','date','time')->whereBetween('date',$between)->get();
        $meetingsare = array();
        foreach($meetings as $m => $k) {
            $datetime = $k->date.' '.$k->time;
            $meetingsare[] = array('subject'=>$k->subject,'datetime'=>strtotime($datetime));
        }
        return response()->json($meetingsare);
    }

    public function getdepartments(Request $request) {
        $dept_id = $request->get('dept_id');
        $first_fin_year = $request->get('first_fin_year');
        $second_fin_year = $request->get('second_fin_year');
        $data = array('dept_id'=>$dept_id,'first_fin_year'=>$first_fin_year,'second_fin_year'=>$second_fin_year);
      
    }


    public function addremarks(Request $request) {
        $validate = Validator::make($request->all(),[
            'scheme_id' => 'required|numeric',
            'remarks' => 'required|string'
        ]);
        if($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate->errors());
        } else {
            $scheme_id = $request->input('scheme_id');
            $remarks = $request->input('remarks');
            Scheme::where('scheme_id',$scheme_id)->update(['gadremarks'=>$remarks]);
            return redirect()->back();
        }
    }

    public function current_calendar(Request $request) {
        $date = $request->get('date');
        $curdate = Date('Y-m-t');
        $date_between = array($date,$curdate);
        $meetings = Meetinglog::select('date','time','subject')->whereBetween('date',$date_between)->orderBy('date','asc')->get();
        $allmeetings = array();
        foreach($meetings as $m) {
            $datetime = date('d F',strtotime($m->date));
            $allmeetings[] = array('startDate'=>$m->time,'endDate'=>$datetime,'summary'=>$m->subject);
        }
        return response()->json($allmeetings);
    }

    public function next_calendar(Request $request) {
        $date = $request->get('date');
        $curdate = Date('Y-m-t',strtotime($date));
        $date_between = array($date,$curdate);
        $meetings = Meetinglog::select('date','time','subject')->whereBetween('date',$date_between)->orderBy('date','asc')->get();
        $allmeetings = array();
        foreach($meetings as $m) {
            $datetime = date('d F',strtotime($m->date));
            $allmeetings[] = array('startDate'=>$m->time,'endDate'=>$datetime,'summary'=>$m->subject);
        }
        return response()->json($allmeetings);
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

    public function communication() {
        $get_study_ids = Eval_activity_status::distinct('study_id')->pluck('study_id');
        $studies = Proposal::select('draft_id','scheme_name')->whereIn('draft_id',$get_study_ids)->orderBy('scheme_name')->get();
        $topics = CommunicationTopics::orderBy('id','desc')->get();
        $communication = Communication::select('communication.*','proposals.scheme_name','communication_topics.topic as topic_name', 'departments.dept_name')->leftjoin('itransaction.proposals','proposals.draft_id','=','communication.study_id')->leftjoin('imaster.communication_topics','communication.topic_id','=','communication_topics.id')->orderBy('communication.id','desc')->leftjoin('imaster.departments','communication.dept_id','=','departments.dept_id')->get();
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
        return view('dashboards.gad-sec.communication',compact('topics','studies','communication_arr'));
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

    public function changeviewed(Request $request) {
        $user_id = Auth::user()->id;
        $user_role = Auth::User()->role;
        $arr = array('viewed'=>'1');
        MeetingsViewedBy::where('user_id',$user_id)->where('user_role',$user_role)->update($arr);
        return response()->json('done');
    }

    public function reportmodule() {
        $status_study_ids = Eval_activity_status::distinct('study_id')->where('approval','1')->pluck('study_id');
        $team_study_ids = Teams::whereIn('study_id',$status_study_ids)->orderBy('team_id','desc')->pluck('study_id');
        $studies = Proposal::select('draft_id','scheme_name')->whereIn('draft_id',$team_study_ids)->orderBy('scheme_name')->get();
        return view('dashboards.gad-sec.report_module',compact('studies'));
    }
    public function getreportmodule(Request $request) {
        $id = $request->get('id');
        $study = Eval_activity_status::select('eval_activity_status.id as act_id', 'eval_activity_status.status_id as act_status_id', 'eval_activity_status.user_id as act_user_id', 'eval_activity_status.study_id', 'eval_activity_status.created_at as act_date', 'proposals.*')->join('itransaction.proposals','eval_activity_status.study_id','=','proposals.draft_id')->where('proposals.draft_id',$id)->where('eval_activity_status.current_status','1')->orderBy('id','desc')->take(1)->get();
        $gad_approval_date_value = Eval_activity_status::where('eval_activity_status.study_id',$id)->orderBy('id')->take(1)->value('created_at');
        $gad_approval_date = '';
        if($gad_approval_date_value != '') {
            $gad_approval_date = date('d F, Y',strtotime($gad_approval_date_value));
        }
        $project_team_date_val = Teams::where('study_id',$id)->orderBy('team_id','desc')->take(1)->value('created_at');
        $project_team_date = '';
        if($project_team_date_val) {
            $project_team_date = date('d F, Y',strtotime($project_team_date_val));
        }
        $publication_date_value = Eval_activity_status_dates::where('study_id',$id)->where('status_id','26')->value('created_at');
        $publication_date = '';
        if($publication_date_value != '') {
            $publication_date = date('d F, Y',strtotime($publication_date_value));
        }
      
        $ecc_date_value = Eval_activity_status_dates::where('study_id',$id)->where('status_id','25')->value('created_at');
        $ecc_date = '';
        if($ecc_date_value != '') {
            $ecc_date = date('d F, Y',strtotime($ecc_date_value));
        }
        $pilot_testing_date_value = Eval_activity_status::whereIn('status_id',[13,14,15])->where('study_id',$id)->orderBy('id','desc')->take(1)->value('created_at');
        $pilot_testing_date = '';
        if($pilot_testing_date_value != '') {
            $pilot_testing_date = date('d F, Y',strtotime($pilot_testing_date_value));
        }
        $proposal_date_value = Proposal::where('draft_id',$id)->value('created_at');
        $proposal_date = '';
        if($proposal_date_value != '') {
            $proposal_date = date('d F, Y',strtotime($proposal_date_value));
        }
        $proposal_returned_date_value = Eval_activity_status::where('status_id','24')->where('study_id',$id)->orderBy('id','desc')->take(1)->value('created_at');
        $proposal_returned_date = '';
        if($proposal_returned_date_value != '') {
            $proposal_returned_date = date('d F, Y',strtotime($proposal_returned_date_value));
        }


        $report_data = Communication::where('study_id',$id)->orderBy('id','desc')->get();
        $dateofapproval_in_ecc = Eval_activity_status::where('status_id','25')->orderBy('id','asc')->take(1)->value('created_at');
        $dateofapproval_ecc = '';
        if($dateofapproval_in_ecc) {
            $dateofapproval_ecc = date('d F, Y',strtotime($dateofapproval_in_ecc));
        }
        $dec_date_made = Aftermeeting::where('draft_id',$id)->orderBy('amid')->take(1)->value('created_at');
        $dec_date = '';
        if($dec_date_made) {
            $dec_date = date('d F, Y',strtotime($dec_date_made));
        }
        $ecc_date_found = Meetinglog::where('draft_id',$id)->orderBy('mid','desc')->take(1)->value('created_at');
        $ecc_meeting_date = '';
        if($ecc_date_found) {
            $ecc_meeting_date = date('d F, Y',strtotime($ecc_date_found));
        }

        $arr = array('study'=>$study,'publication_date'=>$publication_date,'dec_date'=>$dec_date,'gad_approval_date'=>$gad_approval_date,'project_team_date'=>$project_team_date,'pilot_testing_date'=>$pilot_testing_date,'proposal_date'=>$proposal_date,'proposal_returned_date'=>$proposal_returned_date,'ecc_date'=>$ecc_date,'report_data'=>$report_data,'dateofapproval_ecc'=>$dateofapproval_ecc,'ecc_meeting_date'=>$ecc_meeting_date);
        return response()->json($arr);
    }
    function updateInfo(Request $request){
           
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

    function updatePicture(Request $request){
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

    function changePassword(Request $request){
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
}


