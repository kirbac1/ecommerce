<?php

namespace App\Http\Controllers;

use App\Customer;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class AuthController extends Controller
{
    /**
     * Get a reference to the App.
     *
     * AuthController constructor.
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function customerLogin(Request $request)
    {
        
        $credentials = [
            'email1' => $request->get('email'),
            'password' => $request->get('password'),
            'enabled' => 1,
        ];
        if (Auth::guard('customers')->attempt($credentials)) {
            return [
                'success' => true,
                'token' => csrf_token(),
            ];
        } else {
            return [
                'success' => false,
                'token' => csrf_token(),
            ];
        }

    }

    public function customerLogout(Request $request)
    {
        // Customers sign in on the 'customers' guard, so clearing the default
        // one left them signed in.
        Auth::guard('customers')->logout();
        return [
            'success' => true,
        ];
    }

    public function customerRegistration(Request $request)
    {
        $array = $request->all();
        $array['enabled'] = false;
        $customer = Customer::create($array);
        return $customer;
    }
}