<?php

namespace Classiebit\Eventmie\Http\Controllers;
use App\Http\Controllers\Controller; 
use Facades\Classiebit\Eventmie\Eventmie;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Auth;
use Redirect;
use File;
use Log;
use GuzzleHttp\Client as Client;
use Config;
use Validator;
use Illuminate\Validation\Rule;
use Exception;


class TourDetailController extends Controller
{
    private static function getCategoryAccessToken() {
        Log::info('In TourDetailController->getCategoryAccessToken()');
        try {
            Log::info('HOLIDAY_M_BASE_URL:'. Config::get('app.HOLIDAY_M_BASE_URL'));
            Log::info('HOLIDAY_M_CLIENT_ID: ' . Config::get('app.HOLIDAY_M_CLIENT_ID'));
            Log::info('HOLIDAY_M_CLIENT_ID: ' . Config::get('app.HOLIDAY_M_CLIENT_ID'));
            Log::info('HOLIDAY_M_CLIENT_SECRET: ' . Config::get('app.HOLIDAY_M_CLIENT_SECRET'));
            Log::info('Getting the token!');
            $http = new Client(); //GuzzleHttp\Client
            Log::info('after client the token!');
            Log::info('before  post client the token!');
            $response = $http->post(
                Config::get('app.HOLIDAY_M_BASE_URL') . Config::get('app.HOLIDAY_M_OAUTH_TOKEN_URL'),
                [
                    'form_params' => [
                        'grant_type' => Config::get('app.HOLIDAY_M_GRANT_TYPE'),
                        'client_id' => Config::get('app.HOLIDAY_M_CLIENT_ID'),
                        'client_secret' => Config::get('app.HOLIDAY_M_CLIENT_SECRET'),
                        'redirect_uri' => '',
                    ],
                ]
            );
            Log::info('after  post client the token!');
            $array = $response->getBody()->getContents();
            $json = json_decode($array, true);
            $collection = collect($json);
            $access_token = $collection->get('access_token');
            Log::info('Got the token!');
            return $access_token;
        } catch (RequestException $e) {
            Log::info('There is some exception in TourDetailController->getCategoryAccessToken()');
            return $e->getResponse()->getStatusCode() . ': ' . $e->getMessage();
        }
    }

