<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Proposal;
use App\Models\Districts;
use App\Models\Stage;
use Illuminate\Support\Facades\Auth;
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
use App\Models\User;
use App\Models\Scheme;
use Carbon\Carbon;
use Mail;
use App\Models\Attachment;
use Notification;
use App\Mail\MeetingMail;
use App\Jobs\MeetingEMailJob;
use App\Models\Aftermeeting;
use App\Models\Status;
use App\Models\Status_log;
use App\Models\Branch;
use App\Models\SchemeSend;
use App\Models\User_user_role_deptid;
use App\Http\Controllers\Auth\LoginController;
use App\Models\Eval_activity_status;
use App\Models\Eval_activity_log;
use Illuminate\Support\Arr;
use App\Models\Activitylog;
use URL;
use Session;
use App\Models\Sdggoals;
use App\Models\Dept_meetings;
use App\Models\Taluka;
use App\Models\Eval_activity_status_dates;
use App\Models\ReportModule;
use App\Models\CommunicationTopics;
use App\Models\Communication;
use App\Models\MeetingsViewedBy;
use App\Models\BranchRole;
use Illuminate\Support\Facades\Crypt;
use App\Http\ViewComposers\draftComposer;


class EvaldirController extends Controller {

    public $envirment;
    public function __construct() {
        $this->middleware('auth');
        $this->replace_url = URL::to('/');
        $this->envirment = environmentCheck();
    }

    public function get_count($thing) {
        if($thing == 'proposals') {
            $dir_proposal_count = SchemeSend::where('status_id','25')->where('viewed','0')->orWhere('status_id','25')->count("*");
            Session::put('proposals',$dir_proposal_count);
            return $dir_proposal_count;
        }
    }

    public function count_all_proposals($param) {   
        $dept_id = Auth::user()->dept_id;
    
        if ($param == "new") {
            $status_id = '25';
            if(Auth::check() && Auth::user()->role_manage == 2 && Auth::user()->role == 24 ){ //JD ROLE
                return SchemeSend::whereNotNull('scheme_send.team_member_dd')
                                    ->where('scheme_send.approved', 2)
                                    // ->whereIn('scheme_send.status_id', [25])
                                    // ->where('scheme_send.forward_btn_show', 1)
                                    // ->where('scheme_send.forward_id', 1)
                                    // ->where('scheme_send.approved', 0)
                                    ->orderByDesc('scheme_send.id')
                                    ->distinct()
                                    ->count();
            }else{ // DIR
                return SchemeSend::whereNotNull('scheme_send.team_member_dd')
                                                    ->where('scheme_send.approved', 3)
                                                    // ->whereIn('scheme_send.status_id', [25])
                                                    // ->where('scheme_send.forward_btn_show', 1)
                                                    // ->where('scheme_send.forward_id', 1)
                                                    // ->where('scheme_send.approved', 0)
                                                    ->orderByDesc('scheme_send.id')
                                                    ->distinct()
                                                    ->count();
            }
            // return  SchemeSend::select('scheme_send.*','proposals.scheme_name','proposals.scheme_overview','proposals.scheme_objective','proposals.sub_scheme','departments.dept_name')
            //                             ->leftjoin('itransaction.proposals','scheme_send.draft_id','=','proposals.draft_id')
            //                             ->leftjoin('imaster.departments','scheme_send.dept_id','departments.dept_id')
            //                             ->whereNotNull('scheme_send.team_member_dd')
            //                             ->where('scheme_send.approved', 0)
            //                             ->orderByDesc('scheme_send.id')
            //                             ->distinct()->count();
            // return SchemeSend::select('scheme_send.*','proposals.scheme_name','proposals.scheme_overview','proposals.scheme_objective','proposals.sub_scheme','departments.dept_name')
            //                             ->leftjoin('itransaction.proposals','scheme_send.draft_id','=','proposals.draft_id')
            //                             ->leftjoin('imaster.departments','scheme_send.dept_id','departments.dept_id')
            //                             ->whereNull('scheme_send.team_member_dd')
            //                             ->where('scheme_send.status_id',$status_id)
            //                             ->orWhere('scheme_send.status_id','23')
            //                             ->where('scheme_send.forward_btn_show','1')->where('scheme_send.forward_id','1')
            //                             ->orderBy('scheme_send.status_id','desc')->orderBy('scheme_send.id','desc')
            //                             ->distinct()
            //                             ->count();
        }  elseif ($param == "on_going") {
            //Ongoing 
            return  Stage::WhereNull('document')->count();

        }elseif ($param == "return") {
            //Return Proposal
           return SchemeSend::select('scheme_send.*','proposals.scheme_name','proposals.scheme_overview','proposals.scheme_objective','proposals.sub_scheme','departments.dept_name')
                                            ->leftjoin('itransaction.proposals','scheme_send.draft_id','=','proposals.draft_id')
                                            ->leftjoin('imaster.departments','scheme_send.dept_id','departments.dept_id')
                                            ->whereIn('scheme_send.status_id',['24','26','27','28'])
                                        // ->where('scheme_send.forward_btn_show','1')->where('scheme_send.forward_id','1')
                                            ->orderBy('scheme_send.status_id','desc')->orderBy('scheme_send.id','desc')
                                            ->count();
     
        }elseif ($param == "completed") {
           //Completed 
            return Stage::WhereNotNull('document')->count();
        }
        // Default return 0 if $param doesn't match any condition
        return 0;
    }
    
    public function index(Request $request) {
        $new_count = $this->count_all_proposals("new");
        $ongoing_count = $this->count_all_proposals("on_going");
        $return_count = $this->count_all_proposals("return");
        $completed_count = $this->count_all_proposals("completed");
        $scheme_list = Proposal::where('status_id','23')->orderBy('scheme_name','ASC')->groupBy('draft_id')->pluck('scheme_name','draft_id');
        $draft_id = [];
        foreach ($scheme_list as $key => $value) {
            $draft_id[] = $key;
        }
     
       draftComposer::setDraftId($draft_id);
       $act['userid'] = Auth::user()->id;
       $act['ip'] = $request->ip();
       $act['activity'] = 'Director Dashboard.';
       $act['officecode'] = Auth::user()->dept_id;
       $act['pagereferred'] = $request->url();
       Activitylog::insert($act);
       return view('dashboards.eva-dir.index', compact('new_count', 'ongoing_count','return_count','completed_count','scheme_list','draft_id'));
    }
   
   
 
    // public function withfinyear(Request $request) {
    //     $get_year = date('Y');
    //     $last_year = $get_year-1;
    //     $main_menu = Eval_activity_log::groupBy('group_id_dashboard')->orderBy('group_id_dashboard','desc')->pluck('group_id_dashboard');
    //     $get_after_dept_name = array();
    //     $get_meet_dept_name = array();
    //     $published = 0;
    //     $ecc = 0;
    //     $dept_meetings = 0;
    //     $get_dept_meet_dept_name = array();
    //     $get_scheme_name = array();
    //     $understanding_schemes_count = 0;
    //     $get_scheme_name = array();
    //     $main_activity_names = array();
    //     $dept_or_scheme_list = array();
    //     $dept_or_scheme_count = array();
    //     $total_scheme_count = array();

    //     $the_first_year = $request->get('first_year');
    //     $the_second_year = $request->get('second_year');
    //     $first_year_is = explode('-',$the_first_year);
    //     $first_year = $first_year_is[0];
    //     $second_year_is = explode('-',$the_second_year);
    //     $second_year = $second_year_is[0];
    //     $last_fin_year = date('Y-m-d H:i:s',strtotime($first_year.'-04-01 00:00:00'));
    //     $current_fin_year = date('Y-m-d H:i:s',strtotime($second_year.'-03-31 11:59:59'));
    //     $the_month = date('m');
    //     $the_year = date('Y');
    //     if($the_month > 4 && $the_year <= $second_year) {
    //         $current_fin_year = date('Y-m-d H:i:s');
    //     }
    //     $date_between = array($last_fin_year,$current_fin_year);
    //     $team_current_study_ids = Teams::distinct('study_id')->whereBetween('created_at',$date_between)->pluck('study_id');

    //     $previous_year = $first_year - 1;
    //     $very_previous_date = date('Y-m-d H:i:s',strtotime($previous_year.'-04-01 00:00:00'));
    //     $previous_date = date('Y-m-d H:i:s',strtotime($first_year.'-03-31 23:59:59'));
    //     $previous_date_between = array($very_previous_date,$previous_date);
    //     $team_previous_study_ids = Teams::distinct('study_id')->whereBetween('created_at',$previous_date_between)->pluck('study_id');

    //     //start number of proposals received
    //         $studies_of_previous_year = Eval_activity_status::distinct('study_id')->whereBetween('created_at',$previous_date_between)->whereIn('study_id',$team_previous_study_ids)->where('status_id','!=',null)->groupBy('study_id')->count();
    //     //end number of proposals received

       
    //     //start new studies of the current year
    //         $studies_of_the_current_year = Eval_activity_status::distinct('study_id')->where('current_status','1')->whereBetween('created_at',$date_between)->whereIn('study_id',$team_current_study_ids)->where('status_id','!=',null)->whereNotIn('status_id',[31,32,33,34])->count('*');
           

    //     //start 
    //         $completed_studies = Eval_activity_status::distinct('study_id')->where('current_status','1')->where('approval','1')->whereBetween('created_at',$date_between)->whereIn('study_id',$team_current_study_ids)->where('status_id','!=',null)->whereIn('status_id',[31,32,33])->count('*');
    //     //end 

    //     // start dropped studies
    //         $dropped_studies = Eval_activity_status::distinct('study_id')->where('current_status','1')->whereIn('study_id',$team_current_study_ids)->where('status_id','!=',null)->where('status_id','34')->count('*');
    //     //end dropped studies

    //     //start onhand studies
    //         $studies_onhand = Eval_activity_status::distinct('study_id')->where('current_status','1')->whereIn('study_id',$team_current_study_ids)->where('status_id','!=',null)->whereNotIn('status_id',[31,32,33,34])->count('*') - $dropped_studies;
    //     // end onhand studies

    //     //start 
    //         $total_studies = $studies_of_previous_year + $studies_of_the_current_year + $completed_studies;
    //     //end 

    //     $s = 0;
    //     foreach($main_menu as $mk => $mv) {

