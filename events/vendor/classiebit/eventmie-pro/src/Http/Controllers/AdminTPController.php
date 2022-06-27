<?php

namespace Classiebit\Eventmie\Http\Controllers;
use App\Http\Controllers\Controller; 
use Facades\Classiebit\Eventmie\Eventmie;
use Config;
use Validator;
use App\City;
use App\Country;
use App\State;
use DB;
use App\User;
use Auth;
use Redirect;
use File;
use Log;
use Illuminate\Validation\Rule;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\SocialProvider;
use Response;





class AdminTPController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($view = 'eventmie::mytrips.dashboard')
    {
      
        $a_user_api_bearer_token = $this->getAdminAccessToken();
        return view($view, [
            'a_user_api_bearer_token' => $a_user_api_bearer_token
        ]);
       
     
              
    }
   
    public function webuserpermission($view = 'eventmie::mytrips.website_user')
    {
        $a_user_api_bearer_token = $this->getAdminAccessToken();
        return view($view, [
            'a_user_api_bearer_token' => $a_user_api_bearer_token
        ]);
    }
    
    public function category($view = 'eventmie::mytrips.addcategory')
    {
       log::info('inside it my admin  here');
        $a_user_api_bearer_token = $this->getAdminAccessToken();
        return view($view, [
            'a_user_api_bearer_token' => $a_user_api_bearer_token
        ]);
       
            
    }

    public function tourdetails($view = 'eventmie::mytrips.tourdetail')
    {
       
        $a_user_api_bearer_token = $this->getAdminAccessToken();
        return view($view, [
            'a_user_api_bearer_token' => $a_user_api_bearer_token
        ]);
       
            
    }
    
    private function getAdminAccessToken(){
        $currentLoggedInUser = Auth::user();
        $a_user_api_bearer_token = $currentLoggedInUser->createToken('a_user_api_token')-> accessToken;
        return $a_user_api_bearer_token;
    }
  

    public function categories($email)
    {
        $category=TripCategory::where('admin_email', $email)->get();
         $response = [
       'success' => true,
       'data' => $category,
       'message' => 'Course retrieved successfully.',
       'count' => count($category)
       ];
       return response()->json($response, 200);
       
           
    }

    public function international($view = 'eventmie::mytrips.internationaltour')
    {
       
        $a_user_api_bearer_token = $this->getAdminAccessToken();
        return view($view, [
            'a_user_api_bearer_token' => $a_user_api_bearer_token
        ]);
       
            
    }
    public function states($view = 'eventmie::mytrips.statestour')
    {
       
        $a_user_api_bearer_token = $this->getAdminAccessToken();
        return view($view, [
            'a_user_api_bearer_token' => $a_user_api_bearer_token
        ]);
       
            
    }
    public function tourpermission($view = 'eventmie::mytrips.permission')
    {
       
        $a_user_api_bearer_token = $this->getAdminAccessToken();
        return view($view, [
            'a_user_api_bearer_token' => $a_user_api_bearer_token
        ]);
       
            
    }
    public function subscriberlist($view = 'eventmie::touroperator.tourlist')
    {
       
        $a_user_api_bearer_token = $this->getAdminAccessToken();
        return view($view, [
            'a_user_api_bearer_token' => $a_user_api_bearer_token
        ]);
       
            
    }
    
    public function cities($view = 'eventmie::mytrips.citiestour')
    {
       
        $a_user_api_bearer_token = $this->getAdminAccessToken();
        return view($view, [
            'a_user_api_bearer_token' => $a_user_api_bearer_token
        ]);
       
            
    }

    public function profiles($view = 'eventmie::mytrips.profile')
    {
        $users = User::leftJoin('country','users.country', '=', 'country.country_id')
        ->leftJoin('states','users.state', '=', 'states.state_id')
        ->leftJoin('cities','users.city', '=', 'cities.city_id')
        ->select('users.*','cities.city_name','country.country_name','states.state_name','users.name')
        ->orderBy('id', 'desc')->get();
        log::info($users);
        $a_user_api_bearer_token = $this->getAdminAccessToken();
        return view($view, [
            'a_user_api_bearer_token' => $a_user_api_bearer_token
        ], compact('users'));
    }

  
}