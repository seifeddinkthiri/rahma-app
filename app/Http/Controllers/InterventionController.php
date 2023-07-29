<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use App\Models\Intervention;

class InterventionController extends Controller
{
    public function index()
    {
        return Inertia::render('Interventions/Index', [
            'filters' => Request::all('search', 'trashed'),
            'interventions' => Auth::user()->account->interventions()
                ->orderBy('id')
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($intervention) => [
                    'id' => $intervention->id,
                    'type' => $intervention->type,
                    'value' => $intervention->value,
                    'intervenor' => $intervention->intervenor,
                    'intervenor_phone' => $intervention->intervenor_phone,
                    'file' => $intervention->file,
                    'notes' => $intervention->notes,
                    'deleted_at' => $intervention->deleted_at,
                ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('Interventions/Create');
    }

    public function store()
    {


        Request::validate([
        'type' => ['required', 'max:100'],
        'intervenor' => ['nullable', 'max:50'],
        'intervenor_phone' => ['nullable', 'max:50'],
        'file' => 'nullable|file',
        'notes' => ['nullable', 'max:100']]);
       $intervention = Auth::user()->account->interventions()->create([
        'type' => Request::get('type'),
        'value' => Request::get('value'),
        'intervenor' => Request::get('intervenor'),
        'intervenor_phone' => Request::get('intervenor_phone'),
        'notes' => Request::get('notes'),
        'file' => Request::file('file') ? Request::file('file')->store('') : null,

       ]);
       if (Request::file('file')) {
        Request::file('file') ->move(public_path('uploads'), $intervention->file);

    }
        return Redirect::route('interventions')->with('success', 'تم إنشاء التدخل.');
    }

    public function edit(Intervention $intervention)
    {
        return Inertia::render('Interventions/Edit', [
            'intervention' => [
                'id' => $intervention->id,
                'type' => $intervention->type,
                'value' => $intervention->value,
                'intervenor' => $intervention->intervenor,
                'intervenor_phone' => $intervention->intervenor_phone,
                'file' => $intervention->file,
                'notes' => $intervention->notes,
                'deleted_at' => $intervention->deleted_at,
            ],
        ]);
    }

    public function update(Intervention $intervention)
    {
        $intervention->update(
            Request::validate([
                'type' => ['required', 'max:100'],
                'intervenor' => ['nullable', 'max:50'],
                'intervenor_phone' => ['nullable', 'max:50'],
                'file' => 'nullable|file',
                'notes' => ['nullable', 'max:100'],
            ])
        );

        return Redirect::back()->with('success', 'تم تحديث التدخل.');
    }

    public function destroy(Intervention $intervention)
    {
        $intervention->delete();

        return Redirect::back()->with('success', 'تم حذف التدخل.');
    }

    public function restore(Intervention $intervention)
    {
        $intervention->restore();

        return Redirect::back()->with('success', 'تمت استعادة التدخل.');
    }
}
