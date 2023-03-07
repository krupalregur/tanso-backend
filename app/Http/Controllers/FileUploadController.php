<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class FileUploadController extends Controller
{
    public function getFileUploadForm()
    {
        return view('file-upload');
    }

    public function index()
    {
        $files = File::latest()->paginate(10);
        return response()->json([
            "status" => 1,
            "data" => $files
        ], 200);
    }

    public function create()
    {
        //
    }


    public function store(Request $request)
    {

        $fileName = $request->file->getClientOriginalName();
        $filePath = $fileName;

        $path = Storage::disk('s3')->put($filePath, file_get_contents($request->file));
        $path = Storage::disk('s3')->url($fileName);

        // Perform the database operation here
        $data = File::create([
            'filename' => $fileName,
            'url' => $path,
        ]);

        return response()->json([
            "status" => 1,
            "data" => $data,
            "msg" => "File has been successfully uploaded."
        ], 200);
        //return back()->with('success', 'File has been successfully uploaded.');
    }

    public function show(File $files)
    {
        return response()->json([
            "status" => 1,
            "data" => $files
        ], 200);
    }

    public function update(Request $request, File $file)
    {

        $file->update($request->all());

        return response()->json([
            "status" => 1,
            "data" => $file,
            "msg" => "File updated successfully"
        ], 200);
    }

    public function destroy(File $file)
    {
        $file->delete();
        return response()->json([
            "status" => 1,
            "data" => $file,
            "msg" => "File deleted successfully"
        ], 200);
    }
}
