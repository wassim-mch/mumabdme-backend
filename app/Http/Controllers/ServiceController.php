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
    public function index()
    {
        $services = Service::with(['category', 'galleries', 'joursDisponibles'])->get();

        return response()->json([
            'status' => 200,
            'services' => $services
        ], 200);
    }

    public function store(StoreServiceRequest $request)
    {
        $validated = $request->validated();

        $service = Service::create($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('services', 'public');

                Gallery::create([
                    'service_id' => $service->id,
                    'path' => $path
                ]);
            }
        }

        if (!empty($request->days)) {
            foreach ($request->days as $day) {
                JourDisponible::create([
                    'service_id' => $service->id,
                    'day' => $day
                ]);
            }
        }

        return response()->json([
            'status' => 201,
            'message' => 'Service créé avec succès',
            'service' => $service->load(['galleries', 'joursDisponibles'])
        ], 201);
    }

    public function show(Service $service)
    {
        return response()->json([
            'status' => 200,
            'service' => $service->load(['category', 'galleries', 'joursDisponibles'])
        ]);
    }

    public function update(UpdateServiceRequest $request, Service $service)
    {
        $validated = $request->validated();

        $service->update($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('services', 'public');

                Gallery::create([
                    'service_id' => $service->id,
                    'path' => $path
                ]);
            }
        }

        if ($request->has('days')) {
            $service->joursDisponibles()->delete();

            foreach ($request->days as $day) {
                JourDisponible::create([
                    'service_id' => $service->id,
                    'day' => $day
                ]);
            }
        }

        return response()->json([
            'status' => 200,
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
