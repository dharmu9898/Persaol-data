<?php

namespace Classiebit\Eventmie\Http\Controllers;
use App\Http\Controllers\Controller; 
use Facades\Classiebit\Eventmie\Eventmie;

use Illuminate\Http\Request;

use GuzzleHttp\Client as Client;
use Illuminate\Support\Facades\Log;
use Config;
use Validator;
use Illuminate\Validation\Rule;
use Exception;
use Auth;
use Redirect;
use File;

use Illuminate\Http\Response;
use Intervention\Image\Facades\Image;






class AdminController extends Controller
{
    private static function getCategoryAccessToken() {
        Log::info('In AdminController->getCategoryAccessToken()');
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
            Log::info('There is some exception in AdminController->getCategoryAccessToken()');
            return $e->getResponse()->getStatusCode() . ': ' . $e->getMessage();
        }
    }

    // =============================================================
    public function categories($email)
    {
        Log::info('In AdminController->categories()');
        Log::info($email);
        try {
            $access_token = $this->getCategoryAccessToken();
            $url = ''
                . Config::get('app.HOLIDAY_M_BASE_URL')
                . Config::get('app.GET_MYHOLIDAY_URL')
                . '/'
                . $email;
            Log::info('Got the access tokenmyy from AdminController::getCategoryAccessToken(). Now fetching classes!');
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
            Log::info('There was some myexception in AdminController->getCategoryAccessToken()');
            return $e->getResponse()->getStatusCode() . ': ' . $e->getMessage();
        }
    }


    public function profiles($email)
    {
        Log::info('In AdminController->profiles()');
        Log::info($email);
        try {
            $access_token = $this->getCategoryAccessToken();
            $url = ''
                . Config::get('app.HOLIDAY_M_BASE_URL')
                . Config::get('app.GET_MYPROFILE_URL')
                . '/'
                . $email;
            Log::info('Got the access tokenmyy from AdminController::getCategoryAccessToken(). Now fetching classes!');
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
            Log::info('There was some myexception in AdminController->getCategoryAccessToken()');
            return $e->getResponse()->getStatusCode() . ': ' . $e->getMessage();
        }
    }
    public function getuser(Request $request, $email)
{ 
    Log::info('In AdminController->getuser()');
    Log::info($email);
    $page = $request->get('page');
    $query = $request->get('query');
    Log::info($page);
    try {
        $access_token = $this->getCategoryAccessToken();
        $url = ''
            . Config::get('app.HOLIDAY_M_BASE_URL')
            . Config::get('app.GET_ALLHOLIDAYUSER_URL')
            .'/'
            . $email
            . '?page='
            . $page
            . '&query='
            . $query;
        Log::info('Got the access token from AdminController::getCategoryAccessToken(). Now fetching User!');
        Log::info('User Url: ' . $url);
        $guzzleClient = new Client(); 
        Log::info('aa raha User Url: ' . $url);
        $params = [
            'headers' => [
                'Accept'     => 'application/json',
                'Authorization' => 'Bearer ' . $access_token
            ],
        ];
        Log::info('secound User Url: ' . $url);
        $response = $guzzleClient->request('GET', $url, $params);
        Log::info('Got the response from Tools!');
        $json = json_decode($response->getBody()->getContents(), true);
        Log::info('Number of objects in response: ' . count($json['data']));
        return $json;
    } catch (\Exception $e) {
        Log::info('There was some exception in tak aa raha hai AdminController->getCategoryAccessToken()');
        return $e->getResponse()->getStatusCode() . ': ' . $e->getMessage();
    }
}

