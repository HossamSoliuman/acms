<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use App\Http\Requests\StorePlantRequest;
use App\Http\Requests\UpdatePlantRequest;
use App\Http\Resources\PlantResource;

class PlantController extends Controller
{
    public function index()
    {
        $plants = PlantResource::collection(Plant::all());
        return view('plants', compact('plants'));
    }

    public function store(StorePlantRequest $request)
    {
        $validData = $request->validated();
        $validData['cover'] = $this->uploadFile($validData['cover'], Plant::PathToStoredImages);
        $plant = Plant::create($validData);
        return redirect()->route('plants.index');
    }

    public function show(Plant $plant)
    {
        return $this->successResponse(PlantResource::make($plant));
    }

    public function update(UpdatePlantRequest $request, Plant $plant)
    {
        $validData = $request->validated();
        if ($request->hasFile('cover')) {
            $this->deleteFile($plant->cover);
            $validData['cover'] = $this->uploadFile($request->file('cover'), Plant::PathToStoredImages);
        }
        $plant->update($validData);
        return redirect()->route('plants.index');
    }

    public function destroy(Plant $plant)
    {
        $this->deleteFile($plant->cover);
        $plant->delete();
        return redirect()->route('plants.index');
    }
    public function apiIndex()
    {
        $plants = PlantResource::collection(Plant::all());
        return $this->apiResponse($plants);
    }
}
