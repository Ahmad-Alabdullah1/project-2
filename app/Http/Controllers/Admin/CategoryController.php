<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Http\Repository\Eloquent\CategoryRepository;
use App\Http\Requests\Admin\CategoryRequests\CategoryStoreRequest;

class CategoryController extends Controller
{

    public $category;

    public function __construct(CategoryRepository $category)
    {
        $this->category = $category;
    }

    public function index()
    {
        try{
            $categories = $this->category->GetAll();
            return view('admin.categories.index', compact('categories'));
        }catch(\Exception $ex){
            Session::flash()->error("There Is Something Wrong , Please Contact Technical Support");
            return back();
        }
    }

    public function create()
    {
        try{
            return view('admin.categories.create');
        }catch(\Exception $ex){
            Session::flash()->error("There Is Something Wrong , Please Contact Technical Support");
            return back();
        }
    }

    public function store(CategoryStoreRequest $request)
    {
        return $this->category->CategoryStore($request);
    }

    public function edit($id)
    {
        try{
            if((int)$id > 0){
                $category = $this->category->CategoryEdit($id);
                return view('admin.categories.edit', compact('category'));
            }else{
                Session::flash()->error("There Is Something Wrong , Please Contact Technical Support");
                return back();
            }
        }catch(\Exception $ex){
            Session::flash()->error("There Is Something Wrong , Please Contact Technical Support");
            return back();
        }
    }

    public function update(CategoryStoreRequest $request, $id)
    {
        return $this->category->CategoryUpdate($request, $id);
    }

    public function activate(Request $request)
    {
        return $this->category->CategoryActivate($request);
    }

    public function delete(Request $request)
    {
        return $this->category->CategoryDelete($request);
    }

    public function getMore(Request $request)
    {
        return $this->category->CategoryGetMore($request);
    }

    public function search(Request $request)
    {
        return $this->category->CategorySearch($request);
    }

}
