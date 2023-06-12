<?php

namespace App\Http\Controllers;

use App\Models\Family;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class FamilyController extends Controller
{
    public function index()
    {
        return Inertia::render('Families/Index', [
            'filters' => Request::all('search', 'trashed'),
            'families' => Auth::user()->account->families()
                ->orderBy('name')
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($Family) => [
                    'id' => $Family->id,
                    'name' => $Family->name,
                    'photo' => $Family->photo,
                    'deleted_at' => $Family->deleted_at,
                ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('Families/Create');
    }

    public function store()
    {

        Request::validate([
            'name' => ['required', 'max:100'],
            'photo' => ['nullable', 'image'],

        ]);









      $family =  Auth::user()->account->Families()->create(
            [
                'name' => Request::get('name'),
                'photo' => Request::file('photo') ? Request::file('photo')->store('') : null,

            ]
        );
        Request::file('photo') ->move(public_path('uploads'), $family->photo);

        return Redirect::route('families')->with('success', 'Family created.');
    }

    public function edit(Family $family)
    {
        $members = $family->members()
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString()
            ->through(function ($member) {
                return [
                    'id' => $member->id,
                    'name' => $member->name,
                    'kinship' => $member->kinship,
                    'caregiver' => $member->caregiver,
                    'deleted_at' => $member->deleted_at,
                ];
            });
        $notes = $family->notes()
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString()
            ->through(function ($note) {
                return [
                    'id' => $note->id,
                    'title' => $note->title,
                    'value' => $note->value,
                    'deleted_at' => $note->deleted_at,
                ];
            });





        return Inertia::render('Families/Edit', [
            'family' => [
                'id' => $family->id,
                'name' => $family->name,
                'photo' => $family->photo,
                'deleted_at' => $family->deleted_at,
                'members' => $members,
                'notes' => $notes,
                'facilities' =>  $family->facilities()->get(),
                'home' => $family->home()->get(),
            ],
        ]);
    }



    public function update(Family $Family)
    {
        $Family->update(
            [
                'name' => Request::get('name'),
                'photo' => Request::file('photo') ? Request::file('photo')->store('') : null,

            ]
        );
        Request::file('photo') ->move(public_path('uploads'), $Family->photo);

        return Redirect::back()->with('success', 'Family updated.');
    }

    public function destroy(Family $Family)
    {
        $Family->delete();

        return Redirect::back()->with('success', 'Family deleted.');
    }

    public function restore(Family $Family)
    {
        $Family->restore();

        return Redirect::back()->with('success', 'Family restored.');
    }
}
