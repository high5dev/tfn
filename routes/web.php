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

Auth::routes(['verify' => true]);
Route::get('logout', 'LoggedInController@logout');
/*
 * // Authentication Routes...
 * Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
 * Route::post('login', 'Auth\LoginController@login');
 * Route::post('logout', 'Auth\LoginController@logout')->name('logout');
 *
 * // Registration Routes...
 * Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
 * Route::post('register', 'Auth\RegisterController@register');
 *
 * // Password Reset Routes...
 * Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
 * Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
 * Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
 * Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
 *
 * // Email Verification Routes...
 * Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
 * Route::get('email/verify/{id}', 'Auth\VerificationController@verify')->name('verification.verify');
 * Route::post('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');
 */
// public routes
Route::get('/', 'PagesController@index')->name('index');

// pages for logged in users
Route::group([
    'middleware' => 'auth'
], function () {

    // User: homepage and profile
    Route::get('/home', 'LoggedInController@home')->name('home');
    Route::get('/profile', 'ProfileController@show');
    Route::post('/profile', 'ProfileController@update');

    // User: view logs
    Route::get('/logs', 'LoggController@index');
    Route::get('/logs/{id}', 'LoggController@show');


    // User: search
    Route::get('/search', 'SearchController@index');
    Route::get('/search/email', 'SearchController@email');
    Route::get('/search/subject', 'SearchController@subject');

    // User: posts
    Route::get('/posts', 'PostController@index');
    Route::get('/posts/list', 'PostController@list');
    Route::get('/posts/spam', 'PostController@spam');
    Route::post('/posts/notspam/{id}', 'PostController@notSpam');
    Route::delete('/post/{id}', 'PostController@destroy');
    Route::post('/post/done', 'PostController@doneScanning');
    Route::post('/posts/summary', 'PostController@saveSummary');

    Route::get('/scans', 'ScanController@index');

    // User: manage watchwords
    Route::get('/watchwords', 'WatchwordController@index');
    Route::get('/watchwords/create', 'WatchwordController@create');
    Route::post('/watchwords', 'WatchwordController@store');
    Route::delete('/watchwords/{id}', 'WatchwordController@destroy');

    // User: charts
    Route::get('/chart', 'ChartController@index');

    /****************** ADMIN Routes ****************/

    // Admin: manage users
    Route::get('/admin/users', 'Admin\AdminUserController@index');
    Route::get('/admin/users/create', 'Admin\AdminUserController@create');
    Route::post('/admin/users', 'Admin\AdminUserController@store');
    Route::get('/admin/users/{id}', 'Admin\AdminUserController@show');
    Route::patch('/admin/users', 'Admin\AdminUserController@update');
    Route::delete('/admin/users/{id}', 'Admin\AdminUserController@destroy');

    // Admin: manage groups
    Route::get('/admin/groups', 'Admin\AdminGroupController@index');
    Route::get('/admin/groups/create', 'Admin\AdminGroupController@create');
    Route::post('/admin/groups', 'Admin\AdminGroupController@store');
    Route::get('/admin/groups/{id}', 'Admin\AdminGroupController@show');
    Route::patch('/admin/groups', 'Admin\AdminGroupController@update');
    Route::delete('/admin/groups/{id}', 'Admin\AdminGroupController@destroy');
    Route::get('/admin/getGroups', 'Admin\AdminGroupController@getGroups');

    // Admin: manage logs
    Route::get('/admin/logs', 'Admin\AdminLoggController@index');
    Route::get('/admin/logs/{id}', 'Admin\AdminLoggController@show');

    // Admin: manage scans
    Route::get('/admin/scans', 'Admin\AdminScanController@index');
    Route::get('/admin/scans/{id}', 'Admin\AdminScanController@show');

    // Admin: graphs
    Route::get('/admin/chart/users', 'Admin\AdminChartController@users');
    Route::get('/admin/chart/weekly', 'Admin\AdminChartController@weekly');

});
