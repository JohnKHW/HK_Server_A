<?php

namespace App\Http\Controllers;

use App\Http\Requests\Place\DestroyRequest;
use App\Http\Requests\Place\IndexRequest;
use App\Http\Requests\Place\ShowRequest;
use App\Http\Requests\Place\StoreRequest;
use App\Http\Requests\Place\UpdateRequest;
use App\Models\Place;
use App\Models\UserToken;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlaceController extends Controller
{
    public function index(IndexRequest $request)
    {
        $places = Place::all();

        return response($places, 200);
    }

    public function store(StoreRequest $request)
    {

        $image_path = $request->image->getClientOriginalName();
        //Store file into storage path
        $request->image->storeAs('public', $image_path);
        Place::create([
            'name' => $request->input('name'),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
            'image_path' => $image_path,
            'description' => $request->input('description'),
        ]);
        return response([
            'message' => 'create success'
        ], 200);
    }

    public function show(Place $place, ShowRequest $request)
    {
        return response([
            $place
        ], 200);
    }

    public function update(UpdateRequest $request, Place $place)
    {
        $input = collect($request->all())->filter()->all();
        $place->update($input);
        return response([
            'message' => 'update  success'
        ], 200);
    }

    public function destroy(Place $place, DestroyRequest $request)
    {
        $place->delete();
        return response([
            'message' => 'Delete success'
        ], 200);
    }
}
