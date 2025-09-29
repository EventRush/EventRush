<?php

namespace Database\Seeders;

use App\Models\Utilisateur;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScannersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
         $scanners = Utilisateur::where('role', 'scanneur')->get();
         $count = 0;
         foreach ($scanners as $scanner){
            // if(!$ticket->image) {
            // $event = Event::find($id);
            $scanner->email_verified_at = now(); // 'https://res.cloudinary.com/dzdbedjc8/image/upload/v1752243598/lfjkm7baaquklx9dd3xd.jpg';
           
            $scanner->save(); 
            $count++;
            echo "✅ $count scanner mis à jour.\n";
            // }

        }
        echo "Fin, $count scanner mis à jour avec une image.\n";
    
    }
}
