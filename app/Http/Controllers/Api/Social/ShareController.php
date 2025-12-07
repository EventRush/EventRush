<?php

namespace App\Http\Controllers\Api\Social;

use App\Http\Controllers\Controller;
use App\Models\Share;
use Illuminate\Http\Request;

class ShareController extends Controller
{
    //
    // public function store(Request $request)
    // {
    //     $data = $request->validate([
    //         'shareable_type' => 'required|string',
    //         'shareable_id' => 'required|integer',
    //     ]);

    //     $share = Share::create([
    //         'utilisateur_id' => auth()->id(),
    //         'shareable_type' => $data['shareable_type'],
    //         'shareable_id' => $data['shareable_id'],
    //     ]);

    //     return response()->json($share);
    // }

    /**
     * Store a share record
     * Body: shareable_type, shareable_id
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'shareable_type' => 'required|string',
            'shareable_id'   => 'required|integer',
        ]);

        $share = Share::create([
            'utilisateur_id' => auth()->id(),
            'shareable_type' => $data['shareable_type'],
            'shareable_id'   => $data['shareable_id'],
        ]);

        return response()->json($share, 201);
    }

    /**
     * Count shares for an item
     * GET /shares?shareable_type=...&shareable_id=...
     */
    public function count(Request $request)
    {
        $data = $request->validate([
            'shareable_type' => 'required|string',
            'shareable_id'   => 'required|integer',
        ]);

        $total = Share::where('shareable_type', $data['shareable_type'])
            ->where('shareable_id', $data['shareable_id'])
            ->count();

        return response()->json(['total' => $total]);
    }

}