    //         if($mv == '8') {
    //             $rep_status_id = array('31','32','33');
    //             $team_study_ids = Teams::whereBetween('created_at',$date_between)->orderBy('team_id','desc')->pluck('study_id');
    //             $report_writing = Eval_activity_status::where('current_status','1')->whereBetween('created_at',$date_between)->whereIn('status_id',$rep_status_id)->whereIn('study_id',$team_study_ids)->where('status_id','!=',null)->where('approval','1')->count('*');
    //             if($report_writing > 0) {
    //                 $main_activity_names[] = Eval_activity_log::where('group_id_dashboard',$mv)->value('activity_dashboard_name');
    //                 $dept_or_scheme_list[$main_activity_names[$s]] = array();
    //                 $arr_count = array();
    //                 $ar = array();
    //                 $total_scheme_count[$s] = array_sum($arr_count);
    //                 if($report_writing > 0) {
    //                     $rep_writ = Eval_activity_status::distinct('study_id')->whereBetween('created_at',$date_between)->whereIn('status_id',$rep_status_id)->whereIn('study_id',$team_study_ids)->where('status_id','!=',null)->where('current_status','1')->where('approval','1')->pluck('study_id');
    //                     $the_arr = Proposal::distinct('scheme_id')->whereIn('draft_id',$rep_writ)->pluck('dept_id');
    //                     $depts = Department::select('dept_id','dept_name')->whereIn('dept_id',$the_arr)->get();
    //                     foreach($depts as $dk => $dv) {
    //                         $ar[] = array('dept_id'=>$dv->dept_id, 'dept_name'=>$dv->dept_name,'modal_type'=>'report_writing', 'marginTop'=>'0');
    //                         $arr_count[] = Proposal::whereBetween('created_at',$date_between)->where('dept_id',$dv->dept_id)->whereIn('draft_id',$rep_writ)->count('*');
    //                     }
    //                     $dept_or_scheme_list[$main_activity_names[$s]] = $ar;
    //                     $dept_or_scheme_count[$main_activity_names[$s]] = $arr_count;
    //                     $total_scheme_count[$s] = array_sum($arr_count);
    //                 }
    //                 $s++;
    //             }
    //         } else if($mv == '7') {
    //             $rep_status_id = array('30');
    //             $team_study_ids = Teams::distinct('study_id')->whereBetween('created_at',$date_between)->pluck('study_id');
    //             $report_writing = Eval_activity_status::where('current_status','1')->whereBetween('created_at',$date_between)->whereIn('study_id',$team_study_ids)->whereIn('status_id',$rep_status_id)->whereIn('study_id',$team_study_ids)->where('status_id','!=',null)->where('approval','1')->count('*');
    //             if($report_writing > 0) {
    //                 $main_activity_names[] = Eval_activity_log::where('group_id_dashboard',$mv)->value('activity_dashboard_name');
    //                 $dept_or_scheme_list[$main_activity_names[$s]] = array();
    //                 $arr_count = array();
    //                 $ar = array();
    //                 $total_scheme_count[$s] = array_sum($arr_count);
    //                 if($report_writing > 0) {
    //                     $rep_writ = Eval_activity_status::distinct('study_id')->whereBetween('created_at',$date_between)->whereIn('status_id',$rep_status_id)->whereIn('study_id',$team_study_ids)->where('status_id','!=',null)->where('current_status','1')->where('approval','1')->pluck('study_id');
    //                     $the_arr = Proposal::distinct('scheme_id')->whereIn('draft_id',$rep_writ)->pluck('dept_id');
    //                     $depts = Department::select('dept_id','dept_name')->whereIn('dept_id',$the_arr)->get();
    //                     foreach($depts as $dk => $dv) {
    //                         $ar[] = array('dept_id'=>$dv->dept_id, 'dept_name'=>$dv->dept_name,'modal_type'=>'report_writing', 'marginTop'=>'0');
    //                         $arr_count[] = Proposal::whereBetween('created_at',$date_between)->where('dept_id',$dv->dept_id)->whereIn('draft_id',$rep_writ)->count('*');
    //                     }
    //                     $dept_or_scheme_list[$main_activity_names[$s]] = $ar;
    //                     $dept_or_scheme_count[$main_activity_names[$s]] = $arr_count;
    //                     $total_scheme_count[$s] = array_sum($arr_count);
    //                 }
    //                 $s++;
    //             }
    //         } else if($mv == '6') {
    //             $rep_status_id = array('29');
    //             $team_study_ids = Teams::whereBetween('created_at',$date_between)->orderBy('team_id','desc')->pluck('study_id');
    //             $report_writing = Eval_activity_status::where('current_status','1')->whereBetween('created_at',$date_between)->whereIn('status_id',$rep_status_id)->whereIn('study_id',$team_study_ids)->where('status_id','!=',null)->where('approval','1')->count('*');
    //             if($report_writing > 0) {
    //                 $main_activity_names[] = Eval_activity_log::where('group_id_dashboard',$mv)->value('activity_dashboard_name');
    //                 $dept_or_scheme_list[$main_activity_names[$s]] = array();
    //                 $arr_count = array();
    //                 $ar = array();
    //                 $total_scheme_count[$s] = array_sum($arr_count);
    //                 if($report_writing > 0) {
    //                     $rep_writ = Eval_activity_status::distinct('study_id')->whereBetween('created_at',$date_between)->whereIn('status_id',$rep_status_id)->whereIn('study_id',$team_study_ids)->where('status_id','!=',null)->where('current_status','1')->where('approval','1')->pluck('study_id');
    //                     $the_arr = Proposal::distinct('scheme_id')->whereIn('draft_id',$rep_writ)->pluck('dept_id');
    //                     $depts = Department::select('dept_id','dept_name')->whereIn('dept_id',$the_arr)->get();
    //                     foreach($depts as $dk => $dv) {
    //                         $ar[] = array('dept_id'=>$dv->dept_id, 'dept_name'=>$dv->dept_name,'modal_type'=>'report_writing', 'marginTop'=>'0');
    //                         $arr_count[] = Proposal::whereBetween('created_at',$date_between)->where('dept_id',$dv->dept_id)->whereIn('draft_id',$rep_writ)->count('*');
    //                     }
    //                     $dept_or_scheme_list[$main_activity_names[$s]] = $ar;
    //                     $dept_or_scheme_count[$main_activity_names[$s]] = $arr_count;
    //                     $total_scheme_count[$s] = array_sum($arr_count);
    //                 }
    //                 $s++;
    //             }
    //         } else if($mv == '4') {
    //             $log_status_ids = array('22','23','24','25','26','27','28');
    //             $team_study_ids = Teams::whereBetween('created_at',$date_between)->orderBy('team_id','desc')->pluck('study_id');
    //             $impact_analysis = Eval_activity_status::where('current_status','1')->where('status_id','!=',null)->whereBetween('created_at',$date_between)->whereIn('status_id',$log_status_ids)->whereIn('study_id',$team_study_ids)->where('approval','1')->count('*');
    //             if($impact_analysis > 0) {
    //                 $main_activity_names[] = Eval_activity_log::where('group_id_dashboard',$mv)->value('activity_dashboard_name');
    //                 $dept_or_scheme_list[$main_activity_names[$s]] = array();
    //                 $arr_count = array();
    //                 $ar = array();
    //                 $total_scheme_count[$s] = array_sum($arr_count);
    //                 if($impact_analysis > 0) {
    //                     $rep_writ = Eval_activity_status::distinct('study_id')->where('current_status','1')->where('status_id','!=',null)->whereBetween('created_at',$date_between)->whereIn('status_id',$log_status_ids)->whereIn('study_id',$team_study_ids)->where('approval','1')->pluck('study_id');
    //                     $the_arr = Proposal::distinct('scheme_id')->whereIn('draft_id',$rep_writ)->pluck('dept_id');
    //                     $depts = Department::select('dept_id','dept_name')->whereIn('dept_id',$the_arr)->get();
    //                     foreach($depts as $dk => $dv) {
    //                         $ar[] = array('dept_id'=>$dv->dept_id, 'dept_name'=>$dv->dept_name,'modal_type'=>'report_writing', 'marginTop'=>'0');
    //                         $arr_count[] = Proposal::whereBetween('created_at',$date_between)->where('dept_id',$dv->dept_id)->whereIn('draft_id',$rep_writ)->count('*');
    //                     }
    //                     $dept_or_scheme_list[$main_activity_names[$s]] = $ar;
    //                     $dept_or_scheme_count[$main_activity_names[$s]] = $arr_count;
    //                     $total_scheme_count[$s] = array_sum($arr_count);
    //                 }
    //                 $s++;
    //             }
    //         } else if($mv == '3') {
    //             $log_status_ids = array('19','20','21');
    //             $team_study_ids = Teams::whereBetween('created_at',$date_between)->orderBy('team_id','desc')->pluck('study_id');
    //             $impact_analysis = Eval_activity_status::where('current_status','1')->whereBetween('created_at',$date_between)->whereIn('status_id',$log_status_ids)->whereIn('study_id',$team_study_ids)->where('status_id','!=',null)->where('approval','1')->count('*');
    //             if($impact_analysis > 0) {
    //                 $main_activity_names[] = Eval_activity_log::where('group_id_dashboard',$mv)->value('activity_dashboard_name');
    //                 $dept_or_scheme_list[$main_activity_names[$s]] = array();
    //                 $arr_count = array();
    //                 $ar = array();
    //                 $total_scheme_count[$s] = array_sum($arr_count);
    //                 if($impact_analysis > 0) {
    //                     $rep_writ = Eval_activity_status::distinct('study_id')->where('current_status','1')->whereBetween('created_at',$date_between)->whereIn('status_id',$log_status_ids)->whereIn('study_id',$team_study_ids)->where('status_id','!=',null)->where('approval','1')->pluck('study_id');
    //                     $the_arr = Proposal::distinct('scheme_id')->whereIn('draft_id',$rep_writ)->pluck('dept_id');
    //                     $depts = Department::select('dept_id','dept_name')->whereIn('dept_id',$the_arr)->get();
    //                     foreach($depts as $dk => $dv) {
    //                         $ar[] = array('dept_id'=>$dv->dept_id, 'dept_name'=>$dv->dept_name,'modal_type'=>'report_writing', 'marginTop'=>'0');
    //                         $arr_count[] = Proposal::whereBetween('created_at',$date_between)->where('dept_id',$dv->dept_id)->whereIn('draft_id',$rep_writ)->count('*');
    //                     }
    //                     $dept_or_scheme_list[$main_activity_names[$s]] = $ar;
    //                     $dept_or_scheme_count[$main_activity_names[$s]] = $arr_count;
    //                     $total_scheme_count[$s] = array_sum($arr_count);
    //                 }
    //                 $s++;
    //             }
    //         } else if($mv == '2') {
    //             $log_status_ids = array('7','8','9','10','11','12','13','14','15','16','17','18');
    //             $team_study_ids = Teams::whereBetween('created_at',$date_between)->orderBy('team_id','desc')->pluck('study_id');
    //             $sample_frame = Eval_activity_status::where('current_status','1')->whereBetween('created_at',$date_between)->whereIn('status_id',$log_status_ids)->whereIn('study_id',$team_study_ids)->where('status_id','!=',null)->where('approval','1')->count('*');
    //             if($sample_frame > 0) {
    //                 $main_activity_names[] = Eval_activity_log::where('group_id_dashboard',$mv)->orderBy('id')->take(1)->value('activity_dashboard_name');
    //                 $dept_or_scheme_list[$main_activity_names[$s]] = array();
    //                 $arr_count = array();
    //                 $ar = array();
    //                 $total_scheme_count[$s] = array_sum($arr_count);
    //                 if($sample_frame > 0) {
    //                     $rep_writ = Eval_activity_status::distinct('study_id')->where('current_status','1')->whereBetween('created_at',$date_between)->whereIn('status_id',$log_status_ids)->whereIn('study_id',$team_study_ids)->where('status_id','!=',null)->where('approval','1')->pluck('study_id');
    //                     $the_arr = Proposal::distinct('scheme_id')->whereIn('draft_id',$rep_writ)->pluck('dept_id');
    //                     $depts = Department::select('dept_id','dept_name')->whereIn('dept_id',$the_arr)->get();
    //                     foreach($depts as $dk => $dv) {
    //                         $ar[] = array('dept_id'=>$dv->dept_id, 'dept_name'=>$dv->dept_name,'modal_type'=>'report_writing', 'marginTop'=>'0');
    //                         $arr_count[] = Proposal::whereBetween('created_at',$date_between)->where('dept_id',$dv->dept_id)->whereIn('draft_id',$rep_writ)->count('*');
    //                     }
    //                     $dept_or_scheme_list[$main_activity_names[$s]] = $ar;
    //                     $dept_or_scheme_count[$main_activity_names[$s]] = $arr_count;
    //                     $total_scheme_count[$s] = array_sum($arr_count);
    //                 }
    //                 $s++;
    //             }
    //         } else if($mv == '1') {
    //             $log_status_ids = array('1','2','3','4','5','6');
    //             $team_study_ids = Teams::whereBetween('created_at',$date_between)->orderBy('team_id','desc')->pluck('study_id');
    //             $analysis_qry = Eval_activity_status::where('current_status','1')->whereBetween('created_at',$date_between)->whereIn('status_id',$log_status_ids)->whereIn('study_id',$team_study_ids)->where('status_id','!=',null)->where('approval','1')->count('*');
    //             // dd($analysis_qry);
    //             if($analysis_qry > 0) {
    //                 $main_activity_names[] = Eval_activity_log::where('group_id_dashboard',$mv)->value('activity_dashboard_name');
    //                 $dept_or_scheme_list[$main_activity_names[$s]] = array();
    //                 $ar = array();
    //                 $arr_count = array();
    //                 $total_scheme_count[$s] = array_sum($arr_count);
    //                 if($analysis_qry > 0) {
    //                     $rep_writ = Eval_activity_status::distinct('study_id')->where('current_status','1')->whereBetween('created_at',$date_between)->whereIn('status_id',$log_status_ids)->whereIn('study_id',$team_study_ids)->where('status_id','!=',null)->where('approval','1')->pluck('study_id');
    //                     $the_arr = Proposal::distinct('scheme_id')->whereIn('draft_id',$rep_writ)->pluck('dept_id');
    //                     $depts = Department::select('dept_id','dept_name')->whereIn('dept_id',$the_arr)->get();
    //                     foreach($depts as $dk => $dv) {
    //                         $ar[] = array('dept_id'=>$dv->dept_id, 'dept_name'=>$dv->dept_name,'modal_type'=>'report_writing', 'marginTop'=>'0');
    //                         $arr_count[] = Proposal::whereBetween('created_at',$date_between)->where('dept_id',$dv->dept_id)->whereIn('draft_id',$rep_writ)->count('*');
    //                     }
    //                     $dept_or_scheme_list[$main_activity_names[$s]] = $ar;
    //                     $dept_or_scheme_count[$main_activity_names[$s]] = $arr_count;
    //                     $total_scheme_count[$s] = array_sum($arr_count);
    //                 }
    //                 $s++;
    //             }
    //         }
    //     }

    //     $count_scheme_list = count($dept_or_scheme_list);
    //     $total_dept_or_schemes_slides = count($dept_or_scheme_list) / 3;
    //     $dept_or_schemes_slides = ceil($total_dept_or_schemes_slides);
    //     $main_activity_names_count = count($main_activity_names);

    //     $the_month = date('m');
    //     if($the_month < 4) {
    //         $show_date = '01-Apr-'.$last_year;
    //     } else {
    //         $show_date = '01-Apr-'.$get_year;
    //     }
    //     $the_arr = array('show_date'=>$show_date,'main_activity_names'=>$main_activity_names,'dept_or_scheme_list'=>$dept_or_scheme_list,'dept_or_schemes_slides'=>$dept_or_schemes_slides,'dept_or_scheme_count'=>$dept_or_scheme_count,'total_scheme_count'=>$total_scheme_count,'main_activity_names_count'=>$main_activity_names_count,'studies_of_previous_year'=>$studies_of_previous_year,'studies_of_the_current_year'=>$studies_of_the_current_year,'total_studies'=>$total_studies,'completed_studies'=>$completed_studies,'count_scheme_list'=>$count_scheme_list,'studies_onhand'=>$studies_onhand,'dropped_studies'=>$dropped_studies);
    //     return response()->json($the_arr);
    // }


	// public function dashboardevalstatus(Request $request) {
    //     $id = $request->get('dept_or_scheme');
    //     if(strpos($id, 'draft_id_') > -1) {
    //         $the_draft_id = substr($id, 9);
    //         $proposal = Proposal::select('scheme_name','entry_date','nodal_id')->where('draft_id',$the_draft_id)->get();
    //         $data = array();
    //         foreach($proposal as $dk => $dv) {
    //             $scheme_name = $dv->scheme_name;
    //             $entry_date = date('d M, Y',strtotime($dv->entry_date));
    //             $nodal_name = Nodal::where('nodalid',$dv->nodal_id)->value('nodal_name');
    //             $data[] = array('scheme_name'=>$scheme_name,'entry_date'=>$entry_date,'nodal_name'=>$nodal_name);
    //         }
    //         return response()->json($data);
    //     }
    // }

    // public function deptdashboardevalstatus(Request $request) {
    //     $dept_id = $request->get('dept_id');
    //     $thisid = $request->get('the_id');
    //     $expthisid = explode('&mav_',$thisid);
    //     $dept_name = str_replace('_',' ',$expthisid[1]);

    //     $the_first_year = $request->get('first_fin_year');
    //     $the_second_year = $request->get('second_fin_year');
    //     $first_year_is = explode('-',$the_first_year);
    //     $first_year = $first_year_is[0];
    //     $second_year_is = explode('-',$the_second_year);
    //     $second_year = $second_year_is[0];
    //     $last_fin_year = date('Y-m-d H:i:s',strtotime($first_year.'-04-01 00:00:00'));
    //     $current_fin_year = date('Y-m-d H:i:s',strtotime($second_year.'-03-31 11:59:59'));
    //     $the_month = date('m');
    //     $the_year = date('Y');
    //     if($the_month > 4 && $the_year <= $second_year) {
    //         $current_fin_year = date('Y-m-d H:i:s');
    //     }
    //     $date_between = array($last_fin_year,$current_fin_year);

    //     if($dept_id > 0) {
    //         $the_dept_id = $dept_id;
    //         $group_id_by_name = Eval_activity_log::where('activity_dashboard_name',$dept_name)->value('group_id_dashboard');
    //         $get_activity_ids = Eval_activity_log::where('group_id_dashboard',$group_id_by_name)->pluck('id');
    //         $get_activity_status_ids = Eval_activity_status::distinct('study_id')->whereIn('status_id',$get_activity_ids)->pluck('study_id');
    //         $activity_ids = $get_activity_ids->toArray();

