<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PunchOutController extends Controller
{
    public function store()
    {
        if (!Auth::user()->isPunchedIn()) {
            return response()->json([
                'message' => 'You cannot punch out without first being punched in.',
            ], 403);
        }

        Auth::user()->punch();

        return response()->json([
            'message' => 'You have been successfully punched out.',
        ], 200);
    }
}
