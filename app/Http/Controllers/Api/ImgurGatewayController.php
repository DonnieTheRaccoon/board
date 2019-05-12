<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ImgurGatewayController extends Controller
{
    public function upload(Request $request){
        $request->validate([
             'file' => ['required', 'image']
        ]);

        try {
            $image = \Imgur::upload($request->file);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Une erreur est survenue lors de l\'envoi à Imgur'
                // 'exception' => $e->getMessage()
            ]);
        }

        return response()->json([
            'success' => true,
            'file' => $image->usual()
        ]);
    }
}
