<?php

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
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => ['web', 'guest']], function () {
    // Route::get('/signin',
    //     ['as' => 'signin',
    //         'uses' => 'Auth\LoginController@check',]);
    // Route::post('/login',
    //     ['as' => 'login',
    //         'uses' => 'Auth\AuthController@postLogin',]);

    // Route::get('/',
    //     ['uses' => 'Auth\LoginController@check']);
});

Route::group(['middleware' => ['web', 'auth', 'permissions.required']], function () {
    Route::get('dashboard/index', ['as' => 'backend.dashboard.index', 'uses' => 'Backend\DashboardCtrl@getIndex']);
    Route::resource('/','Backend\DashboardCtrl',array('only'=>array('index')));
    Route::get('dashboard/statistik','Backend\DashboardCtrl@getStatistikView');
    Route::get('notifikasi', ['as' => 'notifikasi', 'uses' => 'Backend\DashboardCtrl@getNotifikasi']);



    
    
    //route customer
	// Route::resource('customer', 'CustomerCtrl', ['only' => ['index', 'create', 'edit', 'destroy']]);
	// Route::post('customer/post','CustomerCtrl@post')->name('customer.post');
	// Route::get('customer/{id}/edit', 'CustomerCtrl@edit')->name('customer.edit');
    // Route::post('customer/{id}/update','CustomerCtrl@update')->name('customer.update');
    // Route::resource('driver','DriverCtrl');
    // Route::post('driver','DriverCtrl@post')->name('driver.post');
    // Route::post('driver/change_photo','DriverCtrl@change_photo')->name('driver.change_photo');
    // Route::post('driver/addsaldo','DriverCtrl@add_saldo')->name('driver.addsaldo');
    
    Route::get('driver','Backend\DriverCtrl@view')->name('driver');
    Route::get('driver/data','Backend\DriverCtrl@data')->name('driver-ajaxData');

    Route::get('customer','Backend\CustomerCtrl@view')->name('customer');
    Route::get('customer/data','Backend\CustomerCtrl@data')->name('customer-ajaxData');

    Route::post('customer/addsaldo','Backend\CustomerCtrl@add_saldo')->name('customer.addsaldo');
    Route::get('customer/request_saldo','Backend\CustomerCtrl@request_saldo')->name('customer.request_saldo');
    Route::post('customer/accept_request_saldo','Backend\CustomerCtrl@accept_request_saldo')->name('customer.accept_request_saldo');

    Route::get('admin/booking',
        ['as' => 'admin-booking',
            'uses' => 'Backend\BookingCtrl@view',]);
    Route::get('admin/booking/data',
        ['as' => 'admin-booking-ajaxData',
            'uses' => 'Backend\BookingCtrl@data',]);
    Route::get('admin/booking/read/{id}',
        ['as' => 'admin-booking-view',
            'uses' => 'Backend\BookingCtrl@read',]);
    Route::post('admin/booking/read/{id}',
        ['as' => 'admin-booking-view',
            'uses' => 'Backend\BookingCtrl@read',]);

	// Route::resource('booking','BookingCtrl');
	
    Route::get('report/pemesanan','Backend\ReportCtrl@pemesanan')->name('report.pemesanan');
    Route::post('report/pemesanan','Backend\ReportCtrl@pemesanan');
    Route::get('report/customer','Backend\ReportCtrl@customer')->name('report.customer');
    Route::post('report/customer','Backend\ReportCtrl@customer');

    Route::get('setting/general','Backend\SettingCtrl@general')->name('backend.setting.general');
    Route::post('setting/general','Backend\SettingCtrl@general')->name('backend.setting.general');

    Route::get('setting/fare','Backend\SettingCtrl@fare')->name('backend.setting.fare');
    Route::post('setting/fare','Backend\SettingCtrl@fare')->name('backend.setting.fare');
    
    // Route::resource('pengumuman', 'PengumumanCtrl', ['only' => ['index', 'create', 'edit', 'destroy']]);
	// Route::post('pengumuman/post', 'PengumumanCtrl@postPengumuman')->name('pengumuman.post');

    Route::group(['prefix'=>'backend','namespace'=>'Backend','as'=>'backend.'],function(){
        Route::get('user/notifikasi','BackendCtrl@getNotificationByUser');
        


        //route req saldo
        Route::resource('reqsaldo', 'ReqSaldoCtrl', ['only' => ['index', 'create', 'edit', 'destroy','konfirmasi']]);
        Route::post('reqsaldo/post','ReqSaldoCtrl@post')->name('reqsaldo.post');
        Route::post('reqsaldo/{id}/konfirmasi','ReqSaldoCtrl@konfirmasi')->name('reqsaldo.konfirmasi');
        Route::get('reqsaldo/{id}/edit', 'ReqSaldoCtrl@edit')->name('reqsaldo.edit');
        Route::post('reqsaldo/{id}/update','ReqSaldoCtrl@update')->name('reqsaldo.update');	

        Route::resource('trip_job','TripCtrl');
        Route::post('trip_job/post','TripCtrl@post')->name('trip_job.post');
        Route::get('trip_job_data','TripCtrl@get_data');
        Route::get('trip_job/{trip_id}/detail','TripCtrl@get_detail');
        
        Route::resource('reviews','ReviewCtrl');
        

        Route::resource('services','ServiceCtrl');
        Route::resource('packages','PackageCtrl',['only'=>['index']]);
        Route::get('packages/create/{type?}','PackageCtrl@create')->name('packages.create');
        Route::get('packages/edit/{id}/{type?}','PackageCtrl@edit')->name('packages.edit');
        Route::post('packages-post/{type?}','PackageCtrl@post')->name('packages.post');
        Route::post('packages/{type?}/na','PackageCtrl@na')->name('packages.na');
        Route::get('packages-list/{type?}','PackageCtrl@list')->name('packages.list');
        Route::post('packages-upload','PackageCtrl@upload')->name('packages.upload');
        Route::delete('packages/{id}','PackageCtrl@destroy')->name('packages.destroy');
        Route::resource('typevehicle','VehicleTypeCtrl',['only'=>['index','create','edit','destroy']]);
        Route::post('typevehicle/post','VehicleTypeCtrl@post')->name('typevehicle.post');
        Route::post('typevehicle/upload','VehicleTypeCtrl@upload')->name('typevehicle.upload');

        Route::resource('promo', 'PromoCtrl', ['only' => ['index', 'create', 'edit', 'destroy']]);
        Route::post('promo/post', 'PromoCtrl@post')->name('promo.post');
        Route::post('promo/upload', 'PromoCtrl@upload')->name('promo.upload');
    });
	

	// Route::resource('laporan', 'LaporanCtrl', ['only' => ['index']]);
});
