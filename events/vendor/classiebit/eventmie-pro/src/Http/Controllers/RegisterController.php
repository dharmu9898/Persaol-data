<?php

namespace Classiebit\Eventmie\Http\Controllers\Auth;
use Facades\Classiebit\Eventmie\Eventmie;


use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Log;
use Illuminate\Support\Facades\URL;
use Classiebit\Eventmie\Models\User;
use Classiebit\Eventmie\Notifications\MailNotification;
use Socialite;
use Illuminate\Http\Request;
use App\SocialProvider;
use Classiebit\Eventmie\Mail\SendMail;
use Classiebit\Eventmie\Models\verifycoursetoken;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use GrahamCampbell\ResultType\Success;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
         // language change
        $this->middleware('common');
        $this->middleware('guest');
        $this->redirectTo = !empty(config('eventmie.route.prefix')) ? config('eventmie.route.prefix') : '/';
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    
    {  
     
        log::info('yahan pahucha');
       
        return Eventmie::view('eventmie::auth.register');
    }

    public function showRegistrationUserForm()
    {   
        
        return Eventmie::view('eventmie::auth.register');
    }

    public function showProfileForm($name,$activate,$validToken)
    { 
       
       
        $email=$activate;
        log::info('yeh name hai');
        log::info($email);
    $validToken = verifycoursetoken::where('token', '=',$validToken)->first();

    if($validToken){
        return Eventmie::view('eventmie::auth.register_activate',compact('email','name'));
    }
     

    }
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
           
        ]);
    }
    protected function submitregisteration(Request $request)
    {
        $data = $request->all();
        log::info('yeh data hai');
        log::info($data);
        $slug=$data['name'];
        $mfilter = str_replace("","%",$slug);
        $user   = User::create([
            'name'      => $data['name'],
            'slug'      =>  $mfilter,
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
            'role_id'  => 3,
        ]);
        $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson()
                    ? new JsonResponse([], 201)
                    : redirect($this->redirectPath());
                
    }

    protected function submitprofile(Request $request)
    {
        $data = $request->all();
        log::info('yeh data hai');
        log::info($data);
        $slug=$data['name'];
        $mfilter = str_replace("","%",$slug);
        $user_email = User::where('email', '=',  $data['email'])->first();
       
        if($user_email){
            log::info('inside $user_email if');
            $phone=$data['phone'];
            $msg= 'this email(Touroperator) already exist use any other email';
            return Eventmie::view('eventmie::auth.updates_profile',compact('phone','msg')); 
          
       } 
       else{

        log::info('inside $user_email else');
        $user = User::updateOrCreate(
            ['phone' => $data['phone']],
            ['name'      => $data['name'],
            'slug'      =>  $mfilter,
            'email'     => $data['email'],
            'phone'     => $data['phone'],
            'password'  => Hash::make($data['password']),
            'role_id'  => 3,]
      
        );
       
        $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson()
                    ? new JsonResponse([], 201)
                    : redirect($this->redirectPath());
                }          
    }
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
       
        log::info('here is create');
        $url = URL::current();
        $newstring = substr($url, 39, 47);
        log::info($newstring);
        $OperatorMailsend = array(  
                    'name'      => $data['name'],
                    'email'     => $data['email'],
            );

            
                  $validToken=sha1(time()).rand();
                 $verifycoursetoken = new verifycoursetoken();
                 $verifycoursetoken->token =  $validToken;
                  $verifycoursetoken->save();
                Mail::to($OperatorMailsend['email'])->send(new SendMail($OperatorMailsend,$validToken));
                log::info('after mail');
                
    }

    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }
    
    // add handleProviderCallback() function in RegisterController
    
    public function handleProviderCallback()
        {
            log::info('inside handleProviderCallback');
            try
            {
                $socialUser = Socialite::driver('google')->user();
            }
            catch(\Exception $e)
            {
                return redirect('/');
            }
            //check if we have logged provider
            $socialProvider = SocialProvider::where('provider_id',$socialUser->getId())->first();
            log::info($socialProvider);
            if(!$socialProvider)
            {
                log::info('inside if handleProviderCallback');
                //create a new user and provider
                $user = User::firstOrCreate(
                    ['email' => $socialUser->getEmail()],
                    ['role_id' => 3],
                     ['name' => $socialUser->getName()]
                );
    
                $user->socialProviders()->create(
                    ['provider_id' => $socialUser->getId(), 'provider' => 'google']
                );
    
            }
            else
                $user = $socialProvider->user;
    
            auth()->login($user);
    
            return redirect('/home');
    
        }
    
}
