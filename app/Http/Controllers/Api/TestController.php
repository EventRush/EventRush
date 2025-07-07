<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Billet;
use App\Models\Event;
use App\Models\Test;
use App\Models\Ticket;
use Cloudinary\Transformation\Resize;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TestController extends Controller
{
    
    public function testcloudinary(Request $request){
        $request->validate([
        'image' => 'required|image|mimes:jpg,jpeg,png|max:4096' 

        ]);
    //     dd(
    //   $request->hasFile('image'),      // doit renvoyer true
    //         $request->file('image')          // ne doit pas être null
    //     );
       if (!$request->hasFile('image')) {
        return response()->json(['error' => 'No file provided'], 400);
    }

    $url = Cloudinary::upload(
        $request->file('image')->getRealPath()
    )->getSecurePath();

    return response()->json(['url' => $url]);

    }
    public function testScann($eventId, Request $request)
    {
        $request->validate(['qr_code' => 'required|string']);

        $test = Test::where('qr_code', $request->qr_code)->first();

        if (!$test) {
            return response()->json(['success' => false, 'message' => 'QR non reconnu.'], 404);
        }

        if ($test->status_scan === 'scanné') {
            return response()->json(['success' => false, 'message' => 'QR déjà utilisé.'], 400);
        }

        $test->update(['status_scan' => 'scanné', 'scanned_at' => now()]);

        return response()->json(['success' => true, 'billet' => [
            'event_id' => $eventId,
            'event' => $test->event_id,
            'message' =>"Code recu : $request->qr_code",
            ]]);
        }

    public function update_Ticket(Request $request, $ticketId){

        // $organisateur = auth()->user();
        $ticket = Ticket::findOrFail($ticketId);
        $event = Event::findOrFail($ticket->event_id);


        // if ($event->utilisateur_id !== $organisateur->id) {
        //     return response()->json(['message' => 'Non autorisé.'], 403);
        // }

        $request->validate([
            'type' => 'in:standart,vip1,vip2',
            'prix' => 'nullable|numeric' ,
            'quantite_disponible' => 'nullable|integer' ,
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:6144'          
        ]);
        $imagePath = null;
        if ($request->hasFile('image')) {
            
            $imagePath = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();   
            $ticket->image = $imagePath;

        }


        if ($request->has('type')) $ticket->type = $request->type;
        if ($request->has('prix')) $ticket->prix = $request->prix;
        if ($request->has('quantite_disponible')) {

            $ticket->quantité_disponible = $request->quantite_disponible;
            $ticket->quantite_restante = $request->quantite_disponible;
        }
        $ticket->save();

        return response()->json($ticket, 201);
    }

    public function getTicketData($billetId)
    {
        $billet = Test::with(['event'])->findOrFail($billetId);

        return response()->json([
            'image' => $billet->image, // image Cloudinary
            'qr_code' => $billet->qr_code,
            'event' => $billet->event->titre,
            // 'type_ticket' => $billet->ticket->type,
            // 'montant' => $billet->montant,
            // 'reference' => $billet->reference,
        ]);
    }
            public function storeImage(Request $request)
            {
                $request->validate([
                    'image' => 'nullable|image|max:4096',
                    'event_id' => 'required',
                ]);

                // Upload dans Cloudinary
                $uploadedFileUrl = null;
                if ($request->hasFile('image')) {
                try {
                    $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();
                } catch (\Exception $e) {
                    Log::error('Erreur lors de l\'upload Cloudinary : ' . $e->getMessage());
                    return response()->json(['message' => 'Erreur lors de l\'upload de l\'image.'], 500);
                }
            }

                // Générer UUID comme contenu du QR code
                // $qrCode = (string) Str::uuid(); 
                $qrCode = (string) $uploadedFileUrl; 

                // Créer en base
                $test = Test::create([
                    'qr_code' => $qrCode,
                    'event_id' => $request->event_id,
                    'image' => $uploadedFileUrl,
                ]);
                

                return response()->json([
                    'message' => 'Image et QR enregistrés.',
                    'data' => $test
                ], 201);
            }

            public function getTicketPublic($billetId)
    {
        $billet = Test::with(['event'])->findOrFail($billetId);
        $image = $billet->image ? asset('storage/' . $billet->image) : null;
        // $path = 'public/events/affiches/' . $billet->image;
        // if(!Storage::exists($path)) {
        //     abort(404, 'Image non trouvée');

        // }
        // $file = Storage::get($path);
        $type = Storage::mimeType($image);


        return response()->json([
            'image' => 'http://127.0.0.1:8000' . $image, // image Cloudinary
            'qr_code' => $billet->qr_code,
            'event' => $billet->event->titre,
            // 'type_ticket' => $billet->ticket->type,
            // 'montant' => $billet->montant,
            // 'reference' => $billet->reference,
        // ], 200)->headers("Content-Type", $type);
        ]);
    }

            public function storeImageinPublic(Request $request)
            {
                $request->validate([
                    'image' => 'nullable|image|max:4096',
                    'event_id' => 'required',
                ]);

                // Upload dans Cloudinary
                $affichePath = null;
                if ($request->hasFile('image')) {
                try {
                    $affichePath = $request->file('image')->store('events/affiches', 'public');               
                } catch (\Exception $e) {                                               
                    Log::error('Erreur lors de l\'upload d\'image : ' . $e->getMessage());
                    return response()->json(['message' => 'Erreur lors de l\'upload de l\'image.'], 500);
                }
            }

                // Générer UUID comme contenu du QR code
                // $qrCode = (string) Str::uuid(); 
                $qrCode = (string) $affichePath; 

                // Créer en base
                $test = Test::create([
                    'qr_code' => $qrCode,
                    'event_id' => $request->event_id,
                    'image' => $affichePath,
                ]); 
                

                return response()->json([
                    'message' => 'Image et QR enregistrés.',
                    'data' => $test
                ], 201);
            }
    // public function showImageWithQR($id)
    // {
    //     $test = Test::findOrFail($id);

    //     $imageUrl = $test->image;
    //     $qrData = $test->qr_code;

    //     $manager = ImageManager::gd(); // On force GD

    //     // 📥 Télécharger l'image distante
    //     $tempMainPath = tempnam(sys_get_temp_dir(), 'img');
    //     file_put_contents($tempMainPath, file_get_contents($imageUrl));

    //     // ✅ Générer un QR code et le stocker dans un fichier temporaire
    //     $tempQrPath = tempnam(sys_get_temp_dir(), 'qr') . '.png';
    //     QrCode::format('png')->size(200)->generate($qrData, $tempQrPath);

    //     try {
    //         // 🖼 Charger l'image et le QR code
    //         $mainImage = $manager->read($tempMainPath);
    //         $qrImage = $manager->read($tempQrPath);

    //         // 🧩 Coller le QR en bas à droite
    //         $mainImage->place($qrImage, 'bottom-right', 10, 10);

    //         // 🧹 Supprimer les fichiers temporaires
    //         File::delete([$tempMainPath, $tempQrPath]);

    //         // 🖼 Retourner l’image finale
    //         return response($mainImage->toJpeg(85))
    //             ->header('Content-Type', 'image/jpeg');

    //     } catch (\Exception $e) {
    //         File::delete([$tempMainPath, $tempQrPath]);
    //         return response()->json([
    //             'message' => 'Erreur lors du traitement de l\'image',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }


    // Stocker l'image et le QR code
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:6144',
            'event_id' => 'required',
        ]);

        // Upload image principale
        $imageUpload = Cloudinary::upload($request->file('image')->getRealPath(), [
            'folder' => 'billets'
        ]);
        $imagePublicId = $imageUpload->getPublicId();

        // Générer et uploader un QR code (exemple UUID)
        $qrText = uniqid(); // ou autre donnée comme code billet
        $qrUpload = Cloudinary::upload("https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={$qrText}", [
            'folder' => 'qr_codes'
        ]);
        $qrPublicId = $qrUpload->getPublicId();

        // Enregistrement en base
        $test = Test::create([
            'image' => $imagePublicId,
            'qr_code' => $qrPublicId,
            'event_id' => $request->event_id,

        ]);

        return response()->json([
            'message' => 'Image et QR enregistrés',
            'test' => $test
        ]);
    }

    // Affichage fusionné
    // public function show($id)
    // {
    //     $test = Test::findOrFail($id);

    //     // Génération de l'URL transformée avec superposition
    //     $imageUrl = Cloudinary::image($test->image)
    //         ->overlay($test->qr_code, ['gravity' => 'east', 'width' => 150, 'crop' => 'scale'])
    //         ->resize(Resize::fill(600, 400))
    //         ->toUrl();

    //     return response()->json([
    //         'message' => 'Image avec QR générée',
    //         'url' => $imageUrl
    //     ]);
    // }


        // lastest in 15H 00
    // public function showImageWithQR($id)
    // {
    //     $test = Test::findOrFail($id);

    //     $imageUrl = $test->image;
    //     $qrData = $test->qr_code;

    //     $manager = ImageManager::gd(); // utilise GD, sinon imagick si activé

    //     // 📥 Télécharger temporairement l'image distante
    //     $tempPath = tempnam(sys_get_temp_dir(), 'img');
    //     $imageBinary = @file_get_contents($imageUrl);

    //     if (!$imageBinary) {
    //         return response()->json(['message' => 'Impossible de charger l\'image distante.'], 400);
    //     }

    //     file_put_contents($tempPath, $imageBinary);

    //     try {
    //         // 🖼 Charger l'image avec Intervention
    //         $mainImage = $manager->read($tempPath);

    //         // 🔲 Générer le QR code en PNG
    //         $qrCodePng = QrCode::format('png')->size(200)->generate($qrData);

    //         // 📎 Lire le QR code comme image Intervention
    //         $qrImage = $manager->read($qrCodePng);

    //         // 🧩 Fusionner le QR code dans l'image (coin en bas à droite)
    //         $mainImage->place($qrImage, 'bottom-right', 10, 10);

    //         // 🧹 Nettoyage du fichier temporaire
    //         File::delete($tempPath);

    //         // 🖼 Retourner l'image finale
    //         return response($mainImage->toJpeg(85))
    //             ->header('Content-Type', 'image/jpeg');

    //     } catch (\Exception $e) {
    //         // En cas d’erreur pendant la lecture ou le placement
    //         File::delete($tempPath);
    //         return response()->json(['message' => 'Erreur lors du traitement de l\'image', 'error' => $e->getMessage()], 500);
    //     }
    // }

        // public function showImageWithQR($id)
        // {
        //     $test = Test::findOrFail($id);

        //     // dd($test);

        //     $imageUrl = $test->image;
        //     $qrData = $test->qr_code;

        //     $manager = ImageManager::gd(); // ou ::imagick()

        //     // Lire l'image originale (depuis URL Cloudinary)
        //      $imageBinary = file_get_contents($imageUrl);
        //     if (!$imageBinary) {
        //         return response()->json(['message' => 'Impossible de charger l\'image distante.'], 400);
        //     } 

        //     $mainImage = $manager->read($imageBinary);

        //     // Générer le QR code en binaire PNG
        //     $qrCodePng = QrCode::format('png')->size(200)->generate($qrData);

        //     // Lire le QR code comme image Intervention
        //     $qrImage = $manager->read($qrCodePng);

        //     // Coller le QR à droite de l'image (ajustable)
        //     $mainImage->place($qrImage, 'right', 10, 0);

        //     return response($mainImage->toJpeg(85))
        //         ->header('Content-Type', 'image/jpeg');
        // }
    }
    
   