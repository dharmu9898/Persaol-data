<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AddTrip;
use App\City;
use App\Country;
use App\State;
use DB;
use App\User;
use App\Rvsp;
use App\Package;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Mail;
use App\Mail\OperatorMail;
use App\Mail\OperatorMails;
use Illuminate\Support\Arr;

class AdminLocationController extends Controller
{

    public function index()
    {
      
     $gallery =DB::table('packages')
     ->leftJoin('trip_categories','packages.Category', '=', 'trip_categories.category')
     ->leftJoin('addimages','packages.TripTitle', '=', 'addimages.trips')
     ->leftJoin('iternaries','packages.TripTitle', '=', 'iternaries.trips')                           
  ->select('packages.*','trip_categories.id','trip_categories.category','addimages.image_name','iternaries.Days','iternaries.location','iternaries.explanation')
  ->groupBy('packages.TripTitle')                            
    ->where('Permission','Approve')                             
    ->where('publish',1)                             
                 ->orderBy('packages.id', 'desc')
                      ->get()
                      ->toArray(); 
                      
                      
                      /*  $gallery = DB::table('addimages')->get()
                           ->toArray(); */
                   


//$galls=Package::orderBy('Subscriber','desc')->take(5)->get();
//$galls=Package::orderBy('Subscriber','desc')->take(5)->pluck('Subscriber');
//$galls=Package::orderBy('Subscriber','desc')->take(5)->pluck('TripTitle');
//$galls=Package::orderBy('Subscriber','desc')->take(5)->pluck('Subscriber','id');
                 
                   
                    return response()->json($gallery);


                           //   $state = State::where('states.country_id','101')->get();         

                    
                     
            
                  //   $state= State::where('country_id','101')->pluck('state_name');
                     
                     
              //   $state= State::where('states.country_id','101')->pluck('state_name','id');
               //   $city = DB::table('cities')->whereIn('cities.state_id', $state)->get();
                    
            /*     $gallery = DB::table('trip_titles')
               
                 ->leftJoin('packages','trip_titles.category', '=', 'packages.TripTitle')
                  
                 ->leftJoin('countries','packages.country', '=', 'countries.country_id')
                 ->leftJoin('states','packages.state', '=', 'states.state_id')
                 ->leftJoin('cities','packages.city', '=', 'cities.city_id')
                     ->select('trip_titles.*','states.state_name','countries.country_name','cities.city_name')
                     
                    
                     ->orderBy('trip_titles.id', 'desc')
                              ->paginate(10);*/
               
                          
             
            
}
public function show($state)
{
    log::info($state);
      $category = str_replace('-',' ',$state); 
      $tripcountry= Package::where('slug',$category)->pluck('slug');
     $tripstate=Package::where('slug1',$category)->pluck('slug1');
     $tripcity=Package::where('slug2',$category)->pluck('slug2');
     $triptitle=Package::where('TripTitle',$category)->pluck('TripTitle');
                if(!$tripcountry->isEmpty())
      {

        log::info('inside showcountry');
        $country = str_replace('-',' ',$state); 
        $countries= Country::where('country_name',$country)->pluck('country_id'); 
        $states = DB::table('states')->whereIn('states.state_country_id',$countries)->get();
        $gallery = DB::table('packages')
        ->leftJoin('trip_countries','packages.slug', '=', 'trip_countries.slug')
        ->leftJoin('addimages','packages.TripTitle', '=', 'addimages.trips')   
        ->select('packages.*','trip_countries.desc','addimages.image_name','trip_countries.Image')
        ->groupBy('packages.TripTitle')
        ->where('packages.slug', $country)
     // ->where('Permission', '=', 'yes')
       ->where(function ($query) {
               $query->where('Permission', '=', 'Approve')
                                            
      ->where('publish','1');
           })  
      ->orderBy('packages.id', 'desc')
      ->get()
      ->toArray(); 
      $galleries = DB::table('packages')
      ->leftJoin('trip_countries','packages.slug', '=', 'trip_countries.slug')
      ->leftJoin('addimages','packages.TripTitle', '=', 'addimages.trips')   
      ->select('packages.NoOfDays','trip_countries.Image')
      ->groupBy('packages.TripTitle')
    ->where('packages.slug', $country)
      ->where(function ($query) {
        $query->where('Permission', '=', 'Approve')
                                            
        ->where('publish','1');     
           })  
    ->orderBy('packages.id', 'desc') 
    ->get()
    ->toArray(); 
      $countries = Country::all()->pluck('country_name','id');
      return response()->json([
        "ok" => true,
       "data" => $gallery,  
       "galleries" => $galleries,            
    ]);
      
      }
      if(!$tripstate->isEmpty())
      {

        
        log::info('inside showstating');
        $state = str_replace('-',' ',$state); 
        $states= State::where('state_name',$state)->pluck('state_id');                  
        $cities = DB::table('cities')->whereIn('cities.city_state_id',$states)->get();
        $gallery = DB::table('packages')
        ->leftJoin('addimages','packages.TripTitle', '=', 'addimages.trips') 
       ->leftJoin('trip_states','packages.slug1', '=', 'trip_states.slug')   
        ->select('packages.*','trip_states.Explain','addimages.image_name')
        ->groupBy('packages.TripTitle')
      ->where('packages.slug1', $state)
   ->where(function ($query) {
    $query->where('Permission', '=', 'Approve')
                                            
    ->where('publish','1');
           })   
      ->orderBy('packages.id', 'desc')
      ->get()
                      ->toArray(); 
      $galleries =DB::table('packages')
      ->leftJoin('addimages','packages.TripTitle', '=', 'addimages.trips') 
     ->leftJoin('trip_states','packages.slug1', '=', 'trip_states.slug')   
     ->select('packages.NoOfDays','trip_states.Image','addimages.image_name')
      ->groupBy('packages.TripTitle')
      ->where('packages.slug1', $state)
      ->where(function ($query) {
        $query->where('Permission', '=', 'Approve')
                                            
        ->where('publish','1');     
           })  
    ->orderBy('packages.id', 'desc') 
    ->get()
    ->toArray(); 
     
    return response()->json([
        "ok" => true,
       "data" => $gallery,  
       "galleries" => $galleries,            
    ]);
      
      }
     

      if(!$tripcity->isEmpty())
      {
 
        log::info('inside showcitiess');
        $state = str_replace('-',' ',$state); 
       
        $gallery = DB::table('packages')
        ->leftJoin('addimages','packages.TripTitle', '=', 'addimages.trips')
                ->leftJoin('trip_cities','packages.slug2', '=', 'trip_cities.slug2')   
               ->select('packages.*','trip_cities.Describes','addimages.image_name')
               ->groupBy('packages.TripTitle')
        ->where('packages.slug2', $state)
         ->where(function ($query) {
          $query->where('Permission', '=', 'Approve')
                                              
          ->where('publish','1');      
             })  
        ->orderBy('packages.id', 'desc')
        ->paginate(3);
        $galleries = DB::table('packages')
        ->leftJoin('addimages','packages.TripTitle', '=', 'addimages.trips')
        ->leftJoin('trip_cities','packages.slug2', '=', 'trip_cities.slug2')   
        ->select('packages.NoOfDays','trip_cities.Image','addimages.image_name')
        ->groupBy('packages.TripTitle')
      ->where('packages.slug2', $state)
       ->where(function ($query) {
        $query->where('Permission', '=', 'Approve')
                                              
        ->where('publish','1');
             }) 
      ->orderBy('packages.id', 'desc')
      ->get()
    ->toArray();
    return response()->json([
        "ok" => true,
       "data" => $gallery,  
       "galleries" => $galleries,            
    ]);
     
      }


      if(!$triptitle->isEmpty())
      {
 
        log::info('inside showcitiess');
        $state = str_replace('-',' ',$state); 
        $publish = DB::table('packages')->where('packages.TripTitle', $state)->first();
        $pubs=$publish->publish;
                log::info($pubs);
                if($pubs==1)
                {
                   $gallery = DB::table('packages')
                   ->leftJoin('addimages','packages.TripTitle', '=', 'addimages.trips')
                   ->leftJoin('iternaries','packages.TripTitle', '=', 'iternaries.trips') 
                   ->select('packages.*','addimages.image_name','addimages.video')
                      ->where('packages.TripTitle', $state)
                      ->first();
                      $video = DB::table('addimages')
                         ->where('trips', $state)
                         ->take(8)->get();
                      $image = DB::table('addimages')
                         ->where('addimages.trips', $state)->skip(1)->take(10)
                         ->get();
                         $dayss=$gallery->NoOfDays;
                         log::info($dayss);
                         for($i=1; $i <= $dayss; $i++)
                         {
                           $day=   'day' .$i;
                          $days[]=$day;
                         }
                         $iternary = DB::table('iternaries')
                         ->whereIn('iternaries.Days', $days)
                         ->where('iternaries.trips', $state)
                          ->select('iternaries.*')
                          
                          
                          ->orderBy('iternaries.id', 'asc')
                          ->paginate(10);
                          log::info('my iternary25');
                          log::info($iternary);
                         $alluser=   Rvsp::where('TripHeading',$state)->distinct('emailid')->count('emailid');
                    return view('show', compact('gallery','iternary','pubs','image','alluser','video')); 
                       }
                      else{
                        $gallery = DB::table('packages')
                   ->leftJoin('addimages','packages.TripTitle', '=', 'addimages.trips')
                   ->leftJoin('iternaries','packages.TripTitle', '=', 'iternaries.trips') 
                   ->select('packages.*','addimages.image_name','addimages.video')
                      ->where('packages.TripTitle', $state)
                      ->first();
                      $video = DB::table('addimages')
                      ->where('trips', $state)
                      ->take(8)->get();
                      $image = DB::table('addimages')
                      ->where('addimages.trips', $state)->skip(1)->take(10)
                      ->get();
                      $iternary = DB::table('iternaries')
                      ->select('iternaries.*')
                      ->where('iternaries.trips', $state)
                      ->where('iternaries.Days', 'day 1')
                      ->orderBy('iternaries.id', 'asc')
                      ->paginate(10);
                       $iternary1 = DB::table('iternaries')
                      ->select('iternaries.*')
                      ->where('iternaries.trips', $state)
                      ->where('iternaries.Days', 'day 2')
                      ->orderBy('iternaries.id', 'asc')
                      ->paginate(3);
                      $iternary1 = DB::table('iternaries')
                      ->select('iternaries.*')
                      ->where('iternaries.trips', $state)
                      ->where('iternaries.Days', 'day 2')
                      ->orderBy('iternaries.id', 'asc')
                      ->paginate(3);
                      $alluser=   Rvsp::where('TripHeading',$state)->distinct('emailid')->count('emailid');
                      log::info('all user ka de');
                      log::info($alluser);
                      $video=$gallery->video;
                    return view('show', compact('gallery','iternary','iternary1','pubs','image','alluser','video')); 
                      }
                    }
}


}