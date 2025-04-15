<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

class QrCodeController extends Controller
{
    //
    public function vue()
    {
        $qr = session('qr');
        $code = session('code');

        return view('qrcode', compact('qr', 'code'));
    }

    public function generate(Request $request)
    {
        $code = Str::uuid()->toString();
        $qr = QrCode::size(200)->generate($code);

        // Stocker les résultats en session
        return redirect()->route('codeqr.vue')
            ->with('qr', $qr)
            ->with('code',$code);
        }


//     public function generate()
// {
//     $qrCode = QrCode::size(300)->generate('Contenu du QR code');
//     return view('qrCode', ['qrCode' => $qrCode]);
// }
}
