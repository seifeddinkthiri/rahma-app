<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use App\Models\Intervention;
use App\Models\Family;
use App\Models\Project;
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
        $families = Family::select('id', 'name', 'caregiver_phone')->where('is_family',true)->get();
        $elderlies = Family::where('social_status','elderly')->get();
        $divorceds = Family::where('social_status','divorced')->get();
        $singleMothers = Family::where('social_status','single_mother')->get();
        $widows = Family::where('social_status','widow')->get();



        $projects = Project::select('id', 'name')->get();
        return Inertia::render('Interventions/Create', compact('families','elderlies','divorceds','widows','singleMothers','projects'));
    }

    public function store()
    {
        Request::validate([
            'type' => ['required', 'string', 'max:100'],
            'intervenor' => ['nullable', 'string', 'max:50'],
            'intervenor_phone' => ['nullable', 'numeric', 'digits:8'],
            'notes' => ['nullable'],
            'file' => ['nullable'],
            'title' => ['nullable', 'required_with:file', 'string', 'max:50'],
            'date' => ['nullable', 'date'],
        ]);

        $intervention = Auth::user()->account->interventions()->create([
            'type' => Request::get('type'),
            'value' => Request::get('value'),
            'date' => Request::get('date'),
            'family_id' => Request::get('family'),
            'project_id' => Request::get('project'),
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
        $projects = Project::select('id', 'name')->get();
        $families = Family::select('id', 'name', 'caregiver_phone')->where('is_family',true)->get();
        $elderlies = Family::where('social_status','elderly')->get();
        $divorceds = Family::where('social_status','divorced')->get();
        $singleMothers = Family::where('social_status','single_mother')->get();
        $widows = Family::where('social_status','widow')->get();
        $family = $intervention->family ?? null;
        $beneficial = $family ? $family->social_status : null;

        return Inertia::render('Interventions/Edit', [
            'intervention' => [
                'id' => $intervention->id,
                'family_id' => $intervention->family_id,
                'project_id' => $intervention->project_id,
                'type' => $intervention->type,
                'value' => $intervention->value,
                'date' => $intervention->date,
                'intervenor' => $intervention->intervenor,
                'intervenor_phone' => $intervention->intervenor_phone,
                'notes' => $intervention->notes,
                'deleted_at' => $intervention->deleted_at,
            ],
            'families' => $families,
            'elderlies' => $elderlies,
            'divorceds' => $divorceds,
            'singleMothers' => $singleMothers,
            'widows' => $widows,
            'projects' => $projects,
            'beneficial' => $beneficial,

        ]);
    }

    public function show(Intervention $intervention)
    {
        return Inertia::render('Interventions/Show', [
            'intervention' => [
                'id' => $intervention->id,
                'family' => $intervention->family()->get(),
                'project' => $intervention->project()->get(),
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
        Request::validate([
            'type' => ['required', 'string', 'max:100'],
            'intervenor' => ['nullable', 'string', 'max:50'],
            'intervenor_phone' => ['nullable', 'numeric', 'digits:8'],
            'notes' => ['nullable'],
            'file' => ['nullable'],
            'title' => ['nullable', 'required_with:file','string', 'max:50']
        ]);
        $intervention = Auth::user()->account->interventions()->update([
            'type' => Request::get('type'),
            'value' => Request::get('value'),
            'date' => Request::get('date'),
            'family_id' => Request::get('family'),
            'project_id' => Request::get('project'),
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
