<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Hash as FacadesHash;
use App\Http\Requests\Admin\AdminRequests\AdminLoginRequest;

class AuthController extends Controller
{
    public function login(){
        return view('admin.auth.login');
    }

    public function check_login(AdminLoginRequest $request){
        try{

            // validation
            $admin = Admin::where('email', strtolower(trim($request->email)))->first();
            if($admin){
                if(FacadesHash::check($request->password, $admin->password)){
                    if($admin->is_activate == 1){
                        if(FacadesAuth::guard('admin')->attempt($request->only('email', 'password'))){
                            return redirect(route('admin/index'));
                        }else{
                            Session::flash()->error("There IS Something Worng");
                            return back();
                        }
                    }else{
                        Session::flash()->error("You Are Not Activate");
                        return back();
                    }
                }else{
                    Session::flash()->error("There IS Something Worng");
                    return back();
                }
            }else{
                Session::flash()->error("There IS Something Wrong");
                return back();
            }
        }catch(\Exception $ex){
            return $ex;
            Session::flash()->error("There IS Something Wrong , Please Contact Technical Support");
            return back();
        }
    }

    public function logout(){
        auth('admin')->logout();
        return redirect(route('admin/login'));
    }
//    public function login()
//    {
//        return view('admin.auth.login');
//    }
//
//    public function check_login(AdminLoginRequest $request)
//    {
//        try {
//            // validation
//            $admin = Admin::where('email', strtolower(trim($request->email)))->first();
//
//            // Temporarily disable authentication and authorization checks
//            // Comment out the following if block
//            /*
//            if ($admin) {
//                if (FacadesHash::check($request->password, $admin->password)) {
//                    if ($admin->is_activate == 1) {
//                        if (FacadesAuth::guard('admin')->attempt($request->only('email', 'password'))) {
//                            return redirect(route('admin/index'));
//                        } else {
//                            Session::flash()->error("There IS Something Worng");
//                            return back();
//                        }
//                    } else {
//                        Session::flash()->error("You Are Not Activate");
//                        return back();
//                    }
//                } else {
//                    Session::flash()->error("There IS Something Worng");
//                    return back();
//                }
//            } else {
//                Session::flash()->error("There IS Something Wrong");
//                return back();
//            }
//            */
//
//            // Store the admin account without authentication and authorization checks
//            // Replace the commented code with the following
//            $admin = Admin::create([
//                'name' => $request->name,
//                'email' => $request->email,
//                'password' => FacadesHash::make($request->password),
//            ]);
//
//            Session::flash()->success('Admin account created successfully.');
//            return redirect(route('admin/login'));
//
//        } catch (\Exception $ex) {
//            return $ex;
//            Session::flash()->error("There IS Something Wrong , Please Contact Technical Support");
//            return back();
//        }
//    }
//
//    public function logout()
//    {
//        auth('admin')->logout();
//        return redirect(route('admin/login'));
//    }
}
