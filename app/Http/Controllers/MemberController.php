<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Family;

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
        Auth::user()->account->members()->create(
            Request::validate([
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
                'family_id' => ['required', 'integer']
            ])

        );

        return Redirect::back()->with('success', 'Member created.');

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

        return Redirect::back()->with('success', 'Member deleted.');
    }

    public function restore(Member $Member)
    {
        $Member->restore();

        return Redirect::back()->with('success', 'Member restored.');
    }
}
