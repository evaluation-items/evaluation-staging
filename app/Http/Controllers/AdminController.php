<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use DB;
use URL;
use App\Models\Department;
use App\Models\OdkUser;
use App\Models\Activitylog;
use Mail;
use App\Mail\UserMail;
use App\Jobs\UserMailJob;
use Validator;
use Hash;
use Carbon\Carbon;
use App\Models\User_user_role_deptid;
use App\Models\User_user_role;
use App\Models\Subdepartment;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use App\Models\Stage;
use App\Models\Advertisement;
use App\Models\SchemeSend;
use App\Exports\StageExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;


class AdminController extends Controller {

    function index(Request $request) {
        $user_count = User::count();
        $role_count = User_user_role::count();
        $department_count = Department::active()->count();
        $sub_department_count = Subdepartment::active()->count();

        $new_count = $this->count_all_proposals("new");
        // $ongoing_count = $this->count_all_proposals("on_going");
        // $return_count = $this->count_all_proposals("return");
        // $completed_count = $this->count_all_proposals("completed");
        // $scheme_list = Proposal::where('status_id','23')->orderBy('scheme_name','ASC')->groupBy('draft_id')->pluck('scheme_name','draft_id');
       
        $act['userid'] = Auth::user()->id;
        $act['ip'] = $request->ip();
        $act['activity'] = 'Aproove Proposal By Evaluation Admin.';
        $act['officecode'] = Auth::user()->dept_id;
        $act['pagereferred'] = $request->url();
        Activitylog::insert($act);
        
        return view('dashboards.admins.index',compact('user_count','role_count','department_count','sub_department_count','new_count'));
    }
    
   function profile(){
       return view('dashboards.admins.profile');
   }

//    function settings(){
//        return view('dashboards.admins.settings');
//    }

    function user_list( Request $request) {
        $user_list = User::where('role_manage',0)->with('roles')->orderBy('users.id','desc')->get();
        $departments = Department::active()->orderBy('dept_name','asc')->get();
        $user_roles = User_user_role::where('id', '<=', 22)->orderBy('rolename','Asc')->get();
        return view('dashboards.admins.user_list', compact( 'user_list', 'departments', 'user_roles' ) )->with( 'i', 1 );
    }

    function adduser(Request $request) {
        
        $validate = Validator::make($request->all(),[
            'email' => 'required|email|max:150|unique:users',
            'role' => 'required|numeric|max:999999999',
            'password'=>'required|max:20',
            'confirm_password'=>'required|same:password'
        ]);
        if($validate->fails()) {
           return redirect()->route('admin.user_list')->withError($validate->errors());
        } else {

                $user = User::create([
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'dept_id' =>   $request->dept_id ,
                    //$request->input('selected_dept_id', 0), // default to 0 if not provided
                    'role' => $request->input('role'),
                    'password' => $request->input('password') ? Hash::make($request->input('password')) : null,
                    'role_manage' => 0,
                    'login_user' => 1,
                ]);
            
                User_user_role_deptid::create([
                    'user_id' => $user->id,
                    'user_role_id' => $request->input('role'),
                    'dept_id' => $request->dept_id, // default to 0 if not provided
                ]);
    
                $act['userid'] = Auth::user()->id;
                $act['ip'] = $request->ip();
                $act['activity'] = 'Add User Evaluation Admin.';
                $act['officecode'] = Auth::user()->dept_id;
                $act['pagereferred'] = $request->url();
                Activitylog::insert($act);

                return redirect()->back()->withSuccess( 'User created successfully');
        }
    }

