<?php

namespace App\Http\Controllers;

use App\Traits\BillPayment;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

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
            try {
                $this->balance();
            } catch (\Throwable $th) {
                //throw $th;
            }
        }

        $sides = Storage::disk('root')->files('images/side');
        $sides = collect($sides);

        $sliders = Storage::disk('root')->files('images/slider');
        $sliders = $sliders;
        Storage::disk('root')->delete("images/slider/.DS_Store");
        //return $sliders;
        //return Storage::disk('root')->mimeType($sliders[1]);
        //request()->session()->flash('success', 'Pin Successfully Changed');

        return view('welcome', compact('sides', 'sliders'));
    }

    public function pricing()
    {
        return view('pages.pricing');
    }

    public function about()
    {
        return view('pages.about');
    }

    public function terms()
    {
        return view('pages.terms');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }

    public function contact()
    {
        return view('pages.contact');
    }
}