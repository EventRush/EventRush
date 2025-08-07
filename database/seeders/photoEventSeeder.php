<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class photoEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $events = Event::where('affiche',  null)->get();

        foreach ($events as $event) {
            $event->affiche = 'https://res.cloudinary.com/dzdbedjc8/image/upload/v1752243598/lfjkm7baaquklx9dd3xd.jpg';
            $event->save();
        }
        $tab = [42,43,44,46,47,49,50,51,54,55,58,62,65];
        foreach ($tab as $id){
            if($event) {
            $event = Event::find($id);
            $event->affiche = 'https://res.cloudinary.com/dzdbedjc8/image/upload/v1752243598/lfjkm7baaquklx9dd3xd.jpg';
            $event->save();
        }
        }

    }
}
