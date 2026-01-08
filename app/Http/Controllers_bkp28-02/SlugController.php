<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MenuItem;

class SlugController extends Controller
{
    public function menuItem($slug){
       
        if($slug){
            $menu_item = MenuItem::where('slug',$slug)->latest()->first();
            return view('common_pages.slug_page',compact('menu_item'));
        }
    }
}
