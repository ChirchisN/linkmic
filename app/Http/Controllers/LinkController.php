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
            return response()->json(['message' => 'User is not logged'], 401);
        }

        $validator = Validator::make($request->all(), [
            'link' => ['required', 'url'],
            'short_code' => ['max:50', 'unique:links']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $shortCode = empty($request['short_code']) ? uniqid() : $request['short_code'];

        $link = Link::create([
            'user_id' => Auth::id(),
            'original_link' => $request['link'],
            'short_code' => $shortCode
        ]);

        $adjustedLink = [
            'id' => $link['id'],
            'original_link' => $link['original_link'],
            'short_link' => url(route('home')) . '/lm/' . $link['short_code'],
            'redirected_count' => $link['redirected_count']
        ];

        return response()->json($adjustedLink, 200);
    }

    public function redirect($shortCode)
    {
        $link = Link::where('short_code', $shortCode)->first();

        if (empty($link)) {
            abort(404);
        }

        $link->redirected_count += 1;
        $link->save();
        return redirect($link->original_link);
    }

    public function getLinks()
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'User is not logged'], 401);
        }

        $userRole = Auth::getUser()->role;
        $userId = Auth::getUser()->id;

        $conditions = [];

        if ($userRole != 'ADMIN') {
            $conditions['user_id'] = $userId;
        }

        $links = Link::where($conditions)->orderBy('id', 'desc')->get();

        $adjustedLinks = $this->adjustLinks($links);

        return response()->json($adjustedLinks);
    }

    private function adjustLinks($links)
    {
        $adjustedLinks = [];
        foreach ($links as $link) {
            $adjustedLinks[] = [
                'id' => $link['id'],
                'original_link' => $link['original_link'],
                'short_link' => url(route('home')) . '/lm/' . $link['short_code'],
                'redirected_count' => $link['redirected_count']
            ];
        }

        return $adjustedLinks;
    }
}
