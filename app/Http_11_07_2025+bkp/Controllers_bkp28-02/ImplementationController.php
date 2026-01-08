<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Implementation;
use Illuminate\Support\Facades\Auth;
use App\Models\SchemeSend;
use App\Models\Proposal;
use DB;
use URL;
use App\Models\User_user_role_deptid;
use App\Models\Department;
use App\Models\Stage;
use Session;
use App\Models\Sdggoals;
use App\Models\User;

class ImplementationController extends Controller {

    function index(){
       $this->get_count('proposals');
        
       $the_status_id = '23';
       $dept_id = Auth::user()->dept_id;
       $theforwarded = SchemeSend::select('id','draft_id','status_id')->where('status_id',$the_status_id)->where('dept_id',$dept_id)->orderBy('id','desc')->get()->toArray();
  
       //Ongoing 
       $ongoing_proposal = Stage::WhereNull('document')->get();
      
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

       //Completed 
       $complted_proposal = Stage::WhereNotNull('document')->get();
     
       return view('dashboards.implementations.proposals',compact('proposals_forwarded','complted_proposal','proposals_new','ongoing_proposal','proposals_returned','the_status_id','status_id_return'));
    }

    public function frwd(Request $request){
        $data = $request->all();

        $data['created_at']  =date('Y-m-d h:i:s');
        $d_id = $data['draft_id'];
        $s_id = $data['status_id'];
        $proposal = DB::table('itransaction.proposals')->where('draft_id','=',$d_id)->update(['status_id'=> $s_id]);
      
        $ins = SchemeSend::create($data);

        if($ins){
            $res = json_encode(['status=>1']);
        } else {
            $res = json_encode(['status=>0']);
        }
        return $res;
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
    public function test_proposallist() {
        $this->get_count('proposals');
        
        $the_status_id = '23';
        $dept_id = Auth::user()->dept_id;
        $theforwarded = SchemeSend::select('id','draft_id','status_id')->where('status_id',$the_status_id)->where('dept_id',$dept_id)->orderBy('id','desc')->get()->toArray();
   
        //Ongoing 
        $ongoing_proposal = Stage::WhereNull('document')->get();
       
        $forwarded_draft_ids = array();
        $statusid = 0;
        foreach($theforwarded as $kfor => $vfor) {
            $forwarded_draft_ids[] = $vfor['id'];
        }
       
        $proposals_forwarded = SchemeSend::leftJoin('itransaction.proposals', 'itransaction.scheme_send.draft_id', '=', 'itransaction.proposals.draft_id')
                                    ->select('scheme_send.*', 'proposals.draft_id as prop_draft_id', 'proposals.dept_id as prop_dept_id', 'proposals.scheme_name', 'proposals.scheme_overview', 'proposals.scheme_objective', 'proposals.sub_scheme', 'proposals.status_id as prop_status_id', 'proposals.scheme_id as scheme_id')
                                    ->whereIn('scheme_send.id', $forwarded_draft_ids)
                                    ->where('scheme_send.dept_id', $dept_id)
                                    ->where('scheme_send.status_id', $the_status_id)
                                    ->orderBy('scheme_send.status_id', 'asc')
                                    ->orderBy('scheme_send.id', 'desc')
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
       
        // return $returned_draft_ids; die();
        $proposals_returned = SchemeSend::leftjoin('itransaction.proposals','itransaction.scheme_send.draft_id','=','itransaction.proposals.draft_id')
                    ->select('scheme_send.*','proposals.draft_id as prop_draft_id','proposals.dept_id as prop_dept_id','proposals.scheme_name','proposals.scheme_overview','proposals.scheme_objective','proposals.sub_scheme', 'proposals.status_id as prop_status_id')
                    ->whereIn('scheme_send.id',$returned_draft_ids)
                    ->orderBy('scheme_send.id','desc')
                    ->get();
        //Completed 
        $complted_proposal = Stage::WhereNotNull('document')->get();
      
        return view('dashboards.implementations.proposals',compact('proposals_forwarded','complted_proposal','proposals_new','ongoing_proposal','proposals_returned','the_status_id','status_id_return'));
    }
    public function profile() {
        return view('dashboards.proposal.profile');
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
    
}
