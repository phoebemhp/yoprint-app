<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileRequest;
use App\Models\File;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $files = File::all();

        return view('upload-file', compact('files'));
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function show(File $file)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function edit(File $file)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, File $file)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function destroy(File $file)
    {
        //
    }

    public function upload(FileRequest $request)
    {
        try {
            $status = Config::get('constants.file_status.pending');
            $uploadedFile = $request->file('file');
            $originalName = $uploadedFile->getClientOriginalName();
            $fileIdentifier = md5_file($uploadedFile->getPathname());
            $mimeType = $uploadedFile->getClientMimeType();

            $existingFile = File::findIdentifier($fileIdentifier)->first();
            dd($existingFile);

            if (!$existingFile) {
                $path = $uploadedFile->store('uploads', 'public');
                $status = Config::get('constants.file_status.completed');
                $file = new File([
                    'name' => $path,
                    'original_name' => $originalName,
                    'mime_type' => $mimeType,
                    'identifier' => $fileIdentifier,
                    'status' => $status
                ]);
                $file->save();

                $message = 'File uploaded successfully';
            } else {
                $existingFile->update([
                    'original_name' => $originalName,
                    'mime_type' => $mimeType,
                    'status' => $status
                ]);
            }

            return redirect()->back()->with('success', $message);    
        } catch (Exception $e) {
            Log::error($e->getMessage());
            $status = Config::get('constants.file_status.failed');
            $file->status = $status;
            $file->save();
            return redirect()->back()->with('error', 'Failed to upload file');
        }
        
    }
}
