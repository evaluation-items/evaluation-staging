<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use DB;
use URL;
use App\Models\User_user_role;
use App\Models\Branch;
use App\Models\Activitylog;
use Session;
use App\Models\User;
use App\Models\User_user_role_deptid;
use App\Couchdb\Couchdb;
use Carbon\Carbon;

class BranchController extends Controller
{
    public function index(){
        $branch_list = Branch::with('roles')->get();
        
        return view('dashboards.branch.index',compact('branch_list'));
    }
    public function create(Request $request) {
        try {
            $role_items = User_user_role::where('id', '>=', 22)->orderBy('rolename', 'ASC')->get();
            $branchesWithSameName = []; 
            $act['userid'] = Auth::user()->id;
            $act['ip'] = $request->ip();
            $act['activity'] = 'Branch Create By Evaluation Admin.';
            $act['officecode'] = Auth::user()->dept_id;
            $act['pagereferred'] = $request->url();
            Activitylog::insert($act);
            return view('dashboards.branch.create', compact('role_items', 'branchesWithSameName'));
        } catch (\Throwable $th) {
            return redirect()->route('branch.index')->withError( $th->getMessage());
        }
    }
    public function store(Request $request){
      
        try {
            $validate = Validator::make($request->all(), [
                'name' => 'required|string|max:100',
                'role_id.*' => 'required|numeric|max:9999', // Use role_id.* for array validation
            ]);
        
            if ($validate->fails()) {
                return redirect()->route('branch.index')->withError( $validate->errors());
            } else {
                $branch = Branch::updateOrCreate(
                    ['name' => $request->name],
                    ['status' => true] // You can add more fields to update here if needed
                );
        
                $branch->roles()->sync($request->role_id);
                $act['userid'] = Auth::user()->id;
                $act['ip'] = $request->ip();
                $act['activity'] = 'Branch Role Store By Evaluation Admin.';
                $act['officecode'] = Auth::user()->dept_id;
                $act['pagereferred'] = $request->url();
                Activitylog::insert($act);

                return redirect()->route('branch.index')->withSuccess( 'Branch added successfully');
            }
        } catch (\Throwable $th) {
            return redirect()->route('branch.index')->withError( $th->getMessage());
        }
        
        
    }
    public function edit($id,Request $request) {
        $branch = Branch::with('roles')->find($id);
    
        if (!is_null($branch)) {
            $branchesWithSameName = Branch::whereHas('roles', function ($query) use ($branch) {
                $query->whereIn('role_id', $branch->roles->pluck('id'));
            })->get();
    
            $role_items = User_user_role::where('id', '>=', 22)->orderBy('rolename', 'ASC')->get();
    
            $act['userid'] = Auth::user()->id;
            $act['ip'] = $request->ip();
            $act['activity'] = 'Branch Edit By Evaluation Admin.';
            $act['officecode'] = Auth::user()->dept_id;
            $act['pagereferred'] = $request->url();
            Activitylog::insert($act);
            return view('dashboards.branch.create', compact('role_items', 'branchesWithSameName', 'branch'));
        } else {
            return redirect()->route('branch.index')->withError( 'Branch not found');
        }
    }
    public function update(Request $request, $id) {
        try {
            $validate = Validator::make($request->all(), [
                'name' => 'required|string|max:100',
                'role_id.*' => 'required|numeric|max:9999',
            ]);
    
            if ($validate->fails()) {
                return redirect()->route('branch.index')->withError( $validate->errors());
            } else {
                $branch = Branch::find($id);
    
                if ($branch) {
                    $branch->update([
                        'name' => $request->name,
                    ]);
    
                    $branch->roles()->sync($request->role_id);
                    
                    $act['userid'] = Auth::user()->id;
                    $act['ip'] = $request->ip();
                    $act['activity'] = 'Branch Update By Evaluation Admin.';
                    $act['officecode'] = Auth::user()->dept_id;
                    $act['pagereferred'] = $request->url();
                    Activitylog::insert($act);
                    return redirect()->route('branch.index')->withSuccess( 'Branch updated successfully');
                } else {
                    return redirect()->route('branch.index')->withError( 'Branch not found');
                }
            }
        } catch (\Throwable $th) {
            return redirect()->route('branch.index')->withError( $th->getMessage());
        }
    }
    
    
    
}
