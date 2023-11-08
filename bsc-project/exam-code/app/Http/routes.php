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
    return redirect('users');
});

Route::get('home', function () {
    return redirect('users');
});

//authemtication routes
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

//users controller routes
Route::any('users', 'UsersController@index');
Route::delete('users/{id}', 'UsersController@destroy');
Route::get('users/new', 'UsersController@getRegister');
Route::post('users/new', 'UsersController@postRegister');
Route::get('users/edit/{id}', 'UsersController@edit');
Route::post('users/edit/{id}', 'UsersController@update');

//categories controller routes
Route::any('categories', 'Categories@index');
Route::delete('categories/{id}', 'Categories@destroy');
Route::get('categories/new', 'Categories@create');
Route::post('categories/new', 'Categories@store');
Route::get('categories/edit/{id}', 'Categories@edit');
Route::post('categories/edit/{id}', 'Categories@update');

//difficultylevels controller routes
Route::any('difficultylevels', 'DifficultyLevels@index');
Route::delete('difficultylevels/{id}', 'DifficultyLevels@destroy');
Route::get('difficultylevels/new', 'DifficultyLevels@create');
Route::post('difficultylevels/new', 'DifficultyLevels@store');
Route::get('difficultylevels/edit/{id}', 'DifficultyLevels@edit');
Route::post('difficultylevels/edit/{id}', 'DifficultyLevels@update');

//exams controller routes
Route::any('exams', 'Exams@index');
Route::delete('exams/{id}', 'Exams@destroy');
Route::get('exams/new', 'Exams@create');
Route::post('exams/new', 'Exams@store');
Route::get('exams/edit/{id}', 'Exams@edit');
Route::post('exams/edit/{id}', 'Exams@update');
Route::get('exams/import/{id}', 'Exams@import');
Route::post('exams/import/{id}', 'Exams@saveImport');

//questions controller routes
Route::any('questions/{examId}', 'Questions@index');
Route::any('questions/delete/{id}', 'Questions@destroy');
Route::get('questions/{examId}/new', 'Questions@create');
Route::post('questions/{examId}/new', 'Questions@store');
Route::get('questions/edit/{id}', 'Questions@edit');
Route::post('questions/edit/{id}', 'Questions@update');

//bank questions controller routes
Route::any('bankquestions', 'BankQuestions@index');
Route::delete('bankquestions/{id}', 'BankQuestions@destroy');
Route::get('bankquestions/new', 'BankQuestions@create');
Route::post('bankquestions/new', 'BankQuestions@store');
Route::get('bankquestions/edit/{id}', 'BankQuestions@edit');
Route::post('bankquestions/edit/{id}', 'BankQuestions@update');

//takeexams controller routes
Route::any('takeexams', 'TakeExams@index');
Route::any('takeexams/generatesubmission/{id}', 'TakeExams@generateSubmission');
Route::get('takeexams/take/{submissionId}', 'TakeExams@take');
Route::post('takeexams/take/{submissionId}', 'TakeExams@saveSubmission');

//results controller routes
Route::any('results', 'Results@index');
Route::any('results/automaticcorrection/{id}', 'Results@automaticCorrection');
Route::get('results/manualcorrection/{id}', 'Results@manualCorrection');
Route::post('results/manualcorrection/{id}', 'Results@saveManualCorrection');
Route::get('results/myresults/{id}', 'Results@myResults');
Route::get('results/allresults/{id}', 'Results@allResults');
Route::get('results/answersheet/{id}', 'Results@answersheet');