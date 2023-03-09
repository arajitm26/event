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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix'=>'v1'],function () {
     
     //testing purpose 
     
     Route::get('/register', "ApiController@register");
     Route::get('/dompdf', "ApiController@dompdf");
     Route::get('/set_jwt', "ApiController@set_jwt");
     Route::get('/get_jwt', "ApiController@get_jwt");

     //testing purpose end

     Route::get('/login/{phone}', "ApiController@login");
     
     Route::post('verify-otp', "ApiController@verify_otp");


    
    Route::group(['middleware'=>['ckeckToken']],function (){
    
     Route::get('/get_event_list', "ApiController@get_event_list");

     Route::post('/update-location', "ApiController@update_location");
     Route::post('/get_event_list', "ApiController@get_event_list");
     Route::post('/get_trending_events_parties', "ApiController@get_trending_events_parties");
     Route::post('/get_categories', "ApiController@get_categories");
     Route::post('/get_my_booked_events/{user_id}', "ApiController@get_my_booked_events");

    });
     
     
});
