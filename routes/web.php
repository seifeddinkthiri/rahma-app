<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\WaitingUsersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\ImagesController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\SpecificController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\InterventionController;
use App\Http\Controllers\ProjectController;

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

// Auth

//login
Route::get('login', [AuthenticatedSessionController::class, 'create'])
    ->name('login')
    ->middleware('guest');

Route::post('login', [AuthenticatedSessionController::class, 'store'])
    ->name('login.store')
    ->middleware('guest');

Route::delete('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

//register
Route::get('register', [RegisterController::class, 'create'])
    ->name('register')
    ->middleware('guest');

Route::post('register', [RegisterController::class, 'store'])
    ->name('register.store')
    ->middleware('guest');

// Dashboard

Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth');

// Users

Route::get('users', [UsersController::class, 'index'])
    ->name('users')
    ->middleware('auth');

Route::get('users/create', [UsersController::class, 'create'])
    ->name('users.create')
    ->middleware('auth');

Route::post('users', [UsersController::class, 'store'])
    ->name('users.store')
    ->middleware('auth');

Route::get('users/{user}/edit', [UsersController::class, 'edit'])
    ->name('users.edit')
    ->middleware('auth');

Route::put('users/{user}', [UsersController::class, 'update'])
    ->name('users.update')
    ->middleware('auth');

Route::delete('users/{user}', [UsersController::class, 'destroy'])
    ->name('users.destroy')
    ->middleware('auth');

Route::put('users/{user}/restore', [UsersController::class, 'restore'])
    ->name('users.restore')
    ->middleware('auth');

//waiting list

Route::get('wait_list', [WaitingUsersController::class, 'index'])
    ->name('wait_list')
    ->middleware('auth');

Route::get('wait_list/{user}/edit', [WaitingUsersController::class, 'edit'])
    ->name('wait_list.edit')
    ->middleware('auth');

Route::get('wait_list/{user}/delete_demonde', [WaitingUsersController::class, 'delete_demonde'])
    ->name('wait_list.delete_demonde')
    ->middleware('auth');




Route::any('beneficials', [FamilyController::class, 'beneficials'])
    ->name('beneficials')
    ->middleware('auth');
    Route::post('specifics', [SpecificController::class, 'store'])
    ->name('specifics.store')
    ->middleware('auth');
    Route::get('/families/members/specific/{Family}/{type}', [SpecificController::class, 'create'])  ->name('specifics.create');


// families


Route::post('families/{type}', [FamilyController::class, 'store'])
    ->name('families.store')
    ->middleware('auth');

Route::get('families/{family}/edit', [FamilyController::class, 'edit'])
    ->name('families.edit')
    ->middleware('auth');

Route::post('families_update/{family}', [FamilyController::class, 'update'])
    ->name('families.update')
    ->middleware('auth');
    Route::get('families/{family}/show', [FamilyController::class, 'show'])
    ->name('families.show')
    ->middleware('auth');
Route::any('families/{family}', [FamilyController::class, 'destroy'])
    ->name('families.destroy')
    ->middleware('auth');

Route::put('families/{family}/restore', [FamilyController::class, 'restore'])
    ->name('families.restore')
    ->middleware('auth');

    //members

    Route::get('members/{family}/create_new_one', [MemberController::class, 'create_new_one'])
    ->name('members.create_new_one')
    ->middleware('auth');

    Route::get('members/{Family}/create', [MemberController::class, 'create'])
    ->name('members.create')
    ->middleware('auth');




    Route::get('members/{member}/show', [MemberController::class, 'show'])
    ->name('members.show')
    ->middleware('auth');

    Route::post('members', [MemberController::class, 'store'])
    ->name('members.store')
    ->middleware('auth');

    Route::post('newMembers', [MemberController::class, 'newMembers'])
    ->name('newMembers')
    ->middleware('auth');


    Route::get('members/{member}/edit', [MemberController::class, 'edit'])
    ->name('members.edit')
    ->middleware('auth');

    Route::post('members_update/{member}', [MemberController::class, 'update'])
    ->name('members.update')
    ->middleware('auth');

    Route::delete('members/{member}', [MemberController::class, 'destroy'])
    ->name('members.destroy')
    ->middleware('auth');

    Route::put('members/{member}/restore', [MemberController::class, 'restore'])
    ->name('members.restore')
    ->middleware('auth');

    //member edit_caregiver
    Route::get('members/{member}/edit_caregiver', [MemberController::class, 'edit_caregiver'])
    ->name('members.edit_caregiver')
    ->middleware('auth');

    //Member Health Status

    Route::put('healthStatusMemeber/{member}', [MemberController::class, 'update_health_status'])
    ->name('member.health.update')
    ->middleware('auth');

    //files
    Route::post('files/{family}', [FileController::class, 'store'])
    ->name('files.store')
    ->middleware('auth');
    Route::delete('files/{file}', [FileController::class, 'destroy'])
    ->name('files.destroy')
    ->middleware('auth');

    Route::delete('files/{file}', [FileController::class, 'destroy'])
    ->name('files.destroy')
    ->middleware('auth');
    //notes
    Route::post('notes/{family}', [NoteController::class, 'store'])
    ->name('notes.store')
    ->middleware('auth');


    Route::put('notes/{note}', [NoteController::class, 'update'])
    ->name('notes.update')
    ->middleware('auth');

    Route::delete('notes/{note}', [NoteController::class, 'destroy'])
    ->name('notes.destroy')
    ->middleware('auth');

    Route::put('restore_notes/{note}/restore', [NoteController::class, 'restore'])
    ->name('notes.restore')
    ->middleware('auth');

 //Home
 Route::post('home/{family}', [HomeController::class, 'store'])
 ->name('home.store')
 ->middleware('auth');


 Route::delete('home/{family}', [HomeController::class, 'destroy'])
 ->name('home.destroy')
 ->middleware('auth');

 Route::put('restore_home/{family}/restore', [HomeController::class, 'restore'])
 ->name('homes.restore')
 ->middleware('auth');



    //facilities

    Route::put('facilities/{family}', [FacilityController::class, 'update'])
    ->name('facilities.update')
    ->middleware('auth');


    Route::delete('facilities/{family}', [FacilityController::class, 'destroy'])
    ->name('facilities.destroy')
    ->middleware('auth');

    Route::put('restore_facilities/{family}/restore', [FacilityController::class, 'restore'])
    ->name('facilities.restore')
    ->middleware('auth');



// Images

Route::get('/img/{path}', [ImagesController::class, 'show'])
    ->where('path', '.*')
    ->name('image');




    // interventions

Route::get('interventions', [InterventionController::class, 'index'])
->name('interventions')
->middleware('auth');

Route::get('interventions/create', [InterventionController::class, 'create'])
->name('interventions.create')
->middleware('auth');

Route::post('interventions', [InterventionController::class, 'store'])
->name('interventions.store')
->middleware('auth');

Route::get('interventions/{intervention}/edit', [InterventionController::class, 'edit'])
->name('interventions.edit')
->middleware('auth');
Route::get('interventions/{intervention}/show', [InterventionController::class, 'show'])
->name('interventions.show')
->middleware('auth');
Route::post('interventions/{intervention}', [InterventionController::class, 'update'])
->name('interventions.update')
->middleware('auth');

Route::delete('interventions/{intervention}', [InterventionController::class, 'destroy'])
->name('interventions.destroy')
->middleware('auth');

Route::put('interventions/{intervention}/restore', [InterventionController::class, 'restore'])
->name('interventions.restore')
->middleware('auth');



    // interventions

Route::get('interventions', [InterventionController::class, 'index'])
->name('interventions')
->middleware('auth');

Route::get('interventions/create', [InterventionController::class, 'create'])
->name('interventions.create')
->middleware('auth');

Route::post('interventions', [InterventionController::class, 'store'])
->name('interventions.store')
->middleware('auth');

Route::get('interventions/{intervention}/edit', [InterventionController::class, 'edit'])
->name('interventions.edit')
->middleware('auth');
Route::get('interventions/{intervention}/show', [InterventionController::class, 'show'])
->name('interventions.show')
->middleware('auth');
Route::post('interventions/{intervention}', [InterventionController::class, 'update'])
->name('interventions.update')
->middleware('auth');

Route::delete('interventions/{intervention}', [InterventionController::class, 'destroy'])
->name('interventions.destroy')
->middleware('auth');

Route::put('interventions/{intervention}/restore', [InterventionController::class, 'restore'])
->name('interventions.restore')
->middleware('auth');


  // projects

  Route::get('projects', [ProjectController::class, 'index'])
  ->name('projects')
  ->middleware('auth');

  Route::get('projects/create', [ProjectController::class, 'create'])
  ->name('projects.create')
  ->middleware('auth');

  Route::post('projects', [ProjectController::class, 'store'])
  ->name('projects.store')
  ->middleware('auth');

  Route::get('projects/{project}/edit', [ProjectController::class, 'edit'])
  ->name('projects.edit')
  ->middleware('auth');
  Route::get('projects/{project}/show', [ProjectController::class, 'show'])
  ->name('projects.show')
  ->middleware('auth');
  Route::post('projects/{project}', [ProjectController::class, 'update'])
  ->name('projects.update')
  ->middleware('auth');


  Route::delete('projects/{project}', [ProjectController::class, 'destroy'])
  ->name('projects.destroy')
  ->middleware('auth');

  Route::put('projects/{project}/restore', [ProjectController::class, 'restore'])
  ->name('projects.restore')
  ->middleware('auth');
