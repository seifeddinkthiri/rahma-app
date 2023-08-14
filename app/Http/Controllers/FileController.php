<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\File;
use App\Models\Individual;
use App\Models\Intervention;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( Family $family)
{
   Request::validate([
        'title' => 'required|string|max:255',
        'file' => 'required|file|max:2048',
    ]);

    $file = $family->files()->create([
        'title' =>Request::input('title'),
        'file' =>Request::file('file') ?Request::file('file')->store('uploads/files') : null,
    ]);

    if (Request::file('file')) {
       Request::file('file')->move(public_path('uploads/files'), $file->file);
    }

    return Redirect::back()->with('success', 'تمت إضافة الملف');
}





public function store_for_Individual( Individual $individual)
{
   Request::validate([
        'title' => 'required|string|max:255',
        'file' => 'required|file|max:2048',
    ]);

    $file = $individual->files()->create([
        'title' =>Request::input('title'),
        'file' =>Request::file('file') ?Request::file('file')->store('uploads/files') : null,
    ]);

    if (Request::file('file')) {
       Request::file('file')->move(public_path('uploads/files'), $file->file);
    }

    return Redirect::back()->with('success', 'تمت إضافة الملف');
}




    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(File $File)
    {
        $File->delete();

        return Redirect::back()->with('success', 'تم حذف الملف');
    }
}
