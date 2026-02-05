<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NodalDesignation;
use App\Models\Department;
use App\Models\Activitylog;
use  Validator;
use Auth;

class NodalDesignationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $nodal = NodalDesignation::orderBy('designation_name','ASC')->get();
        $departments = Department::orderBy('dept_name','ASC')->get();
        return view('dashboards.admins.nodal_designations.index',compact('nodal','departments'));
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
    public function store(Request $request)
    {
        //dd($request->all());
        $validate = Validator::make($request->all(), [
            'dept_id' => 'required|integer',
            'designation_name' => 'required|string',
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate->errors());
        }
        $arr = [
            'dept_id' => $request->dept_id,
            'designation_name' => $request->designation_name,
        ];
        NodalDesignation::create($arr);
        $act['userid'] = Auth::user()->id;
        $act['ip'] = $request->ip();
        $act['activity'] = 'Nodal Designation Store By Admin';
        $act['officecode'] = Auth::user()->dept_id;
        $act['pagereferred'] = $request->url();
        Activitylog::insert($act);
        return response()->json(['success'=> 'Nodal Designation created successfully.']);

       // return redirect()->route('units.index')->withSuccess( 'Unit created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\NodalDesignation  $nodal
     * @return \Illuminate\Http\Response
     */
    public function show(NodalDesignation $nodal)
    {
        return view('dashboards.admins.nodal_designations.show',compact('nodal'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\NodalDesignation  $nodal
     * @return \Illuminate\Http\Response
     */
    public function edit(NodalDesignation $nodal)
    {
      //  $departments = Department::orderBy('dept_name','ASC')->get(); // Retrieve all departments
        $nodals = NodalDesignation::all(); // Retrieve all nodal designations
        return view('dashboards.admins.nodal_designations.index',compact('nodal','nodals'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\NodalDesignation  $nodal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
       //dd($request->all());
        $validate = Validator::make($request->all(), [
            'dept_id' => 'required|integer',
            'designation_name' => 'required|string',
        ]);

        if ($validate->fails()) {
            return response()->json(['errors'=>$validate->errors()], 422);
        }
        NodalDesignation::where('id', $request->nodal_designation_id)->update(['dept_id' => $request->dept_id,'designation_name' => $request->designation_name]);
            
        $act['userid'] = Auth::user()->id;
        $act['ip'] = $request->ip();
        $act['activity'] = 'Nodal Designation Update By Admin';
        $act['officecode'] = Auth::user()->dept_id;
        $act['pagereferred'] = $request->url();
        Activitylog::insert($act);
        return response()->json(['success'=> 'Nodal Designation updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\NodalDesignation  $nodal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,NodalDesignation $nodal)
    {
        if (!is_null($nodal)) {
            $nodal->delete();
            $act['userid'] = Auth::user()->id;
            $act['ip'] = $request->ip();
            $act['activity'] = 'Nodal Designation Delete By Admin';
            $act['officecode'] = Auth::user()->dept_id;
            $act['pagereferred'] = $request->url();
            Activitylog::insert($act);
            return redirect()->route('nodal-designations.index')->withSuccess('Nodal Designation deleted successfully');
        } else {
            return redirect()->route('nodal-designations.index')->withError('Invalid Nodal Designation');
        }
    }
}
