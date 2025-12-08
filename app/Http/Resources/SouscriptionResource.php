<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SouscriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'organisateur_id' => $this->organisateur_id,
            'utilisateur_id' => $this->utilisateur_id,
            'plans_souscription_id' => $this->plans_souscription_id,
            
            // Détails de la période
            'date_debut' => $this->date_debut,
            'date_fin' => $this->date_fin,
            'est_active' => $this->estActive(), // Utilise la méthode du modèle
            
            // Détails de la transaction
            'statut' => $this->statut,
            'montant' => (float) $this->montant,
            'methode' => $this->methode,
            'statut_paiement' => $this->statut_paiement,
            'reference' => $this->reference,
            'souscription_fedapay_id' => $this->souscription_fedapay_id,
            
            // Relation avec le plan (chargée conditionnellement)
            'plan' => $this->whenLoaded('plan', [
                'id' => $this->plan->id,
                'nom' => $this->plan->nom,
                'prix' => (float) $this->plan->prix,
                'duree_jours' => $this->plan->duree_jours,
            ]),
            
            // Relation avec l'utilisateur (chargée conditionnellement)
            'utilisateur' => $this->whenLoaded('utilisateur', [
                'id' => $this->utilisateur->id,
                'nom' => $this->utilisateur->nom,
                'email' => $this->utilisateur->email,
                'role' => $this->utilisateur->role,
            ]),

            'cree_a' => $this->created_at,
            'mis_a_jour_a' => $this->updated_at,
        ];
    }
}
