<?php

namespace App\Http\Controllers;
use App\Models\Family;
use App\Models\Facility;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class FacilityController extends Controller
{



    public function update(Family $Family)
    {
        Request::validate([
            'Sanitation' => ['required', 'boolean'],
            'electricity' => ['required', 'boolean'],
            'water' => ['required', 'boolean'],
            'ventilation' => ['required', 'boolean'],
        ]);


        $Family->facilities->update([
            'Sanitation' => Request::input('Sanitation'),
            'electricity' => Request::input('electricity'),
            'water' => Request::input('water'),
            'ventilation' => Request::input('ventilation'),
        ]);

        return redirect()->back()->with('success', 'تم تحديث المنشأة');
    }




    public function destroy(Facility $Facility)
    {
        $Facility->delete();

        return Redirect::back()->with('success', 'تم حذف المنشأة');
    }

}