    function updateInfo(Request $request){
        
            $validator = \Validator::make($request->all(),[
                'name'=>'required',
                'email'=> 'required|email|unique:users,email,'.Auth::user()->id,
            ]);

            if(!$validator->passes()){
                return response()->json(['status'=>0,'error'=>$validator->errors()->toArray()]);
            }else{
                $query = User::find(Auth::user()->id)->update([
                        'name'=>$request->name,
                        'email'=>$request->email,
                        'phone'=>$request->phone,
                ]);

                $act['userid'] = Auth::user()->id;
                $act['ip'] = $request->ip();
                $act['activity'] = 'Update User By Evaluation Admin.';
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

        $act['userid'] = Auth::user()->id;
        $act['ip'] = $request->ip();
        $act['activity'] = 'Change User By Evaluation Admin.';
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

    // public function odk_user_list(){
    // $users_list = OdkUser::get();
    // return view('dashboards.admins.odk_user_list',compact('users_list'));
    // }

    // public function add_odk_user(Request $request){
    //     $validate = Validator::make($request->all(),[
    //         'email' => 'required|email|max:150',
    //         'password'=>'required|max:20',
    //         'confirm_password'=>'required|same:password'
    //     ]);
    //     if($validate->fails()) {
    //         return redirect()->back()->withInput()->withErrors($validate->errors());
    //     } else {
            
    //         $email = $request->input('email');
    //         $pass = $request->input('password');
    //         $encrypt_password = base64_encode($pass);
    //         $password = Hash::make($pass);
    //         $arr = array('email'=>$email,'password'=>$password,'encrypt_password'=>$encrypt_password);
    //         $ifemail = OdkUser::where('email',$email)->get();
    //         if($ifemail->isEmpty()) {
    //             $user_id = OdkUser::create($arr);
                
    //             return redirect()->back();
    //         } else {
    //             return redirect()->back()->with('email_err','Email already exists');
    //         }
    //     }
    // }

    // public function status(Request $request,$id){
    //             // Get all active users
    //             $activeUsers = OdkUser::where('status', true)->get();
                
    //             // Find the user by ID
    //             $user = OdkUser::find(Crypt::decrypt($id));

    //             if ($activeUsers->count() > 0) {
    //                 foreach ($activeUsers as $activeUser) {
    //                     $activeUser->update(['status' => false]);
                        
    //                     $user->update(['status' => $request->status]);
    //                 }
    //             } else {
    //                 // If there are no active users, update the status of the requested user
    //                 $user->update(['status' => $request->status]);
    //             }

    //             return response()->json(['success' => 'Status changes successful.']);
    // }

    // public function odk_user_destory($id)
    // {
    //     if (!is_null($id)) {
            
    //         OdkUser::find(Crypt::decrypt($id))->delete();
    
    //         return response()->json(['message' => 'Odk User deleted successfully']);
    //     } else {
    //         return response()->json(['message' => 'Invalid department']);
    //     }
    // }

    /* Role Items */
    public function role(){
        $role_list = User_user_role::orderBy('rolename','ASC')->get();
        return view('dashboards.role.index',compact('role_list'));
    }
    public function role_edit($id,Request $request){
        $role = User_user_role::find(Crypt::decrypt($id));

        $act['userid'] = Auth::user()->id;
        $act['ip'] = $request->ip();
        $act['activity'] = 'Edit Role By Evaluation Admin.';
        $act['officecode'] = Auth::user()->dept_id;
        $act['pagereferred'] = $request->url();
        Activitylog::insert($act);

        return view('dashboards.role.edit',compact('role'));
    }
    public function role_update(Request $request,$id)
    {
        $validate = Validator::make($request->all(),[
            'rolename' => 'required',
        ]);
        if($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate->errors());
        }else{
            $role = User_user_role::find(Crypt::decrypt($id));
            $role->update($request->all());
            $act['userid'] = Auth::user()->id;
            $act['ip'] = $request->ip();
            $act['activity'] = 'Update Role By Evaluation Admin.';
            $act['officecode'] = Auth::user()->dept_id;
            $act['pagereferred'] = $request->url();
            Activitylog::insert($act);
            return redirect()->route('admin.roles')->withSuccess('Role Update Successfully');
        }
    }
    public function role_destory($id,Request $request){
        $role = User_user_role::find(Crypt::decrypt($id))->delete();
        $act['userid'] = Auth::user()->id;
        $act['ip'] = $request->ip();
        $act['activity'] = 'Role Delete By Evaluation Admin.';
        $act['officecode'] = Auth::user()->dept_id;
        $act['pagereferred'] = $request->url();
        Activitylog::insert($act);
        return redirect()->route('admin.roles')->withSuccess('Role Delete Successfully');

    }
    public function role_store(Request $request){
        $validate = Validator::make($request->all(),[
            'rolename' => 'required',
        ]);
        if($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate->errors());
        }else{
            $role = User_user_role::create($request->all());
            $act['userid'] = Auth::user()->id;
            $act['ip'] = $request->ip();
            $act['activity'] = 'Role Store By Evaluation Admin.';
            $act['officecode'] = Auth::user()->dept_id;
            $act['pagereferred'] = $request->url();
            Activitylog::insert($act);
            return redirect()->route('admin.roles')->withSuccess('Role Added Successfully');
        }
    }

