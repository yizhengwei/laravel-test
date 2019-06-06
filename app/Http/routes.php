<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('login');
});

Route::get('/login','View\MemberController@toLogin');

Route::get('/register','View\MemberController@toRegister');

Route::get('/category','View\BookController@toCategory');
Route::get('/product/category_id/{category}','View\BookController@toProduct');
Route::get('/product/{product_id}','View\BookController@toPdtContent');


Route::group(['prefix' => 'service'], function () {
    Route::get('validate_code/create', 'Service\ValidateController@create');
	Route::post('validate_phone/send', 'Service\ValidateController@sendSMS');
	Route::post('register', 'Service\MemberController@register');
	Route::post('login', 'Service\MemberController@login');
	Route::any('category/parent_id/{parent_id}', 'Service\BookController@getCategoryByParentId');
	Route::any('cart/add/{product_id}', 'Service\CartController@addCart');

    });

/*Route::any('service/validate_code/create', 'Service\ValidateController@create');
Route::any('service/validate_phone/send', 'Service\ValidateController@sendSMS');
Route::any('service/validate_email', 'Service\ValidateController@validateEmail');
Route::any('service/register', 'Service\MemberController@register');
Route::any('service/login', 'Service\MemberController@login');*/