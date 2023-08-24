<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use App\Models\Intervention;
use App\Models\Family;
use App\Models\Individual;

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
                    'date' => $intervention->date,
                    'intervenor' => $intervention->intervenor,
                    'intervenor_phone' => $intervention->intervenor_phone,
                    'notes' => $intervention->notes,
                    'deleted_at' => $intervention->deleted_at,
                ]),
        ]);
    }

    public function create()
    {
        $families = Family::select('id', 'name', 'caregiver_phone')->get();
        $individuals = Individual::select('id', 'name', 'phone')->get();

        return Inertia::render('Interventions/Create', compact('families', 'individuals'));
    }

    public function store()
    {


       /*
         $family = Request::get('family');
        $individual = Request::get('individual');
       if (!$family && !$individual) {
            return Redirect::back()->with('error', 'يجب إختيار منتفع');
        } else {


        }*/
        Request::validate([
            'type' => ['required', 'string', 'max:100'],
            'intervenor' => ['nullable', 'string', 'max:50'],
            'intervenor_phone' => ['nullable', 'numeric', 'digits:8'],
            'notes' => ['nullable'],
        ]);
        $intervention = Auth::user()->account->interventions()->create([
            'type' => Request::get('type'),
            'value' => Request::get('value'),
            'date' => Request::get('date'),
            'family_id' => Request::get('family'),
            'individual_id' => Request::get('individual'),
            'intervenor' => Request::get('intervenor'),
            'intervenor_phone' => Request::get('intervenor_phone'),
            'notes' => Request::get('notes'),

        ]);


        if (Request::file('file')) {
            $file = $intervention->files()->create([
                'title' => Request::input('title'),
                'file' => Request::file('file') ? Request::file('file')->store('uploads/files') : null,
            ]);
            Request::file('file')->move(public_path('uploads/files'), $file->file);
        }

        return Redirect::back()->with('success', 'تم إنشاء التدخل.');
    }

    public function edit(Intervention $intervention)
    {
        $families = Family::select('id', 'name', 'caregiver_phone')->get();
        $individuals = Individual::select('id', 'name', 'phone')->get();

        return Inertia::render('Interventions/Edit', [
            'intervention' => [
                'id' => $intervention->id,
                'family_id' => $intervention->family_id,
                'individual_id' => $intervention->individual_id,
                'type' => $intervention->type,
                'value' => $intervention->value,
                'date' => $intervention->date,
                'intervenor' => $intervention->intervenor,
                'intervenor_phone' => $intervention->intervenor_phone,
                'notes' => $intervention->notes,
                'notes' => $intervention->notes,

                'deleted_at' => $intervention->deleted_at,
            ],
            'families' => $families,
            'individuals' => $individuals,
        ]);
    }

    public function show(Intervention $intervention)
    {
        return Inertia::render('Interventions/Show', [
            'intervention' => [
                'id' => $intervention->id,
                'family' => $intervention->family()->get(),
                'individual' => $intervention->individual()->get(),
                'type' => $intervention->type,
                'value' => $intervention->value,
                'date' => $intervention->date,
                'intervenor' => $intervention->intervenor,
                'intervenor_phone' => $intervention->intervenor_phone,
                'notes' => $intervention->notes,
                'files' =>  $intervention->files()->get(),
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
                'date' => ['nullable'],
                'intervenor_phone' => ['nullable', 'max:50'],
                'notes' => ['nullable'],
            ])

        );
        if (Request::file('file')) {
            $file = $intervention->files()->create([
                'title' => Request::input('title'),
                'file' => Request::file('file') ? Request::file('file')->store('uploads/files') : null,
            ]);
            Request::file('file')->move(public_path('uploads/files'), $file->file);
        }

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
