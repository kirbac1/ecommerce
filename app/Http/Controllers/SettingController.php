<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Support\Settings as Setting;

class SettingController extends Controller
{
    /**
     * Create a new authentication controller instance.
     */
    public function __construct()
    {
        // Only admin can change the settings.
        $this->middleware(['admins']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Setting::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        foreach($request->get('settings') as $key => $value) {
            Setting::set($key, $value);
        }
        return Setting::all();
    }

    /**
     * Display the specified resource.
     *
     * @param  Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function show(Setting $setting)
    {
        return $setting;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Setting   $setting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Setting $setting)
    {
        $setting->forget();
        return response([
            'success' => true
        ], 200);

    }
}
