<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HotelLanguage;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class HotelLanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        try {
            $hotelLanguages = HotelLanguage::all();
            return response()->json(['hotelLanguages' => $hotelLanguages]);
        } catch (Exception $e) {
            return response()->json([$e->getMessage()]);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'hotel_id' => 'required',
                'lang_id' => 'required',
            ]);
            $hotelLanguage = HotelLanguage::create($validated);
            return response()->json(['hotelLanguage' => $hotelLanguage], 201);
        } catch (Exception $e) {
            return response()->json([$e->getMessage(), 400]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        try {
            $hotelLanguage = HotelLanguage::findOrFail($id);
            return response()->json(['hotelLanguage' => $hotelLanguage]);
        } catch (Exception $e) {
            return response()->json([$e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'hotel_id' => 'required',
                'lang_id' => 'required',
            ]);
            $hotelLanguage = HotelLanguage::findOrFail($id);
            $hotelLanguage->update($validated);
            return response()->json(['hotelLanguage' => $hotelLanguage]);
        } catch (Exception $e) {
            return response()->json([$e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $hotelLanguage = HotelLanguage::findOrFail($id);
            $hotelLanguage->delete();
            return response()->json(['hotelLanguage' => $hotelLanguage]);
        } catch (Exception $e) {
            return response()->json([$e->getMessage()]);
        }
    }
}