public function addupermission(Request $request, $email)
{
    Log::info('In addupermission->addupermission()');
    $input = $request->all();
    Log::info($request);
    $validator = Validator::make($input, [
        'Permission' => 'required',
        'Perm' => 'required',
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
        $validatorResponse = $this->validateNewuPermissiontourlData($request);
      Log::info('$validatorResponse[success]: ' . $validatorResponse['success']);
      if ($validatorResponse['success']) {
          $access_token = $this->getCategoryAccessToken();
          $permissiontrip = $request->Perm;   
          $multipartArray = [];
          log::info('in token ll');
          $contiu=count($permissiontrip);
          log::info($contiu);
          for($count = 0; $count < count($permissiontrip); $count++)
          {
     $multipartArray[] = [
        'name'     => 'Perm',
        'contents' => $permissiontrip[$count]
                ];
          array_push($multipartArray, [
            'name'     => 'Permission',
            'contents' => $permissiontrip[$count]
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
      $http = new Client(); //GuzzleHttp\Client 
          $response = $http->post(
              Config::get('app.HOLIDAY_M_BASE_URL') . Config::get('app.PERMIT_USER_URL'),
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
  }
    catch (\Exception $e) {
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

private function validateNewuPermissiontourlData(Request $request) {
    $input = $request->all();
    $validator = Validator::make($input, [
        'Permission' => 'required',
        'Perm' => 'required',
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
    public function editcat($id)
    {
        Log::info($id);
        Log::info('In AdminController->editcat()');
        try {
            $access_token = $this->getCategoryAccessToken();
            $url = ''
                . Config::get('app.HOLIDAY_M_BASE_URL')
                . Config::get('app.EDIT_CAREGORY_URL')
                .'/'
                .$id;
            Log::info('Got the access token from AdminController::getCategoryAccessToken(). Now fetching Classes!');
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
            Log::info('There was some exception in AdminController->editcat()');
            return $e->getResponse()->getStatusCode() . ': ' . $e->getMessage();
        }
    }   

    public function destroy($id)
    {
        Log::info($id);
        Log::info('In AdminController->destroy()');
        try {
            $access_token = $this->getCategoryAccessToken();
            $url = ''
                . Config::get('app.HOLIDAY_M_BASE_URL')
                . Config::get('app.DELETE_CAREGORY_URL')
                .'/'
                .$id;
            Log::info('Got the access token from AdminController::getCategoryAccessToken(). Now fetching Classes!');
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
            Log::info('There was some exception in AdminController->destroy()');
            return $e->getResponse()->getStatusCode() . ': ' . $e->getMessage();
        }
    }

    public function updatecategories(Request $request)
    {
        Log::info('In AdminController->updatecategories()');
        $input = $request->all();
        Log::info($request);

        $validator = Validator::make($input, [
            'category_name' => 'required|string|max:255',
            'description' => 'required',
           
        ]);
        if ($validator->fails()) {
            return $response = [
                'success' => false,
                'message' => $validator->errors()->first()
            ];
        }
        try {
            Log::info('Validating given Training data...');

            $validatorResponse = $this->validateNewToolData($request);

            Log::info('$validatorResponse[success]: ' . $validatorResponse['success']);
            if ($validatorResponse['success']) {

                Log::info('Calling MyTrainingController::getCategoryAccessToken()');
                $access_token = $this->getCategoryAccessToken();
                Log::info('Got the access token from MyTrainingController::getCategoryAccessToken(). Now creating Class!');
                Log::info('HOLIDAY_M_BASE_URL . UPDATE_CATEGORY_URL: ' . Config::get('app.HOLIDAY_M_BASE_URL') . Config::get('app.UPDATE_CATEGORY_URL'));
                $multipartArray = [];
                // $training_logo = null;
                $category_logo = $request->file('category_logo');
                if ($category_logo != '') {
                    if (isset($input['category_logo'])) {
                        $category_logo = $input['category_logo'];
                    }
                    $multipartArray[] = [
                        'name'     => 'category_name',
                        'contents' => $input['category_name']
                    ];
                    array_push($multipartArray, [
                        'name' => 'description',
                        'contents' => $input['description']
                    ]);

                    array_push($multipartArray, [
                        'name' => 'admin_hidden_id',
                        'contents' => $input['admin_hidden_id']
                    ]);

                  
                    array_push($multipartArray, [
                        'name'     => 'category_logo',
                        'contents' => file_get_contents($category_logo),
                        'filename' => $category_logo->getClientOriginalName()
                    ]);
                } else {
                    Log::info('i m else part here');
                    $multipartArray[] = [
                        'name'     => 'category_name',
                        'contents' => $input['category_name']
                    ];
                    array_push($multipartArray, [
                        'name' => 'description',
                        'contents' => $input['description']
                    ]);

                    array_push($multipartArray, [
                        'name' => 'admin_hidden_id',
                        'contents' => $input['admin_hidden_id']
                    ]);

                }
                $http = new Client(); //GuzzleHttp\Client
                $response = $http->post(
                    Config::get('app.HOLIDAY_M_BASE_URL') . Config::get('app.UPDATE_CATEGORY_URL'),
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
            Log::info('There was some exception in AdminController->updatecategories()');
            Log::info($e->getMessage());
            $response = [
                'success' => false,
                'data' => '',
                'message' => $e->getMessage()
            ];

            return response()->json($response, 500);
        }
    }

    public function detailcategories($id)
    {
        Log::info($id);
        Log::info('In AdminController->detailcategories()');
        try {
            $access_token = $this->getCategoryAccessToken();
            $url = ''
                . Config::get('app.HOLIDAY_M_BASE_URL')
                . Config::get('app.DETAIL_CAREGORY_URL')
                .'/'
                .$id;
            Log::info('Got the access token from AdminController::getCategoryAccessToken(). Now fetching Classes!');
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
            Log::info('There was some exception in AdminController->detailcategories()');
            return $e->getResponse()->getStatusCode() . ': ' . $e->getMessage();
        }
    }

public function addcategories(Request $request, $email)
{
    Log::info('In Myaddcategories->addcategories()');
    $input = $request->all();
    Log::info($request);

    $validator = Validator::make($input, [
        'category_name' => 'required|string|max:255',
        'description' => 'required',
       
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

        $validatorResponse = $this->validateNewToolData($request);

        Log::info('$validatorResponse[success]: ' . $validatorResponse['success']);

        if ($validatorResponse['success']) {

            Log::info('Calling AdminController::getCategoryAccessToken()');
            $access_token = $this->getCategoryAccessToken();
            Log::info('Got the access token from AdminController::getCategoryAccessToken(). Now creating Class!');
            Log::info('HOLIDAY_M_BASE_URL . ADD_MYHOLIDAY_URL: ' . Config::get('app.HOLIDAY_M_BASE_URL') . Config::get('app.ADD_MYHOLIDAY_URL'));
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
                'name' => 'description',
                'contents' => $input['description']
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
                'name'     => 'category_logo',
                'contents' => file_get_contents($category_logo),
                'filename' => $category_logo->getClientOriginalName()
            ]);
            $http = new Client(); 
            $response = $http->post(
                Config::get('app.HOLIDAY_M_BASE_URL') . Config::get('app.ADD_MYHOLIDAY_URL'),
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
        Log::info('There was some exception in AdminController->addcategories()');
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
        Log::info('In AdminController->detailinternal()');
        try {
            $access_token = $this->getCategoryAccessToken();
            $url = ''
                . Config::get('app.HOLIDAY_M_BASE_URL')
                . Config::get('app.DETAIL_INTERNAL_URL')
                .'/'
                .$id;
            Log::info('Got the access token from AdminController::getCategoryAccessToken(). Now fetching Classes!');
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
            Log::info('There was some exception in AdminController->detailinternal()');
            return $e->getResponse()->getStatusCode() . ': ' . $e->getMessage();
        }
    }


public function destroyinter($id)
{
    Log::info($id);
    Log::info('In AdminController->destroyinter()');
    try {
        $access_token = $this->getCategoryAccessToken();
        $url = ''
            . Config::get('app.HOLIDAY_M_BASE_URL')
            . Config::get('app.DELETE_INTERNAL_URL')
            .'/'
            .$id;
        Log::info('Got the access token from AdminController::getCategoryAccessToken(). Now fetching Classes!');
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
        Log::info('There was some exception in AdminController->destroyinter()');
        return $e->getResponse()->getStatusCode() . ': ' . $e->getMessage();
    }
}



public function editinter($id)
{
    Log::info($id);
    Log::info('In AdminController->editinter()');
    try {
        $access_token = $this->getCategoryAccessToken();
        $url = ''
            . Config::get('app.HOLIDAY_M_BASE_URL')
            . Config::get('app.EDIT_INTERNA_URL')
            .'/'
            .$id;
        Log::info('Got the access token from AdminController::getCategoryAccessToken(). Now fetching Editinter!');
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
        Log::info('There was some exception in AdminController->editinter()');
        return $e->getResponse()->getStatusCode() . ': ' . $e->getMessage();
    }
}   

public function updateinterna(Request $request)
    {
        Log::info('In AdminController->updateinterna()');
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

                Log::info('Calling MyTrainingController::getCategoryAccessToken()');
                $access_token = $this->getCategoryAccessToken();
                Log::info('Got the access token from MyTrainingController::getCategoryAccessToken(). Now creating Class!');
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
            Log::info('There was some exception in AdminController->updatecategories()');
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
        Log::info('In AdminController->detailstates()');
        try {
            $access_token = $this->getCategoryAccessToken();
            $url = ''
                . Config::get('app.HOLIDAY_M_BASE_URL')
                . Config::get('app.DETAIL_STATES_URL')
                .'/'
                .$id;
            Log::info('Got the access token from AdminController::getCategoryAccessToken(). Now fetching Classes!');
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
            Log::info('There was some exception in AdminController->detailstates()');
            return $e->getResponse()->getStatusCode() . ': ' . $e->getMessage();
        }
    }



public function international($email)
{
    Log::info('In AdminController->international()');
    Log::info($email);
    try {
        $access_token = $this->getCategoryAccessToken();
        $url = ''
            . Config::get('app.HOLIDAY_M_BASE_URL')
            . Config::get('app.GET_MYINTERNATIONAL_URL')
            . '/'
            . $email;
        Log::info('Got the access tokenmyy from AdminController::getCategoryAccessToken(). Now fetching international2!');
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
        Log::info('There was some myexception in AdminController->getCategoryAccessToken()');
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

            Log::info('Calling AdminController::getInternationalAccessToken()');
            $access_token = $this->getCategoryAccessToken();
            Log::info('Got the access token from AdminController::getInternationalAccessToken(). Now creating International!');
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

public function gettrip(Request $request, $email)
{ 
    Log::info('In AdminController->gettrip()');
    Log::info($email);
    $page = $request->get('page');
    $query = $request->get('query');
    Log::info($page);
    try {
        $access_token = $this->getCategoryAccessToken();
        $url = ''
            . Config::get('app.HOLIDAY_M_BASE_URL')
            . Config::get('app.GET_ALLHOLIDAYTRIP_URL')
            .'/'
            . $email
            . '?page='
            . $page
            . '&query='
            . $query;
        Log::info('Got the access token from AdminController::getCategoryAccessToken(). Now fetching Commands!');
        Log::info('Command Url: ' . $url);
        $guzzleClient = new Client(); 
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
        Log::info('There was some exception in AdminController->getCategoryAccessToken()');
        return $e->getResponse()->getStatusCode() . ': ' . $e->getMessage();
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


    private function validateNewToolData(Request $request) {
       
        $input = $request->all();
        $validator = Validator::make($input, [
            'category_name' => 'required|string',
            'description' =>'required|string',

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
    Log::info('In AdminController->destroystate()');
    try {
        $access_token = $this->getCategoryAccessToken();
        $url = ''
            . Config::get('app.HOLIDAY_M_BASE_URL')
            . Config::get('app.DELETE_STATES_URL')
            .'/'
            .$id;
        Log::info('Got the access token from AdminController::getCategoryAccessToken(). Now fetching Classes!');
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
        Log::info('There was some exception in AdminController->destroystate()');
        return $e->getResponse()->getStatusCode() . ': ' . $e->getMessage();
    }
}


public function updatestates(Request $request)
    {
        Log::info('In AdminController->updatestates()');
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

                Log::info('Calling MyTrainingController::getCategoryAccessToken()');
                $access_token = $this->getCategoryAccessToken();
                Log::info('Got the access token from MyTrainingController::getCategoryAccessToken(). Now creating Class!');
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
            Log::info('There was some exception in AdminController->updatestates()');
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
    Log::info('In AdminController->editstate()');
    try {
        $access_token = $this->getCategoryAccessToken();
        $url = ''
            . Config::get('app.HOLIDAY_M_BASE_URL')
            . Config::get('app.EDIT_STATE_URL')
            .'/'
            .$id;
        Log::info('Got the access token from AdminController::getCategoryAccessToken(). Now fetching Editstate!');
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
        Log::info('There was some exception in AdminController->editstate()');
        return $e->getResponse()->getStatusCode() . ': ' . $e->getMessage();
    }
}   

public function states($email)
{
    Log::info('In AdminController->states()');
    Log::info($email);
    try {
        $access_token = $this->getCategoryAccessToken();
        $url = ''
            . Config::get('app.HOLIDAY_M_BASE_URL')
            . Config::get('app.GET_MYSTATES_URL')
            . '/'
            . $email;
        Log::info('Got the access tokenmyy from AdminController::getCategoryAccessToken(). Now fetching states!');
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
        Log::info('There was some myexception in AdminController->getCategoryAccessToken()');
        return $e->getResponse()->getStatusCode() . ': ' . $e->getMessage();
    }
}




public function addpermission(Request $request, $email)
{
    Log::info('In addpermission->addpermission()');
    $input = $request->all();
    Log::info($request);

    $validator = Validator::make($input, [
        'Permission' => 'required',
        'Perm' => 'required',
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
        $validatorResponse = $this->validateNewPermissiontourlData($request);
      Log::info('$validatorResponse[success]: ' . $validatorResponse['success']);
      if ($validatorResponse['success']) {
          $access_token = $this->getCategoryAccessToken();
          $permissiontrip = $request->Perm;   
          $multipartArray = [];
          log::info('in token ll');
          $contiu=count($permissiontrip);
          log::info($contiu);
          for($count = 0; $count < count($permissiontrip); $count++)
          {
     $multipartArray[] = [
        'name'     => 'Perm',
        'contents' => $permissiontrip[$count]
                ];
          array_push($multipartArray, [
            'name'     => 'Permission',
            'contents' => $permissiontrip[$count]
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
      $http = new Client(); //GuzzleHttp\Client
          $response = $http->post(
              Config::get('app.HOLIDAY_M_BASE_URL') . Config::get('app.PERMIT_TRIP_URL'),
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
  }
    catch (\Exception $e) {
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

private function validateNewPermissiontourlData(Request $request) {
       
    $input = $request->all();
    $validator = Validator::make($input, [
        'Permission' => 'required',
        'Perm' => 'required',
        
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
    
                Log::info('Calling AdminController::getCategoryAccessToken()');
                $access_token = $this->getCategoryAccessToken();
                Log::info('Got the access token from AdminController::getCategoryAccessToken(). Now creating States!');
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
   
// =============================================================
public function detailcity($id)
    {
        Log::info($id);
        Log::info('In AdminController->detailcity()');
        try {
            $access_token = $this->getCategoryAccessToken();
            $url = ''
                . Config::get('app.HOLIDAY_M_BASE_URL')
                . Config::get('app.DETAIL_CITY_URL')
                .'/'
                .$id;
            Log::info('Got the access token from AdminController::getCategoryAccessToken(). Now fetching City!');
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
            Log::info('There was some exception in AdminController->detailcity()');
            return $e->getResponse()->getStatusCode() . ': ' . $e->getMessage();
        }
    }


public function destroycity($id)
{
    Log::info($id);
    Log::info('In AdminController->destroycity()');
    try {
        $access_token = $this->getCategoryAccessToken();
        $url = ''
            . Config::get('app.HOLIDAY_M_BASE_URL')
            . Config::get('app.DELETE_CITY_URL')
            .'/'
            .$id;
        Log::info('Got the access token from AdminController::getCategoryAccessToken(). Now fetching City!');
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
        Log::info('There was some exception in AdminController->destroycity()');
        return $e->getResponse()->getStatusCode() . ': ' . $e->getMessage();
    }
}


public function updatecitys(Request $request)
    {
        Log::info('In AdminController->updatecitys()');
        $input = $request->all();
        Log::info($request);
       

        $validator = Validator::make($input, [
            'countryId1' => 'required|string|max:255',
            'stateId' => 'required|string|max:255',
            'famous_city_name' => 'required|string|max:255',
            'description1' => 'required',
           
        ]);
        if ($validator->fails()) {
            return $response = [
                'success' => false,
                'message' => $validator->errors()->first()
            ];
        }
        try {
            Log::info('Validating given Training data...');

            $validatorResponse = $this->validateNewCitytourlData($request);

            Log::info('$validatorResponse[success]: ' . $validatorResponse['success']);
            if ($validatorResponse['success']) {

                Log::info('Calling MyTrainingController::getCategoryAccessToken()');
                $access_token = $this->getCategoryAccessToken();
                Log::info('Got the access token from MyTrainingController::getCategoryAccessToken(). Now creating Class!');
                Log::info('HOLIDAY_M_BASE_URL . UPDATE_CITY_URL: ' . Config::get('app.HOLIDAY_M_BASE_URL') . Config::get('app.UPDATE_CITY_URL'));
                $multipartArray = [];
                // $training_logo = null;
                $city_logo = $request->file('city_logo');
                if ($city_logo != '') {
                    if (isset($input['city_logo'])) {
                        $city_logo = $input['city_logo'];
                    }
                    $multipartArray[] = [
                        'name'     => 'countryId1',
                        'contents' => $input['countryId1']
                    ];
                    $multipartArray[] = [
                        'name'     => 'stateId',
                        'contents' => $input['stateId']
                    ];
                    $multipartArray[] = [
                        'name'     => 'famous_city_name',
                        'contents' => $input['famous_city_name']
                    ];
                    array_push($multipartArray, [
                        'name' => 'description1',
                        'contents' => $input['description1']
                    ]);

                    array_push($multipartArray, [
                        'name' => 'admin_hidden_id',
                        'contents' => $input['admin_hidden_id']
                    ]);
                    array_push($multipartArray, [
                        'name'     => 'city_logo',
                        'contents' => file_get_contents($city_logo),
                        'filename' => $city_logo->getClientOriginalName()
                    ]);
                } else {
                    Log::info('state  else part here');
                    $multipartArray[] = [
                        'name'     => 'countryId1',
                        'contents' => $input['countryId1']
                    ];
                    $multipartArray[] = [
                        'name'     => 'stateId',
                        'contents' => $input['stateId']
                    ];
                    $multipartArray[] = [
                        'name'     => 'famous_city_name',
                        'contents' => $input['famous_city_name']
                    ];
                    array_push($multipartArray, [
                        'name' => 'description1',
                        'contents' => $input['description1']
                    ]);

                    array_push($multipartArray, [
                        'name' => 'admin_hidden_id',
                        'contents' => $input['admin_hidden_id']
                    ]);

                }
                $http = new Client(); //GuzzleHttp\Client
                $response = $http->post(
                    Config::get('app.HOLIDAY_M_BASE_URL') . Config::get('app.UPDATE_CITY_URL'),
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
            Log::info('There was some exception in AdminController->updatecitys()');
            Log::info($e->getMessage());
            $response = [
                'success' => false,
                'data' => '',
                'message' => $e->getMessage()
            ];

            return response()->json($response, 500);
        }
    }
    public static function getvalueall() 
    
    {
        Log::info('In AdminController->getvalueall()');
        try {
            Log::info('HOLIDAY_M_BASE_URL . HOLIDAY_CATEGORY_GET: ' . Config::get('app.HOLIDAY_M_BASE_URL') . Config::get('app.HOLIDAY_CATEGORY_GET'));
            Log::info('Calling AdminController->getCatManagementAccessToken()');
            $access_token = AdminController::getCategoryAccessToken();
            Log::info('Got the access token from AdminController->getCatManagementAccessToken(). Now fetching Category!');
            $http = new Client(); //GuzzleHttp\Client
            $response = $http->get(
                Config::get('app.HOLIDAY_M_BASE_URL') . Config::get('app.HOLIDAY_CATEGORY_GET').'/'.Auth::user()->id,
                [
                    'headers' => [
                        'Accept'     => 'application/json',
                        'Authorization' => 'Bearer ' . $access_token
                    ]
                ]
            );
            Log::info('Got the response from Commands fgbdb!');
            $json = json_decode($response->getBody()->getContents(), true);
         return $json;
            Log::info('Number of objects in category response: ' . count($json['data']));
            return $json['data'];
        } catch (\Exception $e) {
            Log::info('There was some exception in AdminController->getvalueall()');
            return $e->getResponse()->getStatusCode() . ': ' . $e->getMessage();
        }
    }
public function editcity($id)
{
    Log::info($id);
    Log::info('In AdminController->editcity()');
    try {
        $access_token = $this->getCategoryAccessToken();
        $url = ''
            . Config::get('app.HOLIDAY_M_BASE_URL')
            . Config::get('app.EDIT_CITY_URL')
            .'/'
            .$id;
        Log::info('Got the access token from AdminController::getCategoryAccessToken(). Now fetching editcity!');
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
        Log::info('There was some exception in AdminController->editcity()');
        return $e->getResponse()->getStatusCode() . ': ' . $e->getMessage();
    }
}   


public function city($email)
{
    Log::info('In AdminController->city()');
    Log::info($email);
    try {
        $access_token = $this->getCategoryAccessToken();
        $url = ''
            . Config::get('app.HOLIDAY_M_BASE_URL')
            . Config::get('app.GET_MYCITY_URL')
            . '/'
            . $email;
        Log::info('Got the access tokenmyy from AdminController::getCategoryAccessToken(). Now fetching City!');
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
        Log::info('There was some myexception in AdminController->getCategoryAccessToken()');
        return $e->getResponse()->getStatusCode() . ': ' . $e->getMessage();
    }
}



    public function addcitytour(Request $request, $email)
    {
        Log::info('In Myaddcitytour->addcitytour()');
        $input = $request->all();
        Log::info($request);
    
        $validator = Validator::make($input, [
            'countryId1' => 'required|string',
            'stateId' => 'required|string',
            'famous_city_name' => 'required|string',
            'description1' => 'required|string',
           
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
    
            $validatorResponse = $this->validateNewCitytourlData($request);
    
            Log::info('$validatorResponse[success]: ' . $validatorResponse['success']);
    
            if ($validatorResponse['success']) {
    
                Log::info('Calling AdminController::getCategoryAccessToken()');
                $access_token = $this->getCategoryAccessToken();
                Log::info('Got the access token from AdminController::getCityAccessToken(). Now creating City!');
                Log::info('HOLIDAY_M_BASE_URL . ADD_CITY_TOUR_URL: ' . Config::get('app.HOLIDAY_M_BASE_URL') . Config::get('app.ADD_CITY_TOUR_URL'));
                $multipartArray = [];
                $city_logo = null;
                if (isset($input['city_logo'])) {
                    $city_logo = $input['city_logo'];
                }
                $multipartArray[] = [
                    'name'     => 'countryId1',
                    'contents' => $input['countryId1']
                ];
                $multipartArray[] = [
                    'name'     => 'stateId',
                    'contents' => $input['stateId']
                ];
                $multipartArray[] = [
                    'name'     => 'famous_city_name',
                    'contents' => $input['famous_city_name']
                ];
                array_push($multipartArray, [
                    'name' => 'description1',
                    'contents' => $input['description1']
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
                    'name'     => 'city_logo',
                    'contents' => file_get_contents($city_logo),
                    'filename' => $city_logo->getClientOriginalName()
                ]);
                $http = new Client(); //GuzzleHttp\Client
                $response = $http->post(
                    Config::get('app.HOLIDAY_M_BASE_URL') . Config::get('app.ADD_CITY_TOUR_URL'),
                    [
                        'headers' => [
                            'Accept'     => 'application/json',
                            'Authorization' => 'Bearer ' . $access_token
                        ],
                        'multipart' => $multipartArray,
    
                    ]
                );
    
                Log::info('Got the response from create City!');
                $responseJson = json_decode($response->getBody()->getContents(), true);
    
                return response()->json($responseJson, 200);
            } else {
                return response()->json($responseJson, 422);
            }
        } catch (\Exception $e) {
            Log::info('There was some exception In Myaddcitytour->addcitytour()');
            Log::info($e->getMessage());
            $response = [
                'success' => false,
                'data' => '',
                'message' => $e->getMessage()
            ];
            return response()->json($response, 500);
        }
    }
    
    
    
    private function validateNewCitytourlData(Request $request) {
           
        $input = $request->all();
        $validator = Validator::make($input, [
            'countryId1' => 'required|string',
            'stateId' => 'required|string',
            'famous_city_name' => 'required|string',
            'description1' =>'required|string',
    
        ]);
    
        if ($validator->fails()) {
            return $response = [
                'success' => false,
                'message' => 'City already exits.'
            ];
        } else {
            return $response = [
                'success' => true,
                'message' => 'City  data is ok to store.'
            ];
        }
    }
   

}