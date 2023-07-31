<?php

namespace App\Http\Controllers;

use App\Models\Family;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Illuminate\Support\Facades\Session;

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
                    'phone' => $Family->caregiver_phone,
                    'address' => $Family->address,
                    'photo' => $Family->photo,
                    'deleted_at' => $Family->deleted_at,
                    'members' => $Family->members()->get(),
                ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('Families/Create');
    }
    public function create_B_C(Family $Family)
    {
        $Family->members()->forceDelete();
        $Family->notes()->delete();
        $Family->home()->delete();
        $facility = $Family->facilities; // Assuming you have a hasOne relationship defined for 'facilities' in the Family model

        if ($facility) {
            $facility->reinitialise();
            $facility->save(); // Save the changes to the database if needed
        }
        Session::flash('success', 'تم حذف ما تم اضافته، يمكنك إعادة العملية ');
        return Inertia::render('Families/create_B_C', compact('Family'));

    }



    public function Store_B_C(Family $Family)
    {

        Request::validate([
            'caregiver_phone' => [
                'required',
                'numeric',
                'digits:8',
                Rule::unique('families')->ignore($Family->id),
            ],            'wife' => ['nullable', 'boolean'],
            'husband' => ['nullable', 'boolean'],
            'photo' => ['nullable', 'image'],
            'elderlies_number' => ['nullable', 'numeric'],
            'childrens_number' => ['nullable', 'numeric'],
            'other_members_number' => ['nullable', 'numeric'],
        ]);

        $Family =  Auth::user()->account->Families()->update(
            [
                'caregiver_phone' => Request::get('caregiver_phone'),
                'wife' => Request::get('wife'),
                'husband' => Request::get('husband'),
                'photo' => Request::get('photo'),
                'elderlies_number' => Request::get('elderlies_number'),
                'childrens_number' => Request::get('childrens_number'),
                'other_members_number' => Request::get('other_members_number'),

                'photo' => Request::file('photo') ? Request::file('photo')->store('') : null,

            ]
        );
        if (Request::file('photo')) {
            Request::file('photo')->move(public_path('uploads'), $Family->photo);
        }

        return redirect()->route('members.create', ['family' => $Family])->with('success', 'تم تحديث بيانات الأسرة. تابع العملية');
    }

    public function store()
    {

        Request::validate([
            'caregiver_phone' => 'required|numeric||digits:8|unique:'.Family::class,
            'wife' => ['nullable', 'boolean'],
            'husband' => ['nullable', 'boolean'],
            'photo' => ['nullable', 'image'],
            'elderlies_number' => ['nullable', 'numeric'],
            'childrens_number' => ['nullable', 'numeric'],
            'other_members_number' => ['nullable', 'numeric'],
        ]);

        $Family =  Auth::user()->account->Families()->create(
            [
                'caregiver_phone' => Request::get('caregiver_phone'),
                'wife' => Request::get('wife'),
                'husband' => Request::get('husband'),
                'photo' => Request::get('photo'),
                'elderlies_number' => Request::get('elderlies_number'),
                'childrens_number' => Request::get('childrens_number'),
                'other_members_number' => Request::get('other_members_number'),

                'photo' => Request::file('photo') ? Request::file('photo')->store('') : null,

            ]
        );
        if (Request::file('photo')) {
            Request::file('photo') ->move(public_path('uploads'), $Family->photo);

        }

        return redirect()->route('members.create', ['family' => $Family])->with('success', 'تم انشاء الأسرة. الرجاء إضافة أعضاء');

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
                'phone' => $family->caregiver_phone,
                'address' => $family->address,
                'deleted_at' => $family->deleted_at,
                'members' => $members,
                'notes' => $notes,
                'facilities' =>  $family->facilities()->get(),
                'home' => $family->home()->get(),
            ],
        ]);
    }
    public function show(Family $family)
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
                    'healthStatus' => $member->healthStatus()->get(),
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





        return Inertia::render('Families/Show', [
            'family' => [
                'id' => $family->id,
                'name' => $family->name,
                'photo' => $family->photo,
                'phone' => $family->caregiver_phone,
                'address' => $family->address,
                'deleted_at' => $family->deleted_at,
                'members' => $members,
                'notes' => $notes,
                'facilities' =>  $family->facilities()->get(),
                'home' => $family->home()->get(),
            ],
        ]);
    }
    public function update(Family $family)
    {
        Request::validate([
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name' => Request::input('name'),
            'caregiver_phone' => Request::input('phone'),
            'address' => Request::input('address'),
        ];

        if (Request::hasFile('photo')) {
            $photoPath = Request::file('photo')->store('');
            $data['photo'] = $photoPath;
            Request::file('photo')->move(public_path('uploads'), $photoPath);
        }

        $family->update($data);

        return Redirect::back()->with('success', 'تم تحديث العائلة');
    }

    public function destroy(Family $Family)
    {
        $Family->delete();

        return Redirect::back()->with('success', 'تم حذف العائلة.');
    }

    public function restore(Family $Family)
    {
        $Family->restore();

        return Redirect::back()->with('success', 'تم استعادة الأسرة.');
    }
}
