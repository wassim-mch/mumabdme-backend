<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{

    public function store(Request $request, Service $service)
    {
        $request->validate([
            'images.*' => 'required|image|max:2048'
        ]);

        foreach ($request->file('images') as $file) {
            $path = $file->store('services', 'public');

            Gallery::create([
                'service_id' => $service->id,
                'path' => $path
            ]);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Images ajoutées avec succès',
            'service' => $service->load('galleries')
        ]);
    }         

    public function update(Service $service, Gallery $image)
    {
        if ($image->service_id != $service->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $service->update([
            'image' => $image->path
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Default image updated',
            'default_image' => $image->path
        ]);
    }

    public function destroy(Service $service, Gallery $image)
    {
        if ($image->service_id != $service->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        Storage::disk('public')->delete($image->path);

        $image->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Image supprimée avec succès',
        ]);
    }
}
