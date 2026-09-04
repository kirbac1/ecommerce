    
    <header class="journal-header-center journal-header-mega nolang">
        <div class="journal-top-header j-min z-1"></div>
        <div class="journal-menu-bg j-min z-0"> </div>
        <div class="journal-center-bg j-100 z-0"> </div>
        <div id="header" class="journal-header row z-2">
            <div class="journal-links j-min xs-100 sm-100 md-45 lg-45 xl-45">
                <div class="links j-min">
                    <a href="/wishlist" class="wishlist-total icon-only top-menu-item-1"><i style="margin-right: 5px; color: rgb(221, 0, 23); font-size: 15px" data-icon=""></i><span class="top-menu-link">{{ trans('header.Wish List') }} (<span class="product-count">@{{wishlistLength}}</span>)</span></a>

                    <a href="/cart" class="icon-only top-menu-item-3"><i style="margin-right: 5px; color: rgb(241, 196, 15); font-size: 18px" data-icon=""></i><span class="top-menu-link">{{ trans('header.Cart') }} </span></a>
                    <a href="/checkout" class="icon-only top-menu-item-4"><i style="margin-right: 5px; color: rgb(51, 153, 101); font-size: 16px" data-icon=""></i><span class="top-menu-link">{{ trans('header.Checkout') }} </span></a> </div>
            </div>
            <div class="journal-currency j-min xs-5 sm-5 md-10 lg-10 xl-10">
                <form action="http://journal.digital-atelier.com/3/index.php?route=common/currency/currency" method="post" enctype="multipart/form-data">
                    <div id="currency">
                        <div class="btn-group">
                            <button class="dropdown-toggle" type="button" data-hover="dropdown">
                                <span class="currency-symbol">$</span> <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" style="left: 50%; margin-left: -20px;">
                                <li><a onclick="$(this).closest('form').find('input[name=\'code\']').val('EUR'); $(this).closest('form').submit();">€</a></li>
                                <li><a onclick="$(this).closest('form').find('input[name=\'code\']').val('GBP'); $(this).closest('form').submit();">£</a></li>
                                <li><a onclick="$(this).closest('form').find('input[name=\'code\']').val('USD'); $(this).closest('form').submit();">$</a></li>
                            </ul>
                        </div>
                        <input type="hidden" name="code" value="">
                        <input type="hidden" name="redirect" value="http://journal.digital-atelier.com/3/index.php?route=common/home">
                    </div>
                </form>
            </div>
            <div class="journal-secondary j-min xs-100 sm-100 md-45 lg-45 xl-45">
                <div class="links j-min">
                    <span class="no-link">{{ trans('header.Welcome Message') }} </span>
                    @if (Auth::check() && Auth::user()->isCustomer)

                    <a href="/account"><i style="margin-right: 5px; color: rgb(105, 185, 207); font-size: 16px" data-icon=""></i><span class="top-menu-link">{{ trans('header.my account') }}</span></a>
                    <a href="/account/logout"><i style="margin-right: 5px; color: rgb(221, 106, 106); font-size: 16px" data-icon=""></i><span class="top-menu-link">{{ trans('header.logout') }}</span></a> </div>
                    @else
                    <a href="/account/login" class="icon-only secondary-menu-item-1"><i style="margin-right: 5px; color: rgb(51, 153, 101); font-size: 15px" data-icon=""></i><span class="top-menu-link">{{ trans('header.login') }}</span></a>
                    <a href="/account/register"><i style="margin-right: 5px; color: rgb(105, 185, 207); font-size: 16px" data-icon=""></i><span class="top-menu-link">{{ trans('header.register') }}</span></a> </div>
                    @endif

            </div>
            <div class="journal-logo j-100 xs-100 sm-100 md-30 lg-30 xl-30">
                <div id="logo">
                    <a href="/">
                        <img src="/templates/assets/images/logo.png" width="221" height="34" alt="Ugur Bakkal" title="Ugur Bakkal"> </a>
                </div>
            </div>
            <div class="journal-search j-min xs-100 sm-50 md-45 lg-45 xl-45">
                <div id="search" class="input-group j-min">
                    <input type="text" name="" e="search" v-model="query" v-on:keyup.enter="searchSubmit" placeholder="{{ trans('header.search product') }}" autocomplete="off" class="form-control input-lg" autocomplete2="off">
                    <div class="button-search">
                        <button type="button"><i></i></button>
                    </div>
                    <div class="autocomplete2-suggestions" style="position: absolute; display: none; width: 100%; max-height: 2000px; z-index: 9999;"></div>
                </div>
            </div>
            <div class="journal-cart row j-min xs-100 sm-50 md-25 lg-25 xl-25">
                <div id="cart" class="btn-group btn-block">
                    <button type="button" data-toggle="dropdown" class="btn btn-inverse btn-block btn-lg dropdown-toggle heading"><a><span id="cart-total" data-loading-text="Loading...&nbsp;&nbsp;"> @{{cartLength}} item(s) - @{{cart.products | total 'quantity' 'priceEach' 'qtyPerPack'}} €</span> <i></i></a></button>
                    <div class="content">
                        <ul class="cart-wrapper">
                        <li  v-show="isCartEmpty">
                         <p class="text-center empty">Your shopping cart is empty!</p>
                             </li>
                            <li  v-show="!isCartEmpty" class="mini-cart-info">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr v-for="product in cart.products">
                                            <td class="text-center image">
                                                <a href="/product?id=@{{product.product.id}}"><img src="/catalog/@{{product.product.image}}" alt="@{{product.name}}" title="@{{product.name}}" class="img-thumbnail"></a>
                                            </td>
                                            <td class="text-right quantity">@{{product.quantity}} x </td>
                                            <td class="text-left name"><a href="http://journal.digital-atelier.com/3/index.php?route=product/product&amp;product_id=57">@{{product.product.name}}</a>
                                                <div>
                                                </div>
                                            </td>
                                           
                                            <td class="text-right total">@{{calculatePrice(product.quantity,product.product.priceEach,product.product.qtyPerPack) }}</td>
                                            <td class="text-center remove">
                                                <button type="button" v-on:click="removeFromCart(product.product.id)" title="Remove" class=""><i class=""></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </li>
                            
                            <li  v-show="!isCartEmpty && isLoggedIn">
                                <div class="mini-cart-total">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <td class="text-right right"><strong>Without Tax-Total</strong></td>
                                                <td class="text-right right">@{{cart.products | total 'quantity' 'priceEach' 'qtyPerPack'}}</td>
                                            </tr>
                                             <tr>
                                                <td class="text-right right"><strong>Tax Amount</strong></td>
                                                <td class="text-right right">@{{cart.products | total 'quantity' 'taxAmount' 'qtyPerPack'}}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-right right"><strong>Total</strong></td>
                                                <td class="text-right right">@{{cart.products | total 'quantity' 'taxedPrice' 'qtyPerPack'}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <p class="text-right checkout"><a class="button" href="/cart">View Cart</a>&nbsp;<a class="button" href="/checkout">Checkout</a></p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="journal-menu j-min xs-100 sm-100 md-100 lg-100 xl-100">
                <div class="mobile-trigger">MENU</div>
                <ul class="super-menu mobile-menu menu-floated" style="table-layout: ">
                    <li class="drop-down float-left icon-only main-menu-item-1">
                        <a href="/"><i style="margin-right: 5px; font-size: 20px; top: -1px" data-icon=""></i></a>
                        <span class="mobile-plus">+</span>
                    </li>
                    <li class="mega-menu-mixed float-left main-menu-item-2">
                        <a href="/"><span class="main-menu-text">{{ trans('header.categories') }}</span></a>
                       
                    </li>
                   <li class="drop-down float-left main-menu-item-3">
                        <a href="/brands"><span class="main-menu-text">{{ trans('header.brands') }}</span></a>
                       
                        
                    </li>
                    <li class="drop-down float-left main-menu-item-3">
                        <a href="/about-us"><span class="main-menu-text">{{ trans('header.about us') }}</span></a>
                       
                        
                    </li>
                    <li class="mega-menu-mixed float-left main-menu-item-4">
                        <a href="/promotions"><span class="main-menu-text">{{ trans('header.promotions') }}</span></a>
                        
                        
                    </li>
                    <li class="mega-menu-mixed float-left main-menu-item-5">
                        <a href="/gallery"><span class="main-menu-text">{{ trans('header.gallery') }}</span></a>
                       
                    </li>
                    
                </ul>
            </div>
 
        </div>
    </header>

