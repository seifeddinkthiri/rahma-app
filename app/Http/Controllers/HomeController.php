<?php

namespace App\Http\Controllers;

use App\Models\Home;
use App\Models\Family;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
class HomeController extends Controller
{




    public function store(Family $family)
    {
        Request::validate([
            'status' => ['required', 'string', 'max:100'],
            'allocation_price' => ['nullable', 'integer'],
            'desciption' => ['nullable', 'string', 'max:1000'],

        ]);

        $family->home()->create([
            'status' => Request::input('status'),
            'allocation_price' => Request::input('allocation_price'),
            'desciption' => Request::input('desciption'),

        ]);

        return redirect()->back()->with('success', 'تم إنشاء المنزل.');
    }


    public function update(Family $Family)
    {
        Request::validate([
            'status' => ['required', 'string', 'max:100'],
            'allocation_price' => ['nullable', 'integer'],
            'desciption' => ['required', 'string', 'max:1000'],

        ]);

        $Family->home()->update([
            'status' => Request::input('status'),
            'allocation_price' => Request::input('allocation_price'),
            'desciption' => Request::input('desciption'),
        ]);

        return redirect()->back()->with('success', 'تم تحديث الصفحة الرئيسية');
    }




    public function destroy(Family $Family)
    {
        $Family->home()->delete();

        return Redirect::back()->with('success','تم حذف الصفحة الرئيسية');
    }


    public function restore(Family $Family)
    {
        $Family->home()->restore();

        return Redirect::back()->with('success','تم استعادة الصفحة الرئيسية');
    }

}