    //         if(in_array('0',$activity_ids)) {
    //             $pub_qry = Aftermeeting::select('draft_id','subject','chairperson','date','time')->whereBetween('created_at',$date_between)->where('dept_id',$the_dept_id)->orderBy('amid','desc')->get();
    //             $data = array();
    //             foreach($pub_qry as $ek => $ev) {
    //                 $sch_name = Proposal::where('draft_id',$ev->draft_id)->value('scheme_name');
    //                 $dateis = date('d M, Y',strtotime($ev->date));
    //                 $data[] = array('scheme_name'=>$sch_name, 'subject'=>$ev->subject, 'chairperson'=>$ev->chairperson, 'date'=>$dateis, 'time'=>$ev->time);
    //             }
    //             return response()->json($data);
    //         } else if(in_array('0',$activity_ids)) {
    //             $ecc_badge_count = 0;
    //             $ecc_qry = Meetinglog::select('draft_id','subject','chairperson','date','time')->whereBetween('created_at',$date_between)->where('dept_id',$the_dept_id)->get();
    //             $data = array();
    //             foreach($ecc_qry as $ek => $ev) {
    //                 $sch = Proposal::where('draft_id',$ev->draft_id)->value('scheme_name');
    //                 $dateis = date('d M, Y',strtotime($ev->date));
    //                 $data[] = array('scheme_name'=>$sch, 'subject'=>$ev->subject, 'chairperson'=>$ev->chairperson, 'date'=>$dateis, 'time'=>$ev->time);
    //             }
    //             return response()->json($data);
    //         } else if(in_array('0',$activity_ids)) {
    //             $dec_qry = Dept_meetings::select('mid','subject','chairperson','date','time','venue','scheme_id')->whereBetween('created_at',$date_between)->where('dept_id',$the_dept_id)->get();
    //             $data = array();
    //             foreach($dec_qry as $dk => $dv) {
    //                 $decscheme = Scheme::where('scheme_id',$dv->scheme_id)->value('scheme_name');
    //                 $dateis = date('d M, Y',strtotime($dv->date));
    //                 $data[] = array('scheme_name'=>$decscheme, 'subject'=>$dv->subject, 'chairperson'=>$dv->chairperson, 'date'=>$dateis, 'time'=>$dv->time);
    //             }
    //             return response()->json($data);
    //         } else if($this->in_array_any(['1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','18','19','20','21','22','23','24','25','26','27','28','29','30','31','32','33'],$activity_ids)) {
    //             $team_study_ids = Teams::whereBetween('created_at',$date_between)->whereIn('study_id',$get_activity_status_ids)->pluck('study_id');
    //             $the_study_ids = Eval_activity_status::join('itransaction.proposals','eval_activity_status.study_id','=','proposals.draft_id')->whereIn('eval_activity_status.status_id',$activity_ids)->whereIn('eval_activity_status.study_id',$team_study_ids)->where('eval_activity_status.current_status','1')->where('eval_activity_status.approval','1')->where('proposals.dept_id',$the_dept_id)->pluck('eval_activity_status.study_id');
    //             $the_study_ids = $the_study_ids->toArray();
    //             $proposal = Proposal::select('draft_id','scheme_name','entry_date','nodal_officer_name')->whereIn('draft_id',$get_activity_status_ids)->whereBetween('created_at',$date_between)->where('dept_id',$the_dept_id)->whereIn('draft_id',$the_study_ids)->orderBy('draft_id','desc')->get();
    //             $data = array();
    //             foreach($proposal as $dk => $dv) {
    //                 $scheme_name = $dv->scheme_name;
    //                 $study_date = Eval_activity_status::whereIn('status_id',$activity_ids)->where('study_id',$dv->draft_id)->where('approval','1')->value('created_at');
    //                 $createdat = date('d M, Y',strtotime($study_date));
    //                 $data[] = array('scheme_name'=>$scheme_name,'entry_date'=>$createdat,'nodal_name'=>$dv->nodal_officer_name);
    //             }
    //             return response()->json($data);
    //         }
    //     }
    // }

 

    // public function show_prop_detail(Request $request) {
    //     $this->get_count('proposals');
    //     $dept_id_is = $request->get('dept_id');
    //     $proposals = array();
    //     if(Auth::user()->role == 2) {
    //         $the_status_id = '25';
    //         $proposal_list = SchemeSend::leftjoin('itransaction.proposals','itransaction.scheme_send.draft_id','=','itransaction.proposals.draft_id')
    //                 ->select('scheme_send.id','proposals.draft_id','proposals.dept_id','proposals.scheme_name','proposals.nodal_id','proposals.con_id')
    //                 ->where('scheme_send.status_id',$the_status_id)
    //                 ->orderBy('scheme_send.id','desc')
    //                 ->get();
    //         if($proposal_list->isNotEmpty()) {
    //             foreach($proposal_list as $key=>$val) {
    //                 $nodal_name = Nodal::where('nodalid',$val->nodal_id)->value('nodal_name');
    //                 $convener_name = Convener::where('con_id',$val->con_id)->value('convener_name');
    //                 $dept_id = Proposal::where('draft_id',$val->draft_id)->where('dept_id',$dept_id_is)->value('dept_id');
    //                 $proposals[] = array('send_id'=>$val->id,'draft_id'=>$val->draft_id,'dept_id'=>$dept_id,'scheme_name'=>$val->scheme_name,'convener_name'=>$convener_name,'nodal_name'=>$nodal_name);
    //             }
    //         }
    //     }
        
    //     $replace_url = URL::to('/');
    //     if(!empty($proposals)) {
    //         $arr = array('proposal_list'=>$proposals,'replace_url'=>$replace_url);
    //         return response()->json($arr);
    //     }
    // }

    // public function in_array_any($needles, $haystack) {
    //     return !empty(array_intersect($needles, $haystack));
    // }

    // public function publishedstudies(Request $request) {
    //     $name = $request->get('name');
    //     $dept_id = $request->get('dept_id');
    //     $team_leader = $request->get('team_leader');
    //     $the_first_year = $request->get('first_fin_year');
    //     $the_second_year = $request->get('second_fin_year');
    //     $first_year_is = explode('-',$the_first_year);
    //     $first_year = $first_year_is[0];
    //     $second_year_is = explode('-',$the_second_year);
    //     $second_year = $second_year_is[0];
    //     $last_fin_year = date('Y-m-d H:i:s',strtotime($first_year.'-04-01 00:00:00'));
    //     $current_fin_year = date('Y-m-d H:i:s',strtotime($second_year.'-03-31 11:59:59'));
    //     $the_month = date('m');
    //     $the_year = date('Y');
    //     if($the_month > 4 && $the_year <= $second_year) {
    //         $current_fin_year = date('Y-m-d H:i:s');
    //     }
    //     $date_between = array($last_fin_year,$current_fin_year);

    //     $group_id_by_name = Eval_activity_log::where('activity_dashboard_name',$name)->value('group_id_dashboard');
    //     $get_activity_ids = Eval_activity_log::where('group_id_dashboard',$group_id_by_name)->pluck('id');
    //     $activity_ids = $get_activity_ids->toArray();

    //     return $this->getpublished_studies($activity_ids,$date_between,$dept_id,$team_leader);
    // }

    // public function getpublished_studies($activity_ids,$date_between,$dept_id,$team_leader) {
    //     if($this->in_array_any(['31','32','33'],$activity_ids)) {
    //         $ids = array('31','32','33');
    //         $team_study_ids = Teams::whereBetween('created_at',$date_between)->orderBy('team_id','desc')->pluck('study_id');
    //         $study_ids = Eval_activity_status::distinct('study_id')->where('current_status','1')->where('current_status','1')->whereIn('status_id',$ids)->where('approval','1')->whereIn('study_id',$team_study_ids)->whereBetween('created_at',$date_between)->pluck('study_id');
    //         if($dept_id != '') {
    //             $get_dept_ids = Proposal::distinct('scheme_id')->whereIn('draft_id',$study_ids)->where('dept_id',$dept_id)->pluck('dept_id');
    //         } else {
    //             $get_dept_ids = Proposal::distinct('scheme_id')->whereIn('draft_id',$study_ids)->pluck('dept_id');
    //         }
    //         $studies = Proposal::whereIn('draft_id',$study_ids)->whereIn('dept_id',$get_dept_ids)->orderBy('draft_id','desc')->get();
    //         $study = array();
    //         foreach($studies as $k => $v) {
    //             $dd_id = Teams::where('study_id',$v->draft_id)->orderBy('team_id','desc')->value('dd_leader');
    //             $dd_name = User::where('id',$dd_id)->value('name');
    //             if($dd_id == '') {
    //                 $dd_name = 'No Team';
    //             }
    //             $scheme_name = Proposal::where('draft_id',$v->draft_id)->value('scheme_name');
    //             $dept_name = Department::where('dept_id',$v->dept_id)->value('dept_name');
    //             $study_date = Eval_activity_status::where('current_status','1')->whereIn('status_id',$ids)->where('study_id',$v->draft_id)->where('approval','1')->value('created_at');
    //             $createdat = date('d M, Y',strtotime($study_date));
    //             $study[] = array('DD'=>$dd_name,'scheme_name'=>$scheme_name,'dept_name'=>$dept_name,'date'=>$createdat,'remarks'=>$v->remarks);
    //         }
    //         return response()->json($study);
    //     } elseif(in_array('30',$activity_ids)) {
    //         $ids = array('30');
    //         $team_study_ids = Teams::whereBetween('created_at',$date_between)->orderBy('team_id','desc')->pluck('study_id');
    //         $study_ids = Eval_activity_status::distinct('study_id')->where('current_status','1')->whereIn('status_id',$ids)->where('approval','1')->whereIn('study_id',$team_study_ids)->whereBetween('created_at',$date_between)->pluck('study_id');
    //         if($dept_id != '') {
    //             $get_dept_ids = Proposal::distinct('scheme_id')->whereIn('draft_id',$study_ids)->where('dept_id',$dept_id)->pluck('dept_id');
    //         } else {
    //             $get_dept_ids = Proposal::distinct('scheme_id')->whereIn('draft_id',$study_ids)->pluck('dept_id');
    //         }
    //         $studies = Proposal::whereIn('draft_id',$study_ids)->whereIn('dept_id',$get_dept_ids)->orderBy('draft_id','desc')->get();
    //         $study = array();
    //         foreach($studies as $k => $v) {
    //             $dd_id = Teams::where('study_id',$v->draft_id)->orderBy('team_id','desc')->value('dd_leader');
    //             $dd_name = User::where('id',$dd_id)->value('name');
    //             if($dd_id == '') {
    //                 $dd_name = 'No Team';
    //             }
    //             $scheme_name = Proposal::where('draft_id',$v->draft_id)->value('scheme_name');
    //             $dept_name = Department::where('dept_id',$v->dept_id)->value('dept_name');
    //             $study_date = Eval_activity_status::where('current_status','1')->whereIn('status_id',$ids)->where('study_id',$v->draft_id)->where('approval','1')->value('created_at');
    //             $createdat = date('d M, Y',strtotime($study_date));
    //             $study[] = array('DD'=>$dd_name,'scheme_name'=>$scheme_name,'dept_name'=>$dept_name,'date'=>$createdat,'remarks'=>$v->remarks);
    //         }
    //         return response()->json($study);
    //     } elseif(in_array('29',$activity_ids)) {
    //         $ids = array('29');
    //         $team_study_ids = Teams::whereBetween('created_at',$date_between)->orderBy('team_id','desc')->pluck('study_id');
    //         $study_ids = Eval_activity_status::distinct('study_id')->where('current_status','1')->whereIn('status_id',$ids)->where('approval','1')->whereIn('study_id',$team_study_ids)->whereBetween('created_at',$date_between)->pluck('study_id');
    //         if($dept_id != '') {
    //             $get_dept_ids = Proposal::distinct('scheme_id')->whereIn('draft_id',$study_ids)->where('dept_id',$dept_id)->pluck('dept_id');
    //         } else {
    //             $get_dept_ids = Proposal::distinct('scheme_id')->whereIn('draft_id',$study_ids)->pluck('dept_id');
    //         }
    //         $studies = Proposal::whereIn('draft_id',$study_ids)->whereIn('dept_id',$get_dept_ids)->orderBy('draft_id','desc')->get();
    //         $study = array();
    //         foreach($studies as $k => $v) {
    //             $dd_id = Teams::where('study_id',$v->draft_id)->orderBy('team_id','desc')->value('dd_leader');
    //             $dd_name = User::where('id',$dd_id)->value('name');
    //             if($dd_id == '') {
    //                 $dd_name = 'No Team';
    //             }
    //             $scheme_name = Proposal::where('draft_id',$v->draft_id)->value('scheme_name');
    //             $dept_name = Department::where('dept_id',$v->dept_id)->value('dept_name');
    //             $study_date = Eval_activity_status::where('current_status','1')->whereIn('status_id',$ids)->where('study_id',$v->draft_id)->where('approval','1')->value('created_at');
    //             $createdat = date('d M, Y',strtotime($study_date));
    //             $study[] = array('DD'=>$dd_name,'scheme_name'=>$scheme_name,'dept_name'=>$dept_name,'date'=>$createdat,'remarks'=>$v->remarks);
    //         }
    //         return response()->json($study);
    //     } elseif($this->in_array_any(['22','23','24','25','26','27','28'],$activity_ids)) {
    //         $ids = array('22','23','24','25','26','27','28');
    //         $team_study_ids = Teams::whereBetween('created_at',$date_between)->orderBy('team_id','desc')->pluck('study_id');
    //         $study_ids = Eval_activity_status::where('current_status','1')->where('status_id','!=',null)->whereIn('status_id',$ids)->where('approval','1')->whereIn('study_id',$team_study_ids)->whereBetween('created_at',$date_between)->orderBy('id','desc')->pluck('study_id');
    //         if($dept_id != '') {
    //             $get_dept_ids = Proposal::distinct('scheme_id')->whereIn('draft_id',$study_ids)->where('dept_id',$dept_id)->pluck('dept_id');
    //         } else {
    //             $get_dept_ids = Proposal::distinct('scheme_id')->whereIn('draft_id',$study_ids)->pluck('dept_id');
    //         }
    //         $studies = Proposal::whereIn('draft_id',$study_ids)->whereIn('dept_id',$get_dept_ids)->orderBy('draft_id','desc')->get();
    //         $study = array();
    //         foreach($studies as $k => $v) {
    //             $dd_id = Teams::where('study_id',$v->draft_id)->orderBy('team_id','desc')->value('dd_leader');
    //             $dd_name = User::where('id',$dd_id)->value('name');
    //             if($dd_id == '') {
    //                 $dd_name = 'No Team';
    //             }
    //             $scheme_name = Proposal::where('draft_id',$v->draft_id)->value('scheme_name');
    //             $dept_name = Department::where('dept_id',$v->dept_id)->value('dept_name');
    //             $study_date = Eval_activity_status::where('current_status','1')->whereIn('status_id',$ids)->where('study_id',$v->draft_id)->where('approval','1')->value('created_at');
    //             $createdat = date('d M, Y',strtotime($study_date));
    //             $study[] = array('DD'=>$dd_name,'scheme_name'=>$scheme_name,'dept_name'=>$dept_name,'date'=>$createdat,'remarks'=>$v->remarks);
    //         }
    //         return response()->json($study);
    //     } elseif($this->in_array_any(['19','20','21'],$activity_ids)) {
    //         $ids = array('19','20','21');
    //         $team_study_ids = Teams::whereBetween('created_at',$date_between)->orderBy('team_id','desc')->pluck('study_id');
    //         $study_ids = Eval_activity_status::distinct('study_id')->where('current_status','1')->whereIn('status_id',$ids)->where('approval','1')->whereIn('study_id',$team_study_ids)->whereBetween('created_at',$date_between)->pluck('study_id');
    //         if($dept_id != '') {
    //             $get_dept_ids = Proposal::distinct('scheme_id')->whereIn('draft_id',$study_ids)->where('dept_id',$dept_id)->pluck('dept_id');
    //         } else {
    //             $get_dept_ids = Proposal::distinct('scheme_id')->whereIn('draft_id',$study_ids)->pluck('dept_id');
    //         }
    //         $studies = Proposal::whereIn('draft_id',$study_ids)->whereIn('dept_id',$get_dept_ids)->orderBy('draft_id','desc')->get();
    //         $study = array();
    //         foreach($studies as $k => $v) {
    //             $dd_id = Teams::where('study_id',$v->draft_id)->orderBy('team_id','desc')->value('dd_leader');
    //             $dd_name = User::where('id',$dd_id)->value('name');
    //             if($dd_id == '') {
    //                 $dd_name = 'No Team';
    //             }
    //             $scheme_name = Proposal::where('draft_id',$v->draft_id)->value('scheme_name');
    //             $dept_name = Department::where('dept_id',$v->dept_id)->value('dept_name');
    //             $study_date = Eval_activity_status::where('current_status','1')->whereIn('status_id',$ids)->where('study_id',$v->draft_id)->where('approval','1')->orderBy('id','desc')->take(1)->value('created_at');
    //             $createdat = date('d M, Y',strtotime($study_date));
    //             $study[] = array('DD'=>$dd_name,'scheme_name'=>$scheme_name,'dept_name'=>$dept_name,'date'=>$createdat,'remarks'=>$v->remarks);
    //         }
    //         return response()->json($study);
    //     } elseif($this->in_array_any(['7','8','9','10','11','12','13','14','15','16','17','18'],$activity_ids)) {
    //         $ids = array('7','8','9','10','11','12','13','14','15','16','17','18');
    //         $team_study_ids = Teams::whereBetween('created_at',$date_between)->orderBy('team_id','desc')->pluck('study_id');
    //         $study_ids = Eval_activity_status::distinct('study_id')->where('current_status','1')->whereIn('status_id',$ids)->where('approval','1')->whereIn('study_id',$team_study_ids)->whereBetween('created_at',$date_between)->pluck('study_id');
    //         if($dept_id != '') {
    //             $get_dept_ids = Proposal::distinct('scheme_id')->whereIn('draft_id',$study_ids)->where('dept_id',$dept_id)->pluck('dept_id');
    //         } else {
    //             $get_dept_ids = Proposal::distinct('scheme_id')->whereIn('draft_id',$study_ids)->pluck('dept_id');
    //         }
    //         $studies = Proposal::whereIn('draft_id',$study_ids)->whereIn('dept_id',$get_dept_ids)->orderBy('draft_id','desc')->get();
    //         $study = array();
    //         foreach($studies as $k => $v) {
    //             $dd_id = Teams::where('study_id',$v->draft_id)->orderBy('team_id','desc')->value('dd_leader');
    //             $dd_name = User::where('id',$dd_id)->value('name');
    //             if($dd_id == '') {
    //                 $dd_name = 'No Team';
    //             }
    //             $scheme_name = Proposal::where('draft_id',$v->draft_id)->value('scheme_name');
    //             $dept_name = Department::where('dept_id',$v->dept_id)->value('dept_name');
    //             $study_date = Eval_activity_status::where('current_status','1')->whereIn('status_id',$ids)->where('study_id',$v->draft_id)->where('approval','1')->value('created_at');
    //             $createdat = date('d M, Y',strtotime($study_date));
    //             $study[] = array('DD'=>$dd_name,'scheme_name'=>$scheme_name,'dept_name'=>$dept_name,'date'=>$createdat,'remarks'=>$v->remarks);
    //         }
    //         return response()->json($study);
    //     } elseif($this->in_array_any(['1','2','3','4','5','6'],$activity_ids)) {
    //         $ids = array(1,2,3,4,5,6);
    //         $team_study_ids = Teams::whereBetween('created_at',$date_between)->orderBy('team_id','desc')->pluck('study_id');
    //         $study_ids = Eval_activity_status::distinct('study_id')->where('current_status','1')->whereIn('status_id',$ids)->where('approval','1')->whereIn('study_id',$team_study_ids)->whereBetween('created_at',$date_between)->pluck('study_id');
    //         if($dept_id != '') {
    //             $get_dept_ids = Proposal::distinct('scheme_id')->whereIn('draft_id',$study_ids)->where('dept_id',$dept_id)->pluck('dept_id');
    //         } else {
    //             $get_dept_ids = Proposal::distinct('scheme_id')->whereIn('draft_id',$study_ids)->pluck('dept_id');
    //         }
    //         $studies = Proposal::whereIn('draft_id',$study_ids)->whereIn('dept_id',$get_dept_ids)->orderBy('draft_id','desc')->get();
    //         $study = array();
    //         foreach($studies as $k => $v) {
    //             $dd_id = Teams::where('study_id',$v->draft_id)->orderBy('team_id','desc')->value('dd_leader');
    //             $dd_name = User::where('id',$dd_id)->value('name');
    //             if($dd_id == '') {
    //                 $dd_name = 'No Team';
    //             }
    //             $scheme_name = Proposal::where('draft_id',$v->draft_id)->value('scheme_name');
    //             $dept_name = Department::where('dept_id',$v->dept_id)->value('dept_name');
    //             $study_date = Eval_activity_status::where('current_status','1')->whereIn('status_id',$ids)->where('study_id',$v->draft_id)->where('approval','1')->value('created_at');
    //             $createdat = date('d M, Y',strtotime($study_date));
    //             $study[] = array('DD'=>$dd_name,'scheme_name'=>$scheme_name,'dept_name'=>$dept_name,'date'=>$createdat,'remarks'=>$v->remarks);
    //         }
    //         return response()->json($study);
    //     }
    // }

    
	// public function sdggoals() {
    //     $sdggoals = Sdggoals::orderBy('goal_id','desc')->get();
    //     $requesturi = \Request::getRequestUri();
    //     $currenturl = \URL::current();
    //     $replace_url = str_replace($requesturi, '', $currenturl);
    //     return view('dashboards.eva-dir.sdggoals',compact('sdggoals','replace_url'));
    // }

