<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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

    $url = \CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary::upload(
        $request->file('image')->getRealPath()
    )->getSecurePath();

    return response()->json(['url' => $url]);

    }
}
