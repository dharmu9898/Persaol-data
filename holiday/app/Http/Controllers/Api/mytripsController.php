<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManagerStatic as Image;
use App\AddTrip;
use App\City;
use App\Country;
use App\State;
use DB;
use App\Event;
use App\Eventtax;
use App\User;
use App\Userevent;
use App\TripCategory;
use Validator;
use App\addimage;
use App\Iternaries;
use Carbon\Carbon;
use App\Eventcountry;
use App\Eventstate;
use App\Eventcity;
use App\Category;
use App\Rvsp;

use App\Package;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;
use App\Mail\OperatorMail;
use App\Mail\OperatorMails;
use App\Mail\AdminMails;
use App\Mail\OperatorMailing;
use Mail;

class mytripsController extends Controller
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
}

public function mytrip()
    {

     $gallery =DB::table('packages')
     ->leftJoin('trip_categories','packages.Category', '=', 'trip_categories.category')
     ->leftJoin('addimages','packages.TripTitle', '=', 'addimages.trips')
     ->leftJoin('iternaries','packages.TripTitle', '=', 'iternaries.trips')
  ->select('packages.*','trip_categories.id','trip_categories.category','addimages.image_name','iternaries.Days','iternaries.location','iternaries.explanation')
  ->groupBy('packages.TripTitle')

                 ->orderBy('packages.id', 'desc')
                      ->get()
                      ->toArray();





                    return response()->json($gallery);

      }
public function country()
    {

      $tripcountry = Country::orderBy('country_id', 'asc')
      ->get()
      ->toArray();


      return response()->json($tripcountry);



}

public function eventtaxe()
    {

      $tripcountry = DB::connection('mysql2')->table("taxes")->orderBy('id', 'desc')
      ->get()
      ->toArray();

      return response()->json($tripcountry);

}


public function mytripsimage()
    {

      $myimage = DB::table('addimages')->orderBy('id', 'desc')
->get()
      ->toArray();
      return response()->json($myimage);

}

public function mytour()
    {


                      $mytour = json_decode(strip_tags(Package::orderBy('id', 'desc')->get()),true);
                      log::info($mytour);
                 return response()->json($mytour);



}

public function myiternary()
    {

                      $myiternary = json_decode(strip_tags(Iternary::orderBy('id', 'desc')->get()),true);

      return response()->json($myiternary);





}
public function iternaryupdate(Request $request)
{

  $id = $request->tour_id;
  Log::info("here id");
  Log::info($id);
  $id = $request->tour_id;
  Log::info($id);
  $day=$request->Days;

  if(empty($request->Days))
  {
      Log::info ('inside empty');
      $image_trip= DB::table('iternaries')->where('tour_id', $request->tour_id)
      ->update(['trips' =>$request->trips,
      'location'=>$request->location,'explanation'=>$request->explanation,'Days'=>$request->Days,]);
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
      $image_trip= DB::table('iternaries')->where('tour_id', $request->tour_id)
      ->update(['trips' =>$request->trips,
      'location'=>$request->location,'explanation'=>$request->explanation,'Days'=>$request->Days,]);
                  return response()->json([
      "ok" => true,
      "message" => "Request Successfully Added.",
  ]);
  }

}

