<?php

namespace App\Http\Repository\Eloquent;

use App\Models\Category;
use Illuminate\Validation\Rule;
use DB;
use Illuminate\Support\Facades\Session;

class CategoryRepository extends AbstractRepository
{
    protected $model;

    public function __construct(Category $model)
    {
        $this->model = $model;
    }

    public function CategoryStore($request)
    {
        try{
            // validation
            // create new
            $category = new $this->model();
            $category->name = $request->name;
            $category->is_activate = 1;
            $category->added_by = auth()->guard('admin')->user()->id;
            $category->save();
            Session::flash()->success("Added Has Been Done");
            return back();
        }catch(\Exception $ex){
            Session::flash()->error("There IS Somrthing Wrong , Please Contact Technical Support");
            return back();
        }
    }

    public function CategoryEdit($id)
    {
        return $this->model->findOrFail($id);
    }

    public function CategoryUpdate($request, $id)
    {
        try{
            // get by id
            $category = $this->model->findOrFail($id);
            $category->name = $request->name;
            $category->edited_by = auth()->guard('admin')->user()->id;
            $category->save();
            Session::flash()->success("Edited Has Been Done");
            return back();
        }catch(\Exception $ex){
            Session::flash()->error("There Is Something Wrong , Please Contact Technical Support");
            return back();
        }
    }

    public function CategoryActivate($request)
    {
        try{
            $category = $this->model->findOrFail($request->record_id);
            if($category->is_activate == 0){
                $category->update(['is_activate' => 1]);
            }else{
                $category->update(['is_activate' => 0]);
            }
            Session::flash()->success("The Change Has Been Done");
            return back();
        }catch(\Exception $ex){
            Session::flash()->error("There IS Something Wrong , Please Contact Technical Support");
            return back();
        }
    }

    public function CategoryDelete($request)
    {
        try{
            $category =  $this->model->findOrFail($request->record_id);
            $category->delete();
            Session::flash()->success("Deleted Has Been Done");
            return back();
        }catch(\Exception $ex){
            Session::flash()->error("There Is Somrthing Wrong , Please Contact Technical Support");
            return back();
        }
    }

    public function CategoryGetMore($request)
    {
        try{
            if( isset($request->id) && $request->id > 0){
                $categories = $this->model->latest()->skip($request->id)->limit(PAGINATION_COUNT)->get();
            }else{
                $categories = $this->model->latest()->skip(PAGINATION_COUNT)->limit(PAGINATION_COUNT)->get();
            }
            $all_data = NULL;
            if($categories && count($categories) > 0){
                $all_data = $categories;
            }
            return $all_data;
        }catch(\Exception $ex){
            return responseJson(0, 'error');
        }
    }

    public function CategorySearch($request)
    {
        try{
            $query = $request->get('query');
            $categories = NULL;
            if($query != ''){
                $categories = $this->model->latest()->where('id', 'LIKE', '%'. $query .'%')
                                                ->orWhere('name', 'LIKE', '%'. $query .'%')
                                                ->get();
            }else{
                $categories = $this->model->latest()->limit(PAGINATION_COUNT)->get();
            }
            $all_data = NULL;
            if($categories && count($categories) > 0){
                if( $query != '' ){
                    $categories[0]->searchButton = 0;
                }else{
                    $categories[0]->searchButton = 1;
                }
                return $categories;
            }
            return $all_data;
        }catch(\Exception $ex){
            return responseJson(0, 'error');
        }
    }

}
