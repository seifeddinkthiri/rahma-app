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
    ->middleware(['auth', 'can:accepted']);



// Users

Route::get('users', [UsersController::class, 'index'])
    ->name('users')
        ->middleware(['auth', 'can:owner']);


Route::get('users/create', [UsersController::class, 'create'])
    ->name('users.create')
        ->middleware(['auth', 'can:owner']);


Route::post('users', [UsersController::class, 'store'])
    ->name('users.store')
        ->middleware(['auth', 'can:owner']);


Route::get('users/{user}/edit', [UsersController::class, 'edit'])
    ->name('users.edit')
        ->middleware(['auth', 'can:accepted']);



        Route::get('users/{user}/show', [UsersController::class, 'show'])
        ->name('users.show')
            ->middleware(['auth', 'can:accepted']);

Route::put('users/{user}', [UsersController::class, 'update'])
    ->name('users.update')
        ->middleware(['auth', 'can:owner']);


Route::delete('users/{user}', [UsersController::class, 'destroy'])
    ->name('users.destroy')
        ->middleware(['auth', 'can:owner']);


Route::put('users/{user}/restore', [UsersController::class, 'restore'])
    ->name('users.restore')
        ->middleware(['auth', 'can:owner']);


//waiting list

Route::get('wait_list', [WaitingUsersController::class, 'index'])
    ->name('wait_list')
    ->middleware(['auth', 'can:owner']);
Route::get('wait_list/{user}/edit', [WaitingUsersController::class, 'edit'])
    ->name('wait_list.edit')
        ->middleware(['auth', 'can:owner']);


Route::get('wait_list/{user}/delete_demonde', [WaitingUsersController::class, 'delete_demonde'])
    ->name('wait_list.delete_demonde')
        ->middleware(['auth', 'can:owner']);





Route::any('beneficials', [FamilyController::class, 'beneficials'])
    ->name('beneficials')
        ->middleware(['auth', 'can:accepted']);

    Route::post('specifics', [SpecificController::class, 'store'])
    ->name('specifics.store')
        ->middleware(['auth', 'can:accepted']);

    Route::get('/families/members/specific/{Family}/{type}', [SpecificController::class, 'create'])  ->name('specifics.create')  ->middleware(['auth', 'can:accepted']);


// families


Route::post('families/{type}', [FamilyController::class, 'store'])
    ->name('families.store')
        ->middleware(['auth', 'can:accepted']);


Route::get('families/{family}/edit', [FamilyController::class, 'edit'])
    ->name('families.edit')
        ->middleware(['auth', 'can:accepted']);


Route::post('families_update/{family}', [FamilyController::class, 'update'])
    ->name('families.update')
        ->middleware(['auth', 'can:accepted']);

    Route::get('families/{family}/show', [FamilyController::class, 'show'])
    ->name('families.show')
        ->middleware(['auth', 'can:accepted']);

Route::any('families/{family}', [FamilyController::class, 'destroy'])
    ->name('families.destroy')
        ->middleware(['auth', 'can:accepted']);


Route::put('families/{family}/restore', [FamilyController::class, 'restore'])
    ->name('families.restore')
        ->middleware(['auth', 'can:accepted']);


    //members

    Route::get('members/{family}/create_new_one', [MemberController::class, 'create_new_one'])
    ->name('members.create_new_one')
        ->middleware(['auth', 'can:accepted']);


    Route::get('members/{Family}/create', [MemberController::class, 'create'])
    ->name('members.create')
        ->middleware(['auth', 'can:accepted']);





    Route::get('members/{member}/show', [MemberController::class, 'show'])
    ->name('members.show')
        ->middleware(['auth', 'can:accepted']);


    Route::post('members', [MemberController::class, 'store'])
    ->name('members.store')
        ->middleware(['auth', 'can:accepted']);


    Route::post('newMembers', [MemberController::class, 'newMembers'])
    ->name('newMembers')
        ->middleware(['auth', 'can:accepted']);



    Route::get('members/{member}/edit', [MemberController::class, 'edit'])
    ->name('members.edit')
        ->middleware(['auth', 'can:accepted']);


    Route::post('members_update/{member}', [MemberController::class, 'update'])
    ->name('members.update')
        ->middleware(['auth', 'can:accepted']);


    Route::delete('members/{member}', [MemberController::class, 'destroy'])
    ->name('members.destroy')
        ->middleware(['auth', 'can:accepted']);


    Route::put('members/{member}/restore', [MemberController::class, 'restore'])
    ->name('members.restore')
        ->middleware(['auth', 'can:accepted']);


    //member edit_caregiver
    Route::get('members/{member}/edit_caregiver', [MemberController::class, 'edit_caregiver'])
    ->name('members.edit_caregiver')
        ->middleware(['auth', 'can:accepted']);


    //Member Health Status

    Route::put('healthStatusMemeber/{member}', [MemberController::class, 'update_health_status'])
    ->name('member.health.update')
        ->middleware(['auth', 'can:accepted']);


    //files
    Route::post('files/{family}', [FileController::class, 'store'])
    ->name('files.store')
        ->middleware(['auth', 'can:accepted']);

    Route::delete('files/{file}', [FileController::class, 'destroy'])
    ->name('files.destroy')
        ->middleware(['auth', 'can:accepted']);

  Route::get('/openFile/{file}', [FileController::class, 'show'])->middleware(['auth', 'can:accepted']);

    Route::delete('files/{file}', [FileController::class, 'destroy'])
    ->name('files.destroy')
        ->middleware(['auth', 'can:accepted']);

    //notes
    Route::post('notes/{family}', [NoteController::class, 'store'])
    ->name('notes.store')
        ->middleware(['auth', 'can:accepted']);



    Route::put('notes/{note}', [NoteController::class, 'update'])
    ->name('notes.update')
        ->middleware(['auth', 'can:accepted']);


    Route::delete('notes/{note}', [NoteController::class, 'destroy'])
    ->name('notes.destroy')
        ->middleware(['auth', 'can:accepted']);


    Route::put('restore_notes/{note}/restore', [NoteController::class, 'restore'])
    ->name('notes.restore')
        ->middleware(['auth', 'can:accepted']);


 //Home
 Route::post('home/{family}', [HomeController::class, 'store'])
 ->name('home.store')
     ->middleware(['auth', 'can:accepted']);



 Route::delete('home/{family}', [HomeController::class, 'destroy'])
 ->name('home.destroy')
     ->middleware(['auth', 'can:accepted']);


 Route::put('restore_home/{family}/restore', [HomeController::class, 'restore'])
 ->name('homes.restore')
     ->middleware(['auth', 'can:accepted']);




    //facilities

    Route::put('facilities/{family}', [FacilityController::class, 'update'])
    ->name('facilities.update')
        ->middleware(['auth', 'can:accepted']);



    Route::delete('facilities/{family}', [FacilityController::class, 'destroy'])
    ->name('facilities.destroy')
        ->middleware(['auth', 'can:accepted']);


    Route::put('restore_facilities/{family}/restore', [FacilityController::class, 'restore'])
    ->name('facilities.restore')
        ->middleware(['auth', 'can:accepted']);