public function storetrip(Request $request)
{
  Log::info('ye h store function international ka ');
  Log::info ($request);
  $input = $request->all();
  Log::info ('package ke pahle ');
  $trip = Package::latest()->first();
  $tours=$trip->tour_id;
   $tour_id= $tours + 1;


  $OperatorMailsends = array(
      'email' => $request->operator_email

);
Mail::to($OperatorMailsends['email'])->send(new OperatorMailing($OperatorMailsends));

$AdminMailsend = array(
  'adminemail' => $request->admin_email

);
Mail::to($AdminMailsend['adminemail'])->send(new AdminMails($AdminMailsend));
  $validator = Validator::make($input, [
      'Category' => 'required|string|max:255',
 'TripTitle' => 'required|string|max:255',
      'country' => 'required|string|max:255',
      'state' => 'required|string|max:255',
      'city' => 'required|string|max:255',
      'NoOfDays' => 'required|string|max:255',
      'night' => 'required|string|max:255',
      'datetime' => 'required|string|max:255',
      'time' => 'required',
      'Destination' =>'required',
      'Keyword' =>'required',
      'Description' =>'required',
      'operator_email' =>'required',
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
      $lower = strtolower($request->country);
      $myslug = str_replace(" ", "-", $lower);
      $Country=Country::where("country_id" , $myslug)->first();
      $slug1=$Country->country_name;
      $lowerstate = strtolower($request->state);
      $myslugsta = str_replace(" ", "-", $lowerstate);
     $State=State::where("state_id" , $myslugsta)->first();
      $slug=$State->state_name;
Log::info($slug. 'ye slug1 me hai');
     $lowercity = strtolower($request->city);
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
      $getsCategory = $request->TripTitle;
      $Category = str_slug($getsCategory);
      $c_s_c_cat=collect([$Countriess, $States,$cities,$Category])->implode('/');

      $day=$request->NoOfDays;
      $nights=$request->night;
      $merge = $day . 'days/ ' . $nights.'night';
                  $Trip = new Package;
                  $Trip->slug = $slug1;
                  $Trip->slug1 = $slug;
                  $Trip->slug2 = $slug2;
                  $Trip->publish = 0;
                  $Trip->country = $request->country;
                  $Trip->state = $request->state;
                  $Trip->city = $request->city;

                  $Trip->operator_name = $request->operator_name;
                  $Trip->operator_email = $request->operator_email;
                  $Trip->Category = $request->Category;
 $Trip->TripTitle = $request->TripTitle;
                  $Trip->NoOfDays = $request->NoOfDays;
                  $Trip->night = $request->night;
                  $Trip->daynight = $merge;
                  $Trip->datetime = $request->datetime;
                  $Trip->time = $request->time;
                  $Trip->Destination = $request->Destination;
                  $Trip->Description = $request->Description;
                  $Trip->user_id = 3;
                  $Trip->tour_id = $tour_id;
                  $Trip->Keyword = $request->Keyword;
                  $Trip->country_state = $country_state;
                  $Trip->country_state_city = $country_state_city;
                  $Trip->c_s_c_cat = $c_s_c_cat;
                  $Trip->save();
                  Log::info($Trip. 'ye TripCountry me hai');
                  return response()->json([
                    "ok" => true,
                    "message" => "Request Successfully Added.",
                ]);
  }
}
public function storeiternary(Request $request)
{
  log::info('inside store iternary');
  $this->validate($request,[
      'trips'=>'required',
      'Days'=>'required',
      'location'=>'required',
      'explanation'=>'required',
          ]);
          $titles=Package::where('TripTitle',$request->trips)->where('operator_email',$request->operator_email)->first();
          $tour_id=$titles->tour_id;
          $tripid=Package::where('id',$tour_id)->first();
          $day=$request->Days;
          Log::info ($day);
          $merge =  'day' .$day;
          Log::info ($merge);
         $addrequest = new Iternaries();

    $addrequest->trips =  $request->trips;
  $addrequest->Days =  $request->Days;
  $addrequest->location =  $request->location;
  $addrequest->explanation =  $request->explanation;
   $addrequest->operator_name = $request->operator_name;
  $addrequest->operator_email = $request->operator_email;
  $addrequest->tour_id =  $tour_id;
  $addrequest->user_id = 3;
   $addrequest->save();
   return response()->json([
    "ok" => true,
    "message" => "Request Successfully Added.",
]);
}
public function pubtrip(Request $request)
{

  Log::info("publish function of Event");

  $mydate=Package::where('tour_id', $request->tour_id)->first();
  $tour_id=$mydate->tour_id;
  $date=$mydate->datetime;
  $time=$mydate->time;
  $merge = $date . ' ' . $time;
  $current = Carbon::now();
  $date_interval =$current->diffInHours($merge);
  log::info($date_interval);
  if ( $date_interval>48 || $date_interval==48)

  {
      $event= Package::where('tour_id', $request->tour_id)->update(array('publish' => '-1'));
      $image= addimage::where('trip_id', $request->tour_id)->update(array('publis' => '-1'));

      $response = [
          'success' => false,
          'data' => $event,
          'message' => 'Event Deleted successfully.'
      ];
      return response()->json($response, 200);
  }
  else{
      $event= Package::where('tour_id', $request->tour_id)->update(array('publish' => '1'));
      $image= addimage::where('trip_id', $request->tour_id)->update(array('publis' => '1'));

      return response()->json([
 "ok" => true,
        "message" => "Request Successfully Added.",
    ]);

  }


}


public function pubevent(Request $request)
{

  Log::info("publish function of Event");



  if ( $request->publish==0 )

  {
    $image_trip= DB::connection('mysql2')->table("events")->where('event_id',$request->event_id)->
    update(array('publish' => '1'));

      $response = [
          'success' => false,
          'data' => $image_trip,
          'message' => 'Event Deleted successfully.'
      ];
      return response()->json($response, 200);
  }
}
public function unpubevent(Request $request)
{

  Log::info("unpublish function of Event");

  Log::info($request);
  $mypub=DB::connection('mysql2')->table("events")->where('event_id',$request->event_id)->first();
  $pubss=$mypub->publish;
  if ($pubss==1)

  {
    $image_trip= DB::connection('mysql2')->table("events")->where('event_id',$request->event_id)->
    update(array('publish' => '0'));
    Log::info($image_trip);
      $response = [
          'success' => false,
          'data' => $image_trip,
          'message' => 'Event Deleted successfully.'
      ];
      return response()->json($response, 200);
  }



}
public function repubtrip(Request $request)
{
  log::info('inside store iternary');

          $titles=Package::where('tour_id',$request->tour_id)->where('operator_email',$request->operator_email)->first();
          $tour_id=$titles->tour_id;
          $tripid=Package::where('tour_id',$tour_id)->first();
  $event= Package::where('tour_id', $request->tour_id)->update(array('publish' => '1','datetime' => $request->datetime));
      $image= addimage::where('trip_id', $request->tour_id)->update(array('publis' => '1','datetime' => $request->datetime));

   return response()->json([
    "ok" => true,
    "message" => "Request Successfully Added.",
]);
}

public function unpubtrip(Request $request)
{

        Log::info("unpublish function of Event");
        $mydate=Package::where('tour_id', $request->tour_id)->first();
       $tour_id=$mydate->tour_id;
        $event= Package::where('tour_id', $request->tour_id)->update(array('publish' => '-1'));
       $title=Package::where('tour_id', $request->tour_id)->pluck('TripTitle');
       $image= addimage::where('trip_id', $tour_id)->update(array('publis' => '-1'));
       $sub=Rvsp::whereIn('TripHeading', $title)->update(array('status' => '0'));
   return response()->json([
    "ok" => true,
    "message" => "Request Successfully Added.",
]);
}

public function storegallery(Request $request)
{
  log::info('inside storegallery');
  log::info($request);
  $this->validate($request,[
    'trips'=>'required',
 ]);
       log::info('yanha aata hao');
       log::info($request->trips);
       log::info($request->operator_email);
       $titles=Package::where('TripTitle',$request->trips)->where('operator_email',$request->operator_email)->first();
       log::info( $titles);
       $tour_id=$titles->tour_id;
       log::info($tour_id);
       $tripid=Package::where('tour_id',$tour_id)->first();
       log::info('inside storegallery hai');
       log::info($titles);
       $destination=$tripid->Destination;
       $description=$tripid->Description;
       $keyword=$tripid->Keyword;
       $daynight=$tripid->daynight;
       $slug=$tripid->slug;
       $slug1=$tripid->slug1;
       $slug2=$tripid->slug2;
       $Permission=$tripid->Permission;
       $publish=$tripid->publish;
       $country=$tripid->country;
       $state=$tripid->state;
       $city=$tripid->city;
       $datetime=$tripid->datetime;
       $time=$tripid->time;
        if($request->hasfile('image_name'))
        {
            $file= $request->file('image_name');
            $name = $file->getClientOriginalName();
         $imname = str_replace("image_picker", "", $name);
         $path=$file->move(public_path().'/category/', $imname);
 $string = str_ireplace("FoX", "CAT", "the quick brown fox jumps over the lazy dog");
            $merge =  'https://www.holidaylandmark.com/category/ ' . $imname;
        }


        log::info($imname);

                     $addrequest = new addimage();
                     $addrequest->user_id = 3;
                     $addrequest->trips =  $request->trips;
                     $addrequest->trip_id =  $tour_id;
                     $addrequest->Description =  $description;
                     $addrequest->Overview =  $keyword;
                     $addrequest->Highlight =  $destination;
                     $addrequest->country =  $slug;
                     $addrequest->state =  $slug1;
                     $addrequest->city =  $slug2;

                     $addrequest->Permision =  $Permission;
                     $addrequest->publis =  $publish;

                     $addrequest->country_id =  $country;
                     $addrequest->state_id =  $state;
                     $addrequest->city_id =  $city;
                     $addrequest->datetime =  $datetime;
                     $addrequest->time =  $time;
                     $addrequest->daynight =  $daynight;

                     $addrequest->operator_name = $request->operator_name;
                     $addrequest->operator_email = $request->operator_email;
                     $addrequest->image_name =  $imname;
                     $addrequest->files =  $merge;
 $addrequest->save();
                   return response()->json([
                    "ok" => true,
                    "message" => "Request Successfully Added.",
                ]);
}

public function eventmedia(Request $request)
{
  log::info('inside storegallery');
  log::info($request);


  $my_data= Event::orderBy('id','desc')->first();
  $my_publish=$my_data->is_publishable;
  $contains = str_contains($my_publish, 'media');
  log::info($contains);
  log::info('$contains hai');
  if($contains==1){
    $myslug = str_replace(',"media":1', '', $my_publish);
    log::info($myslug);
    $pubs=rtrim($myslug,'}');
  log::info($pubs);
  $is_publishable= $pubs.',"media":1}';
  log::info($is_publishable);
        if($request->hasfile('image_name'))
        {

 $file= $request->file('image_name');
 $name = $file->getClientOriginalName();
$imname = str_replace("image_picker", "", $name);
$image_resize = Image::make($file->getRealPath());
$image_resize->resize(512, 512);

$changepath=public_path();
$rep_path = str_ireplace("site", "events", $changepath);
$final_path = '/storage/'.'events/'.Carbon::now()->format('FY').'/';


$path=$image_resize->resize(512, 512, function ($constraint) {
  $constraint->aspectRatio();
})->save($rep_path.$final_path.$imname);
log::info($path);
        }

        $thumbnail = 'events/'.Carbon::now()->format('FY').'/'.$imname;
        log::info($thumbnail);
        $myimg='events\/'.Carbon::now()->format('FY').'\/'.$imname;
        $imgs='["' . $myimg . '"]';
        $images=$imgs;

        log::info($images);

        $image_trip= DB::connection('mysql2')->table("events")->where('id',$request->event_id)->
        update(array('thumbnail' => $thumbnail, 'is_publishable'  =>  $is_publishable));



                   return response()->json([
                    "ok" => true,
                    "message" => "Request Successfully Added.",
                ]);
  }
             else{
  $pubs=rtrim($my_publish,'}');
  log::info($pubs);
  $is_publishable= $pubs.',"media":1}';
  log::info($is_publishable);
        if($request->hasfile('image_name'))
        {

 $file= $request->file('image_name');
 $name = $file->getClientOriginalName();
$imname = str_replace("image_picker", "", $name);

$image_resize = Image::make($file->getRealPath());
$image_resize->resize(512, 512);

$changepath=public_path();
$rep_path = str_ireplace("site", "events", $changepath);
$final_path = '/storage/'.'events/'.Carbon::now()->format('FY').'/';


$path=$image_resize->resize(512, 512, function ($constraint) {
  $constraint->aspectRatio();
})->save($rep_path.$final_path.$imname);
log::info($path);

        }



        $thumbnail = 'events/'.Carbon::now()->format('FY').'/'.$imname;
        log::info($thumbnail);
        $myimg='events\/'.Carbon::now()->format('FY').'\/'.$imname;
$imgs='["' . $myimg . '"]';
        $images=$imgs;

        log::info($images);

        $image_trip= DB::connection('mysql2')->table("events")->where('id',$request->event_id)->
        update(array('thumbnail' => $thumbnail,'is_publishable'  =>  $is_publishable));



                   return response()->json([
                    "ok" => true,
                    "message" => "Request Successfully Added.",
                ]);
              }
}
public function eventsmedia(Request $request)
{
  log::info('inside storegallery');
  log::info($request);



        if($request->hasfile('image_name'))
        {

 $file= $request->file('image_name');
 $name = $file->getClientOriginalName();
$imname = str_replace("image_picker", "", $name);

$image_resize = Image::make($file->getRealPath());
$image_resize->resize(1920, 1080);
$changepath=public_path();
$rep_path = str_ireplace("site", "events", $changepath);
$final_path = '/storage/'.'events/'.Carbon::now()->format('FY').'/';


$path=$image_resize->resize(512, 512, function ($constraint) {
  $constraint->aspectRatio();
})->save($rep_path.$final_path.$imname);
log::info($path);

        }




        $myimg='events\/'.Carbon::now()->format('FY').'\/'.$imname;
        $imgs='["' . $myimg . '"]';
        $images=$imgs;

        log::info($images);
        $poster ='events/'.Carbon::now()->format('FY').'/'.$imname;
        $image_trip= DB::connection('mysql2')->table("events")->where('id',$request->event_id)->
        update(array('poster'  => $poster,'images'  => $images));



                   return response()->json([
                    "ok" => true,
                    "message" => "Request Successfully Added.",
                ]);
}

public function eventsallmedia(Request $request)
{
  log::info('inside storegallery');
  log::info($request);



        if($request->hasfile('image_name'))
        {

 $file= $request->file('image_name');
 $name = $file->getClientOriginalName();
$imname = str_replace("image_picker", "", $name);

$image_resize = Image::make($file->getRealPath());
$image_resize->resize(512, 512);

$changepath=public_path();
$rep_path = str_ireplace("site", "events", $changepath);
$final_path = '/storage/'.'events/'.Carbon::now()->format('FY').'/';


$path=$image_resize->resize(512, 512, function ($constraint) {
  $constraint->aspectRatio();
})->save($rep_path.$final_path.$imname);
log::info($path);

        }


        $imagedata = DB::connection('mysql2')->table("events")->where('id',$request->event_id)->pluck('images');
        log::info('$imagedata');
                       if(!$imagedata->isEmpty() )
        {

       $firstimage = DB::connection('mysql2')->table("events")->where('id',$request->event_id)->first();

       $fst=$firstimage->images;
       $fstsecond=rtrim($fst,'"]');
        $myimg='events\/'.Carbon::now()->format('FY').'\/'.$imname;

        $imgs=$fstsecond.','. $myimg . '"]';
        $images=$imgs;

        log::info($images);
        $poster ='events/'.Carbon::now()->format('FY').'/'.$imname;
        $image_trip= DB::connection('mysql2')->table("events")->where('id',$request->event_id)->
        update(array('images'  =>  $images));



                   return response()->json([
                    "ok" => true,
                    "message" => "Request Successfully Added.",
                ]);
              }

              else{
                $myimg='events\/'.Carbon::now()->format('FY').'\/'.$imname;
                $imgs='["' . $myimg . '"]';
                $images=$imgs;

                log::info($images);
                $poster ='events/'.Carbon::now()->format('FY').'/'.$imname;
                                  $image_trip= DB::connection('mysql2')->table("events")->where('id',$request->event_id)->
                update(array('images'  =>  $images));



                           return response()->json([
                            "ok" => true,
                            "message" => "Request Successfully Added.",
                        ]);

              }
}
public function storeprofile(Request $request)
{
  log::info('inside of inside storeprofile');


    $input = $request->all();
    log::info($input);
    $validator = Validator::make($input, [
        'emailid'=>'required',
        'Phoneno'=>'required',
        'Name'=>'required',
        'Address'=>'required',
    ]);
    if ($validator->fails()) {
        return response()->json([
            'ok' => false,
            'error' => $validator->messages(),
        ]);
    }
    try {
                           $titles=Package::where('TripTitle',$request->TripHeading)->first();
      log::info($titles);
      $tour_id=$titles->tour_id;
      log::info($tour_id);
      $tripid=Package::where('id',$tour_id)->first();
      $sub=$request->TripHeading;
      $data = $request->emailid;
      log::info($data);
$email = Rvsp::where('emailid',$data)->where('TripHeading',$sub)->first();
$myemail = Rvsp::where('emailid',$data)->where('TripHeading',$sub)->pluck('emailid');
$visit_trip = Rvsp::where('emailid',$data)->pluck('emailid');
if(!$myemail->isEmpty() )
{
                 $trip=$email->TripHeading;
                 log::info($trip);
                 log::info($sub);
  if($trip==$sub){
    log::info('inside it');
    DB::table('rvsps')
    ->where('emailid', $data)
    ->increment('visit_trip', 1);
    DB::table('rvsps')
  ->where('emailid', $data)->where('TripHeading', $sub)
  ->increment('users', 1);
  }
  else{
    $addrequest = new Rvsp();
    log::info('inside of inside else');
    DB::table('rvsps')
    ->where('emailid', $data)
    ->increment('visit_trip', 1);
    DB::table('packages')
                            ->where('TripTitle', $sub)
  ->increment('Subscriber', 1);
   $addrequest->emailid= $request->emailid;
   $addrequest->Phoneno =  $request->Phoneno;
   $addrequest->Name =  $request->Name;
   $addrequest->status =  1;
   $addrequest->tour_id =  $tour_id;

   $addrequest->visit_trip =  1;
   $addrequest->Address =  $request->Address;
   $addrequest->TripHeading =  $request->TripHeading;
                 $addrequest->save();
                }
}
        return response()->json([
            "ok" => true,
            "message" => "Request Successfully Added.",
        ]);
    } catch (\Exception $ex) {
        return response()->json([
            "ok" => false,
            "error" => $ex->getMessage(),
        ]);
    }
}


public function storeprofiles(Request $request)
{
  log::info('inside of inside storeingfprofile');
  $input =$request->emailid;
 log::info($input);
      $userdata = DB::table('rvsps')->where('emailid', $input)->first();
      $usersdata = DB::table('rvsps')->where('emailid', $input)->pluck('emailid');
      if(!$usersdata->isEmpty() )
      {
      $name=$userdata->Name;
      $address=$userdata->Address;
      $phone=$userdata->Phoneno;
      $TripHeading =  $request->TripHeading;
      log::info('inside show stores okk');
              $this->validate($request, [
                'emailid'  =>  'required',

        ]);
        $sub=$request->TripHeading;
                            $data = $request->emailid;
                          log::info($data);
                    $email = Rvsp::where('emailid',$data)->where('TripHeading',$sub)->first();
                    $myemail = Rvsp::where('emailid',$data)->where('TripHeading',$sub)->pluck('emailid');
                    $visit_trip = Rvsp::where('emailid',$data)->pluck('emailid');
                      if(!$myemail->isEmpty() )
{
             $trip=$email->TripHeading;
             log::info($trip);
             log::info($sub);
        if($trip==$sub){
        log::info('inside it');
        DB::table('rvsps')
        ->where('emailid', $data)
        ->increment('visit_trip', 1);
        DB::table('rvsps')
        ->where('emailid', $data)->where('TripHeading', $sub)
 ->increment('users', 1);
        }
        else{
        $addrequest = new Rvsp();
        log::info('inside of inside else');
        DB::table('rvsps')
        ->where('emailid', $data)
        ->increment('visit_trip', 1);
        DB::table('packages')
        ->where('TripTitle', $sub)
        ->increment('Subscriber', 1);
          $addrequest->emailid= $request->emailid;
        $addrequest->Phoneno =  $phone;
        $addrequest->Name =  $name;
        $addrequest->status =  1;
        $addrequest->Address =  $address;
        $addrequest->TripHeading =  $request->TripHeading;
        $addrequest->password = $request->password;
                      $addrequest->save();
      }
      }
      else{
      if(!$visit_trip->isEmpty() )
      {
      log::info('inside visit else');
      $addrequest = new Rvsp();
      $counts = DB::table('rvsps')
      ->where('emailid', $data)
      ->count('emailid');
      DB::table('packages')
      ->where('TripTitle', $sub)
      ->increment('Subscriber', 1);
  $addrequest->save();
                    $addrequest->emailid= $request->emailid;
                    $addrequest->Phoneno =  $phone;
                    $addrequest->Name =  $name;
                    $addrequest->status =  1;
      $addrequest->visit_trip =  $counts+1;
      $addrequest->Address =  $address;
      $addrequest->TripHeading =  $request->TripHeading;
      $addrequest->password = $request->password;
                    $addrequest->save();
      }
      else{
      log::info('inside else');
      $addrequest = new Rvsp();
      DB::table('packages')
      ->where('TripTitle', $sub)
      ->increment('Subscriber', 1);
      $addrequest->emailid= $request->emailid;
                    $addrequest->Phoneno =  $phone;
                    $addrequest->Name =  $name;
                    $addrequest->status =  1;
      $addrequest->visit_trip =  1;
      $addrequest->Address =  $address;
      $addrequest->TripHeading =  $request->TripHeading;
      $addrequest->password = $request->password;
                  $addrequest->save();
                  }
                  log::info('yanha addreq aa raha hai');
                  log::info($addrequest);
      }
      return response()->json([
        "ok" => true,
 "message" => "Request Successfully Added.",
    ]);
    }
    else{
      return response()->json([
        "ok" => false,
        "message" => "Request not Successfully Added.",
    ]);  }
}
public function state(Request $request)
    {

      $tripstate = State::where("state_country_id",$request->country_id)

      ->get();


      return response()->json($tripstate);



}

public function iternaryday(Request $request)
    {

      $tripstate = Package::where("tour_id",$request->tour_id)
           ->first();
           $dayss=$tripstate->NoOfDays;

           for($i=1; $i <= $dayss; $i++)
           {
 $day=   'day'.$i;
            $days[]=$day;
           }
           log::info('yeh hai tripstaiternary days');
                     log::info($days);
      return response()->json($days);

}

public function storetiming(Request $request)
    {
      log::info('inside here storetiming');
      $data = $request->all();
      log::info('inside here');
      log::info($request);

      $my_data= Event::orderBy('id','desc')->first();
      $my_publish=$my_data->is_publishable;
      $contains = str_contains($my_publish, 'timing');
if($contains==1)
{
  $myslug = str_replace(',"timing":1', '', $my_publish);
  log::info($myslug);
  $pubs=rtrim($myslug,'}');
log::info($pubs);
$is_publishable= $pubs.',"timing":1}';


$image_trip= DB::connection('mysql2')->table("events")->where('event_id',$request->event_id)->
update(array('start_date' => $data['start_date'], 'end_date'  => $data['end_date'],'start_time'  =>  $data['start_time'],'end_time'  => $data['end_time'],'is_publishable'  => $is_publishable));

 return response()->json([
      "ok" => true,
      "message" => "Request Successfully Added.",
  ]);

}
else{
      $pubs=rtrim($my_publish,'}');
      log::info($pubs);
      $is_publishable= $pubs.',"timing":1}';
      log::info($is_publishable);

   $image_trip= DB::connection('mysql2')->table("events")->where('event_id',$request->event_id)->
   update(array('start_date' => $data['start_date'], 'end_date'  => $data['end_date'],'start_time'  =>  $data['start_time'],'end_time'  => $data['end_time'],'is_publishable'  => $is_publishable));


         log::info('$image_trip');
         log::info($image_trip);
        return response()->json([
          "ok" => true,
          "message" => "Request Successfully Added.",
      ]);

    }
}

public function storelocation(Request $request)
    {

      $data = $request->all();
                               $lower = strtolower($request->slug3);
      $country = str_replace(" ", "-", $lower);
      $lower_state = strtolower($request->state);
      $state = str_replace(" ", "-", $lower_state);
      $lower_city = strtolower($request->city);
      $city = str_replace(" ", "-", $lower_city);

      $my_data= Event::orderBy('id','desc')->first();
      $my_publish=$my_data->is_publishable;
      $contains = str_contains($my_publish, 'location');

      if($contains==1){


        $myslug = str_replace(',"location":1', '', $my_publish);
        log::info($myslug);
        $pubs=rtrim($myslug,'}');
      log::info($pubs);
      $is_publishable= $pubs.',"location":1}';

        $image_trip= DB::connection('mysql2')->table("events")->where('event_id',$request->event_id)->
         update(array('venue' => $data['venue'], 'address'  => $data['address'],'latitude'  =>  $data['latitude'],'longitude'  => $data['longitude'],'city'  => $data['city'],'state'  => $data['state'],'zipcode'  => $data['zipcode'],'country_id'  => $data['country_id'],'slug3'  => $country,'slug2'  => $state,'slug1'  => $city,'is_publishable'  => $is_publishable));

           log::info('$image_trip');
           log::info($image_trip);
          return response()->json([
            "ok" => true,
            "message" => "Request Successfully Added.",
        ]);
      }
$pubs=rtrim($my_publish,'}');
      log::info($pubs);
      $is_publishable= $pubs.',"location":1}';
      log::info($is_publishable);


      $image_trip= DB::connection('mysql2')->table("events")->where('event_id',$request->event_id)->
       update(array('venue' => $data['venue'], 'address'  => $data['address'],'latitude'  =>  $data['latitude'],'longitude'  => $data['longitude'],'city'  => $data['city'],'state'  => $data['state'],'zipcode'  => $data['zipcode'],'country_id'  => $data['country_id'],'slug3'  => $country,'slug2'  => $state,'slug1'  => $city,'is_publishable'  => $is_publishable));

         log::info('$image_trip');
         log::info($image_trip);
        return response()->json([
          "ok" => true,
          "message" => "Request Successfully Added.",
      ]);
    }


public function storeseo(Request $request)
    {

      $data = $request->all();
      log::info('inside here');
      log::info($request);



   $image_trip= DB::connection('mysql2')->table("events")->where('event_id',$request->event_id)->
              update(array('meta_title' => $data['meta_title'], 'meta_description'  => $data['meta_description'],'meta_keywords'  =>  $data['meta_keywords']));

         log::info('$image_trip');
         log::info($image_trip);
        return response()->json([
          "ok" => true,
          "message" => "Request Successfully Added.",
      ]);


}
public function storeticket(Request $request)
    {

      $data = $request->all();
      log::info('inside here');
      log::info($request);
      $my_data= Event::orderBy('id','desc')->first();
      $my_publish=$my_data->is_publishable;
      $contains = str_contains($my_publish, 'location');
      if($contains==1){

        $myslug = str_replace(',"tickets":1', '', $my_publish);
        log::info($myslug);
        $pubs=rtrim($myslug,'}');
      log::info($pubs);
      $is_publishable= $pubs.',"tickets":1}';

        $myimage_event= DB::connection('mysql2')->table("events")->where('id',$request->event_id)->
        update(array('price_type' => 1,'is_publishable'  => $is_publishable));
 $myemail_id=Userevent::where('id',$data['user_id'])->first();
        $myemails=$myemail_id->email;

     $image_trip= DB::connection('mysql2')->table("tickets")->
     insert([
      ['title' => $data['title'], 'price'  => $data['price'],'quantity'  =>  $data['quantity'],'description'  => $data['description'],'event_id'  => $data['event_id'],'status'  => 1,'user_id'  => $data['user_id'],'operator_email'  => $myemails]

    ]);


           log::info('$image_trip');
           log::info($image_trip);
          return response()->json([
            "ok" => true,
            "message" => "Request Successfully Added.",
        ]);
      }
      else{
      $pubs=rtrim($my_publish,'}');
      log::info($pubs);
      $is_publishable= $pubs.',"tickets":1}';
      log::info($is_publishable);


      $myimage_event= DB::connection('mysql2')->table("events")->where('id',$request->event_id)->
      update(array('price_type' => 1,'is_publishable'  => $is_publishable));



   $image_trip= DB::connection('mysql2')->table("tickets")->
                        insert([
    ['title' => $data['title'], 'price'  => $data['price'],'quantity'  =>  $data['quantity'],'description'  => $data['description'],'event_id'  => $data['event_id'],'status'  => 1,'user_id'  => $data['user_id']]

  ]);


         log::info('$image_trip');
         log::info($image_trip);
        return response()->json([
          "ok" => true,
          "message" => "Request Successfully Added.",
      ]);
    }

}
public function category_country(Request $request)
    {


      $tripstate = Package::where("Category",$request->Category)->where("publish",1)->where("Permission",'Approve')->groupBy('country')->get();




      return response()->json($tripstate);



}
public function getmycountry(Request $request)
 {
      $tripstate = addimage::where("operator_email",$request->operator_email)->where("publis",1)
      ->where("Permision",'Approve')->groupBy('country_id')->get();

      return response()->json($tripstate);
}

public function evengetmycountry(Request $request)
    {
      $products = Event::where("operator_email",$request->operator_email)->where('publish','1')->groupBy('slug3')
      ->get()
      ->toArray();
      return response()->json($products);
}
public function getmyeventstate(Request $request)
    {
      $products = Event::where("operator_email",$request->operator_email)->where('publish','1')->groupBy('slug2')
      ->get()
      ->toArray();
      return response()->json($products);
}

public function getmystate(Request $request)
    {
      $tripstate = addimage::where("operator_email",$request->operator_email)->where("publis",1)
      ->where("Permision",'Approve')->groupBy('state_id')->get();

      return response()->json($tripstate);
}

public function fileventcity(Request $request)
    {
$products = Event::where("operator_email",$request->operator_email)->where('publish','1')->groupBy('slug1')
      ->get()
      ->toArray();
      return response()->json($products);
}

public function getmycity(Request $request)
    {
      $tripstate = addimage::where("operator_email",$request->operator_email)->where("publis",1)
      ->where("Permision",'Approve')->groupBy('city_id')->get();

      return response()->json($tripstate);
}


public function category_state(Request $request)
    {

      $tripstate = Package::where("Category",$request->Category)->where("publish",1)->where("Permission",'Approve')->groupBy('state')->get();




      return response()->json($tripstate);



}
public function category_city(Request $request)
    {
   $tripstate = Package::where("Category",$request->Category)->where("publish",1)->where("Permission",'Approve')->groupBy('city')->get();




      return response()->json($tripstate);



}

public function gettriptitle(Request $request)
    {

      $tripstate = Package::where("operator_email",$request->operator_email)->orderBy('id', 'desc')->get();
      log::info('inside gettriptitle ');
   log::info($tripstate);


      return response()->json($tripstate);



}
public function category_country_state(Request $request)
    {
      log::info('inside _country_state');
      log::info($request->Category);
      log::info('inside else');
      log::info($request->country);
                              $tripstate = Package::where("Category",$request->Category)->where("country",$request->country)->where("publish",1)->where("Permission",'Approve')->groupBy('state')->get();
      log::info($tripstate);
      return response()->json($tripstate);



}
public function category_country_city(Request $request)
    {

      $tripstate = Package::where("Category",$request->Category)->where("state",$request->state)->where("publish",1)->where("Permission",'Approve')->groupBy('city')->get();




      return response()->json($tripstate);



}
public function city_category(Request $request)
    {

      $tripstate = Package::where("city",$request->city)->where("publish",1)->where("Permission",'Approve')->groupBy('Category')->get();


      return response()->json($tripstate);

}
public function tripdelete(Request $request)
    {


      $event = Package::where("tour_id" , $request->tour_id)->first();
      $eventtrip=$event->TripTitle;
      log::info($eventtrip);
      $event_iter=addimage::where("trip_id" , $request->tour_id)->delete();
      $event_image=Iternaries::where("tour_id" , $request->tour_id)->delete();
      $title=Package::where('tour_id', $request->tour_id)->pluck('TripTitle');
      $sub=Rvsp::whereIn('tour_id', $title)->update(array('status' => '0'));

      $event->delete();

      return response()->json([
        "ok" => true,
        "message" => "Request Successfully Added.",
    ]);
      return response()->json($response, 200);



}

public function iternarydelete(Request $request)
    {




      $event_image=Iternaries::where("tour_id" , $request->tour_id)->where("Days" , $request->Days)->delete();

 return response()->json([
        "ok" => true,
        "message" => "Request Successfully Added.",
    ]);
      return response()->json($response, 200);



}
public function updateprofile(Request $request)
    {

    log::info('inside here');
    log::info($request);
    $data = $request->all();
 $slug=$request->name;
 $mfilter = str_replace("","%",$slug);
 log::info($request);
 log::info($slug);
 log::info($mfilter);


 $image_trip= DB::connection('mysql2')->table("users")->where('email', $request->email)->
 update(array('name' => $data['name'],  'address' => $data['address'],'slug' => $mfilter,'role_id' =>3, ));


       log::info('$image_trip');
       log::info($image_trip);
      return response()->json([
        "ok" => true,
 "message" => "Request Successfully Added.",
    ]);
      return response()->json($response, 200);



}

public function eventdetail(Request $request)
    {
      $data = $request->all();
    log::info('inside here');
    log::info($request);
  $email_id=Userevent::where('id',$request->user_id)->first();
  $category=Category::where('name',$request->category)->first();
  $emai_ids=$email_id->id;

  $emailss=$email_id->email;
  $cat_ids=$category->id;
  print($emai_ids);
  $event = Event::orderBy('id','desc')->first();
  log::info('event aa raha hai');
  log::info($event);
 $events_id=$event->event_id;
  $ev_id= $events_id + 1;
  log::info($ev_id);
$is_publishable='{"detail":1}';
$lowerslug = strtolower($request->title);
$mainslug = str_replace(" ", "-", $lowerslug);
 $image_trip= DB::connection('mysql2')->table("events")->
 insert([
  ['title' => $data['title'],'slug' => $mainslug, 'user_id'  => $emai_ids,'category_id'  => $cat_ids,'faq'  => $data['faq'],'category'  => $data['category'], 'excerpt' =>$data['excerpt'],'description'=> $data['description'],'is_publishable'=>$is_publishable,'event_id'=>$ev_id,'operator_email'=>$emailss]

]);

       log::info('$image_trip');
       log::info($image_trip);
      return response()->json([
        "ok" => true,
        "message" => "Request Successfully Added.",
    ]);
      return response()->json($response, 200);

}



public function updatedetail(Request $request)
    {
      $data = $request->all();
    log::info('inside here');
    log::info($request);
 $category=Category::where('name',$request->category)->first();

  $cat_ids=$category->id;



$lowerslug = strtolower($request->title);
$mainslug = str_replace(" ", "-", $lowerslug);
 $image_trip= DB::connection('mysql2')->table("events")->where('event_id',$request->event_id)->
  update(array('title' => $data['title'],'slug' => $mainslug, 'category_id'  => $cat_ids,'faq'  => $data['faq'],'category'  => $data['category'], 'excerpt' =>$data['excerpt'],'description'=> $data['description']));


       log::info('$image_trip');
       log::info($image_trip);
      return response()->json([
        "ok" => true,
        "message" => "Request Successfully Added.",
    ]);
      return response()->json($response, 200);

}
public function updatetiming(Request $request)
    {
      $data = $request->all();
    log::info('inside here');
    log::info($request);

    $image_trip= DB::connection('mysql2')->table("events")->where('event_id',$request->event_id)->
    update(array('start_date' => $data['start_date'], 'end_date'  => $data['end_date'],'start_time'  =>  $data['start_time'],'end_time'  => $data['end_time']));
return response()->json([
        "ok" => true,
        "message" => "Request Successfully Added.",
    ]);
      return response()->json($response, 200);

}


public function fcmTokenStore(Request $request)
    {
      $data = $request->all();
    log::info('inside here');
    log::info($request);
    log::info($request->userId);
    $lower = strtolower($request->userId);
    $slugs = str_replace("[", "", $lower);
    $myslugs = str_replace("]", "", $slugs);

    if($request->userId!=null)
    {

      $image_trip= DB::connection('mysql2')->table("users")->where('id',$myslugs)->update(array('remember_token'  => $data['token']));






       log::info('$image_trip');
       log::info($image_trip);
 return response()->json([
        "ok" => true,
        "message" => "Request Successfully Added.",
    ]);
      return response()->json($response, 200);
    }

}


public function myprofile(Request $request)
    {
      $data = $request->all();
    log::info('inside here');
    log::info($request);
    log::info($request->userId);



      $image_trip= DB::connection('mysql2')->table("users")->where('email',$request->email)->get()
      ->toArray();

      return response()->json($image_trip);


}

public function storeuserprofile(Request $request)
    {
      $data = $request->all();
    log::info('inside here');
    log::info($request);$my_email= DB::connection('mysql2')->table("users")->where('email',$request->email)->pluck('email');

    if($my_email->isEmpty() )
    {
      $lower = strtolower($request->name);
      $slugs = str_replace(" ", "-", $lower);
    $image_trip= DB::connection('mysql2')->table("users")->insert([
      ['name' => $data['name'],'email' => $data['email'], 'avatar'  => $data['image'],'role_id'  => 3,'slug' => $slugs]

    ]);

      $my_trip= DB::connection('mysql2')->table("users")->where('email',$request->email)->first();
       $my_id=$my_trip->id;
       $social_trip= DB::connection('mysql2')->table("social_providers")->insert([
        ['provider_id' => $data['provider_id'],'provider' => $data['provider'], 'user_id'  => $my_id]

      ]);

       log::info('$image_trip');
       log::info($image_trip);
       return response()->json($image_trip);
    }
    $mydata = DB::connection('mysql2')->table("users")->
    where('email',$request->email)
      ->get();
      log::info('$mydata');

      log::info($mydata);
      $myids = DB::connection('mysql2')->table("users")->
    where('email',$request->email)
      ->pluck('id');
      $myemail = DB::connection('mysql2')->table("users")->
                           where('email',$request->email)
        ->pluck('email');
        $myaddress = DB::connection('mysql2')->table("users")->
        where('email',$request->email)
          ->pluck('address');

          $myname = DB::connection('mysql2')->table("users")->
          where('email',$request->email)
            ->pluck('slug');


      log::info($myids);
      return response()->json(['status'=>true,
      'user'=>$mydata,'userid'=>$myids,'useremail'=>$myemail,'useraddress'=>$myaddress,'userphone'=>'8521727378','username'=>$myname
      ]);

}



public function storephoneprofile(Request $request)
    {
      $data = $request->all();
    log::info('inside here');
    log::info($request);
    log::info($request->userid);
    $lower = strtolower($request->userid);
    $slugs = str_replace("[", "", $lower);
    $myuserid = str_replace("]", "", $slugs);

$names = strtolower($request->name);
    $nameslug = str_replace(" ", "-", $names);

    $lowerph = strtolower($request->phone);
    $slugsph = str_replace("[", "", $lowerph);
    $myuserphone = str_replace("]", "", $slugsph);

    log::info($myuserid);
    $myupemail= DB::connection('mysql2')->table("users")->where('email',$request->email)->pluck('email');
    if($myupemail->isEmpty() )
    {
    $image_trip= DB::connection('mysql2')->table("users")->where('id',$myuserid)->
    update(array('email' => $data['email'], 'name'  => $data['name'],'phone'  =>  $myuserphone,'address'  => $data['address'],'slug'  => $nameslug));

       log::info('$image_trip');
       log::info($image_trip);
       $mydata = DB::connection('mysql2')->table("users")->
       where('email',$request->email)
         ->get();
         log::info('$mydata');

         log::info($mydata);
         $myids = DB::connection('mysql2')->table("users")->
       where('email',$request->email)
         ->pluck('id');

         $myemail = DB::connection('mysql2')->table("users")->
         where('email',$request->email)
           ->pluck('email');
           log::info($myemail);
 $myphone = DB::connection('mysql2')->table("users")->
           where('email',$request->email)
             ->pluck('phone');
           $myaddress = DB::connection('mysql2')->table("users")->
           where('email',$request->email)
             ->pluck('address');

             $myname = DB::connection('mysql2')->table("users")->
             where('email',$request->email)
               ->pluck('slug');



         log::info('elae me aaya');
         log::info($myemail);
         return response()->json(['status'=>true,
         'user'=>$mydata,'userid'=>$myids,'useremail'=>$myemail,'useraddress'=>$myaddress,'username'=>$myname,'userphone'=>$myphone
       ]);
      }
      else{
        $mysimage_trip= DB::connection('mysql2')->table("users")->where('phone',$myuserphone)->delete();
        $image_trip= DB::connection('mysql2')->table("users")->where('email',$request->email)->
        update(array('email' => $data['email'], 'name'  => $data['name'],'phone'  =>  $myuserphone,'address'  => $data['address'],'slug'  => $nameslug));


           log::info('$image_trip');
           log::info($image_trip);
           $mydata = DB::connection('mysql2')->table("users")->
           where('email',$request->email)
             ->get();
             log::info('$mydata');
 $myids = DB::connection('mysql2')->table("users")->
           where('email',$request->email)
             ->pluck('id');

             $myemail = DB::connection('mysql2')->table("users")->
             where('email',$request->email)
               ->pluck('email');
               log::info($myemail);
               $myphone = DB::connection('mysql2')->table("users")->
               where('email',$request->email)
                 ->pluck('phone');
               $myaddress = DB::connection('mysql2')->table("users")->
               where('email',$request->email)
                 ->pluck('address');

                 $myname = DB::connection('mysql2')->table("users")->
                 where('email',$request->email)
                   ->pluck('slug');



             log::info('elae me aaya');
             log::info($myemail);
             return response()->json(['status'=>true,
             'user'=>$mydata,'userid'=>$myids,'useremail'=>$myemail,'useraddress'=>$myaddress,'username'=>$myname,'userphone'=>$myphone
           ]);



      }
}
public function phone(Request $request)
    {
      $data = $request->all();
    log::info('inside here');
    log::info($request);
    $lowerph = strtolower($request->phone);
    $slugsph = substr($lowerph, 3);
    $my_email= DB::connection('mysql2')->table("users")->where('phone',$slugsph)->pluck('email');

    if($my_email->isEmpty() )
    {
      $lower = strtolower($request->name);
      $slugs = str_replace(" ", "-", $lower);
    $image_trip= DB::connection('mysql2')->table("users")->insert([
      ['phone' => $slugsph, 'avatar'  => $data['image'],'role_id'  => 3]

    ]);

      $my_trip= DB::connection('mysql2')->table("users")->where('phone',$slugsph)->first();
       $my_id=$my_trip->id;
       $myids = DB::connection('mysql2')->table("users")->
    where('phone',$slugsph)
      ->pluck('id');
      $mydata = DB::connection('mysql2')->table("users")->
      where('phone',$slugsph)
        ->get();
        $myphone = DB::connection('mysql2')->table("users")->
        where('phone',$slugsph)
          ->pluck('phone');
       log::info('$image_trip');
       log::info($image_trip);
       log::info($myids);
 return response()->json(['status'=>true,
       'user'=>$mydata,'userid'=>$myids,'userphone'=>$myphone
       ]);

    }
    else{
    $mydata = DB::connection('mysql2')->table("users")->
    where('phone',$slugsph)
      ->get();
      log::info('$mydata');

      log::info($mydata);
      $myids = DB::connection('mysql2')->table("users")->
    where('phone',$slugsph)
      ->pluck('id');

      $myemail = DB::connection('mysql2')->table("users")->
      where('phone',$slugsph)
        ->pluck('email');
        log::info($myemail);
        $myphone = DB::connection('mysql2')->table("users")->
        where('phone',$request->phone)
          ->pluck('phone');
        $myaddress = DB::connection('mysql2')->table("users")->
        where('phone',$slugsph)
          ->pluck('address');

          $myname = DB::connection('mysql2')->table("users")->
          where('phone',$slugsph)
            ->pluck('slug');
 return response()->json(['status'=>false,
      'user'=>$mydata,'userid'=>$myids,'useremail'=>$myemail,'useraddress'=>$myaddress,'username'=>$myname,'userphone'=>$myphone
    ]);
  }

}
public function updateticket(Request $request)
    {
      $data = $request->all();
    log::info('inside here');
    log::info($request);

    $myimage_event= DB::connection('mysql2')->table("events")->where('id',$request->event_id)->
        update(array('price_type' => 1));


        $image_trip= DB::connection('mysql2')->table("tickets")->where('id',$request->id)->
        update(array('title' => $data['title'], 'price'  => $data['price'],'quantity'  =>  $data['quantity'],'description'  => $data['description'],'event_id'  => $data['event_id']));


       log::info('$image_trip');
       log::info($image_trip);
      return response()->json([
        "ok" => true,
        "message" => "Request Successfully Added.",
    ]);
      return response()->json($response, 200);

}

public function updatelocation(Request $request)
 {
      $data = $request->all();
    log::info('inside here');
    log::info($request);
    $lower = strtolower($request->slug3);
    $country = str_replace(" ", "-", $lower);
    $lower_state = strtolower($request->state);
    $state = str_replace(" ", "-", $lower_state);
    $lower_city = strtolower($request->city);
    $city = str_replace(" ", "-", $lower_city);


    $image_trip= DB::connection('mysql2')->table("events")->where('event_id',$request->event_id)->
    update(array('venue' => $data['venue'], 'address'  => $data['address'],'latitude'  =>  $data['latitude'],'longitude'  => $data['longitude'],'city'  => $data['city'],'state'  => $data['state'],'zipcode'  => $data['zipcode'],'country_id'  => $data['country_id'],'slug3'  => $country,'slug2'  => $state,'slug1'  => $city));


       log::info('$image_trip');
       log::info($image_trip);
      return response()->json([
        "ok" => true,
        "message" => "Request Successfully Added.",
    ]);
      return response()->json($response, 200);

}

public function updatemedia(Request $request)
    {
      $data = $request->all();
    log::info('inside here');
if($request->hasfile('image_name'))
    {

$file= $request->file('image_name');
$name = $file->getClientOriginalName();
$imname = str_replace("image_picker", "", $name);

$image_resize = Image::make($file->getRealPath());
$image_resize->resize(512, 512);

$changepath=public_path();
$rep_path = str_ireplace("site", "events", $changepath);
$final_path = '/storage/'.'events/'.Carbon::now()->format('FY').'/';


$path=$image_resize->resize(512, 512, function ($constraint) {
$constraint->aspectRatio();
})->save($rep_path.$final_path.$imname);
log::info($path);

    }



    $thumbnail = 'events/'.Carbon::now()->format('FY').'/'.$imname;
    log::info($thumbnail);
    $myimg='events\/'.Carbon::now()->format('FY').'\/'.$imname;
    $imgs='["' . $myimg . '"]';
    $images=$imgs;

    log::info($images);

                         $image_trip= DB::connection('mysql2')->table("events")->where('event_id',$request->event_id)->
    update(array('thumbnail' => $thumbnail, 'images'  =>  $images));



               return response()->json([
                "ok" => true,
                "message" => "Request Successfully Added.",
            ]);

}

public function updatemedias(Request $request)
    {
      $data = $request->all();
    log::info('inside here');
    log::info($request);

    if($request->hasfile('image_name'))
    {

$file= $request->file('image_name');
$name = $file->getClientOriginalName();
$imname = str_replace("image_picker", "", $name);

$image_resize = Image::make($file->getRealPath());
$image_resize->resize(1920, 1080);

$changepath=public_path();
$rep_path = str_ireplace("site", "events", $changepath);
$final_path = '/storage/'.'events/'.Carbon::now()->format('FY').'/';
$path=$image_resize->resize(512, 512, function ($constraint) {
$constraint->aspectRatio();
})->save($rep_path.$final_path.$imname);


    }

    $myimg='events\/'.Carbon::now()->format('FY').'\/'.$imname;
    $imgs='["' . $myimg . '"]';
    $images=$imgs;


    $poster ='events/'.Carbon::now()->format('FY').'/'.$imname;
    $image_trip= DB::connection('mysql2')->table("events")->where('event_id',$request->event_id)->
    update(array('poster'  => $poster,'images'  =>  $images));


               return response()->json([
                "ok" => true,
                "message" => "Request Successfully Added.",
            ]);

}
public function imagedelete(Request $request)
    {




      $event_image=DB::table('addimages')->where("trip_id" , $request->trip_id)->where("image_name" , $request->image_name)->delete();
                        return response()->json([
        "ok" => true,
        "message" => "Request Successfully Added.",
    ]);
      return response()->json($response, 200);



}

public function city(Request $request)
    {

      $tripcity = City::where("city_state_id",$request->state_id)

      ->get();


      return response()->json($tripcity);



}

public function imageupdate(Request $request)
    {


      $id = $request->id;
      Log::info("here id");
      Log::info($id);
      $id = $request->id;
 Log::info($id);

      if($request->hasfile('image_name'))
        {
            $file= $request->file('image_name');
            $name = $file->getClientOriginalName();
            $imname = str_replace("image_picker", "", $name);
            $file->move(public_path().'/category/', $imname);
            $merge =  'https://www.holidaylandmark.com/category/ ' . $imname;
        }


          Log::info ('inside else empty');
          $image_trip= DB::table('addimages')->where('id', $request->id)
          ->update(['image_name' =>$imname,
         ]);
        return response()->json([
          "ok" => true,
          "message" => "Request Successfully Added.",
      ]);




}



public function eventtitle(Request $request)
    {
 $tripcity = Event::where("operator_email",$request->operator_email)->orderBy('id', 'desc')

      ->get();


      return response()->json($tripcity);



}
public function profilebyphone(Request $request)
    {

      $tripcity = DB::connection('mysql2')->table("users")->where("phone",$request->phone)->orderBy('id', 'desc')

      ->get();


      return response()->json($tripcity);



}
public function my_city_country(Request $request)
    {

      $tripcity = City::where("city_country_id",$request->state_id)

      ->get();


      return response()->json($tripcity);
                                            }
public function myeventcountry()
    {


      $products = Event::groupBy('slug3')->where('publish','1') ->orderBy('id', 'desc')
      ->get()
      ->toArray();
      return response()->json($products);



}
public function myalltripcont()
    {

      $gallery =addimage::groupBy('country')->where('Permision','Approve')
      ->where('publis',1)->orderBy('id', 'desc')->get();


                      log::info($gallery);
      return response()->json($gallery);



}
public function myalltripstate()
    {
      log::info('inside state aaraha hai');

                      $gallery =addimage::groupBy('state')->where('Permision','Approve')
                      ->where('publis',1)->orderBy('id', 'desc')->get();
               log::info($gallery);
      return response()->json($gallery);



}
public function myalltripcity()
    {

                      $gallery = addimage::groupBy('city')->where('Permision','Approve')
                      ->where('publis',1)->orderBy('id', 'desc')->get();
                      log::info('yeh hai city $gallery');
                      log::info($gallery);
      return response()->json($gallery);



}
public function event()
    {

      $products = Event::leftJoin('countries','events.country_id', '=', 'countries.id')->where('publish','1') ->orderBy('events.id', 'desc')
      ->get()
      ->toArray();


      return response()->json($products);


}
public function eventticket()
    {

      $products=  DB::connection('mysql2')->table("tickets")
      ->select('tickets.*')
                 ->orderBy('tickets.id', 'desc')
                      ->get()
                      ->toArray();

      return response()->json($products);



}
public function allsevents()
    {
      $products=  DB::connection('mysql2')->table("events")
      ->select('events.*')
                 ->orderBy('events.id', 'desc')
                      ->get()
                      ->toArray();

      return response()->json($products);



}


public function alleventscountry()
    {

 $tripcountry = DB::connection('mysql2')->table("countries")->orderBy('id', 'asc')
      ->get()
      ->toArray();


      return response()->json($tripcountry);



}
public function myeventstate()
    {

      $products = Event::groupBy('slug2')->where('publish','1') ->orderBy('id', 'desc')
      ->get()
      ->toArray();


      return response()->json($products);



}


public function myeventcity()
    {

      $products = Event::groupBy('slug1')->where('publish','1') ->orderBy('id', 'desc')
      ->get()
      ->toArray();
                   return response()->json($products);



}
public function eventcat()
    {

      $eventcat = Category::orderBy('categories.id', 'desc')
      ->get()
      ->toArray();


      return response()->json($eventcat);



}
public function tripcat()
    {



$tripcat = json_decode(strip_tags(TripCategory::orderBy('trip_categories.id', 'desc')->get()),true);
 return response()->json($tripcat);



}

public function eventcountry()
 {

      $tripcountry = Eventcountry::orderBy('country.id', 'asc')
      ->get()
      ->toArray();


      return response()->json($tripcountry);



}

public function touroperator()
    {

      $eventcat = Userevent::where('role_id','3')->orderBy('id', 'asc')
      ->get()
      ->toArray();


      return response()->json($eventcat);





}
public function getprofile(Request $request)
    {
  $tripstate = Userevent::where("email",$request->email)

      ->get();


      return response()->json($tripstate);




}
public function alluser()
    {

      $eventcat = Userevent::orderBy('id', 'asc')
      ->get()
      ->toArray();


      return response()->json($eventcat);





}
public function myuserdata()
    {

      $moreactivated= DB::table('rvsps')->groupby('emailid')->get()->toArray();

 return response()->json($moreactivated);





}

public function populartour()
    {

      $eventcat =DB::table('packages')
      ->leftJoin('trip_categories','packages.Category', '=', 'trip_categories.category')
      ->leftJoin('addimages','packages.TripTitle', '=', 'addimages.trips')
      ->leftJoin('iternaries','packages.TripTitle', '=', 'iternaries.trips')
   ->select('packages.*','trip_categories.id','trip_categories.category','addimages.image_name','iternaries.Days','iternaries.location','iternaries.explanation')
   ->groupBy('packages.TripTitle')
     ->where('Permission','Approve')
     ->where('publish',1)

                   ->orderBy('Subscriber','desc')
                       ->get()
                       ->toArray();

      return response()->json($eventcat);

}

public function myuser()
    {

      $myuser = DB::table('rvsps')->orderBy('id', 'asc')->groupby('emailid')
      ->get()
      ->toArray();

      return response()->json($myuser);

}
public function updatemytrip(Request $request)
    {
      Log::info($request);
      $id = $request->tour_id;
        $my= $request->country;
        Log::info("here  mydgid city");
        Log::info($my);
        $SCountry_ids=Package::where("tour_id" , $id)->first();
        $scont_id=$SCountry_ids->slug;
        Log::info($scont_id);
        $cont= $request->country;
         $stat =$request->state;
        $citi= $request->city;
        $trip_title=$request->TripTitle;
                                 $tourism_titles=$SCountry_ids->tour_id;
                      $getCountryName = Country::where('country_id', $request->country)->first();

        if (!empty($getCountryName))
         {
    Log::info('inside empty');

      $Countryname = $getCountryName->country_name;
      $Countriess =str_slug($Countryname);


      $getStateName = State::where('state_id', $request->state)->first();



      $Statename = $getStateName->state_name;
      $States =str_slug($Statename);
      $country_state=collect([$Countriess, $States])->implode('/');


      $getCityName = City::where('city_id', $request->city)->first();


      $cityname = $getCityName->city_name;
      $cities = str_slug($cityname);
      $country_state_city=collect([$Countriess, $States,$cities])->implode('/');




$getsCategory = $request->TripTitle;
Log::info($getsCategory);
$Category = str_slug($getsCategory);
$c_s_c_cat=collect([$Countriess, $States,$cities,$Category])->implode('/');
Log::info($c_s_c_cat);
$image_trip_update= DB::table('packages')->where('tour_id', $id)
->update(['c_s_c_cat' =>$c_s_c_cat ]);
}
        if($my==$scont_id)
        {

          $training = Package::find($request->tour_id);
          $id = $request->tour_id;
                    $training = Package::find($id);
                     log::info($trip_title);
                    $tour_title=$training->TripTitle;
                    log::info($trip_title);


                    $iternary_trip=DB::table('iternaries')->where('trips', $tour_title)
                    ->update(['trips' =>$trip_title ]);
                    log::info($iternary_trip);
                    $day=$request->NoOfDays;
            $nights=$request->night;
            $merge = $day . 'days/ ' . $nights.'night';

                        $image_trip= DB::table('addimages')->where('trips', $tour_title)
            ->update(['trips' =>$trip_title,'Description'=>$request->Description,'Overview'=>$request->Keyword,'Highlight'=>$request->Destination,
            'time'=>$request->time,'datetime'=>$request->datetime,
            'daynight'=> $merge,]);

         $training->Category = $request->Category;
               $training->TripTitle = $request->TripTitle;
               $training->NoOfDays = $request->NoOfDays;
 $training->night = $request->night;
                 $training->daynight = $merge ;
                $training->datetime = $request->datetime;
               $training->time = $request->time;
               $training->Destination = $request->Destination;
               $training->Keyword = $request->Keyword;
               $training->Description = $request->Description;
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
                      $id = $request->tour_id;

                    $iternary_trip=DB::table('iternaries')->where('tour_id', $tourism_titles)
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
$merge = $day . 'days/ ' . $nights.'night';

               $city_name=$citiess->city_name;

                            $image_trip= DB::table('addimages')->where('trip_id', $tourism_titles)
                    ->update(['trips' =>$trip_title,
                    'country'=>$cont_name,'state'=>$stat_name,'city'=>$city_name,'Description'=>$request->Description,'Overview'=>$request->Keyword,'Highlight'=>$request->Destination,
                    'time'=>$request->time,'datetime'=>$request->datetime,
                    'daynight'=> $merge,]);
                    log::info('yeh triptitle hai '.$request->TripTitle);

                    DB::table('packages')->where('tour_id', $request->tour_id)
                    ->update(['TripTitle' =>$request->TripTitle,'Category'=>$request->Category,'slug'=>$cont_name,'slug2'=>$city_name,'slug1'=>$stat_name,
                    'country'=>$request->country,'state'=>$request->state,'city'=>$request->city,'Description'=>$request->Description,'Keyword'=>$request->Keyword,'Destination'=>$request->Destination,
                    'NoOfDays'=>$request->NoOfDays,'night'=>$request->night,'time'=>$request->time,'datetime'=>$request->datetime,
                    'country_state'=> $country_state, 'country_state_city'=> $country_state_city, 'c_s_c_cat'=> $c_s_c_cat,'daynight'=> $merge,]);



              return response()->json([
                "ok" => true,
                "message" => "Request Successfully Added.",
            ]);
               Log::info('response chala gya');
            }
}public function eventstate(Request $request)
    {


      $tripstate = Eventstate::where("state_country_id",$request->country_id)

      ->get();


      return response()->json($tripstate);




}
public function eventcity(Request $request)
    {



      $tripcity = Eventcity::where("city_state_id ",$request->state_id)

      ->get();


      return response()->json($tripcity);



}
}










                 

