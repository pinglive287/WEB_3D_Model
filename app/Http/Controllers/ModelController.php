<?php

namespace App\Http\Controllers; // ต้องเป็นแบบนี้

use Illuminate\Http\Request;

class ModelController extends Controller
{
    public function view(Request $request)
    {
        $color = $request->query('color', 'red');
        $allowedColors = ['red', 'yellow'];

        if (!in_array($color, $allowedColors)) {
            abort(404, 'Model not found');
        }

        $objPath = asset("models/{$color}.obj");
        $mtlPath = asset("models/{$color}.mtl");

        return view('model-view', compact('objPath', 'mtlPath', 'color'));
    }
}
