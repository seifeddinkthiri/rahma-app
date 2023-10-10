<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Illuminate\Support\Facades\Session;

class FamilyController extends Controller
{




    public function beneficials()
    {
        $emptyFamilies = Family::doesntHave('members')->get();
        foreach ($emptyFamilies as $emptyFamily) {
            $emptyFamily->forceDelete();
        }

        return Inertia::render('beneficials', [
            'filters' => Request::all('search', 'social_status','status', 'trashed'),
            'families' => Auth::user()->account->families()
                ->orderBy('name')
                ->filter(Request::only('search', 'social_status', 'status','trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($Family) => [
                    'id' => $Family->id,
                    'name' => $Family->name,
                    'phone' => $Family->caregiver_phone,
                    'address' => $Family->address,
                    'status' => $Family->status,
                    'is_family' => $Family->is_family,
                    'social_status' => $Family->social_status,
                    'photo' => $Family->photo,
                    'deleted_at' => $Family->deleted_at,
                    'members' => $Family->members()->get(),
                ]),

        ]);
    }


    public function store($type)
    {
        $Family =  Auth::user()->account->Families()->create(
            [
                'caregiver_phone' => null,
                'name' => null,
                'status' => null,
                'address' => null,
                'caregiver_phone' => null,
                'photo' => null,
                'status' => 'active',



            ]
        );
        if ($type !== 'family') {
            $Family->update([
                'social_status' => $type ,
            ]);
            $redirectUrl = route('specifics.create', ['Family' => $Family, 'type' => $type]);
            return Redirect::to($redirectUrl);
        } else {
            $Family->update([
                'is_family' => true,
                'social_status' => 'family',

            ]);
            return redirect()->route('members.create', ['Family' => $Family])->with('success', '   الرجاء إضافة معطيات الأسرة');
        }
    }


    public function edit(Family $family)
    {
        $projects = Project::select('id', 'name')->where('isSolitary',false)->get();

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
                'is_family' => $family->is_family,
                'status' => $family->status,
                'social_status' => $family->social_status,
                'deleted_at' => $family->deleted_at,
                'members' => $members,
                'notes' => $notes,
                'files' =>  $family->files()->get(),
                'facilities' =>  $family->facilities()->get(),
                'interventions' =>$family->interventions()->with('project')->get(),
                'home' => $family->home()->get(),
            ],
            'projects' => $projects,
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



            $projects = Project::select('id', 'name')->where('isSolitary',false)->get();


        return Inertia::render('Families/Show', [
            'family' => [
                'id' => $family->id,
                'name' => $family->name,
                'photo' => $family->photo,
                'phone' => $family->caregiver_phone,
                'address' => $family->address,
                'status' => $family->status,
                'is_family' => $family->is_family,
                'social_status' => $family->social_status,
                'deleted_at' => $family->deleted_at,
                'members' => $members,
                'notes' => $notes,
                'files' =>  $family->files()->get(),
                'facilities' =>  $family->facilities()->get(),
                'interventions' => $family->interventions()->get(),
                'home' => $family->home()->get(),
            ],
            'projects' =>$projects,
        ]);
    }
    public function update(Family $family)
    {
        Request::validate([
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'social_status' => 'required_if:isFamily,false|nullable',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name' => Request::input('name'),
            'caregiver_phone' => Request::input('phone'),
            'address' => Request::input('address'),
            'status' => Request::input('status'),
            'social_status' => Request::input('social_status'),


        ];

        if (Request::hasFile('photo')) {
            $photoPath = Request::file('photo')->store('');
            $data['photo'] = $photoPath;
            Request::file('photo')->move(public_path('uploads'), $photoPath);
        }

        $family->update($data);
        if (Request::input('social_status')!=='family') {
            $family->update([
                'is_family' => false,
            ]);         }
        return Redirect::back()->with('success', 'تم تحديث المنتفع');
    }

    public function destroy(Family $Family)
    {
        $Family->delete();

        return Redirect::back();
    }

    public function restore(Family $Family)
    {
        $Family->restore();

        return Redirect::back()->with('success', 'تم استعادة المنتفع.');
    }
}
