<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\District;
use App\Models\Taluka;
use App\Models\State;
use  Validator;

class TalukaController extends Controller
{
   
    public function index(){
      //  $states = State::whereHas('districts', function ($query) {
        //     $query->where('state_code', 24);
        // })
        // ->with(['taluka','districts'])
        // ->orderBy('name', 'ASC')
        // ->get();        
        $states = State::active()->whereHas('dist', function ($query) {
                $query->where('state_code', 24);
            })
            ->where('state_code', 24)
            ->with('dist','dist.taluka')
            ->orderBy('name', 'ASC')
            ->get();  
            
       // dd($states);
        $state_list = State::active()->where('state_code',24)->orderBy('name','asc')->get();
        $district_list = District::where('state_code',24)->orderBy('name_e','asc')->pluck('name_e','dcode');
        
        return view('dashboards.admins.taluka.index',compact('states','state_list','district_list'));
    }

    public function store(Request $request){

        $validate = Validator::make($request->all(), [
            'state_id' => 'required|string',
            'dcode'=> 'required|string',
            'tname_e' => 'required|string',
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate->errors());
        }
      
        $tcode = "";
        $checkDcode = Taluka::where('dcode', $request->dcode)->pluck('tcode');

        if ($checkDcode->count() > 0) {
            $checkDcode = $checkDcode->map(function ($item) {
                return strlen($item) > 4 ? substr($item, -2) : substr($item, -1);
            });
           // dd($checkDcode);
            $greatestNumber = $checkDcode->map(function ($item) {
                return intval($item);
            })->max();
        }

        if (strlen($request->dcode) == 1) {
            $tcode = $request->dcode . "00" . ($greatestNumber + 1);

        } elseif (strlen($request->dcode) == 2) {
            $tcode = $request->dcode . "0" . ($greatestNumber + 1);

        }
        
        Taluka::create([
            'dcode'=> $request->dcode,
          //  'state_code'=> $request->state_id,
            'tname_e'=> $request->tname_e,
            'tcode' => $tcode
        ]);
 
        return redirect()->route('taluka.index')->withSuccess('Taluka created successfully.');
    }

    public function district(Request $request){
     
        if(!is_null($request->state_code)){
            $district_list = District::where('state_code',$request->state_code)->pluck('id','name_e');
           
            return $district_list;
        }else{
            return false;
        }
    }
     /**
     * Display the specified resource.
     *
     * @param  \App\Models\Taluka  $taluka
     * @return \Illuminate\Http\Response
     */
    public function show(Taluka $taluka)
    {
        return view('dashboards.admins.taluka.show',compact('taluka'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Taluka  $taluka
     * @return \Illuminate\Http\Response
     */
    public function edit(Taluka $taluka)
    {
        $state_list = State::active()->where('state_code',24)->orderBy('name','asc')->get();
        $district_name = District::where('dcode',$taluka->dcode)->latest()->first();
      
        if(!is_null($district_name)){
            $state_code =  $district_name->state_code;
            $district_name = $district_name->name_e;
        }
        return view('dashboards.admins.taluka.edit',compact('taluka','state_list','state_code','district_name'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Taluka  $taluka
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Taluka $taluka)
    {
        $validate = Validator::make($request->all(), [
            'state_id' => 'required|string',
            'dcode'=> 'required|string',
            'tname_e' => 'required|string',
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate->errors());
        }

       $tcode = $taluka->tcode;
      if($taluka->tcode != "" && ($request->dcode != $taluka->dcode)){
        $tcode = "";
        $checkDcode = Taluka::where('dcode', $request->dcode)->pluck('tcode');

        if ($checkDcode->count() > 0) {
            $checkDcode = $checkDcode->map(function ($item) {
                return strlen($item) > 4 ? substr($item, -2) : substr($item, -1);
            });
            $greatestNumber = $checkDcode->map(function ($item) {
                return intval($item);
            })->max();
        }

        if (strlen($request->dcode) == 1) {
            $tcode = $request->dcode . "00" . ($greatestNumber + 1);

        } elseif (strlen($request->dcode) == 2) {
            $tcode = $request->dcode . "0" . ($greatestNumber + 1);

        }
      }
       
        $taluka->update([
            'dcode'=> $request->dcode,
            // 'state_code'=> $request->state_id,
            'tname_e'=> $request->tname_e,
            'tcode' => $tcode
        ]);
        return redirect()->route('taluka.index')->withSuccess( 'Taluka updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Taluka  $taluka
     * @return \Illuminate\Http\Response
     */
    public function destroy(Taluka $taluka)
    {
        if (!is_null($taluka)) {
            $taluka->delete();
    
            return redirect()->route('taluka.index')->withSuccess('Taluka deleted successfully');
        } else {
            return redirect()->route('taluka.index')->withError('Invalid department');
        }
    }
    public function talukaList(Request $request){
        try {
            if (!is_null($request->dcode)) {
                $taluka_items = Taluka::where('dcode', $request->dcode)->orderBy('tname_e', 'ASC')->get();
                $district_name = District::where('dcode', $request->dcode)->pluck('name_e');
                
                $data = [];
                foreach ($taluka_items as $key => $taluka) {
                    $data[] = [
                        'DT_RowIndex' => $key + 1,
                        'state' => "Gujarat",
                        'district' => $district_name,
                        'name' => $taluka->tname_e,
                        'actions' => '<a href="' . route('taluka.show', $taluka->tid) . '" class="btn btn-xs btn-info" style="display:inline-block">View</a>
                                        <a href="' . route('taluka.edit', $taluka->tid) . '" class="btn btn-xs btn-primary">Edit</a>
                                        <a href="#myModal" class="btn btn-xs btn-danger trigger-btn" data-id="' . $taluka->tid . '"data-bs-toggle="modal">Delete</a> 
                                        <form id="delete-form-' . $taluka->tid . '" action="' . route('taluka.destroy', $taluka->tid) . '" method="POST" style="display: none;">
                                            @csrf
                                            @method("DELETE")
                                        </form>'
                    ];
                }
        
                return response()->json(['data' => $data]);
            }
        } catch (\Exception $th) {
            // Log or handle the exception
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }             
}
