<?php
namespace App\Http\Controllers;

use App\Models\Stage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Implementation;
use Illuminate\Support\Facades\Auth;
use App\Models\SchemeSend;
use App\Models\Proposal;
use DB;
use URL;
use App\Models\User_user_role;
use App\Models\Department;
use Session;
use App\Models\User;
use App\Models\User_user_role_deptid;
use App\Couchdb\Couchdb;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;
use function App\Helpers\PdfHelper\getPdfContent;
use Mail;
use App\Mail\UserMail;
use App\Jobs\UserMailJob;
use Hash;
use App\Models\Subdepartment;

class EvaluationUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_list = User::with('roles')->where('dept_id',0)->orWhere('role_manage','>',0)->orWherenull('dept_id')->orderBy('users.id', 'desc')->cursor();
       

        return view('dashboards.evaluation_user.index',compact('user_list'));
    }
    public function create(){
        $role = User_user_role::where('id', '>=', 23)->get();
        return view('dashboards.evaluation_user.create',compact('role'));
    }
    public function store(Request $request){
       try{
        $validate = Validator::make($request->all(),[
             'email' => 'required|email|max:150|unique:users',
             'dept_id' => 'required|numeric|max:999999999',
             'role' => 'required|numeric|max:999999999',
             'password'=>'required|max:20',
             'confirm_password'=>'required|same:password'
         ]);
         if($validate->fails()) {
            return redirect()->back()->withError($validate->errors());
         } else {
 
                 $user = User::create([
                     'name' => $request->input('name'),
                     'email' => $request->input('email'),
                     'dept_id' => $request->input('dept_id', 0), // default to 0 if not provided
                     'role' => $request->input('role'),
                     'password' => $request->input('password') ? Hash::make($request->input('password')) : null,
                     'role_manage' => $request->input('role_manage', 0),
                 ]);
             
                 User_user_role_deptid::create([
                     'user_id' => $user->id,
                     'user_role_id' => $request->input('role'),
                     'dept_id' => $request->input('dept_id', 0), // default to 0 if not provided
                 ]);
     
                 return redirect()->back()->withSuccess( 'Evaluation User created successfully');
             
         }
       }catch(Exception $e){
         return redirect()->back()->withError($e->getMessage());

       }
    }
    public function edit($id){
        $role = User_user_role::where('id', '>=', 23)->get();
        $user = User::find($id);
        return view('dashboards.evaluation_user.edit',compact('user','role'));
    } 
    public function update(Request $request,$id){
        $validate = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email,' . $id,
            'dept_id' => 'required|numeric|max:999999999',
            'role' => 'required|numeric|max:999999999'
        ]);
        if($validate->fails()) {
            return redirect()->back()->withError($validate->errors());
        } else {

            $user = User::find($id);
            $input = $request->all();
            $input['name'] = (isset($request->name) && $request->has('name'))  ? $request->name : null;
            $input['email'] = (isset($request->email) && $request->has('email'))  ? $request->email : null;
            $input['dept_id'] = (isset($request->dept_id) && $request->has('dept_id'))  ? $request->dept_id : null;
            $input['role'] = (isset($request->role) && $request->has('role'))  ? $request->role : null;
            $input['role_manage'] = (isset($request->role_manage) && $request->has('role_manage'))  ? $request->role_manage : null;
            if(isset($request->password) && $request->has('password')){
                $input['password'] = Hash::make($pass);
            }else{
                $input['password'] = $request->user_password;
            }
          
          
            $user->update($input);
            User_user_role_deptid::where('user_id',$id)->update(['user_id'=>$id, 'user_role_id'=> $request->role,'dept_id'=> $request->dept_id]);
            return redirect()->back()->withSuccess( 'Evaluation User updated successfully');
        }
    }
    public function destroy($id)
    {
        if (!is_null($id)) {
           User::find($id)->delete();
            return redirect()->back()->withSuccess( 'Evaluation User deleted successfully');
        } else {
            return redirect()->back()->withError( 'Invalid department');
        }
    }
}
