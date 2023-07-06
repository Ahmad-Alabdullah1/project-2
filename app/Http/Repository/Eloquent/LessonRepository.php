<?php

namespace App\Http\Repository\Eloquent;

use App\Models\Lesson;
use Illuminate\Validation\Rule;
use DB;
use Illuminate\Support\Facades\Session;

class LessonRepository extends AbstractRepository
{
    protected $model;

    public function __construct(Lesson $model)
    {
        $this->model = $model;
    }

    public function LessonStore($request)
    {
        try{
            // validation
            // create new
            $lesson = new $this->model();
            $lesson->title = $request->title;
            $lesson->description = $request->description;
            $lesson->category_id = $request->category_id;
            $lesson->is_activate = 1;
            $lesson->added_by = auth()->guard('admin')->user()->id;
            $lesson->save();
            Session::flash()->success("Added Has Been Done");
            return back();
        }catch(\Exception $ex){
            Session::flash()->error("There IS Somrthing Wrong , Please Contact Technical Support");
            return back();
        }
    }

    public function LessonEdit($id)
    {
        return $this->model->findOrFail($id);
    }

    public function LessonUpdate($request, $id)
    {
        try{
            // get by id
            $lesson = $this->model->findOrFail($id);
            $lesson->title = $request->title;
            $lesson->description = $request->description;
            $lesson->category_id = $request->category_id;
            $lesson->edited_by = auth()->guard('admin')->user()->id;
            $lesson->save();
            Session::flash()->success("Edited Has Been Done");
            return back();
        }catch(\Exception $ex){
            Session::flash()->error("There Is Something Wrong , Please Contact Technical Support");
            return back();
        }
    }

    public function LessonActivate($request)
    {
        try{
            $lesson = $this->model->findOrFail($request->record_id);
            if($lesson->is_activate == 0){
                $lesson->update(['is_activate' => 1]);
            }else{
                $lesson->update(['is_activate' => 0]);
            }
            Session::flash()->success("The Change Has Been Done");
            return back();
        }catch(\Exception $ex){
            Session::flash()->error("There IS Something Wrong , Please Contact Technical Support");
            return back();
        }
    }

    public function LessonDelete($request)
    {
        try{
            $lesson =  $this->model->findOrFail($request->record_id);
            $lesson->delete();
            Session::flash()->success("Deleted Has Been Done");
            return back();
        }catch(\Exception $ex){
            Session::flash()->error("There Is Somrthing Wrong , Please Contact Technical Support");
            return back();
        }
    }

    public function LessonGetMore($request)
    {
        try{
            if( isset($request->id) && $request->id > 0){
                $lessons = $this->model->latest()->skip($request->id)->limit(PAGINATION_COUNT)->get();
            }else{
                $lessons = $this->model->latest()->skip(PAGINATION_COUNT)->limit(PAGINATION_COUNT)->get();
            }
            $all_data = NULL;
            if($lessons && count($lessons) > 0){
                $all_data = $lessons;
            }
            return $all_data;
        }catch(\Exception $ex){
            return responseJson(0, 'error');
        }
    }

    public function LessonSearch($request)
    {
        try{
            $query = $request->get('query');
            $lessons = NULL;
            if($query != ''){
                $lessons = $this->model->latest()->where('id', 'LIKE', '%'. $query .'%')
                                                ->orWhere('title', 'LIKE', '%'. $query .'%')
                                                ->get();
            }else{
                $lessons = $this->model->latest()->limit(PAGINATION_COUNT)->get();
            }
            $all_data = NULL;
            if($lessons && count($lessons) > 0){
                if( $query != '' ){
                    $lessons[0]->searchButton = 0;
                }else{
                    $lessons[0]->searchButton = 1;
                }
                return $lessons;
            }
            return $all_data;
        }catch(\Exception $ex){
            return responseJson(0, 'error');
        }
    }

}
