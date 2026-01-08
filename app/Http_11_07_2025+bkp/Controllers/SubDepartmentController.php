<?php

namespace App\Http\Controllers;

use  Validator;
use Illuminate\Http\Request;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;
use App\Models\Subdepartment;

class SubDepartmentController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        $sub_departments = Subdepartment::with('department')->active()->orderBy('dept_id', 'asc')->get();//latest()->paginate(5);
        $departments = Department::active()->orderBy('dept_id', 'asc')->get();
        return view('sub-departments.index',compact('sub_departments','departments'));
    }

    public function create()
    {
        
        return view('sub-departments.create');
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'dept_id' =>'required',
            'name' => 'required|string',
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate->errors());
        }
        Subdepartment::create($request->all());
        return redirect()->route('subdepartments.index')->withSuccess('Sub Department created successfully.');
    
    }

    public function show($id)
    {
        if(!is_null($id)){
            $sub_departments = Subdepartment::with('department')->active()->where('id',$id)->get();
         
            if($sub_departments->count() > 0){
                    return view('sub-departments.show',compact('sub_departments'));
            }else{
                return redirect()->route('subdepartments.index')->withError( "Sub Department is doesn't not existing");
            }
           }else{
               return redirect()->route('subdepartments.index')->withError( 'Something went wrong.');
           }
       
    } 

    public function edit(Subdepartment $subdepartment)
    {
        $departments = Department::active()->orderBy('dept_id', 'asc')->get();

        return view('sub-departments.edit',compact('subdepartment','departments'));
    }

    public function update(Request $request,$id)
    {
        $sub_departments = Subdepartment::find($id);
       
        $validate = Validator::make($request->all(), [
            'dept_id' =>'required',
            'name' => 'required|string',
        ]);
      
        if ($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate->errors());
        }

        $sub_departments->update($request->all());
        return redirect()->route('subdepartments.index')->withSuccess( 'Sub-Department updated successfully.');
       
    }

    public function destroy(Request $request,$id)
    {
		 if (isset($id) && !empty($id)) {
            $decodedId = base64_decode($id);

            if (is_numeric($decodedId)) {
                Subdepartment::find($decodedId)->update(['status' => false]);
                return response()->json(['message' => 'Sub-Department delete successfully']);
            } else {
                return response()->json(['message' => 'Invalid ID provided']);
            }
        } else {
            return response()->json(['message' => 'ID is required']);
        }
		
        // if (!is_null($id)) {
            // Subdepartment::find($id)->update(['status' => false]);
    
            // return redirect()->route('subdepartments.index')->withSuccess('Sub-Department deleted successfully');
        // } else {
            // return redirect()->route('subdepartments.index')->withError( 'Invalid department');
        // }
    }

}
