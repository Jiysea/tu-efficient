<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function show($filename)
    {
        dd($filename);
        // Path to the image in storage
        $path = $filename;

        // // Check if the file exists
        if (Storage::exists($path)) {
            abort(404); // Return 404 if file is not found
        }

        // // Get the absolute path to the file on the disk
        $absolutePath = Storage::path($path);

        // // Return the file as a response
        return response()->file($absolutePath);
    }
}
