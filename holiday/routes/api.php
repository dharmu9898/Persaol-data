<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Auth::routes();

Route::get('mytrips', 'Api\mytripsController@index');
Route::get('mycountry', 'Api\mytripsController@country');
Route::get('selectcountry', 'Api\mytripsController@mycountry');
Route::post('mystate', 'Api\mytripsController@state');
Route::post('mycity', 'Api\mytripsController@city');
Route::get('myevents', 'Api\mytripsController@event');
Route::get('myeventscat', 'Api\mytripsController@eventcat');
Route::get('mytripscat', 'Api\mytripsController@tripcat');
Route::resource('location', 'Api\AdminLocationController')->except([
    'create'

]);

Route::group(['prefix'=>'v1/j', 'middleware' => 'client_credentials'
			], function(){
				Route::get('gettourcategory/{email}', 'Admin\AdminTPController@categories');
				Route::post('/addtourcategory', 'Admin\AdminTPController@store');
				Route::get('/edittripcategory/{id}', 'Admin\AdminTPController@edit');
                Route::get('/deletetourcategory/{id}', 'Admin\AdminTPController@destroy');
                Route::get('/editscategory/{id}', 'Admin\AdminTPController@edits');
                Route::post('/updatecategory', 'Admin\AdminTPController@update');
                Route::get('/detailcategory/{id}', 'Admin\AdminTPController@detail');
				Route::post('/addinternational', 'Admin\AdminTPController@storeinternational');
				Route::get('gettourinternational/{email}', 'Admin\AdminTPController@international');
				Route::get('/editinterna/{id}', 'Admin\AdminTPController@editinter');
				Route::post('/updateinter', 'Admin\AdminTPController@updateinterna');
				Route::get('/deletetourinter/{id}', 'Admin\AdminTPController@destroyinter');
				Route::get('/detailinternal/{id}', 'Admin\AdminTPController@detailinter');
				Route::post('/addsattes', 'Admin\AdminTPController@storestates');
				Route::get('gettoursattes/{email}', 'Admin\AdminTPController@sattes');
				Route::get('/editstate/{id}', 'Admin\AdminTPController@editstates');
				Route::post('/updatestate', 'Admin\AdminTPController@updatestates');
				Route::get('/deletetourstate/{id}', 'Admin\AdminTPController@destroystates');
				Route::get('/detailstates/{id}', 'Admin\AdminTPController@detailstate');
				Route::post('/addcity', 'Admin\AdminTPController@storecity');
				Route::get('gettourcity/{email}', 'Admin\AdminTPController@city');
				Route::get('/editcity/{id}', 'Admin\AdminTPController@editcitys');
				Route::post('/updatecity', 'Admin\AdminTPController@updatecitys');
				Route::get('/deletetourcity/{id}', 'Admin\AdminTPController@destroycity');
				Route::get('/detailcity/{id}', 'Admin\AdminTPController@detailcity');
				Route::get('/getcategory/{id}', 'Admin\AdminTPController@getcategory');
				Route::get('gettripdetail/{email}', 'Admin\AdminTPController@tripdetail');
				Route::get('getusers/{email}', 'Admin\AdminTPController@usersdetail');
				Route::get('/detailsusers/{id}', 'Admin\AdminTPController@detailuser');
				Route::get('/alldetailsusers/{id}', 'Admin\AdminTPController@alldetailuser');
				
				Route::get('loginmy', 'Admin\AdminTPController@loginmy')->name('loginmy');

				Route::get('/pubtrip/{id}', 'Admin\AdminTPController@publishtrip');
				Route::get('/unpubtrip/{id}', 'Admin\AdminTPController@unpublishtrip');
				Route::post('/repubtrip', 'Admin\AdminTPController@repubtrip');
				
				Route::post('/addtripdetail', 'Admin\AdminTPController@storetrip');
				Route::get('iternarydetail/{email}', 'Admin\AdminTPController@iternarydetail');
				Route::post('/additernarydetail', 'Admin\AdminTPController@storeiternary');
				Route::get('imagedetail/{email}', 'Admin\AdminTPController@imagesdetail');
				Route::post('/addimagedetail', 'Admin\AdminTPController@storeimages');
				Route::post('/permittrip', 'Admin\AdminTPController@storepermission');

				Route::post('/userpermittrip', 'Admin\AdminTPController@storeupermission');
				Route::get('gettripsdetail/{email}', 'Admin\AdminTPController@alltripdetail');

				Route::get('getuserdetail/{email}', 'Admin\AdminTPController@alluserdetail');

				Route::get('/destroytrip/{id}', 'Admin\AdminTPController@destroytrip');
				Route::get('/detailstrip/{id}', 'Admin\AdminTPController@detailstrip');
				Route::get('/edittrip/{id}', 'Admin\AdminTPController@edittrips');
				Route::post('/updatetrips', 'Admin\AdminTPController@tripupdate');
				Route::get('/destroyiternary/{id}', 'Admin\AdminTPController@destroyiter');
				Route::get('/detailsiternary/{id}', 'Admin\AdminTPController@detailsiternary');
				Route::get('/edititernary/{id}', 'Admin\AdminTPController@edititernary');
                Route::post('/updateiternary', 'Admin\AdminTPController@iternaryupdate');
                Route::get('/destroyimage/{id}', 'Admin\AdminTPController@destroyimages');
                Route::get('/detailsimage/{id}', 'Admin\AdminTPController@detailsimages');
                Route::get('/editimage/{id}', 'Admin\AdminTPController@editimages');
                Route::post('/updateimage', 'Admin\AdminTPController@imageupdate');
				Route::get('getmail/{email}', 'Admin\AdminTPController@getallmail');
				Route::get('getsublist/{email}', 'Admin\AdminTPController@subdetail');
				Route::post('/addsublists', 'Admin\AdminTPController@storesubscribelist');
				Route::get('iternarydays/{iternary_title}', 'Admin\AdminTPController@getiternary');
				Route::post('/addtripcategory', 'Admin\AdminTPController@storecategory');

				Route::get('/mycategory', 'Admin\AdminTPController@mycategory');
				Route::post('/addmytripdetail', 'Admin\AdminTPController@addmytripdetail');
				Route::get('getmytripdetail/{email}', 'Admin\AdminTPController@getmytripdetail');
				Route::post('/addmytriptiming', 'Admin\AdminTPController@addmytriptiming');
				Route::get('/mycountry', 'Admin\AdminTPController@mycountry');
				Route::post('/addmytriplocation', 'Admin\AdminTPController@addmytriplocation');
				Route::post('/addmytrippayment', 'Admin\AdminTPController@addmytrippayment');

				Route::post('/addmytripiternary', 'Admin\AdminTPController@addmytripiternary');
				Route::post('/delmytripiternary', 'Admin\AdminTPController@delmytripiternary');
				Route::post('/addmytripmedia', 'Admin\AdminTPController@addmytripmedia');
				Route::post('/addmytripticket', 'Admin\AdminTPController@addmytripticket');

				Route::post('/delmytriptickets', 'Admin\AdminTPController@delmytriptickets');
				
				
			});



Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
