<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function destroy($id)
    {
        $gallery = Gallery::findOrFail($id);

        Storage::disk('public')->delete($gallery->path);

        $gallery->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Image supprimée'
        ]);
    }
}
