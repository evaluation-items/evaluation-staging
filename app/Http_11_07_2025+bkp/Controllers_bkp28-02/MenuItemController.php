<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Str;

class MenuItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menu_items = MenuItem::orderBy('name','ASC')->get();
        return view('common_pages.index',compact('menu_items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('common_pages.create');
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
            'name' =>'required',
            'description' => 'required|string',
        ]);

        if ($validate->fails()) {
            return response()->json(['error'=>$validate->errors()]);
        }
        MenuItem::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description
        ]);
        return redirect()->route('menu-item.index')->withSuccess( 'Menu Item created successfully.');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MenuItem  $menuItem
     * @return \Illuminate\Http\Response
     */
    public function show(MenuItem $menuItem)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MenuItem  $menuItem
     * @return \Illuminate\Http\Response
     */
    public function edit(MenuItem $menuItem)
    {
        return view('common_pages.create',compact('menuItem'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MenuItem  $menuItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MenuItem $menuItem)
    {
        $validate = Validator::make($request->all(), [
            'name' =>'required',
            'description' => 'required|string',
        ]);

        if ($validate->fails()) {
            return response()->json(['error'=>$validate->errors()]);
        }
        $menuItem->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description
        ]);
        return redirect()->route('menu-item.index')->withSuccess( 'Menu Item updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MenuItem  $menuItem
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (isset($id) && !empty($id)) {
            $decodedId = base64_decode($id);

            if (is_numeric($decodedId)) {
                MenuItem::find($decodedId)->delete();
                return response()->json(['message' => 'Menu Item deleted successfully']);
            } else {
                return response()->json(['message' => 'Menu Item not deleted']);
            }
        } else {
            // Handle missing ID
            return response()->json(['message' => 'ID is required']);
        }
    }
}