// Images

Route::get('/img/{path}', [ImagesController::class, 'show'])
    ->where('path', '.*')
    ->name('image');







    // interventions

Route::get('interventions', [InterventionController::class, 'index'])
->name('interventions')
    ->middleware(['auth', 'can:accepted']);


Route::get('interventions/create', [InterventionController::class, 'create'])
->name('interventions.create')
    ->middleware(['auth', 'can:accepted']);

Route::get('interventions/createSolitary', [InterventionController::class, 'createSolitary'])
    ->name('interventions.createSolitary')
        ->middleware(['auth', 'can:accepted']);


Route::post('interventions', [InterventionController::class, 'store'])
->name('interventions.store')
    ->middleware(['auth', 'can:accepted']);



Route::get('interventions/{intervention}/edit', [InterventionController::class, 'edit'])
->name('interventions.edit')
    ->middleware(['auth', 'can:accepted']);

Route::get('interventions/{intervention}/show', [InterventionController::class, 'show'])
->name('interventions.show')
    ->middleware(['auth', 'can:accepted']);






Route::post('interventions/{intervention}', [InterventionController::class, 'update'])
->name('interventions.update')
    ->middleware(['auth', 'can:accepted']);


Route::delete('interventions/{intervention}', [InterventionController::class, 'destroy'])
->name('interventions.destroy')
    ->middleware(['auth', 'can:accepted']);


Route::put('interventions/{intervention}/restore', [InterventionController::class, 'restore'])
->name('interventions.restore')
    ->middleware(['auth', 'can:accepted']);





    Route::get('solitaryInterventions/{intervention}/edit', [InterventionController::class, 'solitaryEdit'])
    ->name('interventions.edit')
        ->middleware(['auth', 'can:accepted']);

    Route::post('solitaryInterventions', [InterventionController::class, 'solitaryStore'])
    ->name('interventions.solitaryStore')
        ->middleware(['auth', 'can:accepted']);




  // projects

  Route::get('projects', [ProjectController::class, 'index'])
  ->name('projects')
      ->middleware(['auth', 'can:accepted']);


  Route::get('projects/create', [ProjectController::class, 'create'])
  ->name('projects.create')
      ->middleware(['auth', 'can:accepted']);


  Route::post('projects', [ProjectController::class, 'store'])
  ->name('projects.store')
      ->middleware(['auth', 'can:accepted']);


  Route::get('projects/{project}/edit', [ProjectController::class, 'edit'])
  ->name('projects.edit')
      ->middleware(['auth', 'can:accepted']);

  Route::get('projects/{project}/show', [ProjectController::class, 'show'])
  ->name('projects.show')
      ->middleware(['auth', 'can:accepted']);

  Route::post('projects/{project}', [ProjectController::class, 'update'])
  ->name('projects.update')
      ->middleware(['auth', 'can:accepted']);



  Route::delete('projects/{project}', [ProjectController::class, 'destroy'])
  ->name('projects.destroy')
      ->middleware(['auth', 'can:accepted']);


  Route::put('projects/{project}/restore', [ProjectController::class, 'restore'])
  ->name('projects.restore')
      ->middleware(['auth', 'can:accepted']);