    // public function addgoal(Request $request) {
    //     $validate = Validator::make($request->all(),[
    //         'goal_name' => 'required|string'
    //     ]);
    //     if($validate->fails()) {
    //         return redirect()->back()->withInput()->withErrors($validate->errors());
    //     } else {
           
    //         $goal_name = $request->get('goal_name');
    //         Sdggoals::insert(['goal_name'=>$goal_name]);
    //         return redirect()->back()->with('goal_success','Goal saved successfully');
    //     }
    // }

    // public function editgoal(Request $request) {
    //     $validate = Validator::make($request->all(),[
    //         'goal_id' => 'required|numeric',
    //         'goal_name' => 'required|string',
    //         'status' => 'required|numeric'
    //     ]);
    //     if($validate->fails()) {
    //         return redirect()->back()->withInput()->withErrors($validate->errors());
    //     } else {
    //         $goal_id = $request->get('goal_id');
    //         $goal_name = $request->get('goal_name');
    //         $status = $request->get('status');
    //         Sdggoals::where('goal_id',$goal_id)->update(['goal_name'=>$goal_name,'status'=>$status,'updated_at'=>date('Y-m-d H:i:s')]);
    //         return redirect()->back()->with('goal_success','Goal saved successfully');
    //     }
    // }

    // public function getgoaldata(Request $request) {
    //     $validate = Validator::make($request->all(),[
    //         'goal_id' => 'required|numeric',
    //     ]);
    //     if($validate->fails()) {
    //         return redirect()->back()->withInput()->withErrors($validate->errors());
    //     } else {
    //         $goal_id = $request->get('goal_id');
    //         $goal = Sdggoals::where('goal_id',$goal_id)->get();
    //         $goal_name = '';
    //         $status = '';
    //         if($goal->isNotEmpty()) {
    //             foreach($goal as $g) {
    //                 $goal_name = $g->goal_name;
    //                 $status = $g->status;
    //             }
    //         }
    //         $arr = array('goal_name'=>$goal_name,'status'=>$status);
    //         return response()->json($arr);
    //     }
    // }
    public function profile() {
        return view('dashboards.eva-dir.profile');
    }

    public function settings() {
        return view('dashboards.eva-dir.settings');
    }

    public function proposallist($param) {
        $dept_id = Auth::user()->dept_id;
        $user_id = auth()->user()->role;
        $user_login = DB::table('users')->leftjoin('public.user_user_role_deptid','user_id','=','users.id')
                                        ->where('users.id','=',$user_id)
                                        ->select('users.name','users.id','users.role')
                                        ->get();
          
        $branch_list = Branch::orderBy('name','Asc')->get();


           if($param == "new"){
            //New Proposal
            if(Auth::check() && Auth::user()->role_manage == 2 && Auth::user()->role == 24 ){ //JD ROLE
                $new_proposals = SchemeSend::whereNotNull('scheme_send.team_member_dd')
                                    ->where('scheme_send.approved', 2)
                                     ->whereIn('scheme_send.status_id', [25])
                                    ->where('scheme_send.forward_btn_show', 1)
                                    ->where('scheme_send.forward_id', 1)
                                    ->orderByDesc('scheme_send.id')
                                    ->distinct()
                                    ->get();
            }else{ // DIR
                        $new_proposals = SchemeSend::whereNotNull('scheme_send.team_member_dd')
                                                    ->where('scheme_send.approved', 3)
                                                     ->whereIn('scheme_send.status_id', [25])
                                                    ->where('scheme_send.forward_btn_show', 1)
                                                    ->where('scheme_send.forward_id', 1)
                                                    ->orderByDesc('scheme_send.id')
                                                    ->distinct()
                                                    ->get();
            }
           
                           
            // $new_proposals = SchemeSend::where(function ($query) {
            //                         $query->whereNotNull('scheme_send.status_id')
            //                             ->orWhereNull('scheme_send.team_member_dd');
            //                     })
            //                     ->whereIn('scheme_send.status_id', [25, 23])
            //                     ->where('scheme_send.forward_btn_show', 1)
            //                     ->where('scheme_send.forward_id', 1)
            //                     ->orderByDesc('scheme_send.id')
            //                     ->distinct()
                                //->get();
         
            return view('dashboards.eva-dir.proposal.new_proposal',compact('new_proposals','branch_list'));
   
           }elseif ($param == "on_going") {
            //Ongoing 
            $ongoing_proposal = Stage::with(['schemeSend' => function ($q) {
                $q->whereNotNull('team_member_dd');
            }])->whereNull('document')->get();       
        
            return view('dashboards.eva-dir.proposal.ongoing_proposal',compact('ongoing_proposal'));
   
   
           }elseif ($param == "return") {
            //Return
            $returned_proposals = SchemeSend::whereIn('scheme_send.status_id', [24, 26,27,28])
                                        ->where(function ($query) {
                                            $query->whereNotNull('scheme_send.status_id');
                                        })
                                        ->orderByDesc('scheme_send.id')
                                        ->distinct()
                                        ->get();

            return view('dashboards.eva-dir.proposal.return_proposal',compact('returned_proposals'));
   
           }elseif ($param == "completed") {
               //Completed 
               $complted_proposal = Stage::WhereNotNull('document')->get();
              return view('dashboards.eva-dir.proposal.completed_proposal',compact('complted_proposal'));

           }
        
    }
   
    

    // public function proposaldetail($draft_id) {
    //     $goals = Sdggoals::where('status','1')->orderBy('goal_id','desc')->get();
    //     $proposal_list = Proposal::with(['gr_file','notification_files','brochure_files','pamphlets_files','otherdetailscenterstate_files'])->where('draft_id',Crypt::decrypt($draft_id))->get();
      
    //     $replace_url = URL::to('/');
    //     $dept_name = '';
    //     $major_objectives = array();
    //     $imdept = array();
    //     $major_indicator_hod = array();
    //     $financial_progress = array();
    //     $beneficiariesGeoLocal = beneficiariesGeoLocal();
    //     $district_ids = array();
    //     $taluka_ids = array();
    //     $dept_name = array();
    //     $conv_dept_ids = array();
    //     $conv_dept_remarks = array();
    //     $entered_goals = array();
    //     $bencovfile = 'no data';
    //     $trainingfile = 'no data';
    //     $iecfile = 'no data';
    //     $eval_report = 'no data';
    //     $gr_files = 'no data';
    //     $notification_files = 'no data';
    //     $brochure_files = 'no data';
    //     $pamphlets_files = 'no data';
    //     $otherdetailscenterstate_files = 'no data';
    //     $all_convergence = array();
    //     foreach($proposal_list as $key=>$value) {
    //         $scheme_id = $value->scheme_id;
    //         $dept_name = Department::where('dept_id',$value->dept_id)->value('dept_name');
    //         if($value->major_objective) {
    //             $major_objectives = json_decode($value->major_objective);
    //         }
    //         if($value->major_indicator) {
    //             $major_indicators = json_decode($value->major_indicator);
    //         }
    //         $imdept = Implementation::where('id',$value->im_id)->get();
    //         if($value->major_indicator_hod) {
    //             $major_indicator_hod = json_decode($value->major_indicator_hod);
    //         }
    //         $financial_progress = FinancialProgress::where('scheme_id',$value->scheme_id)->get();
    //         if($value->districts != 'null' or $value->districts != '') {
    //             $district_ids = json_decode($value->districts);
    //         }
    //         if($value->talukas != 'null' or $value->talukas != '') {
    //             $taluka_ids = json_decode($value->talukas);
    //         }
         
