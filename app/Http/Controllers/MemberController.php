<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Family;
use App\Models\HealthStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Illuminate\Validation\Rule;

class MemberController extends Controller
{

    public function create(Family $Family)
    {
        return Inertia::render('Families/Members/Create', compact('Family'));
    }

    public function create_new_one(Family $Family)
    {
        $hasWife = $Family->members()->where('kinship', 'wife')->exists();
        $hasHusband = $Family->members()->where('kinship', 'husband')->exists();

        return Inertia::render('Families/Members/Create_new_one', compact('Family','hasWife','hasHusband'));
    }

    public function store()
    {
        $messages = [
            'cin.required_if' => 'هوية المعيل  مطلوبة ',
            'phone.required_if' => 'هاتف المعيل  مطلوب',
        ];
        Request::validate([
            'name' => ['required', 'max:100'],
            'address' => ['required', 'max:100'],
            'cin' => 'required_if:caregiver,true|nullable|numeric|digits:8|unique:members',
            'phone' => 'required_if:caregiver,true|nullable|numeric|digits:8|unique:members',
            'birth_date' => ['nullable', 'date'],
            'birth_city' => ['nullable', 'max:100'],
            'social_status' => ['nullable', 'max:100'],
            'kinship' => ['nullable', 'max:100'],
            'education_level' => ['nullable', 'max:100'],
            'job' => ['nullable', 'max:100'],
            'job_place' => ['nullable', 'max:100'],
            'family_id' => ['nullable', 'integer'],

        ],$messages);
        // Create the health status
        $H_S_Validation = Request::validate([
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

        $member = Auth::user()->account->members()->create([

            'name' => Request::get('name'),
            'address' => Request::get('address'),
            'cin' => Request::get('cin'),
            'phone' => Request::get('phone'),
            'birth_date' => Request::get('birth_date'),
            'birth_city' => Request::get('birth_city'),
            'social_status' => Request::get('social_status'),
            'kinship' => Request::get('kinship'),
            'education_level' => Request::get('education_level'),
            'job' => Request::get('job'),
            'job_place' => Request::get('job_place'),
            'family_id' => Request::get('family_id'),
            'photo' => Request::file('photo') ? Request::file('photo')->store('') : null,
        ]);

        if (Request::file('photo')) {
            Request::file('photo')->move(public_path('uploads'), $member->photo);
        }
        if (Request::get('grant') == true) {
            $Grant_Validation = array_combine(['source', 'value'], array_values($Grant_Validation));

            $member->grant()->update(

                $Grant_Validation

            );
        }

        $family = $member->family;
        $family->members()->update(['caregiver' => false]);

        $husband = $family->members()
            ->where('kinship', 'husband')
            ->first();

        if (!$husband) {
            $wife = $family->members()
                ->where('kinship', 'wife')
                ->first();

            if (!$wife) {
                $child = $family->members()
                    ->where('kinship', 'child')
                    ->orderBy('created_at')
                    ->first();

                if (!$child) {
                    $other_member = $family->members()
                        ->where('kinship', 'other_member')
                        ->orderBy('created_at')
                        ->first();

                    if (!$other_member) {
                        // Set the member with kinship = "elderly" if no other members exist
                        $elderly_member = $family->members()
                            ->where('kinship', 'elderly')
                            ->orderBy('created_at')
                            ->first();

                        $family_caregiver = $elderly_member;
                    } else {
                        $family_caregiver = $other_member;
                    }
                } else {
                    $family_caregiver = $child;
                }
            } else {
                $family_caregiver = $wife;
            }
        } else {
            $family_caregiver = $husband;
        }

        // Update family and her caregiver details
        if ($family_caregiver) {
            $family_caregiver->update([
                'caregiver' => true,
            ]);
            $family->update([
                'name' => $family_caregiver->name,
                'caregiver_phone' => $family_caregiver->phone,
                'address' => $family_caregiver->address,
                'photo' => $family_caregiver->photo,

            ]);
        }


        $healthStatus = new HealthStatus([
            'health_insurance' => $H_S_Validation['health_insurance'],
            'good' => $H_S_Validation['good'],
            'disease' => $H_S_Validation['disease'],
            'disability' => $H_S_Validation['disability'],
            'disability_card_number' => $H_S_Validation['disability_card_number'],
        ]);

        $member->healthStatus()->save($healthStatus);


        return redirect()->back()->with('success', 'تم إنشاء العضو');
    }


    public function show(Member $Member)
    {
        return Inertia::render('Families/Members/Show', [
            'member' => [
                'id' => $Member->id,
                'name' => $Member->name,
                'address' => $Member->address,
                'cin' => $Member->cin,
                'phone' => $Member->phone,
                'photo' => $Member->photo,
                'birth_date' => $Member->birth_date,
                'birth_city' => $Member->birth_city,
                'social_status' => $Member->social_status,
                'kinship' => $Member->kinship,
                'education_level' => $Member->education_level,
                'caregiver' => $Member->caregiver,
                'job' => $Member->job,
                'job_place' => $Member->job_place,
                'family_id' => $Member->family_id,
                'grant' => $Member->grant()->get(),
                'healthStatus' => $Member->healthStatus()->get(),
                'deleted_at' => $Member->deleted_at,
            ],
        ]);
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
                'kinship' => $Member->kinship,
                'education_level' => $Member->education_level,
                'job' => $Member->job,
                'job_place' => $Member->job_place,
                'family_id' => $Member->family_id,
                'grant' => $Member->grant()->get(),
                'healthStatus' => $Member->healthStatus()->get(),
                'deleted_at' => $Member->deleted_at,
            ],
        ]);
    }

    public function update_health_status(Member $Member)
    {
        Request::validate([
            'health_insurance' => ['nullable', 'boolean'],
            'good' => ['nullable', 'boolean'],
            'disease' => ['nullable', 'string', 'max:100'],
            'disability' => ['nullable', 'required_with:disability_card_number',  'string', 'max:100'],
            'disability_card_number' => ['nullable', 'required_with:disability', 'numeric', 'digits:8'],
        ]);


        if (Request::get('good') == true) {
            $Member->healthStatus()->update([
                'health_insurance' => Request::get('health_insurance'),
                'good' => Request::get('good'),
                'disease' => null,
                'disability' => null,
                'disability_card_number' => null,
            ]);
        } else {
            $Member->healthStatus()->update([
                'health_insurance' => Request::get('health_insurance'),
                'good' => Request::get('good'),
                'disease' => Request::get('disease'),
                'disability' => Request::get('disability'),
                'disability_card_number' => Request::get('disability_card_number'),
            ]);
        }
        return redirect()->route('members.edit', ['member' => $Member])->with('success', 'تم تحديث الحالة الصحية للعضو ');
    }





    public function update(Member $Member)
    {
           if ($Member->caregiver) {
            $messages = [
                'cin.required' => 'هوية المعيل  مطلوبة ',
                'phone.required' => 'هاتف المعيل  مطلوب',
            ];
            Request::validate([
                'cin' => ['required', 'integer', 'digits:8'],
                'phone' => ['required', 'integer', 'digits:8']],$messages);

            }
        $Member->update(
            Request::validate([
                'name' => ['required', 'max:100'],
                'address' => ['required', 'max:100'],
                'cin' => ['nullable','integer', 'digits:8',Rule::unique('members')->ignore($Member->id)],
                'phone' => ['nullable','integer', 'digits:8',Rule::unique('members')->ignore($Member->id)],
                'birth_date' => ['nullable', 'date'],
                'birth_city' => ['nullable', 'max:100'],
                'social_status' => ['nullable', 'max:100'],
                'kinship' => ['nullable', 'max:100'],
                'education_level' => ['nullable', 'max:100'],
                'job' => ['nullable', 'max:100'],
                'job_place' => ['nullable', 'max:100'],
                'family_id' => ['nullable', 'integer'],

            ])
        );



        $Grant_Validation = Request::validate([
            'grant_source' => ['nullable', 'required_with:grant_value',  'string', 'max:100'],
            'grant_value' => ['nullable', 'required_with:grant_source', 'integer'],
        ]);


        if (Request::file('photo')) {
            $Member->update(
                [
                    'photo' => Request::file('photo') ? Request::file('photo')->store('') : null,
                ]
            );
            Request::file('photo')->move(public_path('uploads'), $Member->photo);
        }

        if ($Member->caregiver) {
            $famely = Family::where('id', $Member->family_id)->first();
            $famely->update([
                'name' => $Member->name,
                'caregiver_phone' => $Member->phone,
                'address' => $Member->address,
            ]);
        }
        if (Request::get('grant') == true) {
            $Grant_Validation = array_combine(['source', 'value'], array_values($Grant_Validation));

            $Member->grant()->update(

                $Grant_Validation

            );
        }
        else {
            $Member->grant()->update([
                'source' => null,
                'value' => null,
            ]);


        }
        return redirect()->route('members.edit', ['member' => $Member])->with('success', 'تم تحديث العضو');
    }






    public function newMembers()
    {

        $validatedData = Request::validate([
            'name' => ['required', 'max:100'],
            'address' => ['required', 'max:100'],
            'cin' => 'nullable|integer||digits:8|unique:' . Member::class,
            'phone' => 'nullable|integer||digits:8|unique:' . Member::class,
            'birth_date' => ['nullable', 'date'],
            'birth_city' => ['nullable', 'max:100'],
            'social_status' => ['nullable', 'max:100'],
            'kinship' => ['nullable', 'max:100'],
            'education_level' => ['nullable', 'max:100'],
            'job' => ['nullable', 'max:100'],
            'job_place' => ['nullable', 'max:100'],
            'family_id' => ['nullable', 'integer'],

        ]);
        $H_S_Validation = Request::validate([
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

        $member = Auth::user()->account->members()->create($validatedData);

        if (Request::file('photo')) {
            $member->update(
                [
                    'photo' => Request::file('photo') ? Request::file('photo')->store('') : null,

                ]
            );
            Request::file('photo')->move(public_path('uploads'), $member->photo);
        }

        if (Request::get('grant') == true) {
            $Grant_Validation = array_combine(['source', 'value'], array_values($Grant_Validation));

            $member->grant()->update(

                $Grant_Validation

            );
        }

        $healthStatus = new HealthStatus($H_S_Validation);

        $member->healthStatus()->save($healthStatus);
        $Family = $member->Family;

        return redirect()->route('families.edit', ['family' => $Family])->with('success', 'تم إنشاء العضو');
    }



    public function destroy(Member $Member)
    {
        $Member->delete();

        return Redirect::back()->with('success', 'تم حذف العضو');
    }

    public function restore(Member $Member)
    {
        $Member->restore();

        return Redirect::back()->with('success', 'تم استعادة العضو');
    }
    //
    public function edit_caregiver(Member $Member)
    {
        $members = Member::where('family_id', $Member->family_id)->get();
        foreach ($members as $member) {
            if ($member == $Member) {
                $member->caregiver = true;
                $famely = Family::where('id', $member->family_id)->first();
                $famely->update([
                    'name' => $member->name,
                    'caregiver_phone' => $member->phone,
                    'address' => $member->address,
                    'photo' => $member->photo,

                ]);
            } else
                $member->caregiver = false;
            $member->save();
        }

        return Redirect::back()->with('success', '  تم تحديث راعي الأسرة');
    }
}
