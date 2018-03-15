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

Route::get('/users/verify/{code}', 'UsersController@verify');



Route::get('/', 'PagesController@getHome');
Route::get('/contact-us', 'PagesController@getContact');
Route::post('/contact-us', 'PagesController@postContact');
Route::get('/about', 'PagesController@getAbout');
Route::get('/faq', 'PagesController@getFaq');
Route::get('/privacy', 'PagesController@getPrivacy');
Route::get('/terms-of-use', 'PagesController@getTerms');
Route::get('/how-to-set-stellar-inflation-destination', 'PagesController@setInflation')->name('set-inflation');

// administrative routes
Route::namespace('Admin')->group(function() {
    // handling transaction signing
    Route::post('/saveuser');
    Route::get('/transaction/:id', 'TransactionsController@get');
    Route::get('/transaction/list/:limit', 'TransactionsController@list');
    Route::get('/transaction/submit/:id', 'TransactionsController@submit');
    Route::post('/transaction', 'TransactionsController@store');
    Route::post('/transaction/sign', 'TransactionsController@sign');
    Route::post('/transaction/signers', 'TransactionsController@signers');
});



Auth::routes();

Route::get('/login', function() {
    return view('dashboard');
});
Route::get('/register', function() {
    return view('dashboard');
});
Route::get('/dashboard', function() {
    return view('dashboard');
});

Route::any('{all}', function () {
    return view('dashboard');
})->where(['all' => '.*']);


//Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');
