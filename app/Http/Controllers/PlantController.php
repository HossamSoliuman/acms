<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use App\Http\Requests\StorePlantRequest;
use App\Http\Requests\UpdatePlantRequest;
use App\Http\Resources\PlantResource;
use Illuminate\Http\Request;

class PlantController extends Controller
{
    public function index()
    {
        $plants = PlantResource::collection(Plant::all());
        return view('plants.index', compact('plants'));
    }

    public function store(Request $request)
    {
        $validData = $request->all();
        $validData['cover'] = $this->uploadFile($validData['cover'], Plant::PathToStoredImages);
        $plant = Plant::create($validData);
        return redirect()->route('plants.index');
    }

    public function show(Plant $plant)
    {
        return view('plants.show', compact('plant'));
    }

    public function update(UpdatePlantRequest $request, Plant $plant)
    {
        $validData = $request->validated();
        if ($request->hasFile('cover')) {
            $this->deleteFile($plant->cover);
            $validData['cover'] = $this->uploadFile($request->file('cover'), Plant::PathToStoredImages);
        }
        $plant->update($validData);
        return redirect()->back();
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
