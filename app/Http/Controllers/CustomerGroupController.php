<?php

namespace App\Http\Controllers;

use App\CustomerGroup;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CustomerGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->get('limit', 10);
        Cache::forget('customergroups');
        Cache::forget('customers');
        return CustomerGroup::take($limit)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $customerGroup = new CustomerGroup($request->all());
        $customerGroup->save();
        Cache::forget('customers');
        Cache::forget('customergroups');
        return $customerGroup;
    }

    /**
     * Display the specified resource.
     *
     * @param  CustomerGroup  $customerGroup
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerGroup $customerGroup)
    {
        return $customerGroup;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  CustomerGroup  $customerGroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomerGroup $customerGroup)
    {
        $customerGroup->update($request->all());
        Cache::forget('customergroups');
        Cache::forget('customers');
        return $customerGroup;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  CustomerGroup  $customerGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerGroup $customerGroup)
    {
        $customerGroup->delete();
        Cache::forget('customergroups');
        Cache::forget('customers');
        return response([
            'success' => true
        ], 200);
    }
}
