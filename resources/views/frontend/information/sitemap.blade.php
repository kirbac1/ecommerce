@extends('layouts.default') @section('content')
<div class="carta-container">
    <div id="container" class="container j-container">
        <ul class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li><a href="#">Site Map</a></li>
        </ul>
        <div class="row">
            <div id="content" class="col-sm-12">
                <h1 class="heading-title">Site Map</h1>
                <div class="row sitemap-info">
                    <div class="col-sm-6 left">
                        <ul>
                            <li><a href="#">Electronics</a>
                                <ul>
                                    <li><a href="#">Desktops</a>
                                        <ul>
                                            <li><a href="#">Subcategory</a></li>
                                            <li><a href="#">PC</a></li>
                                            <li><a href="#">Mac</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="#">Laptops &amp; Notebooks</a>
                                        <ul>
                                            <li><a href="#">Macs</a></li>
                                            <li><a href="#">Windows</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="#">Components</a>
                                        <ul>
                                            <li><a href="#">Monitors</a></li>
                                            <li><a href="#">Printers</a></li>
                                            <li><a href="#">Scanners</a></li>
                                            <li><a href="#">Web Cameras</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="#">Phones &amp; PDAs</a>
                                    </li>
                                    <li><a href="#">Cameras</a>
                                    </li>
                                    <li><a href="#">MP3 Players</a>
                                    </li>
                                </ul>
                            </li>
                            <li><a href="#">Fashion</a>
                                <ul>
                                    <li><a href="#">Accesories</a>
                                        <ul>
                                            <li><a href="#">Belts</a></li>
                                            <li><a href="#">Hats</a></li>
                                            <li><a href="#">Jewelry</a></li>
                                            <li><a href="#">Scarves</a></li>
                                            <li><a href="#">Sunglasses</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="#">Bags</a>
                                        <ul>
                                            <li><a href="#">Clutches</a></li>
                                            <li><a href="#">Formal</a></li>
                                            <li><a href="#">Purses</a></li>
                                            <li><a href="#">Shoulder</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="#">Beauty</a>
                                        <ul>
                                            <li><a href="#">Foundation</a></li>
                                            <li><a href="#">Lipstick</a></li>
                                            <li><a href="#">Makeup</a></li>
                                            <li><a href="#">Mascara</a></li>
                                            <li><a href="#">Nails</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="#">Dresses</a>
                                        <ul>
                                            <li><a href="#">Casual</a></li>
                                            <li><a href="#">Evening</a></li>
                                            <li><a href="#">Occasion</a></li>
                                            <li><a href="#">Skirt</a></li>
                                            <li><a href="#">Summer</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="#">Lingerie</a>
                                        <ul>
                                            <li><a href="#">Bras</a></li>
                                            <li><a href="#">Corsets</a></li>
                                            <li><a href="#">Nightgowns</a></li>
                                            <li><a href="#">Panties</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="#">Pants</a>
                                        <ul>
                                            <li><a href="#">Formal</a></li>
                                            <li><a href="#">Jeans</a></li>
                                            <li><a href="#">Leggings</a></li>
                                            <li><a href="#">Training</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="#">Shoes</a>
                                        <ul>
                                            <li><a href="#">Boots</a></li>
                                            <li><a href="#">Heels</a></li>
                                            <li><a href="#">Running</a></li>
                                            <li><a href="#">Sandals</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="#">Tops</a>
                                        <ul>
                                            <li><a href="#">Blouses</a></li>
                                            <li><a href="#">Jackets</a></li>
                                            <li><a href="#">Shirts</a></li>
                                            <li><a href="#">Sleeveless</a></li>
                                            <li><a href="#">Summer</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <li><a href="#">Food</a>
                                <ul>
                                    <li><a href="#">Breakfast</a>
                                    </li>
                                    <li><a href="#">Dessert</a>
                                    </li>
                                    <li><a href="#">Grill</a>
                                    </li>
                                    <li><a href="#">Pasta</a>
                                    </li>
                                    <li><a href="#">Pizza</a>
                                    </li>
                                    <li><a href="#">Salads</a>
                                    </li>
                                    <li><a href="#">Sandwiches</a>
                                    </li>
                                    <li><a href="#">Sushi</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="col-sm-6 right">
                        <ul>
                            <li><a href="/promotions">Special Offers</a></li>
                            <li><a href="/account">My Account</a>
                                <ul>
                                    <li><a href="/account/edit">Account Information</a></li>
                                    <li><a href="/account/password">Password</a></li>
                                    <li><a href="/account/address_edit">Address Book</a></li>
                                    <li><a href="/account/orders">Order History</a></li>
                                    <li><a href="#">Downloads</a></li>
                                </ul>
                            </li>
                            <li><a href="/cart">Shopping Cart</a></li>
                            <li><a href="/checkout">{{ trans('messages.Checkout') }}</a></li>
                            <li><a href="/search">Search</a></li>
                            <li>Information
                                <ul>
                                    <li><a href="#">Returns Policy</a></li>
                                    <li><a href="#">Theme Features</a></li>
                                    <li><a href="#">About Us</a></li>
                                    <li><a href="#">Delivery Information</a></li>
                                    <li><a href="#">Privacy Policy</a></li>
                                    <li><a href="#">Terms &amp; Conditions</a></li>
                                    <li><a href="/contact">Contact Us</a></li>
                                </ul>
                            </li>
                            <li><a href="#">Journal News</a>
                                <ul>
                                    <li><a href="#">Shopping</a></li>
                                    <li><a href="#">Traveling</a></li>
                                    <li><a href="#">Branding</a></li>
                                    <li><a href="#">New Products</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
