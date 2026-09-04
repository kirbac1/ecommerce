<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

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

    /**
     * Contact page. The view has always existed and the footer links to it,
     * but there was no route, so the link 404'd.
     */
    public function contact()
    {
        return view('frontend/information/contact');
    }

    /** Sitemap page -- same story as contact(). */
    public function sitemap()
    {
        return view('frontend/information/sitemap');
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

    /**
     * Sign the customer out.
     *
     * Was returning 'frontend/account/logout', a view that does not exist, and
     * never called logout() -- so this 500'd and the session survived.
     * Customers authenticate on their own guard, so that is the one to clear.
     */
    public function logout(Request $request)
    {
        Auth::guard('customers')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
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
