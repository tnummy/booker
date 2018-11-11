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
    return view('welcome')->with('navVisibility', 0);
});

Auth::routes();

Route::get('/home', 'HomeController@homePageEvents')->name('home');
Route::get('/events', 'HomeController@allEvents')->name('events');

Route::prefix('inbox')->group(function () {
	Route::get('requests/all', 'HomeController@allRequests')->name('allRequests');
	Route::get('negotiations/all', 'HomeController@allNegotiations')->name('allNegotiations');
	Route::get('dismiss/{id}', 'InboxController@dismissNotification')->name('dismissNotification');
	Route::get('confirm/{id}', 'InboxController@confirmInquiry')->name('confirmInquiry');
	Route::get('decline/{id}', 'InboxController@declineInquiry')->name('declineInquiry');
	Route::get('details/{id}', 'InboxController@inquiryDetails')->name('inquiryDetails');
});

Route::prefix('dependency')->group(function () {
	Route::get('all', 'HomeController@allDependencies')->name('allDependencies');
	Route::get('create', 'DependencyController@create')->name('createDependency');
	Route::post('store', 'DependencyController@store')->name('storeDependency');
	Route::get('delete/{id}', 'DependencyController@delete')->name('deleteDependency');
	Route::get('restore/{id}', 'DependencyController@restore')->name('restoreDependency');
	Route::get('edit/{id}', 'DependencyController@edit')->name('editDependency');
	Route::post('update/{id}', 'DependencyController@update')->name('updateDependency');
});


Route::prefix('inquiry')->group(function () {
	Route::post('negotiation/send/{id}', 'InquiryController@sendNegotiation')->name('sendNegotiation');
	Route::get('create', 'InquiryController@create')->name('createInquiry');
	Route::post('store', 'InquiryController@store')->name('storeInquiry');
	Route::get('edit/{id}', 'InquiryController@edit')->name('editInquiry');
	Route::post('/update', 'InquiryController@update')->name('updateInquiry');
	Route::post('delete', 'InquiryController@delete')->name('deleteInquiry');
});

Route::get('/asteroids', function () {
    return view('fun/asteroids');
})->name('asteroids');