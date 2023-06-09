<?php

namespace App\Http\Controllers;

use App\Models\Member;
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
            'value' => ['required', 'string', 'max:100'],
        ]);

        $family->notes()->create([
            'title' => Request::input('title'),
            'value' => Request::input('value'),
        ]);

        return redirect()->back()->with('success', 'Note created.');
    }


    public function update(Note $Note)
    {
        Request::validate([
            'value' => ['required', 'string', 'max:100'],
        ]);

        $Note->healthStatus()->update([
            'value' => Request::input('value'),
        ]);

        return redirect()->route('notes.edit', ['note' => $Note])->with('success', 'Note updated');
    }




    public function destroy(Note $Note)
    {
        $Note->delete();

        return Redirect::back()->with('success', 'Note deleted');
    }
}
