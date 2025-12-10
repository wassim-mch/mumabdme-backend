<?php

namespace App\Http\Controllers;

use App\Models\Rdv;
use App\Models\Facture;
use App\Models\RdvItem;
use Illuminate\Http\Request;

class RdvController extends Controller
{
    public function indexAll()
    {
        $rdvs = Rdv::with('items.service', 'facture')->get();
        return response()->json([
            'status' => 200,
            'rdvs' => $rdvs
        ], 200);
    }

    public function index()
    {
        $rdvs = Rdv::where('user_id', auth()->id())->with('items.service', 'facture')->get();
        return response()->json([
            'status' => 200,
            'rdvs' => $rdvs
        ], 200);
    }

    public function store(Request $request)
    {
        if (!empty($request->items)) {
            $rdv = new Rdv();
            $rdv->user_id = $request->user()->id;
            $rdv->status = $request->status ?? 'en attente';
            $rdv->scheduled_at = $request->scheduled_at;
            $rdv->save();

            foreach ($request->items as $item) {
                $rdvItem = new RdvItem();
                $rdvItem->rdv_id = $rdv->id;
                $rdvItem->service_id = $item['service_id'];
                $rdvItem->price = $item['price']; 
                $rdvItem->save();
            }

            $total = collect($request->items)->sum(fn($i) => $i['price']);
            $facture = new Facture();
            $facture->user_id = $request->user()->id;
            $facture->rdv_id = $rdv->id;
            $facture->total = $total;
            $facture->save();

            return response()->json([
                'status' => 200,
                'rdv_id' => $rdv->id,
                'message' => 'Rdv successfully created with items and facture'
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No items provided for the Rdv'
            ], 404);
        }
    }

    public function update(Request $request, Rdv $rdv)
    {
        $validated = $request->validate([
            'status' => 'in:en attente,confirmer,complet,annuler',
            'scheduled_at' => 'date',
        ]);

        $rdv->update($validated);
        return response()->json([
            'status' => 200,
            'message' => 'Rendez-vous mis à jour avec succès.'
        ]);
    }

    public function destroy(Rdv $rdv)
    {
        if ($rdv->status === 'en attente') {
            $rdv->update(['status' => 'annuler']);
            return response()->json([
                'status' => 200,
                'message' => 'Rendez-vous annulé avec succès.'
            ]);
        } else {
            return response()->json([
                'status' => 403,
                'message' => 'Seul les rendez-vous "en attente" peuvent être annulés.'
            ], 403);
        }
    }
}
