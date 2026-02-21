<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sdggoals;
use App\Models\Activitylog;
use  Validator;
use Auth;

class SDGController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sdggoals = Sdggoals::orderBy('goal_id','ASC')->get();
        return view('dashboards.admins.sdg_goals.index',compact('sdggoals'));
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
            'goal_name_guj' => ['required', 'regex:/^[\p{Gujarati}\s,.\-\(\)]+$/u'],
            'goal_name' => 'required|string',
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate->errors());
        }
        $arr = [
            'goal_name_guj' => $request->goal_name_guj,
            'goal_name' => $request->goal_name,
        ];
        Sdggoals::create($arr);
        $act['userid'] = Auth::user()->id;
        $act['ip'] = $request->ip();
        $act['activity'] = 'SDG Goal Store By Admin';
        $act['officecode'] = Auth::user()->dept_id;
        $act['pagereferred'] = $request->url();
        Activitylog::insert($act);
        return response()->json(['success'=> 'SDG Goal created successfully.']);

       // return redirect()->route('units.index')->withSuccess( 'Unit created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sdggoals  $sdggoal
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sdggoal = Sdggoals::find($id);
       
        return view('dashboards.admins.sdg_goals.show',compact('sdggoal'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Sdggoals  $sdggoal
     * @return \Illuminate\Http\Response
     */
    public function edit(Sdggoals $sdggoal)
    {
      //  $departments = Department::orderBy('dept_name','ASC')->get(); // Retrieve all departments
        $sdggoal = Sdggoals::all(); // Retrieve all nodal designations
        return view('dashboards.admins.sdg_goals.edit',compact('sdggoal'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sdggoals  $sdggoal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sdggoals $sdggoal)
    {
      
       $validate = Validator::make($request->all(), [
            'goal_name_guj' => ['required', 'regex:/^[\p{Gujarati}\s,.\-\(\)]+$/u'],
            'goal_name' => 'required|string',
        ]);
        if ($validate->fails()) {
            return response()->json(['errors'=>$validate->errors()], 422);
        }
        Sdggoals::where('goal_id', $request->sdg_goal_id)->update(['goal_name_guj' => $request->goal_name_guj,'goal_name' => $request->goal_name]);
            
        $act['userid'] = Auth::user()->id;
        $act['ip'] = $request->ip();
        $act['activity'] = 'SDG Goal Update By Admin';
        $act['officecode'] = Auth::user()->dept_id;
        $act['pagereferred'] = $request->url();
        Activitylog::insert($act);
        return response()->json(['success'=> 'SDG Goal updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sdggoals  $sdggoal
     * @return \Illuminate\Http\Response
     */
        public function destroy(Request $request, $id)
        {
            $sdggoal = Sdggoals::find($id);

            if (!$sdggoal) {
                return redirect()->back()->withError('Invalid record');
            }

            $sdggoal->update(['status' => 0]);

            return redirect()->route('sdg-goals.index')->withSuccess('Deleted successfully');
        }

}
