<?php

namespace App\Http\Controllers;

use App\Http\Resources\LinkResource;
use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LinkController extends Controller
{
    public function create(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'User is not logged'], Response::HTTP_UNAUTHORIZED);
        }

        $validator = Validator::make($request->all(), [
            'link' => ['required', 'url'],
            'short_code' => ['max:50', 'unique:links']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $shortCode = empty($request['short_code']) ? uniqid() : $request['short_code'];

        $link = Link::create([
            'user_id' => Auth::id(),
            'original_link' => $request['link'],
            'short_code' => $shortCode
        ]);

        return new LinkResource($link);
    }

    public function redirect($shortCode)
    {
        $link = Link::where('short_code', $shortCode)->first();

        if (empty($link)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $link->redirected_count += 1;
        $link->save();
        return redirect($link->original_link);
    }

    public function getLinks()
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'User is not logged'], Response::HTTP_UNAUTHORIZED);
        }

        $userRole = Auth::getUser()->role;
        $userId = Auth::getUser()->id;

        $conditions = [];

        if ($userRole != 'ADMIN') {
            $conditions['user_id'] = $userId;
        }

        return LinkResource::collection(Link::where($conditions)->orderBy('id', 'desc')->get());
    }

    public function destroy($id)
    {
        $link = Link::find($id);

        if ($link == null) {
            return response()->json(['message' => 'The link in not found!'], Response::HTTP_NOT_FOUND);
        }

        $link->delete();

        return response()->json(['message' => 'The link is deleted'], Response::HTTP_OK);
    }
}
