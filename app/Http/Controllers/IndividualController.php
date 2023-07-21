<?php

namespace App\Http\Controllers;

use App\Models\Individual;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class IndividualController extends Controller
{
    public function index()
    {
        return Inertia::render('Individuals/Index', [
            'filters' => Request::all('search', 'trashed'),
            'individuals' => Auth::user()->account->individuals()
                ->orderBy('name')
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($Individual) => [
                    'id' => $Individual->id,
                    'photo' => $Individual->photo,
                    'phone' => $Individual->phone,
                    'address' => $Individual->address,
                    'cin' => $Individual->cin,
                    'gender' => $Individual->gender,
                    'birth_date' => $Individual->birth_date,
                    'birth_city' => $Individual->birth_city,
                    'social_status' => $Individual->social_status,
                    'monthly_income' => $Individual->monthly_income,
                    'health_insurance' => $Individual->health_insurance,
                    'education_level' => $Individual->education_level,
                    'job' => $Individual->job,
                    'job_place' => $Individual->job_place,
                    'deleted_at' => $Individual->deleted_at,
                ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('Individuals/Create');
    }

    public function store()
    {

        Request::validate([
            'photo' => 'nullable|string',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'cin' => 'nullable|string',
            'gender' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'birth_city' => 'nullable|string',
            'social_status' => 'nullable|string',
            'monthly_income' => 'nullable|numeric',
            'health_insurance' => 'nullable|string',
            'education_level' => 'nullable|string',
            'job' => 'nullable|string',
            'job_place' => 'nullable|string',
        ]);

        $Individual =  Auth::user()->account->Individuals()->create(
            [
                'photo' => Request::input('photo'),
                'phone' => Request::input('phone'),
                'address' => Request::input('address'),
                'cin' => Request::input('cin'),
                'gender' => Request::input('gender'),
                'birth_date' => Request::input('birth_date'),
                'birth_city' => Request::input('birth_city'),
                'social_status' => Request::input('social_status'),
                'monthly_income' => Request::input('monthly_income'),
                'health_insurance' => Request::input('health_insurance'),
                'education_level' => Request::input('education_level'),
                'job' => Request::input('job'),
                'job_place' => Request::input('job_place'),
                'photo' => Request::file('photo') ? Request::file('photo')->store('') : null,

            ]
        );
        if (Request::file('photo')) {
            Request::file('photo')->move(public_path('uploads'), $Individual->photo);
        }

        return redirect()->route('individuals.create', ['family' => $Individual])->with('success', 'Individual created');
    }

    public function edit(Individual $individual)
    {






        return Inertia::render('Individuals/Edit', [
            'individual' => [
                'id' => $individual->id,
                'photo' => $individual->photo,
                'phone' => $individual->phone,
                'address' => $individual->address,
                'cin' => $individual->cin,
                'gender' => $individual->gender,
                'birth_date' => $individual->birth_date,
                'birth_city' => $individual->birth_city,
                'social_status' => $individual->social_status,
                'monthly_income' => $individual->monthly_income,
                'health_insurance' => $individual->health_insurance,
                'education_level' => $individual->education_level,
                'job' => $individual->job,
                'job_place' => $individual->job_place,

                'deleted_at' => $individual->deleted_at,
                'notes' => $individual->notes()->get(),
                'facilities' =>  $individual->facilities()->get(),
                'home' => $individual->home()->get(),
            ],
        ]);
    }

    public function update(Individual $individual)
    {
        Request::validate([
            'photo' => 'nullable|string',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'cin' => 'nullable|string',
            'gender' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'birth_city' => 'nullable|string',
            'social_status' => 'nullable|string',
            'monthly_income' => 'nullable|numeric',
            'health_insurance' => 'nullable|string',
            'education_level' => 'nullable|string',
            'job' => 'nullable|string',
            'job_place' => 'nullable|string',
        ]);


        $data = [
            'photo' => Request::input('photo'),
            'phone' => Request::input('phone'),
            'address' => Request::input('address'),
            'cin' => Request::input('cin'),
            'gender' => Request::input('gender'),
            'birth_date' => Request::input('birth_date'),
            'birth_city' => Request::input('birth_city'),
            'social_status' => Request::input('social_status'),
            'monthly_income' => Request::input('monthly_income'),
            'health_insurance' => Request::input('health_insurance'),
            'education_level' => Request::input('education_level'),
            'job' => Request::input('job'),
            'job_place' => Request::input('job_place'),
            'photo' => Request::file('photo') ? Request::file('photo')->store('') : null,
        ];

        if (Request::file('photo')) {
            Request::file('photo')->move(public_path('uploads'), $individual->photo);
        }

        $individual->update($data);

        return Redirect::back()->with('success', 'Individual updated.');
    }

    public function destroy(Individual $individual)
    {
        $individual->delete();

        return Redirect::back()->with('success', 'Individual deleted.');
    }

    public function restore(Individual $Individual)
    {
        $Individual->restore();

        return Redirect::back()->with('success', 'Individual restored.');
    }
}
