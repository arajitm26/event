<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
    //return date("h:i:s");
});

Route::get('/test', function () {
  // echo "test";
    // $artisan = Artisan::call("storage:link");
    // dd($artisan);
});

Route::get('/make_payment', 'PaymentController@make_payment');
// Auth::routes();


Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login','Auth\LoginController@login');

Route::get('register','Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register','Auth\RegisterController@register');
Route::post('logout','Auth\LoginController@logout')->name('logout');

Route::get('/home', 'HomeController@index')->name('home');



// Password Reset Routes...
Route::post('password/email', [
  'as' => 'password.email',
  'uses' => 'Auth\ForgotPasswordController@sendResetLinkEmail'
]);
Route::get('password/reset', [
  'as' => 'password.request',
  'uses' => 'Auth\ForgotPasswordController@showLinkRequestForm'
]);
Route::post('password/reset', [
  'as' => 'password.update',
  'uses' => 'Auth\ResetPasswordController@reset'
]);
Route::get('password/reset/{token}', [
  'as' => 'password.reset',
  'uses' => 'Auth\ResetPasswordController@showResetForm'
]);

// Route::group(['middleware'=>['ckeckApi'],'prefix'=>'app'],function () {
    
//      Route::get('/weblogin', "ApiController@login");
//      Route::get('/webregister', "ApiController@register");
// });



Route::group(['prefix'=>'admin'],function () {
    
    // Route::group(['middleware'=>['ckeckApi']],function (){
    	
    // 	Route::get('/get_event_list', "ApiController@get_event_list");
    // });
     Route::get('/login', "AdminController@showlogin")->middleware('guest:admin');

     Route::post('/login', "AdminController@login")->name('login_submit');

     Route::post('/logout', "AdminController@logout")->name('admin_logout');
     
     

     
     
     Route::group(['middleware'=>['auth:admin']],function (){
      Route::get('/', "AdminController@index");
      Route::get('/test/{id?}', "AdminController@test")->middleware('can:check_permissions,"create_user",id');

     
    });

     
     
});
