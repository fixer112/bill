<?php

namespace App\Http\Controllers;

use App\Traits\BillPayment;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    use BillPayment;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (!Session::has('balance')) {
            $this->balance();
        }
        return view('welcome');
    }

    public function pricing()
    {
        return view('pricing');
    }
}