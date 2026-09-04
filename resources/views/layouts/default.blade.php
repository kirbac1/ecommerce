<!doctype html >
<html class="webkit chrome chrome50 mac journal-desktop is-guest skin-3 responsive-layout mobile-menu-on-tablet boxed-header header-center header-center-sticky product-grid-second-image product-list-second-image home-page layout-1 route-common-home oc2 csstransforms csstransforms3d csstransitions">

<head>
    @include('partials.frontend.head')

        @section('head')
        @show

</head>

<body>
    @include('partials.frontend.header') @yield('content') 

      @include('partials.frontend.footer')
    @yield('footer')
    <script>
    Vue.config.debug = true;
    Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector("meta[name='_token']").getAttribute('content');
    </script>
</body>

</html>
