<!doctype html >
<html class="webkit chrome chrome50 mac journal-desktop is-guest skin-3 responsive-layout mobile-menu-on-tablet boxed-header header-center header-center-sticky product-grid-second-image product-list-second-image home-page layout-1 route-common-home oc2 csstransforms csstransforms3d csstransitions">

<head>
    @include('partials.cashier.head')

    {{-- Per-page stylesheets. Child views must add theirs here rather than in
         a bare <head> block: anything outside a section is written before the
         doctype, which drops the browser into quirks mode. --}}
    @yield('page.head')

</head>

<body>
    @include('partials.cashier.logout')

     @yield('content')

</body>

</html>
