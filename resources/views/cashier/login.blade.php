

@extends('layouts.cashier')
 @section('content')
<body class="login-content">
    <div class="lc-block toggled" id="l-login">
        <div class="input-group m-b-20">
            <span class="input-group-addon"><i class="md md-account"></i></span>
            <div class="fg-line">
                <input type="text" class="form-control" placeholder="{{ trans('messages.E-Mail') }}" v-model="email" @keyup.enter.prevent="doLogin">
            </div>
        </div>

        <div class="input-group m-b-20">
            <span class="input-group-addon"><i class="md md-male"></i></span>
            <div class="fg-line">
                <input type="password" class="form-control" placeholder="{{ trans('messages.Password') }}" v-model="password" @keyup.enter.prevent="doLogin">
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="checkbox">
            <label>
                <input type="checkbox" value="" v-model="remember">
                <i class="input-helper"></i>
                {{ trans('messages.Keep me signed in') }}
            </label>
        </div>

        <a href="#" class="btn btn-login btn-danger btn-float" @click.prevent="doLogin"><i class="md md-arrow-forward"></i></a>
    </div>

    <!-- Older IE warning message -->
    <!--[if lt IE 9]>
    <div class="ie-warning">
        <h1 class="c-white">Warning!!</h1>
        <p>You are using an outdated version of Internet Explorer, please upgrade <br/>to any of the following web browsers to access this website.</p>
        <div class="iew-container">
            <ul class="iew-download">
                <li>
                    <a href="http://www.google.com/chrome/">
                        <img src="/assets/img/browsers/chrome.png" alt="">
                        <div>Chrome</div>
                    </a>
                </li>
                <li>
                    <a href="https://www.mozilla.org/en-US/firefox/new/">
                        <img src="/assets/img/browsers/firefox.png" alt="">
                        <div>Firefox</div>
                    </a>
                </li>
                <li>
                    <a href="http://www.opera.com">
                        <img src="/assets/img/browsers/opera.png" alt="">
                        <div>Opera</div>
                    </a>
                </li>
                <li>
                    <a href="https://www.apple.com/safari/">
                        <img src="/assets/img/browsers/safari.png" alt="">
                        <div>Safari</div>
                    </a>
                </li>
                <li>
                    <a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie">
                        <img src="/assets/img/browsers/ie.png" alt="">
                        <div>IE (New)</div>
                    </a>
                </li>
            </ul>
        </div>
        <p>Sorry for the inconvenience!</p>
    </div>
    <![endif]-->

    <!-- Javascript Libraries -->
    <script src="/assets/js/jquery-2.2.0.min.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
    <script src="/assets/vendors/sweet-alert/sweet-alert.min.js"></script>

    <script src="/assets/vendors/waves/waves.min.js"></script>

    <!-- Placeholder for IE9 -->
    <!--[if IE 9 ]>
    <script src="/assets/vendors/jquery-placeholder/jquery.placeholder.min.js"></script>
    <![endif]-->

    <script src="/assets/js/functions.js"></script>
    <script src="/assets/js/vue-1.0.17.js"></script>
    <script src="/assets/js/vue-resource-0.7.0.js"></script>
     <script>
            Vue.config.debug = true;
         

            var getXsrfToken = function() {
    var cookies = document.cookie.split(';');
    var token = '';

    for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i].split('=');
        if(cookie[0] == 'XSRF-TOKEN') {
            token = decodeURIComponent(cookie[1]);
        }
    }

    return token;
}

   Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector("meta[name='_token']").getAttribute('content');
    Vue.http.headers.common['X-XSRF-TOKEN'] =getXsrfToken();
        </script>
    <script>
        var vue = new Vue({
            el: 'body',
            data: {
                @if(app('env') == 'local')
                    email: '',
                    password: '',
                    remember: true,
                @else
                    email: '',
                    password: '',
                    remember: false,
                @endif
            },
            methods: {
                doLogin: function doLogin() {
                    var that = this;
                    var credentials = { email: this.email, password: this.password, remember: this.remember };
                    Vue.http.post('/cashier/login', credentials).then(function success(response) {
                        if (response.data.success) {
                            if (that.remember) localStorage.setItem('login_email', that.email);
                            location.href = "/cashier";
                        } else {
                            if (that.remember) localStorage.setItem('login_email', '');
                            swal({
                                title: "{{ trans('messages.Authentication failed!') }}",
                                text: "{{ trans('messages.Check your credentials again: these ones are not valid.') }}",
                                showConfirmButton: true,
                                type: 'error',
                                html: true,
                                closeOnConfirm: true
                            });
                        }
                    }, function error(response) {
                        swal({
                            title: "{{ trans('messages.Authentication doesn\'t seem to work!') }}",
                            text: "{{ trans('messages.Could not connect to the server to authenticate. Please, check back later.') }}",
                            showConfirmButton: true,
                            type: 'warning',
                            html: true,
                            closeOnConfirm: true
                        });
                    });
                },
            },
            ready: function ready() {
                Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector("meta[name='_token']").content;
                if (localStorage.getItem('login_email')) this.email = localStorage.getItem('login_email');
            }
        });
    </script>

@stop 