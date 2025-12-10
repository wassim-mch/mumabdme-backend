<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Models\JourDisponible;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use Symfony\Component\HttpKernel\HttpCache\Store;

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
            'message' => 'Service a été créé avec succès',
            'service' => $service->load(['galleries', 'joursDisponibles'])
        ], 201);
    }

    public function show(Service $service)
    {
        return response()->json([
            'status' => 200,
            'service' => $service->load(['category','galleries','joursDisponibles'])
        ], 200);    
    }

    public function update(UpdateServiceRequest $request, Service $service)
    {
        $validated = $request->validated();

        $service->update($validated);

        if ($request->hasFile('images')) {

            $service->galleries()->delete();

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
            'message' => 'Service a été mis à jour avec succès',
        ], 200);
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Service a été supprimé avec succès',
        ], 200);
    }
}