    // =============================================================
    public function gettrip(Request $request, $email)
    { 
               $page = $request->get('page');
        $query = $request->get('query');
               try {
            $access_token = $this->getCategoryAccessToken();
            $url = ''
                . Config::get('app.HOLIDAY_M_BASE_URL')
                . Config::get('app.GET_MYHOLIDAYTRIP_URL')
                .'/'
                . $email
                . '?page='
                . $page
                . '&query='
                . $query;
             $guzzleClient = new Client(); //GuzzleHttp\Client
            $params = [
                'headers' => [
                    'Accept'     => 'application/json',
                    'Authorization' => 'Bearer ' . $access_token
                ],

            ];
            $response = $guzzleClient->request('GET', $url, $params);
           $json = json_decode($response->getBody()->getContents(), true);
            return $json;
        } catch (\Exception $e) {
            return $e->getResponse()->getStatusCode() . ': ' . $e->getMessage();
        }
        
    }
    public function storetrip(Request $request)
    {
        Log::info('In storetrip->storetrip()');
        $input = $request->all();
        Log::info($request);
    
        $validator = Validator::make($input, [
            'title' => 'required',
            'category_id' => 'required|string|max:255',
            'excerpt' => 'required',
            'description' => 'required',
            'faq' => 'required',
                    
        ]);
    
        if ($validator->fails()) {
            Log::info('if  job condition fails');
            return $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
        }
        Log::info('
        ');
        try {
            Log::info('Validating given Training data...');
    
            $validatorResponse = $this->validateNewDetailData($request);
    
            Log::info('$validatorResponse[success]: ' . $validatorResponse['success']);
    
            if ($validatorResponse['success']) {
    
                Log::info('Calling TourDetailController::getCategoryAccessToken()');
                $access_token = $this->getCategoryAccessToken();
                Log::info('Got the access token from TourDetailController::getCategoryAccessToken(). Now creating Class!');
                Log::info('HOLIDAY_M_BASE_URL . ADD_MYHOLIDAYDETAIL_URL: ' . Config::get('app.HOLIDAY_M_BASE_URL') . Config::get('app.ADD_MYHOLIDAYDETAIL_URL'));
              
          
           
                $http = new Client(); //GuzzleHttp\Client
                log::info("before param");
                $response = $http->post(
                    Config::get('app.HOLIDAY_M_BASE_URL') . Config::get('app.ADD_MYHOLIDAYDETAIL_URL'),
                    [
                        'headers' => [
                            'Accept'     => 'application/json',
                            'Authorization' => 'Bearer ' . $access_token
                        ],
                        'form_params' => [
                            'event_id' => $request->event_id,
                            'user_id' => $request->user_id,
                            'category_id' => $request->category_id,
                            'title' => $request->title,
                            'excerpt' => $request->excerpt,
                            'description' => $request->description,
                            'faq' => $request->faq,
                            'flight_event' => $request->flight_event,
                            'hotel_event' => $request->hotel_event,
                            'transfer_event' => $request->transfer_event,
                            'activity_event' => $request->activity_event,

                        ]
    
                    ]
                );
                log::info("after param");
                Log::info('Got the response from create Training!');
                $responseJson = json_decode($response->getBody()->getContents(), true);
    
                return response()->json($responseJson, 200);
            } else {
                return response()->json($responseJson, 422);
            }
        } catch (\Exception $e) {
            Log::info('There was some exception in TourDetailController->addsiternary()');
            Log::info($e->getMessage());
            $response = [
                'success' => false,
                'data' => '',
                'message' => $e->getMessage()
            ];
            return response()->json($response, 500);
        }
    }

    public function storetrip_ticket(Request $request) {
        Log::info('In store trip storetrip_iternary');
        $input = $request->all();
        Log::info($request);
      
        $validator = Validator::make($input, [
            'title_id' => 'required',
            'cost' => 'required',          
        ]);
        if ($validator->fails()) {
            log::info('yanha validation  aata');
            return $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
        }
        try {
           
            Log::info('Validating given Bid data...');
            
                $validatorResponse = $this->validateNewticketData($request);
            
            Log::info('$validatorResponse[success]: ' . $validatorResponse['success']);
            Log::info('$validatorResponse[message]: ' . $validatorResponse['message']);
            if ($validatorResponse['success']) {
                
                Log::info('Calling QuoteController::getQuoteMgmntAccessToken()');
                $access_token = $this->getCategoryAccessToken();
                Log::info('Got the access token from QuoteController::getQuoteMgmntAccessToken(). Now creating Class!');

                Log::info('HOLIDAY_M_BASE_URL . ADD_MYHOLIDAYTICKET_URL: ' . Config::get('app.HOLIDAY_M_BASE_URL') . Config::get('app.ADD_MYHOLIDAYTICKET_URL'));
              
                $http = new Client(); //GuzzleHttp\Client
                $response = $http->post(
                    Config::get('app.HOLIDAY_M_BASE_URL') . Config::get('app.ADD_MYHOLIDAYTICKET_URL'),
                    [
                        'headers' => [
                            'Accept'     => 'application/json',
                            'Authorization' => 'Bearer ' . $access_token
                        ],
                        'form_params' => [
                            'price' => $request->cost,
                            'title' => $request->title_id,
                            'discount_id' => $request->discount_id,
                            'discountweek_id' => $request->discountweek_id,
                            'seconddiscount_id' => $request->seconddiscount_id,
                            'selectdiscountweek_id' => $request->selectdiscountweek_id,
                            'thirddiscount_id' => $request->thirddiscount_id,
                            'seconddiscountweek_id' => $request->seconddiscountweek_id,
                            'currency' => $request->currency_id,                  
                            'trip_id' => $request->event_id,
                           
                            
                        ]
                    ]
                );
                
                Log::info('Got the response from create Training!');
                $responseJson = json_decode($response->getBody()->getContents(), true);
                
                return response()->json($responseJson, 200);
            } else {
                return response()->json($responseJson, 422);
            }
           
        } catch (\Exception $e) {
            Log::info('There was some exception in MyClassController->createClass()');
            Log::info($e->getMessage());
            $response = [
                'success' => false,
                'data' => '' ,
                'message' => $e->getMessage()
            ];
        
            return response()->json($response, 500);
        }
    }

    private function validateNewticketData(Request $request) {
       
        $input = $request->all();
        $validator = Validator::make($input, [
           
            'title_id' => 'required',
            'cost' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return $response = [
                'success' => false,
                'message' => 'Class already exits.'
            ];
        } else {
            return $response = [
                'success' => true,
                'message' => 'Class data is ok to store.'
            ];
        }
    }
    public function iternary_delete(Request $request) {
        Log::info('In store trip storetrip_iternary');
        $input = $request->all();
        Log::info($request);
      
        $validator = Validator::make($input, [
           
           
            
        ]);
        if ($validator->fails()) {
            return $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
        }
        try {
           
            Log::info('Validating given Bid data...');
            
                $validatorResponse = $this->validateNewIternarydeleteData($request);
            
            Log::info('$validatorResponse[success]: ' . $validatorResponse['success']);
            Log::info('$validatorResponse[message]: ' . $validatorResponse['message']);
            if ($validatorResponse['success']) {
                
                Log::info('Calling QuoteController::getQuoteMgmntAccessToken()');
                $access_token = $this->getCategoryAccessToken();
                Log::info('Got the access token from QuoteController::getQuoteMgmntAccessToken(). Now creating Class!');

                Log::info('HOLIDAY_M_BASE_URL . DELETE_MYHOLIDAYITERNARYDEL_URL: ' . Config::get('app.HOLIDAY_M_BASE_URL') . Config::get('app.DELETE_MYHOLIDAYITERNARYDEL_URL'));
              
                $http = new Client(); //GuzzleHttp\Client
                $response = $http->post(
                    Config::get('app.HOLIDAY_M_BASE_URL') . Config::get('app.DELETE_MYHOLIDAYITERNARYDEL_URL'),
                    [
                        'headers' => [
                            'Accept'     => 'application/json',
                            'Authorization' => 'Bearer ' . $access_token
                        ],
                        'form_params' => [
                            
                            'trip_id' => $request->event_id,
                            'id' => $request->ticket_id,
                            
                        ]
                    ]
                );
                
                Log::info('Got the response from create Training!');
                $responseJson = json_decode($response->getBody()->getContents(), true);
                
                return response()->json($responseJson, 200);
            } else {
                return response()->json($responseJson, 422);
            }
           
        } catch (\Exception $e) {
            Log::info('There was some exception in MyClassController->createClass()');
            Log::info($e->getMessage());
            $response = [
                'success' => false,
                'data' => '' ,
                'message' => $e->getMessage()
            ];
        
            return response()->json($response, 500);
        }
    }
    public function tripticket_delete(Request $request) {
        Log::info('In store trip storetrip_iternary');
        $input = $request->all();
        Log::info($request);
      
        $validator = Validator::make($input, [
           
           
            
        ]);
        if ($validator->fails()) {
            return $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
        }
        try {
           
            Log::info('Validating given Bid data...');
            
                $validatorResponse = $this->validateNewTicketdeleteData($request);
            
            Log::info('$validatorResponse[success]: ' . $validatorResponse['success']);
            Log::info('$validatorResponse[message]: ' . $validatorResponse['message']);
            if ($validatorResponse['success']) {
                
                Log::info('Calling QuoteController::getQuoteMgmntAccessToken()');
                $access_token = $this->getCategoryAccessToken();
                Log::info('Got the access token from QuoteController::getQuoteMgmntAccessToken(). Now creating Class!');

                Log::info('HOLIDAY_M_BASE_URL . DELETE_MYHOLIDAYTICKETDEL_URL: ' . Config::get('app.HOLIDAY_M_BASE_URL') . Config::get('app.DELETE_MYHOLIDAYTICKETDEL_URL'));
              
                $http = new Client(); //GuzzleHttp\Client
                $response = $http->post(
                    Config::get('app.HOLIDAY_M_BASE_URL') . Config::get('app.DELETE_MYHOLIDAYTICKETDEL_URL'),
                    [
                        'headers' => [
                            'Accept'     => 'application/json',
                            'Authorization' => 'Bearer ' . $access_token
                        ],
                        'form_params' => [
                            
                            'event_id' => $request->event_id,
                            'ticket_id' => $request->ticket_id,
                            'id' => $request->ticket_id,
                            
                        ]
                    ]
                );
                
                Log::info('Got the response from create Training!');
                $responseJson = json_decode($response->getBody()->getContents(), true);
                
                return response()->json($responseJson, 200);
            } else {
                return response()->json($responseJson, 422);
            }
           
        } catch (\Exception $e) {
            Log::info('There was some exception in MyClassController->createClass()');
            Log::info($e->getMessage());
            $response = [
                'success' => false,
                'data' => '' ,
                'message' => $e->getMessage()
            ];
        
            return response()->json($response, 500);
        }
    }

    private function validateNewTicketdeleteData(Request $request) {
       
        $input = $request->all();
        $validator = Validator::make($input, [
           
           
        ]);

        if ($validator->fails()) {
            return $response = [
                'success' => false,
                'message' => 'Class already exits.'
            ];
        } else {
            return $response = [
                'success' => true,
                'message' => 'Class data is ok to store.'
            ];
        }
    }
    
    public function storetrip_time(Request $request) {
        Log::info('In store trip timing');
        $input = $request->all();
        Log::info($request);
        Log::info($request->repetitive_type);
        Log::info($request->repetitive_days);
        Log::info($request->repetitive_dates);
        Log::info($request->from_time);
        Log::info($request->to_time);
        $validator = Validator::make($input, [
            'start_date' => 'required|string|max:255',
            'end_date' => 'required|string|max:255',
            'start_time' => 'required|string|max:255',
            'end_time' => 'required',
        ]);
        if ($validator->fails()) {
            return $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
        }
        try {
            $date = Carbon::today()->toDateString();
            log::info('aaj ka $date');
            log::info($date);
            if($date>$request->start_date)
            {
                log::info('inside if');
                return response()->json(['status' => false]);

            }
            Log::info('Validating given Bid data...');
            
                $validatorResponse = $this->validateNewTimingData($request);
            
            Log::info('$validatorResponse[success]: ' . $validatorResponse['success']);
            Log::info('$validatorResponse[message]: ' . $validatorResponse['message']);
            if ($validatorResponse['success']) {
                
                Log::info('Calling QuoteController::getQuoteMgmntAccessToken()');
                $access_token = $this->getCategoryAccessToken();
                Log::info('Got the access token from QuoteController::getQuoteMgmntAccessToken(). Now creating Class!');

                Log::info('HOLIDAY_M_BASE_URL . ADD_MYHOLIDAYTIMING_URL: ' . Config::get('app.HOLIDAY_M_BASE_URL') . Config::get('app.ADD_MYHOLIDAYTIMING_URL'));
              
                $http = new Client(); //GuzzleHttp\Client
                $response = $http->post(
                    Config::get('app.HOLIDAY_M_BASE_URL') . Config::get('app.ADD_MYHOLIDAYTIMING_URL'),
                    [
                        'headers' => [
                            'Accept'     => 'application/json',
                            'Authorization' => 'Bearer ' . $access_token
                        ],
                        'form_params' => [
                            'start_date' => $request->start_date,
                            'end_date' => $request->end_date,
                            'merge_schedule' => $request->merge_schedule,
                           
                            'start_time' => $request->start_time,
                            'end_time' => $request->end_time,
                            'event_id' => $request->event_id,
                            'from_time' => $request->from_time,
                            'repetitive' => $request->repetitive,
                            'repetitive_type' => $request->repetitive_type,
                            'repetitive_days' => $request->repetitive_days,
                            'repetitive_dates' => $request->repetitive_dates,
                            'to_time' => $request->to_time,
                            'schedule_ids' => $request->schedule_ids,
                           

                            
                           
                        ]
                    ]
                );
                
                Log::info('Got the response from create Training!');
                $responseJson = json_decode($response->getBody()->getContents(), true);
                
                return response()->json($responseJson, 200);
            } else {
                return response()->json($responseJson, 422);
            }
           
        } catch (\Exception $e) {
            Log::info('There was some exception in MyClassController->createClass()');
            Log::info($e->getMessage());
            $response = [
                'success' => false,
                'data' => '' ,
                'message' => $e->getMessage()
            ];
        
            return response()->json($response, 500);
        }
    }

    private function validateNewTimingData(Request $request) {
       
        $input = $request->all();
        $validator = Validator::make($input, [
            'start_date' => 'required|string|max:255',
            'end_date' => 'required|string|max:255',
            'start_time' => 'required|string|max:255',
            'end_time' => 'required',
            
        ]);

        if ($validator->fails()) {
            return $response = [
                'success' => false,
                'message' => 'Class already exits.'
            ];
        } else {
            return $response = [
                'success' => true,
                'message' => 'Class data is ok to store.'
            ];
        }
    }
    private function validateNewLocationData(Request $request) {
       
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
            return $response = [
                'success' => false,
                'message' => 'Class already exits.'
            ];
        } else {
            return $response = [
                'success' => true,
                'message' => 'Class data is ok to store.'
            ];
        }
    }
    private function validateNewIternarydayData(Request $request) {
       
        $input = $request->all();
        $validator = Validator::make($input, [
           
            'price' => 'required|string|max:255',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return $response = [
                'success' => false,
                'message' => 'Class already exits.'
            ];
        } else {
            return $response = [
                'success' => true,
                'message' => 'Class data is ok to store.'
            ];
        }
    }

    private function validateNewIternarydeleteData(Request $request) {
       
        $input = $request->all();
        $validator = Validator::make($input, [
           
           
        ]);

        if ($validator->fails()) {
            return $response = [
                'success' => false,
                'message' => 'Class already exits.'
            ];
        } else {
            return $response = [
                'success' => true,
                'message' => 'Class data is ok to store.'
            ];
        }
    }
    

    private function validateNewDetailData(Request $request) {
       
        $input = $request->all();
        $validator = Validator::make($input, [
            'title' => 'required|string|max:255',
            'category_id' => 'required|string|max:255',
            'excerpt' => 'required|string|max:512',
            'description' => 'required|string|max:1012',
            'faq' => 'required|string|max:1012',
            
        ]);

        if ($validator->fails()) {
            return $response = [
                'success' => false,
                'message' => 'Class already exits.'
            ];
        } else {
            return $response = [
                'success' => true,
                'message' => 'Class data is ok to store.'
            ];
        }
    }

    public function storetrip_iternary(Request $request) {
        Log::info('In store trip storetrip_iternary');
        $input = $request->all();
        Log::info($request);
      
        $validator = Validator::make($input, [
           
            'price' => 'required|string|max:255',
            'description' => 'required',
            
        ]);
        if ($validator->fails()) {
            return $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
        }
        try {
           
            Log::info('Validating given Bid data...');
            
                $validatorResponse = $this->validateNewIternarydayData($request);
            
            Log::info('$validatorResponse[success]: ' . $validatorResponse['success']);
            Log::info('$validatorResponse[message]: ' . $validatorResponse['message']);
            if ($validatorResponse['success']) {
                
                Log::info('Calling QuoteController::getQuoteMgmntAccessToken()');
                $access_token = $this->getCategoryAccessToken();
                Log::info('Got the access token from QuoteController::getQuoteMgmntAccessToken(). Now creating Class!');

                Log::info('HOLIDAY_M_BASE_URL . ADD_MYHOLIDAYITERNARYDAYS_URL: ' . Config::get('app.HOLIDAY_M_BASE_URL') . Config::get('app.ADD_MYHOLIDAYITERNARYDAYS_URL'));
              
                $http = new Client(); //GuzzleHttp\Client
                $response = $http->post(
                    Config::get('app.HOLIDAY_M_BASE_URL') . Config::get('app.ADD_MYHOLIDAYITERNARYDAYS_URL'),
                    [
                        'headers' => [
                            'Accept'     => 'application/json',
                            'Authorization' => 'Bearer ' . $access_token
                        ],
                        'form_params' => [
                            'days' => $request->taxes_ids,
                            'location' => $request->price,
                          
                            'description' => $request->description,
                            'trip_id' => $request->event_id,
                            'id' => $request->ticket_id,
                            
                        ]
                    ]
                );
                
                Log::info('Got the response from create Training!');
                $responseJson = json_decode($response->getBody()->getContents(), true);
                
                return response()->json($responseJson, 200);
            } else {
                return response()->json($responseJson, 422);
            }
           
        } catch (\Exception $e) {
            Log::info('There was some exception in MyClassController->createClass()');
            Log::info($e->getMessage());
            $response = [
                'success' => false,
                'data' => '' ,
                'message' => $e->getMessage()
            ];
        
            return response()->json($response, 500);
        }
    }
    public function storetrip_location(Request $request) {
        Log::info('In store trip timing');
        $input = $request->all();
        Log::info($request);
      
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
            return $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
        }
        try {
           
            Log::info('Validating given Bid data...');
            
                $validatorResponse = $this->validateNewLocationData($request);
            
            Log::info('$validatorResponse[success]: ' . $validatorResponse['success']);
            Log::info('$validatorResponse[message]: ' . $validatorResponse['message']);
            if ($validatorResponse['success']) {
                
                Log::info('Calling QuoteController::getQuoteMgmntAccessToken()');
                $access_token = $this->getCategoryAccessToken();
                Log::info('Got the access token from QuoteController::getQuoteMgmntAccessToken(). Now creating Class!');

                Log::info('HOLIDAY_M_BASE_URL . ADD_MYHOLIDAYLOCATION_URL: ' . Config::get('app.HOLIDAY_M_BASE_URL') . Config::get('app.ADD_MYHOLIDAYLOCATION_URL'));
              
                $http = new Client(); //GuzzleHttp\Client
                $response = $http->post(
                    Config::get('app.HOLIDAY_M_BASE_URL') . Config::get('app.ADD_MYHOLIDAYLOCATION_URL'),
                    [
                        'headers' => [
                            'Accept'     => 'application/json',
                            'Authorization' => 'Bearer ' . $access_token
                        ],
                        'form_params' => [
                            'venue' => $request->venue,
                            'latitude' => $request->latitude,
                            'longitude' => $request->longitude,
                           
                            'address' => $request->address,
                            'zipcode' => $request->zipcode,
                            'event_id' => $request->event_id,
                            'country_id' => $request->country_id,
                            'state_id' => $request->state_id,
                            'city_id' => $request->city_id,
                            
                        ]
                    ]
                );
                
                Log::info('Got the response from create Training!');
                $responseJson = json_decode($response->getBody()->getContents(), true);
                
                return response()->json($responseJson, 200);
            } else {
                return response()->json($responseJson, 422);
            }
           
        } catch (\Exception $e) {
            Log::info('There was some exception in MyClassController->createClass()');
            Log::info($e->getMessage());
            $response = [
                'success' => false,
                'data' => '' ,
                'message' => $e->getMessage()
            ];
        
            return response()->json($response, 500);
        }
    }
    public function getusers(Request $request, $email)
    { 
               $page = $request->get('page');
        $query = $request->get('query');
               try {
            $access_token = $this->getCategoryAccessToken();
            $url = ''
                . Config::get('app.HOLIDAY_M_BASE_URL')
                . Config::get('app.GET_MYHOLIDAYUSERS_URL')
                .'/'
                . $email
                . '?page='
                . $page
                . '&query='
                . $query;
             $guzzleClient = new Client(); //GuzzleHttp\Client
            $params = [
                'headers' => [
                    'Accept'     => 'application/json',
                    'Authorization' => 'Bearer ' . $access_token
                ],

            ];
            $response = $guzzleClient->request('GET', $url, $params);
           $json = json_decode($response->getBody()->getContents(), true);
            return $json;
        } catch (\Exception $e) {
            return $e->getResponse()->getStatusCode() . ': ' . $e->getMessage();
        }
        
    }
    public function getlist(Request $request, $id)
    { 
               $page = $request->get('page');
        $query = $request->get('query');
               try {
            $access_token = $this->getCategoryAccessToken();
            $url = ''
                . Config::get('app.HOLIDAY_M_BASE_URL')
                . Config::get('app.GET_MYHOLIDAYLIST_URL')
                .'/'
                . $id
                . '?page='
                . $page
                . '&query='
                . $query;
             $guzzleClient = new Client(); //GuzzleHttp\Client
            $params = [
                'headers' => [
                    'Accept'     => 'application/json',
                    'Authorization' => 'Bearer ' . $access_token
                ],

            ];
            $response = $guzzleClient->request('GET', $url, $params);
           $json = json_decode($response->getBody()->getContents(), true);
            return $json;
        } catch (\Exception $e) {
            return $e->getResponse()->getStatusCode() . ': ' . $e->getMessage();
        }
        
    }

    
    public function getiternary(Request $request, $email)
    { 
        $page = $request->get('page');
        $query = $request->get('query');
        Log::info($page);
        try {
            $access_token = $this->getCategoryAccessToken();
            $url = ''
                . Config::get('app.HOLIDAY_M_BASE_URL')
                . Config::get('app.GET_MYHOLIDAYITERNARY_URL')
                .'/'
                . $email
                . '?page='
                . $page
                . '&query='
                . $query;
                $guzzleClient = new Client(); 
            $params = [
                'headers' => [
                    'Accept'     => 'application/json',
                    'Authorization' => 'Bearer ' . $access_token
                ],

            ];
            $response = $guzzleClient->request('GET', $url, $params);
                $json = json_decode($response->getBody()->getContents(), true);
                   return $json;
        } catch (\Exception $e) {
            Log::info('There was some exception in TourDetailController->getCategoryAccessToken()');
            return $e->getResponse()->getStatusCode() . ': ' . $e->getMessage();
        }
      
    }

    public function addcategories(Request $request)
    {
        Log::info('In Myaddsiternary->addcategories()');
        $input = $request->all();
        Log::info($request);
        $validator = Validator::make($input, [
            'category_name' => 'required|string|max:255',
            'catdescription' => 'required',
        ]);
        if ($validator->fails()) {
            Log::info('if  job condition fails');
            return $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
        }
        try {
            Log::info('Validating given Training data...');
            $validatorResponse = $this->validateNewToolData($request);
            Log::info('$validatorResponse[success]: ' . $validatorResponse['success']);
            if ($validatorResponse['success']) {
                Log::info('Calling TourDetailController::getCategoryAccessToken()');
                $access_token = $this->getCategoryAccessToken();
                Log::info('Got the access token from TourDetailController::getCategoryAccessToken(). Now creating Class!');
                Log::info('HOLIDAY_M_BASE_URL . ADD_MYHOLIDAYCATEGORY_URL: ' . Config::get('app.HOLIDAY_M_BASE_URL') . Config::get('app.ADD_MYHOLIDAYCATEGORY_URL'));
                $multipartArray = [];
                $category_logo = null;
                if (isset($input['category_logo'])) {
                    $category_logo = $input['category_logo'];
                }
                $multipartArray[] = [
                    'name'     => 'category_name',
                    'contents' => $input['category_name']
                ];
                array_push($multipartArray, [
                    'name' => 'catdescription',
                    'contents' => $input['catdescription']
                ]);
             array_push($multipartArray, [
                'name'     => 'operator_auth_id',
                'contents' => $input['operator_auth_id']
            ]);
            array_push($multipartArray, [
                'name'     => 'operator_auth_name',
                'contents' => $input['operator_auth_name']
            ]);
            array_push($multipartArray, [
                'name'     => 'operator_auth_email',
                'contents' => $input['operator_auth_email']
            ]);
            array_push($multipartArray, [
                'name'     => 'category_logo',
                'contents' => file_get_contents($category_logo),
                'filename' => $category_logo->getClientOriginalName()
            ]);
                $http = new Client(); //GuzzleHttp\Client
                log::info("before param");
                $response = $http->post(
                    Config::get('app.HOLIDAY_M_BASE_URL') . Config::get('app.ADD_MYHOLIDAYCATEGORY_URL'),
                    [
                        'headers' => [
                            'Accept'     => 'application/json',
                            'Authorization' => 'Bearer ' . $access_token
                        ],
                        'multipart' => $multipartArray,
                    ]
                );
                log::info("after param");
                Log::info('Got the response from create Training!');
                $responseJson = json_decode($response->getBody()->getContents(), true);
                return response()->json($responseJson, 200);
            } else {
                return response()->json($responseJson, 422);
            }
        } catch (\Exception $e) {
            Log::info('There was some exception in TourDetailController->addcategories()');
            Log::info($e->getMessage());
            $response = [
                'success' => false,
                'data' => '',
                'message' => $e->getMessage()
            ];
            return response()->json($response, 500);
        }
    }
    private function validateNewToolData(Request $request) {
        $input = $request->all();
        $validator = Validator::make($input, [
            'category_name' => 'required|string|max:255',
            'catdescription' => 'required',
        ]);
        if ($validator->fails()) {
            return $response = [
                'success' => false,
                'message' => 'Tool already exits.'
            ];
        } else {
            return $response = [
                'success' => true,
                'message' => 'Tool data is ok to store.'
            ];
        }
    }
    public function getimage(Request $request, $email)
    { 
        Log::info('In TourDetailController->getimage()');
        Log::info($email);
        $page = $request->get('page');
        $query = $request->get('query');
        Log::info($page);
        try {
            $access_token = $this->getCategoryAccessToken();
            $url = ''
                . Config::get('app.HOLIDAY_M_BASE_URL')
                . Config::get('app.GET_MYHOLIDAYIMAGE_URL')
                .'/'
                . $email
                . '?page='
                . $page
                . '&query='
                . $query;
            Log::info('Got the access token from TourDetailController::getCategoryAccessToken(). Now fetching Commands!');
            Log::info('Command Url: ' . $url);
            $guzzleClient = new Client(); //GuzzleHttp\Client
            $params = [
                'headers' => [
                    'Accept'     => 'application/json',
                    'Authorization' => 'Bearer ' . $access_token
                ],

            ];
            $response = $guzzleClient->request('GET', $url, $params);
            Log::info('Got the response from Tools!');
            $json = json_decode($response->getBody()->getContents(), true);
            Log::info('Number of objects in response: ' . count($json['data']));
            return $json;
        } catch (\Exception $e) {
            Log::info('There was some exception in TourDetailController->getCategoryAccessToken()');
            return $e->getResponse()->getStatusCode() . ': ' . $e->getMessage();
        }
      
    }

    public function addsimage(Request $request, $email)
    {
        Log::info('In Myaddsimage->addsimage()');
        $input = $request->all();
        $validator = Validator::make($input, [
            'image_title' => 'required',
            'youtube_url' => 'required',
           
            
                ]);

       
        if ($validator->fails()) {
            Log::info('if condition fails');
            return $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
        }   
               try {
              $validatorResponse = $this->validateNewImageData($request);
            Log::info('$validatorResponse[success]: ' . $validatorResponse['success']);
            if ($validatorResponse['success']) {
                $access_token = $this->getCategoryAccessToken();
                $imagetrip = $request->image_logo;   
                $multipartArray = [];
                log::info('in token ll');
                
                for($count = 0; $count < count($imagetrip); $count++)
                {
           $multipartArray[] = [
                    'name'     => 'image_title',
                    'contents' => $input['image_title']
                ];
           $multipartArray[] = [
                    'name'     => 'youtube_url',
                    'contents' => $input['youtube_url']
                ];    
            array_push($multipartArray, [
                'name'     => 'operator_auth_id',
                'contents' => $input['operator_auth_id']
            ]);
            array_push($multipartArray, [
                'name'     => 'operator_auth_name',
                'contents' => $input['operator_auth_name']
            ]);
            array_push($multipartArray, [
                'name'     => 'operator_auth_email',
                'contents' => $input['operator_auth_email']
            ]);

            array_push($multipartArray, [
                'name'     => 'image_logo',
                'contents' => file_get_contents($imagetrip[$count]),
                'filename' => $imagetrip[$count]->getClientOriginalName()
            ]);
            $http = new Client(); //GuzzleHttp\Client
                $response = $http->post(
                    Config::get('app.HOLIDAY_M_BASE_URL') . Config::get('app.ADD_MYHOLIDAYIMAGE_URL'),
                    [
                        'headers' => [
                            'Accept'     => 'application/json',
                            'Authorization' => 'Bearer ' . $access_token
                        ],
                        'multipart' => $multipartArray,

                    ]
                );

          
                }
                
                
                Log::info('Got the response from create Training!');
                $responseJson = json_decode($response->getBody()->getContents(), true);

                return response()->json($responseJson, 200);
            } else {
                return response()->json($responseJson, 422);
            }
        } catch (\Exception $e) { 
            Log::info('There was some exception in TourDetailController->addsimage()');
            Log::info($e->getMessage()); 
            $response = [ 
                'success' => false, 
                'data' => '', 
                'message' => $e->getMessage() 
            ];
            return response()->json($response, 500);
        }
    }

    private function validateNewImageData(Request $request) {
       
        $input = $request->all();
        $validator = Validator::make($input, [
            'image_title' => 'required|string|max:255',
            'youtube_url' => 'required',
            
        ]);

        if ($validator->fails()) {
            return $response = [
                'success' => false,
                'message' => 'Class already exits.'
            ];
        } else {
            return $response = [
                'success' => true,
                'message' => 'Class data is ok to store.'
            ];
        }
    }

    public function addsiternary(Request $request)
    {
        Log::info('In Myaddsiternary->addsiternary()');
        $input = $request->all();
        Log::info($request);
    
        $validator = Validator::make($input, [
            'iternary_title' => 'required|string|max:255',
            'iternary_days' => 'required|string|max:255',
            'iternarylocation' => 'required|string|max:255',
            'iternarydescription' => 'required',
                    
        ]);
    
        if ($validator->fails()) {
            Log::info('if  job condition fails');
            return $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
        }
        Log::info('
        ');
        try {
            Log::info('Validating given Training data...');
    
            $validatorResponse = $this->validateNewIternaryData($request);
    
            Log::info('$validatorResponse[success]: ' . $validatorResponse['success']);
    
            if ($validatorResponse['success']) {
    
                Log::info('Calling TourDetailController::getCategoryAccessToken()');
                $access_token = $this->getCategoryAccessToken();
                Log::info('Got the access token from TourDetailController::getCategoryAccessToken(). Now creating Class!');
                Log::info('HOLIDAY_M_BASE_URL . ADD_MYHOLIDAYITERNARY_URL: ' . Config::get('app.HOLIDAY_M_BASE_URL') . Config::get('app.ADD_MYHOLIDAYITERNARY_URL'));
                $multipartArray = [];
           
            $multipartArray[] = [
                'name'     => 'iternary_title',
                'contents' => $input['iternary_title']
            ];
            array_push($multipartArray, [
                'name' => 'iternary_days',
                'contents' => $input['iternary_days']
            ]);  array_push($multipartArray, [
                'name'     => 'operator_auth_id',
                'contents' => $input['operator_auth_id']
            ]);
            array_push($multipartArray, [
                'name'     => 'operator_auth_name',
                'contents' => $input['operator_auth_name']
            ]);
            array_push($multipartArray, [
                'name'     => 'operator_auth_email',
                'contents' => $input['operator_auth_email']
            ]);
            array_push($multipartArray, [
                'name'     => 'iternarylocation',
                'contents' => $input['iternarylocation']
            ]);
            array_push($multipartArray, [
                'name'     => 'iternarydescription',
                'contents' => $input['iternarydescription']
            ]);
           
                $http = new Client(); //GuzzleHttp\Client
                log::info("before param");
                $response = $http->post(
                    Config::get('app.HOLIDAY_M_BASE_URL') . Config::get('app.ADD_MYHOLIDAYITERNARY_URL'),
                    [
                        'headers' => [
                            'Accept'     => 'application/json',
                            'Authorization' => 'Bearer ' . $access_token
                        ],
                        'multipart' => $multipartArray,
    
                    ]
                );
                log::info("after param");
                Log::info('Got the response from create Training!');
                $responseJson = json_decode($response->getBody()->getContents(), true);
    
                return response()->json($responseJson, 200);
            } else {
                return response()->json($responseJson, 422);
            }
        } catch (\Exception $e) {
            Log::info('There was some exception in TourDetailController->addsiternary()');
            Log::info($e->getMessage());
            $response = [
                'success' => false,
                'data' => '',
                'message' => $e->getMessage()
            ];
            return response()->json($response, 500);
        }
    }

    private function validateNewIternaryData(Request $request) {
       
        $input = $request->all();
        $validator = Validator::make($input, [
            'iternary_title' => 'required|string|max:255',
           
            'iternarylocation' => 'required|string|max:255',
            'iternarydescription' => 'required',
            
        ]);

        if ($validator->fails()) {
            return $response = [
                'success' => false,
                'message' => 'Class already exits.'
            ];
        } else {
            return $response = [
                'success' => true,
                'message' => 'Class data is ok to store.'
            ];
        }
    }

    public function destroyiternary($id)
    {
        Log::info($id);
        Log::info('In TourDetailController->destroyiternary()');
        try {
            $access_token = $this->getCategoryAccessToken();
            $url = ''
                . Config::get('app.HOLIDAY_M_BASE_URL')
                . Config::get('app.DELETE_ITERNARY_URL')
                .'/'
                .$id;
            Log::info('Got the access token from TourDetailController::getCategoryAccessToken(). Now fetching Iter!');
            Log::info('Deleting Url: ' . $url);
            $guzzleClient = new Client(); //GuzzleHttp\Client
            $params = [
                'headers' => [
                    'Accept'     => 'application/json',
                    'Authorization' => 'Bearer ' . $access_token
                ]
            ];
            $response = $guzzleClient->request('GET', $url, $params);
            Log::info('Got the response from Professions!');
            $json = json_decode($response->getBody()->getContents(), true);
            return $json;
        } catch (\Exception $e) {
            Log::info('There was some exception in TourDetailController->destroyiternary()');
            return $e->getResponse()->getStatusCode() . ': ' . $e->getMessage();
        }
    }
    

    public function allgetuserdetail($id)
    {
        Log::info($id);
        Log::info('In TourDetailController->allgetuserdetail()');
        try {
            $access_token = $this->getCategoryAccessToken();
            $url = ''
                . Config::get('app.HOLIDAY_M_BASE_URL')
                . Config::get('app.DETAIL_ALLSUBSCRIBER_URL')
                .'/'
                .$id;
            Log::info('Got the access token from TourDetailController::getCategoryAccessToken(). Now fetching Iternary!');
            Log::info('Deleting Url: ' . $url);
            $guzzleClient = new Client(); //GuzzleHttp\Client
            $params = [
                'headers' => [
                    'Accept'     => 'application/json',
                    'Authorization' => 'Bearer ' . $access_token
                ]
            ];
            $response = $guzzleClient->request('GET', $url, $params);
            Log::info('Got the response from Professions!');
            $json = json_decode($response->getBody()->getContents(), true);
            return $json;
        } catch (\Exception $e) {
            Log::info('There was some exception in TourDetailController->allgetuserdetail()');
            return $e->getResponse()->getStatusCode() . ': ' . $e->getMessage();
        }
    }
    public function getuserdetail($id)
    {
        Log::info($id);
        Log::info('In TourDetailController->getuserdetail()');
        try {
            $access_token = $this->getCategoryAccessToken();
            $url = ''
                . Config::get('app.HOLIDAY_M_BASE_URL')
                . Config::get('app.DETAIL_SUBSCRIBER_URL')
                .'/'
                .$id;
            Log::info('Got the access token from TourDetailController::getCategoryAccessToken(). Now fetching Iternary!');
            Log::info('Deleting Url: ' . $url);
            $guzzleClient = new Client(); //GuzzleHttp\Client
            $params = [
                'headers' => [
                    'Accept'     => 'application/json',
                    'Authorization' => 'Bearer ' . $access_token
                ]
            ];
            $response = $guzzleClient->request('GET', $url, $params);
            Log::info('Got the response from Professions!');
            $json = json_decode($response->getBody()->getContents(), true);
            return $json;
        } catch (\Exception $e) {
            Log::info('There was some exception in TourDetailController->getuserdetail()');
            return $e->getResponse()->getStatusCode() . ': ' . $e->getMessage();
        }
    }

    public function detailiternary($id)
    {
        Log::info($id);
        Log::info('In TourDetailController->detailiternary()');
        try {
            $access_token = $this->getCategoryAccessToken();
            $url = ''
                . Config::get('app.HOLIDAY_M_BASE_URL')
                . Config::get('app.DETAIL_ITERNARY_URL')
                .'/'
                .$id;
            Log::info('Got the access token from TourDetailController::getCategoryAccessToken(). Now fetching Iternary!');
            Log::info('Deleting Url: ' . $url);
            $guzzleClient = new Client(); //GuzzleHttp\Client
            $params = [
                'headers' => [
                    'Accept'     => 'application/json',
                    'Authorization' => 'Bearer ' . $access_token
                ]
            ];
            $response = $guzzleClient->request('GET', $url, $params);
            Log::info('Got the response from Professions!');
            $json = json_decode($response->getBody()->getContents(), true);
            return $json;
        } catch (\Exception $e) {
            Log::info('There was some exception in TourDetailController->detailiternary()');
            return $e->getResponse()->getStatusCode() . ': ' . $e->getMessage();
        }
    }

    public function edititernary($id)
    {
        Log::info($id);
        Log::info('In TourDetailController->edititernary()');
        try {
            $access_token = $this->getCategoryAccessToken();
            $url = ''
                . Config::get('app.HOLIDAY_M_BASE_URL')
                . Config::get('app.EDIT_ITERNARY_URL')
                .'/'
                .$id;
            Log::info('Got the access token from TourDetailController::getCategoryAccessToken(). Now fetching Iternary!');
            Log::info('Deleting Url: ' . $url);
            $guzzleClient = new Client(); //GuzzleHttp\Client
            $params = [
                'headers' => [
                    'Accept'     => 'application/json',
                    'Authorization' => 'Bearer ' . $access_token
                ]
            ];
            $response = $guzzleClient->request('GET', $url, $params);
            Log::info('Got the response from Professions!');
            $json = json_decode($response->getBody()->getContents(), true);
            return $json;
        } catch (\Exception $e) {
            Log::info('There was some exception in TourDetailController->edititernary()');
            return $e->getResponse()->getStatusCode() . ': ' . $e->getMessage();
        }
    }   

    public function destroyimages($id)
    {
        Log::info($id);
        Log::info('In TourDetailController->destroyimages()');
        try {
            $access_token = $this->getCategoryAccessToken();
            $url = ''
                . Config::get('app.HOLIDAY_M_BASE_URL')
                . Config::get('app.DELETE_IMAGES_URL')
                .'/'
                .$id;
            Log::info('Got the access token from TourDetailController::getCategoryAccessToken(). Now fetching Iter!');
            Log::info('Deleting Url: ' . $url);
            $guzzleClient = new Client(); //GuzzleHttp\Client
            $params = [
                'headers' => [
                    'Accept'     => 'application/json',
                    'Authorization' => 'Bearer ' . $access_token
                ]
            ];
            $response = $guzzleClient->request('GET', $url, $params);
            Log::info('Got the response from Professions!');
            $json = json_decode($response->getBody()->getContents(), true);
            return $json;
        } catch (\Exception $e) {
            Log::info('There was some exception in TourDetailController->destroyimages()');
            return $e->getResponse()->getStatusCode() . ': ' . $e->getMessage();
        }
    }

    public function detailimages($id)
    {
        Log::info($id);
        Log::info('In TourDetailController->detailimages()');
        try {
            $access_token = $this->getCategoryAccessToken();
            $url = ''
                . Config::get('app.HOLIDAY_M_BASE_URL')
                . Config::get('app.DETAIL_IMAGES_URL')
                .'/'
                .$id;
            Log::info('Got the access token from TourDetailController::getCategoryAccessToken(). Now fetching Image!');
            Log::info('Deleting Url: ' . $url);
            $guzzleClient = new Client(); //GuzzleHttp\Client
            $params = [
                'headers' => [
                    'Accept'     => 'application/json',
                    'Authorization' => 'Bearer ' . $access_token
                ]
            ];
            $response = $guzzleClient->request('GET', $url, $params);
            Log::info('Got the response from Professions!');
            $json = json_decode($response->getBody()->getContents(), true);
            return $json;
        } catch (\Exception $e) {
            Log::info('There was some exception in TourDetailController->detailimages()');
            return $e->getResponse()->getStatusCode() . ': ' . $e->getMessage();
        }
    }

    public function editimages($id)
    {
        Log::info($id);
        Log::info('In TourDetailController->editimages()');
        try {
            $access_token = $this->getCategoryAccessToken();
            $url = ''
                . Config::get('app.HOLIDAY_M_BASE_URL')
                . Config::get('app.EDIT_IMAGES_URL')
                .'/'
                .$id;
            Log::info('Got the access token from TourDetailController::getCategoryAccessToken(). Now fetching Image!');
            Log::info('Editing Url: ' . $url);
            $guzzleClient = new Client(); //GuzzleHttp\Client
            $params = [
                'headers' => [
                    'Accept'     => 'application/json',
                    'Authorization' => 'Bearer ' . $access_token
                ]
            ];
            $response = $guzzleClient->request('GET', $url, $params);
            Log::info('Got the response from Professions!');
            $json = json_decode($response->getBody()->getContents(), true);
            return $json;
        } catch (\Exception $e) {
            Log::info('There was some exception in TourDetailController->editimages()');
            return $e->getResponse()->getStatusCode() . ': ' . $e->getMessage();
        }
    }   

    public function updateimages(Request $request)
    {
        Log::info('In TourDetailController->updateimages()');
        $input = $request->all();
        Log::info($request);
        $validator = Validator::make($input, [
            'image_title' => 'required',
            'youtube_url' => 'required',
        ]);
        if ($validator->fails()) {
            return $response = [
                'success' => false,
                'message' => $validator->errors()->first()
            ];
        }
        try {
            Log::info('Validating given Training data...');
            $validatorResponse = $this->validateNewImageData($request);
            Log::info('$validatorResponse[success]: ' . $validatorResponse['success']);
            if ($validatorResponse['success']) {
                Log::info('Calling TourDetailController::getCategoryAccessToken()');
                $access_token = $this->getCategoryAccessToken();
                Log::info('Got the access token from TourDetailController::getCategoryAccessToken(). Now creating Class!');
                Log::info('HOLIDAY_M_BASE_URL . UPDATE_IMAGE_URL: ' . Config::get('app.HOLIDAY_M_BASE_URL') . Config::get('app.UPDATE_IMAGE_URL'));
                $imagetrip = $request->image_logo;   
                $multipartArray = [];
                log::info('in token ll');
                if($imagetrip)
                {
                for($count = 0; $count < count($imagetrip); $count++)
                {
           $multipartArray[] = [
                    'name'     => 'image_title',
                    'contents' => $input['image_title']
                ];
             $multipartArray[] = [
                    'name'     => 'youtube_url',
                    'contents' => $input['youtube_url']
                ];   
            array_push($multipartArray, [
                'name'     => 'operator_auth_id',
                'contents' => $input['operator_auth_id']
            ]);
            array_push($multipartArray, [
                'name'     => 'operator_auth_name',
                'contents' => $input['operator_auth_name']
            ]);
            array_push($multipartArray, [
                'name'     => 'operator_auth_email',
                'contents' => $input['operator_auth_email']
            ]);
            array_push($multipartArray, [
                'name'     => 'operator_hidden_id',
                'contents' => $input['operator_hidden_id']
            ]);
            array_push($multipartArray, [
                'name'     => 'image_logo',
                'contents' => file_get_contents($imagetrip[$count]),
                'filename' => $imagetrip[$count]->getClientOriginalName()
            ]);
            $http = new Client(); //GuzzleHttp\Client
                $response = $http->post(
                    Config::get('app.HOLIDAY_M_BASE_URL') . Config::get('app.UPDATE_IMAGE_URL'),
                    [
                        'headers' => [
                            'Accept'     => 'application/json',
                            'Authorization' => 'Bearer ' . $access_token
                        ],
                        'multipart' => $multipartArray,
                    ]
                );
                }
            }
            else{
                $multipartArray[] = [
                    'name'     => 'image_title',
                    'contents' => $input['image_title']
                ];
                $multipartArray[] = [
                    'name'     => 'youtube_url',
                    'contents' => $input['youtube_url']
                ];  
            array_push($multipartArray, [
                'name'     => 'operator_auth_id',
                'contents' => $input['operator_auth_id']
            ]);
            array_push($multipartArray, [
                'name'     => 'operator_auth_name',
                'contents' => $input['operator_auth_name']
            ]);
            array_push($multipartArray, [
                'name'     => 'operator_auth_email',
                'contents' => $input['operator_auth_email']
            ]);
            array_push($multipartArray, [
                'name'     => 'operator_hidden_id',
                'contents' => $input['operator_hidden_id']
            ]);
            $http = new Client(); //GuzzleHttp\Client
                $response = $http->post(
                    Config::get('app.HOLIDAY_M_BASE_URL') . Config::get('app.UPDATE_IMAGE_URL'),
                    [
                        'headers' => [
                            'Accept'     => 'application/json',
                            'Authorization' => 'Bearer ' . $access_token
                        ],
                        'multipart' => $multipartArray,
                    ]
                );
            }
                $responseJson = json_decode($response->getBody()->getContents(), true);
                return response()->json($responseJson, 200);
            } else {
                return response()->json($responseJson, 422);
            }
        } catch (\Exception $e) {
            Log::info('There was some exception in TourDetailController->updateimages()');
            Log::info($e->getMessage());
            $response = [
                'success' => false,
                'data' => '',
                'message' => $e->getMessage()
            ];
            return response()->json($response, 500);
        }
    }

    public function edittrips($id)
    {
        Log::info($id);
        Log::info('In TourDetailController->edittrips()');
        try {
            $access_token = $this->getCategoryAccessToken();
            $url = ''
                . Config::get('app.HOLIDAY_M_BASE_URL')
                . Config::get('app.EDIT_TRIP_URL')
                .'/'
                .$id;
            Log::info('Got the access token from TourDetailController::getCategoryAccessToken(). Now fetching Trip!');
            Log::info('Deleting Url: ' . $url);
            $guzzleClient = new Client(); //GuzzleHttp\Client
            $params = [
                'headers' => [
                    'Accept'     => 'application/json',
                    'Authorization' => 'Bearer ' . $access_token
                ]
            ];
            $response = $guzzleClient->request('GET', $url, $params);
            Log::info('Got the response from Professions!');
            $json = json_decode($response->getBody()->getContents(), true);
            return $json;
        } catch (\Exception $e) {
            Log::info('There was some exception in TourDetailController->edittrips()');
            return $e->getResponse()->getStatusCode() . ': ' . $e->getMessage();
        }
    }   

    public function updateiterna(Request $request)
    {
        Log::info('In TourDetailController->updateiterna()');
        $input = $request->all();
        Log::info($request);

        $validator = Validator::make($input, [
            'iternary_title' => 'required|string|max:255',
          
            'iternarylocation' => 'required|string|max:255',
            'iternarydescription' => 'required',
        ]);
        if ($validator->fails()) {
            return $response = [
                'success' => false,
                'message' => $validator->errors()->first()
            ];
        }
        try {
            Log::info('Validating given Training data...');

            $validatorResponse = $this->validateNewIternaryData($request);

            Log::info('$validatorResponse[success]: ' . $validatorResponse['success']);
            if ($validatorResponse['success']) {

                Log::info('Calling TourDetailController::getCategoryAccessToken()');
                $access_token = $this->getCategoryAccessToken();
                Log::info('Got the access token from TourDetailController::getCategoryAccessToken(). Now creating Class!');
                Log::info('HOLIDAY_M_BASE_URL . UPDATE_ITERNARY_URL: ' . Config::get('app.HOLIDAY_M_BASE_URL') . Config::get('app.UPDATE_ITERNARY_URL'));
                $multipartArray = [];
           
            $multipartArray[] = [
                'name'     => 'iternary_title',
                'contents' => $input['iternary_title']
            ];
            array_push($multipartArray, [
                'name' => 'iternary_days',
                'contents' => $input['iternary_days']
            ]);  array_push($multipartArray, [
                'name'     => 'operator_auth_id',
                'contents' => $input['operator_auth_id']
            ]);
            array_push($multipartArray, [
                'name'     => 'operator_auth_name',
                'contents' => $input['operator_auth_name']
            ]);
            array_push($multipartArray, [
                'name'     => 'operator_auth_email',
                'contents' => $input['operator_auth_email']
            ]);
            array_push($multipartArray, [
                'name'     => 'operator_hidden_id',
                'contents' => $input['operator_hidden_id']
            ]);
            array_push($multipartArray, [
                'name'     => 'iternarylocation',
                'contents' => $input['iternarylocation']
            ]);
            array_push($multipartArray, [
                'name'     => 'iternarydescription',
                'contents' => $input['iternarydescription']
            ]);

                
                $http = new Client(); //GuzzleHttp\Client
                $response = $http->post(
                    Config::get('app.HOLIDAY_M_BASE_URL') . Config::get('app.UPDATE_ITERNARY_URL'),
                    [
                        'headers' => [
                            'Accept'     => 'application/json',
                            'Authorization' => 'Bearer ' . $access_token
                        ],
                        'multipart' => $multipartArray,

                    ]
                );

                $responseJson = json_decode($response->getBody()->getContents(), true);

                return response()->json($responseJson, 200);
            } else {
                return response()->json($responseJson, 422);
            }
        } catch (\Exception $e) {
            Log::info('There was some exception in TourDetailController->updateiterna()');
            Log::info($e->getMessage());
            $response = [
                'success' => false,
                'data' => '',
                'message' => $e->getMessage()
            ];

            return response()->json($response, 500);
        }
    }

    public function destroytrips($id)
    {
        Log::info($id);
        Log::info('In TourDetailController->destroytrips()');
        try {
            $access_token = $this->getCategoryAccessToken();
            $url = ''
                . Config::get('app.HOLIDAY_M_BASE_URL')
                . Config::get('app.DELETE_TRIP_URL')
                .'/'
                .$id;
            Log::info('Got the access token from TourDetailController::getCategoryAccessToken(). Now fetching Trip!');
            Log::info('Deleting Url: ' . $url);
            $guzzleClient = new Client(); //GuzzleHttp\Client
            $params = [
                'headers' => [
                    'Accept'     => 'application/json',
                    'Authorization' => 'Bearer ' . $access_token
                ]
            ];
            $response = $guzzleClient->request('GET', $url, $params);
            Log::info('Got the response from Professions!');
            $json = json_decode($response->getBody()->getContents(), true);
            return $json;
        } catch (\Exception $e) {
            Log::info('There was some exception in TourDetailController->destroytrips()');
            return $e->getResponse()->getStatusCode() . ': ' . $e->getMessage();
        }
    }

    public function publishtrip($id)
    {
        Log::info($id);
        Log::info('In TourDetailController->publishtrip()');
        try {
            $access_token = $this->getCategoryAccessToken();
            $url = ''
                . Config::get('app.HOLIDAY_M_BASE_URL')
                . Config::get('app.PUBLISH_TRIP_URL')
                .'/'
                .$id;
            Log::info('Got the access token from TourDetailController::getCategoryAccessToken(). Now fetching Trip!');
            Log::info('Deleting Url: ' . $url);
            $guzzleClient = new Client(); //GuzzleHttp\Client
            $params = [
                'headers' => [
                    'Accept'     => 'application/json',
                    'Authorization' => 'Bearer ' . $access_token
                ]
            ];
            $response = $guzzleClient->request('GET', $url, $params);
            Log::info('Got the response from Professions!');
            $json = json_decode($response->getBody()->getContents(), true);
            return $json;
        } catch (\Exception $e) {
            Log::info('There was some exception in TourDetailController->publishtrip()');
            return $e->getResponse()->getStatusCode() . ': ' . $e->getMessage();
        }
    }
    public function unpublishtrip($id)
    {
        Log::info($id);
        Log::info('In TourDetailController->unpublishtrip()');
        try {
            $access_token = $this->getCategoryAccessToken();
            $url = ''
                . Config::get('app.HOLIDAY_M_BASE_URL')
                . Config::get('app.UNPUBLISH_TRIP_URL')
                .'/'
                .$id;
            Log::info('Got the access token from TourDetailController::getCategoryAccessToken(). Now fetching Trip!');
            Log::info('Deleting Url: ' . $url);
            $guzzleClient = new Client(); //GuzzleHttp\Client
            $params = [
                'headers' => [
                    'Accept'     => 'application/json',
                    'Authorization' => 'Bearer ' . $access_token
                ]
            ];
            $response = $guzzleClient->request('GET', $url, $params);
            Log::info('Got the response from Professions!');
            $json = json_decode($response->getBody()->getContents(), true);
            return $json;
        } catch (\Exception $e) {
            Log::info('There was some exception in TourDetailController->unpublishtrip()');
            return $e->getResponse()->getStatusCode() . ': ' . $e->getMessage();
        }
    }

    public function updatetrip(Request $request)
    {
        Log::info('In TourDetailController->updatetrip()');
        $input = $request->all();
        Log::info($request);

        $validator = Validator::make($input, [
            'category_slug' => 'required|string|max:255',
            'my_trip' => 'required|string|max:255',
            'tourcountryId' => 'required|string|max:255',
            'tourstate' => 'required|string|max:255',
            'tourcity' => 'required|string|max:255',
            'NoOfDays' => 'required|string|max:255',
            'NoOfNight' => 'required|string|max:255',
            'tour_date' => 'required|string|max:255',
            'tour_time' => 'required|string|max:255',
            'Destination' => 'required',
            'trip_highlight' => 'required',
            'tripdescription' =>'required',
           
        ]);
        if ($validator->fails()) {
            return $response = [
                'success' => false,
                'message' => $validator->errors()->first()
            ];
        }
        try {
            Log::info('Validating given Training data...');

            $validatorResponse = $this->validateNewTripData($request);

            Log::info('$validatorResponse[success]: ' . $validatorResponse['success']);
            if ($validatorResponse['success']) {

                Log::info('Calling TourDetailController::getCategoryAccessToken()');
                $access_token = $this->getCategoryAccessToken();
                Log::info('Got the access token from TourDetailController::getCategoryAccessToken(). Now creating Class!');
                Log::info('HOLIDAY_M_BASE_URL . UPDATE_TRIP_URL: ' . Config::get('app.HOLIDAY_M_BASE_URL') . Config::get('app.UPDATE_TRIP_URL'));
                $multipartArray = [];
                // $training_logo = null;
               
            
                    
                    $multipartArray[] = [
                        'name'     => 'category_slug',
                        'contents' => $input['category_slug']
                    ];
                    array_push($multipartArray, [
                        'name' => 'my_trip',
                        'contents' => $input['my_trip']
                    ]);  array_push($multipartArray, [
                        'name'     => 'operator_auth_id',
                        'contents' => $input['operator_auth_id']
                    ]);
                    array_push($multipartArray, [
                        'name'     => 'operator_auth_name',
                        'contents' => $input['operator_auth_name']
                    ]);
                    array_push($multipartArray, [
                        'name'     => 'operator_auth_email',
                        'contents' => $input['operator_auth_email']
                    ]);
                    array_push($multipartArray, [
                        'name'     => 'tourcountryId',
                        'contents' => $input['tourcountryId']
                    ]);
                    array_push($multipartArray, [
                        'name'     => 'tourstate',
                        'contents' => $input['tourstate']
                    ]);
                    array_push($multipartArray, [
                        'name'     => 'tourcity',
                        'contents' => $input['tourcity']
                    ]);
                    array_push($multipartArray, [
                        'name'     => 'NoOfDays',
                        'contents' => $input['NoOfDays']
                    ]);
               
                    array_push($multipartArray, [
                        'name'     => 'NoOfNight',
                        'contents' => $input['NoOfNight']
                    ]);
                    array_push($multipartArray, [
                        'name'     => 'tour_date',
                        'contents' => $input['tour_date']
                    ]);
                    array_push($multipartArray, [
                        'name'     => 'tour_time',
                        'contents' => $input['tour_time']
                    ]);
                    array_push($multipartArray, [
                        'name'     => 'Destination',
                        'contents' => $input['Destination']
                    ]);
                    array_push($multipartArray, [
                        'name'     => 'trip_highlight',
                        'contents' => $input['trip_highlight']
                    ]);
                    array_push($multipartArray, [
                        'name'     => 'operator_hidden_id',
                        'contents' => $input['operator_hidden_id']
                    ]);
                    array_push($multipartArray, [
                        'name'     => 'tripdescription',
                        'contents' => $input['tripdescription']
                    ]);

                
                $http = new Client(); //GuzzleHttp\Client
                $response = $http->post(
                    Config::get('app.HOLIDAY_M_BASE_URL') . Config::get('app.UPDATE_TRIP_URL'),
                    [
                        'headers' => [
                            'Accept'     => 'application/json',
                            'Authorization' => 'Bearer ' . $access_token
                        ],
                        'multipart' => $multipartArray,

                    ]
                );

                $responseJson = json_decode($response->getBody()->getContents(), true);

                return response()->json($responseJson, 200);
            } else {
                return response()->json($responseJson, 422);
            }
        } catch (\Exception $e) {
            Log::info('There was some exception in TourDetailController->updatetrip()');
            Log::info($e->getMessage());
            $response = [
                'success' => false,
                'data' => '',
                'message' => $e->getMessage()
            ];

            return response()->json($response, 500);
        }
    }

    public function repubsTrip(Request $request)
    {
        Log::info('In TourDetailController->repubsTrip()');
        $input = $request->all();
        Log::info($request);

        $validator = Validator::make($input, [
            'category_slug' => 'required|string|max:255',
            'my_trip' => 'required|string|max:255',
            'tourcountryId' => 'required|string|max:255',
            'tourstate' => 'required|string|max:255',
            'tourcity' => 'required|string|max:255',
            'NoOfDays' => 'required|string|max:255',
            'NoOfNight' => 'required|string|max:255',
            'tour_date' => 'required|string|max:255',
            'tour_time' => 'required|string|max:255',
            'Destination' => 'required',
            'trip_highlight' => 'required',
            'tripdescription' =>'required',
           
        ]);
        if ($validator->fails()) {
            return $response = [
                'success' => false,
                'message' => $validator->errors()->first()
            ];
        }
        try {
            Log::info('Validating given Training data...');

            $validatorResponse = $this->validateNewTripData($request);

            Log::info('$validatorResponse[success]: ' . $validatorResponse['success']);
            if ($validatorResponse['success']) {

                Log::info('Calling TourDetailController::getCategoryAccessToken()');
                $access_token = $this->getCategoryAccessToken();
                Log::info('Got the access token from TourDetailController::getCategoryAccessToken(). Now creating Class!');
                Log::info('HOLIDAY_M_BASE_URL . REPUB_TRIP_URL: ' . Config::get('app.HOLIDAY_M_BASE_URL') . Config::get('app.REPUB_TRIP_URL'));
                $multipartArray = [];
                // $training_logo = null;
               
            
                    
                    $multipartArray[] = [
                        'name'     => 'category_slug',
                        'contents' => $input['category_slug']
                    ];
                    array_push($multipartArray, [
                        'name' => 'my_trip',
                        'contents' => $input['my_trip']
                    ]);  array_push($multipartArray, [
                        'name'     => 'operator_auth_id',
                        'contents' => $input['operator_auth_id']
                    ]);
                    array_push($multipartArray, [
                        'name'     => 'operator_auth_name',
                        'contents' => $input['operator_auth_name']
                    ]);
                    array_push($multipartArray, [
                        'name'     => 'operator_auth_email',
                        'contents' => $input['operator_auth_email']
                    ]);
                    array_push($multipartArray, [
                        'name'     => 'tourcountryId',
                        'contents' => $input['tourcountryId']
                    ]);
                    array_push($multipartArray, [
                        'name'     => 'tourstate',
                        'contents' => $input['tourstate']
                    ]);
                    array_push($multipartArray, [
                        'name'     => 'tourcity',
                        'contents' => $input['tourcity']
                    ]);
                    array_push($multipartArray, [
                        'name'     => 'NoOfDays',
                        'contents' => $input['NoOfDays']
                    ]);
               
                    array_push($multipartArray, [
                        'name'     => 'NoOfNight',
                        'contents' => $input['NoOfNight']
                    ]);
                    array_push($multipartArray, [
                        'name'     => 'tour_date',
                        'contents' => $input['tour_date']
                    ]);
                    array_push($multipartArray, [
                        'name'     => 'tour_time',
                        'contents' => $input['tour_time']
                    ]);
                    array_push($multipartArray, [
                        'name'     => 'Destination',
                        'contents' => $input['Destination']
                    ]);
                    array_push($multipartArray, [
                        'name'     => 'trip_highlight',
                        'contents' => $input['trip_highlight']
                    ]);
                    array_push($multipartArray, [
                        'name'     => 'operator_hidden_id',
                        'contents' => $input['operator_hidden_id']
                    ]);
                    array_push($multipartArray, [
                        'name'     => 'tripdescription',
                        'contents' => $input['tripdescription']
                    ]);

                
                $http = new Client(); //GuzzleHttp\Client
                $response = $http->post(
                    Config::get('app.HOLIDAY_M_BASE_URL') . Config::get('app.REPUB_TRIP_URL'),
                    [
                        'headers' => [
                            'Accept'     => 'application/json',
                            'Authorization' => 'Bearer ' . $access_token
                        ],
                        'multipart' => $multipartArray,

                    ]
                );

                $responseJson = json_decode($response->getBody()->getContents(), true);

                return response()->json($responseJson, 200);
            } else {
                return response()->json($responseJson, 422);
            }
        } catch (\Exception $e) {
            Log::info('There was some exception in TourDetailController->repubsTrip()');
            Log::info($e->getMessage());
            $response = [
                'success' => false,
                'data' => '',
                'message' => $e->getMessage()
            ];

            return response()->json($response, 500);
        }
    }

    public function detailtrips($id)
    {
        Log::info($id);
        Log::info('In TourDetailController->detailtrips()');
        try {
            $access_token = $this->getCategoryAccessToken();
            $url = ''
                . Config::get('app.HOLIDAY_M_BASE_URL')
                . Config::get('app.DETAIL_TRIP_URL')
                .'/'
                .$id;
            Log::info('Got the access token from TourDetailController::getCategoryAccessToken(). Now fetching Trip!');
            Log::info('Deleting Url: ' . $url);
            $guzzleClient = new Client(); //GuzzleHttp\Client
            $params = [
                'headers' => [
                    'Accept'     => 'application/json',
                    'Authorization' => 'Bearer ' . $access_token
                ]
            ];
            $response = $guzzleClient->request('GET', $url, $params);
            Log::info('Got the response from Professions!');
            $json = json_decode($response->getBody()->getContents(), true);
            return $json;
        } catch (\Exception $e) {
            Log::info('There was some exception in TourDetailController->detailtrips()');
            return $e->getResponse()->getStatusCode() . ': ' . $e->getMessage();
        }
    }

public function addtrip(Request $request, $email)
{
    Log::info('In addtrip->addtrip()');
    $input = $request->all();
    Log::info($request);

    $validator = Validator::make($input, [
        'category_slug' => 'required',
        'my_trip' => 'required',
        'tourcountryId' => 'required',
        'tourstate' => 'required',
        'tourcity' => 'required',
        'NoOfDays' => 'required',
        'NoOfNight' => 'required',
        'tour_date' => 'required',
        'tour_time' => 'required',
        'Destination' => 'required',
        'trip_highlight' => 'required',
        'tripdescription' => 'required',
        
       
           ]);

    if ($validator->fails()) {
        Log::info('if condition fails');
        return $response = [
            'success' => false,
            'message' => $validator->errors()
        ];
    }
    Log::info('validation not working');
    try {
        Log::info('Validating given Training data...');

        $validatorResponse = $this->validateNewTripData($request);

        Log::info('$validatorResponse[success]: ' . $validatorResponse['success']);

        if ($validatorResponse['success']) {

            Log::info('Calling TourDetailController::getCategoryAccessToken()');
            $access_token = $this->getCategoryAccessToken();
            Log::info('Got the access token from TourDetailController::getCategoryAccessToken(). Now creating Class!');
            Log::info('HOLIDAY_M_BASE_URL . ADD_MYHOLIDAYTRIP_URL: ' . Config::get('app.HOLIDAY_M_BASE_URL') . Config::get('app.ADD_MYHOLIDAYTRIP_URL'));
            $multipartArray = [];
           
            $multipartArray[] = [
                'name'     => 'category_slug',
                'contents' => $input['category_slug']
            ];
            array_push($multipartArray, [
                'name' => 'my_trip',
                'contents' => $input['my_trip']
            ]);  array_push($multipartArray, [
                'name'     => 'operator_auth_id',
                'contents' => $input['operator_auth_id']
            ]);
            array_push($multipartArray, [
                'name'     => 'operator_auth_name',
                'contents' => $input['operator_auth_name']
            ]);
            array_push($multipartArray, [
                'name'     => 'operator_auth_email',
                'contents' => $input['operator_auth_email']
            ]);

            array_push($multipartArray, [
                'name'     => 'adm_hidden_email',
                'contents' => $input['adm_hidden_email']
            ]);
            array_push($multipartArray, [
                'name'     => 'operator_auth_email',
                'contents' => $input['operator_auth_email']
            ]);
            array_push($multipartArray, [
                'name'     => 'tourcountryId',
                'contents' => $input['tourcountryId']
            ]);
            array_push($multipartArray, [
                'name'     => 'tourstate',
                'contents' => $input['tourstate']
            ]);
            array_push($multipartArray, [
                'name'     => 'tourcity',
                'contents' => $input['tourcity']
            ]);
            array_push($multipartArray, [
                'name'     => 'NoOfDays',
                'contents' => $input['NoOfDays']
            ]);

            array_push($multipartArray, [
                'name'     => 'NoOfNight',
                'contents' => $input['NoOfNight']
            ]);
            array_push($multipartArray, [
                'name'     => 'tour_date',
                'contents' => $input['tour_date']
            ]);
            array_push($multipartArray, [
                'name'     => 'tour_time',
                'contents' => $input['tour_time']
            ]);
            array_push($multipartArray, [
                'name'     => 'Destination',
                'contents' => $input['Destination']
            ]);
            array_push($multipartArray, [
                'name'     => 'trip_highlight',
                'contents' => $input['trip_highlight']
            ]);
            array_push($multipartArray, [
                'name'     => 'tripdescription',
                'contents' => $input['tripdescription']
            ]);
           
           
            $http = new Client(); //GuzzleHttp\Client
            $response = $http->post(
                Config::get('app.HOLIDAY_M_BASE_URL') . Config::get('app.ADD_MYHOLIDAYTRIP_URL'),
                [
                    'headers' => [
                        'Accept'     => 'application/json',
                        'Authorization' => 'Bearer ' . $access_token
                    ],
                    'multipart' => $multipartArray,

                ]
            );

            Log::info('Got the response from create Training!');
            $responseJson = json_decode($response->getBody()->getContents(), true);

            return response()->json($responseJson, 200);
        } else {
            return response()->json($responseJson, 422);
        }
    } catch (\Exception $e) {
        Log::info('There was some exception in TourDetailController->addtrip()');
        Log::info($e->getMessage());
        $response = [
            'success' => false,
            'data' => '',
            'message' => $e->getMessage()
        ];
        return response()->json($response, 500);
    }
}

public function detailinternal($id)
    {
        Log::info($id);
        Log::info('In TourDetailController->detailinternal()');
        try {
            $access_token = $this->getCategoryAccessToken();
            $url = ''
                . Config::get('app.HOLIDAY_M_BASE_URL')
                . Config::get('app.DETAIL_INTERNAL_URL')
                .'/'
                .$id;
            Log::info('Got the access token from TourDetailController::getCategoryAccessToken(). Now fetching Classes!');
            Log::info('Deleting Url: ' . $url);
            $guzzleClient = new Client(); //GuzzleHttp\Client
            $params = [
                'headers' => [
                    'Accept'     => 'application/json',
                    'Authorization' => 'Bearer ' . $access_token
                ]
            ];
            $response = $guzzleClient->request('GET', $url, $params);
            Log::info('Got the response from Professions!');
            $json = json_decode($response->getBody()->getContents(), true);
            return $json;
        } catch (\Exception $e) {
            Log::info('There was some exception in TourDetailController->detailinternal()');
            return $e->getResponse()->getStatusCode() . ': ' . $e->getMessage();
        }
    }


public function destroyinter($id)
{
    Log::info($id);
    Log::info('In TourDetailController->destroyinter()');
    try {
        $access_token = $this->getCategoryAccessToken();
        $url = ''
            . Config::get('app.HOLIDAY_M_BASE_URL')
            . Config::get('app.DELETE_INTERNAL_URL')
            .'/'
            .$id;
        Log::info('Got the access token from TourDetailController::getCategoryAccessToken(). Now fetching Classes!');
        Log::info('Deleting Url: ' . $url);
        $guzzleClient = new Client(); //GuzzleHttp\Client
        $params = [
            'headers' => [
                'Accept'     => 'application/json',
                'Authorization' => 'Bearer ' . $access_token
            ]
        ];
        $response = $guzzleClient->request('GET', $url, $params);
        Log::info('Got the response from Professions!');
        $json = json_decode($response->getBody()->getContents(), true);
        return $json;
    } catch (\Exception $e) {
        Log::info('There was some exception in TourDetailController->destroyinter()');
        return $e->getResponse()->getStatusCode() . ': ' . $e->getMessage();
    }
}



public function editinter($id)
{
    Log::info($id);
    Log::info('In TourDetailController->editinter()');
    try {
        $access_token = $this->getCategoryAccessToken();
        $url = ''
            . Config::get('app.HOLIDAY_M_BASE_URL')
            . Config::get('app.EDIT_INTERNA_URL')
            .'/'
            .$id;
        Log::info('Got the access token from TourDetailController::getCategoryAccessToken(). Now fetching Editinter!');
        Log::info('Editing Url: ' . $url);
        $guzzleClient = new Client(); //GuzzleHttp\Client
        $params = [
            'headers' => [
                'Accept'     => 'application/json',
                'Authorization' => 'Bearer ' . $access_token
            ]
        ];
        $response = $guzzleClient->request('GET', $url, $params);
        Log::info('Got the response from Professions!');
        $json = json_decode($response->getBody()->getContents(), true);
        return $json;
    } catch (\Exception $e) {
        Log::info('There was some exception in TourDetailController->editinter()');
        return $e->getResponse()->getStatusCode() . ': ' . $e->getMessage();
    }
}   

public function updateinterna(Request $request)
    {
        Log::info('In TourDetailController->updateinterna()');
        $input = $request->all();
        Log::info($request);

        $validator = Validator::make($input, [
            'countryId' => 'required|string|max:255',
            'interdescription' => 'required',
           
        ]);
        if ($validator->fails()) {
            return $response = [
                'success' => false,
                'message' => $validator->errors()->first()
            ];
        }
        try {
            Log::info('Validating given Training data...');

            $validatorResponse = $this->validateNewInternationalData($request);

            Log::info('$validatorResponse[success]: ' . $validatorResponse['success']);
            if ($validatorResponse['success']) {

                Log::info('Calling TourDetailController::getCategoryAccessToken()');
                $access_token = $this->getCategoryAccessToken();
                Log::info('Got the access token from TourDetailController::getCategoryAccessToken(). Now creating Class!');
                Log::info('HOLIDAY_M_BASE_URL . UPDATE_INTERNA_URL: ' . Config::get('app.HOLIDAY_M_BASE_URL') . Config::get('app.UPDATE_INTERNA_URL'));
                $multipartArray = [];
                // $training_logo = null;
                $international_logo = $request->file('international_logo');
                if ($international_logo != '') {
                    if (isset($input['international_logo'])) {
                        $international_logo = $input['international_logo'];
                    }
                    $multipartArray[] = [
                        'name'     => 'countryId',
                        'contents' => $input['countryId']
                    ];
                    array_push($multipartArray, [
                        'name' => 'interdescription',
                        'contents' => $input['interdescription']
                    ]);

                    array_push($multipartArray, [
                        'name' => 'admin_hidden_id',
                        'contents' => $input['admin_hidden_id']
                    ]);
                    array_push($multipartArray, [
                        'name'     => 'international_logo',
                        'contents' => file_get_contents($international_logo),
                        'filename' => $international_logo->getClientOriginalName()
                    ]);
                } else {
                    Log::info('internal  else part here');
                    $multipartArray[] = [
                        'name'     => 'countryId',
                        'contents' => $input['countryId']
                    ];
                    array_push($multipartArray, [
                        'name' => 'interdescription',
                        'contents' => $input['interdescription']
                    ]);

                    array_push($multipartArray, [
                        'name' => 'admin_hidden_id',
                        'contents' => $input['admin_hidden_id']
                    ]);

                }
                $http = new Client(); //GuzzleHttp\Client
                $response = $http->post(
                    Config::get('app.HOLIDAY_M_BASE_URL') . Config::get('app.UPDATE_INTERNA_URL'),
                    [
                        'headers' => [
                            'Accept'     => 'application/json',
                            'Authorization' => 'Bearer ' . $access_token
                        ],
                        'multipart' => $multipartArray,

                    ]
                );

                $responseJson = json_decode($response->getBody()->getContents(), true);

                return response()->json($responseJson, 200);
            } else {
                return response()->json($responseJson, 422);
            }
        } catch (\Exception $e) {
            Log::info('There was some exception in TourDetailController->updatetrip()');
            Log::info($e->getMessage());
            $response = [
                'success' => false,
                'data' => '',
                'message' => $e->getMessage()
            ];

            return response()->json($response, 500);
        }
    }
// =============================================================
public function detailstates($id)
    {
        Log::info($id);
        Log::info('In TourDetailController->detailstates()');
        try {
            $access_token = $this->getCategoryAccessToken();
            $url = ''
                . Config::get('app.HOLIDAY_M_BASE_URL')
                . Config::get('app.DETAIL_STATES_URL')
                .'/'
                .$id;
            Log::info('Got the access token from TourDetailController::getCategoryAccessToken(). Now fetching Classes!');
            Log::info('Deleting Url: ' . $url);
            $guzzleClient = new Client(); //GuzzleHttp\Client
            $params = [
                'headers' => [
                    'Accept'     => 'application/json',
                    'Authorization' => 'Bearer ' . $access_token
                ]
            ];
            $response = $guzzleClient->request('GET', $url, $params);
            Log::info('Got the response from Professions!');
            $json = json_decode($response->getBody()->getContents(), true);
            return $json;
        } catch (\Exception $e) {
            Log::info('There was some exception in TourDetailController->detailstates()');
            return $e->getResponse()->getStatusCode() . ': ' . $e->getMessage();
        }
    }



public function international($email)
{
    Log::info('In TourDetailController->international()');
    Log::info($email);
    try {
        $access_token = $this->getCategoryAccessToken();
        $url = ''
            . Config::get('app.HOLIDAY_M_BASE_URL')
            . Config::get('app.GET_MYINTERNATIONAL_URL')
            . '/'
            . $email;
        Log::info('Got the access tokenmyy from TourDetailController::getCategoryAccessToken(). Now fetching international2!');
        Log::info('before param');
        $guzzleClient = new Client(); //GuzzleHttp\Client
        $params = [
            'headers' => [
                'Accept'     => 'application/json',
                'Authorization' => 'Bearer ' . $access_token
            ]
        ];
        Log::info('after param');
        $response = $guzzleClient->request('GET', $url, $params);
        Log::info('Got the response from Trainings!');
        $json = json_decode($response->getBody()->getContents(), true);
        Log::info('Number of objects in response: ' . count($json['data']));
        return $json;
    } catch (\Exception $e) {
        Log::info('There was some myexception in TourDetailController->getCategoryAccessToken()');
        return $e->getResponse()->getStatusCode() . ': ' . $e->getMessage();
    }
}
public function getiternary_day($iternary_title)
{
    Log::info('In TourDetailController->getiternary_day()');
    Log::info($iternary_title);
    try {
        $access_token = $this->getCategoryAccessToken();
        $url = ''
            . Config::get('app.HOLIDAY_M_BASE_URL')
            . Config::get('app.GET_MYITERNARY_URL')
            . '/'
            . $iternary_title;
        Log::info('Got the access tokenmyy from TourDetailController::getCategoryAccessToken(). Now fetching international2!');
        Log::info('before param');
        $guzzleClient = new Client(); //GuzzleHttp\Client
        $params = [
            'headers' => [
                'Accept'     => 'application/json',
                'Authorization' => 'Bearer ' . $access_token
            ]
        ];
        Log::info('after param');
        $response = $guzzleClient->request('GET', $url, $params);
        Log::info('Got the response from Trainings!');
        $json = json_decode($response->getBody()->getContents(), true);
        Log::info('Number of objects in response: ' . count($json['data']));
        return $json;
    } catch (\Exception $e) {
        Log::info('There was some myexception in TourDetailController->getCategoryAccessToken()');
        return $e->getResponse()->getStatusCode() . ': ' . $e->getMessage();
    }
}

public function addinternational(Request $request, $email)
{
    Log::info('In Myaddinternational->addinternational()');
    $input = $request->all();
    Log::info($request);

    $validator = Validator::make($input, [
        'countryId' => 'required|string|max:255',
        'interdescription' => 'required',
       
           ]);

    if ($validator->fails()) {
        Log::info('if condition fails');
        return $response = [
            'success' => false,
            'message' => $validator->errors()
        ];
    }
    Log::info('validation not working');
    try {
        Log::info('Validating given Training data...');

        $validatorResponse = $this->validateNewInternationalData($request);

        Log::info('$validatorResponse[success]: ' . $validatorResponse['success']);

        if ($validatorResponse['success']) {

            Log::info('Calling TourDetailController::getInternationalAccessToken()');
            $access_token = $this->getCategoryAccessToken();
            Log::info('Got the access token from TourDetailController::getInternationalAccessToken(). Now creating International!');
            Log::info('HOLIDAY_M_BASE_URL . ADD_INTERNATIONAL_URL: ' . Config::get('app.HOLIDAY_M_BASE_URL') . Config::get('app.ADD_INTERNATIONAL_URL'));
            $multipartArray = [];
            $international_logo = null;
            if (isset($input['international_logo'])) {
                $international_logo = $input['international_logo'];
            }
            $multipartArray[] = [
                'name'     => 'countryId',
                'contents' => $input['countryId']
            ];
            array_push($multipartArray, [
                'name' => 'interdescription',
                'contents' => $input['interdescription']
            ]);  array_push($multipartArray, [
                'name'     => 'admin_auth_id',
                'contents' => $input['admin_auth_id']
            ]);
            array_push($multipartArray, [
                'name'     => 'admin_auth_name',
                'contents' => $input['admin_auth_name']
            ]);
            array_push($multipartArray, [
                'name'     => 'admin_auth_email',
                'contents' => $input['admin_auth_email']
            ]);
          
            array_push($multipartArray, [
                'name'     => 'international_logo',
                'contents' => file_get_contents($international_logo),
                'filename' => $international_logo->getClientOriginalName()
            ]);
            $http = new Client(); //GuzzleHttp\Client
            $response = $http->post(
                Config::get('app.HOLIDAY_M_BASE_URL') . Config::get('app.ADD_INTERNATIONAL_URL'),
                [
                    'headers' => [
                        'Accept'     => 'application/json',
                        'Authorization' => 'Bearer ' . $access_token
                    ],
                    'multipart' => $multipartArray,

                ]
            );

            Log::info('Got the response from create International!');
            $responseJson = json_decode($response->getBody()->getContents(), true);

            return response()->json($responseJson, 200);
        } else {
            return response()->json($responseJson, 422);
        }
    } catch (\Exception $e) {
        Log::info('There was some exception In Myaddinternational->addinternational()');
        Log::info($e->getMessage());
        $response = [
            'success' => false,
            'data' => '',
            'message' => $e->getMessage()
        ];
        return response()->json($response, 500);
    }
}



private function validateNewInternationalData(Request $request) {
       
    $input = $request->all();
    $validator = Validator::make($input, [
        'countryId' => 'required|string',
        'interdescription' =>'required|string',

    ]);

    if ($validator->fails()) {
        return $response = [
            'success' => false,
            'message' => 'International already exits.'
        ];
    } else {
        return $response = [
            'success' => true,
            'message' => 'International data is ok to store.'
        ];
    }
}


    private function validateNewTripData(Request $request) {
       
        $input = $request->all();
        $validator = Validator::make($input, [
            'category_slug' => 'required|string|max:255',
        'my_trip' => 'required|string|max:255',
        'tourcountryId' => 'required|string|max:255',
        'tourstate' => 'required|string|max:255',
        'tourcity' => 'required|string|max:255',
        'NoOfDays' => 'required|string|max:255',
        'NoOfNight' => 'required|string|max:255',
        'tour_date' => 'required|string|max:255',
        'tour_time' => 'required|string|max:255',
        'Destination' => 'required',
        'trip_highlight' => 'required',
        'tripdescription' => 'required',
        
        ]);

        if ($validator->fails()) {
            return $response = [
                'success' => false,
                'message' => 'Tool already exits.'
            ];
        } else {
            return $response = [
                'success' => true,
                'message' => 'Tool data is ok to store.'
            ];
        }
    }

    
    

// =============================================================
public function destroystate($id)
{
    Log::info($id);
    Log::info('In TourDetailController->destroystate()');
    try {
        $access_token = $this->getCategoryAccessToken();
        $url = ''
            . Config::get('app.HOLIDAY_M_BASE_URL')
            . Config::get('app.DELETE_STATES_URL')
            .'/'
            .$id;
        Log::info('Got the access token from TourDetailController::getCategoryAccessToken(). Now fetching Classes!');
        Log::info('Deleting Url: ' . $url);
        $guzzleClient = new Client(); //GuzzleHttp\Client
        $params = [
            'headers' => [
                'Accept'     => 'application/json',
                'Authorization' => 'Bearer ' . $access_token
            ]
        ];
        $response = $guzzleClient->request('GET', $url, $params);
        Log::info('Got the response from Professions!');
        $json = json_decode($response->getBody()->getContents(), true);
        return $json;
    } catch (\Exception $e) {
        Log::info('There was some exception in TourDetailController->destroystate()');
        return $e->getResponse()->getStatusCode() . ': ' . $e->getMessage();
    }
}


public function updatestates(Request $request)
    {
        Log::info('In TourDetailController->updatestates()');
        $input = $request->all();
        Log::info($request);
  
        $validator = Validator::make($input, [
            'scountryId' => 'required|string|max:255',
            'sstateId' => 'required|string|max:255',
            'explain' => 'required',
           
        ]);
        if ($validator->fails()) {
            return $response = [
                'success' => false,
                'message' => $validator->errors()->first()
            ];
        }
        try {
            Log::info('Validating given Training data...');

            $validatorResponse = $this->validateNewStatestourlData($request);

            Log::info('$validatorResponse[success]: ' . $validatorResponse['success']);
            if ($validatorResponse['success']) {

                Log::info('Calling TourDetailController::getCategoryAccessToken()');
                $access_token = $this->getCategoryAccessToken();
                Log::info('Got the access token from TourDetailController::getCategoryAccessToken(). Now creating Class!');
                Log::info('HOLIDAY_M_BASE_URL . UPDATE_STATES_URL: ' . Config::get('app.HOLIDAY_M_BASE_URL') . Config::get('app.UPDATE_STATES_URL'));
                $multipartArray = [];
                // $training_logo = null;
                $state_logo = $request->file('state_logo');
                if ($state_logo != '') {
                    if (isset($input['state_logo'])) {
                        $state_logo = $input['state_logo'];
                    }
                    $multipartArray[] = [
                        'name'     => 'scountryId',
                        'contents' => $input['scountryId']
                    ];
                    $multipartArray[] = [
                        'name'     => 'sstateId',
                        'contents' => $input['sstateId']
                    ];
                    array_push($multipartArray, [
                        'name' => 'explain',
                        'contents' => $input['explain']
                    ]);

                    array_push($multipartArray, [
                        'name' => 'admin_hidden_id',
                        'contents' => $input['admin_hidden_id']
                    ]);
                    array_push($multipartArray, [
                        'name'     => 'state_logo',
                        'contents' => file_get_contents($state_logo),
                        'filename' => $state_logo->getClientOriginalName()
                    ]);
                } else {
                    Log::info('state  else part here');
                    $multipartArray[] = [
                        'name'     => 'scountryId',
                        'contents' => $input['scountryId']
                    ];
                    $multipartArray[] = [
                        'name'     => 'sstateId',
                        'contents' => $input['sstateId']
                    ];
                    array_push($multipartArray, [
                        'name' => 'explain',
                        'contents' => $input['explain']
                    ]);

                    array_push($multipartArray, [
                        'name' => 'admin_hidden_id',
                        'contents' => $input['admin_hidden_id']
                    ]);

                }
                $http = new Client(); //GuzzleHttp\Client
                $response = $http->post(
                    Config::get('app.HOLIDAY_M_BASE_URL') . Config::get('app.UPDATE_STATES_URL'),
                    [
                        'headers' => [
                            'Accept'     => 'application/json',
                            'Authorization' => 'Bearer ' . $access_token
                        ],
                        'multipart' => $multipartArray,

                    ]
                );

                $responseJson = json_decode($response->getBody()->getContents(), true);

                return response()->json($responseJson, 200);
            } else {
                return response()->json($responseJson, 422);
            }
        } catch (\Exception $e) {
            Log::info('There was some exception in TourDetailController->updatestates()');
            Log::info($e->getMessage());
            $response = [
                'success' => false,
                'data' => '',
                'message' => $e->getMessage()
            ];

            return response()->json($response, 500);
        }
        
    }


public function editstate($id)
{
    Log::info($id);
    Log::info('In TourDetailController->editstate()');
    try {
        $access_token = $this->getCategoryAccessToken();
        $url = ''
            . Config::get('app.HOLIDAY_M_BASE_URL')
            . Config::get('app.EDIT_STATE_URL')
            .'/'
            .$id;
        Log::info('Got the access token from TourDetailController::getCategoryAccessToken(). Now fetching Editstate!');
        Log::info('Editing Url: ' . $url);
        $guzzleClient = new Client(); //GuzzleHttp\Client
        $params = [
            'headers' => [
                'Accept'     => 'application/json',
                'Authorization' => 'Bearer ' . $access_token
            ]
        ];
        $response = $guzzleClient->request('GET', $url, $params);
        Log::info('Got the response from Professions!');
        $json = json_decode($response->getBody()->getContents(), true);
        return $json;
    } catch (\Exception $e) {
        Log::info('There was some exception in TourDetailController->editstate()');
        return $e->getResponse()->getStatusCode() . ': ' . $e->getMessage();
    }
}   

public function states($email)
{
    Log::info('In TourDetailController->states()');
    Log::info($email);
    try {
        $access_token = $this->getCategoryAccessToken();
        $url = ''
            . Config::get('app.HOLIDAY_M_BASE_URL')
            . Config::get('app.GET_MYSTATES_URL')
            . '/'
            . $email;
        Log::info('Got the access tokenmyy from TourDetailController::getCategoryAccessToken(). Now fetching states!');
        Log::info('before param');
        $guzzleClient = new Client(); //GuzzleHttp\Client
        $params = [
            'headers' => [
                'Accept'     => 'application/json',
                'Authorization' => 'Bearer ' . $access_token
            ]
        ];
        Log::info('after param');
        $response = $guzzleClient->request('GET', $url, $params);
        Log::info('Got the response from Trainings!');
        $json = json_decode($response->getBody()->getContents(), true);
        Log::info('Number of objects in response: ' . count($json['data']));
        return $json;
    } catch (\Exception $e) {
        Log::info('There was some myexception in TourDetailController->getCategoryAccessToken()');
        return $e->getResponse()->getStatusCode() . ': ' . $e->getMessage();
    }
}


    public function addstatestour(Request $request, $email)
    {
        Log::info('In Myaddstatestour->addstatestour()');
        $input = $request->all();
        Log::info($request);
    
        $validator = Validator::make($input, [
            'scountryId' => 'required|string',
            'sstateId' => 'required|string',
            'explain' => 'required|string',
           
               ]);
    
        if ($validator->fails()) {
            Log::info('if condition fails');
            return $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
        }
        Log::info('validation not working');
        try {
            Log::info('Validating given Training data...');
    
            $validatorResponse = $this->validateNewStatestourlData($request);
    
            Log::info('$validatorResponse[success]: ' . $validatorResponse['success']);
    
            if ($validatorResponse['success']) {
    
                Log::info('Calling TourDetailController::getStatesAccessToken()');
                $access_token = $this->getCategoryAccessToken();
                Log::info('Got the access token from TourDetailController::getStatesAccessToken(). Now creating States!');
                Log::info('HOLIDAY_M_BASE_URL . ADD_STATES_TOUR_URL: ' . Config::get('app.HOLIDAY_M_BASE_URL') . Config::get('app.ADD_STATES_TOUR_URL'));
                $multipartArray = [];
                $state_logo = null;
                if (isset($input['state_logo'])) {
                    $state_logo = $input['state_logo'];
                }
                $multipartArray[] = [
                    'name'     => 'scountryId',
                    'contents' => $input['scountryId']
                ];
                $multipartArray[] = [
                    'name'     => 'sstateId',
                    'contents' => $input['sstateId']
                ];
                array_push($multipartArray, [
                    'name' => 'explain',
                    'contents' => $input['explain']
                ]);  array_push($multipartArray, [
                    'name'     => 'admin_auth_id',
                    'contents' => $input['admin_auth_id']
                ]);
                array_push($multipartArray, [
                    'name'     => 'admin_auth_name',
                    'contents' => $input['admin_auth_name']
                ]);
                array_push($multipartArray, [
                    'name'     => 'admin_auth_email',
                    'contents' => $input['admin_auth_email']
                ]);
              
                array_push($multipartArray, [
                    'name'     => 'state_logo',
                    'contents' => file_get_contents($state_logo),
                    'filename' => $state_logo->getClientOriginalName()
                ]);
                $http = new Client(); //GuzzleHttp\Client
                $response = $http->post(
                    Config::get('app.HOLIDAY_M_BASE_URL') . Config::get('app.ADD_STATES_TOUR_URL'),
                    [
                        'headers' => [
                            'Accept'     => 'application/json',
                            'Authorization' => 'Bearer ' . $access_token
                        ],
                        'multipart' => $multipartArray,
    
                    ]
                );
    
                Log::info('Got the response from create States!');
                $responseJson = json_decode($response->getBody()->getContents(), true);
    
                return response()->json($responseJson, 200);
            } else {
                return response()->json($responseJson, 422);
            }
        } catch (\Exception $e) {
            Log::info('There was some exception In Myaddstatestour->addstatestour()');
            Log::info($e->getMessage());
            $response = [
                'success' => false,
                'data' => '',
                'message' => $e->getMessage()
            ];
            return response()->json($response, 500);
        }
    }
    
    
    
    private function validateNewStatestourlData(Request $request) {
           
        $input = $request->all();
        $validator = Validator::make($input, [
            'scountryId' => 'required|string',
            'sstateId' => 'required|string',
            'explain' =>'required|string',
    
        ]);
    
        if ($validator->fails()) {
            return $response = [
                'success' => false,
                'message' => 'States already exits.'
            ];
        } else {
            return $response = [
                'success' => true,
                'message' => 'States  data is ok to store.'
            ];
        }
    }
   

    public function getcategory()
    {
      
        Log::info('In TourDetailController->getcategory()');
        try {
            $access_token = $this->getCategoryAccessToken();
            $url = ''
                . Config::get('app.HOLIDAY_M_BASE_URL')
                . Config::get('app.GET_CATEGORY_URL');
               
            Log::info('Got the access token from TourDetailController::getCategoryAccessToken(). Now fetching Classes!');
            Log::info('Deleting Url: ' . $url);
            $guzzleClient = new Client(); //GuzzleHttp\Client
            $params = [
                'headers' => [
                    'Accept'     => 'application/json',
                    'Authorization' => 'Bearer ' . $access_token
                ]
            ];
            $response = $guzzleClient->request('GET', $url, $params);
            Log::info('Got the response from Professions!');
            $json = json_decode($response->getBody()->getContents(), true);
            return $json;
        } catch (\Exception $e) {
            Log::info('There was some exception in TourDetailController->detailinternal()');
            return $e->getResponse()->getStatusCode() . ': ' . $e->getMessage();
        }
    }






}
