<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\MainCategory;
use Illuminate\Http\Request;

class MainCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mainCategories = MainCategory::with('media')->get();
        return response()->json(['data' => $mainCategories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //return request()->all();
        try {
            $hotel = Hotel::findOrFail($request->input('hotel_id'));
            $validated = $request->validate([
                'image' => 'required'

            ]);
            $mainCategory = MainCategory::create($request->all());
            $mainCategory->addMediaFromRequest('image')->toMediaCollection();
            $mainCategory->getMedia();
            return response()->json(['data' => $mainCategory], '201');
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $mainCategory = MainCategory::findOrFail($id);
            $mainCategory->getMedia();
            return response()->json(['data' => $mainCategory]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        try {
            $hotel = Hotel::findOrFail($request->input('hotel_id'));
            $mainCategory = MainCategory::findOrFail($id);
            $validated = $request->validate([
                'hotel_id' => 'required',
                'image' => 'required'
            ]);
            $mainCategory->update($validated);
            $mainCategory->addMediaFromRequest('image')->toMediaCollection();
            return response()->json(['data' => $mainCategory], '200');
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $mainCategory = MainCategory::findOrFail($id);
            $mainCategory->delete();
            return response()->json(['data' => $mainCategory]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
