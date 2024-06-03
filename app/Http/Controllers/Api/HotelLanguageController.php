<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\HotelLanguage;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class HotelLanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //        try {
//            $this->authorize('view', HotelLanguage::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
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
        //        try {
//            $this->authorize('create', HotelLanguage::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
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
        //        try {
//            $this->authorize('viewAny', HotelLanguage::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
        try {
            $hotelLanguage = HotelLanguage::findOrFail($id);
            return response()->json(['hotelLanguage' => $hotelLanguage]);
        } catch (Exception $e) {
            return response()->json([$e->getMessage()]);
        }
    }

    public function showByHotelId($id)
    {
        //        try {
//            $this->authorize('viewAny', HotelLanguage::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
        try {
            $hotel = Hotel::findOrFail($id);
            $hotelLanguage = DB::table('hotel_languages as hl')
                ->join('languages as l', 'l.id', '=', 'hl.lang_id')
                ->select('hl.id', 'hl.hotel_id', 'hl.lang_id', 'l.code')
                ->where('hl.hotel_id', $hotel->id)
                ->get();
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
        //        try {
//            $this->authorize('update', HotelLanguage::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
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
        //        try {
//            $this->authorize('forceDelete', HotelLanguage::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
        try {
            $hotelLanguage = HotelLanguage::findOrFail($id);
            $hotelLanguage->delete();
            return response()->json(['hotelLanguage' => $hotelLanguage]);
        } catch (Exception $e) {
            return response()->json([$e->getMessage()]);
        }
    }
}
