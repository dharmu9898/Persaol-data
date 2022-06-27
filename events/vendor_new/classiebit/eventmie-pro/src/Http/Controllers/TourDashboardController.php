<?php

namespace Classiebit\Eventmie\Http\Controllers;
use App\Http\Controllers\Controller; 
use Facades\Classiebit\Eventmie\Eventmie;


use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Carbon;
use Carbon\CarbonPeriod;
use Auth;
use Redirect;
use File;
use Log;
use Classiebit\Eventmie\Models\Booking;
use Classiebit\Eventmie\Models\Event;
use Classiebit\Eventmie\Models\Ticket;
use Classiebit\Eventmie\Models\User;
use Classiebit\Eventmie\Models\Category;
use Classiebit\Eventmie\Models\Country;
use Classiebit\Eventmie\Models\Schedule;
use Classiebit\Eventmie\Models\Tag;

use Classiebit\Eventmie\Models\Tax;
use Classiebit\Eventmie\Notifications\MailNotification;

class TourDashboardController extends Controller
{

    public function index($view = 'eventmie::touroperator.dashboard')
    {
        $a_user_api_bearer_token = $this->getAdminAccessToken();
        return view($view, [
            'a_user_api_bearer_token' => $a_user_api_bearer_token
        ]);
    }
    public function trip($view = 'eventmie::touroperator.index')
    {
        $adminemail=User::where('role_id', 1)->first();
        $admins=$adminemail->email;
        log::info($adminemail);
        $a_user_api_bearer_token = $this->getAdminAccessToken();
        return view($view, [
            'a_user_api_bearer_token' => $a_user_api_bearer_token
        ],compact('admins'));
    }
    private function getAdminAccessToken(){
        $currentLoggedInUser = Auth::user();
        $a_user_api_bearer_token = $currentLoggedInUser->createToken('a_user_api_token')-> accessToken;
        return $a_user_api_bearer_token;
    }

    public function category($view = 'eventmie::touroperator.category')
    {
        $a_user_api_bearer_token = $this->getAdminAccessToken();
        return view($view, [
            'a_user_api_bearer_token' => $a_user_api_bearer_token
        ]);
    }
    public function iternary($view = 'eventmie::touroperator.iternary')
    {
        $a_user_api_bearer_token = $this->getAdminAccessToken();
        return view($view, [
            'a_user_api_bearer_token' => $a_user_api_bearer_token
        ]);
    }

    public function image($view = 'eventmie::touroperator.image')
    {
        $a_user_api_bearer_token = $this->getAdminAccessToken();
        return view($view, [
            'a_user_api_bearer_token' => $a_user_api_bearer_token
        ]);
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