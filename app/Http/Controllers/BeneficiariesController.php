<?php

namespace App\Http\Controllers;

use App\Models\Beneficiaries;
use App\Models\Activitylog;
use Illuminate\Http\Request;
use Validator;
use Auth;

class BeneficiariesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $beneficiarie_item = Beneficiaries::orderBy('name','ASC')->get();
        return view('dashboards.admins.beneficiaries.index',compact('beneficiarie_item'));
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
            'name' => 'required|string',
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate->errors());
        }
        Beneficiaries::create($request->all());
        $act['userid'] = Auth::user()->id;
        $act['ip'] = $request->ip();
        $act['activity'] = 'Beneficiaries Create By Evaluation Admin.';
        $act['officecode'] = Auth::user()->dept_id;
        $act['pagereferred'] = $request->url();
        Activitylog::insert($act);
        return response()->json(['success'=> 'Beneficiaries created successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Beneficiaries  $beneficiaries
     * @return \Illuminate\Http\Response
     */
  //  public function show(Beneficiaries $beneficiarie_items)
    public function show($id)
    {
        $beneficiaries =  Beneficiaries::find($id);
        return view('dashboards.admins.beneficiaries.show',compact('beneficiaries'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Beneficiaries  $beneficiaries
     * @return \Illuminate\Http\Response
     */
    public function edit(Beneficiaries $beneficiaries)
    {
        $beneficiarie_item = Beneficiaries::all(); // Retrieve all beneficiaries
        return view('dashboards.admins.beneficiaries.index',compact('beneficiarie_item','beneficiaries'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Beneficiaries  $beneficiaries
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $beneficiaries = Beneficiaries::find($id);
        $validate = Validator::make($request->all(), [
            'name' => 'required|string',
        ]);

        if ($validate->fails()) {
            return response()->json()->withInput()->withErrors($validate->errors());
        }
        
        $beneficiaries->update(['name' => $request->name]);

        $act['userid'] = Auth::user()->id;
        $act['ip'] = $request->ip();
        $act['activity'] = 'Beneficiaries Update By Evaluation Admin.';
        $act['officecode'] = Auth::user()->dept_id;
        $act['pagereferred'] = $request->url();
        Activitylog::insert($act);
        return response()->json(['success'=> 'Beneficiaries updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Beneficiaries  $beneficiaries
     * @return \Illuminate\Http\Response
     */
    public function destroy($id,Request $request)
    {
       $beneficiaries = Beneficiaries::find($id);
        if (!is_null($beneficiaries)) {
            $beneficiaries->delete();
    
            $act['userid'] = Auth::user()->id;
            $act['ip'] = $request->ip();
            $act['activity'] = 'Beneficiaries Delete By Evaluation Admin.';
            $act['officecode'] = Auth::user()->dept_id;
            $act['pagereferred'] = $request->url();
            Activitylog::insert($act);
            return redirect()->route('beneficiaries.index')->withSuccess('Beneficiaries deleted successfully');
        } else {
            return redirect()->route('beneficiaries.index')->withError('Invalid department');
        }
    }
}