    //         if($value->is_sdg) {
    //             $entered_goals = json_decode($value->is_sdg);
    //         }
    //         if($value->all_convergence) {
    //             $all_convergence = json_decode($value->all_convergence);
    //         }
    //         $bencovfile = $this->getthefilecount($scheme_id,'_beneficiaries_coverage');
    //         $trainingfile = $this->getthefilecount($scheme_id,'_training');
    //         $iecfile = $this->getthefilecount($scheme_id,'_iec');
    //         $eval_report = $this->getthefilecount($scheme_id,'_eval_report_');
    //         $gr_files = $this->getthefilecount($scheme_id,'_gr_');
    //         $notification_files = $this->getthefilecount($scheme_id,'_notification');
    //         $brochure_files = $this->getthefilecount($scheme_id,'_brochure');
    //         $pamphlets_files = $this->getthefilecount($scheme_id,'_pamphlets');
    //         $otherdetailscenterstate_files = $this->getthefilecount($scheme_id,'_otherdetailscenterstate');
    //     }
    //     $the_convergence = array();
    //     if(!empty($all_convergence)) {
    //         foreach($all_convergence as $kc => $vc) {
    //             $dept_name = Department::where('dept_id',$vc->dept_id)->value('dept_name');
    //             if($dept_name == '') {
    //                 $dept_name = 'no department';
    //             }
    //             $the_convergence[] = array('dept_name'=>$dept_name,'remarks'=>$vc->dept_remarks);
    //         }
    //     }
    //     $district_names = array();
    //     if(!empty($district_ids)) {
    //         foreach($district_ids as $dkey=>$dval) {
    //             $district_names[] = Districts::where('dcode',$dval)->value('name_e');
    //         }
    //     }
    //     $taluka_names = array();
    //     if(!empty($taluka_ids)) {
    //         foreach($taluka_ids as $dkey=>$dval) {
    //             $taluka_names[] = Taluka::where('tcode',$dval)->value('tname_e');
    //         }
    //     }
    //     $dept_names = array();
    //     if(count($conv_dept_ids) > 0) {
    //         foreach($conv_dept_ids as $convkey=>$conval) {
    //             $dept_is = Department::select('dept_id','dept_name')->where('dept_id',$conval)->get();
    //             $dept_names[] = $dept_is->toArray();
    //         }
    //     }
    //     return view('dashboards.eva-dir.proposaldetail',compact('proposal_list','dept_name','major_objectives','major_indicators','imdept','major_indicator_hod','financial_progress','replace_url','scheme_id','beneficiariesGeoLocal','bencovfile','trainingfile','iecfile','district_names','dept_names','taluka_names','conv_dept_remarks','goals','entered_goals','eval_report','gr_files','notification_files','brochure_files','pamphlets_files','otherdetailscenterstate_files','the_convergence'));
    // }

    // public function pending_proposal_remarks(Request $request) {
    //     $draft_id = $request->input('theid');
    //     $remarks = $request->input('remarks');
    //     Proposal::where('draft_id',$draft_id)->update(['remarks'=>$remarks]);
    //     return redirect()->back();
    // }

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

    // public function getthefile($id,$scheme) {
    //     $id = 'scheme_'.$id;
    //     $extended = new Couchdb();
    //     $extended->InitConnection();
    //     $status = $extended->isRunning();
    //     $out = $extended->getDocument($this->envirment['database'],$id);
    //     $arrays = json_decode($out, true);
    //     if(isset($arrays)) {
    //         $attachments = $arrays['_attachments'];
    //     } else {
    //         return "no data";
    //     }
    //     foreach($attachments as $attachment_name => $attachment) {
    //         $at_name[] = $attachment_name;
    //     }
    //     if(count($at_name) > 0) {
    //         foreach($at_name as $atkey=>$atvalue) {
    //             if(strpos($atvalue,$scheme) !== false) {
    //                 $cont = file_get_contents($this->envirment['url'].$id."/".$atvalue);
    //                 if($cont) {
    //                     return response($cont)->withHeaders(['Content-type'=>'application/pdf']);
    //                 } else {
    //                     return "Error fetching the document. Contact NIC";
    //                 }
    //             }
    //         }
    //     } else {
    //         return 'Document not found !';
    //     }
    // }

	
    public function returnToGad(Request $request) {
      
        $validate = Validator::make($request->all(),[
            'remarks' => 'string|required',
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
            $data['status_id'] = '27';
            $data['user_id'] = Auth::user()->id;
            $data['created_by'] = Auth::user()->name;
            $data['dept_id'] = Crypt::decrypt($request->dept_id);
         //   $data['dept_id'] = $dept_id;
            $data['draft_id'] = $d_id;
            $data['remarks'] = $request->remarks;
            $data['forward_btn_show'] = 1;
            $data['forward_id'] = 0;
			$data['approved'] = 0;
			$data['team_member_dd'] = null;
            $s_id = '27';
            $update = SchemeSend::where('draft_id',$d_id)->latest()->first();
           
            if($request->send_id != null) {
                unset($data['_token']);
                unset($request->send_id);
                Proposal::where('draft_id','=',$d_id)->update(['status_id'=> $s_id]);
                SchemeSend::where('draft_id',$d_id)->update($data);
            } else {
                unset($request->send_id);
                unset($data['_token']);
                Proposal::where('draft_id',$d_id)->update(['status_id'=>$s_id]);
            }
            $act['userid'] = Auth::user()->id;
            $act['ip'] = $request->ip();
            $act['activity'] = 'Return Proposal to GAD.';
            $act['officecode'] = Auth::user()->dept_id;
            $act['pagereferred'] = $request->url();
            Activitylog::insert($act);

            return redirect()->back()->with('forward_to_gad_success','Proposal sent successfully to GAD');
        }
    }
    public function sendschemetodd(Request $request) {
       
        $validate = Validator::make($request->all(),[
            'remarks' => 'string|required',
        ]);
        if($validate->fails()) {
            return redirect()->back()->withError($validate->errors());
        } else {

            $update = SchemeSend::where('draft_id',$request->draft_id)->where('dept_id',$request->dept_id)->where('id',$request->send_id)->latest()->first();
           
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
                    'draft_id' => (!is_null($request->draft_id)) ? $request->draft_id : null,
                    'dept_id' => (!is_null($request->dept_id)) ? $request->dept_id : null,
                    'user_id' => Auth::user()->id,
                    'created_by' => Auth::user()->name,
                    'remarks' => (!is_null($request->remarks)) ? $request->remarks : null,
                    'team_member_dd' => (!is_null($roleIds)) ? implode(',', $roleIds)  : null,
                ]);
            }
            $act['userid'] = Auth::user()->id;
            $act['ip'] = $request->ip();
            $act['activity'] = 'Proposal Sending to DD';
            $act['officecode'] = Auth::user()->dept_id;
            $act['pagereferred'] = $request->url();
            Activitylog::insert($act);
           
