<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\State;
use App\Models\Activitylog;
use Illuminate\Http\Request;
use  Validator;
use Auth;

class DistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $district_list = District::with('state')->orderBy('name_g','asc')->get();
        $state_list = State::active()->where('state_code',24)->orderBy('name','asc')->get();
        return view('dashboards.admins.district.index',compact('district_list','state_list'));
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
        $validate = Validator::make($request->all(), [
            'state_id' => 'required|string',
            'dcode'=> 'required|string',
            'name_e' => 'required|string',
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate->errors());
        }
      
        District::create([
            'dcode'=> $request->dcode,
            'state_code'=> $request->state_id,
            'name_e'=> $request->name_e
        ]);
        $act['userid'] = Auth::user()->id;
        $act['ip'] = $request->ip();
        $act['activity'] = 'District Add By Evaluation Admin.';
        $act['officecode'] = Auth::user()->dept_id;
        $act['pagereferred'] = $request->url();
        Activitylog::insert($act);
        return redirect()->route('district.index')->withSuccess( 'District created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\District  $district
     * @return \Illuminate\Http\Response
     */
    public function show(District $district)
    {
        return view('dashboards.admins.district.show',compact('district'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\District  $district
     * @return \Illuminate\Http\Response
     */
    public function edit(District $district)
    {
        $state_list = State::active()->where('state_code',24)->orderBy('name','asc')->get();
        return view('dashboards.admins.district.edit',compact('district','state_list'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\District  $district
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, District $district)
    {
        $validate = Validator::make($request->all(), [
            'state_id' => 'required|string',
            'dcode'=> 'required|string',
            'name_e' => 'required|string',
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate->errors());
        }
      
        $district->update([
            'dcode'=> $request->dcode,
            'state_code'=> $request->state_id,
            'name_e'=> $request->name_e
        ]);
        $act['userid'] = Auth::user()->id;
        $act['ip'] = $request->ip();
        $act['activity'] = 'District Update By Evaluation Admin.';
        $act['officecode'] = Auth::user()->dept_id;
        $act['pagereferred'] = $request->url();
        Activitylog::insert($act);
        return redirect()->route('district.index')->withSuccess( 'District updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\District  $district
     * @return \Illuminate\Http\Response
     */
    public function destroy(District $district,Request $request)
    {
        if (!is_null($district)) {
            $district->delete();
            $act['userid'] = Auth::user()->id;
            $act['ip'] = $request->ip();
            $act['activity'] = 'District Delete By Evaluation Admin.';
            $act['officecode'] = Auth::user()->dept_id;
            $act['pagereferred'] = $request->url();
            Activitylog::insert($act);
            return redirect()->route('district.index')->withSuccess('District deleted successfully');
        } else {
            return redirect()->route('district.index')->withError('Invalid department');
        }
    }
}
