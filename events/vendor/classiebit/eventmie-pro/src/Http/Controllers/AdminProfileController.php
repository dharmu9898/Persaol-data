<?php
namespace Classiebit\Eventmie\Http\Controllers;
use App\Http\Controllers\Controller; 
use Facades\Classiebit\Eventmie\Eventmie;
use Illuminate\Http\Request;
use Response;
use Intervention\Image\Facades\Image;
use Redirect;
use File;
use Log;
use Illuminate\Validation\Rule;
use Exception;
use GuzzleHttp\Client as Client;
use Config;
use Validator;
use App\City;
use App\Country;
use App\State;
use DB;

use Illuminate\Support\Facades\Hash;

use Auth;
use Classiebit\Eventmie\Models\User;

class AdminProfileController extends Controller
{
    // =============================================================
    public function addsublist(Request $request, $email)
    {
        $data = $request->except('_token');
        log::info('inside it');
        $emils=$request->email;
        $users = User::where('email', $emils)->first();
        if ($users) {
            log::info('inside if sd');
            return response()->json(array('success' => false));        
 
        }
        else{
        log::info('here here');
        $permissiontrip = count($data['Sub']);
            //   $permissiontrip = $request->Sub;   
            
               log::info('in token ll');
              
             
               for($count = 0; $count < $permissiontrip; $count++)
               {
                log::info('inside for');
                $user = new User();
        $user->name =  $data['name'][$count];
        $user->email = $data['email'][$count];
        $user->phone =  $data['phonno'][$count];
       
        $user->password =  Hash::make($data['password'][$count]);
           $user->address = $data['address'][$count];
           $user->trip = $data['tripss'][$count];
           $user->role_id = 6;
        $user->save();
      
               
               
               }
              
               
              
               log::info('inside forsd');
               $response = [
                   'success' => true,
                   //'data' => $data,
                   'data' => $user,
                   'message' => 'Event Fetching successfully.'
               ];
                   return response()->json($response, 200);         
    
                       
                }             
    
           
    }

    public function profile(Request $request)
    {
       
    }
    private function getAdminAccessToken(){
        $currentLoggedInUser = Auth::user();
        $a_user_api_bearer_token = $currentLoggedInUser->createToken('a_user_api_token')-> accessToken;
        return $a_user_api_bearer_token;
    }
    
    public function storeprofile($view = 'eventmie::touroperator.activate-event-user')
    {
        $a_user_api_bearer_token = $this->getAdminAccessToken();
        return view($view, [
            'a_user_api_bearer_token' => $a_user_api_bearer_token
        ]);
    }
}
