<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Models\JourDisponible;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::with('joursDisponibles');

        if ($request->category_id) {
            $query->where('category_id', $request->category_id)->with('joursDisponibles');
        }

        if ($request->min_price) {
            $query->where('price', '>=', $request->min_price)->with('joursDisponibles');
        }

        if ($request->max_price) {
            $query->where('price', '<=', $request->max_price)->with('joursDisponibles');
        }

        $services = $query->paginate(4);

        return response()->json([
            'status' => 200,
            'services' => $services->items(),
            'total' => $services->total(),
        ]);
    }

    public function store(StoreServiceRequest $request)
    {
        $validated = $request->validated();
        $days = $validated['days'] ?? [];
        unset($validated['days']);

        if ($request->hasFile('image')) {
            $mainImagePath = $request->file('image')->store('services', 'public');
            $validated['image'] = $mainImagePath;
        }

        $service = Service::create($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('services', 'public');

                Gallery::create([
                    'service_id' => $service->id,
                    'path'       => $path
                ]);
            }
        }

        foreach ($days as $day) {
            JourDisponible::create([
                'service_id' => $service->id,
                'day'        => $day
            ]);
        }

        return response()->json([
            'status'  => 201,
            'message' => 'Service créé avec succès',
        ], 201);
    }
    



    public function show(Service $service)
    {
        $service->each(function($s) {
            $s->galleries->transform(function($g) {
                $g->path = Storage::url($g->path);
                return $g;
            });
        });
        return response()->json([
            'status' => 200,
            'service' => $service->load(['category', 'galleries', 'joursDisponibles'])
        ]);
    }

    public function update(UpdateServiceRequest $request, Service $service)
    {
        $validated = $request->validated();
        $days = $validated['days'] ?? [];
        unset($validated['days']);

        // -------------------------------
        // التعامل مع الصورة الافتراضية الجديدة
        // -------------------------------
        // 1) إذا رفعنا صورة جديدة كصورة رئيسية
        if ($request->hasFile('image')) {
            // حذف الصورة الرئيسية القديمة من التخزين
            if ($service->image) {
                Storage::disk('public')->delete($service->image);
            }

            // رفع الصورة الجديدة
            $mainImagePath = $request->file('image')->store('services', 'public');
            $validated['image'] = $mainImagePath;

            // إذا كانت هناك صورة افتراضية قديمة → تحويلها للـ gallery
            if ($request->old_main_image_to_gallery) {
                Gallery::create([
                    'service_id' => $service->id,
                    'path'       => str_replace('/storage/', '', $request->old_main_image_to_gallery)
                ]);
            }
        }

        // 2) إذا اخترنا صورة قديمة كصورة رئيسية
        if ($request->default_old_image) {
            // الصورة الحالية تتحول إلى gallery إذا ليست نفسها
            if ($service->image && $service->image !== str_replace('/storage/', '', $request->default_old_image)) {
                Gallery::create([
                    'service_id' => $service->id,
                    'path'       => $service->image
                ]);
            }

            $service->image = str_replace('/storage/', '', $request->default_old_image);
        }

        // -------------------------------
        // حذف الصور القديمة إذا تم تحديدها
        // -------------------------------
        if ($request->oldImagesToDelete) {
            foreach ($request->oldImagesToDelete as $id) {
                $gallery = Gallery::find($id);
                if ($gallery) {
                    Storage::disk('public')->delete($gallery->path);
                    $gallery->delete();
                }
            }
        }

        // -------------------------------
        // تحديث بيانات الخدمة
        // -------------------------------
        $service->update($validated);

        // -------------------------------
        // تحديث الأيام المتاحة
        // -------------------------------
        $service->joursDisponibles()->delete();
        foreach ($days as $day) {
            JourDisponible::create([
                'service_id' => $service->id,
                'day'        => $day
            ]);
        }

        // -------------------------------
        // رفع الصور الجديدة (غير الرئيسية)
        // -------------------------------
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('services', 'public');

                Gallery::create([
                    'service_id' => $service->id,
                    'path'       => $path
                ]);
            }
        }

        return response()->json([
            'status'  => 200,
            'message' => 'Service mis à jour avec succès',
            'service' => $service->load(['galleries', 'joursDisponibles'])
        ]);
    }



    public function destroy(Service $service)
    {
        foreach ($service->galleries as $img) {
            Storage::disk('public')->delete($img->path);
        }

        $service->galleries()->delete();
        $service->joursDisponibles()->delete();

        $service->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Service supprimé avec succès'
        ]);
    }
}
