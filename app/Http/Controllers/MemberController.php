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

    public function create(Family $Family)
    {
        return Inertia::render('Families/Members/Create', compact('Family'));
    }

    public function create_new_one(Family $Family)
    {
        return Inertia::render('Families/Members/Create_new_one', compact('Family'));
    }

    public function store()
    {
        $validatedData = Request::validate([
            'name' => ['required', 'max:100'],
            'address' => ['required', 'max:100'],
            'cin' => 'required|integer||digits:8|unique:'.Member::class,
            'phone' => 'required|integer||digits:8|unique:'.Member::class,
            'birth_date' => ['required', 'date'],
            'birth_city' => ['required', 'max:100'],
            'social_status' => ['required', 'max:100'],
            'monthly_income' => ['nullable', 'integer'],
            'kinship' => ['required', 'max:100'],
            'education_level' => ['nullable', 'max:100'],
            'job' => ['nullable', 'max:100'],
            'job_place' => ['nullable', 'max:100'],
            'family_id' => ['required', 'integer'],

        ]);

        $member = Auth::user()->account->members()->create($validatedData);
        $family = $member->family; // Retrieve the Family model instance
        if ($member->phone == $family->caregiver_phone) {
            $member->update([
                'caregiver' => true,

            ]);

            $family->update([
                'name' => $member->name,
                'phone' => $member->phone,
                'address' => $member->address,

            ]);
        }

        // Create the health status
        $validatedData = Request::validate([
            'health_insurance' => ['nullable', 'boolean'],
            'good' => ['nullable', 'boolean'],
            'disease' => ['nullable', 'string', 'max:100'],
            'disability' => ['nullable', 'string', 'max:100'],
            'disability_card_number' => ['nullable', 'integer', 'digits:8'],
        ]);

        $healthStatus = new HealthStatus([
            'health_insurance' => $validatedData['health_insurance'],
            'good' => $validatedData['good'],
            'disease' => $validatedData['disease'],
            'disability' => $validatedData['disability'],
            'disability_card_number' => $validatedData['disability_card_number'],
        ]);

        $member->healthStatus()->save($healthStatus);

        return redirect()->route('members.create', ['family' => $member->family])->with('success', 'Member created');
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
                'kinship' => $Member->kinship,
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
            'health_insurance' => ['nullable', 'boolean'],
            'good' => ['required', 'boolean'],
            'disease' => ['nullable', 'string', 'max:100'],
            'disability' => ['nullable', 'string', 'max:100'],
            'disability_card_number' => ['nullable', 'integer', 'digits:8'],
        ]);

        $Member->healthStatus()->update([
            'health_insurance' => Request::input('health_insurance'),
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
                'monthly_income' => ['nullable', 'integer'],
                'kinship' => ['required', 'max:100'],
                'education_level' => ['nullable', 'max:100'],
                'job' => ['nullable', 'max:100'],
                'job_place' => ['nullable', 'max:100'],
                'family_id' => ['required', 'integer'],
            ])
        );

        return redirect()->route('members.edit', ['member' => $Member])->with('success', 'Member updated');
    }






    public function newMembers()
    {
        $validatedData = Request::validate([
            'name' => ['required', 'max:100'],
            'address' => ['required', 'max:100'],
            'cin' => ['required', 'integer', 'digits:8'],
            'phone' => ['required', 'integer', 'digits:8'],
            'birth_date' => ['required', 'date'],
            'birth_city' => ['required', 'max:100'],
            'social_status' => ['required', 'max:100'],
            'monthly_income' => ['nullable', 'integer'],
            'kinship' => ['required', 'max:100'],
            'education_level' => ['nullable', 'max:100'],
            'job' => ['nullable', 'max:100'],
            'job_place' => ['nullable', 'max:100'],
            'family_id' => ['required', 'integer'],

        ]);

        $member = Auth::user()->account->members()->create($validatedData);


        // Create the health status
        $validate_HS_dData = Request::validate([
            'health_insurance' => ['nullable', 'boolean'],
            'good' => ['required', 'boolean'],
            'disease' => ['nullable', 'string', 'max:100'],
            'disability' => ['nullable', 'string', 'max:100'],
            'disability_card_number' => ['nullable', 'integer', 'digits:8'],
        ]);

        $healthStatus = new HealthStatus($validate_HS_dData);

        $member->healthStatus()->save($healthStatus);
        $Family = $member->Family;

        return redirect()->route('families.edit', ['family' => $Family])->with('success', 'Member created');

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
    //
    public function edit_caregiver(Member $Member)
    {
        $members = Member::all();
        foreach ($members as $member) {
            if($member == $Member){
                $member->caregiver = true;
                $famely = Family::where('id', $member->family_id)->first();
                $famely->update([
                    'name' => $member->name,
                    'phone' => $member->phone,
                    'address' => $member->address,
                ]);
            }
            else
                $member->caregiver = false;
            $member->save();
        }

        return Redirect::back()->with('success', 'the caregiver was changed');
    }
}
