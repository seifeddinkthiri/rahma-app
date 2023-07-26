<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\WaitingUsersController;
use App\Http\Controllers\ContactsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\ImagesController;
use App\Http\Controllers\OrganizationsController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\IndividualController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\InterventionController;
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
// Organizations

Route::get('organizations', [OrganizationsController::class, 'index'])
    ->name('organizations')
    ->middleware('auth');

Route::get('organizations/create', [OrganizationsController::class, 'create'])
    ->name('organizations.create')
    ->middleware('auth');

Route::post('organizations', [OrganizationsController::class, 'store'])
    ->name('organizations.store')
    ->middleware('auth');

Route::get('organizations/{organization}/edit', [OrganizationsController::class, 'edit'])
    ->name('organizations.edit')
    ->middleware('auth');

Route::put('organizations/{organization}', [OrganizationsController::class, 'update'])
    ->name('organizations.update')
    ->middleware('auth');

Route::delete('organizations/{organization}', [OrganizationsController::class, 'destroy'])
    ->name('organizations.destroy')
    ->middleware('auth');

Route::put('organizations/{organization}/restore', [OrganizationsController::class, 'restore'])
    ->name('organizations.restore')
    ->middleware('auth');


// individuals

Route::get('individuals', [IndividualController::class, 'index'])
    ->name('individuals')
    ->middleware('auth');

Route::get('individuals/create', [IndividualController::class, 'create'])
    ->name('individuals.create')
    ->middleware('auth');

Route::post('individuals', [IndividualController::class, 'store'])
    ->name('individuals.store')
    ->middleware('auth');

Route::get('individuals/{individual}/edit', [IndividualController::class, 'edit'])
    ->name('individuals.edit')
    ->middleware('auth');

Route::put('individuals/{individual}', [IndividualController::class, 'update'])
    ->name('individuals.update')
    ->middleware('auth');

Route::delete('individuals/{individual}/', [IndividualController::class, 'destroy'])
    ->name('individuals.destroy')
    ->middleware('auth');

Route::put('individuals/{individual}/restore', [IndividualController::class, 'restore']);


//Individual health status

Route::put('healthStatus/{individual}', [IndividualController::class, 'update_health_status'])
->name('health.update')
->middleware('auth');


// families

Route::get('families', [FamilyController::class, 'index'])
    ->name('families')
    ->middleware('auth');

Route::get('families/create', [FamilyController::class, 'create'])
    ->name('families.create')
    ->middleware('auth');
    Route::get('families/{Family}/create_B_C', [FamilyController::class, 'create_B_C'])
    ->name('families.create_B_C')
    ->middleware('auth');

Route::post('families', [FamilyController::class, 'store'])
    ->name('families.store')
    ->middleware('auth');
    Route::post('families_B_C/{Family}', [FamilyController::class, 'Store_B_C'])
    ->name('families.Store_B_C')
    ->middleware('auth');
Route::get('families/{family}/edit', [FamilyController::class, 'edit'])
    ->name('families.edit')
    ->middleware('auth');

Route::post('families_update/{family}', [FamilyController::class, 'update'])
    ->name('families.update')
    ->middleware('auth');

Route::delete('families/{family}', [FamilyController::class, 'destroy'])
    ->name('families.destroy')
    ->middleware('auth');

Route::put('families/{family}/restore', [FamilyController::class, 'restore'])
    ->name('families.restore')
    ->middleware('auth');

    //members

    Route::get('members/{family}/create_new_one', [MemberController::class, 'create_new_one'])
    ->name('members.create_new_one')
    ->middleware('auth');

    Route::get('members/{family}/create', [MemberController::class, 'create'])
    ->name('members.create')
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

    Route::put('members/{member}', [MemberController::class, 'update'])
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
    ->name('health.update')
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

 Route::put('home/{family}', [HomeController::class, 'update'])
 ->name('home.update')
 ->middleware('auth');

 Route::delete('home/{family}', [HomeController::class, 'destroy'])
 ->name('home.destroy')
 ->middleware('auth');

 Route::put('restore_home/{family}/restore', [HomeController::class, 'restore'])
 ->name('homes.restore')
 ->middleware('auth');




    Route::put('facilities/{family}', [FacilityController::class, 'update'])
    ->name('facilities.update')
    ->middleware('auth');

    Route::delete('facilities/{family}', [FacilityController::class, 'destroy'])
    ->name('facilities.destroy')
    ->middleware('auth');

    Route::put('restore_facilities/{family}/restore', [FacilityController::class, 'restore'])
    ->name('facilities.restore')
    ->middleware('auth');
// Contacts

Route::get('contacts', [ContactsController::class, 'index'])
    ->name('contacts')
    ->middleware('auth');

Route::get('contacts/create', [ContactsController::class, 'create'])
    ->name('contacts.create')
    ->middleware('auth');

Route::post('contacts', [ContactsController::class, 'store'])
    ->name('contacts.store')
    ->middleware('auth');

Route::get('contacts/{contact}/edit', [ContactsController::class, 'edit'])
    ->name('contacts.edit')
    ->middleware('auth');

Route::put('contacts/{contact}', [ContactsController::class, 'update'])
    ->name('contacts.update')
    ->middleware('auth');

Route::delete('contacts/{contact}', [ContactsController::class, 'destroy'])
    ->name('contacts.destroy')
    ->middleware('auth');

Route::put('contacts/{contact}/restore', [ContactsController::class, 'restore'])
    ->name('contacts.restore')
    ->middleware('auth');

// Reports

Route::get('reports', [ReportsController::class, 'index'])
    ->name('reports')
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

Route::put('interventions/{intervention}', [InterventionController::class, 'update'])
->name('interventions.update')
->middleware('auth');

Route::delete('interventions/{intervention}', [InterventionController::class, 'destroy'])
->name('interventions.destroy')
->middleware('auth');

Route::put('interventions/{intervention}/restore', [InterventionController::class, 'restore'])
->name('interventions.restore')
->middleware('auth');
