<?php

namespace App\Http\Controllers;

use App\Models\Village;
use Illuminate\Http\Request;
use App\Models\District;
use Validator;
use App\Models\Taluka;

class VillageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $village_list = Village::with('district','taluka_list')->orderBy('vname_e','ASC')->paginate(10);
       $district_list = District::where('state_code',24)->orderBy('name_e','asc')->pluck('name_e','dcode');
      // $district_list = District::orderBy('name_e','ASC')->paginate(10);
      
       return view('dashboards.admins.village.index',compact('village_list','district_list'));
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
            'dcode' => 'required|string',
            'tcode'=> 'required|string',
            'vname_e' => 'required|string',
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate->errors());
        }
        $vcode = "";
        $checkDcode = Village::where('tcode', $request->tcode)->pluck('vcode');

        if ($checkDcode->count() > 0) {
            $checkDcode = $checkDcode->map(function ($item) {
                return strlen($item) > 4 ? substr($item, -2) : substr($item, -1);
            });
           
            $greatestNumber = $checkDcode->map(function ($item) {
                return intval($item);
            })->max();
        }
      
        if (strlen($request->tcode) == 5) {
            $vcode = strlen($request->dcode) . "00" . ($greatestNumber + 1);

        }
      
        Village::create([
            'dcode'=> $request->dcode,
            'tcode' => $request->tcode,
            'vname_e'=> $request->vname_e,
            'vcode' =>  $vcode
        ]);
 
        return redirect()->route('village.index')->withSuccess('Village created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Village  $village
     * @return \Illuminate\Http\Response
     */
    public function show(Village $village)
    {        
        dd('show');
        return view('dashboards.admins.village.show',compact('village'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Village  $village
     * @return \Illuminate\Http\Response
     */
    public function edit(Village $village)
    {
        $district_list = District::where('state_code',24)->orderBy('name_e','asc')->pluck('name_e','dcode');
       
        $taluka_list= Taluka::where('tcode',$village->tcode)->get();
        $taluka_name = [];
        if($taluka_list->count() > 0){
            foreach ($taluka_list as $key => $tname) {
                $taluka_name = $tname->tname_e;
            }
        }
       
        return view('dashboards.admins.village.edit',compact('village','taluka_name','district_list'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Village  $village
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Village $village)
    {
        $validate = Validator::make($request->all(), [
            'dcode' => 'required|string',
            'tcode'=> 'required|string',
            'vname_e' => 'required|string',
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate->errors());
        }

       $vcode = $village->vcode;
      if( $village->vcode != "" && ($request->tcode !=  $village->tcode)){
        $vcode = "";
        $checkDcode = Village::where('tcode', $request->tcode)->pluck('vcode');

        if ($checkDcode->count() > 0) {
            $checkDcode = $checkDcode->map(function ($item) {
                return strlen($item) > 4 ? substr($item, -2) : substr($item, -1);
            });
            $greatestNumber = $checkDcode->map(function ($item) {
                return intval($item);
            })->max();
        }

        if (strlen($request->tcode) == 5) {
            $vcode = strlen($request->dcode) . "00" . ($greatestNumber + 1);
        }
      }
       
        $village->update([
            'dcode'=> $request->dcode,
            'tcode' => $request->tcode,
            'vname_e'=> $request->vname_e,
            'vcode' =>  $vcode
        ]);
        return redirect()->route('village.index')->withSuccess( 'Village updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Village  $village
     * @return \Illuminate\Http\Response
     */
    public function destroy(Village $village)
    {
       
        if (!is_null($village)) {
            $village->delete();
    
            return response()->json(['success' => 'Village deleted successfully']);
        } else {
            return response()->json(['error' => 'Something Went Wrong']);

        }
    }
    public function villageList(Request $request){
        try {
            if (!is_null($request->tcode)) {
                $village_items = Village::with('district','taluka_list')->where('tcode', $request->tcode)->orderBy('vname_e', 'ASC')->get();
                $data = [];
                foreach ($village_items as $key => $village) {
                    foreach ($village->district as $distkey => $dist) {
                        foreach ($village->taluka_list as $villagekey => $taluka) {
                            $data[] = [
                                'DT_RowIndex' => $key + 1,
                                'district_name' => $dist->name_e,
                                'taluka_name' => $taluka->tname_e,
                                'name' => $village->vname_e,
                                'actions' => '<a href="' . route('village.show', $village->id) . '" class="btn btn-xs btn-info" style="display:inline-block">View</a>
                                                <a href="' . route('village.edit', $village->id) . '" class="btn btn-xs btn-primary">Edit</a>
                                                <a href="#myModal" class="btn btn-xs btn-danger trigger-btn" data-id="' . $village->id . '"data-bs-toggle="modal">Delete</a> 
                                               '
                            ];
                        }
                    }
                  
                }
        
                return response()->json(['data' => $data]);
            }
        } catch (\Exception $th) {
            // Log or handle the exception
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
}
