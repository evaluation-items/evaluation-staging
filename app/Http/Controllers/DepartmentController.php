<?php

namespace App\Http\Controllers;

use  Validator;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Activitylog;
use Illuminate\Support\Facades\Auth;
use App\Models\Subdepartment;


class DepartmentController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        $departments = Department::active()->orderBy('dept_id', 'asc')->get();   
        return view('departments.index',compact('departments'))->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function create()
    {
        return view('departments.create');
    }

    public function store(Request $request)
    {
            $validate = Validator::make($request->all(), [
                'dept_name' => 'required|string',
            ]);

            if ($validate->fails()) {
                return redirect()->back()->withInput()->withErrors($validate->errors());
            }

            $existing_name = Department::where('dept_name', $request->dept_name)->active()->latest()->first();

            if (!is_null($existing_name)) {
                return redirect()->route('departments.index')->withError( 'This Department is already existing.');
            } else {
                Department::create($request->all());
                $act['userid'] = Auth::user()->id;
                $act['ip'] = $request->ip();
                $act['activity'] = 'Department Create By Evaluation Admin.';
                $act['officecode'] = Auth::user()->dept_id;
                $act['pagereferred'] = $request->url();
                Activitylog::insert($act);
                return redirect()->route('departments.index')->withSuccess( 'Department created successfully.');
            }

    }

    public function show(Department $department)
    {
        return view('departments.show',compact('department'));
    } 

    public function edit(Department $department)
    {
        return view('departments.edit',compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $validate = Validator::make($request->all(), [
            'dept_name' => 'required|string',
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate->errors());
        }

        $existing_name = Department::where('dept_name', $request->dept_name)->active()->latest()->first();
        $act['userid'] = Auth::user()->id;
        $act['ip'] = $request->ip();
        $act['activity'] = 'Department Update By Evaluation Admin.';
        $act['officecode'] = Auth::user()->dept_id;
        $act['pagereferred'] = $request->url();
        Activitylog::insert($act);
        if (!is_null($existing_name)) {
            
            return redirect()->route('departments.index')->withError( 'This Department is already existing.');
        } else {
            $department->update($request->all());
            return redirect()->route('departments.index')->withSuccess( 'Department updated successfully.');
        }
    }

    public function destroy(Department $department,Request $request)
    {
        if (!is_null($department)) {
            $department->update(['status' => false]);
            $act['userid'] = Auth::user()->id;
            $act['ip'] = $request->ip();
            $act['activity'] = 'Department Delete By Evaluation Admin.';
            $act['officecode'] = Auth::user()->dept_id;
            $act['pagereferred'] = $request->url();
            Activitylog::insert($act);
            return redirect()->route('departments.index')->withSuccess('Department deleted successfully');
        } else {
            return redirect()->route('departments.index')->withError('Invalid department');
        }
    }
  
  
}