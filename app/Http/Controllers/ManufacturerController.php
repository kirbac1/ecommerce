<?php

namespace App\Http\Controllers;

use App\Manufacturer;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ManufacturerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->get('limit', 100);
        $start = $request->get('start', 0);

        return Cache::remember('manufacturers', 120, function() {
            return Manufacturer::get();
        })->splice($start, $limit);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $manufacturer = new Manufacturer($request->all());
        $manufacturer->save();
        Cache::forget('manufacturers');
        Cache::forget('products');
        return $manufacturer;
    }

    /**
     * Display the specified resource.
     *
     * @param  Manufacturer  $manufacturer
     * @return \Illuminate\Http\Response
     */
    public function show(Manufacturer $manufacturer)
    {
        return $manufacturer;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Manufacturer  $manufacturer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Manufacturer $manufacturer)
    {
        $manufacturer->update($request->all());
        $manufacturer->save();
        Cache::forget('manufacturers');
        Cache::forget('products');
        return $manufacturer;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Manufacturer  $manufacturer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Manufacturer $manufacturer)
    {
        $manufacturer->delete();
        Cache::forget('manufacturers');
        Cache::forget('products');
        return response([
            'success' => true
        ], 200);
    }
}
