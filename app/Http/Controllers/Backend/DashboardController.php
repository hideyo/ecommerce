<?php namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use Notification;
use Lava;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        return view('backend.dashboard.stats')->with(
            array(

            )
        );
    }
}
