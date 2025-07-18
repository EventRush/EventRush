<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $tags = ['Musique', 'Techno', 'Gospel', 'Startups', 'Mode', 'Gaming', 'Danse', 'Business'];

        foreach ($tags as $nom) {
            Tag::firstOrCreate(['nom' => $nom]);
        }
    }
}
            