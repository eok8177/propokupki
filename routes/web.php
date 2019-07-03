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

Auth::routes();
// Route::redirect('/register', '/login'); //Block register

// Social login
Route::get('/login/{social}','Auth\LoginController@socialLogin')->where('social','twitter|facebook|linkedin|google|github|bitbucket');
Route::get('/login/{social}/callback','Auth\LoginController@handleProviderCallback')->where('social','twitter|facebook|linkedin|google|github|bitbucket');

Route::group([
    'as' => 'admin.',
//    'middleware' => 'auth',
    'namespace' => 'Admin',
    'prefix' => 'admin'], function() {

    //      Language
    Route::resource('/language', 'LanguagesController');
    //      Cities
    Route::resource('/cities', 'CitiesController');
    //      Shops
    Route::resource('/shops', 'ShopsController');
    Route::post('shops/ajaxShops', ['as' => 'shops.ajaxShops', 'uses' => 'ShopsController@ajaxShops']);
    Route::put('shops/status/{id}', ['as' => 'shops.status', 'uses' => 'ShopsController@status']);
    //      Discounts
    Route::resource('/discounts', 'DiscountsController');
    Route::put('discounts/status/{id}', ['as' => 'discounts.status', 'uses' => 'DiscountsController@status']);

    Route::get('/profile', ['as' => 'profile.edit', 'uses' => 'ProfilesController@edit']);
    Route::post('/profile', ['as' => 'profile.update', 'uses' => 'ProfilesController@update']);


//      Categories
//    Route::resource('/categories', 'CategoriesController');

//      Posts
//    Route::resource('/posts', 'PostsController');

//      Posts
//    Route::resource('/reviews', 'ReviewsController');
//    Route::put('reviews/status/{id}', ['as' => 'reviews.status', 'uses' => 'ReviewsController@status']);

//      Users
//    Route::resource('/users', 'UsersController');

//      Roles
//    Route::resource('/roles', 'RolesController');

//      Settings
//    Route::prefix('settings')->name('settings.')->group(function () {
//        Route::get('/', 'SettingsController@edit')->name('edit');
//        Route::put('/', 'SettingsController@update')->name('update');
//    });
    // Dashboard
//    Route::get('/', 'DashboardController@index')->name('dashboard');

//      Pages
//    Route::get('/pages/{type}', 'PagesController@index');
//    Route::get('/pages', 'PagesController@index');
//    Route::get('/pages/create/{type}', ['as' => 'pages.create', 'uses' => 'PagesController@create']);
//    Route::post('/pages/store', ['as' => 'pages.store', 'uses' => 'PagesController@store']);
//    Route::get('/pages/{id}/edit', ['as' => 'pages.edit', 'uses' => 'PagesController@edit']);
//    Route::put('/pages/{id}', ['as' => 'pages.update', 'uses' => 'PagesController@update']);
//    Route::get('/pages/{id}/delete', ['as' => 'pages.delete', 'uses' => 'PagesController@delete']);
    // Route::resource('/pages', 'PagesController');

});


Route::get('/cutup-login', function () {
    return view('cutup.login');
});
Route::get('/cutup-register', function () {
    return view('cutup.register');
});

Route::get('/cutup-admin-profile', function () {
    return view('cutup.admin.profile');
});

Route::get('/cutup-admin-shops', function () {
    return view('cutup.admin.shops');
});

Route::get('/cutup-admin-shopadd', function () {
    return view('cutup.admin.shopadd');
});

Route::get('/cutup-admin-actions', function () {
    return view('cutup.admin.actions');
});

Route::get('/cutup-admin-actionadd', function () {
    return view('cutup.admin.actionadd');
});


Route::get('/{any}', function () {
    return view('spa');
})->where('any', '.*');