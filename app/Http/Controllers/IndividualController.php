<?php

namespace App\Http\Controllers;

use App\Models\HealthStatus;
use App\Models\Individual;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Session;

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
                    'name' => $Individual->name,
                    'phone' => $Individual->phone,
                    'address' => $Individual->address,
                    'cin' => $Individual->cin,
                    'gender' => $Individual->gender,
                    'deleted_at' => $Individual->deleted_at,
                ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('Individuals/Create');
    }

    public function create_B_C(Individual $Individual)
    {
        $Individual->notes()->delete();
        $Individual->home()->delete();
        $facility = $Individual->facilities; // Assuming you have a hasOne relationship defined for 'facilities' in the Family model

        if ($facility) {
            $facility->reinitialise();
            $facility->save(); // Save the changes to the database if needed
        }
        Session::flash('success', 'تم حذف ما تم اضافته، يمكنك إعادة العملية ');
        return Inertia::render('Individuals/create_B_C',[
            'Individual' => [
                'id' => $Individual->id,
                'name' => $Individual->name,
                'address' => $Individual->address,
                'cin' => $Individual->cin,
                'phone' => $Individual->phone,
                'photo' => $Individual->photo,
                'birth_date' => $Individual->birth_date,
                'birth_city' => $Individual->birth_city,
                'social_status' => $Individual->social_status,
                'monthly_income' => $Individual->monthly_income,
                'gender' => $Individual->gender,
                'education_level' => $Individual->education_level,
                'job' => $Individual->job,
                'job_place' => $Individual->job_place,
                'healthStatus' =>[
                    'health_insurance' => $Individual->healthStatus->health_insurance,
                    'good' => $Individual->healthStatus->good,
                    'disability' => $Individual->healthStatus->disability,
                    'disability_card_number' => $Individual->healthStatus->disability_card_number,
                    'disease' => $Individual->healthStatus->disease,
                ]
            ],
        ]);
    }

    public function store()
    {

        Request::validate([
            'name' => ['required', 'max:100'],
            'address' => ['required', 'max:100'],
            'photo' => ['nullable', 'image'],
            'gender' => ['required'],
            'cin' => 'nullable|integer||digits:8|unique:'.Individual::class,
            'phone' => 'nullable|integer||digits:8|unique:'.Individual::class,
            'birth_date' => ['required', 'date'],
            'birth_city' => ['required', 'max:100'],
            'social_status' => ['nullable', 'max:100'],
            'monthly_income' => ['nullable', 'integer'],
            'education_level' => ['nullable', 'max:100'],
            'job' => ['nullable', 'max:100'],
            'job_place' => ['nullable', 'max:100'],
        ]);

        $Individual =  Auth::user()->account->Individuals()->create(
            [
                'photo' => Request::get('photo'),
                'phone' => Request::get('phone'),
                'name' => Request::get('name'),
                'address' => Request::get('address'),
                'cin' => Request::get('cin'),
                'gender' => Request::get('gender'),
                'birth_date' => Request::get('birth_date'),
                'birth_city' => Request::get('birth_city'),
                'social_status' => Request::get('social_status'),
                'monthly_income' => Request::get('monthly_income'),
                'education_level' => Request::get('education_level'),
                'job' => Request::get('job'),
                'job_place' => Request::get('job_place'),
                'photo' => Request::file('photo') ? Request::file('photo')->store('') : null,

            ]
        );
        if (Request::file('photo')) {
            Request::file('photo')->move(public_path('uploads'), $Individual->photo);
        }


        $Individual->healthStatus()->create(
            Request::validate([
                'health_insurance' => ['nullable', 'boolean'],
                'good' => ['nullable', 'boolean'],
                'disease' => ['nullable', 'string', 'max:100'],
                'disability' => ['nullable', 'string', 'max:100'],
                'disability_card_number' => ['nullable', 'integer', 'digits:8'],
            ])
        );

        return redirect()->route('individuals.Create_complet', ['Individual' => $Individual])->with('success', 'تم انشاء العضو.   ');
    }

    public function Create_complet(Individual $Individual)
    {
        return Inertia::render('Individuals/Create_complet', compact('Individual'));
    }
    public function edit(Individual $individual)
    {
        $notes = $individual->notes()
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
        return Inertia::render('Individuals/Edit', [
            'individual' => [
                'photo' => $individual->photo,
                'id' => $individual->id,
                'name' => $individual->name,
                'phone' => $individual->phone,
                'address' => $individual->address,
                'cin' => $individual->cin,
                'gender' => $individual->gender,
                'birth_date' => $individual->birth_date,
                'birth_city' => $individual->birth_city,
                'social_status' => $individual->social_status,
                'monthly_income' => $individual->monthly_income,
                'education_level' => $individual->education_level,
                'job' => $individual->job,
                'job_place' => $individual->job_place,
                'deleted_at' => $individual->deleted_at,
                
                'healthStatus' => $individual->healthStatus()->get(),
                'facilities' =>  $individual->facilities()->get(),
                'home' => $individual->home()->get(),
                'notes' => $notes,
            ],
        ]);
    }

    public function update(Individual $individual)
    {
        Request::validate([
            'name' => ['required', 'max:100'],
            'address' => ['nullable', 'max:100'],
            'photo' => ['nullable', 'image'],
            'gender' => ['nullable', 'max:100'],
            'cin' => ['required', 'integer', 'digits:8'],
            'phone' => ['required', 'integer', 'digits:8'],
            'birth_date' => ['required', 'date'],
            'birth_city' => ['required', 'max:100'],
            'social_status' => ['nullable', 'max:100'],
            'monthly_income' => ['nullable', 'integer'],
            'education_level' => ['nullable', 'max:100'],
            'job' => ['nullable', 'max:100'],
            'job_place' => ['nullable', 'max:100'],
        ]);


        $data = [
            'photo' => Request::get('photo'),
            'name' => Request::get('name'),
            'phone' => Request::get('phone'),
            'address' => Request::get('address'),
            'cin' => Request::get('cin'),
            'gender' => Request::get('gender'),
            'birth_date' => Request::get('birth_date'),
            'birth_city' => Request::get('birth_city'),
            'social_status' => Request::get('social_status'),
            'monthly_income' => Request::get('monthly_income'),
            'education_level' => Request::get('education_level'),
            'job' => Request::get('job'),
            'job_place' => Request::get('job_place'),
            'photo' => Request::file('photo') ? Request::file('photo')->store('') : null,
        ];

        if (Request::file('photo')) {
            Request::file('photo')->move(public_path('uploads'), $individual->photo);
        }

        $individual->update($data);

        return Redirect::back()->with('success','تم تحديث العضو');
    }

    public function create_B_C_update(Individual $individual)
    {
        Request::validate([
            'name' => ['required', 'max:100'],
            'address' => ['nullable', 'max:100'],
            'photo' => ['nullable', 'image'],
            'gender' => ['nullable', 'max:100'],
            'cin' => ['required', 'integer', 'digits:8'],
            'phone' => ['required', 'integer', 'digits:8'],
            'birth_date' => ['required', 'date'],
            'birth_city' => ['required', 'max:100'],
            'social_status' => ['nullable', 'max:100'],
            'monthly_income' => ['nullable', 'integer'],
            'education_level' => ['nullable', 'max:100'],
            'job' => ['nullable', 'max:100'],
            'job_place' => ['nullable', 'max:100'],
            'health_insurance' => ['nullable', 'boolean'],
            'good' => ['nullable', 'boolean'],
            'disease' => ['nullable', 'string', 'max:100'],
            'disability' => ['nullable', 'string', 'max:100'],
            'disability_card_number' => ['nullable', 'integer', 'digits:8'],
        ]);


        $data = [
            'photo' => Request::get('photo'),
            'name' => Request::get('name'),
            'phone' => Request::get('phone'),
            'address' => Request::get('address'),
            'cin' => Request::get('cin'),
            'gender' => Request::get('gender'),
            'birth_date' => Request::get('birth_date'),
            'birth_city' => Request::get('birth_city'),
            'social_status' => Request::get('social_status'),
            'monthly_income' => Request::get('monthly_income'),
            'education_level' => Request::get('education_level'),
            'job' => Request::get('job'),
            'job_place' => Request::get('job_place'),
            'photo' => Request::file('photo') ? Request::file('photo')->store('') : null,
        ];
        $datahelth = [
            'health_insurance' => Request::get('health_insurance'),
            'good' => Request::get('good'),
            'disease' => Request::get('disease'),
            'disability' => Request::get('disability'),
            'disability_card_number' => Request::get('disability_card_number'),
        ];

        if (Request::file('photo')) {
            Request::file('photo')->move(public_path('uploads'), $individual->photo);
        }

        $individual->update($data);

        $individual->healthStatus()->update($datahelth);

        return redirect()->route('individuals.Create_complet', ['Individual' => $individual])->with('success','تم تحديث العضو');

    }








    public function update_health_status(Individual $Individual)
    {
        Request::validate([
            'health_insurance' => ['nullable', 'boolean'],
            'good' => ['required', 'boolean'],
            'disease' => ['nullable', 'string', 'max:100'],
            'disability' => ['nullable', 'string', 'max:100'],
            'disability_card_number' => ['nullable', 'integer', 'digits:8'],
        ]);

        $Individual->healthStatus()->update([
            'health_insurance' => Request::input('health_insurance'),
            'good' => Request::input('good'),
            'disease' => Request::input('disease'),
            'disability' => Request::input('disability'),
            'disability_card_number' => Request::input('disability_card_number'),
        ]);

        return redirect()->route('individuals.edit', ['individual' => $Individual])->with('success', 'تم تحديث الحالة الصحية للعضو ');
    }











    public function destroy(Individual $individual)
    {
        $individual->delete();

        return Redirect::back()->with('success', 'تم حذف العضو');
    }

    public function restore(Individual $Individual)
    {
        $Individual->restore();

        return Redirect::back()->with('success','تم استعادة العضو');
    }
}
