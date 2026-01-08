<?php

namespace App\Http\Controllers;

use App\Models\DepartmentHod;
use Illuminate\Http\Request;
use App\Models\Department;
use Validator;
use Auth;

class DepartmentHodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->role == 25){
            $hod_list = DepartmentHod::get();
        }else{
            $hod_list = DepartmentHod::where('dept_id', Auth::user()->dept_id)->get();
        }
        $departments = Department::active()->orderBy('dept_id', 'asc')->get();
        
        return view('department_hod.index',compact('hod_list','departments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$param)
    {
        $validate = Validator::make($request->all(), [
            'dept_id' =>'required',
            'name' => 'required|string',
        ]);

        if($param == 1){
            if ($validate->fails()) {
                return response()->json(['error'=>$validate->errors()]);
            }
            DepartmentHod::create($request->all());
            return response()->json(['success'=> true]);
        }else{
            if ($validate->fails()) {
                return redirect()->back()->withInput()->withErrors($validate->errors());
            }
            DepartmentHod::create($request->all());
            return redirect()->route('department_hod.index')->withSuccess( 'Department HOD created successfully.');
        }
        $act['userid'] = Auth::user()->id;
        $act['ip'] = $request->ip();
        $act['activity'] = 'DepartmentHod Create By Evaluation Admin.';
        $act['officecode'] = Auth::user()->dept_id;
        $act['pagereferred'] = $request->url();
        Activitylog::insert($act);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DepartmentHod  $departmentHod
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!is_null($id)){
            $departmentHod = DepartmentHod::findorfail($id);
            return view('department_hod.show',compact('departmentHod'));
           }else{
               return redirect()->route('department_hod.index')->withError('Something went wrong.');
           }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DepartmentHod  $departmentHod
     * @return \Illuminate\Http\Response
     */
    public function edit(DepartmentHod $departmentHod)
    {
        $departments = Department::active()->orderBy('dept_id', 'asc')->get();
        return view('department_hod.edit',compact('departmentHod','departments'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DepartmentHod  $departmentHod
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $departmentHod = DepartmentHod::find($id);
     
        $validate = Validator::make($request->all(), [
            'dept_id' =>'required',
            'name' => 'required|string',
        ]);
      
        if ($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate->errors());
        }
        
        $departmentHod->update($request->all());
        $act['userid'] = Auth::user()->id;
        $act['ip'] = $request->ip();
        $act['activity'] = 'DepartmentHod Update By Evaluation Admin.';
        $act['officecode'] = Auth::user()->dept_id;
        $act['pagereferred'] = $request->url();
        Activitylog::insert($act);
        return redirect()->route('department_hod.index')->withSuccess( 'Department of HOD updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DepartmentHod  $departmentHod
     * @return \Illuminate\Http\Response
     */
    public function destroy($id,Request $request)
    {

        if (isset($id) && !empty($id)) {
            $decodedId = base64_decode($id);

            if (is_numeric($decodedId)) {
                DepartmentHod::find($decodedId)->delete();
                $act['userid'] = Auth::user()->id;
                $act['ip'] = $request->ip();
                $act['activity'] = 'DepartmentHod Delete By Evaluation Admin.';
                $act['officecode'] = Auth::user()->dept_id;
                $act['pagereferred'] = $request->url();
                Activitylog::insert($act);
                return response()->json(['message' => 'HOD deleted successfully']);
            } else {
                return response()->json(['message' => 'HOD not deleted']);
            }
        } else {
            // Handle missing ID
            return response()->json(['message' => 'ID is required']);
        }
    }
}
