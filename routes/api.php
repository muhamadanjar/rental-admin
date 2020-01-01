<?php

use Illuminate\Http\Request;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'Api\AuthCtrl@login');
    Route::post('signup', 'Api\AuthCtrl@signup');
    Route::post('logout', 'Api\AuthCtrl@logout');
    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::get('user', 'Api\AuthCtrl@user');
    });
});



Route::post('register','Api\InitCtrl@init');
Route::post('register/init','Api\DaftarAnggotaCtrl@init');
Route::post('register/setpin','Api\DaftarAnggotaCtrl@SetPIN');
Route::post('register/validate-pin','Api\DaftarAnggotaCtrl@ValidatePIN');
Route::post('register/check-pin-{pin}','Api\DaftarAnggotaCtrl@checkPin');
Route::post('register/update-data','Api\DaftarAnggotaCtrl@updateData');
Route::post('register/get-status','Api\DaftarAnggotaCtrl@getStatus');
//Common
Route::get('car-types/{id?}','Api\CommonCtrl@getTypeCar');
Route::get('packages/{id?}','Api\CommonCtrl@getRentPackage');
Route::get('promo/{id?}','Api\CommonCtrl@getPromo');
Route::get('bank/{id?}','Api\CommonCtrl@getBank');
Route::get('services-type/{id?}','Api\CommonCtrl@getServiceType');
Route::get('global-settings','Api\CommonCtrl@getSettings');

Route::get('getprovinsi','Api\CommonCtrl@getProvinsi');



Route::post('reviews','Api\CommonCtrl@postReview');

//Order
Route::post('booking','Api\OrderCtrl@postOrder');
Route::post('booking/update-status','Api\OrderCtrl@postUpdateOrder');
Route::post('topup/saldo','Api\OrderCtrl@postTopUpSaldo');
Route::post('topup/bukti','Api\OrderCtrl@postUploadBukti');
Route::post('booking/history','Api\OrderCtrl@getHistoryOrderByUser');

//User
Route::get('user/location','Api\UserCtrl@getUserLocation');
Route::post('user/changestatus','Api\UserCtrl@postChangeStatusOnline');
Route::get('/users-notification', 'UserNotificationController@getNotification');
// User Meta
Route::post('user/meta/update-status', 'Api\UserMetaCtrl@postStatusMeta');
Route::post('user/meta/get-status', 'Api\UserMetaCtrl@getStatusMeta');
Route::post('user/post/meta', 'Api\UserMetaCtrl@postMeta');
Route::post('user/post/attachment', 'Api\UserMetaCtrl@postFilesMeta');
Route::post('user/post/check-meta-value', 'Api\UserMetaCtrl@checkUserMeta');

Route::post('/users/email/setup', 'Api\UserCtrl@ChangeEmail');
Route::post('/users/email/verification', 'Api\UserCtrl@GenerateEmailVerification');
Route::get('/users/email/status', 'Api\UserCtrl@EmailStatus');
Route::post('/users/post-reviews', 'Api\UserCtrl@postReview');


Route::post('/account/post/initiation', 'Api\DaftarAnggotaCtrl@Initiation');
Route::post('/account/post/setpin', 'Api\DaftarAnggotaCtrl@SetPIN');
Route::post('/account/post/checkpin', 'Api\DaftarAnggotaCtrl@ValidatePIN');
Route::post('/account/post/aktifasi', 'Api\DaftarAnggotaCtrl@Aktifasi');
Route::get('/account/status', 'Api\DaftarAnggotaCtrl@Status');

//WebView
Route::get('/help/faq',function ()  {
    return view('/webview/faq');
});
