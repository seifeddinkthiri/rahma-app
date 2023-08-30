<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\Note;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class NoteController extends Controller
{

    public function store(Family $family)
    {
        Request::validate([
            'title' => ['required', 'string', 'max:100'],
            'value' => ['required'],
        ]);

        $family->notes()->create([
            'title' => Request::input('title'),
            'value' => Request::input('value'),
        ]);

        return redirect()->back()->with('success', 'تم إنشاء الملاحظة.');
    }




    public function update(Note $Note)
    {
        Request::validate([
            'title' => ['required', 'string', 'max:100'],
            'value' => ['required'],

        ]);

        $Note->update([
            'title' => Request::input('title'),
            'value' => Request::input('value'),
        ]);

        return redirect()->back()->with('success', 'تم تحديث الملاحظة');
    }




    public function destroy(Note $Note)
    {
        $Note->delete();

        return Redirect::back()->with('success', 'تم حذف الملاحظة');
    }
}