    public function showUser($id){
        $user = User::with('roles')->find(Crypt::decrypt($id));
        return view('dashboards.admins.show',compact('user'));
    }
    public function editUser($id,Request $request){
        $role = User_user_role::get();
        $department  = Department::active()->get();
        $user = User::find(Crypt::decrypt($id));
        $act['userid'] = Auth::user()->id;
        $act['ip'] = $request->ip();
        $act['activity'] = 'Edit User By Evaluation Admin.';
        $act['officecode'] = Auth::user()->dept_id;
        $act['pagereferred'] = $request->url();
        Activitylog::insert($act);
        return view('dashboards.admins.edit',compact('user','role','department'));
    }
    public function userUpdate(Request $request,$id){
        $validate = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email,' . Crypt::decrypt($id),
            'role' => 'required|numeric|max:999999999'
        ]);
        if($validate->fails()) {
            return redirect()->route('admin.user_list')->withError($validate->errors());
        } else {

            $userId = Crypt::decrypt($id);
             $user = User::findOrFail($userId);
             $input = [
                'name'     => $request->has('name') ? $request->name : null,
                'email'    => $request->has('email') ? $request->email : null,
                'dept_id'  => $request->has('selected_dept_id') ? $request->selected_dept_id : null,
                'role'     => $request->has('role') ? $request->role : null,
            ];
          
            // $input['name'] = (isset($request->name) && $request->has('name'))  ? $request->name : null;
            // $input['email'] = (isset($request->email) && $request->has('email'))  ? $request->email : null;
            // $input['dept_id'] = (isset($request->selected_dept_id) && $request->has('selected_dept_id'))  ? $request->selected_dept_id : null;
            // $input['role'] = (isset($request->role) && $request->has('role'))  ? $request->role : null;
           
            if ($request->filled('password')) {
                $input['password'] = Hash::make($request->password);
            } else {
                $input['password'] = $request->user_password;
            }
          
            $user->update($input);

           // Update user-role-dept mapping
                User_user_role_deptid::where('user_id', $userId)->update([
                    'user_id' => $userId,
                    'user_role_id' => $request->role,
                    'dept_id' => $request->selected_dept_id,
                ]);

                // Log the activity
                $act = [
                    'userid' => Auth::user()->id,
                    'ip' => $request->ip(),
                    'activity' => 'User Update By Evaluation Admin.',
                    'officecode' => Auth::user()->dept_id,
                    'pagereferred' => $request->url(),
                ];
                Activitylog::insert($act);

         return redirect()->route('admin.user_list')->withSuccess('User Updated Successfully');
        }
       
    }
    public function userDestory($id,Request $request)
    {
        if (!is_null($id)) {
           User::find(base64_decode($id))->delete();
           $act['userid'] = Auth::user()->id;
           $act['ip'] = $request->ip();
           $act['activity'] = 'User Delete By Evaluation Admin.';
           $act['officecode'] = Auth::user()->dept_id;
           $act['pagereferred'] = $request->url();
           Activitylog::insert($act);
            return redirect()->route('admin.user_list')->withSuccess( 'User deleted successfully');
        } else {
            return redirect()->route('admin.user_list')->withError( 'Invalid department');
        }
    }
    public function Userrole(Request $request){
        $id = base64_decode($request->id);
        $dept_id = $request->dept_id;
        $param = $request->param;
        $role = [];
       
        if(!is_null($param)){
           
            if (!is_null($id) && $id > 0) {
                $role = User_user_role::where('id', '<=', 22)->get(); //Default Role
            } else {
                $role = User_user_role::where('id', '>', 22)->get(); //Evaluation Role
            }
        }else{
           
            if ((!is_null($id) && $id > 0)) {
                $role = User_user_role::where('id', '<=', 22)->get(); //Default Role

            } else {
                $role = User_user_role::where('id', '>', 22)->get(); //Evaluation Role

            }
        }
      
        return response()->json(['role' => $role]);
    }
    public function updateStage(Request $request){
        $ongoing_proposal = Stage::with(['schemeSend' => function ($q) {
            $q->whereNotNull('team_member_dd');
        }])->whereNull('document')->get();               
        $act['userid'] = Auth::user()->id;
        $act['ip'] = $request->ip();
        $act['activity'] = 'Stage Uodate By Evaluation Admin.';
        $act['officecode'] = Auth::user()->dept_id;
        $act['pagereferred'] = $request->url();
        Activitylog::insert($act);
        return view('dashboards.admins.stage_update',compact('ongoing_proposal'));
    }
    public function Stagefiltter(Request $request){
         $stages_filtter = $request->stages_filtter;
        if($stages_filtter){
            $ongoing_proposal = Stage::with(['schemeSend' => function ($q) {
                $q->whereNotNull('team_member_dd');
            }])->whereNull('document')->get(); 
            $data = [];
            $team_member_dd = [];
            foreach ($ongoing_proposal as $key => $prop) {
                if($stages_filtter == 2){
                    $data[] =  SchemeName($prop->scheme_id);
                    $team_member_dd[] = $prop->scheme_id;
                }else if($stages_filtter == 3){
                    $data[] = date('d-M-y',strtotime($prop->created_at));
                    $team_member_dd[] = date('Y-m-d',strtotime($prop->created_at));
                }elseif ($stages_filtter == 4) {
                    $data[] = branch_list($prop->schemeSend->team_member_dd);
                    $team_member_dd[] = $prop->schemeSend->team_member_dd;
                }elseif ($stages_filtter == 5) {
                    $stage_key_fields = current_stage_fields();
                    foreach ($ongoing_proposal as $key => $prop) {
                            $stage_key = null;
                            // Get the actual stage key
                            $stage = Stage::find($prop->id);
                            foreach ($stage_key_fields as $field => $label) {
                                if (!empty($stage->$field)) {
                                    $stage_key = $field;
                                }
                            }
                    
                            if ($stage_key) {
                                $data[] = $stage_key_fields[$stage_key]; // Label
                                $team_member_dd[] = 'stage_key_' . $stage_key; // Send key for filtering later
                            }
                    }
                }
            }
            $data = array_values(array_unique($data));
            $team_member_dd = array_values(array_unique($team_member_dd));
        }
        
                
        $act['userid'] = Auth::user()->id;
        $act['ip'] = $request->ip();
        $act['activity'] = 'Stage Filter.';
        $act['officecode'] = Auth::user()->dept_id;
        $act['pagereferred'] = $request->url();
        Activitylog::insert($act);
        return response()->json([
            'data' => $data,
            'team_member_dd' => $team_member_dd
        ]);    
    }

    public function getFilteredData($filter = null)
    {
        $query = Stage::query()->with(['schemeSend' => function ($q) {
            $q->whereNotNull('team_member_dd');
        }])->whereNull('document');

        if ($filter) {
            if (str_contains($filter, ',')) {
                $roleIds = explode(',', $filter);
                $query->whereHas('schemeSend', function ($query) use ($roleIds) {
                    $query->where(function ($q) use ($roleIds) {
                        foreach ($roleIds as $roleId) {
                            $q->orWhereRaw("(',' || team_member_dd || ',') ILIKE ?", ["%," . $roleId . ",%"]);
                        }
                    });
                });
            } elseif (is_numeric($filter)) {
                $query->whereHas('schemeSend', function ($query) use ($filter) {
                    $query->where('scheme_id', $filter);
                });
            } elseif (strtotime($filter)) {
                $query->whereDate('created_at', Carbon::parse($filter)->format('Y-m-d'));
            } elseif (Str::startsWith($filter, 'stage_key_')) {
                $all = $query->get();
                $stageKey = Str::replaceFirst('stage_key_', '', $filter);
                return $all->filter(fn($item) => current_stage_key($item->id) === $stageKey)->values();
            }
        }

        return $query->get();
    }

    public function summaryReport(Request $request){
       
        if(!is_null($request->selected)){
          
             $filter = $request->selected; 
             $items = $this->getFilteredData($filter);


            $html = "";
            if($items->count() > 0){
              
                $html .= '<div class="row">
                <div class="card-body">
                  <div class="col-lg-12" >
                    <table class="table table-bordered table-striped dataTable dtr-inline" id="example1">
                          <a href="'.route('stage.export', ['filter' => $filter]).'" class="btn btn-primary float-right btn-download">Download Excel</a>
                         <a href="'.route('stage.export.pdf', ['filter' => $filter]).'" class="btn btn-primary float-right btn-download">Download PDF</a>
                      <thead>
                            <tr>
                                <th>Sr No.</th>
                                <th>Scheme Name</th>
                                <th>Assigned Date</th>
                                <th>Branch Name</th>
                                <th>Current Stage</th>
                            </tr>
                        <tbody>';
                foreach($items as $key => $items){
                        $html .= '<tr>
                                    <td>'.++$key.'</td>
                                    <td>'.SchemeName($items->scheme_id).'</td>
                                    <td>'.date('d-M-y',strtotime($items->created_at)).'</td>
                                    <td>'.branch_list($items->schemeSend->team_member_dd).'</td>
                                    <td>'.current_stages($items->id) .'</td>
                                </tr>';
                    }
                    $html .= '</tbody>
                            </table>
                        </div>
                        </div>
                    </div>';
            }
          return response($html);

        }
    }

    public function exportExcel(Request $request)
    {
        try {
            $items = $this->getFilteredData($request->filter);
            return Excel::download(new StageExport($items),'stage-summary.xlsx');
   
        } catch (DecryptException $e) {
            return response()->json(['error' => 'Invalid encrypted data'], 400);
        }
    }


    public function exportPdf(Request $request)
    {
        $items = $this->getFilteredData($request->filter);

        $data = [];

        foreach ($items as $item) {
            $data[] = [
                'scheme_name'   => SchemeName($item->scheme_id),
                'assigned_date' => date('d-M-y', strtotime($item->created_at)),
                'branch_name'   => branch_list($item->schemeSend->team_member_dd ?? ''),
                'current_stage' => current_stages($item->id)
            ];
        }

        if (empty($data)) {
            return back()->with('error', 'No data found to export!');
        }

        $pdf = \PDF::loadView('pdf.stage-report', compact('data'));
        return $pdf->download('stage-report.pdf');

    }
    

    function advertisement_list() {
        $add_list = Advertisement::orderBy('id','desc')->get();
        return view('dashboards.admins.advertisement.index', compact('add_list'))->with( 'i', 1);
    }

    function addAdvertisement(Request $request) {
        $validate = Validator::make($request->all(),[
            'name' => 'required',
        ]);
        if($validate->fails()) {
           return redirect()->route('admin.advertisement_list')->withError($validate->errors());
        } else {

         Advertisement::create($request->all());
         $act['userid'] = Auth::user()->id;
         $act['ip'] = $request->ip();
         $act['activity'] = 'Advertiesement Added By Evaluation Admin.';
         $act['officecode'] = Auth::user()->dept_id;
         $act['pagereferred'] = $request->url();
         Activitylog::insert($act);
        return redirect()->back()->withSuccess( 'Advertiesment created successfully');
        }
    }
 
    public function editAdvertisement($id,Request $request){
        $add = Advertisement::find(Crypt::decrypt($id));
        $act['userid'] = Auth::user()->id;
        $act['ip'] = $request->ip();
        $act['activity'] = 'Advertiesment Edit By Evaluation Admin.';
        $act['officecode'] = Auth::user()->dept_id;
        $act['pagereferred'] = $request->url();
        Activitylog::insert($act);
        return view('dashboards.admins.advertisement.edit',compact('add'));
    }
    public function advertisementUpdate(Request $request,$id){
        $validate = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if($validate->fails()) {
            return redirect()->route('admin.advertisement_list')->withError($validate->errors());
        } else {

            $add = Advertisement::find(Crypt::decrypt($id));
            $input = $request->all();
            $input['name'] = isset($request->name) ? $request->name : null;
            $add->update($input);
            $act['userid'] = Auth::user()->id;
            $act['ip'] = $request->ip();
            $act['activity'] = 'Advertiesment Update By Evaluation Admin.';
            $act['officecode'] = Auth::user()->dept_id;
            $act['pagereferred'] = $request->url();
            Activitylog::insert($act);
            return redirect()->route('admin.advertisement_list')->withSuccess('Add Updated Successfully');
        }
       
    }
    public function advertisementDestory($id,Request $request)
    {
        if (!is_null($id)) {
            Advertisement::find(base64_decode($id))->delete();
            $act['userid'] = Auth::user()->id;
            $act['ip'] = $request->ip();
            $act['activity'] = 'Advertiesment Delete By Evaluation Admin.';
            $act['officecode'] = Auth::user()->dept_id;
            $act['pagereferred'] = $request->url();
            Activitylog::insert($act);
            return response()->json(['success'=> 'Add deleted successfully']);
        } else {
            return response()->json(['error' => 'Something went wrong']);
        }
    }
    public function advertisementStatus($id,Request $request)
    {
        if (isset($id) && !empty($id)) {
            $decodedId = base64_decode($id);

            if (is_numeric($decodedId)) {
                $add = Advertisement::find($decodedId);
                if ($add) {
                    $add->update(['active' => 1]);
                    $act['userid'] = Auth::user()->id;
                    $act['ip'] = $request->ip();
                    $act['activity'] = 'Advertiesment Status Change By Evaluation Admin.';
                    $act['officecode'] = Auth::user()->dept_id;
                    $act['pagereferred'] = $request->url();
                    Activitylog::insert($act);
                    return response()->json(['message' => 'Advertisement activated successfully']);

                } else {
                    return response()->json(['message' => 'Advertisement not found']);
                }
            } else {
             
                return response()->json(['message' => 'Invalid ID provided']);
            }
        } else {
            // Handle missing ID
            return response()->json(['message' => 'ID is required']);
        }

    }
     public function advertisementNews($id)
    {
        if (isset($id) && !empty($id)) {
            $decodedId = base64_decode($id);

            if (is_numeric($decodedId)) {
                $add = Advertisement::find($decodedId);
               
                if ($add) {
                  //  Advertisement::where('active', 1)->update(['active' => 0]);
                    $add->update(['is_adverties' => 1]);
                    return response()->json(['message' => 'Advertisement News activated successfully']);

                } else {
                    return response()->json(['message' => 'Advertisement not found']);
                }
            } else {
             
                return response()->json(['message' => 'Invalid ID provided']);
            }
        } else {
            // Handle missing ID
            return response()->json(['message' => 'ID is required']);
        }

    }
    public function count_all_proposals($param) {   
    
        if ($param == "new") {
            $status_id = '25';

            return  SchemeSend::select('scheme_send.*','proposals.scheme_name','proposals.scheme_overview','proposals.scheme_objective','proposals.sub_scheme','departments.dept_name')
                                        ->leftjoin('itransaction.proposals','scheme_send.draft_id','=','proposals.draft_id')
                                        ->leftjoin('imaster.departments','scheme_send.dept_id','departments.dept_id')
                                        ->whereNotNull('scheme_send.team_member_dd')
                                        ->where('scheme_send.approved', 0)
                                        ->orderByDesc('scheme_send.id')
                                        ->distinct()->count();
    
        }  
        // Default return 0 if $param doesn't match any condition
        return 0;
    }
    public function proposallist($param) {
        if($param == "new"){
        //New Proposal
        $new_proposals = SchemeSend::whereNotNull('scheme_send.team_member_dd')
                                    ->where('scheme_send.approved', 0)
                                    ->orderByDesc('scheme_send.id')
                                    ->distinct()
                                    ->get();
        
            return view('dashboards.admins.proposals.new_proposal',compact('new_proposals'));

        }
    }
    public function approvedPScheme(Request $request){
            $id = Crypt::decrypt($request->id);
            $draft_id = Crypt::decrypt($request->draft_id);
            $dept_id = Crypt::decrypt($request->dept_id);
        if(!is_null($id) && (!is_null($draft_id)) && (!is_null($dept_id))){
            SchemeSend::where([['dept_id', '=', $dept_id], ['draft_id', '=', $draft_id],['id','=',$id]])->update(['approved' => 2]);
            
            $act['userid'] = Auth::user()->id;
            $act['ip'] = $request->ip();
            $act['activity'] = 'Approve Scheme By Evaluation Admin.';
            $act['officecode'] = Auth::user()->dept_id;
            $act['pagereferred'] = $request->url();
            Activitylog::insert($act);
            return response()->json(['success'=>true]);
        }else{
            return response()->json(['error'=>false]);
        }
    }
    
}