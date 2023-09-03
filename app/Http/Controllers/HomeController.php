<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use App\Models\User;

class HomeController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function dashboard()
    {
       
        if(Auth::check() && Auth::user()->role==0){
            return view('patient_dashboard');
        }
        else if(Auth::check() && Auth::user()->role==1){
            return view('dr_dashboard');
        }
        else{
            return redirect("login")->withSuccess('Opps! You do not have access');
        }
  
        
    }

    public function book_appointment()
    {
        if(Auth::user()->role==0)
        {
            return view('book_appointment');
        }
        else{
            return redirect("dashboard")->withSuccess('Opps! You do not have access');
        }
    }
}
