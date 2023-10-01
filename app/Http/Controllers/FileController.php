<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\File;
use App\Models\Intervention;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;

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
        'file' =>Request::file('file') ?Request::file('file')->store('') : null,
    ]);

    if (Request::file('file')) {
       Storage::put('uploads/files',   Request::file('file'));

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

         $file=File::findorFail($id);
         $file_name= $file->file ;
         $path = storage_path("app/uploads/files/{$file_name}");

        return response()->file($path);
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
