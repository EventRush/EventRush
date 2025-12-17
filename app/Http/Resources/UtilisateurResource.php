<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UtilisateurResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $souscription_actived = false;
        if ($this->souscriptionActive ?? null) $souscription_actived = true ;

        return [
            // Identifiants de base
            'id' => $this->id,
            'nom' => $this->nom,
            'email' => $this->email,
            'avatar' => $this->avatar,
            'role' => $this->role,
            
            // État du compte
            'statut_compte' => $this->statut_compte,
            'est_actif' => $this->estActif(), // Utilise la méthode estActif() du modèle
            'email_verifie_a' => $this->email_verified_at,
            'email_verifie' => (bool) $this->email_verified_at,
            
            // Informations additionnelles
            'points' => $this->points,
            'google_id' => $this->google_id, // Utile pour les connexions sociales

            // Relations (chargées conditionnellement ou en tant que liens)
            // On utilise $this->whenLoaded() pour inclure la relation uniquement si elle a été chargée (e.g., via Eager Loading)
            
            // Profile Organisateur
            // 'organisateur_profil' => OrganisateurProfileResource::make($this->whenLoaded('organisateurProfil')),
            
            // Souscription active
            // On peut choisir d'inclure la relation de base ou la relation active
            'souscription_active' => SouscriptionResource::make($this->whenLoaded('souscriptionActive')),
            'souscription_actived' => $souscription_actived,
            
            // Les relations many-to-many peuvent être renvoyées sous forme de collection de ressources
            'favoris_count' => $this->whenCounted('favoris', $this->favoris_count), // Utilise whenCounted pour les comptes
            // 'favoris' => EventResource::collection($this->whenLoaded('favoris')), // Décommenter si vous voulez la liste complète
            
            'suiveurs_count' => $this->whenCounted('suiveurs', $this->suiveurs_count),
            'suivis_count' => $this->whenCounted('suivis', $this->suivis_count),
            
            // Autres relations (billets, tags, badges)
            'billets_count' => $this->whenCounted('billets', $this->billets_count),
            // 'billets' => BilletResource::collection($this->whenLoaded('billets')),
            
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'badges' => BadgeResource::collection($this->whenLoaded('badges')),

            // Timestamps
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
