<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class WaitingUsersController extends Controller
{
    public function index()
    {
        $currentUser = Auth::user();

        $users = $currentUser->account->users()
            ->orderByName()
            ->filter(Request::only('search', 'role', 'trashed'))
            ->where('id', '!=', $currentUser->id) // Exclude the current user
            ->get()
            ->transform(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'owner' => $user->owner,
                    'admin' => $user->admin,
                    'wait' => $user->wait,
                    'photo' => $user->photo_path ? URL::route('image', ['path' => $user->photo_path, 'w' => 40, 'h' => 40, 'fit' => 'crop']) : null,
                    'deleted_at' => $user->deleted_at,
                ];
            })
            ->where('admin', false);

        return Inertia::render('waiting_list/index', [
            'filters' => Request::all('search', 'role', 'trashed'),
            'users' => $users,
        ]);
    }

    public function edit(User $user)
    {

        $currentUserId = Auth::user()->id;
        if (!$user -> admin && $user->id !== $currentUserId ) {
            $message='';
            if($user->wait){
                $user->wait = false;
                $message = 'تم قبول المستخدم';
            }
            else{
                $user->wait = true;
                $user->owner = false;
                $message = 'تم حضر المستخدم';
            }
            $user->save();
            return Redirect::back()->with('success', $message);

        }


    }
    public function delete_demonde(User $user)
    {
        $currentUserId = Auth::user()->id;
        if (!$user -> admin && $user->id !== $currentUserId ) {
            $user->forceDelete();
            return Redirect::back()->with('success', 'تم حذف المستخدم.');
        }

    }

}
