<?php

namespace App\Http\Controllers;

use Auth;
use App\Category;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class CashierController extends Controller
{
    public function __construct()
    {
        $this->middleware(['web']);
    }

    public function index()
    {
        $user = Auth::user();
        if ($user) {
            if ($user->isUser) {
                return view('/cashier/index');
            } else {
                App::abort(401, 'Unauthorized.');
                return false;
            }
        } else return redirect('/cashier/login');
    }

    public function aboutus()
    {
        return view('cashier/information/about-us');
    }

    public function gallery()
    {
        return view('cashier/information/gallery');

    }

    public function promotions()
    {
        return view('cashier/information/promotions');
    }

    public function wishlist()
    {
        return view('cashier/account/wishlist');
    }

    public function account()
    {
        return view('cashier/account/account');
    }

    public function login()
    {
        return view('cashier/account/login');
    }

    public function register()
    {
        return view('cashier/account/register');
    }

    public function getLogin()
    {
        return view('cashier.login');
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
                'redirect' => '/cashier',
            ];
        } else {
            return [
                'success' => false,
                'redirect' => '/cashier/login',
            ];
        }
    }

    public function logout()
    {
        return view('cashier/account/logout');
    }

    public function search()
    {
        return view('cashier/product/search');
    }

    public function checkout()
    {
        return view('cashier/checkout/checkout');
    }

    public function cart()
    {
        return view('cashier/checkout/cart');
    }

    public function forgotten()
    {
        return view('cashier/account/forgotten');
    }

    public function accountEdit()
    {
        return view('cashier/account/edit');
    }

    public function password()
    {
        return view('cashier/account/password');
    }

    public function addressEdit()
    {
        return view('cashier/account/address_edit');
    }

    public function orders()
    {
        return view('cashier/account/orders');
    }

    public function order()
    {
        return view('cashier/account/order');
    }

    public function newsletter()
    {
        return view('cashier/account/newsletter');
    }

    public function product()
    {
        return view('cashier/product/product');
    }

    public function proforma()
    {
        return view('/cashier/proforma');
    }

    public function shipment()
    {
        return view('/cashier/shipment');
    }

    public function invoice()
    {
        return view('/cashier/invoice');
    }

    public function receipt()
    {
        return view('/cashier/receipt');
    }

    public function returned()
    {
        return view('/cashier/return');
    }

    public function returnPreview()
    {
        return view('/cashier/returnPreview');
    }
}
