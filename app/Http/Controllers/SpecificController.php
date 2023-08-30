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
        $memberValidation =Request::validate([
            'name' => ['required', 'max:100'],
            'address' => ['required', 'max:100'],
            'cin' => 'required|numeric|digits:8|unique:members',
            'phone' => 'required|numeric|digits:8|unique:members',
            'birth_date' => ['nullable', 'date'],
            'birth_city' => ['nullable', 'max:100'],
            'social_status' => ['nullable', 'max:100'],
            'kinship' => ['nullable', 'max:100'],
            'education_level' => ['nullable', 'max:100'],
            'job' => ['nullable', 'max:100'],
            'job_place' => ['nullable', 'max:100'],
            'family_id' => ['nullable', 'integer'],
        ]);

        $healthStatusValidation =Request::validate([
            'health_insurance' => ['nullable', 'boolean'],
            'good' => ['nullable', 'boolean'],
            'disease' => ['nullable', 'string', 'max:100'],
            'disability' => ['nullable', 'required_with:disability_card_number', 'string', 'max:100'],
            'disability_card_number' => ['nullable', 'required_with:disability', 'numeric', 'digits:8'],
        ]);

        $grantValidation =Request::validate([
            'grant_source' => ['nullable', 'required_with:grant_value', 'string', 'max:100'],
            'grant_value' => ['nullable', 'required_with:grant_source', 'integer'],
        ]);

        $user = Auth::user();
        $account = $user->account;

        $member = $account->members()->create($memberValidation);

        if (Request::file('photo')) {
            $photoPath = Request::file('photo')->move(public_path('uploads'), $member->photo);
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
