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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');


// USER

// create team
Route::post('/create_team', ['uses' =>  'RegisterTeamController@createTeam', 'middleware' => 'auth'])->name('user_createTeam');
// generate players
Route::post('/create_player', ['uses' =>  'PlayersController@createPlayer', 'middleware' => 'auth'])->name('user_createPlayer');

// Pages
// Players
Route::get('/players', ['uses' => 'PlayersController@playersList','middleware' => 'auth'])->name('user_players');
Route::post('/players/filters', ['uses' => 'PlayersController@playersListFilters','middleware' => 'auth'])->name('user_playersFilters');
Route::post('/players/putOnTransferList/{id}', ['uses' => 'TransfersController@putOnTransferList','middleware' => 'auth'])->name('user_putOnTransferList');

// Tactics
Route::get('/tactics', ['uses' => 'TacticsController@tactics','middleware' => 'auth'])->name('user_tactics');
Route::post('/user/saveTactic', ['uses' => 'TacticsController@saveTactic','middleware' => 'auth'])->name('user_saveTactic');

// Random match
Route::get('/matches/challenge', ['uses' => 'MatchesController@challenge','middleware' => 'auth'])->name('user_challenge');

// Transfers
Route::get('/transfers', ['uses' => 'TransfersController@transfersList','middleware' => 'auth'])->name('user_transfersList');
Route::post('/transfers/filters', ['uses' => 'TransfersController@transfersListFilters','middleware' => 'auth'])->name('user_transfersFilters');
Route::post('/transfers/buyPlayer/{player_id}', ['uses' => 'TransfersController@buyPlayer','middleware' => 'auth'])->name('user_transfersBuyPlayer');

// ADMIN
Route::get('/admin/dashboard', ['uses' => 'AdminDashboard@dashboard', 'middleware' => 'auth'])->name('admin_dashboard');
Route::post('/admin/addName', ['uses' => 'AdminDashboard@addName', 'middleware' => 'auth'])->name('admin_addName');
Route::post('/admin/addSurname', ['uses' => 'AdminDashboard@addSurname', 'middleware' => 'auth'])->name('admin_addSurname');