            return redirect()->back()->withSuccess('Proposal sent successfully to DD');
        }
    }

    // public function meetings() {
    //     $schemes = SchemeSend::select('proposals.draft_id','proposals.scheme_name','proposals.scheme_id')->leftjoin('itransaction.proposals','scheme_send.draft_id','=','proposals.draft_id')->where('scheme_send.status_id','25')->orderBy('scheme_send.id','desc')->get();
    //     $schemelist = array();
    //     $i = 0;
    //     $j = 0;
    //     foreach($schemes as $sc) {
    //         if($j == 0) {
    //             $schemelist[] = array('draft_id'=>$sc->draft_id,'scheme_name'=>$sc->scheme_name,'scheme_id'=>$sc->scheme_id);
    //         } else {
    //             $plucked_arr = Arr::pluck($schemelist,'draft_id');
    //             if(!in_array($sc->draft_id,$plucked_arr)) {
    //                 $schemelist[] = array('draft_id'=>$sc->draft_id,'scheme_name'=>$sc->scheme_name,'scheme_id'=>$sc->scheme_id);
    //                 $i++;
    //             }
    //         }
    //         $j++;
    //     }
    //     $user_id = Auth::user()->id;
    //     $user_role_id = Auth::user()->role;
    //     $departments = Department::orderBy('dept_name','asc')->get();
    //     $meetings = Meetinglog::select('meetinglogs.*','aftermeeting.amid','aftermeeting.filename as aftermeetingfile')->leftjoin('itransaction.aftermeeting','meetinglogs.mid','=','aftermeeting.mid')->where('meetinglogs.user_id',$user_id)->where('meetinglogs.user_role_id',$user_role_id)->orderBy('meetinglogs.mid','desc')->get();
    //     $attendees = User::select('id','name')->where('role','3')->orWhere('role','4')->get();
    //     $implementations = Implementation::all();

    //     $replace_url = URL::to('/');
    //     return view('dashboards.eva-dir.meetings',compact('meetings','attendees','schemelist','departments','implementations','replace_url'));
    // }

    // public function getdepartment(Request $request) {
    //     $draft_id = $request->input('draft_id');
    //     $scheme_dept = Proposal::select('scheme_id','dept_id')->where('draft_id',$draft_id)->get();
    //     $scheme_name = '';
    //     $dept_name = '';
    //     foreach($scheme_dept as $key=>$val) {
    //         $scheme_name = Scheme::where('scheme_id',$val->scheme_id)->value('scheme_name');
    //         $dept_name = Department::where('dept_id',$val->dept_id)->value('dept_name');
    //     }
    //     $arr = array('scheme_name'=>$scheme_name,'dept_name'=>$dept_name);
    //     return response()->json($arr);
    // }

    // public function addmeeting(Request $request) {
    //     $validate = Validator::make($request->all(),[
    //         'draft_id' => 'required|numeric',
    //         'subject' => 'required|string|max:100',
    //         'description' => 'required|string|max:990',
    //         'chairperson' => 'required|string|max:100',
    //         'date' => 'required|date',
    //         'time' => 'required|date_format:H:i:s',
    //         'venue' => 'required|max:50',
    //         'attendees' => 'required',
    //         'document' => 'required|mimes:docx,pdf,xlsx',
    //     ]);
    //     if($validate->fails()) {
    //         return redirect()->back()->withInput()->withErrors($validate->errors());
    //     } else {
    //         if($request->input('venue') == '' and $request->input('venue_of_meeting_text') == '') {
    //             return redirect()->back();
    //         }
    //         $venue = '';
    //         if($request->input('venue_of_meeting_text') == '') {
    //             $venue = $request->input('venue');
    //         }  else {
    //             $venue = $request->input('venue_of_meeting_text');
    //         }
    //         $draft_id = $request->input('draft_id');
    //         $scheme_id = Proposal::where('draft_id',$draft_id)->value('scheme_id');
    //         $dept_id = Proposal::where('draft_id',$draft_id)->value('dept_id');
    //         $scheme_name = Scheme::where('scheme_id',$draft_id)->value('scheme_name');
    //         $subject = $request->input('subject');
    //         $description = $request->input('description');
    //         $chairperson = $request->input('chairperson');
    //         $date = $request->input('date');
    //         $time = $request->input('time');
    //         $created_at = Carbon::now();
    //         $attendees = json_encode(array('users'=>$request->input('attendees')));
	// 		$attendees_users = $request->input('attendees');
           

    //         $attendees_emails = User::whereIn('id',$request->input('attendees'))->pluck('email');

    //         $details = array('draft_id'=>$draft_id, 'dept_id'=>$dept_id,'scheme_name'=>$scheme_name,'subject'=>$subject,'description'=>$subject,'description'=>$description,'venue'=>$venue,'chairperson'=>$chairperson,'attendees'=>$attendees,'date'=>$date,'time'=>$time,'created_at'=>$created_at);

    //         $path = array();
    //         $document = $request->file();
    //         $rev = '';
    //         $flname = '';
    //         foreach($document as $dkey=>$dval) {
    //             $doc_id= 'scheme_'.$scheme_id;
    //             $extended = new Couchdb();
    //             $extended->InitConnection();
    //             $status = $extended->isRunning();
    //             $file = Attachment::where('couch_doc_id',$doc_id)->first();
    //             $file_data = json_decode($file,true);
    //             $rev = $file_data['couch_rev_id'];
    //             $path['id'] = $doc_id;
    //             $path['tmp_name'] = $dval->getRealPath();
    //             $path['extension']  = $dval->getClientOriginalExtension();
    //             $path['name'] = $doc_id.'_setmeeting'.'.'.$path['extension'];
    //             $flname = $path['name'];
    //             $details['filename'] = $flname;
    //             $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);

    //             $array = json_decode($out, true);

    //             if(array_key_exists('error',$array)) {
    //                 echo $rev;
    //                 print_r($array);
    //                 die();
    //             } else {
    //                 $id = $array['id'];
    //                 $rev = $array['rev'];
    //                 $data['couch_rev_id'] = $rev;
    //                 $attachment = Attachment::where('scheme_id',$scheme_id)->update($data);
    //             }
    //         }
    //         $user_id = Auth::user()->id;
    //         $user_role_id = Auth::user()->role;
    //         $insertQRY = array('draft_id'=>$draft_id,'scheme_id'=>$scheme_id,'dept_id'=>$dept_id,'subject'=>$subject,'description'=>$subject,'description'=>$description,'chairperson'=>$chairperson,'attendees'=>$attendees,'date'=>$date,'time'=>$time, 'venue'=>$venue, 'filename'=>$flname,'created_at'=>$created_at,'user_id'=>$user_id,'user_role_id'=>$user_role_id);
    //         if(isset($rev)) {
    //             $result = Attachment::where('couch_doc_id',$doc_id)->update(['couch_rev_id'=>$rev]);
    //             if($result) {
    //                 Meetinglog::insert($insertQRY);

	// 				// Meeting Notification start
    //                 $mid = Meetinglog::orderBy('mid','desc')->take(1)->value('mid');
    //                 $notify_users = User::select('id','role')->whereIn('id',$attendees_users)->orWhereIn('role',[14,5,6,7])->get();
    //                 foreach($notify_users as $us) {
    //                     $user_id = $us->id;
    //                     $user_role = $us->role;
    //                     $meetingviewed = array('user_id'=>$user_id,'user_role'=>$user_role,'mid'=>$mid,'study_id'=>$draft_id);
    //                     MeetingsViewedBy::create($meetingviewed);
    //                 }
	// 				// Meeting Notification end
    //                 $act['userid'] = Auth::user()->id;
    //                 $act['ip'] = $request->ip();
    //                 $act['activity'] = 'Meeting added by Eval Director';
    //                 $act['officecode'] = $dept_id;
    //                 $act['pagereferred'] = $request->url();
    //                 Activitylog::insert($act);
    //                 return redirect()->back()->withSuccess('Email Sent & Meeting Schedule successfully Created !');
    //             } else {
    //                 return redirect()->back()->withError('Error: Meeting 402, Contact NIC.');
    //             }
    //         } else {
    //             return redirect()->back()->withError('Error: Meeting 402, Contact NIC.');
    //         }

    //         return redirect()->back();
    //     }
    // }

    // public function postpone_meeting(Request $request) {
    //     $validate = Validator::make($request->all(),[
    //         'postpone_mid' => 'required|numeric',
    //         'postpone_date' => 'required|date',
    //         'postpone_time' => 'required|date_format:H:i'
    //     ]);
    //     if($validate->fails($validate->errors())) {
    //         return redirect()->back()->withInput()->withErrors($validate->errors());
    //     } else {
    //         $mid = $request->get('postpone_mid');
    //         $date = $request->get('postpone_date');
    //         $time = $request->get('postpone_time');
    //         $thetime = array('date'=>$date,'time'=>$time);
    //         Meetinglog::where('mid',$mid)->update($thetime);

    //         $data = Meetinglog::where('mid',$mid)->get();
    //         $detaildata = $data->toArray();
    //         $dept_id = 0;
    //         if(count($detaildata) > 0) {
    //             $details = $detaildata[0];
    //             $scheme_id = $detaildata[0]['draft_id'];
    //             $scheme_name = Scheme::where('scheme_id',$scheme_id)->value('scheme_name');
    //             $details['venue'] = Meetinglog::where('draft_id',$scheme_id)->value('venue');
    //             $details['scheme_name'] = $scheme_name;
    //             $attendees = array();
    //                 $att = json_decode($details['attendees'],true);
    //                 foreach($att as $attkey=>$attval) {
    //                     $attendees[] = $attval;
    //                 }
          
    //             $dept_id = $detaildata[0]['dept_id'];
    //             if(count($attendees)) {
    //                 $attendees_ids = $attendees[0];
    //                 $attendees_emails = User::whereIn('id',$attendees_ids)->pluck('email');
    //             }
    //         }
    //         $act['userid'] = Auth::user()->id;
    //         $act['ip'] = $request->ip();
    //         $act['activity'] = 'Meeting updated by Eval Director';
    //         $act['officecode'] = $dept_id;
    //         $act['pagereferred'] = $request->url();
    //         Activitylog::insert($act);
    //         return redirect()->back()->with('postpone_msg','Meeting postponed successfully !!!!!');
    //     }
    // }

    // public function finishmeeting(Request $request) {
    //     $mid = $request->get('mid');
     
    //     $fmeeting = Meetinglog::leftjoin('itransaction.proposals','meetinglogs.draft_id','=','proposals.draft_id')
    //                     ->leftjoin('imaster.departments','departments.dept_id','=','meetinglogs.dept_id')
    //                     ->where('meetinglogs.mid',$mid)
    //                     ->get();
    //     $attendees_table_id = User::select('id')->where('role','3')->orWhere('role','4')->get();
    //     $att_arr = array();
    //     if($attendees_table_id->isNotEmpty()) {
    //         $attendees_toarray = $attendees_table_id->toArray();
    //         foreach($attendees_toarray as $at_tokey => $at_toval) {
    //             $att_arr[] = $at_toval['id'];
    //         }
    //     }
    //     $attendees_ids = array();
    //     if($fmeeting->isNotEmpty()) {
    //         $ss = json_decode($fmeeting[0]['attendees']);
    //         foreach($ss->users as $skey=>$sval) {
    //             if(in_array($sval,$att_arr)) {
    //                 $attendees_ids[] = $sval;
    //             }
    //         }
    //     }
       
    //     $attendees = User::select('id','name')->whereIn('id',$attendees_ids)->get();
    //     $arr = array('mid'=>$mid,'fmeeting'=>$fmeeting,'attendees'=>$attendees);
    //     return response()->json($arr);
    // }

    // public function updatemeeting(Request $request) {
    //     $validate = Validator::make($request->all(),[
    //         'mid' => 'required|numeric',
    //         'draft_id' => 'required|numeric',
    //         'subject' => 'required|string|max:100',
    //         'description' => 'required|string|max:990',
    //         'chairperson' => 'required|string|max:100',
    //         'date' => 'required|date',
    //         'time' => 'required|date_format:H:i:s',
    //         'venue' => 'required|string',
    //         'attendees' => 'required',
    //         'meeting_minutes' => 'required|mimes:pdf,docx,xlsx',
    //         'meeting_attendance' => 'required|mimes:pdf,docx,xlsx'
    //     ]);
    //     if($validate->fails()) {
    //         return redirect()->back()->withInput()->withErrors($validate->errors());
    //     } else {
    //         $mid = $request->input('mid');
    //         $draft_id = $request->input('draft_id');
    //         $dept_id = Proposal::where('draft_id',$draft_id)->value('dept_id');
    //         $scheme_id = Proposal::where('draft_id',$draft_id)->value('scheme_id');
    //         $scheme_name = Scheme::where('scheme_id',$scheme_id)->value('scheme_name');
    //         $subject = $request->input('subject');
    //         $description = $request->input('description');
    //         $chairperson = $request->input('chairperson');
    //         $date = $request->input('date');
    //         $time = $request->input('time');
    //         $venue = $request->input('venue');
    //         $created_at = Carbon::now();
    //         $attendees = json_encode(array('users'=>$request->input('attendees')));
            

    //         $attendees_emails = User::whereIn('id',$request->input('attendees'))->pluck('email');

    //         $details = array('filesare'=>'multiple','draft_id'=>$draft_id,'scheme_id'=>$scheme_id,'dept_id'=>$dept_id,'scheme_name'=>$scheme_name,'subject'=>$subject,'description'=>$subject,'description'=>$description,'venue'=>$venue,'chairperson'=>$chairperson,'attendees'=>$attendees,'date'=>$date,'time'=>$time, 'mid'=>$mid, 'created_at'=>$created_at);
    //         $flname = array();
    //         $path = array();
    //         $i = 0;
    //         $details['filename'] = array();
    //         if($request->hasFile('meeting_minutes')) {
    //             $meeting_minutes = $request->file('meeting_minutes');
    //             $rev = '';
    //             $doc_id= 'scheme_'.$scheme_id;
    //             $extended = new Couchdb();
    //             $extended->InitConnection();
    //             $status = $extended->isRunning();
    //             $file = Attachment::where('couch_doc_id',$doc_id)->first();
    //             $file_data = json_decode($file,true);
    //             $rev = $file_data['couch_rev_id'];
    //             $path['id'] = $doc_id;
    //             $path['tmp_name'] = $meeting_minutes->getRealPath();
    //             $path['extension']  = $meeting_minutes->getClientOriginalExtension();
    //             $path['name'] = $doc_id.'_'.'meeting_minutes_'.$mid.'_aftermeeting'.'.'.$path['extension'];
    //             $flname[] = $path['name'];
    //             $details['filename'][$i] = $path['name'];

    //             $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
    //             $array = json_decode($out, true);
    //             if(array_key_exists('error',$array)) {
    //                 echo 'rev error <br>';
    //                 $rev = '';
    //                 print_r($array);
    //                 die();
    //             } else {
    //                 $rev = $array['rev'];
    //                 $result = Attachment::where('couch_doc_id',$doc_id)->update(['couch_rev_id'=>$rev]);
    //             }
    //             $i++;
    //         }
    //         if($request->hasFile('meeting_attendance')) {
    //             $meeting_attendance = $request->file('meeting_attendance');
    //             $rev = '';
    //             $doc_id= 'scheme_'.$scheme_id;
    //             $extended = new Couchdb();
    //             $extended->InitConnection();
    //             $status = $extended->isRunning();
    //             $file = Attachment::where('couch_doc_id',$doc_id)->first();
    //             $file_data = json_decode($file,true);
    //             $rev = $file_data['couch_rev_id'];
    //             $path['id'] = $doc_id;
    //             $path['tmp_name'] = $meeting_attendance->getRealPath();
    //             $path['extension']  = $meeting_attendance->getClientOriginalExtension();
    //             $path['name'] = $doc_id.'_'.'meeting_attendance_'.$mid.'_aftermeeting'.'.'.$path['extension'];
    //             $flname[] = $path['name'];
    //             $details['filename'][$i] = $path['name'];

    //             $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
    //             $array = json_decode($out, true);
    //             if(array_key_exists('error',$array)) {
    //                 echo 'rev error <br>';
    //                 $rev = '';
    //                 print_r($array);
    //                 die();
    //             } else {
    //                 $rev = $array['rev'];
    //                 $result = Attachment::where('couch_doc_id',$doc_id)->update(['couch_rev_id'=>$rev]);
    //             }
    //             $i++;
    //         }
    //         $theflnames = implode(',',$flname);
    //         $insertQRY = array('draft_id'=>$draft_id, 'scheme_id'=>$scheme_id, 'dept_id'=>$dept_id, 'subject'=>$subject, 'description'=>$subject, 'description'=>$description, 'chairperson'=>$chairperson, 'attendees'=>$attendees, 'date'=>$date, 'time'=>$time, 'venue'=>$venue, 'mid'=>$mid, 'filename'=>$theflnames, 'created_at'=>$created_at);
    //         if(isset($rev)) {
    //             Aftermeeting::insert($insertQRY);
    //             Meetinglog::where('mid',$mid)->update(['status'=>'1']);
    //             $act['userid'] = Auth::user()->id;
    //             $act['ip'] = $request->ip();
    //             $act['activity'] = 'Meeting conducted by Eval Director';
    //             $act['officecode'] = $dept_id;
    //             $act['pagereferred'] = $request->url();
    //             Activitylog::insert($act);
    //             return redirect()->back()->withSuccess('Email Sent & Meeting Schedule successfully Created !');
    //         } else {
    //             return redirect()->back()->withError('Error: Meeting 402, Contact NIC.');
    //         }
    //     }
    //     return redirect()->back();
    // }

    // public function currentmeetingevents(Request $request) {
    //     $date = date('Y-m-01');
    //     $lastdatedays = date("t",strtotime($date));
    //     $month = date('m');
    //     $lastday = date("Y-$month-$lastdatedays");
    //     $between = array($date,$lastday);
    //     $meetings = Meetinglog::select('subject','date','time')->whereBetween('date',$between)->get();
    //     $meetingsare = array();
    //     foreach($meetings as $m => $k) {
    //         $datetime = $k->date.' '.$k->time;
    //         $meetingsare[] = array('subject'=>$k->subject,'datetime'=>strtotime($datetime));
    //     }
    //     return response()->json($meetingsare);
    // }

    // public function nextmeetingevents(Request $request) {
    //     $month = date('m') + 1;
    //     if($month < 10) {
    //         $month = '0'.$month;
    //     }
    //     $date = date("Y-$month-01");
    //     $last_date = date("Y-m-t",strtotime($date));
    //     $between = array($date,$last_date);
        
    //     $meetings = Meetinglog::select('subject','date','time')->whereBetween('date',$between)->get();
    //     $meetingsare = array();
    //     foreach($meetings as $m => $k) {
    //         $datetime = $k->date.' '.$k->time;
    //         $meetingsare[] = array('subject'=>$k->subject,'datetime'=>strtotime($datetime));
    //     }
    //     return response()->json($meetingsare);
    // }

   

    // public function listfinishedmeetings() {
    //     $schemes = Scheme::orderBy('scheme_id','desc')->get();
    //     $departments = Department::orderBy('dept_name','asc')->get();
    //     $meetings = Meetinglog::orderBy('mid','desc')->get();
    //     $attendees = User::select('id','name')->where('role','3')->orWhere('role','4')->get();
    //     $implementations = Implementation::all();
    //     $aftermeeting = Aftermeeting::orderBy('amid','desc')->get();
    //     $replace_url = $this->replace_url;
    //     $aftermeetings = Aftermeeting::join('itransaction.meetinglogs','aftermeeting.mid','=','meetinglogs.mid')->orderBy('aftermeeting.amid','desc')->get();
    //     return view('dashboards.eva-dir.finishedmeetings',compact('aftermeeting','meetings','schemes','departments','attendees','implementations','replace_url'));
    // }

    // public function timeline() {
    //     $status = Status::join('itransaction.status_log','status.status_id','=','status_log.sid')->orderBy('status.status_id','desc')->get();
    //     $study = SchemeSend::select('scheme_send.dept_id','scheme_send.draft_id','scheme_send.user_id', 'scheme_send.remarks','scheme_send.created_by as scheme_by','scheme_send.created_at as scheme_created_at','scheme_send.updated_at as scheme_updated_at','scheme_send.updated_by as scheme_updated_by','proposals.scheme_name','status.status_id as status_table_id','status.status_name','status.s_level')
    //                         ->join('itransaction.proposals','scheme_send.draft_id','=','proposals.draft_id')
    //                         ->join('imaster.status','scheme_send.status_id','=','status.status_id')
    //                         ->orderBy('scheme_send.id','desc')
    //                         ->get();
    //     return view('dashboards.eva-dir.timeline',compact('status','study'));
    // }

    // public function teams() {
    //     $study = Proposal::distinct()->select('proposals.draft_id','proposals.scheme_name')->rightjoin('itransaction.scheme_send','proposals.draft_id','=','scheme_send.draft_id')->where('scheme_send.status_id','25')->orderBy('proposals.scheme_name')->get();
    //     $teams = Teams::orderBy('team_id','desc')->get();
    //     $study_names = array();
    //     foreach($teams as $tms) {
    //         $study_names[] = Proposal::where('draft_id',$tms->study_id)->value('scheme_name');
    //     }
    //     $dd = User::select('id','name')->where('role','3')->get();
    //     $dd_array = $dd->toArray();
    //     $ro = User::select('id','name')->where('role','4')->get();
    //     $ro_array = $ro->toArray();
    //     return view('dashboards.eva-dir.teams',compact('teams','study','dd','dd_array','ro','ro_array','study_names'));
    // }

    // public function teamcount(Request $request) {
    //     $validate = Validator::make($request->all(),[
    //         'study_id' => 'required|numeric',
    //         'the_team_member_id' => 'nullable|numeric'
    //     ]);
    //     if($validate->fails()) {

    //     } else {
    //         $study_id = $request->input('study_id');
    //         $cur_year = date('y');
    //         $next_year = $cur_year + 1;
    //         $cur_date = date('Y-01-01 00:00:00');
    //         $last_date = date('Y-12-31 23:59:59');
    //         $date_between = array($cur_date,$last_date);
    //         if($request->input('the_team_member_id')) {
    //             $teams = Teams::where('study_id',$study_id)->whereBetween('created_at',$date_between)->count();
    //         } else {
    //             $teams = Teams::where('study_id',$study_id)->whereBetween('created_at',$date_between)->count() + 1;
    //         }
    //         $team_name = 'Project_'.$cur_year.'_'.$next_year.'_'.$teams;
    //         return response()->json($team_name);
    //     }
    // }

    // public function addteam(Request $request) {
    //     $validate = Validator::make($request->all(),[
    //         'team_name' => 'required|string|max:100',
    //         'study_id' => 'required|numeric',
    //         'team_member_dd' => 'required|array',
    //         'team_member_ro' => 'required|array'
    //     ]);
    //     if($validate->fails()) {
    //         return redirect()->back()->withInput()->withErrors($validate->errors());
    //     } else {
    //         $teamname = $request->input('team_name');
    //         $study_id = $request->input('study_id');
            
    //         $team_dd_ar = $request->input('team_member_dd');
    //         $dd_leader = array_shift($team_dd_ar);
    //         $team_dd_arr = '';
           
    //         $team_dd_arr = json_encode($request->input('team_member_dd'));
           
    //         $team_ro_ar = $request->input('team_member_ro');
    //         $ro_leader = array_shift($team_ro_ar);
         
    //         $team_ro_arr = json_encode($request->input('team_member_ro'));
           
    //         $team_dd = array();
           
    //         $arr = array('team_name'=>$teamname, 'study_id'=>$study_id, 'active'=>'Y', 'team_member_dd'=>$team_dd_arr, 'team_member_ro'=>$team_ro_arr, 'dd_leader'=>$dd_leader, 'ro_leader'=>$ro_leader);
    //         $act['userid'] = Auth::user()->id;
    //         $act['ip'] = $request->ip();
    //         $act['activity'] = 'Meeting conducted by Eval Director';
    //         $act['officecode'] = '8';
    //         $act['pagereferred'] = $request->url();
    //         Activitylog::insert($act);
    //         Teams::insert($arr);
    //         $last_team = Teams::orderBy('team_id','desc')->take(1)->get();
    //         $the_team = array();
    //         foreach($last_team as $kt => $vt) {
    //             $study_name = Proposal::where('draft_id',$vt->study_id)->value('scheme_name');
    //             $dd_leader = User::where('id',$vt->dd_leader)->value('name');
    //             $ro_leader = User::where('id',$vt->ro_leader)->value('name');
    //             $dd_members = array();
    //             if($vt->team_member_dd) {
    //                 $dd_members_are = json_decode($vt->team_member_dd);
    //                 foreach($dd_members_are as $dk => $dv) {
    //                     $the_dd = User::where('id',$dv)->value('name');
    //                     $dd_members[] = $the_dd;
    //                 }
    //             }
    //             if($vt->team_member_ro) {
    //                 $ro_members_are = json_decode($vt->team_member_ro);
    //                 foreach($ro_members_are as $rk => $rv) {
    //                     $the_ro = User::where('id',$rv)->value('name');
    //                     $ro_members[] = $the_ro;
    //                 }
    //             }
    //             $the_team[] = array('study_name'=>$study_name,'team_name'=>$vt->team_name,'dd_leader'=>$dd_leader,'ro_leader'=>$ro_leader,'dd_members'=>$dd_members,'ro_members'=>$ro_members);
    //         }
    //         return response()->json($the_team);
    //     }
    // }

    // public function editteam(Request $request) {
    //     $id = $request->input('id');
    //     $teams = Teams::where('team_id',$id)->orderBy('team_id','desc')->get();
    //     $study = Proposal::distinct()->select('proposals.draft_id','proposals.scheme_name')->join('itransaction.scheme_send','proposals.draft_id','=','scheme_send.draft_id')->where('scheme_send.status_id','25')->orderBy('proposals.scheme_name')->get();
    //     $dd = User::select('id','name')->where('role','3')->get();
    //     $ro = User::select('id','name')->where('role','4')->get();
    //     $ro_array = $ro->toArray();
    //     $arr = array('teams'=>$teams,'study'=>$study,'dd'=>$dd,'ro'=>$ro,'ro_array'=>$ro_array);
    //     return response()->json($arr);
    // }

    // public function updateteam(Request $request) {
    //     $validate = Validator::make($request->all(),[
    //         'the_team_member_id' => 'required|numeric',
    //         'team_name' => 'required|string|max:100',
    //         'study_id' => 'required|numeric',
    //         'team_member_dd' => 'required|array',
    //         'team_member_ro' => 'required|array'
    //     ]);
    //     if($validate->fails()) {
    //         return redirect()->back()->withInput()->withErrors($validate->errors());
    //     } else {
    //         $team_id = $request->input('the_team_member_id');
    //         $teamname = $request->input('team_name');
    //         $study_id = $request->input('study_id');
    //         $dd_members = $request->input('team_member_dd');
    //         $ro_members = $request->input('team_member_ro');
    //         $dds = Teams::where('team_id',$team_id)->value('team_member_dd');
    //         $ros = Teams::where('team_id',$team_id)->value('team_member_ro');
    //         $dds = json_decode($dds);
    //         $ros = json_decode($ros);
    //         $former_dds = array();
    //         if($dds != '') {
    //             foreach($dds as $ddm) {
    //                 if(!in_array($ddm,$dd_members)) {
    //                     $former_dds[] = $ddm;
    //                 }
    //             }
    //         }
    //         $former_dds_are = array();
    //         if(!empty($former_dds)) {
    //             $former_dds_are = json_encode($former_dds);
    //         }
    //         $former_ros = array();
    //         if($ros != '') {
    //             foreach($ros as $rom) {
    //                 if(in_array($rom,$ro_members)) {
    //                     $former_ros[] = $rom;
    //                 }
    //             }
    //         }
    //         $former_ros_are = array();
    //         if(!empty($former_ros)) {
    //             $former_ros_are = json_encode($former_ros);
    //         }
    //         $formers = array('study_id'=>$study_id,'dd_ids'=>$former_dds_are,'ro_ids'=>$former_ros_are);
    //         DB::table('itransaction.former_ro_dd')->insert($formers);
    //         $team_member_ro = json_encode($request->input('team_member_ro'));
    //         $team_member_dd = json_encode($request->input('team_member_dd'));
    //         $ro_leader = Teams::where('team_id',$team_id)->value('ro_leader');
    //         $dd_leader = Teams::where('team_id',$team_id)->value('dd_leader');
           
    //         $arr = array('team_name'=>$teamname, 'study_id'=>$study_id, 'active'=>'Y', 'team_member_dd'=>$team_member_dd, 'team_member_ro'=>$ro_members);
    //         Teams::where('team_id',$team_id)->update($arr);
    //         $act['userid'] = Auth::user()->id;
    //         $act['ip'] = $request->ip();
    //         $act['activity'] = 'Meeting conducted by Eval Director';
    //         $act['officecode'] = '8';
    //         $act['pagereferred'] = $request->url();
    //         Activitylog::insert($act);
    //         return redirect()->back();
    //     }
    // }

    // public function approveproposals() {
    //           $study_ids = Eval_activity_status::distinct('study_id')->pluck('study_id');
    //     $draft_ids = SchemeSend::distinct('draft_id')->where('status_id','25')->pluck('draft_id');
    //     $proposals = SchemeSend::distinct('scheme_send.draft_id')->select('scheme_send.draft_id','proposals.scheme_name','proposals.scheme_overview','proposals.scheme_objective','proposals.remarks','proposals.created_at as submission_date','scheme_send.created_at as approved_date')->rightjoin('itransaction.proposals','scheme_send.draft_id','=','proposals.draft_id')->whereIn('scheme_send.draft_id',$draft_ids)->whereIn('scheme_send.draft_id',$study_ids)->get();
    //     return view('dashboards.eva-dir.approvedproposals',compact('proposals'));
    // }

    // public function approveproposal(Request $request) {
    //     $draft_id = $request->input('id');
    //     Eval_activity_status::where('study_id',$draft_id)->update(['approval'=>1]);
    //     return response()->json('approved');
    // }

    // public function get_prop_status(Request $request) {
    //     $id = $request->input('id');
    //     $status_ids = Eval_activity_status::where('study_id',$id)->pluck('status_id');
    //     $eval_activities = Eval_activity_log::whereIn('id',$status_ids)->get();
    //     $eval_options = Eval_activity_log::skip(2)->whereNotIn('id',$status_ids)->orderBy('id')->get();
    //     $arr = array('eval_activities'=>$eval_activities,'eval_options'=>$eval_options);
    //     return response()->json($arr);
    // }

    // public function searchproposals(Request $request) {
    //     $prop = $request->get('search');
    //     $data = Proposal::select('scheme_name','draft_id')->where('scheme_name','like',$prop.'%')->take(1)->orderBy('draft_id','desc')->get();
    //     $the_data = array();
    //     foreach($data as $key=>$value) {
    //         $the_data[] = array('value'=>$value->scheme_name,'label'=>$value->scheme_name);
    //     }
       
    //     return response()->json($the_data);
    // }

    // public function get_scheme_detail(Request $request) {
    //     $prop_name = $request->get('prop_name');
    //     $prop = Proposal::where('scheme_name','like',$prop_name.'%')->get();
    //     return response()->json($prop);
    // }

    // public function activities() {
    //     $activities = Eval_activity_log::orderBy('id','desc')->get();
    //     return view('dashboards.eva-dir.activities',compact('activities'));
    // }

    // public function getactivityonmodal(Request $request) {
    //     $id = $request->get('id');
    //     $activity = Eval_activity_log::where('id',$id)->get();
    //     return response()->json($activity);
    // }

    // public function updateactivity(Request $request) {
    //     $id = $request->get('activity_id');
    //     $activity_name = $request->get('activity_name');
    //     $sub_activity_name = $request->get('sub_activity_name');
    //     $arr = array('activity_name'=>$activity_name, 'sub_activity_name'=>$sub_activity_name);
    //     Eval_activity_log::where('id',$id)->update($arr);
    //     return redirect()->back()->with('actlog_update','Activity update successfull');
    // }

    // public function pendingproposals() {
    //     $study_ids = Eval_activity_status::distinct('study_id')->pluck('study_id');
    //     $draft_ids = SchemeSend::distinct('draft_id')->where('status_id','25')->pluck('draft_id');
    //     $pending = SchemeSend::distinct('scheme_send.draft_id')->select('scheme_send.draft_id','proposals.scheme_name','proposals.scheme_overview','proposals.scheme_objective','proposals.remarks')->rightjoin('itransaction.proposals','scheme_send.draft_id','=','proposals.draft_id')->whereNotIn('scheme_send.draft_id',$draft_ids)->whereNotIn('scheme_send.draft_id',$study_ids)->get();
    //     return view('dashboards.eva-dir.pendingproposals',compact('pending'));
    // }

    // public function reportmodule() {
    //     $status_study_ids = Eval_activity_status::distinct('study_id')->where('approval','1')->pluck('study_id');
    //     $team_study_ids = Teams::whereIn('study_id',$status_study_ids)->orderBy('team_id','desc')->pluck('study_id');
    //     $studies = Proposal::select('draft_id','scheme_name')->whereIn('draft_id',$team_study_ids)->orderBy('scheme_name')->get();
    //     return view('dashboards.eva-dir.report_module',compact('studies'));
    // }

    // public function getreportmodule(Request $request) {
    //     $id = $request->get('id');
    //     $study = Eval_activity_status::select('eval_activity_status.id as act_id', 'eval_activity_status.status_id as act_status_id', 'eval_activity_status.user_id as act_user_id', 'eval_activity_status.study_id', 'eval_activity_status.created_at as act_date', 'proposals.*')->join('itransaction.proposals','eval_activity_status.study_id','=','proposals.draft_id')->where('proposals.draft_id',$id)->where('eval_activity_status.current_status','1')->orderBy('id','desc')->take(1)->get();
    //     $gad_approval_date_value = Eval_activity_status::where('eval_activity_status.study_id',$id)->orderBy('id','asc')->take(1)->value('created_at');
    //     $gad_approval_date = '';
    //     if($gad_approval_date_value != '') {
    //         $gad_approval_date = date('d F, Y',strtotime($gad_approval_date_value));
    //     }
    //     $project_team_date_val = Teams::where('study_id',$id)->orderBy('team_id','desc')->take(1)->value('created_at');
    //     $project_team_date = '';
    //     if($project_team_date_val) {
    //         $project_team_date = date('d F, Y',strtotime($project_team_date_val));
    //     }
    //     $publication_date_value = Eval_activity_status_dates::where('study_id',$id)->where('status_id','32')->orderBy('id','desc')->value('created_at');
    //     $publication_date = '';
    //     if($publication_date_value != '') {
    //         $publication_date = date('d F, Y',strtotime($publication_date_value));
    //     }
      
    //     $ecc_date_value = Eval_activity_status_dates::where('study_id',$id)->where('status_id','25')->orderBy('id','desc')->value('created_at');
    //     $ecc_date = '';
    //     if($ecc_date_value != '') {
    //         $ecc_date = date('d F, Y',strtotime($ecc_date_value));
    //     }
    //     $pilot_testing_date_value = Eval_activity_status::where('status_id','16')->where('study_id',$id)->orderBy('id','desc')->take(1)->value('created_at');
    //     $pilot_testing_date = '';
    //     if($pilot_testing_date_value != '') {
    //         $pilot_testing_date = date('d F, Y',strtotime($pilot_testing_date_value));
    //     }
    //     $pilot_operation_start_date_value = Eval_activity_status::where('status_id','14')->where('study_id',$id)->orderBy('id','desc')->take(1)->value('created_at');
    //     $pilot_operation_start_date = '';
    //     if($pilot_operation_start_date_value != '') {
    //         $pilot_operation_start_date = date('d F, Y',strtotime($pilot_operation_start_date_value));
    //     }
    //     $proposal_date_value = Proposal::where('draft_id',$id)->value('created_at');
    //     $proposal_date = '';
    //     if($proposal_date_value != '') {
    //         $proposal_date = date('d F, Y',strtotime($proposal_date_value));
    //     }
    //     $proposal_returned_date_value = Eval_activity_status::where('status_id','33')->where('study_id',$id)->orderBy('id','desc')->take(1)->value('created_at');
    //     $proposal_returned_date = '';
    //     if($proposal_returned_date_value != '') {
    //         $proposal_returned_date = date('d F, Y',strtotime($proposal_returned_date_value));
    //     }


    //     $report_data = Communication::where('study_id',$id)->orderBy('id','desc')->take(1)->get();
    //     $dateofapproval_in_ecc = Eval_activity_status::where('study_id',$id)->where('status_id','25')->orderBy('id','asc')->take(1)->value('created_at');
    //     $dateofapproval_ecc = '';
    //     if($dateofapproval_in_ecc) {
    //         $dateofapproval_ecc = date('d F, Y',strtotime($dateofapproval_in_ecc));
    //     }
    //     $dec_date_made = Aftermeeting::where('draft_id',$id)->orderBy('amid')->take(1)->value('created_at');
    //     $dec_date = '';
    //     if($dec_date_made) {
    //         $dec_date = date('d F, Y',strtotime($dec_date_made));
    //     }
    //     $ecc_date_found = Meetinglog::where('draft_id',$id)->orderBy('mid','desc')->take(1)->value('created_at');
    //     $ecc_meeting_date = '';
    //     if($ecc_date_found) {
    //         $ecc_meeting_date = date('d F, Y',strtotime($ecc_date_found));
    //     }

    //     $date_field_operation_start_value = Eval_activity_status::where('status_id','20')->where('study_id',$id)->orderBy('id','desc')->take(1)->value('created_at');
    //     $date_field_operation_start = '';
    //     if($date_field_operation_start_value != '') {
    //         $date_field_operation_start = date('d F, Y',strtotime($date_field_operation_start_value));
    //     }
    //     $date_field_operation_done_value = Eval_activity_status::where('status_id','21')->where('study_id',$id)->orderBy('id','desc')->take(1)->value('created_at');
    //     $date_field_operation_done = '';
    //     if($date_field_operation_done_value != '') {
    //         $date_field_operation_done = date('d F, Y',strtotime($date_field_operation_done_value));
    //     }

    //     $hod_name = Proposal::where('draft_id',$id)->value('hod_name');

    //     $date_send_report_to_dept_value = Eval_activity_status::where('status_id','28')->where('study_id',$id)->orderBy('id','desc')->take(1)->value('created_at');
    //     $date_send_report_to_dept = '';
    //     if($date_send_report_to_dept_value != '') {
    //         $date_send_report_to_dept = date('d F, Y',strtotime($date_send_report_to_dept_value));
    //     }

    //     $date_get_report_from_office_value = Eval_activity_status::where('status_id','18')->where('study_id',$id)->orderBy('id','desc')->take(1)->value('created_at');
    //     $date_get_report_from_office = '';
    //     if($date_get_report_from_office_value != '') {
    //         $date_get_report_from_office = date('d F, Y',strtotime($date_get_report_from_office_value));
    //     }

    //     $study_design_approved_date_value = Eval_activity_status::where('status_id','17')->where('study_id',$id)->orderBy('id','desc')->take(1)->value('created_at');
    //     $study_design_approved_date = '';
    //     if($study_design_approved_date_value != '') {
    //         $study_design_approved_date = date('d F, Y',strtotime($study_design_approved_date_value));
    //     }
        
    //     $study_design_sending_date_value = Eval_activity_status::where('status_id','19')->where('study_id',$id)->orderBy('id','desc')->take(1)->value('created_at');
    //     $study_design_sending_date = '';
    //     if($study_design_sending_date_value != '') {
    //         $study_design_sending_date = date('d F, Y',strtotime($study_design_sending_date_value));
    //     }

    //     $date_get_primary_info_value = Eval_activity_status::where('status_id','10')->where('study_id',$id)->orderBy('id','desc')->take(1)->value('created_at');
    //     $date_get_primary_info = '';
    //     if($date_get_primary_info_value != '') {
    //         $date_get_primary_info = date('d F, Y',strtotime($date_get_primary_info_value));
    //     }

    //     $date_start_statistical_analysis_value = Eval_activity_status::where('status_id','22')->where('study_id',$id)->orderBy('id','desc')->take(1)->value('created_at');
    //     $date_start_statistical_analysis = '';
    //     if($date_start_statistical_analysis_value != '') {
    //         $date_start_statistical_analysis = date('d F, Y',strtotime($date_start_statistical_analysis_value));
    //     }

    //     $date_start_report_writing_value = Eval_activity_status::where('status_id','26')->where('study_id',$id)->orderBy('id','desc')->take(1)->value('created_at');
    //     $date_start_report_writing = '';
    //     if($date_start_report_writing_value != '') {
    //         $date_start_report_writing = date('d F, Y',strtotime($date_start_report_writing_value));
    //     }

        

    //     $arr = array('study'=>$study,'publication_date'=>$publication_date,'dec_date'=>$dec_date,'gad_approval_date'=>$gad_approval_date,'project_team_date'=>$project_team_date,'pilot_testing_date'=>$pilot_testing_date,'proposal_date'=>$proposal_date,'proposal_returned_date'=>$proposal_returned_date,'ecc_date'=>$ecc_date,'report_data'=>$report_data,'dateofapproval_ecc'=>$dateofapproval_ecc,'ecc_meeting_date'=>$ecc_meeting_date,'pilot_operation_start_date'=>$pilot_operation_start_date,'date_field_operation_start'=>$date_field_operation_start,'date_field_operation_done'=>$date_field_operation_done,'hod_name'=>$hod_name,'date_send_report_to_dept'=>$date_send_report_to_dept,'date_get_report_from_office'=>$date_get_report_from_office,'study_design_approved_date'=>$study_design_approved_date,'study_design_sending_date'=>$study_design_sending_date,'date_get_primary_info'=>$date_get_primary_info,'date_start_statistical_analysis'=>$date_start_statistical_analysis,'date_start_report_writing'=>$date_start_report_writing);
    //     return response()->json($arr);
    // }

    // public function current_calendar(Request $request) {
    //     $date = $request->get('date');
    //     $curdate = Date('Y/m/t');
    //     $date_between = array($date,$curdate);
    //     $meetings = Meetinglog::select('date','time','subject')->whereBetween('date',$date_between)->orderBy('date','asc')->get();
    //     $allmeetings = array();
    //     foreach($meetings as $m) {
    //         $datetime = date('d F',strtotime($m->date));
    //         $allmeetings[] = array('startDate'=>$m->time,'endDate'=>$datetime,'summary'=>$m->subject);
    //     }
    //     return response()->json($allmeetings);
    // }

    // public function next_calendar(Request $request) {
    //     $date = $request->get('date');
    //     $curdate = Date('Y-m-t',strtotime($date));
    //     $date_between = array($date,$curdate);
    //     $meetings = Meetinglog::select('date','time','subject')->whereBetween('date',$date_between)->orderBy('date','asc')->get();
    //     $allmeetings = array();
    //     foreach($meetings as $m) {
    //         $datetime = date('d F',strtotime($m->date));
    //         $allmeetings[] = array('startDate'=>$m->time,'endDate'=>$datetime,'summary'=>$m->subject);
    //     }
    //     return response()->json($allmeetings);
    // }
	
    // public function communication() {
    //     $get_study_ids = Eval_activity_status::distinct('study_id')->pluck('study_id');
    //     $studies = Proposal::select('draft_id','scheme_name')->whereIn('draft_id',$get_study_ids)->orderBy('scheme_name')->get();
    //     $topics = CommunicationTopics::orderBy('id','desc')->get();
    //     $communication = Communication::select('communication.*','proposals.scheme_name','communication_topics.topic as topic_name', 'departments.dept_name')->leftjoin('itransaction.proposals','proposals.draft_id','=','communication.study_id')->leftjoin('imaster.communication_topics','communication.topic_id','=','communication_topics.id')->orderBy('communication.id','desc')->leftjoin('imaster.departments','communication.dept_id','=','departments.dept_id')->get();
    //     $communication_arr = array();
    //     foreach($communication as $key => $val) {
    //         $study_id = $val->study_id;
    //         $topic_id = '';
    //         if($val->topic_id != 0) {
    //             $topic_id = $val->topic_id;
    //         }
    //         $topic_text = $val->topic_text;
    //         $remarks = $val->remarks;
    //         $document = $val->document;
    //         $document_count = $this->getthefilecount($val->scheme_id,$document);
    //         $file_type = '';
    //         if($document_count > 0) {
    //             $file_type = pathinfo($document, PATHINFO_EXTENSION);
    //         }
    //         $dept_id = $val->dept_id;
    //         $scheme_id = $val->scheme_id;
    //         $scheme_name = $val->scheme_name;
    //         $topic_name = $val->topic_name;
    //         $dept_name = $val->dept_name;
    //         $date = date('d F, Y',strtotime($val->created_at));
    //         $arr = array('study_id'=>$study_id,'topic_id'=>$topic_id,'topic_text'=>$topic_text,'remarks'=>$remarks,'document_count'=>$document_count,'document'=>$document,'dept_id'=>$dept_id,'scheme_id'=>$scheme_id,'scheme_name'=>$scheme_name,'topic_name'=>$topic_name,'dept_name'=>$dept_name,'file_type'=>$file_type,'date'=>$date);
    //         $communication_arr[] = $arr;
    //     }
    //     return view('dashboards.eva-dir.communication',compact('topics','studies','communication_arr'));
    // }

    // public function addcommunication(Request $request) {
    //     $validate = Validator::make($request->all(),[
    //         'study_id' => 'required|numeric',
    //         'topic_id' => 'nullable|numeric',
    //         'topic_text' => 'nullable|string',
    //         'remarks' => 'required|string|max:500',
    //         'document' => 'nullable|mimes:docx,xlsx,pdf'
    //     ]);
    //     if($validate->fails()) {
    //         return response()->json('validation_error');
    //     } else {
    //         $study_id = $request->input('study_id');
    //         $scheme_id = Proposal::where('draft_id',$study_id)->value('scheme_id');
    //         $dept_id = Proposal::where('draft_id',$study_id)->value('dept_id');
    //         if($request->input('topic_id') != 0) {
    //             $topic_id = $request->input('topic_id');
    //         } else {
    //             $topic_id = '';
    //         }
    //         $user_id = Auth::user()->id;
    //         $user_role = Auth::user()->role;
    //         $commu = new Communication;
    //         $commu->study_id = $study_id;
    //         $commu->topic_id = $topic_id;
    //         $commu->topic_text = $request->input('topic_text');
    //         $commu->remarks = $request->input('remarks');
    //         $commu->dept_id = $dept_id;
    //         $commu->scheme_id = $scheme_id;
    //         $commu->user_id = $user_id;
    //         $commu->user_role = $user_role;
    //         $commu->save();
    //         $last_id = $commu->id;
    //         if($request->hasFile('document')) {
    //             $document = $request->file('document');
    //             $rev = Attachment::where('scheme_id',$scheme_id)->value('couch_rev_id');
    //             $extended = new Couchdb();
    //             $extended->InitConnection();
    //             $status = $extended->isRunning();
    //             $doc_id = "scheme_".$scheme_id;
    //             $docid = 'communication_userid_'.Auth::user()->id.'_role_'.Auth::user()->role.'_id_'.$last_id.'_file';
    //             $path['id'] = $docid;
    //             $path['tmp_name'] = $document->getRealPath();
    //             $path['extension']  = $document->getClientOriginalExtension();
    //             $path['name'] = $doc_id.'_'.$path['id'].'.'.$path['extension'];
    //             $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
    //             $array = json_decode($out, true);
    //             $rev = $array['rev'];
    //             if(isset($rev)) {
    //                 $result = Attachment::where('scheme_id',$scheme_id)->update(['couch_rev_id'=>$rev]);
    //             }
    //             $filename_exp = explode('scheme_'.$scheme_id.'_',$path['name']);
    //             $filename = $filename_exp[1];
    //             Communication::where('id',$last_id)->update(['document'=>$filename]);
    //         }
    //         $act['userid'] = Auth::user()->id;
    //         $act['ip'] = $request->ip();
    //         $act['activity'] = 'Communication Added by Eval Director';
    //         $act['officecode'] = Auth::user()->dept_id;
    //         $act['pagereferred'] = $request->url();
    //         Activitylog::insert($act);

    //         return response()->json('successfully_saved');
    //     }
    // }

    // public function changeviewed(Request $request) {
    //     $user_id = Auth::user()->id;
    //     $user_role = Auth::User()->role;
    //     $arr = array('viewed'=>'1');
    //     MeetingsViewedBy::where('user_id',$user_id)->where('user_role',$user_role)->update($arr);
    //     return response()->json('done');
    // }
 

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
             $act['userid'] = Auth::user()->id;
             $act['ip'] = $request->ip();
             $act['activity'] = 'Director Profile Information Update';
             $act['officecode'] = Auth::user()->dept_id;
             $act['pagereferred'] = $request->url();
             Activitylog::insert($act);
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
        $act['userid'] = Auth::user()->id;
        $act['ip'] = $request->ip();
        $act['activity'] = 'Director Profile Information Update';
        $act['officecode'] = Auth::user()->dept_id;
        $act['pagereferred'] = $request->url();
        Activitylog::insert($act);
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
     $act['userid'] = Auth::user()->id;
     $act['ip'] = $request->ip();
     $act['activity'] = 'Director Password change successfully.';
     $act['officecode'] = Auth::user()->dept_id;
     $act['pagereferred'] = $request->url();
     Activitylog::insert($act);
     if( !$update ){
         return response()->json(['status'=>0,'msg'=>'Something went wrong, Failed to update password in db']);
     }else{
         return response()->json(['status'=>1,'msg'=>'Your password has been changed successfully']);
     }
    }
}
    
    public function approvedProposal(Request $request){
            $id = Crypt::decrypt($request->id);
            $draft_id = Crypt::decrypt($request->draft_id);
            $dept_id = Crypt::decrypt($request->dept_id);
        if(!is_null($id) && (!is_null($draft_id)) && (!is_null($dept_id))){
            if(Auth::check() && Auth::user()->role_manage == 2 && Auth::user()->role == 24 ){
                Proposal::where('draft_id','=',$draft_id)->update(['status_id'=> 23]);
                SchemeSend::where([['dept_id', '=', $dept_id], ['draft_id', '=', $draft_id],['id','=',$id]])->update(['approved' => 3]);
            }else{
                Proposal::where('draft_id','=',$draft_id)->update(['status_id'=> 23]);
                SchemeSend::where([['dept_id', '=', $dept_id], ['draft_id', '=', $draft_id],['id','=',$id]])->update(['approved' => 1]);
            }
            return response()->json(['success'=>true]);
        }else{
            return response()->json(['error'=>false]);
        }
        $act['userid'] = Auth::user()->id;
        $act['ip'] = $request->ip();
        $act['activity'] = 'Director Approve Proposal';
        $act['officecode'] = Auth::user()->dept_id;
        $act['pagereferred'] = $request->url();
        Activitylog::insert($act);
    }
    
}

