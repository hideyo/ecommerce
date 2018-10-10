<?php namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use Notification;
use Lava;
use Carbon\Carbon;

class DashboardController extends Controller
{


    /*
    |--------------------------------------------------------------------------
    | Home Controller
    |--------------------------------------------------------------------------
    |
    | This controller renders your application's "dashboard" for users that
    | are authenticated. Of course, you are free to change or remove the
    | controller as you wish. It is just here to get your app started!
    |
    */


    public function index()
    {
        $shop  = auth('hideyobackend')->user()->shop;

        return view('backend.dashboard.stats')->with(
            array(

            )
        );
    }
}
