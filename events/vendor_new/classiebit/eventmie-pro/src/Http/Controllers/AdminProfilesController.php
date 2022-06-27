<?php
namespace Classiebit\Eventmie\Http\Controllers;
use App\Http\Controllers\Controller; 
use Facades\Classiebit\Eventmie\Eventmie;
use Illuminate\Http\Request;
use GuzzleHttp\Client as Client;

use Config;
use Validator;
use App\City;
use App\Country;
use App\State;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Exception;
use Auth;
use File;
use Log;
use Redirect;
use Response;
use Intervention\Image\Facades\Image;
use Classiebit\Eventmie\Models\User;







class AdminProfilesController extends Controller
{
    // =============================================================
    public function addprofile(Request $request)
    {
        $data = $request->email;
        $email = User::where('email',$data)->first();
        if($email){
           return redirect()->back()->with('success', 'please extend date.');
        }
        $this->validate($request,[
            'name' => 'required|string|max:255',
            'procountryId' => 'required|string|max:255',
            'prostate' => 'required|string|max:255',
            'procity' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'tour_op_experience' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|max:255',
        ]);
       $cont= $request->procountryId;
      $stat =$request->prostate;
     $citi= $request->procity;
     $contr=Country::Where('country_id',$cont)->first();
     $cont_name=$contr->country_name;
     $statess=State::Where('state_id',$stat)->first();
     $stat_name=$statess->state_name;
     $citiess=City::Where('city_id',$citi)->first();
     $city_name=$citiess->city_name;
        $user = new User();
        $user->name =  $request->name;
        $user->email = $request->email;
        $user->phone =  $request->phone;
        $user->password =   Hash::make($request->password);
        $user->Experience = $request->tour_op_experience;
        $user->country =  $cont_name;
        $user->state =  $stat_name;
        $user->city = $city_name;
        $user->role_id = "5";
        $user->save();
        $response = [
            'success' => true,
            //'data' => $data,
            'data' => $user,
            'message' => 'Event Fetching successfully.'
        ];
            return response()->json($response, 200);
    }
    public function tourprofile(Request $request,$email)
    {
        Log::info('getAllComm ka request aa gya hai'.$request);
        $query = $request->get('query');
        Log::info('getAllComm query'.$query);
        Log::info($query);
    
    Log::info($email);
        $query1 = str_replace("","%",$query);
        if(empty($query)){
            $addprofile=User::leftJoin('country', 'users.country', '=', 'country.country_id')
        ->leftJoin('states', 'users.state', '=', 'states.state_id')->leftJoin('cities', 'users.city', '=', 'cities.city_id')
        ->orderBy('id', 'desc')->paginate(20);
        Log::info('ye mera profile hai');
        Log::info($addprofile);      
        }
        else{
            $addprofile = User::leftJoin('country', 'users.country', '=', 'country.country_name') ->leftJoin('states', 'users.state', '=', 'states.state_name')->leftJoin('cities', 'users.city', '=', 'cities.city_name')->
            where('country.country_name', 'like', '%'.$query1.'%')->orwhere('states.state_name', 'like', '%'.$query1.'%')->where('role_id','>', 3)->orwhere('cities.city_name', 'like', '%'.$query1.'%')->where('role_id','>', 3)->orwhere('name', 'like', '%'.$query1.'%')->where('role_id','>', 3)->orWhere('email', 'like', '%'.$query1.'%')->where('role_id','>', 3)->orWhere('country.country_id', $query1)->where('role_id','>', 3)->orWhere('states.state_id', $query1)->where('role_id','>', 3)->orWhere('cities.city_id', $query1)->where('role_id','>', 3)->paginate(10);
            Log::info('query in search data');
            Log::info('addprofile');
            Log::debug((array) $addprofile);
        }
         $response = [
       'success' => true,
       'data' => $addprofile,
       'message' => 'Course retrieved successfully.',
       'count' => count($addprofile)
       ];
       return response()->json($response, 200);    
    }

    public function  editprofile($id)
    {
        Log::info($id);
        Log::info("edit function of Event");
        $event = User::find($id);
        $response = [   
            'success' => true,
            'message' => 'Categoty retrieved successfully for edit.',
            'data' => $event
        ];
        Log::info("response to chala gya ");
        return response()->json($response, 200);
    } 

    
    public function updateprofiles(Request $request)
    {
        $id = $request->admin_hidden_id;
      
     $my= $request->procountryId;
  
     Log::info("here  mydgid city");
     Log::info($my);
    
            
     $SCountry_ids=User::where("id" , $id)->first(); 
     $scont_id=$SCountry_ids->country;  
     $cont= $request->procountryId;
      $stat =$request->prostate;
     $citi= $request->procity;
    
     
      if($my==$scont_id)
      {
        $training = User::find($id);
      
        $training->name = $request->name;
       
        $training->phone = $request->phone;
        $training->Experience = $request->tour_op_experience;
        $training->email = $request->email;
        $training->password = $request->password;
       $training->save();
        $response = [
            'success' => true,
            'data' => $training,
            'message' => 'Training Updated successfully.'
        ];

        Log::debug((array) $response);
        return response()->json($response, 200);
        Log::info('response chala gya');


      }
     
        else{
    
            $id = $request->admin_hidden_id;
           
            $training = User::find($id);
            $contr=Country::Where('country_id',$cont)->first();
            Log::info('yeh country hai naaa');
            Log::info($contr);
           
            $cont_name=$contr->country_name;
            Log::info($cont_name);
            $statess=State::Where('state_id',$stat)->first();
            $stat_name=$statess->state_name;
            $citiess=City::Where('city_id',$citi)->first();
            $city_name=$citiess->city_name;
            $training->name = $request->name;
            $training->country =  $cont_name;
        $training->state =  $stat_name;
        $training->city = $city_name;
            $training->phone = $request->phone;
            $training->Experience = $request->tour_op_experience;
            $training->email = $request->email;
            $training->password = $request->password;   
           $training->save();
            $response = [
                'success' => true,
                'data' => $training,
                'message' => 'Training Updated successfully.'
            ];
    
            Log::debug((array) $response);
            return response()->json($response, 200);
            Log::info('response chala gya');
        } 
    }
    public function destroyprofile($id)
    {
        Log::info($id);
        Log::info("delete function of Event");
        $event = User::findOrFail($id);
        $event->delete();
        $response = [
            'success' => true,
            'data' => $event,
            'message' => 'User Deleted successfully.'
        ];
        return response()->json($response, 200);
    } 
    public function detailprofiles($id)
    {
        Log::info($id);
        Log::info("deatil function of Event");
        $event = User::find($id);
        $response = [   
            'success' => true,
            'message' => 'Categoty retrieved successfully for edit.',
            'data' => $event
        ];
        Log::info("response to chala gya ");
        return response()->json($response, 200);
    } 

    public function myprofiles($email)
    {
        Log::info('inside myprofile');
        $firm = new AdminController;
        $getfirm  = $firm->profiles($email);
        Log::info($email);
        Log::info($getfirm);
        Log::info("deatil function of Event");
        $event = User::find($email);
        $response = [   
            'success' => true,
            'message' => 'Categoty retrieved successfully for edit.',
            'data' => $event
        ];
        Log::info("response to chala gya ");
        return response()->json($response, 200);
    } 
}
