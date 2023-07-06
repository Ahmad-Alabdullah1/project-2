<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Http\Repository\Eloquent\LessonRepository;
use App\Http\Requests\Admin\LessonRequests\LessonStoreRequest;

class LessonController extends Controller
{


    public $lesson;

    public function __construct(LessonRepository $lesson)
    {
        $this->lesson = $lesson;
    }

    public function index()
    {
        try{
            $lessons = $this->lesson->GetAll();
            return view('admin.lessons.index', compact('lessons'));
        }catch(\Exception $ex){
            Session::flash()->error("There Is Something Wrong , Please Contact Technical Support");
            return back();
        }
    }

    public function create()
    {
        try{
            return view('admin.lessons.create');
        }catch(\Exception $ex){
            Session::flash()->error("There Is Something Wrong , Please Contact Technical Support");
            return back();
        }
    }

    public function store(LessonStoreRequest $request)
    {
        return $this->lesson->LessonStore($request);
    }

    public function edit($id)
    {
        try{
            if((int)$id > 0){
                $lesson = $this->lesson->LessonEdit($id);
                return view('admin.lessons.edit', compact('lesson'));
            }else{
                Session::flash()->error("There Is Something Wrong , Please Contact Technical Support");
                return back();
            }
        }catch(\Exception $ex){
            return $ex;
            Session::flash()->error("There Is Something Wrong , Please Contact Technical Support");
            return back();
        }
    }

    public function update(LessonStoreRequest $request, $id)
    {
        return $this->lesson->LessonUpdate($request, $id);
    }

    public function activate(Request $request)
    {
        return $this->lesson->LessonActivate($request);
    }

    public function delete(Request $request)
    {
        return $this->lesson->LessonDelete($request);
    }

    public function getMore(Request $request)
    {
        return $this->lesson->LessonGetMore($request);
    }

    public function search(Request $request)
    {
        return $this->lesson->LessonSearch($request);
    }

}
