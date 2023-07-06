<?php

namespace App\Http\Repository\Eloquent;

use DB;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AdminRepository extends AbstractRepository
{

    protected $model;

    public function __construct(Admin $model)
    {
        $this->model = $model;
    }

    public function AdminStore($request)
    {
        try{
            // validation
            // hash password
            $request->merge(['password' => bcrypt($request->password)]);
            //create admin
            $admin = new $this->model();
            $admin->is_activate = 1;
            $admin->name = $request->name;
            $admin->email = $request->email;
            $admin->phone = $request->phone;
            $admin->password = $request->password;
            //save image
            if (!$request->hasFile('photo') == null) {
                $file = uploadIamge( $request->file('photo'), 'admins'); // function on helper file to upload file
                $admin->photo = $file;
            }
            $admin->save();
           Session::flash()->success("Success Add");
            return back();
        }catch(\Exception $ex){
           Session::flash()->error("There IS Something Wrong , Please Contact Technical Support");
            return back();
        }
    }

    public function AdminInfo()
    {
        try{
            $admin = auth()->guard('admin')->user();
            return view('admin.admins.info', compact('admin'));
        }catch(\Exception $ex){
           Session::flash()->error("There Is Somrthing Wrong , Please Contact Technical Support");
            return back();
        }
    }

    public function AdminUpdateInfo($request)
    {
        try{
            // get admin
            $admin =auth()->guard('admin')->user();
            if(!$admin){
               Session::flash()->error("There IS Somrthing Wrong");
                return back();
            }
            // validation
            $validator = validator()->make($request->all(),[
                'email' => ['required', 'email', Rule::unique('admins', 'email')->ignore($admin->id,'id')],
                'name' => 'required',
                'phone' => ['required', Rule::unique('admins', 'phone')->ignore($admin->id,'id')],
            ]);
            if($validator->fails()){
               Session::flash()->error($validator->errors()->first());
                return back();
            }
            // updaet information
            $admin->email = $request->email;
            $admin->name = $request->name;
            $admin->phone = $request->phone;
            // save image
            if(!$request->hasFile('photo') == null){
                $file = uploadIamge( $request->file('photo'), 'admins'); // function on helper file to upload file
                $admin->photo = $file;
            }
            $admin->save();
           Session::flash()->success("Success");
            return back();
        }catch(\Exception $ex){
           Session::flash()->error("There IS Something Wrong , Please Contact Technical Support");
            return back();
        }
    }

    public function AdminChangePassword($request)
    {
        try{
            // validation
            // get admin
            $admin = auth()->guard('admin')->user();
            if(!$admin){
               Session::flash()->error("There IS Somrthing Wrong");
                return back();
            }
            // check old password
            if(!Hash::check($request->input('old_password'), $admin->password)){
               Session::flash()->error("There IS Something Wrong");
                return back();
            }
            // update password
            $admin->password = bcrypt($request->input('password'));
            $admin->save();
           Session::flash()->success("Success");
            return back();
        }catch(\Exception $ex){
           Session::flash()->error("There Is Somrthing Wrong , Please Contact Technical Support");
            return back();
        }
    }

    public function AdminActivate($request)
    {
        try{
            $admin = $this->model->findOrFail($request->record_id);
            if($admin->is_activate == 1){
                $admin->update(['is_activate' => 0]);
            }else{
                $admin->update(['is_activate' => 1]);
            }
           Session::flash()->success("The Change Has Been Done");
            return back();
        }catch(\Exception $ex){
           Session::flash()->error("There Is Something Wrong , Please Contact Technical Support");
            return back();
        }
    }

    public function AdminDelete($request)
    {
        try{
            $admin = $this->model->findOrFail($request->record_id);
            $admin->delete();
           Session::flash()->success("The Deleted Has Been Done");
            return back();
        }catch(\Exception $ex){
           Session::flash()->error("There Is Something Wrong , Please Contact technical Support");
            return back();
        }
    }

    public function AdminGetMore($request)
    {
        try{
            if(isset($request->id) && $request->id > 0){
                $admins = $this->model->latest()->skip($request->id)->limit(PAGINATION_COUNT)->get();
            }else{
                $admins = $this->model->latest()->skip(PAGINATION_COUNT)->limit(PAGINATION_COUNT)->get();
            }
            $all_data = [];
            if($admins && count($admins) > 0){
                foreach($admins as $admin ){
                    $admin->photo = asset($admin->photo);
                    $all_data [] = $admin;
                }
            }
            return $all_data;
        }catch(\Exception $ex){
            return responseJson(0, 'error');
        }
    }

    public function AdminSearch($request)
    {
        try{
            $query = $request->get('query');
            $admins = [];
            if($query != ''){
                $admins = $this->model->latest()->where('id', 'LIKE', '%'. $query .'%')
                                                ->orWhere('email', 'LIKE', '%'. $query .'%')
                                                ->orWhere('name', 'LIKE', '%'. $query .'%')
                                                ->get();
            }else{
                $admins = $this->model->latest()->paginate(PAGINATION_COUNT);
            }
            $all_data = [];
            if($admins && count($admins) > 0){
                foreach($admins as $admin ){
                    $admin->photo = asset($admin->photo);
                    if( $query != '' ){
                        $admin->searchButton = 0;
                    }else{
                        $admin->searchButton = 1;
                    }
                    $all_data [] = $admin;
                }
            }
            return $all_data;
        }catch(\Exception $ex){
            return responseJson(0, 'error');
        }
    }

}
