<?php

namespace App\Http\Controllers;

use App\Http\Requests\Place\StoreRequest;
use App\Models\Place;
use App\Models\UserToken;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlaceController extends Controller
{
    public function index(Request $request)
    {
        // $userToken = UserToken::where('token', $request->token)->first();

        // if ($userToken == null) {
        //     return response([
        //         'message' => 'Unauthorized user'
        //     ], 401);
        // }

        $places = Place::all();

        return response($places, 200);
    }

    public function store(StoreRequest $request)
    {
        $user = UserToken::where('token', $request->input('token'))->first()->user;
        if (!$user || $user->role != 'ADMIN') {
            return response([
                'message' => 'Unauthorized user'
            ], 401);
        }

        // $validated = $request->validate([
        //     'name' => 'required',
        //     'latitude' => 'required',
        //     'longitude' => 'required',
        //     // 'x' => 'required',
        //     // 'y' => 'required',
        //     'image' => 'required',
        //     'description' => 'required',
        // ]);
        $image_path = $request->image->getClientOriginalName();
        //Store file into storage path
        $path = $request->image->storeAs('public', $image_path);
        error_log(asset(Storage::url($path)));
        $place = Place::create([
            'name' => $request->input('name'),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
            'image_path' => $image_path,
            'description' => $request->input('description'),
        ]);
        if ($place) {
            return response([
                'message' => 'create success'
            ], 200);
        } else {
            return response([
                'message' => 'Data cannot be processed'
            ], 422);
        }
    }

    public function show(Place $place, Request $request)
    {
        $userToken = UserToken::where('token', $request->input('token'));

        if (!$userToken) {
            return response([
                'message' => 'Unauthorized user'
            ], 401);
        }

        return response([
            $place
        ], 200);
    }

    public function update(Request $request, Place $place)
    {
        $user = UserToken::where('token', $request->input('token'))->first()->user;
        if (!$user || $user->role != 'ADMIN') {
            return response([
                'message' => 'Unauthorized user'
            ], 401);
        }

        $input = collect($request->all())->filter()->all();

        if ($place->update($input)) {
            return response([
                'message' => 'update  success'
            ], 200);
        } else {
            return response([
                'message' => 'Data cannot be updated'
            ], 400);
        }
    }

    public function destroy(Place $place, Request $request)
    {
        error_log(UserToken::where('token', $request->input('token'))->first());
        $user = UserToken::where('token', $request->input('token'))->first()->user;
        if (!$user || $user->role != 'ADMIN') {
            return response([
                'message' => 'Unauthorized user'
            ], 401);
        }

        if ($place->delete()) {
            return response([
                'message' => 'delete success'
            ], 200);
        } else {
            return response([
                'message' => 'Data cannot be deleted'
            ], 400);
        }
    }
}
