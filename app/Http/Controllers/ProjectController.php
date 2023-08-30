<?php

namespace App\Http\Controllers;

use App\Models\Family;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use App\Models\project;

class projectController extends Controller
{
    public function index()
    {
        return Inertia::render('projects/Index', [
            'filters' => Request::all('search', 'trashed'),
            'projects' => Auth::user()->account->projects()
                ->orderBy('id')
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($project) => [
                    'id' => $project->id,
                    'name' => $project->name,
                    'description' => $project->description,
                    'status' => $project->status,
                    'date' => $project->date,
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
        ]);
         Auth::user()->account->projects()->create([
            'name' => Request::get('name'),
            'description' => Request::get('description'),
            'date' => Request::get('date'),
            'deadline' => Request::get('deadline'),
            'status' => Request::get('status'),

        ]);



        return Redirect::back()->with('success', 'تم إنشاء المشروع.');
    }

    public function edit(project $project)
    {
        return Inertia::render('projects/Edit', [
            'project' => [
                'id' => $project->id,
                'name' => $project->name,
                'description' => $project->description,
                'date' => $project->date,
                'deadline' => $project->deadline,
                'status' => $project->status,
                'interventions' => $project->interventions()->get(),
                'deleted_at' => $project->deleted_at,
            ],
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
                'interventions' => $interventions->map(function ($intervention) {
                    return [
                        'id' => $intervention->id,
                        'intervenor' => $intervention->intervenor??null,
                        'family' => [
                            'name' => $intervention->family->name ??null,
                        ],
                    ];
                }),
                'deleted_at' => $project->deleted_at,
            ],
        ]);
    }


    public function update(project $project)
    {//dd(Request::get('status'));
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

        return Redirect::back()->with('success', 'تم حذف المشروع.');
    }

    public function restore(project $project)
    {
        $project->restore();

        return Redirect::back()->with('success', 'تمت استعادة المشروع.');
    }
}
