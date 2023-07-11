<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LinkController extends Controller
{
    public function create(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'User is not logged'],
                401);
        }

        $validator = Validator::make($request->all(), [
            'link' => ['required', 'url'],
            'short_code' => ['max:50', 'unique:links']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $shortCode = empty($request['short_code']) ? uniqid() : $request['short_code'];

        Link::create([
            'user_id' => Auth::id(),
            'original_link' => $request['link'],
            'short_code' => $shortCode
        ]);

        return response()->json(['link' => url(route('home')) . '/lm/' . $shortCode], 200);
    }
}
