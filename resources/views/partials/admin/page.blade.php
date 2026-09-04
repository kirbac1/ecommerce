<html>
    <head>
        @include('partials.admin.head')
        @section('page.head')
        @show
    </head>
    <body class="sw-toggled @yield('page.body.class')">
        @include('partials.admin.body')
        @include('partials.admin.foot')
        <script>
            Vue.config.debug = true;
            Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector("meta[name='_token']").getAttribute('content');
        </script>
        @yield('page.footer')
    </body>
</html>