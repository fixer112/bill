<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function jsonWebBack($type, $message)
    {
        if (request()->wantsJson()) {
            return [$type => $message];
        }
        request()->session()->flash($type, $message);
        return url()->previous() == url()->current() && !request()->isMethod('post') ? $message : back();

    }
    public function jsonWebRedirect($type, $message, $link)
    {
        if (request()->wantsJson()) {
            return [$type => $message];
        }
        request()->session()->flash($type, $message);

        return redirect($link);
    }
}