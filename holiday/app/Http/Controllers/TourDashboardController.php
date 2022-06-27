<?php

namespace App\Http\Controllers\touroperator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Auth;
use App\city;
use App\country;
use App\state;
use DB;
use App\TourOperator;
use App\AddTrip;
use App\Rvsp;
use App\User;
use App\Package;
use Log;

class TourDashboardController extends Controller
{
    public function index($view = 'eventmie::welcome')
    {
        Log::info('file yaha tak aaya');
        return view($view);      
    }
    public function rvsplist()
    {
        
    }


    public function confirm($id)
    {

        
    }

    public function list($TripTitle)
    {
       
    }

    public function updates(Request $request)
    {





    }
}