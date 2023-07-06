<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home(){
        return view('admin.home');
    }

    public function categories(Request $request)
    {
        try{
            $query = $request->get('q');
            $categories = NULL;
            if($query != ''){
                $categories = Category::active()->latest()->where('id', 'LIKE', '%'. $query .'%')
                                                ->orWhere('title', 'name', '%'. $query .'%')
                                                ->get();
            }else{
                $categories = Category::active()->latest()->get();
            }
            return response()->json($categories);
        }catch(\Exception $ex){
            return response()->json(['error' => 'There IS Something Wrong , Please Concat Technical Support']);
        }
    }

}
