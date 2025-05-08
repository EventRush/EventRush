<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PlansSouscription;
use App\Models\Utilisateur;
use Illuminate\Http\Request;

class PlansSouscriptionsController extends Controller
{
    //
    public function addPlan(Request $request)
    {
        $admin = auth()->user();

        if($admin->role !== 'admin'){
            return response()->json(['message' => 'Non autorisé'], 403);

        }

        
            $validated = $request->validate([
              
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prix' => 'required|integer',
            'duree_jours' => 'required|integer'
       
        ]);
        // dd([$validated]);
   
        $plan = PlansSouscription::create($validated);
        $plans = PlansSouscription::all();
       

        return response()->json([
            'message' => 'Plan ajouté avec success',
            'plan' => $plan,
            'les_plans' => $plans
        ]);
    }

    public function updatePlan(Request $request, $id)
    {
        $admin = auth()->user();

        if($admin->role !== 'admin'){
            return response()->json(['message' => 'Non autorisé'],403);

        }

        $plan = PlansSouscription::findOrFail($id);
        if(!$plan){
            return response()->json(['message' => 'Plan non trouvé'], 404);

        }

        $request->validate([
            'nom' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'prix' => 'nullable|integer',
            'duree_jours' => 'nullable|integer',
          
        ]);

        if ($request->has('nom')) $plan->nom = $request->nom;
        if ($request->has('description')) $plan->description = $request->description;
        if ($request->has('prix')) $plan->prix = $request->prix;
        if ($request->has('duree_jours')) $plan->duree_jours = $request->duree_jours;
   
        $plan ->save();
       

        return response()->json([
            'message' => 'Plan ajouté avec success',
            'plan' => $plan
        ]);
    }
    public function deletePlan($id){
        $admin = auth()->user();

        if($admin->role !== 'admin'){
            return response()->json(['message' => 'Non autorisé'],403);

        }
        $plan = PlansSouscription::findOrFail($id);
        if(!$plan){
            return response()->json(['message' => 'Plan non trouvé'], 404);

        }

        $plan->delete();

        return response()->json(['message' => 'Plan supprimé']);


    }
}
