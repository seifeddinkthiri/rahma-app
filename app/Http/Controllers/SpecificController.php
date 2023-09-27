<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\HealthStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use App\Models\Family;
use Illuminate\Support\Facades\Redirect;

class SpecificController extends Controller
{
    public function create($Family, $type)
    {

        return Inertia::render('Families/Members/Specific', compact('Family', 'type'))
            ->with('success', 'الرجاء إضافة معطيات المنتفع');
    }



    public function store()
    {

        $messages = [
            'cin.required_if' => 'هوية المنتفع  مطلوبة ',
            'phone.required_if' => 'هاتف المنتفع  مطلوب',
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
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], $messages);

        $healthStatusValidation =Request::validate([
            'health_insurance' => ['nullable', 'boolean'],
            'good' => ['nullable', 'boolean'],
            'disease' => ['nullable', 'string', 'max:100'],
            'disability' => ['nullable', 'required_with:disability_card_number', 'string', 'max:100'],
            'disability_card_number' => ['nullable', 'required_with:disability', 'numeric', 'digits:8'],
        ]);

        $grantValidation =Request::validate([
            'source' => ['nullable', 'required_with:value', 'string', 'max:100'],
            'value' => ['nullable', 'required_with:source', 'integer'],
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
            $photoPath = 'uploads/' . $member->photo;
            Request::file('photo')->move(public_path('uploads'), $member->photo);
        }

        if (Request::get('grant')) {
            $member->grant()->update($grantValidation);
        }

        $Family = $member->family;
        $isCaregiver = Request::get('caregiver') ;
        if ($isCaregiver == true) {
            $FamilyCaregiver = $member;
        } else {
            $FamilyCaregiver = null;
        }
        if ($FamilyCaregiver) {
            $FamilyCaregiver->update([
                'caregiver' => true,
            ]);

            $Family->update([
                'name' => $FamilyCaregiver->name,
                'caregiver_phone' => $FamilyCaregiver->phone,
                'address' => $FamilyCaregiver->address,
                'photo' => $FamilyCaregiver->photo,
            ]);
        }

        $healthStatus = new HealthStatus($healthStatusValidation);
        $member->healthStatus()->save($healthStatus);

        $type = Request::get('type');

        return Redirect::back()->with([
            'type' => $type,
            'Family' => $Family
        ])->with('success', 'تم إنشاء العضو');

    }

}
