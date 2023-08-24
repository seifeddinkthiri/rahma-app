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
                    'status' => $Individual->status,
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
        return Inertia::render('Individuals/create_B_C', [
            'Individual' => [
                'id' => $Individual->id,
                'name' => $Individual->name,
                'address' => $Individual->address,
                'status' => $Individual->status,
                'cin' => $Individual->cin,
                'phone' => $Individual->phone,
                'photo' => $Individual->photo,
                'birth_date' => $Individual->birth_date,
                'birth_city' => $Individual->birth_city,
                'social_status' => $Individual->social_status,
                'gender' => $Individual->gender,
                'education_level' => $Individual->education_level,
                'job' => $Individual->job,
                'job_place' => $Individual->job_place,
                'grant' => $Individual->grant()->get(),
                'healthStatus' => [
                    'health_insurance' => $Individual->healthStatus->health_insurance,
                    'good' => $Individual->healthStatus->good,
                    'disability' => $Individual->healthStatus->disability,
                    'disability_card_number' => $Individual->healthStatus->disability_card_number,
                    'disease' => $Individual->healthStatus->disease,
                ]
            ],
        ]);
    }
    public function show(Individual $Individual)
    {

        $notes = $Individual->notes()
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

        return Inertia::render('Individuals/Show', [
            'individual' => [
                'id' => $Individual->id,
                'name' => $Individual->name,
                'photo' => $Individual->photo,
                'phone' => $Individual->phone,
                'status' => $Individual->status,
                'address' => $Individual->address,
                'deleted_at' => $Individual->deleted_at,
                'notes' => $notes,
                'files' =>  $Individual->files()->get(),
                'facilities' => $Individual->facilities()->get(),
                'home' => $Individual->home()->get(),
            ],
        ]);
    }
    public function store()
    {
        Request::validate([
            'name' => ['required', 'max:100'],
            'address' => ['required', 'max:100'],
            'cin' => 'required|numeric||digits:8|unique:' . Individual::class,
            'phone' => 'required|numeric||digits:8|unique:' . Individual::class,
            'photo' => ['nullable', 'image'],
            'gender' => ['nullable'],
            'birth_date' => ['nullable', 'date'],
            'birth_city' => ['nullable', 'max:100'],
            'social_status' => ['nullable', 'max:100'],
            'education_level' => ['nullable', 'max:100'],
            'job' => ['nullable', 'max:100'],
            'job_place' => ['nullable', 'max:100'],
        ]);

        $Grant_Validation = Request::validate([
            'grant_source' => ['nullable', 'required_with:grant_value',  'string', 'max:100'],
            'grant_value' => ['nullable', 'required_with:grant_source', 'integer'],
        ]);


        $H_S_Validation = Request::validate([
            'health_insurance' => ['nullable', 'boolean'],
            'good' => ['required', 'boolean'],
            'disease' => ['nullable', 'string', 'max:100'],
            'disability' => ['nullable', 'required_with:disability_card_number',  'string', 'max:100'],
            'disability_card_number' => ['nullable', 'required_with:disability', 'numeric', 'digits:8'],
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

            $H_S_Validation

        );

        if (Request::get('grant') == true) {
            $Grant_Validation = array_combine(['source', 'value'], array_values($Grant_Validation));

            $Individual->grant()->create(

                $Grant_Validation

            );
        }

        return redirect()->route('individuals.Create_all', ['Individual' => $Individual])->with('success', 'تم انشاء العضو.   ');
    }

    public function Create_all(Individual $Individual)
    {
        return Inertia::render('Individuals/Create_all', compact('Individual'));
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
                'status' => $individual->status,
                'cin' => $individual->cin,
                'gender' => $individual->gender,
                'birth_date' => $individual->birth_date,
                'birth_city' => $individual->birth_city,
                'social_status' => $individual->social_status,
                'education_level' => $individual->education_level,
                'job' => $individual->job,
                'job_place' => $individual->job_place,
                'deleted_at' => $individual->deleted_at,
                'files' =>  $individual->files()->get(),
                'healthStatus' => $individual->healthStatus()->get(),
                'facilities' =>  $individual->facilities()->get(),
                'interventions' =>  $individual->interventions()->get(),
                'home' => $individual->home()->get(),
                'grant' => $individual->grant()->get(),
                'notes' => $notes,
            ],
        ]);
    }

    public function update(Individual $individual)
    {
        Request::validate([
            'name' => ['required', 'max:100'],
            'address' => ['required', 'max:100'],
            'photo' => ['nullable', 'image'],
            'gender' => ['nullable', 'max:100'],
            'cin' => ['required', 'numeric', 'digits:8'],
            'phone' => ['required', 'numeric', 'digits:8'],
            'birth_date' => ['nullable', 'date'],
            'birth_city' => ['nullable', 'max:100'],
            'social_status' => ['nullable', 'max:100'],
            'education_level' => ['nullable', 'max:100'],
            'job' => ['nullable', 'max:100'],
            'job_place' => ['nullable', 'max:100'],
        ]);
        $Grant_Validation = Request::validate([
            'grant_source' => ['nullable', 'required_with:grant_value',  'string', 'max:100'],
            'grant_value' => ['nullable', 'required_with:grant_source', 'integer'],
        ]);



        $data = [
            'photo' => Request::get('photo'),
            'name' => Request::get('name'),
            'phone' => Request::get('phone'),
            'address' => Request::get('address'),
            'cin' => Request::get('cin'),
            'status' => Request::input('status'),
            'gender' => Request::get('gender'),
            'birth_date' => Request::get('birth_date'),
            'birth_city' => Request::get('birth_city'),
            'social_status' => Request::get('social_status'),
            'education_level' => Request::get('education_level'),
            'job' => Request::get('job'),
            'job_place' => Request::get('job_place'),
            'photo' => Request::file('photo') ? Request::file('photo')->store('') : null,
        ];

        if (Request::file('photo')) {
            Request::file('photo')->move(public_path('uploads'), $individual->photo);
        }

        $individual->update($data);
        if (Request::get('grant') == true) {
            $Grant_Validation = array_combine(['source', 'value'], array_values($Grant_Validation));
            $individual->grant()->update(

                $Grant_Validation

            );
        }
        else {
            $individual->grant()->update([
                'source' => null,
                'value' => null,
            ]);


        }
        return Redirect::back()->with('success', 'تم تحديث العضو');
    }

    public function create_B_C_update(Individual $individual)
    {
        Request::validate([
            'name' => ['required', 'max:100'],
            'address' => ['required', 'max:100'],
            'photo' => ['nullable', 'image'],
            'gender' => ['nullable', 'max:100'],
            'cin' => ['required', 'numeric', 'digits:8'],
            'phone' => ['required', 'numeric', 'digits:8'],
            'birth_date' => ['nullable', 'date'],
            'birth_city' => ['nullable', 'max:100'],
            'social_status' => ['nullable', 'max:100'],
            'education_level' => ['nullable', 'max:100'],
            'job' => ['nullable', 'max:100'],
            'job_place' => ['nullable', 'max:100'],
            'health_insurance' => ['nullable', 'boolean'],
            'good' => ['nullable', 'boolean'],
            'disease' => ['nullable', 'string', 'max:100'],
            'disability' => ['nullable', 'required_with:disability_card_number',  'string', 'max:100'],
            'disability_card_number' => ['nullable', 'required_with:disability', 'numeric', 'digits:8'],
        ]);

        $Grant_Validation = Request::validate([
            'grant_source' => ['nullable', 'required_with:grant_value',  'string', 'max:100'],
            'grant_value' => ['nullable', 'required_with:grant_source', 'integer'],
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
        if (Request::get('grant') == true) {
            $Grant_Validation = array_combine(['source', 'value'], array_values($Grant_Validation));
            $individual->grant()->update(

                $Grant_Validation

            );
        }
        else {
            $individual->grant()->update([
                'source' => null,
                'value' => null,
            ]);


        }

        return redirect()->route('individuals.Create_all', ['Individual' => $individual])->with('success', 'تم تحديث العضو');
    }








    public function update_health_status(Individual $Individual)
    {
        Request::validate([
            'health_insurance' => ['nullable', 'boolean'],
            'good' => ['nullable', 'boolean'],
            'disease' => ['nullable', 'string', 'max:100'],
            'disability' => ['nullable', 'required_with:disability_card_number',  'string', 'max:100'],
            'disability_card_number' => ['nullable', 'required_with:disability', 'numeric', 'digits:8'],
        ]);

        if (Request::get('good') == true) {
            $Individual->healthStatus()->update([
                'health_insurance' => Request::get('health_insurance'),
                'good' => Request::get('good'),
                'disease' => null,
                'disability' => null,
                'disability_card_number' => null,
            ]);
        } else {
            $Individual->healthStatus()->update([
                'health_insurance' => Request::get('health_insurance'),
                'good' => Request::get('good'),
                'disease' => Request::get('disease'),
                'disability' => Request::get('disability'),
                'disability_card_number' => Request::get('disability_card_number'),
            ]);
        }
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

        return Redirect::back()->with('success', 'تم استعادة العضو');
    }
}
