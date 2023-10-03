<?php

namespace App\Http\Controllers;

use App\Models\Family;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use App\Models\Project;

class projectController extends Controller
{
    public function index()
    {
        return Inertia::render('projects/Index', [
            'filters' => Request::all('search', 'trashed','status','isSolitary'),
            'projects' => Auth::user()->account->projects()
            ->orderBy('id')
            ->filter(Request::only('search', 'trashed', 'status','isSolitary'))
            ->paginate(10)
            ->withQueryString()
            ->through(fn ($project) => [
                'id' => $project->id,
                'name' => $project->name,
                'description' => $project->description,
                'status' => $project->status,
                'date' => $project->date,
                'isSolitary' => $project->isSolitary,
                'deadline' => $project->deadline,
                'deleted_at' => $project->deleted_at,
            ]),

        ]);
    }

    public function create()
    {
        return Inertia::render('projects/Create');
    }

    public function store()
    {
        Request::validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'date' => ['required', 'date'],
            'deadline' => ['required', 'date'],
            'status' => ['required', 'boolean'],
            'isSolitary' => ['required', 'boolean'],

        ]);
        Auth::user()->account->projects()->create([
            'name' => Request::get('name'),
            'description' => Request::get('description'),
            'date' => Request::get('date'),
            'deadline' => Request::get('deadline'),
            'status' => Request::get('status'),
            'isSolitary' => Request::get('isSolitary'),

        ]);



        return Redirect::back()->with('success', 'تم إنشاء المشروع.');
    }

    public function edit(project $project)
    {


        $families = Family::select('id', 'name', 'caregiver_phone')->where('is_family',true)->get();
        $elderlies = Family::where('social_status','elderly')->get();
        $divorceds = Family::where('social_status','divorced')->get();
        $singleMothers = Family::where('social_status','single_mother')->get();
        $widows = Family::where('social_status','widow')->get();



        return Inertia::render('projects/Edit', [
            'project' => [
                'id' => $project->id,
                'name' => $project->name,
                'description' => $project->description,
                'date' => $project->date,
                'deadline' => $project->deadline,
                'isSolitary' => $project->isSolitary,
                'status' => $project->status,
                'interventions' => $project->interventions()->with('family')->get(),
                'deleted_at' => $project->deleted_at,
            ],
            'families' => $families,
            'elderlies' => $elderlies,
            'divorceds' => $divorceds,
            'singleMothers' => $singleMothers,
            'widows' => $widows,

        ]);
    }
    public function show(Project $project)
    {
        $interventions = $project->interventions()->with('family')->get();

        return Inertia::render('projects/Show', [
            'project' => [
                'id' => $project->id,
                'name' => $project->name,
                'description' => $project->description,
                'date' => $project->date,
                'deadline' => $project->deadline,
                'status' => $project->status,
                'isSolitary' => $project->isSolitary,
                'interventions' => $interventions,
                'deleted_at' => $project->deleted_at,
            ],
        ]);
    }


    public function update(project $project)
    {
        $project->update(
            Request::validate([
                'name' => ['required', 'string', 'max:255'],
                'description' => ['required', 'string'],
                'date' => ['required', 'date'],
                'deadline' => ['required', 'date'],
                'status' => ['required', 'boolean'],

            ])

        );

        return Redirect::back()->with('success', 'تم تحديث المشروع.');
    }



    public function destroy(project $project)
    {
        $project->delete();

        return Redirect::back();
    }

    public function restore(project $project)
    {
        $project->restore();

        return Redirect::back()->with('success', 'تمت استعادة المشروع.');
    }
}
