@section('partials.page.body')
    <header id="header" class="clearfix" data-current-skin="blue">
        <ul class="header-inner">
            <li id="menu-trigger" data-trigger="#sidebar">
                <div class="line-wrap">
                    <div class="line top"></div>
                    <div class="line center"></div>
                    <div class="line bottom"></div>
                </div>
            </li>

            <li class="logo hidden-xs">
                <a href="/admin">{{ trans('messages.Admin Section') }}</a>
            </li>

        </ul>


        <!-- Top Search Content -->
        <div id="top-search-wrap">
            <div class="tsw-inner">
                <i id="top-search-close" class="zmdi zmdi-arrow-left"></i>
                <input type="text">
            </div>
        </div>
    </header>

    <section id="main">
        <aside id="sidebar">
            <div class="sidebar-inner">
                <div class="si-inner">
                    <div class="profile-menu">
                        <a href="/admin">
                            <div class="profile-pic">
                                <img src="/assets/img/profile-pics/2.jpg" alt="">
                            </div>

                            <div class="profile-info">
                                @yield('sidebar.username')
                            </div>
                        </a>
                    </div>

                    <ul class="main-menu">
                        <li><a href="/admin"><i class="md md-home"></i> {{ trans('messages.Dashboard') }}</a></li>
                        <li><a href="/admin/customers"><i class="md md-person"></i> {{ trans('messages.Customers') }}</a></li>
                        <li><a href="/admin/customergroups"><i class="md md-people"></i> {{ trans('messages.Customer Groups') }}</a></li>
                        <li><a href="/admin/users"><i class="md md-face-unlock"></i> {{ trans('messages.Users') }}</a></li>
                        <li><a href="/admin/categories"><i class="md md-dvr"></i> {{ trans('messages.Categories') }}</a></li>
                        <li><a href="/admin/manufacturers"><i class="md md-settings-input-component"></i> {{ trans('messages.Manufacturers') }}</a></li>
                        <li><a href="/admin/products"><i class="md md-play-install"></i> {{ trans('messages.Products') }}</a></li>
                        <li><a href="/admin/productmigration"><i class="md md-swap-horiz"></i> {{ trans('messages.Import_Export Products') }}</a></li>
                        
                        <li><a href="/admin/proformas"><i class="md md-work"></i>&nbsp; {{ trans('messages.Proformas') }}</a></li>
                        <li><a href="/admin/orders"><i class="md md-inbox"></i>&nbsp; {{ trans('messages.Orders') }}</a></li>
                        {{--<li><a href="/admin/shipments"><i class="md md-directions-ferry"></i>&nbsp; {{ trans('messages.Shipments') }}</a></li>--}}
                        {{--<li><a href="/admin/payments"><i class="md md-credit-card"></i>&nbsp; {{ trans('messages.Payments') }}</a></li>--}}
                        <li><a href="/admin/receipts"><i class="md md-receipt"></i>&nbsp; {{ trans('messages.Receipts') }}</a></li>
                        <li><a href="/admin/invoices"><i class="md md-local-post-office"></i>&nbsp; {{ trans('messages.Invoices') }}</a></li>
                        <li><a href="/admin/returns"><i class="md md-replay"></i>&nbsp; {{ trans('messages.Returns') }}</a></li>
                        {{--<li><a href="/admin/discounts"><i class="md md-wallet-giftcard"></i> {{ trans('messages.Discounts') }}</a></li>--}}
                        <li><a href="/admin/support"><i class="md md-live-help"></i> {{ trans('messages.Support') }}</a></li>
                        <li><a href="/admin/settings"><i class="md md-settings"></i> {{ trans('messages.Settings') }}</a></li>
                        <li><a href="/admin/logout"><i class="md md-settings-power"></i> {{ trans('messages.Logout') }}</a></li>
                    </ul>
                </div>
            </div>
        </aside>

        <section id="content">
            <div class="container">
                @yield('page.content')
            </div>
        </section>

    </section>
@show