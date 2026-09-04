<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Measureunit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MeasureunitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Cache::remember('measureunits', 120, function() {
            return Measureunit::get();
        });
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $measureunit = new Measureunit($request->all());
        $measureunit->save();
        Cache::forget('measureunits');
        Cache::forget('products');
        return $measureunit;
    }

    /**
     * Display the specified resource.
     *
     * @param  Measureunit  $measureunit
     * @return \Illuminate\Http\Response
     */
    public function show(Measureunit $measureunit)
    {
        return $measureunit;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Measureunit  $measureunit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Measureunit $measureunit)
    {
        $measureunit->update($request->all());
        $measureunit->save();
        Cache::forget('measureunits');
        Cache::forget('products');
        return $measureunit;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Measureunit  $measureunit
     * @return \Illuminate\Http\Response
     */
    public function destroy(Measureunit $measureunit)
    {
        $measureunit->delete();
        Cache::forget('measureunits');
        Cache::forget('products');
        return response([
            'success' => true
        ], 200);
    }
}
