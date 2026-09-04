<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function __construct()
    {
        $this->middleware(['web']);
    }

    public function index()
    {
        return view('frontend/index');
    }

    public function aboutus()
    {
        return view('frontend/information/about-us');
    }

    public function gallery()
    {
        return view('frontend/information/gallery');

    }
    public function brands()
    {
        return view('frontend/product/brands');

    }
    public function promotions()
    {
        return view('frontend/information/promotions');
    }

    public function wishlist()
    {
        return view('frontend/account/wishlist');
    }

    public function account()
    {
        return view('frontend/account/account');
    }

    public function login()
    {
        return view('frontend/account/login');
    }

    public function register()
    {
        return view('frontend/account/register');
    }

    public function getLogin()
    {
        return view('frontend.account.login');
    }

    public function postLogin(Request $request)
    {
        $credentials = [
            'email' => $request->get('email'),
            'password' => $request->get('password'),
            'enabled' => 1,
        ];
        if (Auth::attempt($credentials)) {
            return [
                'success' => true,
                'redirect' => '/',
            ];
        } else {
            return [
                'success' => false,
                'redirect' => '/account/login',
            ];
        }
    }

    public function logout()
    {
        return view('frontend/account/logout');
    }

    public function search()
    {
        return view('frontend/product/search');
    }

    public function checkout()
    {
        return view('frontend/checkout/checkout');
    }

    public function cart()
    {
        return view('frontend/checkout/cart');
    }

    public function forgotten()
    {
        return view('frontend/account/forgotten');
    }

    public function accountEdit()
    {
        return view('frontend/account/edit');
    }

    public function password()
    {
        return view('frontend/account/password');
    }

    public function addressEdit()
    {
        return view('frontend/account/address_edit');
    }

    public function orders()
    {
        return view('frontend/account/orders');
    }

    public function order()
    {
        return view('frontend/account/order');
    }

    public function newsletter()
    {
        return view('frontend/account/newsletter');
    }

    public function product()
    {
        return view('frontend/product/product');
    }
}
