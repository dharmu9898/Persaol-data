<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\User;
use DB;
use Auth;
use Intervention\Image\Facades\Image;
use App\Package;
use App\addimage;
use File;
use App\TripCategory;
use App\Discount_title;
use App\Mydiscount;
use App\Week;
use App\Discount;
use App\TripCountry;
use App\TripState;
use App\TripCity;
use App\Ticket;
use App\Tax_ticket;
use App\City;
use App\Event;
use App\Trip;
use App\Schedule;
use App\Iternaries;
use App\Iternary_day;
use App\Iternary;
use Redirect;
use App\Country;
use App\State;
use App\Rvsp;
use Illuminate\Support\Facades\Log;
use App\SocialProvider;
use Validator;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Validation\Rule;
use Config;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Mail\OperatorMail;
use App\Mail\OperatorMails;
use App\Mail\AdminMails;
use App\Mail\OperatorMailing;
use Mail;

use App\Userevent;

class AdminTPController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
    
        $this->trip    = new Trip;
        $this->iternary_day    = new Iternary_day;
        $this->iternary    = new Iternary;
        $this->schedule=new Schedule;
        $this->ticket=new Ticket;
        $this->tax_ticket=new Tax_ticket;
        $this->country=new Country;
        

    }
    public function index()
    {
        $a_user_api_bearer_token = $this->getAdminAccessToken();
        // return $a_user_api_bearer_token;
        return view('admin.dashboard', [
                        'a_user_api_bearer_token' => $a_user_api_bearer_token
                    ]);
     
              
    }
    private function getAdminAccessToken(){
        $currentLoggedInUser = Auth::user();
        $a_user_api_bearer_token = $currentLoggedInUser->createToken('a_user_api_token')-> accessToken;
        return $a_user_api_bearer_token;
    }
    public function category()
    {
        $a_user_api_bearer_token = $this->getAdminAccessToken();
        return view('admin.tourcategory', [
            'a_user_api_bearer_token' => $a_user_api_bearer_token
        ]);
            
    }

    public function storecategory(Request $request)
    {
        Log::info('ye h store function event ka ');
        Log::info ($request);
        Log::info ($request->category_logo);
        $input = $request->all();
        $validator = Validator::make($input, [
            'category_name' => 'required|string|max:255',
            'catdescription'=> 'required', 
            'operator_auth_id' =>'required',
            'operator_auth_name' =>'required',
            'operator_auth_email' =>'required',
            'category_logo' => 'required|file|mimes:jpeg,png,pdf|max:2048'
            ]);
                 if ($validator->fails()) {
            $response = [
                'success' => false,
                'data'    => 'Validation Error.',
                'message' => $validator->errors()
            ];
            Log::info('validation ok');
            return response()->json($response, 422);
        } 
        else {
            if($request->hasfile('category_logo'))
            { 
                $file= $request->file('category_logo'); 
                $name = $file->getClientOriginalName();
                $file->move(public_path().'/category/', $name);  
            } 
                $training = TripCategory::create([
                        'category' => $input['category_name'],
                        'Description' => $input['catdescription'],
                        'admin_id' => $input['operator_auth_id'],
                        'admin_name' => $input['operator_auth_name'],
                        'admin_email' => $input['operator_auth_email'],
                        'Image' => $name,
                     ]);
                $response = [
                    'success' => true,
                    //'data' => $data,
                    'data' => $training,
                    'message' => 'Training Fetching successfully.'
                ];
                return response()->json($response, 200);
        }
    }

    
  
    public function destroy($id)
    {
        Log::info($id);
        Log::info("delete function of Event");
        $event = TripCategory::findOrFail($id);
        $event->delete();
        $response = [
            'success' => true,
            'data' => $event,
            'message' => 'Event Deleted successfully.'
        ];
        return response()->json($response, 200);
    }
   

    public function edits($id)
    {
        Log::info($id);
        Log::info("edit function of Event");
        $event = TripCategory::find($id);
        $response = [   
            'success' => true,
            'message' => 'Categoty retrieved successfully for edit.',
            'data' => $event
        ];
        Log::info("response to chala gya ");
        return response()->json($response, 200);
    }

    public function detail($id)
    {
        Log::info($id);
        Log::info("deatil function of Event");
        $event = TripCategory::find($id);
        $response = [   
            'success' => true,
            'message' => 'Categoty retrieved successfully for edit.',
            'data' => $event
        ];
        Log::info("response to chala gya ");
        return response()->json($response, 200);
    }


    public function update(Request $request)
    {

        $id = $request->admin_hidden_id;
        Log::info("here id");
        Log::info($id);
            $category_logo = $request->file('category_logo');
      if($category_logo != '')
      {
      
        if($request->hasfile('category_logo'))
            {
                $file= $request->file('category_logo'); 
                $name = $file->getClientOriginalName();
                $file->move(public_path().'/category/', $name);  
            }

           $id = $request->admin_hidden_id;
           Log::info($id);
          $training = TripCategory::find($id);
        
          $training->category = $request->category_name;
          $training->Description = $request->description;
          $training->Image = $name;
                  
         
          $training->save();
          Log::info('save hua hai ');
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
            Log::info($id);
           $training = TripCategory::find($id);
 
          
           $training->category = $request->category_name;
           $training->Description = $request->description;
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
    public function iternarydetail(Request $request, $email)
    {
        $alltrip = DB::table('packages')->where('operator_email',$email)->select('packages.*')->groupBy('TripTitle')->get();
        log::info($alltrip);
         $query = $request->get('query');
         $query1 = str_replace("","%",$query);
        if(empty($query)){
            $iternary = Iternaries::where('iternaries.operator_email', $email)->paginate(10);
            Log::info('query in empty');
            log::info($iternary);
        }
        else{
            
            

               $iternary = Iternaries::where('trips', 'like', '%'.$query.'%')->where('iternaries.operator_email', $email)->
               orWhere('Days', 'like', '%'.$query.'%')->where('iternaries.operator_email', $email)->orWhere('location', 'like', '%'.$query.'%')->where('iternaries.operator_email', $email)
               ->orWhere('explanation', 'like', '%'.$query.'%')->where('iternaries.operator_email', $email)->paginate(10);
         
               log::info('inside empty');
               log::info($iternary);
        }
             
               $response = [
            'success' => true,
            'data' => $iternary,
            'alltrip' => $alltrip,
            'message' => 'Tool retrieved successfully.',
            'count' => count($iternary)
        ];
              return response()->json($response, 200);
    }


    public function imagesdetail(Request $request, $email)
    {
        
        $alltrip = DB::table('packages')->where('operator_email',$email)->select('packages.*')->groupBy('TripTitle')->get();
        
        log::info($alltrip);
         $query = $request->get('query');
         $query1 = str_replace("","%",$query);

         if(empty($query)){
            $iternary = addimage::where('operator_email', $email)->paginate(10);
            Log::info('query in empty');
            log::info($iternary);
        }
        else{
                
            $iternary = addimage::where('trips', 'like', '%'.$query.'%')->where('addimages.operator_email', $email)->paginate(10);
             
               log::info('inside empty');
               log::info($iternary);
        }
              
               $response = [
            'success' => true,
            'data' => $iternary,
            'alltrip' => $alltrip,
            'message' => 'Tool retrieved successfully.',
            'count' => count($iternary)
        ];
              return response()->json($response, 200);
    }

    public function detailstrip($id)
    {
        Log::info($id);
        Log::info("deatil function of Event");
        $event = Package::find($id);
        $response = [   
            'success' => true,
            'message' => 'Categoty retrieved successfully for edit.',
            'data' => $event
        ];
        Log::info("response to chala gya ");
        return response()->json($response, 200);
    }
    public function getallmail($email)
    {

        Log::info($email);
        Log::info("deatil function of Event");
        $event = Rvsp::find($email);
        $response = [   
            'success' => true,
            'message' => 'Categoty retrieved successfully for edit.',
            'data' => $event
        ];
        Log::info("response to chala gya ");
        return response()->json($response, 200);
    }
    public function getiternary($iternary_title)
    {

        Log::info($iternary_title);
        Log::info("iternary function of Event");
        $myiternary = Package::where('TripTitle', $iternary_title)->pluck('id');
        $myiternaryday=Package::where('id', $myiternary)->get();

        Log::info($myiternaryday);
       
        $response = [   
            'success' => true,
            'message' => 'Categoty retrieved successfully for edit.',
            'data' => $myiternaryday
        ];
        Log::info("response to chala gya ");
        return response()->json($response, 200);
    }
    
    public function edittrips($id)
    {
        Log::info($id);
        
        Log::info("edit function of Event");
        $event = Package::find($id);
        Log::info($event);
        $response = [   
            'success' => true,
            'message' => 'Categoty retrieved successfully for edit.',
            'data' => $event
        ];
        Log::info("response to chala gya ");
        return response()->json($response, 200);
    }

    
    public function tripdetail(Request $request, $email)
    {
$opmail=Package::where('operator_email', $email)->pluck('operator_email');
        $admin_mail_get=Userevent::where('id','1')->first();
        $admin_mail=$admin_mail_get->email;

        $alltrip = DB::table('packages')->where('operator_email',$email)->select('packages.*')->groupBy('TripTitle')->get();

        $allcat = TripCategory::where('admin_email',$email)->orwhere('admin_email',$admin_mail)->get();

        $galleries = Package::where('operator_email', $email)->get();
         $query = $request->get('query');
         $query1 = str_replace("","%",$query);
         $trip=DB::table('packages')->where('operator_email', $email)->pluck('TripTitle');
 
         $sub = DB::table('rvsps')->whereIn('TripHeading', $trip)->where('status', 1)
         ->select( DB::raw('count(DISTINCT emailid) as user_count'))->pluck('user_count');
                  log::info('inside tripdetail');
                 if(empty($query)){
                  $tools=DB::table('packages')
         ->leftJoin('addimages', 'packages.TripTitle', '=', 'addimages.trips')
         ->leftJoin('rvsps', 'packages.TripTitle', '=', 'rvsps.TripHeading')
          ->where('packages.operator_email', $email)
          ->select(
              'packages.*',
              'addimages.image_name','rvsps.TripHeading'
          )->groupBy('packages.TripTitle')
          ->paginate(10);
          log::info($tools);
          
                  }
        else{
            
   
                     $tools = Package::leftJoin('countries', 'packages.slug', '=', 'countries.country_name')
                     
                     
             ->leftJoin('states', 'packages.slug1', '=', 'states.state_name')->
             leftJoin('cities', 'packages.slug2', '=', 'cities.city_name')->leftJoin('addimages', 'packages.TripTitle', '=', 'addimages.trips')->
             where('slug', 'like', '%'.$query1.'%')->where('packages.operator_email', $email)->orwhere('slug1', 'like', '%'.$query1.'%')->where('packages.operator_email', $email)->
             orwhere('slug2', 'like', '%'.$query1.'%')->where('packages.operator_email', $email)->orwhere('TripTitle', 'like', '%'.$query1.'%')->where('packages.operator_email', $email)->
            orwhere('countries.country_id',  $query1)->where('packages.operator_email', $email)->orwhere('states.state_id', $query1)->where('packages.operator_email', $email)->
            orwhere('cities.city_id', $query1)->where('packages.operator_email', $email)->groupBy('packages.TripTitle')->paginate(10);
            log::info($tools);
                            }

               $response = [
            'success' => true,
            'data' => $tools,
            'allcat' => $allcat,
            'sub' => $sub,
            'alltrip' => $alltrip,
                      'galleries' => $galleries,
            'message' => 'Tool retrieved successfully.',
            'count' => count($tools)
        ];
        log::info($tools);
              return response()->json($response, 200);
    }
    

    public function usersdetail(Request $request,$email)
    {
        
        $last=substr($email, -1);
        $first=substr($email, 0, -1);
        log::info($last);
        log::info($first);
         $query = $request->get('query');
         $query1 = str_replace("","%",$query);
         $trip=DB::table('packages')->where('operator_email', $first)->pluck('TripTitle');
         
       
         
        $galleries = Package::where('operator_email', $first)->get();
        
         log::info('inside tripdetail');
        
         if(empty($query)){
             if($last==3)
             {
                log::info('inside role id 2');
                $sub=DB::table('rvsps')->whereIn('TripHeading', $trip)->where('status', 1)
                ->select( DB::raw('count(DISTINCT emailid) as user_count'))->pluck('user_count');
       log::info($sub);
       $allsub=DB::table('rvsps')->whereIn('TripHeading', $trip)->where('status', 1)
                ->select( DB::raw('count(users) as user_count'))->groupby('TripHeading')->pluck('user_count');
                log::info($allsub);
  
                $tools=DB::table('rvsps')->whereIn('TripHeading', $trip)->where('status', 1)
                ->select('TripHeading','id', DB::raw('count(emailid) as user_count'))->groupby('TripHeading')

                
          
          ->paginate(10);
          log::info($tools);
        }
        if($last==1)
             {
                log::info('inside role id 1');
                $allsub=DB::table('rvsps')->where('status', 1)
                ->select( DB::raw('count(users) as user_count'))->groupby('TripHeading')->pluck('user_count');
                log::info($allsub);
               $sub=DB::table('rvsps')->where('status', 1)->select( DB::raw('count(DISTINCT emailid) as user_count'))->pluck('user_count');
                  $tools=DB::table('rvsps')->where('status', 1)
                                ->select('TripHeading','id', DB::raw('count(emailid) as user_count'))->groupby('TripHeading')
          
          ->paginate(10);
          log::info($tools);
        }
                  }
        else{
            if($last==3)
            {
                     $tools = Rvsp::where('rvsps.emailid', $first)->where('status', 1)->where('TripHeading', 'like', '%'.$query1.'%')->orwhere('users', 'like', '%'.$query1.'%')
                      ->paginate(5);
            log::info($tools);

           }
           if($last==1)
           {
                    $tools = Rvsp::where('TripHeading', 'like', '%'.$query1.'%')->where('status', 1)->where('TripHeading', 'like', '%'.$query1.'%')->groupby('TripHeading')
                     ->paginate(5);
           log::info($tools);

          }
        }

               $response = [
            'success' => true,
            'data' => $tools,
            'sub' => $sub,
            'allsub' => $allsub,
            'galleries' => $galleries,
            'message' => 'Tool retrieved successfully.',
            'count' => count($tools)
        ];
              return response()->json($response, 200);
    }

    public function subdetail(Request $request, $id)
    {
      
       // $id = Rvsp::where('TripTitle', $TripTitle)->pluck('id');
        //$data = Rvsp::find($id);
    log::info($id);
         $query = $request->get('query');
         $query1 = str_replace("","%",$query);
         if(empty($query)){
            $id = Rvsp::where('TripHeading', $id)->pluck('id');
            log::info($id);
                  $tools=Rvsp::find($id);
                  log::info($id);
           log::info($tools);
                            }
        else{
            $id = Rvsp::where('TripHeading', $id)->pluck('id');
            log::info($id);
                  $tools=Rvsp::find($id);
   
   
                            }
               $response = [
            'success' => true,
            'data' => $tools,
           
            'message' => 'Tool retrieved successfully.',
            'count' => count($tools)
        ];
              return response()->json($response, 200);
    }
    public function alltripdetail(Request $request, $email)
    {
        log::info($request);
        $allcat = TripCategory::get();
         $query = $request->get('query');
         $query1 = str_replace("","%",$query);
         $sub= Package::sum('subscriber');
         $galleries = Package::all(); 
         log::info('inside all trip'); 
        
         log::info($galleries);
         if(empty($query)){
          //  $tools = Package::where('operator_email', $email)->paginate(5);
         
          $tools=DB::table('packages')
         ->leftJoin('addimages', 'packages.TripTitle', '=', 'addimages.trips')
         
          ->select(
              'packages.*',
              'addimages.image_name'
          )->groupBy('packages.TripTitle')
          ->paginate(5);
             
          log::info($tools);
      
                  }
        else{
         
            $tools = Package::leftJoin('countries', 'packages.slug', '=', 'countries.country_name')
             ->leftJoin('states', 'packages.slug1', '=', 'states.state_name')->
             leftJoin('cities', 'packages.slug2', '=', 'cities.city_name')->leftJoin('addimages', 'packages.TripTitle', '=', 'addimages.trips')->
             where('slug', 'like', '%'.$query1.'%')->orwhere('slug1', 'like', '%'.$query1.'%')->
             orwhere('slug2', 'like', '%'.$query1.'%')->orwhere('TripTitle', 'like', '%'.$query1.'%')->
            orwhere('countries.country_id',  $query1)->orwhere('states.state_id', $query1)->
            orwhere('cities.city_id', $query1)->paginate(5);
            log::info('inside else');
            log::info($tools);
                   }

               $response = [
            'success' => true,
            'data' => $tools,
            'sub' => $sub,
            'galleries' => $galleries,
            'allcat' => $allcat,
            'message' => 'Tool retrieved successfully.',
            'count' => count($tools)
        ];
              return response()->json($response, 200);
    }

    public function alluserdetail(Request $request, $email)
    {   log::info('inside  aa raha all user');
      
         $galleries = Rvsp::paginate(5); 
         log::info('inside all user');
         
        
         log::info($galleries);
               $response = [
            'success' => true,
            'data' => $galleries,
            
            'message' => 'Tool retrieved successfully.',
            'count' => count($galleries)
        ];
              return response()->json($response, 200);
    }

    public function addcategories(Request $request)
    {
        $this->validate($request,[
 
            'category_name'=>'required',
            'description'=>'required',
            'category_logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp',        
                ]);
       if($request->hasfile('category_logo'))
       {
           $file= $request->file('category_logo'); 
           $name = $file->getClientOriginalName();
           $file->move(public_path().'/category/', $name);  
       }
   $dmuescribe=$request->description;
   Log::info('describe me hu');
   log::info($dmuescribe);
   log::info($name);
    $addrequest = new TripCategory();
          $addrequest->category =  $request->category_name;
        $addrequest->Description =  $request->description;
        $addrequest->Image =$name;
        $addrequest->admin_id =$request->admin_auth_id;
        $addrequest->admin_name =$request->admin_auth_name;
        $addrequest->admin_email =$request->admin_auth_email;  
         $addrequest->save();
        return response()->json(['success' => 'category added Successsfully ']);

    }

    public function storeiternary(Request $request)
    {
        log::info($request);
        log::info('inside store iternary');
        $this->validate($request,[
 
            'iternary_title'=>'required',
            'iternary_days'=>'required',
            'iternarylocation'=>'required',  
            'iternarydescription'=>'required',      
                ]);
                
                $titles=Package::where('TripTitle',$request->iternary_title)->where('operator_email',$request->operator_auth_email)->first();
                $tour_id=$titles->tour_id;
                log::info($request->operator_auth_email);
                $tripid=Package::where('id',$tour_id)->first();
               
                log::info($tripid);
                $day=$request->iternary_days;
                Log::info ($day);
                $merge =  'day' .$day;
                Log::info ($merge);
               $addrequest = new Iternaries();
             
          $addrequest->trips =  $request->iternary_title;
        $addrequest->Days =  $merge;
        $addrequest->location =  $request->iternarylocation;
        $addrequest->explanation =  $request->iternarydescription;
        $addrequest->operator_id = $request->operator_auth_id;
        $addrequest->operator_name = $request->operator_auth_name;
        $addrequest->operator_email = $request->operator_auth_email;
        $addrequest->tour_id =  $tour_id;
        $addrequest->user_id = 5;
         $addrequest->save();
        return response()->json(['success' => 'category added Successsfully ']);

    }



   
public function storepermission(Request $request)
{
    log::info('inside storepermission ');
    $this->validate($request,[
        'Perm'=>'required',
       'Permission' => 'required',
           ]);
           $id=$request->Perm;
            $trips = Package::find($id);
            $trip=$trips->Permission;
          
            $tour_id=$trips->tour_id;
            $myid=addimage::where('trip_id', $tour_id)->first();
            $images_id=$myid->trip_id;
           
             if($trip=='Reject')
            {
                log::info('inside approve');
                   DB::table('packages')
                   ->where('id', $id)
                   ->update(array('Permission' => 'Approve'));

                
	     DB::table('addimages')
         ->where('trip_id', $images_id)
         ->update(array('Permision' => 'Approve'));
                                    
return response()->json(['success' => 'category added Successsfully ']); 
                }
                else{
                    log::info('inside reject');
                    DB::table('packages')
                    ->where('id', $id)
                    ->update(array('Permission' => 'Reject','publish' => '-1'));

                      DB::table('addimages')
                    ->where('trip_id', $images_id)
                    ->update(array('Permision' => 'Reject','publis' => '-1'));
                    return response()->json(['success' => 'category added Successsfully ']); 
                }
  
}

public function storeupermission(Request $request)
{
    log::info('inside storeupermission ');
    $this->validate($request,[
        'Perm'=>'required',
       'Permission' => 'required',
           ]);
           $id=$request->Perm;
            $trips = Rvsp::find($id);
            $trip=$trips->Permission;
            log::info($trip);
             if($trip=='Reject')
            {
                log::info('inside approve');
                   DB::table('rvsps')
                   ->where('id', $id)
                   ->update(array('Permission' => 'Approve'));
                                    
return response()->json(['success' => 'category added Successsfully ']); 
                }
                else{
                    log::info('inside reject');
                    DB::table('rvsps')
                    ->where('id', $id)
                    ->update(array('Permission' => 'Reject'));
                    return response()->json(['success' => 'category added Successsfully ']); 
                }
}

public function storesubscribelist(Request $request)
{
    $this->validate($request,[
        'Sub' => 'required',
        'name' => 'required',
        'email' => 'required',
        'phoneno' => 'required',
        'address' => 'required',
        
           ]);
           $id=$request->Sub;
           
          
                   DB::table('packages')
                   ->where('id', $id)
                   ->update(array('Permission' => 'yes'));
              

                    DB::table('packages')
                    ->where('id', $id)
                    ->update(array('Permission' => 'no'));
               
                 
return response()->json(['success' => 'category added Successsfully ']);   
}

    public function store(Request $request)
    {
        Log::info('ye h store function event ka ');
        Log::info ($request);
        $input = $request->all();
       
        $validator = Validator::make($input, [
            'category_name' => 'required|string|max:255',
            'description'=> 'required', 
            'admin_auth_id' =>'required',
            'admin_auth_name' =>'required',
            'admin_auth_email' =>'required',
            'category_logo' => 'required|file|mimes:jpeg,png,pdf|max:2048'
            ]);
                 if ($validator->fails()) {
            $response = [
                'success' => false,
                'data'    => 'Validation Error.',
                'message' => $validator->errors()
            ];
            Log::info('validation ok');
            return response()->json($response, 422);
        } 
        else {
            if($request->hasfile('category_logo'))
            { 
                $file= $request->file('category_logo'); 
                $name = $file->getClientOriginalName();
                $file->move(public_path().'/category/', $name); 
                 
            } 
                $training = TripCategory::create([
                        'category' => $input['category_name'],
                        'Description' => $input['description'],
                        'admin_id' => $input['admin_auth_id'],
                       
                        'admin_name' => $input['admin_auth_name'],
                        'admin_email' => $input['admin_auth_email'],
                        'Image' => $name,
                        
                     ]);
                $response = [
                    'success' => true,
                    //'data' => $data,
                    'data' => $training,
                    'message' => 'Training Fetching successfully.'
                ];
                Log::info('validation ok');
                Log::info($training);
                return response()->json($response, 200);
        }
    }

    public function detailinter($id)
    {
        Log::info($id);
        Log::info("deatil function of Event");
        $event = TripCountry::find($id);
        $response = [   
            'success' => true,
            'message' => 'Categoty retrieved successfully for edit.',
            'data' => $event
        ];
        Log::info("response to chala gya ");
        return response()->json($response, 200);
    }
    
    public function alldetailuser($id)
    {
        Log::info('inside all details');
        Log::info($id);
        $subs=Rvsp::where('id', $id)->pluck('TripHeading');
        Log::info($subs);
        $event=Rvsp::where('TripHeading', $subs)->where('status', 1)->get();
        Log::info("deatil function of Event");
      
        $response = [   
            'success' => true,
            'message' => 'Categoty retrieved successfully for edit.',
            'data' => $event,
            
        ];
        Log::info("response to chala gya ");
        Log::info($event);
        return response()->json($response, 200);
    }

    
    public function detailuser($id)
    {
        Log::info($id);
        Log::info("deatil function of Event");
        $event = Rvsp::find($id);
        $triptitle=$event->TripHeading;
        $trip = Package::leftJoin('addimages', 'packages.TripTitle', '=', 'addimages.trips')
         
        ->select(
            'packages.*',
            'addimages.image_name'
        )->groupBy('packages.TripTitle')->where('TripTitle', $triptitle)->first();
        Log::info($trip);
        $response = [   
            'success' => true,
            'message' => 'Categoty retrieved successfully for edit.',
            'data' => $event,
            'trip' => $trip
        ];
        Log::info("response to chala gya ");
        Log::info($event);
        return response()->json($response, 200);
    }

    
    public function destroyinter($id)
    {
        Log::info($id);
        Log::info("delete function of Event");
        $event = TripCountry::findOrFail($id);
        $event->delete();
        $response = [
            'success' => true,
            'data' => $event,
            'message' => 'Internal Deleted successfully.'
        ];
        return response()->json($response, 200);
    }  

    public function  editinter($id)
    {
        Log::info($id);
        Log::info("edit function of Event");
        $event = TripCountry::find($id);
        $response = [   
            'success' => true,
            'message' => 'Categoty retrieved successfully for edit.',
            'data' => $event
        ];
        Log::info("response to chala gya ");
        return response()->json($response, 200);
    }

    public function international($email)
    {
        $TripCountry=TripCountry::leftJoin('countries', 'trip_countries.country', '=', 'countries.country_id')->where('admin_email', $email)->orderBy('id', 'desc')->get();
         $response = [
       'success' => true,
       'data' => $TripCountry,
       'message' => 'Course retrieved successfully.',
       'count' => count($TripCountry)
       ];
       return response()->json($response, 200);    
    }

   
    public function storeinternational(Request $request)
    {
        Log::info('ye h store function international ka ');
        Log::info ($request);
        $input = $request->all();
        $validator = Validator::make($input, [
            'countryId' => 'required|string|max:255',
            'interdescription'=> 'required', 
            'admin_auth_id' =>'required',
            'admin_auth_name' =>'required',
            'admin_auth_email' =>'required',
            'international_logo' => 'required|file|mimes:jpeg,png,pdf|max:2048'
            ]);
            
         if ($validator->fails()) {
            $response = [
                'success' => false,
                'data'    => 'Validation Error.',
                'message' => $validator->errors()
            ];
            Log::info('validation ok');
            return response()->json($response, 422);
        } 
        else {
            if($request->hasfile('international_logo'))
            { 
                $file= $request->file('international_logo'); 
                $name = $file->getClientOriginalName();
                $file->move(public_path().'/category/', $name);  
            } 

                        $lower = strtolower($request->countryId);
                        $myslug = str_replace(" ", "-", $lower);
                      
                        $Country=Country::where("country_id" , $myslug)->first();
                        $slug=$Country->country_name;
                        Log::info($slug. 'ye slug me hai');
                        $TripCountry = new TripCountry;
                        $TripCountry->slug = $slug;
                        $TripCountry->country = $request->countryId;
                        $TripCountry->desc = $request->interdescription;
                        $TripCountry->admin_id = $request->admin_auth_id;
                        $TripCountry->admin_name = $request->admin_auth_name;
                        $TripCountry->admin_email = $request->admin_auth_email;
                        $TripCountry->Image = $name;
                        $TripCountry->save();
                        Log::info($TripCountry. 'ye TripCountry me hai');
                $response = [
                    'success' => true,
                    'data' => $TripCountry,
                    'message' => 'Internation Fetching successfully.'
                ];
                return response()->json($response, 200);
        }
    }


    public function storetrip(Request $request)
    {
        Log::info('ye h store function storetrip ka ');
        Log::info ($request);
        $input = $request->all();
        Log::info ('package ke pahle ');
        $trip = Package::latest()->first();
        Log::info('ye h first');
        Log::info ($trip);
        $tours=$trip->tour_id;
        Log::info ('package ke bad ');
        Log::info ($tours);
        $tour_id= $tours + 1;
        Log::info ($tour_id);
       
     
   
        $validator = Validator::make($input, [
            'category_slug' => 'required|string|max:255',
            'my_trip' => 'required|string|max:255',
            'tourcountryId' => 'required|string|max:255',
            'tourstate' => 'required|string|max:255',
            'tourcity' => 'required|string|max:255',
            'NoOfDays' => 'required|string|max:255',
            'NoOfNight' => 'required|string|max:255',
            'tour_date' => 'required|string|max:255',
            'tour_time' => 'required',
            'Destination' =>'required',
            'trip_highlight' =>'required',
            'tripdescription' =>'required', 
            'operator_auth_id' =>'required',
            'operator_auth_name' =>'required',
            'operator_auth_email' =>'required',
              ]); 
            
         if ($validator->fails()) { 
            $response = [ 
                'success' => false, 
                'data'    => 'Validation Error.', 
                'message' => $validator->errors() 
            ]; 
            Log::info('validation ok'); 
            return response()->json($response, 422);
        } 
        else {
            $lower = strtolower($request->tourcountryId);
            $myslug = str_replace(" ", "-", $lower);
            $Country=Country::where("country_id" , $myslug)->first();
            $slug1=$Country->country_name;
            $lowerstate = strtolower($request->tourstate);
            $myslugsta = str_replace(" ", "-", $lowerstate);
           $State=State::where("state_id" , $myslugsta)->first();
            $slug=$State->state_name;
            Log::info($slug. 'ye slug1 me hai');
           $lowercity = strtolower($request->tourcity);
            $myslugcity = str_replace(" ", "-", $lowercity);
            $City=City::where("city_id" , $myslugcity)->first();
            $slug2=$City->city_name;
            $getCountryName = $slug1;
            $Countriess =str_slug($getCountryName);
            $Statename =  $slug; 
            $States =str_slug($Statename);
            $country_state=collect([$Countriess, $States])->implode('/');
            $getCityName = $slug2;
            $cities = str_slug($getCityName); 
            $country_state_city=collect([$Countriess, $States,$cities])->implode('/');
            $getsCategory = $request->my_trip;
            $Category = str_slug($getsCategory); 
            $c_s_c_cat=collect([$Countriess, $States,$cities,$Category])->implode('/');
                          
            $day=$request->NoOfDays;
            Log::info('No of days aa raha hai');
            Log::info($day);
            $nights=$request->NoOfNight;
            Log::info('No of Night aa raha hai');
            Log::info($nights);
            $merge = $day . 'days/ ' . $nights.'night';
            Log::info('No of merge aa raha hai');
            Log::info($merge);
                        $Trip = new Package;
                        $Trip->slug = $slug1;
                        $Trip->slug1 = $slug;
                        $Trip->slug2 = $slug2;
                        $Trip->publish = 0;
                        $Trip->country = $request->tourcountryId;
                        $Trip->state = $request->tourstate;
                        $Trip->city = $request->tourcity;
                        $Trip->operator_id = $request->operator_auth_id;
                        $Trip->operator_name = $request->operator_auth_name;
                        $Trip->operator_email = $request->operator_auth_email;
                        $Trip->Category = $request->category_slug;
                        $Trip->TripTitle = $request->my_trip;
                        $Trip->NoOfDays = $request->NoOfDays;
                        $Trip->night = $request->NoOfNight;
                        $Trip->daynight = $merge;
                        $Trip->tour_id = $tour_id;
                      
                        $Trip->datetime = $request->tour_date;
                        $Trip->time = $request->tour_time;
                        $Trip->Destination = $request->Destination;
                        $Trip->Description = $request->tripdescription;
                        $Trip->user_id = 5;
                        $Trip->Keyword = $request->trip_highlight;
                        $Trip->country_state = $country_state;
                        $Trip->country_state_city = $country_state_city;
                        $Trip->c_s_c_cat = $c_s_c_cat;
                        $Trip->save();
                        Log::info($Trip. 'ye TripCountry me hai');
                $response = [
                    'success' => true,
                    'data' => $Trip,
                    'message' => 'Internation Fetching successfully.'
                ];
                return response()->json($response, 200);
        }
    }

    protected function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        
        // trim
        $text = trim($text, '-');

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }
    protected function complete_step($is_publishable = [], $type = 'detail', $event_id = null)
    {

        log::info('yanha complete_step result hai publishable');
        log::info($is_publishable);

        if(!empty($is_publishable))
            $is_publishable             = json_decode($is_publishable, true);

        $is_publishable[$type]      = 1;
       // save is_publishable
        $params     = ['is_publishable' => json_encode($is_publishable)];
        log::info($params);
        $status     = $this->trip->save_event($params, $event_id);
        return true;
    }
    public function addmytripdetail(Request $request)
    {
        $admin_mail_get=Userevent::where('role_id','1')->first();
      
        $admin_mail=$admin_mail_get->email;
        Log::info('ye h store function international ka');
        Log::info ($request);
        $operator_auth_id=$request->user_id;
        $operator_mail_get=Userevent::where('id',$operator_auth_id)->first();
        $operator_auth_email=$operator_mail_get->email;
        $operator_name=$operator_mail_get->name;;
        Log::info ($operator_auth_email);
        Log::info ($operator_name);
        $input = $request->all();
        $mycat=TripCategory::where('id',$request->category_id)->first();
        $catname=$mycat->category;
       
        $OperatorMailsends = array(  
            'email' => $operator_auth_email
         
    );
  
        $validator = Validator::make($input, [
            'title' => 'required|string|max:255',
            'category_id' => 'required|string|max:255',
            'excerpt' => 'required|string|max:255',
            'description' => 'required',
            'faq' => 'required',
              ]); 
            
         if ($validator->fails()) { 
            $response = [ 
                'success' => false, 
                'data'    => 'Validation Error.', 
                'message' => $validator->errors() 
            ]; 
            Log::info('validation ok'); 
            return response()->json
            (['status' => false  ]);
        }

        else {

            $params = [
                "title"         => $request->title,
                "excerpt"       => $request->excerpt,
                "slug"          => $this->slugify($request->title),
                "description"   => $request->description,
                "user_id"      =>     $request->user_id,
                "operator_email"   => $operator_auth_email,
                "flight"   =>  $request->flight_event,
                "hotels"   =>  $request->hotel_event,
                "transfer"   => $request->transfer_event,
                "activity"   => $request->activity_event,
                "operator_name"   => $operator_name,
                "category"   =>    $catname,
                "faq"           => $request->faq,
                "category_id"   => $request->category_id,
                "featured"      => 0,
            ];
            $event = $this->trip->save_event($params, $request->event_id);
          
            if(empty($request->event_id))
        {
            log::info('yanha complete_step aayge result hai');
            log::info($request->event_id);
            log::info($event->slug);
            log::info($event);
          
            $this->complete_step($event->is_publishable, 'detail', $event->id);
            return response()->json
            (['status' => true, 'id' => $event->id, 'organiser_id' => $event->user_id ,
             'slug' => $event->slug ]);
        }   
        return response()->json
        (['status' => true, 'id' => $event->id, 'organiser_id' => $event->user_id ,
         'slug' => $event->slug ]);
        }
    }


    public function mycountry()
    {
        $countries = $this->country->get_countries();
        if(empty($countries))
        {
            return response()->json(['status' => false]);    
        }
        return response()->json(['status' => true, 'countries' => $countries ]);
    }

    public function addmytriptiming(Request $request)
    {
             Log::info('ye h store function international ka');
        Log::info ($request);
        $input = $request->all();
        $data           = [];
        $schedules      = [];

        $repetitive     = (int) $request->repetitive;
        $validator = Validator::make($input, [
            'start_date' => 'required|string|max:255',
            'end_date' => 'required|string|max:255',
            'start_time' => 'required|string|max:255',
            'end_time' => 'required',
              ]); 
            
         if ($validator->fails()) { 
            $response = [ 
                'success' => false, 
                'data'    => 'Validation Error.', 
                'message' => $validator->errors() 
            ]; 
            Log::info('validation ok'); 
            return response()->json($response, 422);
        } 

        else {
            Log::info('inside else part'); 
          $trips=  Trip::where('id',$request->event_id)
                   
                    ->first();
                    $user_id= $trips->user_id;
                    Log::info($user_id); 
            $check_event    = $this->trip->get_user_event($request->event_id, $user_id);
            log::info('yeh hai na $check_event');
            log::info($check_event);
            if($repetitive)
            {
                $repetitive_event   = $this->prepare_repetitive_event($request);
                $iternary_day       = $this->prepare_days_event($request);
                //=======================Update schedule case=============================================
                
                // update schedule true then return response to vue 
                if(!empty($repetitive_event['update']))
                {
                    if($repetitive_event['update_schedule'] == true) 
                        return response()->json(['status' => true]);
    
                    
                    if($repetitive_event['update_schedule'] == false) 
                    {
                        $msg = __('eventmie-pro::em.schedules_not_updated');
                        return error($msg, Response::HTTP_BAD_REQUEST );
                    }
                }        
                //=====================================End==============================================    
    
                    
                if(!$repetitive_event['status'])
                    return error($repetitive_event['error'], Response::HTTP_BAD_REQUEST );
                
                $data               = $repetitive_event['repetitive_event']['data'];
              
                $schedules          = $repetitive_event['repetitive_event']['schedules'];
                $data1               = $iternary_day['data'];
            }
            else
            {
                $single_event       = $this->prepare_single_event($request);
      $iternary_day       = $this->prepare_days_event($request);
                if(!$single_event['status'])
                    return error($single_event['error'], Response::HTTP_BAD_REQUEST );
    
                // === Delete schedules if have any schedules in case of single repetitive event ===            
                    
                    // if changing event from repetitive to normal, then delete all schedules
                    if($check_event->repetitive)
                    {
                        $params  = [
                            'event_id'          => $request->event_id,
                            'user_id'           => $user_id,
                        ];
                        
                        // delete old schedule because changeid_date have true
                        $delete_schedule  = $this->schedule->delete_schedule($params); 
                        
                        if(empty($delete_schedule))
                        {
                            $msg = __('eventmie-pro::em.schedules_not_updated');
                            return error($msg, Response::HTTP_BAD_REQUEST );
                        }
                           
                    }    
                // ==== End ====    
    
                $data               = $single_event['data'];
                $data1               = $iternary_day['data'];
            }
            if(!empty($schedules))
            {
                // create repetitive event  schedule 
                $schedule      =  $this->schedule->create_schedule($schedules, $request->event_id);
                log::info('indide create_schedule');
                log::info($schedule);
                if(empty($schedule))
                {
                    $msg = __('eventmie-pro::em.schedules_not_updated');
                    return error($msg, Response::HTTP_BAD_REQUEST );
                }
            }
            $event_timing  = $this->trip->save_event($data, $request->event_id);
            $event_days  = $this->iternary_day->save_event($data1, $request->event_id);
            $event    = $this->trip->get_user_event($request->event_id, $user_id);
            // set step complete
            log::info($event->is_publishable);
            $this->complete_step($event->is_publishable, 'timing', $request->event_id);
                log::info('yanha event timing hai');
                log::info($event);
            return response()->json(['status' => true]);
     
        }
    }

    public function addmytrippayment(Request $request)
    {

        Log::info('ye h store function addmytrippayment requested ka');
        Log::info ($request);
               $input = $request->all();
        
        $validator = Validator::make($input, [
            'event_id' => 'required',
            'payment_terms' => 'required',
            'payment_mode' => 'required',
            'pay_cancel_policy' => 'required',
              ]); 
            
         if ($validator->fails()) { 
            $response = [ 
                'success' => false, 
                'data'    => 'Validation Error.', 
                'message' => $validator->errors() 
            ]; 
            Log::info('validation ok'); 
            return response()->json($response, 422);
        } 

        else {
         
            $params = [
                "payment_terms"         => $request->payment_terms,
                "payment_mode"       => $request->payment_mode,
                "pay_cancel_policy"          => $request->pay_cancel_policy,
                
            ];
            $event = $this->trip->save_event($params, $request->event_id);

           
         
            log::info('yanha complete_step aayge result hai');
            log::info($request->event_id);
           
        // get update event
        $trips=  Trip::where('id',$request->event_id)
                   
            ->first();
            $user_id= $trips->user_id;
        $event= $this->trip->get_user_events($request->event_id, $user_id);
        log::info('yanha  $event');
       log::info($event);
        $this->complete_step($event->is_publishable, 'payment', $request->event_id);
        return response()->json(['status' => true, 'event' => $event]);
            
        }
    }
    public function addmytriplocation(Request $request)
    {

        Log::info('ye h store function addmytriplocation requested ka');
        Log::info ($request);
               $input = $request->all();
        
        $validator = Validator::make($input, [
            'venue' => 'required|string|max:255',
            'latitude' => 'required|string|max:255',
            'longitude' => 'required|string|max:255',
            'address' => 'required',
         
            'zipcode' => 'required|string|max:255',
            'state_id' => 'required|string|max:255',
            'city_id' => 'required|string|max:255',
              ]); 
            
         if ($validator->fails()) { 
            $response = [ 
                'success' => false, 
                'data'    => 'Validation Error.', 
                'message' => $validator->errors() 
            ]; 
            Log::info('validation ok'); 
            return response()->json($response, 422);
        } 

        else {
            $trips=  Trip::where('id',$request->event_id)
                   
            ->first();
            $user_id= $trips->user_id;
            $trips_country=  Country::where('country_id',$request->country_id)
                 
            ->first();
            $trips_contname=$trips_country->country_name;
            $trips_state=  State::where('state_id',$request->state_id)
                   
            ->first();
            $trips_statename=$trips_state->state_name;

            $lowerstate = strtolower($trips_statename);
            $slug2 = str_replace(" ", "-", $lowerstate);

            $getCountryName = $trips_contname;
            $Countriess =str_slug($getCountryName);
            $Statename =  $slug2; 
            $States =str_slug($Statename);
            $country_state=collect([$Countriess, $States])->implode('/');
            $trips_city=  City::where('city_id',$request->city_id)
                
            ->first();
            $trips_cityname=$trips_city->city_name;
            $lowercity = strtolower($trips_cityname);
            $slug1 = str_replace(" ", "-", $lowercity);

            $getCityName = $slug1;
            $cities = str_slug($getCityName); 
            $country_state_city=collect([$Countriess, $States,$cities])->implode('/');
            $params = [
                "venue"         => $request->venue,
                "latitude"       => $request->latitude,
                "longitude"          => $request->longitude,
                "address"   => $request->address,
                'online_location' => $request->online_location,
                "zipcode"  =>  $request->zipcode,
                "country_id"   =>  $request->country_id,
                "state_id"       =>  $request->state_id,
                "city_id"           => $request->city_id,
                "zipcode"  =>  $request->zipcode,
                "country_id"   =>  $request->country_id,
                "city"       =>  $trips_cityname,
                "state"           => $trips_statename,
                "slug1"  =>  $slug1,
                "slug2"   =>  $slug2,
                "slug3"       =>  $trips_contname,
                "country_state"    => $country_state,
                "country_state_city" => $country_state_city,
            ];
            $event = $this->trip->save_event($params, $request->event_id);

           
         
            log::info('yanha complete_step aayge result hai');
            log::info($request->event_id);
           
        // get update event
        $trips=  Trip::where('id',$request->event_id)
                   
            ->first();
            $user_id= $trips->user_id;
        $event= $this->trip->get_user_events($request->event_id, $user_id);
        log::info('yanha  $event');
       log::info($event);
        $this->complete_step($event->is_publishable, 'location', $request->event_id);
        return response()->json(['status' => true, 'event' => $event]);
            
        }
    }
    protected function upload_base64_image($params = [])
    {
        if(!empty($params['image'])) 
        { 
            $image           = base64_encode(file_get_contents($params['image']));
            $image           = str_replace('data:image/png;base64,', '', $image);
            $image           = str_replace(' ', '+', $image);

            if(class_exists('\Str'))
                $filename        = time().\Str::random(10).'.'.'jpg';
            else
                $filename        = time().str_random(10).'.'.'jpg';
            
            $path            = '/storage/'.$params['path'].'/'.Carbon::now()->format('FY').'/';
            $image_resize    = Image::make(base64_decode($image))->resize($params['width'], $params['height']);

            // first check if directory exists or not
            
            if (! File::exists(public_path().$path)) {
                File::makeDirectory(public_path().$path, 0777, true);
            }
    
            $image_resize->save(public_path($path . $filename));
            
            return  $filename;
        }
    } 

    public function addmytripmedia(Request $request)
    {

      
        Log::info('ye h store function addmytriplocation addmytripmedia ka');
        Log::info ($request);
        Log::info ($request->images);
               $input = $request->all();
        
        $validator = Validator::make($input, [
            'event_id'      => 'required|numeric|min:1|regex:^[1-9][0-9]*$^',
            'thumbnail'     => 'required',
            'poster'        => 'required',
              ]); 
            
         if ($validator->fails()) { 
            $response = [ 
                'success' => false, 
                'data'    => 'Validation Error.', 
                'message' => $validator->errors() 
            ]; 
            Log::info('validation ok'); 
            return response()->json($response, 422);
        } 

        else {
            $trips=  Trip::where('id',$request->event_id)
                   
            ->first();
            $user_id= $trips->user_id;
            $path = 'trips/'.Carbon::now()->format('FY').'/';

        // for thumbnail
        if(!empty($_REQUEST['thumbnail'])) 
        { 
            $params  = [
                'image'  => $_REQUEST['thumbnail'],
                'path'   => 'events',
                'width'  => 512,
                'height' => 512,  
            ];
            $thumbnail   = $this->upload_base64_image($params);
        }

        if(!empty($_REQUEST['poster'])) 
        {
            $params  = [
                'image'  => $_REQUEST['poster'],
                'path'   => 'events',
                'width'  => 1920,
                'height' => 1080,  
            ];

            $poster   = $this->upload_base64_image($params);
        }
    
        // for image
        if($request->hasfile('images')) 
        { 
            // if have  image and database have images no images this event then apply this rule 
            $request->validate([
                'images'        => 'required',
                'images.*'      => 'mimes:jpeg,png,jpg,gif,svg',
            ]); 
            log::info('me yanha rakesh hu');
            $files = $request->file('images');
    log::info('me rakesh hu');
    log::info($files);
            foreach($files as  $key => $file)
            {
                $extension       = $file->getClientOriginalExtension(); // getting image extension
                $image[$key]     = time().rand(1,988).'.'.$extension;
                $file->storeAs('public/'.$path, $image[$key]);
                log::info('me image j hu');
                $myimage = str_ireplace("tmp", "trip", $file);

                log::info($file); 
                log::info($myimage);
                $images[$key]    = $path.$image[$key];
            }

            
           
        }
        $params = [
            "thumbnail"     => !empty($thumbnail) ? $path.$thumbnail : null ,
            "poster"        => !empty($poster) ? $path.$poster : null,
            "video_link"    => $request->video_link,
           
        ];  

        // if images uploaded
        if(!empty($images))
        {
            if(!empty($result->images))
            {
                $exiting_images = json_decode($result->images, true);

                $images = array_merge($images, $exiting_images);
            }

            $params["images"] = json_encode(array_values($images));

        }    
        
        $status   = $this->trip->save_event($params, $request->event_id);
           
         
            log::info('yanha complete_step aayge result hai');
            log::info($request->event_id);
           
        // get update event
        $trips=  Trip::where('id',$request->event_id)
                   
            ->first();
            $user_id= $trips->user_id;
        $event= $this->trip->get_user_events($request->event_id, $user_id);
        log::info('yanha  $event');
       log::info($event);
        $this->complete_step($event->is_publishable, 'media', $request->event_id);
        return response()->json(['status' => true, 'event' => $event]);
            
        }
    }

    public function addmytripiternary(Request $request)
    {
  
        Log::info('ye h store function addmytriplocation addmytripiternary ka');
        Log::info ($request);
               $input = $request->all();
        
        $validator = Validator::make($input, [
           
           
              ]); 
            
         if ($validator->fails()) { 
            $response = [ 
                'success' => false, 
                'data'    => 'Validation Error.', 
                'message' => $validator->errors() 
            ]; 
            Log::info('validation oktr'); 
            return response()->json($response, 422);
        } 

        else {
            $trips=  Trip::where('id',$request->trip_id)
                 
            ->first();
            log::info('mera trip hai naa');
           
            $user_id= $trips->user_id;
            log::info($user_id);
            log::info($request);
            $status=$trips->status;
            $title=$trips->title;
             $op_mail=$trips->operator_email;
            
                $params = [
                "days"         => $request->days,      
               
                "location"       => $request->location,
                "description"   => $request->description,
                "title"   =>  $title,
                 "flight"   =>  $request->flight,
                 "hotels"   =>  $request->hotels,
                 "transfer"   => $request->transfer,
                  "activity"   => $request->activity,
                "operator_email"   => $request->op_mail,
                "operator_id"  =>  $request->user_id,
                "trip_id"   =>  $request->trip_id,
                "id"   =>  $request->id,
                "status"       =>  $status,
                
            ];
            $event = $this->iternary->save_event($params, $request->trip_id);

            log::info('yanha complete_step aayge result hai');
            log::info($request->trip_id);
           
        // get update event
        $trips=  Trip::where('id',$request->trip_id)
                   
            ->first();
            $user_id= $trips->user_id;
        $event= $this->trip->get_user_events($request->trip_id, $user_id);
        log::info('yanha  $event');
       log::info($event);
        $this->complete_step($event->is_publishable, 'iternary', $request->trip_id);
        return response()->json(['status' => true, 'event' => $event]);
            
        }
    }
   
    protected function prepare_repetitive_event(Request $request)
    {
              // if logged in user is admin
        log::info('inside prepare_repetitive_event aaya');
                 // get old event
                 $start=$request->start_date;
        $end=$request->end_date;
        $datetime1 = strtotime($start); // convert to timestamps
        $datetime2 = strtotime($end); // convert to timestamps
        $dayss = (int)(($datetime2 - $datetime1)/86400); 
        $days = $dayss +1; 
       $trips=  Trip::where('id',$request->event_id)
                   
->first();
$user_id= $trips->user_id;
Log::info($user_id); 
        $request->validate([
            'end_date'          => 'required|date|after_or_equal:start_date',
            'start_time'        => 'required|date_format:H:i:s',
            'end_time'          => 'required|date_format:H:i:s',
            'repetitive'        => 'required|numeric|between:1,1',
            'repetitive_type'   => 'required|numeric|between:1,3',
        ]);
        
        $event = Trip::where(['id' => $request->event_id])->first();
        

        // start validation will not apply if database start_date and request start is same
        if($event->start_date != $request->start_date)
        {
            $request->validate([
                'start_date'        => 'required|date|after_or_equal:today',
            ]);
           
        }

        // 1. count_months => start_date & end_date
        // 2. months_dates => start_date & end_date
            // $months_dates[0]['from_date'] 
            // $months_dates[0]['to_date'] 

            // $months_dates[1]['from_date'] 
            // $months_dates[2]['to_date'] 

        // 3. count_months == count(repetitive_dates) == count(from_time) == count(to_time)
            // return error('Error message!', Response::HTTP_BAD_REQUEST );
        
        $start_month               = \Carbon\Carbon::createFromFormat('Y-m-d', $request->start_date)->modify('first day of this month');
        $end_month                 = \Carbon\Carbon::createFromFormat('Y-m-d', $request->end_date)->modify('last day of this month');
        $count_months              = $end_month->diffInMonths($start_month)+1;

        // validate from_time and to_time
        $request->validate([
            'from_time'             => ['required', 'array', "min:$count_months", "max:$count_months"],
            'from_time.*'           => ['required', 'date_format:H:i:s'],

            'to_time'               => ['required', 'array', "min:$count_months", "max:$count_months"],
            'to_time.*'             => ['required', 'date_format:H:i:s'],
        ]);

        $repetitive_type = (int) $request->repetitive_type;

        // count_months == count_repetitive_dates
        // repetitive dates
        if( ($repetitive_type === 1 || $repetitive_type === 3) && !empty($request->repetitive_dates))
        {
            $request->validate([
                'repetitive_dates'       => ['required', 'array', "min:$count_months", "max:$count_months"],
                'repetitive_dates.*'     => ['required', 'regex:/^[0-9 ,]+$/i'], 
            ]);
        }
            
        // count_months == count_repetitive_days
        // repetitive days
        if( ($repetitive_type === 2) && !empty($request->repetitive_days))
        {
            $request->validate([
                'repetitive_days'       => ['required', 'array', "min:$count_months", "max:$count_months"],
                'repetitive_days.*'     => ['required', 'regex:/^[0-9 ,]+$/i'], 
            ]);
        }


        $start_date     = \Carbon\Carbon::createFromFormat('Y-m-d', $request->start_date)->modify('first day of this month');
        $end_date       = \Carbon\Carbon::createFromFormat('Y-m-d', $request->end_date)->modify('last day of this month');
        
        // get months name between two dates
        $month_names_temp = CarbonPeriod::create($start_date, '1 month', $end_date);
        

        $month_names     = [];
        $from_date       = [];
        $to_date         = [];
        
        foreach ($month_names_temp as $key => $value) 
        {
            $month_names[$key] = $value->format("Y-m-d") ;
        }
        
        if(!empty($month_names))
        {
            // get first day of months
            foreach ($month_names as $key => $value) {
                $first_day_month   = new \DateTime($value);
                $first_day_month->modify('first day of this month');
                $from_date[$key]   = $first_day_month->format('Y-m-d');
            }

            // get last day of months
            foreach ($month_names as $key => $value) {
                $last_day_month  = new \DateTime($value);
                $last_day_month->modify('last day of this month');
                $to_date[$key]   = $last_day_month->format('Y-m-d');
            }
        }
    
        $schedules           = [];
log::info('schedule tak aaya');
        // current date time
        $current_date_time   = Carbon::now()->toDateTimeString();
        
        // repetitive dates
        if( ($repetitive_type === 1 || $repetitive_type === 3) && !empty($request->repetitive_dates))
        {
            foreach ($request->repetitive_dates as $key => $value)
            {
                $schedules[$key]['event_id']             = $request->event_id;
                $schedules[$key]['user_id']              = $user_id;
                $schedules[$key]['repetitive_type']      = $repetitive_type;
                $schedules[$key]['repetitive_dates']     = $value;
                $schedules[$key]['from_time']            = $request->from_time[$key];
                $schedules[$key]['to_time']              = $request->to_time[$key];

                // generate from start_date and end_date
                $schedules[$key]['from_date']            = $from_date[$key];
                $schedules[$key]['to_date']              = $to_date[$key];
                $schedules[$key]['created_at']           = $current_date_time;
                $schedules[$key]['updated_at']           = $current_date_time;

                // timestamp
                // make repetitive_days = null
                $schedules[$key]['repetitive_days']      = null;
            }
        }    

        // repetitive days
        if( ($repetitive_type === 2) && !empty($request->repetitive_days))
        {
            foreach ($request->repetitive_days as $key => $value)
            {
                $schedules[$key]['event_id']             = $request->event_id;
                $schedules[$key]['user_id']              = $user_id;
                $schedules[$key]['repetitive_type']      = $repetitive_type;
                $schedules[$key]['repetitive_days']      = $value;
                $schedules[$key]['from_time']            = $request->from_time[$key];
                $schedules[$key]['to_time']              = $request->to_time[$key];

                // generate from start_date and end_date
                $schedules[$key]['from_date']            = $from_date[$key];
                $schedules[$key]['to_date']              = $to_date[$key];
                $schedules[$key]['created_at']           = $current_date_time;
                $schedules[$key]['updated_at']           = $current_date_time;

                // make repetitive_dates = null
                $schedules[$key]['repetitive_dates']      = null;
            }
        }    
        
        $data = [
            "start_date"        => $request->start_date,
            "start_time"        => $request->start_time,
            "end_date"          => $request->end_date,
            "days"          => $days,
            "end_time"          => $request->end_time,
            "repetitive"        => $request->repetitive,
            "merge_schedule"    => $repetitive_type > 1 ? $request->merge_schedule : 0,
            "user_id"           => $user_id,
        ];


        $repetitive_event = [
            'data'      => $data,
            'schedules' => $schedules,
        ];

        // check for update schedule
            // 1. Check if there's any schedule for this event (call model function)
            // if yes
            // -----------------------
                // 1. check if start_date and end_date changed (if)
                    // if dates changed then delete all schedules
                    // then continue below in this method
                
                // 2. if start_date and end_date not changed (else)
                // call this->update_schedule

            // -----------------------
            // if no 
            // then continue

            // if(already_schedules_available)
            // {
                // 
                    // if(check if dates are changed)
                    // {
                            // delete all schedules
                    // }
                    // else
                    // {
                            // call this->update_schedule
                            // return success;
                    // }
            // }
            
            $check_event    = $this->trip->get_user_event($request->event_id, $user_id);
            // check schedule ids empty on not
            if(!empty($request->schedule_ids))
            {
                log::info('inside schedule aaya');
                // validate from_time and to_time
                $request->validate([
                    'schedule_ids'          => ['required', 'array'],
                    'schedule_ids.*'        => ['required'],
                ]);

                $params  = [
                    'schedule_ids'      => $request->schedule_ids,
                    'event_id'          => $request->event_id,
                    'repetitive_type'   => $request->repetitive_type,
                    'user_id'           => $user_id,
                ];
                
                
                // check schedule ids existed on not in schedule table
                $check_schedule     = $this->schedule->check_schedule($params);

                if(!empty($check_schedule))
                {
                    $old_start_date     = Carbon::createFromFormat('Y-m-d', $check_event['start_date']);
                    $old_end_date       = Carbon::createFromFormat('Y-m-d', $check_event['end_date']);

                    $new_start_date     = Carbon::createFromFormat('Y-m-d', $request->start_date);
                    $new_end_date       = Carbon::createFromFormat('Y-m-d', $request->end_date);

                    // if event date have not changed to previous dates then change_date variable false 
                    $changed_date       = true;
                    if($old_start_date->equalTo($new_start_date) && $old_end_date->equalTo($new_end_date))
                    {
                        
                        $changed_date    = false;

                        if($request->merge_schedule != $check_event->merge_schedule)
                            $changed_date    = true;
                    }

                    // if changed_date true means dates have changed then old schedule delete then new create schedule
                    if($changed_date)
                    {
                        $params  = [
                            'event_id'          => $request->event_id,
                            'user_id'           => $user_id,
                        ];
                        
                        // delete old schedule because changeid_date have true
                        $delete_schedule  = $this->schedule->delete_schedule($params);

                        if(empty($delete_schedule))
                        {
                            $msg = __('eventmie-pro::em.schedules_not_updated');
                            return error($msg, Response::HTTP_BAD_REQUEST );
                        }

                        $params = [
                            'repetitive' => 0
                        ];
                        // update repetitive column by 0 after schedule deleted
                        $this->event->save_event($params, $request->event_id);

                    }
                    else
                    {
                        $update_schedule  = $this->update_schedule($request, $schedules);

                        if(!$update_schedule['status'])
                                return ['update' => 1, 'update_schedule' => false];;
                        
                        return ['update' => 1, 'update_schedule' => true];
                    }
                }
                else
                {
                    $msg = __('eventmie-pro::em.schedules_not_updated');
                    return error($msg, Response::HTTP_BAD_REQUEST );
                }
            }

            $params  = [
                'event_id'          => $request->event_id,
                'user_id'           => $user_id,
            ];
            
            // delete old schedule 
            $delete_schedule  = $this->schedule->delete_schedule($params);

        return [
            'status'            => true,
            'repetitive_event'  => $repetitive_event,
        ]; 
        
    }

    protected function update_schedule(Request $request, $schedules = [])
    {
        // if logged in user is admin
        $this->is_admin($request);
        $trips=  Trip::where('id',$request->event_id)
                   
        ->first();
        $user_id= $trips->user_id;
        // if changed_date false means dates have not changed then update schedule
        
        if(empty($schedules))
        {
            $msg = __('eventmie-pro::em.schedules_not_updated');
            return error($msg, Response::HTTP_BAD_REQUEST );
        }
        
        $params       = [
            'schedules'     => $schedules,
            'schedule_ids'  => $request->schedule_ids,
            'event_id'      => $request->event_id,
            'user_id'       => $user_id,
        ];

        // in case of repetitive event
        if(!empty($params))
        {
            $update_schedule      =  $this->schedule->update_schedule($params);

            if(empty($update_schedule))
            {
                $msg = __('eventmie-pro::em.schedules_not_updated');
                return ['status' => false, 'error' => $msg];
            }
            return ['status' => true];
        }

        $msg = __('eventmie-pro::em.invalid').' '.__('eventmie-pro::em.data');
        return ['status' => false, 'error' => $msg];
    }

    protected function prepare_single_event(Request $request)
    {
       log::info('prepare_single_event');
        $event = Trip::where(['id' => $request->event_id])->first();
        log::info($event);
        log::info($event->start_date);
        log::info( $request->start_date);
        $start=$request->start_date;
        $end=$request->end_date;
        $datetime1 = strtotime($start); // convert to timestamps
        $datetime2 = strtotime($end); // convert to timestamps
        $dayss = (int)(($datetime2 - $datetime1)/86400);
           $days = $dayss +1; // will give the difference in days , 86400 is the timestamp difference of a day
        log::info($days);
        // start validation will not apply if database start_date and request start is same
        if($event->start_date != $request->start_date)
        { 
            log::info('inside if before validate');
            $request->validate([
                'start_date'        => 'required|date|after_or_equal:today',
            ]);
            log::info('validate');
        }

        // if logged in user is admin
      
        log::info('after  validate');
        // 1. validate data
        $request->validate([
            'end_date'          => 'required|date|after_or_equal:start_date',
            'start_time'        => 'required|date_format:H:i:s',
            'end_time'          => 'required|date_format:H:i:s',
        ]);

        $data = [
            "start_date"        => $request->start_date,
            "start_time"        => $request->start_time,
            "end_date"          => $request->end_date,
            "end_time"          => $request->end_time,
            "days"          => $days,
            "Night"          => $dayss,
            "repetitive"        => $request->repetitive,
        ];

       
        return [
            'status'    => true, 
            'data'      => $data,
           
        ];
    }

    protected function prepare_days_event(Request $request)
    {
       log::info('prepare_days_event');
        $event = Trip::where(['id' => $request->event_id])->first();
        log::info($event);
        log::info($event->start_date);
        log::info( $request->start_date);
        $start=$request->start_date;
        $end=$request->end_date;
        $datetime1 = strtotime($start); // convert to timestamps
        $datetime2 = strtotime($end); // convert to timestamps
        $dayss = (int)(($datetime2 - $datetime1)/86400);
           $days = $dayss +1; // will give the difference in days , 86400 is the timestamp difference of a day
        log::info($days);
        // start validation will not apply if database start_date and request start is same
        if($event->start_date != $request->start_date)
        { 
            log::info('inside if before validate');
            $request->validate([
                'start_date'        => 'required|date|after_or_equal:today',
            ]);
            log::info('validate');
        }

        // if logged in user is admin
      
        log::info('after  validate');
        // 1. validate data
        $request->validate([
            'end_date'          => 'required|date|after_or_equal:start_date',
            'start_time'        => 'required|date_format:H:i:s',
            'end_time'          => 'required|date_format:H:i:s',
        ]);
      
        $data = [
            "trip_id"        => $request->event_id,
           
           
        ];

       
        return [
            'status'    => true, 
            'data'      => $data,
           
        ];
    }
    public function destroytrip($id)
    {
        Log::info($id);
        Log::info("delete function of Event");
        $event = Package::findOrFail($id);
        $eventtrip=$event->TripTitle;
        log::info($eventtrip);
        $event_iter=addimage::where("trips" , $eventtrip)->delete();
        $event_image=Iternaries::where("trips" , $eventtrip)->delete();
        $title=Package::where('id', $id)->pluck('TripTitle');
        $sub=Rvsp::whereIn('TripHeading', $title)->update(array('status' => '0'));
       
        $event->delete();
       
        $response = [
            'success' => true,
            'data' => $event,
            'message' => 'Event Deleted successfully.'
        ];
        return response()->json($response, 200);
    }
    public function delmytripiternary(Request $request)
    {
        Log::info($request);
        Log::info("delete function of Event");
        $event = Iternary::where("id" , $request->id)->delete();
        return response()->json(['status' => true]);
    }
    public function delmytriptickets(Request $request)
    {
        
        Log::info($request);
        Log::info("delete function of Event");
        $event = Ticket::where("ticket_id" , $request->ticket_id)->delete();
        return response()->json(['status' => true]);
    }
    public function publishtrip($id)
    {
        Log::info($id);
        Log::info("publish function of Event");
        
       
       

        $mydate=Package::where('id', $id)->first();
        $tour_id=$mydate->tour_id;
        $date=$mydate->datetime;
        $time=$mydate->time;
        $merge = $date . ' ' . $time;
        $current = Carbon::now();
        $date_interval =$current->diffInHours($merge);  
        log::info($date_interval);
        if ( $date_interval>48 || $date_interval==48)
           
        {
            $event= Package::where('id', $id)->update(array('publish' => '-1'));
            $image= addimage::where('trip_id', $tour_id)->update(array('publis' => '-1'));
       
            $response = [
                'success' => false,
                'data' => $event,
                'message' => 'Event Deleted successfully.'
            ];
            return response()->json($response, 200);
        }
        else{
            $event= Package::where('id', $id)->update(array('publish' => '1'));
            $image= addimage::where('trip_id', $tour_id)->update(array('publis' => '1'));
       
            $response = [
                'success' => true,
                'data' => $event,
                'message' => 'Event Deleted successfully.'
            ];
            return response()->json($response, 200);

        }
        
        
    }

    
    public function unpublishtrip($id)
    {
        $mydate=Package::where('id', $id)->first();
       $tour_id=$mydate->tour_id;
        Log::info($id);
        Log::info("unpublish function of Event");
        $event= Package::where('id', $id)->update(array('publish' => '-1'));
       $title=Package::where('id', $id)->pluck('TripTitle');
       $sub=Rvsp::whereIn('TripHeading', $title)->update(array('status' => '0'));
       $image= addimage::where('trip_id', $tour_id)->update(array('publis' => '-1'));
        $response = [
            'success' => true,
            'data' => $event,
            'message' => 'Event Deleted successfully.'
        ];
        return response()->json($response, 200);
    }
    public function getmytripdetail($slug)
    {
        Log::info($slug);
        Log::info("deatil function of Event");
        $event = Trip::where('slug',$slug)->first();
        $response = [   
            'success' => true,
            'message' => 'Categoty retrieved successfully for edit.',
            'data' => $event
        ];
        Log::info("response to chala gya ");
        return response()->json($response, 200);
    }
    public function tripupdate(Request $request)
    {
        $id = $request->operator_hidden_id;
        $my= $request->tourcountryId;
        Log::info("here  mydgid city");
        Log::info($my);
        $SCountry_ids=Package::where("id" , $id)->first(); 
        $scont_id=$SCountry_ids->slug; 
        Log::info($scont_id); 
        $cont= $request->tourcountryId;
         $stat =$request->tourstate;
        $citi= $request->tourcity;
        $trip_title=$request->my_trip;
        $tourism_titles=$SCountry_ids->tour_id;
        Log::info("here  mydgid tourism_titles");
        Log::info($tourism_titles); 
      
        $getCountryName = Country::where('country_id', $request->tourcountryId)->first();
        $getCountryNames = Country::where('country_name', $request->tourcountryId)->first();
         
        
        if (!empty($getCountryNames))
        {
            Log::info('inside empty');
        
              $Countryname = $getCountryNames->country_name; 
              $Countriess =str_slug($Countryname);
          
        
              $getStateNames = State::where('state_name', $request->tourstate)->first();
                   
        
          
              $Statename = $getStateNames->state_name;  
              $States =str_slug($Statename);
              $country_state=collect([$Countriess, $States])->implode('/');
        
        
              $getCityNames = City::where('city_name', $request->tourcity)->first();
                                 
        
              $cityname = $getCityNames->city_name; 
              $cities = str_slug($cityname); 
              $country_state_city=collect([$Countriess, $States,$cities])->implode('/');
             
        
        
        
        $getsCategory = $request->my_trip;
        Log::info($getsCategory);
        $Category = str_slug($getsCategory); 
        $c_s_c_cat=collect([$Countriess, $States,$cities,$Category])->implode('/');
        Log::info($c_s_c_cat);

        $image_trip_update= DB::table('packages')->where('id', $id)
                    ->update(['c_s_c_cat' =>$c_s_c_cat ]);
        }
        if (!empty($getCountryName))
{
    Log::info('inside empty');

      $Countryname = $getCountryName->country_name; 
      $Countriess =str_slug($Countryname);
  

      $getStateName = State::where('state_id', $request->tourstate)->first();
           

  
      $Statename = $getStateName->state_name;  
      $States =str_slug($Statename);
      $country_state=collect([$Countriess, $States])->implode('/');


      $getCityName = City::where('city_id', $request->tourcity)->first();
                         

      $cityname = $getCityName->city_name; 
      $cities = str_slug($cityname); 
      $country_state_city=collect([$Countriess, $States,$cities])->implode('/');
     



$getsCategory = $request->my_trip;
Log::info($getsCategory);
$Category = str_slug($getsCategory); 
$c_s_c_cat=collect([$Countriess, $States,$cities,$Category])->implode('/');
Log::info($c_s_c_cat);
$image_trip_update= DB::table('packages')->where('id', $id)
->update(['c_s_c_cat' =>$c_s_c_cat ]);
}
        if($my==$scont_id)
        {
          
          $training = Package::find($id);
          $id = $request->operator_hidden_id;
                    $training = Package::find($id);
                     log::info($trip_title);
                    $tour_title=$training->TripTitle;
                    log::info($trip_title);
                  
                    
                    $iternary_trip=DB::table('iternaries')->where('trips', $tour_title)
                    ->update(['trips' =>$trip_title ]); 
                    log::info($iternary_trip);
                    $day=$request->NoOfDays;
            $nights=$request->NoOfNight;
            $merge = $day . 'days/ ' . $nights.'night';

            $image_trip= DB::table('addimages')->where('trips', $tour_title)
            ->update(['trips' =>$trip_title,'Description'=>$request->tripdescription,'Overview'=>$request->trip_highlight,'Highlight'=>$request->Destination,
            'time'=>$request->tour_time,'datetime'=>$request->tour_date,
            'daynight'=> $merge,]);

            log::info('yaha tak aa raha hai image_trip'); 
            log::info($image_trip);  
            log::info($tour_title); 
            log::info($request->tripdescription);


         $training->Category = $request->category_slug;
               $training->TripTitle = $request->my_trip;
               $training->NoOfDays = $request->NoOfDays;  
               $training->night = $request->NoOfNight; 
                 $training->daynight = $merge ;
                $training->datetime = $request->tour_date;
               $training->time = $request->tour_time;
               $training->Destination = $request->Destination;
               $training->Keyword = $request->trip_highlight;
               $training->Description = $request->tripdescription; 
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
                      $id = $request->operator_hidden_id;
                    $training = Package::find($id);
                   
                  

                    
                    $iternary_trip=DB::table('iternaries')->where('trips', $trip_title)
                    ->update(['trips' =>$trip_title ]); 
                    log::info($iternary_trip);
               $contr=Country::Where('country_id',$cont)->first();
               Log::info('yeh country hai naaa');
               Log::info($contr);
               $cont_name=$contr->country_name;
               Log::info($cont_name);
               $statess=State::Where('state_id',$stat)->first();
               $stat_name=$statess->state_name;
               $citiess=City::Where('city_id',$citi)->first();
               $day=$request->NoOfDays;
            $nights=$request->NoOfNight;
            $merge = $day . 'days/ ' . $nights.'night';

               $city_name=$citiess->city_name;

               $image_trip= DB::table('addimages')->where('trip_id', $tourism_titles)
                    ->update(['trips' =>$trip_title,
                    'country'=>$cont_name,'state'=>$stat_name,'city'=>$city_name,'Description'=>$request->tripdescription,'Overview'=>$request->trip_highlight,'Highlight'=>$request->Destination,
                    'time'=>$request->tour_time,'datetime'=>$request->tour_date,
                    'daynight'=> $merge,]);

                    log::info('yaha tak aa raha hai image_trip else'); 
                    log::info($image_trip); 
                    log::info($trip_title); 
                    log::info($request->tripdescription); 

               $training->Category = $request->category_slug;
               $training->TripTitle = $request->my_trip;
               $training->slug = $cont_name;
               $training->slug1 =  $stat_name;
               $training->slug2 = $city_name;
               $training->country = $request->tourcountryId;
               $training->state =  $request->tourstate;
               $training->city = $request->tourcity;
               $training->NoOfDays = $request->NoOfDays; 
               $training->night = $request->NoOfNight;  
                              $training->daynight = $merge ;
                  $training->datetime = $request->tour_date;
               $training->time = $request->tour_time;
               $training->Destination = $request->Destination;
               $training->Keyword = $request->trip_highlight;
               $training->Description = $request->tripdescription;  
               $training->country_state = $country_state;
              
               $training->country_state_city = $country_state_city;
               $training->c_s_c_cat = $c_s_c_cat;
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
   public function addmytripticket(Request $request)
    {
  
        Log::info('ye h store function addmytriplocation addmytripticket ka');
        Log::info ($request);
               $input = $request->all();

        
        $validator = Validator::make($input, [
            'price' => 'required',
            'title' => 'required',
            'currency' => 'required',
            'trip_id' => 'required',
           
                    
              ]); 
            
         if ($validator->fails()) { 
            $response = [ 
                'success' => false, 
                'data'    => 'Validation Error.', 
                'message' => $validator->errors() 
            ]; 
            Log::info('validation ok'); 
            return response()->json($response, 422);
        } 

        else {
            $trip=Trip::where('id',$request->trip_id)->first();
            $publish=$trip->publish;
            $tripemail= $trip->email;
            $tripticket= Discount_title::where('id',$request->discountweek_id)->first(); 
          if($tripticket || $publish==0)
          {
            Log::info('$tripticket'); 
            $titles=$tripticket->discount_title;      
            $result = substr($titles, 0, 2);
            log::info($result);
            $users = Trip::where('id', $request->trip_id)->first();
            $tripdate=$users->start_date;
            $tripcreate= substr($users->updated_at, 0, 10);

           
            log::info($tripcreate);
            $mydate= Discount::where('id',$request->discount_id)->first(); 
          
          $currweek=$mydate->week;
        
         
          $mydays=$currweek*7;
          log::info($mydays);
            $usersdates =Carbon::parse($tripdate)->subDays($mydays);
            $usersdate = substr($usersdates, 0, 10);
             log::info($usersdate);
        
      
            if($result==10)
        {
            $cost= $request->price;
            $costper=$cost*90;
            $price = (int)(($costper)/100);
            $params = [
                "ticket_price"         => $price,
                "ticket_title"       => $request->title,
                "ticket_currency"          => $request->currency,
                'ticket_event_id' =>        $request->trip_id,
                'discount_id' => $request->discount_id,
                'discount_week' => $request->discountweek_id,
                "ticket_real_cost"         => $request->price,
                "discountdate"=>$tripcreate,
                "discountseconddate"=>$usersdate,
                "week"         => $mydate->discount,
                "ticket_discount"=> $tripticket->discount_title,
                "ticket_discount"=> $tripticket->discount_title,
                "operator_email"=> $tripemail,
                
                 
                           
                           
            ];
            $event = $this->ticket->save_event($params, $request->trip_id);
                    
           $discounts= Ticket::where('ticket_event_id',$request->trip_id)->pluck('discount_id');
            Week::where('trip_id', $request->trip_id)->whereNotIn('id', $discounts)->delete();
            $discounts_week= Ticket::where('ticket_event_id',$request->trip_id)->pluck('discount_week');
            Mydiscount::where('trip_id', $request->trip_id)->whereNotIn('id', $discounts_week)->delete();
        }

        elseif($result==20)
        { 
            $cost= $request->price;
            $costper=$cost*80;
            $price = (int)(($costper)/100);
            $params = [
                "ticket_price"         => $price,
                "ticket_title"       => $request->title,
                "ticket_currency"          => $request->currency,
                'ticket_event_id' =>        $request->trip_id,
                'discount_id' => $request->discount_id,
                'discount_week' => $request->discountweek_id,
                "ticket_real_cost"         => $request->price,
                "discountdate"=>$tripcreate,
                "discountseconddate"=>$usersdate,
                "week"         => $mydate->discount,
                "ticket_discount"=>"20%",
                "operator_email"=> $tripemail,
            ];
            $event = $this->ticket->save_event($params, $request->trip_id);

            if($request->selectdiscountweek_id)
            {
            $ticketsecond= Discount_title::where('id',$request->selectdiscountweek_id)->first(); 
            $cost2= $request->price;
            $costper2=$cost2*90;
            $price2 = (int)(($costper2)/100);

            $myseconddate= Discount::where('id',$request->seconddiscount_id)->first(); 
          
            $currsecondweek=$myseconddate->week;
          
            
            $myseconddays=$currsecondweek*7;
            log::info($myseconddays);
              $usersseconddates =Carbon::parse($tripdate)->subDays($myseconddays);
              $usersseconddate = substr($usersseconddates, 0, 10);
            
               log::info($usersseconddate);
            $params1 = [
                "ticket_price"         => $price2,
                "ticket_title"       => $request->title,
                "ticket_currency"          => $request->currency,
                'ticket_event_id' =>        $request->trip_id,
                'discount_id' => $request->seconddiscount_id,
                'discount_week' => $request->selectdiscountweek_id,
                "ticket_real_cost"         => $request->price,
                "discountdate"=>$usersdate,
                "discountseconddate"=>$usersseconddate,
               
                "week"         => $myseconddate->discount,
                "ticket_discount"=> $ticketsecond->discount_title,
                "operator_email"=> $tripemail,           
            ];
            $event = $this->ticket->save_event1($params1);
            $discounts= Ticket::where('ticket_event_id',$request->trip_id)->pluck('discount_id');
            Week::where('trip_id', $request->trip_id)->whereNotIn('id', $discounts)->delete();
            $discounts_week= Ticket::where('ticket_event_id',$request->trip_id)->pluck('discount_week');
            Mydiscount::where('trip_id', $request->trip_id)->whereNotIn('id', $discounts_week)->delete();
        }
        }

        elseif($result==30)
        {
            $cost= $request->price;
            $costper=$cost*70;
            $price = (int)(($costper)/100);
            $params = [
                "ticket_price"         => $price,
                "ticket_title"       => $request->title,
                "ticket_currency"          => $request->currency,
                'ticket_event_id' =>        $request->trip_id,
                'discount_id' => $request->discount_id,
                'discount_week' => $request->discountweek_id,
                "ticket_real_cost"         => $request->price,
                "discountdate"=>$tripcreate,
                "discountseconddate"=>$usersdate,
                "week"         => $mydate->discount,
                "ticket_discount"=> "30%",
                "operator_email"=> $tripemail,
             
            ];
            $event = $this->ticket->save_event($params, $request->trip_id);
            if($request->selectdiscountweek_id)
            {
            $ticketsecond= Discount_title::where('id',$request->selectdiscountweek_id)->first(); 
            $titles2=$ticketsecond->discount_title;      
            $result2 = substr($titles2, 0, 2);
            if($result2==10)
            {
                $cost2= $request->price;
                $costper2=$cost2*90;
                $price2 = (int)(($costper2)/100);
                $myseconddate= Discount::where('id',$request->seconddiscount_id)->first(); 
                 $currsecondweek=$myseconddate->week;
             
                $myseconddays=$currsecondweek*7;
                log::info($myseconddays);
                $usersseconddates =Carbon::parse($tripdate)->subDays($myseconddays);
                $usersseconddate = substr($usersseconddates, 0, 10);
                $params1 = [
                    "ticket_price"         => $price2,
                    "ticket_title"       => $request->title,
                    "ticket_currency"          => $request->currency,
                    'ticket_event_id' =>        $request->trip_id,
                    'discount_id' => $request->discount_id,
                    'discount_week' => $request->discountweek_id,
                    "ticket_real_cost"         => $request->price,
                    "discountdate"=>$usersdate,
                "discountseconddate"=>$usersseconddate,
                    "week"         => $mydate->discount,
                    "ticket_discount"=> $ticketsecond->discount_title,
                    "operator_email"=> $tripemail,
    
                                
                ];
                $event = $this->ticket->save_event1($params1);

            }
            else{
                $cost2= $request->price;
                $costper2=$cost2*80;
                $price2 = (int)(($costper2)/100);
                $myseconddate= Discount::where('id',$request->seconddiscount_id)->first(); 
          
                $currsecondweek=$myseconddate->week;
                            
                $myseconddays=$currsecondweek*7;
                log::info($myseconddays);
                $usersseconddates =Carbon::parse($tripdate)->subDays($myseconddays);
                $usersseconddate = substr($usersseconddates, 0, 10);
                $params1 = [
                    "ticket_price"         => $price2,
                "ticket_title"       => $request->title,
                "ticket_currency"          => $request->currency,
                'ticket_event_id' =>        $request->trip_id,
                'discount_id' => $request->discount_id,
                'discount_week' => $request->discountweek_id,
                "ticket_real_cost"         => $request->price,
                "discountdate"=>$usersdate,
                "discountseconddate"=>$usersseconddate,
                "week"         => $mydate->discount,
                "ticket_discount"=> $ticketsecond->discount_title,
                "operator_email"=> $tripemail,
    
                                
                ];
                $event = $this->ticket->save_event1($params1);

            }
        }
        if($request->seconddiscountweek_id)
        {
            $ticketthird= Discount_title::where('id',$request->seconddiscountweek_id)->first(); 
            $cost3= $request->price;
            $costper3=$cost3*90;
            $price3 = (int)(($costper3)/100);
            $myseconddate= Discount::where('id',$request->seconddiscount_id)->first(); 
          
            $currsecondweek=$myseconddate->week;
          
         
            $myseconddays=$currsecondweek*7;
            log::info($myseconddays);
            $usersseconddates =Carbon::parse($tripdate)->subDays($myseconddays);
            $usersseconddate = substr($usersseconddates, 0, 10);

          $mythirddate= Discount::where('id',$request->thirddiscount_id)->first(); 
          $thirddiscount=$mythirddate->week;
         
          $mythirddays=$thirddiscount*7;
          log::info($mythirddays);
            $usersdatethirds =Carbon::parse($tripdate)->subDays($mythirddays);
         
            $usersdatethird = substr($usersdatethirds, 0, 10);
            $params2 = [
                "ticket_price"         => $price3,
                "ticket_title"       => $request->title,
                "ticket_currency"          => $request->currency,
                'ticket_event_id' =>        $request->trip_id,
                'discount_id' => $request->discount_id,
                'discount_week' => $request->discountweek_id,
                "ticket_real_cost"         => $request->price,
                "discountdate"=>$usersseconddate,
                "discountseconddate"=>$usersdatethird,
                "operator_email"=> $tripemail,
                "week"         => $mydate->discount,
                "ticket_discount"=> $ticketthird->discount_title,

                            
            ];
            $event = $this->ticket->save_event2($params2);
            $discounts= Ticket::where('ticket_event_id',$request->trip_id)->pluck('discount_id');
            Week::where('trip_id', $request->trip_id)->whereNotIn('id', $discounts)->delete();
            $discounts_week= Ticket::where('ticket_event_id',$request->trip_id)->pluck('discount_week');
            Mydiscount::where('trip_id', $request->trip_id)->whereNotIn('id', $discounts_week)->delete();
        }
    }
        elseif($result==40)
        {
            $cost= $request->price;
            $costper=$cost*60;
            $price = (int)(($costper)/100);
            $params = [
                "ticket_price"         => $price,
                "ticket_title"       => $request->title,
                "ticket_currency"          => $request->currency,
                'ticket_event_id' =>        $request->trip_id,
                'discount_id' => $request->discount_id,
                'discount_week' => $request->discountweek_id,
                "ticket_real_cost"         => $request->price,
                "discountdate"=>$tripcreate,
                "discountseconddate"=>$usersdate,
                "week"         => $mydate->discount,
                "ticket_discount"         => $tripticket->discount_title,
                "operator_email"=> $tripemail,
             
            ];
            $event = $this->ticket->save_event($params, $request->trip_id);


            if($request->selectdiscountweek_id)
            {
            $ticketsecond= Discount_title::where('id',$request->selectdiscountweek_id)->first(); 
            $titles2=$ticketsecond->discount_title;      
            $result2 = substr($titles2, 0, 2);
            if($result2==10)
            {
                $cost2= $request->price;
                $costper2=$cost2*90;
                $price2 = (int)(($costper2)/100);
                $myseconddate= Discount::where('id',$request->seconddiscount_id)->first(); 
          
                $currsecondweek=$myseconddate->week;
              
               
                $myseconddays=$currsecondweek*7;
                log::info($myseconddays);
                $usersseconddates =Carbon::parse($tripdate)->subDays($myseconddays);
                $usersseconddate = substr($usersseconddates, 0, 10);
                $params1 = [
                    "ticket_price"         => $price2,
                    "ticket_title"       => $request->title,
                    "ticket_currency"          => $request->currency,
                    'ticket_event_id' =>        $request->trip_id,
                    'discount_id' => $request->discount_id,
                    'discount_week' => $request->discountweek_id,
                    "ticket_real_cost"         => $request->price,
                    "discountdate"=>$usersdate,
                "discountseconddate"=>$usersseconddate,
                    "week"         => $mydate->discount,
                    "ticket_discount"         => $ticketsecond->discount_title,
                    "operator_email"=> $tripemail,
                  
                ];
                $event = $this->ticket->save_event1($params1);

            }

           else if($result2==20)
            {
                $cost2= $request->price;
                $costper2=$cost2*80;
                $price2 = (int)(($costper2)/100);
                $myseconddate= Discount::where('id',$request->seconddiscount_id)->first(); 
                $currsecondweek=$myseconddate->week;
                $myseconddays=$currsecondweek*7;
                log::info($myseconddays);
                $usersseconddates =Carbon::parse($tripdate)->subDays($myseconddays);
                $usersseconddate = substr($usersseconddates, 0, 10);
                $params1 = [
                    "ticket_price"         => $price2,
                "ticket_title"       => $request->title,
                "ticket_currency"          => $request->currency,
                'ticket_event_id' =>        $request->trip_id,
                'discount_id' => $request->discount_id,
                'discount_week' => $request->discountweek_id,
                "ticket_real_cost"         => $request->price,
                "discountdate"=>$usersdate,
                "discountseconddate"=>$usersseconddate,
                "week"         => $mydate->discount,
                "ticket_discount"         => $ticketsecond->discount_title,
                "operator_email"=> $tripemail,
                                   
                ];
                $event = $this->ticket->save_event1($params1);

            }

            else{
                $cost2= $request->price;
                $costper2=$cost2*70;
                $price2 = (int)(($costper2)/100);
                $myseconddate= Discount::where('id',$request->seconddiscount_id)->first(); 
          
                $currsecondweek=$myseconddate->week;
              
                $myseconddays=$currsecondweek*7;
                log::info($myseconddays);
                $usersseconddates =Carbon::parse($tripdate)->subDays($myseconddays);
                $usersseconddate = substr($usersseconddates, 0, 10);
                $params1 = [
                    "ticket_price"         => $price2,
                    "ticket_title"       => $request->title,
                    "ticket_currency"          => $request->currency,
                    'ticket_event_id' =>        $request->trip_id,
                    'discount_id' => $request->discount_id,
                    'discount_week' => $request->discountweek_id,
                    "ticket_real_cost"         => $request->price,
                    "discountdate"=>$usersdate,
                "discountseconddate"=>$usersseconddate,
                    "week"         => $mydate->discount,
                    "ticket_discount"=> $ticketsecond->discount_title,
                    "operator_email"=> $tripemail,
                                
                ];
                $event = $this->ticket->save_event1($params1);

            }
        }
        if($request->seconddiscountweek_id)
        {
            $ticketthird= Discount_title::where('id',$request->seconddiscountweek_id)->first(); 
            $titles3=$ticketthird->discount_title;      
            $result3 = substr($titles3, 0, 2);
            if($result3==10)
            {
                $cost3= $request->price;
                $costper3=$cost3*90;
                $price3 = (int)(($costper3)/100);
                $myseconddate= Discount::where('id',$request->seconddiscount_id)->first(); 
          
                $currsecondweek=$myseconddate->week;
              
                $myseconddays=$currsecondweek*7;
                log::info($myseconddays);
                $usersseconddates =Carbon::parse($tripdate)->subDays($myseconddays);
                $usersseconddate = substr($usersseconddates, 0, 10); 
           log::info($usersseconddate);    
          $mythirddate= Discount::where('id',$request->thirddiscount_id)->first(); 
          $thirddiscount=$mythirddate->week;
        
          $mythirddays=$thirddiscount*7;
          log::info($mythirddays);
          $usersdatethirds =Carbon::parse($tripdate)->subDays($mythirddays);
         
          $usersdatethird = substr($usersdatethirds, 0, 10);
                $params2 = [
                    "ticket_price"         => $price3,
                    "ticket_title"       => $request->title,
                    "ticket_currency"          => $request->currency,
                    'ticket_event_id' =>        $request->trip_id,
                    'discount_id' => $request->discount_id,
                    'discount_week' => $request->discountweek_id,
                    "ticket_real_cost"         => $request->price,
                    "discountdate"=>$usersseconddate,
                    "discountseconddate"=>$usersdatethird,
                    "week"         => $mydate->discount,
                    "ticket_discount"=> $ticketthird->discount_title,
                    "operator_email"=> $tripemail,
                                
                ];
                $event = $this->ticket->save_event2($params2);
            }
            else{
                $cost3= $request->price;
                $costper3=$cost3*80;
                $price3 = (int)(($costper3)/100);
                $myseconddate= Discount::where('id',$request->seconddiscount_id)->first(); 
          
                $currsecondweek=$myseconddate->week;
              
                $myseconddays=$currsecondweek*7;
                log::info($myseconddays);
                $usersseconddates =Carbon::parse($tripdate)->subDays($myseconddays);
                $usersseconddate = substr($usersseconddates, 0, 10);     
           log::info($usersseconddate);
          $mythirddate= Discount::where('id',$request->thirddiscount_id)->first(); 
          $thirddiscount=$mythirddate->week;
            $mythirddays=$thirddiscount*7;
          log::info($mythirddays);
          $usersdatethirds =Carbon::parse($tripdate)->subDays($mythirddays);
         
          $usersdatethird = substr($usersdatethirds, 0, 10);
                $params2 = [
                    "ticket_price"         => $price3,
                    "ticket_title"       => $request->title,
                    "ticket_currency"          => $request->currency,
                    'ticket_event_id' =>        $request->trip_id,
                    'discount_id' => $request->discount_id,
                    'discount_week' => $request->discountweek_id,
                    "ticket_real_cost"         => $request->price,
                    "discountdate"=>$usersseconddate,
                    "discountseconddate"=>$usersdatethird,
                    "week"         => $mydate->discount,
                    "ticket_discount"=> $ticketthird->discount_title,
                    "operator_email"=> $tripemail,
                                
                ];
                $event = $this->ticket->save_event2($params2);

            }

          
            $discounts= Ticket::where('event_id',$request->trip_id)->pluck('discount_id');
            Week::where('trip_id', $request->trip_id)->whereNotIn('id', $discounts)->delete();
            $discounts_week= Ticket::where('event_id',$request->trip_id)->pluck('discount_week');
            Mydiscount::where('trip_id', $request->trip_id)->whereNotIn('id', $discounts_week)->delete();
        }
    }
        elseif($result==50)
        { 
            $cost= $request->price;
            $costper=$cost*50;
            $price = (int)(($costper)/100);
            $params = [
                "ticket_price"         => $price,
                "ticket_title"       => $request->title,
                "ticket_currency"          => $request->currency,
                'ticket_event_id' =>        $request->trip_id,
                'discount_id' => $request->discount_id,
                'discount_week' => $request->discountweek_id,
                "ticket_real_cost"         => $request->price,
                "discountdate"=>$tripcreate,
                "discountseconddate"=>$usersdate,
                "week"         => $mydate->discount,
                "ticket_discount"=> $tripticket->discount_title,
                "operator_email"=> $tripemail,
             
            ];
            $event = $this->ticket->save_event($params, $request->trip_id);
            if($request->selectdiscountweek_id)
            {
             $ticketsecond= Discount_title::where('id',$request->selectdiscountweek_id)->first(); 
            $titles2=$ticketsecond->discount_title;      
            $result2 = substr($titles2, 0, 2);
            if($result2==10)
            {
                $cost2= $request->price;
                $costper2=$cost2*90;
                $price2 = (int)(($costper2)/100);
                
 $myseconddate= Discount::where('id',$request->seconddiscount_id)->first(); 
          
 $currsecondweek=$myseconddate->week;


 $myseconddays=$currsecondweek*7;
 log::info($myseconddays);
 $usersseconddates =Carbon::parse($tripdate)->subDays($myseconddays);
 $usersseconddate = substr($usersseconddates, 0, 10);  
log::info($usersseconddate);
                $params1 = [
                    "ticket_price"         => $price2,
                    "ticket_title"       => $request->title,
                    "ticket_currency"          => $request->currency,
                    'ticket_event_id' =>        $request->trip_id,
                    'discount_id' => $request->discount_id,
                    'discount_week' => $request->discountweek_id,
                    "ticket_real_cost"         => $request->price,
                    "discountdate"=>$usersdate,
                "discountseconddate"=>$usersseconddate,
                    "week"         => $mydate->discount,
                    "ticket_discount"         => $ticketsecond->discount_title,
                    "operator_email"=> $tripemail,
                                
                ];
                $event = $this->ticket->save_event1($params1);

            }

           else if($result2==20)
            {
                $cost2= $request->price;
                $costper2=$cost2*80;
                $price2 = (int)(($costper2)/100);
                
 $myseconddate= Discount::where('id',$request->seconddiscount_id)->first(); 
          
 $currsecondweek=$myseconddate->week;

 $myseconddays=$currsecondweek*7;
 log::info($myseconddays);
 $usersseconddates =Carbon::parse($tripdate)->subDays($myseconddays);
 $usersseconddate = substr($usersseconddates, 0, 10);   
log::info($usersseconddate);
                $params1 = [
                    "ticket_price"         => $price2,
                    "ticket_title"       => $request->title,
                    "ticket_currency"          => $request->currency,
                    'ticket_event_id' =>        $request->trip_id,
                    'discount_id' => $request->discount_id,
                    'discount_week' => $request->discountweek_id,
                    "ticket_real_cost"         => $request->price,
                    "discountdate"=>$usersdate,
                    "discountseconddate"=>$usersseconddate,
                    "week"         => $mydate->discount,
                    "ticket_discount"         => $ticketsecond->discount_title,
                    "operator_email"=> $tripemail,            
                ];
                $event = $this->ticket->save_event1($params1);

            }
            else if($result2==30)
            {
                $cost2= $request->price;
                $costper2=$cost2*70;
                $price2 = (int)(($costper2)/100);
                
 $myseconddate= Discount::where('id',$request->seconddiscount_id)->first(); 
          
 $currsecondweek=$myseconddate->week;

 $myseconddays=$currsecondweek*7;
 log::info($myseconddays);
 $usersseconddates =Carbon::parse($tripdate)->subDays($myseconddays);
 $usersseconddate = substr($usersseconddates, 0, 10);   
log::info($usersseconddate);
                $params1 = [
                    "ticket_price"         => $price2,
                    "ticket_title"       => $request->title,
                    "ticket_currency"          => $request->currency,
                    'ticket_event_id' =>        $request->trip_id,
                    'discount_id' => $request->discount_id,
                    'discount_week' => $request->discountweek_id,
                    "ticket_real_cost"         => $request->price,
                    "discountdate"=>$usersdate,
                    "discountseconddate"=>$usersseconddate,
                    "week"         => $mydate->discount,
                    "ticket_discount"         => $ticketsecond->discount_title,
                    "operator_email"=> $tripemail,              
                ];
                $event = $this->ticket->save_event1($params1);

            }

            else{
                $cost2= $request->price;
                $costper2=$cost2*60;
                $price2 = (int)(($costper2)/100);
                
 $myseconddate= Discount::where('id',$request->seconddiscount_id)->first(); 
          
 $currsecondweek=$myseconddate->week;

 $myseconddays=$currsecondweek*7;
 log::info($myseconddays);
 $usersseconddates =Carbon::parse($tripdate)->subDays($myseconddays);
 $usersseconddate = substr($usersseconddates, 0, 10);    
log::info($usersseconddate);
                $params1 = [
                    "ticket_price"         => $price2,
                    "ticket_title"       => $request->title,
                    "ticket_currency"          => $request->currency,
                    'ticket_event_id' =>        $request->trip_id,
                    'discount_id' => $request->discount_id,
                    'discount_week' => $request->discountweek_id,
                    "ticket_real_cost"         => $request->price,
                    "discountdate"=>$usersdate,
                "discountseconddate"=>$usersseconddate,
                    "week"         => $mydate->discount,
                    "ticket_discount"         => $ticketsecond->discount_title,
                    "operator_email"=> $tripemail,             
                ];
                $event = $this->ticket->save_event1($params1);

            }
        }

        
if($request->seconddiscountweek_id)
{
            $ticketthird= Discount_title::where('id',$request->seconddiscountweek_id)->first(); 
            $titles3=$ticketthird->discount_title;      
            $result3 = substr($titles3, 0, 2);
            if($result3==10)
            {
                $cost3= $request->price;
                $costper3=$cost3*90;
                $price3 = (int)(($costper3)/100);
                
 $myseconddate= Discount::where('id',$request->seconddiscount_id)->first(); 
          
 $currsecondweek=$myseconddate->week;


 $myseconddays=$currsecondweek*7;
 log::info($myseconddays);
 $usersseconddates =Carbon::parse($tripdate)->subDays($myseconddays);
 $usersseconddate = substr($usersseconddates, 0, 10); 
log::info($usersseconddate);
$mythirddate= Discount::where('id',$request->thirddiscount_id)->first(); 
$thirddiscount=$mythirddate->week;

$mythirddays=$thirddiscount*7;
log::info($mythirddays);
$usersdatethirds =Carbon::parse($tripdate)->subDays($mythirddays);
         
$usersdatethird = substr($usersdatethirds, 0, 10);

                $params2 = [
                    "ticket_price"         => $price3,
                    "ticket_title"       => $request->title,
                    "ticket_currency"          => $request->currency,
                    'ticket_event_id' =>        $request->trip_id,
                    'discount_id' => $request->discount_id,
                    'discount_week' => $request->discountweek_id,
                    "ticket_real_cost"         => $request->price,
                    "discountdate"=>$usersseconddate,
                    "discountseconddate"=>$usersdatethird,
                    "week"         => $mydate->discount,
                    "ticket_discount"         => $ticketthird->discount_title,
                    "operator_email"=> $tripemail,              
                ];
                $event = $this->ticket->save_event2($params2);
            }
            else if($result3==20)
            {
                $cost3= $request->price;
                $costper3=$cost3*80;
                $price3 = (int)(($costper3)/100);
                $myseconddate= Discount::where('id',$request->seconddiscount_id)->first(); 
          
 $currsecondweek=$myseconddate->week;


 $myseconddays=$currsecondweek*7;
 log::info($myseconddays);
 $usersseconddates =Carbon::parse($tripdate)->subDays($myseconddays);
 $usersseconddate = substr($usersseconddates, 0, 10);    
log::info($usersseconddate);
$mythirddate= Discount::where('id',$request->thirddiscount_id)->first(); 
$thirddiscount=$mythirddate->week;

$mythirddays=$thirddiscount*7;
log::info($mythirddays);
$usersdatethirds =Carbon::parse($tripdate)->subDays($mythirddays);
         
$usersdatethird = substr($usersdatethirds, 0, 10);

                $params2 = [
                    "ticket_price"         => $price3,
                    "ticket_title"       => $request->title,
                    "ticket_currency"          => $request->currency,
                    'ticket_event_id' =>        $request->trip_id,
                    'discount_id' => $request->discount_id,
                    'discount_week' => $request->discountweek_id,
                    "ticket_real_cost"         => $request->price,
                    "discountdate"=>$usersseconddate,
                    "discountseconddate"=>$usersdatethird,
                    "week"         => $mydate->discount,
                    "ticket_discount"         => $ticketthird->discount_title,
                    "operator_email"=> $tripemail,
                                
                ];
                $event = $this->ticket->save_event2($params2);
            }
            else{
                $cost3= $request->price;
                $costper3=$cost3*70;
                $price3 = (int)(($costper3)/100);
                $myseconddate= Discount::where('id',$request->seconddiscount_id)->first(); 
          
 $currsecondweek=$myseconddate->week;

 
 $myseconddays=$currsecondweek*7;
 log::info($myseconddays);
 $usersseconddates =Carbon::parse($tripdate)->subDays($myseconddays);
 $usersseconddate = substr($usersseconddates, 0, 10); 
log::info($usersseconddate);
$mythirddate= Discount::where('id',$request->thirddiscount_id)->first(); 
$thirddiscount=$mythirddate->week;

$mythirddays=$thirddiscount*7;
log::info($mythirddays);
$usersdatethirds =Carbon::parse($tripdate)->subDays($mythirddays);
         
$usersdatethird = substr($usersdatethirds, 0, 10);
                $params2 = [
                    "ticket_price"         => $price3,
                    "ticket_title"       => $request->title,
                    "ticket_currency"          => $request->currency,
                    'ticket_event_id' =>        $request->trip_id,
                    'discount_id' => $request->discount_id,
                    'discount_week' => $request->discountweek_id,
                    "ticket_real_cost"         => $request->price,
                    "discountdate"=>$usersseconddate,
                    "discountseconddate"=>$usersdatethird,
                    "week"         => $mydate->discount,
                    "ticket_discount"         => $ticketthird->discount_title,
                    "operator_email"=> $tripemail,              
                ];
                $event = $this->ticket->save_event2($params2);

            }
           
            $discounts= Ticket::where('ticket_event_id',$request->trip_id)->pluck('discount_id');
            Week::where('trip_id', $request->trip_id)->whereNotIn('id', $discounts)->delete();
            $discounts_week= Ticket::where('ticket_event_id',$request->trip_id)->pluck('discount_week');
            Mydiscount::where('trip_id', $request->trip_id)->whereNotIn('id', $discounts_week)->delete();
        }

    }
        elseif($result==60)
        {  $cost= $request->price;
            $costper=$cost*40;
            $price = (int)(($costper)/100);
            $params = [
                "ticket_price"         => $price,
                    "ticket_title"       => $request->title,
                    "ticket_currency"          => $request->currency,
                    'ticket_event_id' =>        $request->trip_id,
                    'discount_id' => $request->discount_id,
                    'discount_week' => $request->discountweek_id,
                    "ticket_real_cost"         => $request->price,
                    "discountdate"=>$tripcreate,
                    "discountseconddate"=>$usersdate,
                    "week"         => $mydate->discount,
                    "ticket_discount"         => $tripticket->discount_title,
                    "operator_email"=> $tripemail,
             
            ];
            $event = $this->ticket->save_event($params, $request->trip_id);
            if($request->selectdiscountweek_id)
            {
            $ticketsecond= Discount_title::where('id',$request->selectdiscountweek_id)->first(); 
            $titles2=$ticketsecond->discount_title;      
            $result2 = substr($titles2, 0, 2);
            if($result2==10)
            {
                $myseconddate= Discount::where('id',$request->seconddiscount_id)->first(); 
          
                $currsecondweek=$myseconddate->week;
              
              
                $myseconddays=$currsecondweek*7;
                log::info($myseconddays);
                $usersseconddates =Carbon::parse($tripdate)->subDays($myseconddays);
                $usersseconddate = substr($usersseconddates, 0, 10);     
           log::info($usersseconddate);
                $cost2= $request->price;
                $costper2=$cost2*90;
                $price2 = (int)(($costper2)/100);
                $params1 = [
                    "ticket_price"         => $price2,
                    "ticket_title"       => $request->title,
                    "ticket_currency"          => $request->currency,
                    'ticket_event_id' =>        $request->trip_id,
                    'discount_id' => $request->discount_id,
                    'discount_week' => $request->discountweek_id,
                    "ticket_real_cost"         => $request->price,
                    "discountdate"=>$usersdate,
                    "discountseconddate"=>$usersseconddate,
                    "week"         => $mydate->discount,
                    "ticket_discount"         => $ticketsecond->discount_title,
                    "operator_email"=> $tripemail,
         
                ];
                $event = $this->ticket->save_event1($params1);

            }

           else if($result2==20)
            {
                $cost2= $request->price;
                $costper2=$cost2*80;
                $price2 = (int)(($costper2)/100);
                $myseconddate= Discount::where('id',$request->seconddiscount_id)->first(); 
          
                $currsecondweek=$myseconddate->week;
              
               
                $myseconddays=$currsecondweek*7;
                log::info($myseconddays);
                $usersseconddates =Carbon::parse($tripdate)->subDays($myseconddays);
                $usersseconddate = substr($usersseconddates, 0, 10);     
           log::info($usersseconddate);
                $params1 = [
                    "ticket_price"         => $price2,
                    "ticket_title"       => $request->title,
                    "ticket_currency"          => $request->currency,
                    'ticket_event_id' =>        $request->trip_id,
                    'discount_id' => $request->discount_id,
                    'discount_week' => $request->discountweek_id,
                    "ticket_real_cost"         => $request->price,
                    "discountdate"=>$usersdate,
                    "discountseconddate"=>$usersseconddate,
                    "week"         => $mydate->discount,
                    "ticket_discount"         => $ticketsecond->discount_title,
                    "operator_email"=> $tripemail,
    
                                
                ];
                $event = $this->ticket->save_event1($params1);

            }
            else if($result2==30)
            {
                $cost2= $request->price;
                $costper2=$cost2*70;
                $price2 = (int)(($costper2)/100);
                $myseconddate= Discount::where('id',$request->seconddiscount_id)->first(); 
          
                $currsecondweek=$myseconddate->week;
                          
                $myseconddays=$currsecondweek*7;
                log::info($myseconddays);
                $usersseconddates =Carbon::parse($tripdate)->subDays($myseconddays);
                $usersseconddate = substr($usersseconddates, 0, 10);
           log::info($usersseconddate);
                $params1 = [
                    "ticket_price"         => $price2,
                    "ticket_title"       => $request->title,
                    "ticket_currency"          => $request->currency,
                    'ticket_event_id' =>        $request->trip_id,
                    'discount_id' => $request->discount_id,
                    'discount_week' => $request->discountweek_id,
                    "ticket_real_cost"         => $request->price,
                    "discountdate"=>$usersdate,
                    "discountseconddate"=>$usersseconddate,
                    "week"         => $mydate->discount,
                    "ticket_discount"         => $ticketsecond->discount_title,
    
                    "operator_email"=> $tripemail,
                                
                ];
                $event = $this->ticket->save_event1($params1);

            }
            else if($result2==40)
            {
                $cost2= $request->price;
                $costper2=$cost2*60;
                $price2 = (int)(($costper2)/100);
                $myseconddate= Discount::where('id',$request->seconddiscount_id)->first(); 
          
                $currsecondweek=$myseconddate->week;
              
                
                $myseconddays=$currsecondweek*7;
                log::info($myseconddays);
                $usersseconddates =Carbon::parse($tripdate)->subDays($myseconddays);
                $usersseconddate = substr($usersseconddates, 0, 10);     
           log::info($usersseconddate);
                $params1 = [
                    "ticket_price"         => $price2,
                    "ticket_title"       => $request->title,
                    "ticket_currency"          => $request->currency,
                    'ticket_event_id' =>        $request->trip_id,
                    'discount_id' => $request->discount_id,
                    'discount_week' => $request->discountweek_id,
                    "ticket_real_cost"         => $request->price,
                    "discountdate"=>$usersdate,
                    "discountseconddate"=>$usersseconddate,
                    "week"         => $mydate->discount,
                    "ticket_discount"         => $ticketsecond->discount_title,
                    "operator_email"=> $tripemail,
                                
                ];
                $event = $this->ticket->save_event1($params1);

            }

            else{
                $cost2= $request->price;
                $costper2=$cost2*50;
                $price2 = (int)(($costper2)/100);
                $myseconddate= Discount::where('id',$request->seconddiscount_id)->first(); 
          
                $currsecondweek=$myseconddate->week;
              
                
                $myseconddays=$currsecondweek*7;
                log::info('yeh multiply 7 karne ke baad aata hai');
                log::info($myseconddays);
                $usersseconddates =Carbon::parse($tripdate)->subDays($myseconddays);
                $usersseconddate = substr($usersseconddates, 0, 10);
           log::info($usersseconddate);
                $params1 = [
                    "ticket_price"         => $price2,
                    "ticket_title"       => $request->title,
                    "ticket_currency"          => $request->currency,
                    'ticket_event_id' =>        $request->trip_id,
                    'discount_id' => $request->discount_id,
                    'discount_week' => $request->discountweek_id,
                    "ticket_real_cost"         => $request->price,
                    "discountdate"=>$usersdate,
                    "discountseconddate"=>$usersseconddate,
                    "week"         => $mydate->discount,
                    "ticket_discount"         => $ticketsecond->discount_title,
                    "operator_email"=> $tripemail,
                ];
                $event = $this->ticket->save_event1($params1);

            }
        }
        if($request->seconddiscountweek_id)
        {

            $ticketthird= Discount_title::where('id',$request->seconddiscountweek_id)->first(); 
            $titles3=$ticketthird->discount_title;      
            $result3 = substr($titles3, 0, 2);
            if($result3==10)
            {
                $cost3= $request->price;
                $costper3=$cost3*90;
                $price3 = (int)(($costper3)/100);
                $myseconddate= Discount::where('id',$request->seconddiscount_id)->first(); 
          
                $currsecondweek=$myseconddate->week;
              
              
                $myseconddays=$currsecondweek*7;
                log::info($myseconddays);
                $usersseconddates =Carbon::parse($tripdate)->subDays($myseconddays);
                $usersseconddate = substr($usersseconddates, 0, 10);     
           log::info($usersseconddate);
           
          $mythirddate= Discount::where('id',$request->thirddiscount_id)->first(); 
          $thirddiscount=$mythirddate->week;
         
          $mythirddays=$thirddiscount*7;
          log::info($mythirddays);
          $usersdatethirds =Carbon::parse($tripdate)->subDays($mythirddays);
         
          $usersdatethird = substr($usersdatethirds, 0, 10);

                $params2 = [
                    "ticket_price"         => $price3,
                    "ticket_title"       => $request->title,
                    "ticket_currency"          => $request->currency,
                    'ticket_event_id' =>        $request->trip_id,
                    'discount_id' => $request->discount_id,
                    'discount_week' => $request->discountweek_id,
                    "ticket_real_cost"         => $request->price,
                   "discountdate"=>$usersseconddate,
                    "discountseconddate"=>$usersdatethird,
                    "week"         => $mydate->discount,
                    "ticket_discount"         => $ticketthird->discount_title,
                    "operator_email"=> $tripemail,
                                
                ];
                $event = $this->ticket->save_event2($params2);
            }
            else if($result3==20)
            {
                $cost3= $request->price;
                $costper3=$cost3*80;
                $price3 = (int)(($costper3)/100);
                $myseconddate= Discount::where('id',$request->seconddiscount_id)->first(); 
          
                $currsecondweek=$myseconddate->week;
              
                $myseconddays=$currsecondweek*7;
                log::info($myseconddays);
                $usersseconddates =Carbon::parse($tripdate)->subDays($myseconddays);
                $usersseconddate = substr($usersseconddates, 0, 10);     
           log::info($usersseconddate);
           
          $mythirddate= Discount::where('id',$request->thirddiscount_id)->first(); 
          $thirddiscount=$mythirddate->week;
        
          $mythirddays=$thirddiscount*7;
          log::info($mythirddays);
          $usersdatethirds =Carbon::parse($tripdate)->subDays($mythirddays);
         
          $usersdatethird = substr($usersdatethirds, 0, 10);

                $params2 = [
                    "ticket_price"         => $price3,
                    "ticket_title"       => $request->title,
                    "ticket_currency"          => $request->currency,
                    'ticket_event_id' =>        $request->trip_id,
                    'discount_id' => $request->discount_id,
                    'discount_week' => $request->discountweek_id,
                    "ticket_real_cost"         => $request->price,
                    "discountdate"=>$usersseconddate,
                    "discountseconddate"=>$usersdatethird,
                    "week"         => $mydate->discount,
                    "ticket_discount"         => $ticketthird->discount_title,
                    "operator_email"=> $tripemail,
                                
                ];
                $event = $this->ticket->save_event2($params2);
            }
            else if($result3==30)
            {
                $cost3= $request->price;
                $costper3=$cost3*70;
                $price3 = (int)(($costper3)/100);
                $myseconddate= Discount::where('id',$request->seconddiscount_id)->first(); 
          
                $currsecondweek=$myseconddate->week;
              
                $myseconddays=$currsecondweek*7;
                log::info($myseconddays);
                $usersseconddates =Carbon::parse($tripdate)->subDays($myseconddays);
                $usersseconddate = substr($usersseconddates, 0, 10);    
                log::info('yeh teesra cost wala hai'); 
           log::info($usersseconddate);
           
          $mythirddate= Discount::where('id',$request->thirddiscount_id)->first(); 
          $thirddiscount=$mythirddate->week;
        
          $mythirddays=$thirddiscount*7;
          log::info($mythirddays);
          $usersdatethirds =Carbon::parse($tripdate)->subDays($mythirddays);
         
          $usersdatethird = substr($usersdatethirds, 0, 10);
                $params2 = [
                    "ticket_price"         => $price3,
                    "ticket_title"       => $request->title,
                    "ticket_currency"          => $request->currency,
                    'ticket_event_id' =>        $request->trip_id,
                    'discount_id' => $request->discount_id,
                    'discount_week' => $request->discountweek_id,
                    "ticket_real_cost"         => $request->price,
                    "discountdate"=>$usersseconddate,
                    "discountseconddate"=>$usersdatethird,
                    "week"         => $mydate->discount,
                    "ticket_discount"         => $ticketthird->discount_title,

                    "operator_email"=> $tripemail,
                                
                ];
                $event = $this->ticket->save_event2($params2);
            }
            else{
                $cost3= $request->price;
                $costper3=$cost3*60;
                $price3 = (int)(($costper3)/100);
                $myseconddate= Discount::where('id',$request->seconddiscount_id)->first(); 
          
                $currsecondweek=$myseconddate->week;
              
               
                $myseconddays=$currsecondweek*7;
                log::info($myseconddays);
                $usersseconddates =Carbon::parse($tripdate)->subDays($myseconddays);
                $usersseconddate = substr($usersseconddates, 0, 10);
           log::info($usersseconddate);
           
          $mythirddate= Discount::where('id',$request->thirddiscount_id)->first(); 
          $thirddiscount=$mythirddate->week;
         
          $mythirddays=$thirddiscount*7;
          log::info($mythirddays);
          $usersdatethirds =Carbon::parse($tripdate)->subDays($mythirddays);
         
          $usersdatethird = substr($usersdatethirds, 0, 10);
                $params2 = [
                    "ticket_price"         => $price3,
                    "ticket_title"       => $request->title,
                    "ticket_currency"          => $request->currency,
                    'ticket_event_id' =>        $request->trip_id,
                    'discount_id' => $request->discount_id,
                    'discount_week' => $request->discountweek_id,
                    "ticket_real_cost"         => $request->price,
                    "discountdate"=>$usersseconddate,
                    "discountseconddate"=>$usersdatethird,
                    "week"         => $mydate->discount,
                    "ticket_discount"         => $ticketthird->discount_title,
                    "operator_email"=> $tripemail,
                ];
                $event = $this->ticket->save_event2($params2);
            }
            $discounts= Ticket::where('ticket_event_id',$request->trip_id)->pluck('discount_id');
            Week::where('trip_id', $request->trip_id)->whereNotIn('id', $discounts)->delete();
            $discounts_week= Ticket::where('ticket_event_id',$request->trip_id)->pluck('discount_week');
            Mydiscount::where('trip_id', $request->trip_id)->whereNotIn('id', $discounts_week)->delete();
        }
    }

        else{
            $users = Trip::where('id', $request->trip_id)->first();
          
            $tripcreate= substr($users->updated_at, 0, 10);
            $params = [
                "ticket_price"         => $request->price,
                "ticket_title"       => $request->title,
                "ticket_currency"          => $request->currency,
                'ticket_event_id' =>        $request->trip_id,
                "ticket_real_cost"         => $request->price,
                "discountseconddate"=>$tripcreate,
                "ticket_discount"         => "0%",
                "operator_email"=> $tripemail,
                           
            ];
            $event = $this->ticket->save_event($params, $request->trip_id);
            $discounts= Ticket::where('ticket_event_id',$request->trip_id)->pluck('discount_id');
            Week::where('trip_id', $request->trip_id)->whereNotIn('id', $discounts)->delete();
            $discounts_week= Ticket::where('ticket_event_id',$request->trip_id)->pluck('discount_week');
            Mydiscount::where('trip_id', $request->trip_id)->whereNotIn('id', $discounts_week)->delete();
        }



          }
          else{
            Log::info(' nahi $tripticket'); 
            $users = Trip::where('id', $request->trip_id)->first();
          
            $tripcreate= substr($users->updated_at, 0, 10);
            $params = [
                "ticket_price"         => $request->price,
                "ticket_title"       => $request->title,
                "ticket_currency"          => $request->currency,
                'ticket_event_id' =>        $request->trip_id,
                "ticket_real_cost"         => $request->price,
                "discountseconddate"=>$tripcreate,
                "ticket_discount"         => "0%",
                "operator_email"=> $tripemail,
             
            ];
            $event = $this->ticket->save_event($params, $request->trip_id);
            $discounts= Ticket::where('ticket_event_id',$request->trip_id)->pluck('discount_id');
            Week::where('trip_id', $request->trip_id)->whereNotIn('id', $discounts)->delete();
            $discounts_week= Ticket::where('ticket_event_id',$request->trip_id)->pluck('discount_week');
            Mydiscount::where('trip_id', $request->trip_id)->whereNotIn('id', $discounts_week)->delete();
          }
          
            log::info('yanha complete_step aayge result hai4');
            log::info($request->trip_id);
          
        // get update event
        $trips=  Trip::where('id',$request->trip_id)
                
            ->first();
            $user_id= $trips->user_id;
            $event= $this->trip->get_user_events($request->trip_id, $user_id);
        log::info('yanha  $event');
       log::info($event);
        log::info($event->is_publishable);
        $this->complete_step($event->is_publishable, 'tickets', $request->trip_id);
        
        return response()->json(['status' => true, 'event' => $event]);
            
        }
    }
   public function repubtrip(Request $request)
   {
    Log::info("repub me aa gaya");
       $id = $request->operator_hidden_id;
       Log::info($id);
       $mydate=Package::where('id', $id)->first();
       Log::info("mysate me aa gaya");
       Log::info($mydate);
       $tour_id=$mydate->tour_id;
       Log::info("tour me aa gaya");
       Log::info($tour_id);
       $date=$request->tour_date;
       $time=$request->tour_time;
       $merge = $date . ' ' . $time;
       $current = Carbon::now();
       if($merge>$current)
       {
          $training = Package::find($id);
          $id = $request->operator_hidden_id;
          Log::info("tour_time me aa gaya");
          Log::info($request->tour_date);
                 $training->publish = 1 ;

                

                 $image= addimage::where('trip_id', $tour_id)->update(array
                 ('publis' => '1','time'=>$request->tour_time,'datetime'=>$request->tour_date));

                 Log::info("imaghynmnjhmnjme1 me gaa gaya");
                 Log::info($image);
                 $training->datetime = $request->tour_date;
               $training->time = $request->tour_time;
              
         $training->save();
          $response = [
              'success' => true,
              'data' => $training,
              'message' => 'Training Updated successfully.'
          ];
          Log::debug((array) $response);
          return response()->json($response, 200);
        }
        else{
            return redirect()->back()->with('success', 'Email is already exists.');    
        }
        

     
  }
    public function updateinterna(Request $request)
    {

        $id = $request->admin_hidden_id;
        Log::info("here id");
        Log::info($id);
     $my= $request->countryId;
     Log::info("here  mydgid");
     Log::info($my);
            
     $Country_ids=TripCountry::where("id" , $id)->first(); 
     $cont_id=$Country_ids->slug;  
     log::info('country ids hai');       
     log::info($cont_id);
      $lower = strtolower($my);
      Log::info($lower);
      $myslug = str_replace(" ", "-", $lower);
      Log::info($myslug);
     
      $international_logo = $request->file('international_logo');

      if($my==$cont_id)
      {
        $id = $request->admin_hidden_id;
        Log::info($id);
       $training = TripCountry::find($id);
       $training->desc = $request->interdescription;
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
      if($international_logo != '')
      {
        
        $Country=Country::where("country_id" , $myslug)->first();
        Log::info($Country);
        $slug=$Country->country_name;
        Log::info($slug. 'ye slug me hai');
        if($request->hasfile('international_logo'))
            {
                $file= $request->file('international_logo'); 
                $name = $file->getClientOriginalName();
                $file->move(public_path().'/category/', $name);  
            }

           $id = $request->admin_hidden_id;
           Log::info($id);
          $training = TripCountry::find($id);
        
          $training->country = $request->countryId;
          $training->desc = $request->interdescription;
          $training->Image = $name;
          $training->slug = $slug;      
         
          $training->save();
          Log::info('save hua hai ');
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
            $Country=Country::where("country_id" , $myslug)->first();
            Log::info($Country);
            $slug=$Country->country_name;
            Log::info($slug. 'ye slug me hai');
            $id = $request->admin_hidden_id;
            Log::info($id);
           $training = TripCountry::find($id);
 
          
           $training->country = $request->countryId;
           $training->desc = $request->interdescription;
           $training->slug = $slug;   
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
//--------- state Function  ---------//
public function detailstate($id)
    {
        Log::info($id);
        Log::info("deatil function of Event");
        $event = TripState::find($id);
        $response = [   
            'success' => true,
            'message' => 'Categoty retrieved successfully for edit.',
            'data' => $event
        ];
        Log::info("response to chala gya ");
        return response()->json($response, 200);
    }


public function destroystates($id)
{
    Log::info($id);
    Log::info("delete function of Event");
    $event = TripState::findOrFail($id);
    $event->delete();
    $response = [
        'success' => true,
        'data' => $event,
        'message' => 'States Deleted successfully.'
    ];
    return response()->json($response, 200);
}  


public function mycategory()
{
    $tripcats = TripCategory::orderBy('trip_categories.id', 'desc')
      ->get()->toArray(); 
      log::info('$tripcats aa raha hai');
      log::info($tripcats);
      return response()->json(['status' => true, 'categories' => $tripcats ]);
  
  
   
}  
public function updatestates(Request $request)
{

   
      
    $id = $request->admin_hidden_id;
    Log::info("here id state");
    Log::info($id);
 $my= $request->scountryId;
 $smy= $request->sstateId;
 Log::info("here  mydgid state");
 Log::info($my);
 Log::info($smy);
      
 $SCountry_ids=TripState::where("id" , $id)->first(); 
 $scont_id=$SCountry_ids->slug1;  
 log::info('country ids hai');       
 log::info($scont_id);

  $lower = strtolower($my);
  Log::info($lower);
  $myslug = str_replace(" ", "-", $lower);
  Log::info($myslug);
  
  $state_logo = $request->file('state_logo');
  $getCountryName = Country::where('country_id', $request->scountryId)->first();

 
  if($my==$scont_id)
      {  Log::info('inside my part');
        
  if($state_logo != '')
  {
    if($request->hasfile('state_logo'))
    {
        $file= $request->file('state_logo'); 
        $name = $file->getClientOriginalName();
        $file->move(public_path().'/category/', $name);  
    }
        $training = TripState::find($id);
      
       $training->Explain = $request->explain;
       $training->Image = $name;
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
   
        $training = TripState::find($id);
      
       $training->Explain = $request->explain;
      
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

  if($state_logo != '')
  {
    if (!empty($getCountryName))
    {
        Log::info('inside !empty($getCountryName)');
          $Countryname = $getCountryName->country_name; 
          $Countriess =str_slug($Countryname);
      
    
          $getStateName = State::where('state_id', $request->sstateId)->first();
                  $Statename = $getStateName->state_name;  
          $States =str_slug($Statename);
          $country_state=collect([$Countriess, $States])->implode('/');

          $Country=Country::where("country_id" , $myslug)->first();
    Log::info($Country);
    $slug1=$Country->country_name;
    Log::info($slug1. 'ye slug me hai');
  
    $lowersta = strtolower($smy);
    Log::info($lowersta);
    $myslugstate = str_replace(" ", "-", $lowersta);
    Log::info($myslugstate);
    $State=State::where("state_id" , $myslugstate)->first();
    Log::info($State);
    $slug=$State->state_name;
    Log::info($slug. 'ye slug me hai');

    if($request->hasfile('state_logo'))
        {
            $file= $request->file('state_logo'); 
            $name = $file->getClientOriginalName();
            $file->move(public_path().'/category/', $name);  
        }

       $id = $request->admin_hidden_id;
       Log::info($id);
      $training = TripState::find($id);
    
      $training->country = $request->scountryId;
      $training->state = $request->sstateId;
      $training->Explain = $request->explain;
      $training->country_state = $country_state;
      $training->Image = $name;
      $training->slug = $slug;      
      $training->slug1 = $slug1;      
     
      $training->save();
      Log::info('save hua hai ');
       $response = [
           'success' => true,
           'data' => $training,
           'message' => 'States Updated successfully.'
       ];

       Log::debug((array) $response);
       return response()->json($response, 200);
       Log::info('response chala gya');
        
    }
    else{
        Log::info('inside else !empty($getCountryName)');
        $Country=Country::where("country_id" , $myslug)->first();
        Log::info($Country);
        $slug1=$Country->country_name;
        Log::info($slug1. 'ye slug me hai');
              $lowersta = strtolower($smy);
        Log::info($lowersta);
        $myslugstate = str_replace(" ", "-", $lowersta);
        Log::info($myslugstate);
        $State=State::where("state_id" , $myslugstate)->first();
        Log::info($State);
        $slug=$State->state_name;
        Log::info($slug. 'ye slug me hai');
            if($request->hasfile('state_logo'))
            {
                $file= $request->file('state_logo'); 
                $name = $file->getClientOriginalName();
                $file->move(public_path().'/category/', $name);  
            }
               $id = $request->admin_hidden_id;
           Log::info($id);
          $training = TripState::find($id);
          $training->country = $request->scountryId;
          $training->state = $request->sstateId;
          $training->Explain = $request->explain;
          $training->Image = $name;
          $training->slug = $slug;      
          $training->slug1 = $slug1;      
            $training->save();
          Log::info('save hua hai ');
           $response = [
               'success' => true,
               'data' => $training,
               'message' => 'States Updated successfully.'
           ];
               Log::debug((array) $response);
           return response()->json($response, 200);
             }
    
    }
    else{
        if (!empty($getCountryName))
    {

        $Countryname = $getCountryName->country_name; 
        $Countriess =str_slug($Countryname);
    
  
        $getStateName = State::where('state_id', $request->sstateId)->first();
                $Statename = $getStateName->state_name;  
        $States =str_slug($Statename);
        $country_state=collect([$Countriess, $States])->implode('/');
        Log::info('inside else part here');
        $Country=Country::where("country_id" , $myslug)->first();
        Log::info('bheetar me hai');
  Log::info($Country);
  $slug1=$Country->country_name;
  Log::info($slug1. 'ye slug me hai');
  $lowersta = strtolower($smy);
  Log::info($lowersta);
  $myslugstate = str_replace(" ", "-", $lowersta);
  Log::info($myslugstate);
  $State=State::where("state_id" , $myslugstate)->first();
  Log::info($State);
  $slug=$State->state_name;
  Log::info($slug. 'ye slug me hai');
        $id = $request->admin_hidden_id;
        Log::info($id);
       $training = TripState::find($id);
       $training->country = $request->scountryId;
       $training->state = $request->sstateId;
       $training->Explain = $request->explain;
       $training->country_state = $country_state;
           $training->slug = $slug;      
       $training->slug1 = $slug1;   
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
        Log::info('inside else part here');
        $Country=Country::where("country_id" , $myslug)->first();
        Log::info('bheetar me hai');
  Log::info($Country);
  $slug1=$Country->country_name;
  Log::info($slug1. 'ye slug me hai');
  $lowersta = strtolower($smy);
  Log::info($lowersta);
  $myslugstate = str_replace(" ", "-", $lowersta);
  Log::info($myslugstate);
  $State=State::where("state_id" , $myslugstate)->first();
  Log::info($State);
  $slug=$State->state_name;
  Log::info($slug. 'ye slug me hai');
        $id = $request->admin_hidden_id;
        Log::info($id);
       $training = TripState::find($id);
       $training->country = $request->scountryId;
       $training->state = $request->sstateId;
       $training->Explain = $request->explain;
           $training->slug = $slug;      
       $training->slug1 = $slug1;   
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
}

   public function  editstates($id)
    {
        Log::info($id);
        Log::info("edit function of Event");
        $event = TripState::find($id);
        $response = [   
            'success' => true,
            'message' => 'Categoty retrieved successfully for edit.',
            'data' => $event
        ];
        Log::info("response to chala gya ");
        return response()->json($response, 200);
    } 
    public function sattes($email)
    {
        $TripState=TripState::leftJoin('countries', 'trip_states.country', '=', 'countries.country_id')
        ->leftJoin('states', 'trip_states.state', '=', 'states.state_id')
        ->where('admin_email', $email)->orderBy('id', 'desc')->get();
         $response = [
       'success' => true,
       'data' => $TripState,
       'message' => 'Course retrieved successfully.',
       'count' => count($TripState)
       ];
       return response()->json($response, 200);    
    }

    public function storestates(Request $request)
    {
        Log::info('ye h store function states ka ');
        Log::info ($request);
        $input = $request->all();
        $validator = Validator::make($input, [
            'scountryId' => 'required|string|max:255',
            'sstateId' => 'required|string|max:255',
            'explain'=> 'required', 
            'admin_auth_id' =>'required',
            'admin_auth_name' =>'required',
            'admin_auth_email' =>'required',
            'state_logo' => 'required|file|mimes:jpeg,png,pdf|max:2048'
            ]);
            
         if ($validator->fails()) {
            $response = [
                'success' => false,
                'data'    => 'Validation Error.',
                'message' => $validator->errors()
            ];
            Log::info('validation ok');
            return response()->json($response, 422);
        } 
        else {
            if($request->hasfile('state_logo'))
            { 
                $file= $request->file('state_logo'); 
                $name = $file->getClientOriginalName();
                $file->move(public_path().'/category/', $name);  
            } 

            $lower = strtolower($request->scountryId);
            $myslug = str_replace(" ", "-", $lower);
            $Country=Country::where("country_id" , $myslug)->first();
            $slug1=$Country->country_name;
            $lowerstate = strtolower($request->sstateId);
            $myslugsta = str_replace(" ", "-", $lowerstate);
            $State=State::where("state_id" , $myslugsta)->first();
            $slug=$State->state_name;



            $getCountryName = $slug1;
            $Countriess =str_slug($getCountryName);
            $Statename =  $slug; 
            $States =str_slug($Statename);
            $country_state=collect([$Countriess, $States])->implode('/');
            Log::info($slug. 'ye slug1 me hai');

                        Log::info($slug. 'ye slug me hai');
                        $TripState = new TripState;
                        $TripState->slug = $slug;
                        $TripState->slug1 = $slug1;
                        $TripState->country = $request->scountryId;
                        $TripState->state = $request->sstateId;
                        $TripState->country_state = $country_state;
                        $TripState->explain = $request->explain;
                        $TripState->admin_id = $request->admin_auth_id;
                        $TripState->admin_name = $request->admin_auth_name;
                        $TripState->admin_email = $request->admin_auth_email;
                        $TripState->Image = $name;
                        $TripState->save();
                        Log::info($TripState. 'ye TripState me hai');
                $response = [
                    'success' => true,
                    'data' => $TripState,
                    'message' => 'States Fetching successfully.'
                ];
                return response()->json($response, 200);
        }
    }
//---- City Function -----///
public function detailcity($id)
    {
        Log::info($id);
        Log::info("deatil function of Event");
        $event = TripCity::find($id);
        $response = [   
            'success' => true,
            'message' => 'Categoty retrieved successfully for edit.',
            'data' => $event
        ];
        Log::info("response to chala gya ");
        return response()->json($response, 200);
    }


    public function destroycity($id)
    {
        Log::info($id);
        Log::info("delete function of Event");
        $event = TripCity::findOrFail($id);
        $event->delete();
        $response = [
            'success' => true,
            'data' => $event,
            'message' => 'States Deleted successfully.'
        ];
        return response()->json($response, 200);
    }  
    public function getcategory($id)
    {
        $categorys = TripCategory::all();
        Log::info($categorys);
        $response = [
            'success' => true,
            'data' => $categorys,
            'message' => 'Tool retrieved successfully.',
            'count' => count($categorys)
        ];
       
        return response()->json($response, 200);
    }
    public function updatecitys(Request $request)
    {
    
        $id = $request->admin_hidden_id;
        Log::info("here id city");
        Log::info($id);
     $my= $request->countryId1;
     $smy= $request->stateId;
     $cmy= $request->famous_city_name;
     Log::info("here  mydgid city");
     Log::info($my);
     Log::info($smy);
     Log::info($cmy);
            
     $SCountry_ids=TripCity::where("id" , $id)->first(); 
     $scont_id=$SCountry_ids->slug1;  
     log::info('country ids hai');       
     log::info($scont_id);        
    
      $lower = strtolower($my);
      Log::info($lower);
      $myslug = str_replace(" ", "-", $lower);
      Log::info($myslug);
     
      $lowersta = strtolower($smy);
      Log::info($lowersta);
      $myslugstate = str_replace(" ", "-", $lowersta);
      Log::info($myslugstate);
     

      $lowercity = strtolower($cmy);
      Log::info($lowercity);
      $myslugcity = str_replace(" ", "-", $lowercity);
      Log::info($myslugcity);
     

      $getCountryName = Country::where('country_id', $request->countryId1)->first();

      $city_logo = $request->file('city_logo');
      if($my==$scont_id)
          {  Log::info('inside my part');
            
      if($city_logo != '')
      {
        if($request->hasfile('city_logo'))
        {
            $file= $request->file('city_logo'); 
            $name = $file->getClientOriginalName();
            $file->move(public_path().'/category/', $name);  
        }
        $training = TripCity::find($id);
      
        $training->Describes = $request->description1;
           $training->Image = $name;
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
       
        $training = TripCity::find($id);
          
        $training->Describes = $request->description1;
          
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


          $city_logo = $request->file('city_logo');
          if($city_logo != '')
          {
            if (!empty($getCountryName))
            {
                Log::info('inside !empty($getCountryName)');
                  $Countryname = $getCountryName->country_name; 
                  $Countriess =str_slug($Countryname);
              
            
                  $getStateName = State::where('state_id', $request->stateId)->first();
                          $Statename = $getStateName->state_name;  
                  $States =str_slug($Statename);
                  $country_state=collect([$Countriess, $States])->implode('/');

                  $getCityName = City::where('city_id', $request->famous_city_name)->first();
                         

                  $cityname = $getCityName->city_name; 
                  $cities = str_slug($cityname); 
                  $country_state_city=collect([$Countriess, $States,$cities])->implode('/');


        
                  $Country=Country::where("country_id" , $myslug)->first();
                  Log::info($Country);
                  $slug1=$Country->country_name;
                  Log::info($slug1. 'ye slug me hai');
                  $State=State::where("state_id" , $myslugstate)->first();
                  Log::info($State);
                  $slug=$State->state_name;
                  Log::info($slug. 'ye slug me hai');
                  $City=City::where("city_id" , $myslugcity)->first();
                  Log::info($City);
                  $slug2=$City->city_name;
                  Log::info($slug2. 'ye slug me hai');
        
                  if($request->hasfile('city_logo'))
                  {
                      $file= $request->file('city_logo'); 
                      $name = $file->getClientOriginalName();
                      $file->move(public_path().'/category/', $name);  
                  }
               $id = $request->admin_hidden_id;
               Log::info($id);
               $training = TripCity::find($id);
        
               $training->country = $request->countryId1;
               $training->state = $request->stateId;
               $training->city = $request->famous_city_name;
               $training->Describes = $request->description1;
               $training->country_state_city = $country_state_city;
               $training->Image = $name;
               $training->slug = $slug;      
               $training->slug1 = $slug1;      
               $training->slug2 = $slug2; 
                   
             
              $training->save();
              Log::info('save hua hai ');
               $response = [
                   'success' => true,
                   'data' => $training,
                   'message' => 'States Updated successfully.'
               ];
        
               Log::debug((array) $response);
               return response()->json($response, 200);
               Log::info('response chala gya');
                
            }
            else{
                Log::info('inside else !empty($getCountryName)');
                $Country=Country::where("country_id" , $myslug)->first();
                Log::info($Country);
                $slug1=$Country->country_name;
                Log::info($slug1. 'ye slug me hai');
                $State=State::where("state_id" , $myslugstate)->first();
                Log::info($State);
                $slug=$State->state_name;
                Log::info($slug. 'ye slug me hai');
                $City=City::where("city_id" , $myslugcity)->first();
                Log::info($City);
                $slug2=$City->city_name;
                Log::info($slug2. 'ye slug me hai');
                if($request->hasfile('city_logo'))
                {
                    $file= $request->file('city_logo'); 
                    $name = $file->getClientOriginalName();
                    $file->move(public_path().'/category/', $name);  
                }
                $id = $request->admin_hidden_id;
                Log::info($id);
                $training = TripCity::find($id);
                $training->country = $request->countryId1;
                $training->state = $request->stateId;
                $training->city = $request->famous_city_name;
                $training->Describes = $request->description1;
                 $training->Image = $name;
                $training->slug = $slug;      
                $training->slug1 = $slug1;      
                $training->slug2 = $slug2;     
                    $training->save();
                  Log::info('save hua hai ');
                   $response = [
                       'success' => true,
                       'data' => $training,
                       'message' => 'States Updated successfully.'
                   ];
                       Log::debug((array) $response);
                   return response()->json($response, 200);
                     }
            
            }
            else{
                if (!empty($getCountryName))
            {

                $Countryname = $getCountryName->country_name; 
                $Countriess =str_slug($Countryname);
            
          
                $getStateName = State::where('state_id', $request->stateId)->first();
                        $Statename = $getStateName->state_name;  
                $States =str_slug($Statename);
                $country_state=collect([$Countriess, $States])->implode('/');

                $getCityName = City::where('city_id', $request->famous_city_name)->first();
                       

                $cityname = $getCityName->city_name; 
                $cities = str_slug($cityname); 
                $country_state_city=collect([$Countriess, $States,$cities])->implode('/');

                Log::info('inside else part here');
                $Country=Country::where("country_id" , $myslug)->first();
                Log::info($Country);
                $slug1=$Country->country_name;
                Log::info($slug1. 'ye slug me hai');
                $State=State::where("state_id" , $myslugstate)->first();
                Log::info($State);
                $slug=$State->state_name;
                Log::info($slug. 'ye slug me hai');
                $City=City::where("city_id" , $myslugcity)->first();
                Log::info($City);
                $slug2=$City->city_name;
                Log::info($slug2. 'ye slug me hai');
       
                $id = $request->admin_hidden_id;
                Log::info($id);
                $training = TripCity::find($id);
                $training->country = $request->countryId1;
                $training->state = $request->stateId;
                $training->city = $request->famous_city_name;
                $training->country_state_city = $country_state_city;
                $training->Describes = $request->description1;
                 $training->slug = $slug;      
                $training->slug1 = $slug1;      
                $training->slug2 = $slug2;   
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
                Log::info('inside else part here');
                $Country=Country::where("country_id" , $myslug)->first();
                Log::info($Country);
                $slug1=$Country->country_name;
                Log::info($slug1. 'ye slug me hai');
                $State=State::where("state_id" , $myslugstate)->first();
                Log::info($State);
                $slug=$State->state_name;
                Log::info($slug. 'ye slug me hai');
                $City=City::where("city_id" , $myslugcity)->first();
                Log::info($City);
                $slug2=$City->city_name;
                Log::info($slug2. 'ye slug me hai');
       
                $id = $request->admin_hidden_id;
                Log::info($id);
                $training = TripCity::find($id);
                $training->country = $request->countryId1;
                $training->state = $request->stateId;
                $training->city = $request->famous_city_name;
                $training->Describes = $request->description1;
                 $training->slug = $slug;      
                $training->slug1 = $slug1;      
                $training->slug2 = $slug2;   
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

      
    }
        public function  editcitys($id)
        {
            Log::info($id);
            Log::info("edit function of Event");
            $event = TripCity::find($id);
            $response = [   
                'success' => true,
                'message' => 'Categoty retrieved successfully for edit.',
                'data' => $event
            ];
            Log::info("response to chala gya ");
            return response()->json($response, 200);
        } 
    
    public function city($email)
    {
        $TripCity=TripCity::leftJoin('countries', 'trip_cities.country', '=', 'countries.country_id')
        ->leftJoin('states', 'trip_cities.state', '=', 'states.state_id')->leftJoin('cities', 'trip_cities.city', '=', 'cities.city_id')
        ->where('admin_email', $email)->orderBy('id', 'desc')->get();
        Log::info($TripCity);
         $response = [
       'success' => true,
       'data' => $TripCity,
       'message' => 'Course retrieved successfully.',
       'count' => count($TripCity)
       ];
       return response()->json($response, 200);    
    }

    public function storecity(Request $request)
    {
        Log::info('ye h store function city ka ');
        Log::info ($request);
        $input = $request->all();
        $validator = Validator::make($input, [
            'countryId1' => 'required|string|max:255',
            'stateId' => 'required|string|max:255',
            'famous_city_name' => 'required|string|max:255',
            'description1'=> 'required', 
            'admin_auth_id' =>'required',
            'admin_auth_name' =>'required',
            'admin_auth_email' =>'required',
            'city_logo' => 'required|file|mimes:jpeg,png,pdf|max:2048'
            ]);
            
         if ($validator->fails()) {
            $response = [
                'success' => false,
                'data'    => 'Validation Error.',
                'message' => $validator->errors()
            ];
            Log::info('validation ok');
            return response()->json($response, 422);
        } 
        else {
            if($request->hasfile('city_logo'))
            { 
                $file= $request->file('city_logo'); 
                $name = $file->getClientOriginalName();
                $file->move(public_path().'/category/', $name);  
            } 

            $lower = strtolower($request->countryId1);
            $myslug = str_replace(" ", "-", $lower);
          
            $Country=Country::where("country_id" , $myslug)->first();
            $slug1=$Country->country_name;

            $lowerstate = strtolower($request->stateId);
            $myslugsta = str_replace(" ", "-", $lowerstate);
          
            $State=State::where("state_id" , $myslugsta)->first();
            $slug=$State->state_name;
            Log::info($slug. 'ye slug1 me hai');

            $lowercity = strtolower($request->famous_city_name);
            $myslugcity = str_replace(" ", "-", $lowercity);
          
            $City=City::where("city_id" , $myslugcity)->first();
            $slug2=$City->city_name;


            $getCountryName = $slug1;
            $Countriess =str_slug($getCountryName);
            $Statename =  $slug; 
            $States =str_slug($Statename);
            $country_state=collect([$Countriess, $States])->implode('/');
            $getCityName = $slug2;
            $cities = str_slug($getCityName); 
            $country_state_city=collect([$Countriess, $States,$cities])->implode('/');

                        Log::info($slug. 'ye slug me hai');
                        $TripCity = new TripCity;
                        $TripCity->slug = $slug;
                        $TripCity->slug1 = $slug1;
                        $TripCity->slug2 = $slug2;
                        $TripCity->country = $request->countryId1;
                        $TripCity->state = $request->stateId;
                        $TripCity->country_state_city = $country_state_city;
                        $TripCity->city = $request->famous_city_name;
                        $TripCity->Describes = $request->description1;
                        $TripCity->admin_id = $request->admin_auth_id;
                        $TripCity->admin_name = $request->admin_auth_name;
                        $TripCity->admin_email = $request->admin_auth_email;
                        $TripCity->Image = $name;
                        $TripCity->save();
                        Log::info($TripCity. 'ye TripCity me hai');
                $response = [
                    'success' => true,
                    'data' => $TripCity,
                    'message' => 'City Fetching successfully.'
                ];
                return response()->json($response, 200);
        }
    }


    public function destroyiter($id)
    {
        Log::info($id);
        Log::info("delete function of Event");
        $event = Iternaries::findOrFail($id);
        $event->delete();
        $response = [
            'success' => true,
            'data' => $event,
            'message' => 'Event Deleted successfully.'
        ];
        return response()->json($response, 200);
    }

    public function detailsiternary($id)
    {
        Log::info($id);
        Log::info("deatil function of Event");
        $event = Iternaries::find($id);
        $response = [   
            'success' => true,
            'message' => 'Categoty retrieved successfully for edit.',
            'data' => $event
        ];
        Log::info("response to chala gya ");
        return response()->json($response, 200);
    }

    public function edititernary($id)
    {
        Log::info($id);
        Log::info("edit function of Event");
        $event = Iternaries::find($id);
        $ex=$event->trips;
        $pack=Package::where("TripTitle" , $ex)->first();
        $day=$pack->NoOfDays;
        Log::info($event);
        $response = [   
            'success' => true,
            'message' => 'Categoty retrieved successfully for edit.',
            'day' => $day,
            'data' => $event
        ];
        Log::info("response to chala gya ");
        return response()->json($response, 200);
    }


    public function iternaryupdate(Request $request)
    {
        $id = $request->operator_hidden_id;
        Log::info("here id");
        Log::info($id);
        $id = $request->operator_hidden_id;
        Log::info($id);
        $day=$request->iternary_days;
        Log::info ($day);
        $merge =  'day' .$day;
        Log::info ($merge);
        if(empty($request->iternary_days))
        {
            Log::info ('inside empty');
            $training = Iternaries::find($id);
            $dayiternary=$training->Days;
            Log::info ($dayiternary);
            $training->trips = $request->iternary_title;
            $training->Days = $dayiternary;
            $training->location = $request->iternarylocation;
            $training->explanation = $request->iternarydescription;
            $training->save();
            Log::info('save hua hai ');
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
            Log::info ('inside else empty');
          $training = Iternaries::find($id);
          $training->trips = $request->iternary_title;
          $training->Days = $merge;
          $training->location = $request->iternarylocation;
          $training->explanation = $request->iternarydescription;
          $training->save();
          Log::info('save hua hai ');
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
   public function destroyimages($id)
   {
       Log::info($id);
       Log::info("delete function of Event");
       $event = addimage::findOrFail($id);
       $event->delete();
       $response = [
           'success' => true,
           'data' => $event,
           'message' => 'Event Deleted successfully.'
       ];
       return response()->json($response, 200);
   }

   public function detailsimages($id)
   {
       Log::info($id);
       Log::info("deatil function of Event");
       $event = addimage::find($id);
       $response = [   
           'success' => true,
           'message' => 'Categoty retrieved successfully for edit.',
           'data' => $event 
       ];
       Log::info("response to chala gya ");
       return response()->json($response, 200);
   }

   public function editimages($id)
    {
        Log::info($id);
        
        Log::info("edit function of Event");
        $event = addimage::find($id);
        Log::info($event);
        $response = [   
            'success' => true,
            'message' => 'Categoty retrieved successfully for edit.',
            'data' => $event
        ];
        Log::info("response to chala gya ");
        return response()->json($response, 200);
    }

    public function storeimages(Request $request)
    {
        log::info("data is just here");
        log::info($request);
        $this->validate($request,[
            'image_title'=>'required',
         
           'youtube_url' => ['required', 'regex:/^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/'],
               ]);

               $titles=Package::where('TripTitle',$request->image_title)->where('operator_email',$request->operator_auth_email)->first();
               $tour_id=$titles->tour_id;
               log::info("data is tour_id");
               log::info($tour_id);
               $tripid=Package::where('tour_id',$tour_id)->first();
               log::info("data is tripid");
               log::info($tripid);
               
               $Perm=Package::where('TripTitle',$request->image_title)->first();
               $Permission=$Perm->Permission;
               $pub=Package::where('TripTitle',$request->image_title)->first();
               $publish=$Perm->publish;
            

               $destination=$tripid->Destination;
               $description=$tripid->Description;
               $keyword=$tripid->Keyword;
               $daynight=$tripid->daynight;
               $slug=$tripid->slug;
               log::info("data is Cuntary");
               log::info( $slug);
               $slug1=$tripid->slug1;
               $slug2=$tripid->slug2;
               $datetime=$tripid->datetime;
               $time=$tripid->time;

                if($request->hasfile('image_logo'))
                {
                    $file= $request->file('image_logo'); 
                    $name = $file->getClientOriginalName();
                    $file->move(public_path().'/category/', $name);  
                    $merge =  'https://www.holidaylandmark.com/category/ ' . $name;
                }
                        log::info("data is coming here");
                        $video = $request->youtube_url;
                        $embeds = preg_replace(
                            "/\s*[a-zA-Z\/\/:\.]*youtu(be.com\/watch\?v=|.be\/)([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i",
                            "https://www.youtube.com/embed/$2",
                            $video
                        );
                        $embeding=explode("/", $embeds);
                        $embed=Arr::last($embeding);
                             $addrequest = new addimage();
                             $addrequest->user_id = 5;
                             $addrequest->trips =  $request->image_title;
                             $addrequest->video =  $embed;
                             $addrequest->video_link =  $embeds;
                             $addrequest->trip_id =  $tour_id;
                             $addrequest->Permision =  $Permission;
                             $addrequest->publis =  $publish;
                             $addrequest->Description =  $description;
                             $addrequest->Overview =  $keyword;
                             $addrequest->Highlight =  $destination;
                             $addrequest->country =  $slug;
                             log::info("data is coming here");
                             $addrequest->state =  $slug1;
                             $addrequest->city =  $slug2;
                             $addrequest->datetime =  $datetime;
                             $addrequest->time =  $time;
                             $addrequest->daynight =  $daynight;
                             $addrequest->operator_id = $request->operator_auth_id;
                             $addrequest->operator_name = $request->operator_auth_name;
                             $addrequest->operator_email = $request->operator_auth_email;
                             $addrequest->image_name =  $name;
                             $addrequest->files =  $merge;
                           $addrequest->save(); 
    return response()->json(['success' => 'category added Successsfully ']);
}
public function imageupdate(Request $request)
{
    $id = $request->operator_hidden_id;
    Log::info("here id");
    Log::info($id);
    $titles=Package::where('TripTitle',$request->image_title)->where('operator_email',$request->operator_auth_email)->first();
    $tour_id=$titles->id;
    $tripid=Package::where('id',$tour_id)->first();
    $destination=$tripid->Destination;
    $description=$tripid->Description;
    $keyword=$tripid->Keyword;
    $daynight=$tripid->daynight;
    $slug=$tripid->slug;
    $slug1=$tripid->slug1;
    $slug2=$tripid->slug2;
    $datetime=$tripid->datetime;
    $time=$tripid->time;

  $category_logo = $request->file('image_logo');
  $video = $request->youtube_url;
  if($category_logo != '')
  {
    Log::info('if part here');
    if($request->hasfile('image_logo'))
        {
            $file= $request->file('image_logo'); 
            $name = $file->getClientOriginalName();
            $file->move(public_path().'/category/', $name);  
            $merge =  'https://www.holidaylandmark.com/category/' . $name; 
        }
        log::info("data is coming here");
        $embeds = preg_replace(
            "/\s*[a-zA-Z\/\/:\.]*youtu(be.com\/watch\?v=|.be\/)([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i",
            "https://www.youtube.com/embed/$2",
            $video
        );
        $id = $request->operator_hidden_id;
        Log::info($id);
        $embeding=explode("/", $embeds);
        $embed=Arr::last($embeding);
        Log::info($embed);
        $training = addimage::find($id);
        $training->trips = $request->image_title;
         $training->video =  $embed;
         $training->video_link =  $embeds;
        $training->image_name = $name;
        $training->files = $merge;
        $training->save();
      Log::info('save hua hai ');
       $response = [
           'success' => true,
           'data' => $training,
           'message' => 'Training Updated successfully.'
       ];
       Log::debug((array) $response);
       return response()->json($response, 200);
       Log::info('response chala gya');
    }
    elseif($category_logo != '' &&  $video != '' )
    {
      Log::info('if part here');
      if($request->hasfile('image_logo'))
          {
              $file= $request->file('image_logo'); 
              $name = $file->getClientOriginalName();
              $file->move(public_path().'/category/', $name);  
              $merge =  'https://www.holidaylandmark.com/category/' . $name;
          }
          log::info("data is coming here");
          $video = $request->youtube_url;
          $embeds = preg_replace(
              "/\s*[a-zA-Z\/\/:\.]*youtu(be.com\/watch\?v=|.be\/)([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i",
              "https://www.youtube.com/embed/$2",
              $video
          );
          $id = $request->operator_hidden_id;
          Log::info($id);
          $embeding=explode("/", $embeds);
          $embed=Arr::last($embeding);
          Log::info($embed);
          $training = addimage::find($id);
          $training->trips = $request->image_title;
           $training->video =  $embed;
           $training->video_link =  $embeds;
          $training->image_name = $name;
          $training->files = $merge;
          $training->save();
        Log::info('save hua hai ');
         $response = [
             'success' => true,
             'data' => $training,
             'message' => 'Training Updated successfully.'
         ];
         Log::debug((array) $response);
         return response()->json($response, 200);
         Log::info('response chala gya');
      }
      elseif($video != '')
      {
        Log::info('if part here');
            log::info("data is coming here");
            $video = $request->youtube_url;
            $embeds = preg_replace(
                "/\s*[a-zA-Z\/\/:\.]*youtu(be.com\/watch\?v=|.be\/)([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i",
                "https://www.youtube.com/embed/$2",
                $video
            );
            $id = $request->operator_hidden_id;
            Log::info($id);
            $embeding=explode("/", $embeds);
            $embed=Arr::last($embeding);
            Log::info($embed);
            $training = addimage::find($id);
            $images=$training->image_name;
            $merge =  'https://www.holidaylandmark.com/category/' . $images;
            $training->trips = $request->image_title;
             $training->video =  $embed;

             $training->trip_id =  $tour_id;
             $training->Description =  $description;
             $training->Overview =  $keyword;
             $training->Highlight =  $destination;
             $training->country =  $slug;
             $training->state =  $slug1;
             $training->city =  $slug2;
             $training->datetime =  $datetime;
             $training->time =  $time;
             $training->daynight =  $daynight;

             $training->video_link =  $embeds;
             $training->files = $merge;
            $training->save();
          Log::info('save hua hai ');
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
        Log::info('else part here');
        $id = $request->operator_hidden_id;
        Log::info($id);
        $video = $request->youtube_url;
        $embeds = preg_replace(
            "/\s*[a-zA-Z\/\/:\.]*youtu(be.com\/watch\?v=|.be\/)([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i",
            "https://www.youtube.com/embed/$2",
            $video
        );
       $training = addimage::find($id);
       $images=$training->image_name;
       $merge =  'https://www.holidaylandmark.com/category/' . $images;
       $training->trips = $request->image_title;
       $training->video =  $embed;

       $training->trip_id =  $tour_id;
       $training->Description =  $description;
       $training->Overview =  $keyword;
       $training->Highlight =  $destination;
       $training->country =  $slug;
       $training->state =  $slug1;
       $training->city =  $slug2;
       $training->datetime =  $datetime;
       $training->time =  $time;
       $training->daynight =  $daynight;

       $training->video_link =  $embeds;
       $training->files = $merge;
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


}