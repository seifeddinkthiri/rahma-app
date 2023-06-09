<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Family;
use App\Models\HealthStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class MemberController extends Controller
{
    public function index(Family $Family)
    {
        return Inertia::render('Families/Members/Index', [
            'filters' => Request::all('search', 'trashed'),
            'members' => $Family->members()
                ->orderBy('name')
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($Member) => [
                    'id' => $Member->id,
                    'name' => $Member->name,
                    'address' => $Member->address,
                    'deleted_at' => $Member->deleted_at,
                ]),
        ]);
    }

    public function create(Family $Family)
    {
        return Inertia::render('Families/Members/Create',compact('Family'));
    }


    public function store()
    {
        $validatedData = Request::validate([
            'name' => ['required', 'max:100'],
            'address' => ['required', 'max:100'],
            'cin' => ['required', 'integer', 'digits:8'],
            'phone' => ['required', 'integer', 'digits:8'],
            'birth_date' => ['required', 'date'],
            'birth_city' => ['required', 'max:100'],
            'social_status' => ['required', 'max:100'],
            'monthly_income' => ['required', 'integer'],
            'health_insurance' => ['required', 'boolean'],
            'kinship' => ['required', 'max:100'],
            'caregiver' => ['required', 'boolean'],
            'education_level' => ['required', 'max:100'],
            'job' => ['required', 'max:100'],
            'job_place' => ['required', 'max:100'],
            'family_id' => ['required', 'integer'],

        ]);

        $member = Auth::user()->account->members()->create($validatedData);

       // Create the health status
$validatedData = Request::validate([
    'good' => ['required', 'boolean'],
    'disease' => ['required', 'string', 'max:100'],
    'disability' => ['required', 'string', 'max:100'],
    'disability_card_number' => ['required', 'integer','digits:8'],
]);

$healthStatus = new HealthStatus([
    'good' => $validatedData['good'],
    'disease' => $validatedData['disease'],
    'disability' => $validatedData['disability'],
    'disability_card_number' => $validatedData['disability_card_number'],
]);

        $member->healthStatus()->save($healthStatus);

        return redirect()->route('families.edit', ['family' => $member->family])->with('success', 'Member created');
    }


    public function edit(Member $Member)
    {
        return Inertia::render('Families/Members/Edit', [
            'member' => [
                'id' => $Member->id,
                'name' => $Member->name,
                'address' => $Member->address,
                'cin' => $Member->cin,
                'phone' => $Member->phone,
                'birth_date' => $Member->birth_date,
                'birth_city' => $Member->birth_city,
                'social_status' => $Member->social_status,
                'monthly_income' => $Member->monthly_income,
                'health_insurance' => $Member->health_insurance,
                'kinship' => $Member->kinship,
                'caregiver' => $Member->caregiver,
                'education_level' => $Member->education_level,
                'job' => $Member->job,
                'job_place' => $Member->job_place,
                'family_id' => $Member->family_id,
                'healthStatus' => $Member->healthStatus()->get(),
                'deleted_at' => $Member->deleted_at,
            ],
        ]);
    }

    public function update_health_status(Member $Member)
    {
         Request::validate([
            'good' => ['required', 'boolean'],
            'disease' => ['required', 'string', 'max:100'],
            'disability' => ['required', 'string', 'max:100'],
            'disability_card_number' => ['required', 'integer','digits:8'],
        ]);

        $Member->healthStatus()->update([
            'good' => Request::input('good'),
            'disease' => Request::input('disease'),
            'disability' => Request::input('disability'),
            'disability_card_number' => Request::input('disability_card_number'),
        ]);

        return redirect()->route('members.edit', ['member' => $Member])->with('success', 'Member updated');
    }





    public function update(Member $Member)
    {
        $Member->update(
            Request::validate([
                'name' => ['required', 'max:100'],
                'address' => ['required', 'max:100'],
                'cin' => ['required', 'integer', 'digits:8'],
                'phone' => ['required', 'integer', 'digits:8'],
                'birth_date' => ['required', 'date'],
                'birth_city' => ['required', 'max:100'],
                'social_status' => ['required', 'max:100'],
                'monthly_income' => ['required', 'integer'],
                'health_insurance' => ['required', 'max:100'],
                'kinship' => ['required', 'max:100'],
                'caregiver' => ['required', 'boolean'],
                'education_level' => ['required', 'max:100'],
                'job' => ['required', 'max:100'],
                'job_place' => ['required', 'max:100'],
                'family_id' => ['required', 'integer'],
            ])
        );

        return Redirect::route('families.edit', ['family' => $Member->family_id])
        ->with('success', 'Member updated');
    }

    public function destroy(Member $Member)
    {
        $Member->delete();

        return Redirect::back()->with('success', 'Member deleted');
    }

    public function restore(Member $Member)
    {
        $Member->restore();

        return Redirect::back()->with('success', 'Member restored.');
    }
}
