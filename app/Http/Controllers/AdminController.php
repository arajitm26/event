<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Admin;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;

use Auth;
use DB;
use URL;

class AdminController extends Controller
{
    //

    
    
    public function index()
    {
      return view('admin.index',["data"=>"test"]);
    }

    public function showlogin()
    {
      // $this->middleware('guest:admin');
      return view('admin.login');
    }

    public function login(Request $request)
    {
      $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
	    	$credentials = $request->only('email', 'password');

	        if (Auth::guard('admin')->attempt($credentials)) 
	        {
	            // Authentication passed...
	            //return redirect()->intended('dashboard');
	            return redirect('admin');
	        }

	        return redirect('admin/login')->with('error', 'Oppes! You have entered invalid credentials');
	    
    }
    public function logout(Request $request)
    {
    	Auth::guard('admin')->logout();
    	

	    //$request->session()->invalidate();

	    $request->session()->regenerateToken();

        return redirect('/admin/login');
	    
    }


    public function test()
    {
        
        // if (Gate::allows('check_permissions', ["edit1","5"])) 
        // {
        //     dd(true);
        // }
        // if (Gate::allows('check_permissions', ["edit1","5"])) 
        // {
        //     dd(true);
        // }
		return "test";
    	
    }
}
