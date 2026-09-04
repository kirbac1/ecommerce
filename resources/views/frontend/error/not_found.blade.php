<html>

<head>
    @include('partials.frontend.header') @section('main.head') @show
</head>

<body class="sw-toggled @yield('page.body.class')">
    <div class="carta-container">
        <div id="container" class="container j-container not-found-page">
            <ul class="breadcrumb">
                <li><a href="http://journal.digital-atelier.com/3/index.php?route=common/home">Home</a></li>
                <li><a href="http://journal.digital-atelier.com/3/index.php?route=error/not_found">The page you requested cannot be found!</a></li>
            </ul>
            <div class="row">
                <div id="content" class="col-sm-12">
                    <h1 class="heading-title">The page you requested cannot be found!</h1>
                    <p>The page you requested cannot be found.</p>
                    <div class="buttons">
                        <div class="pull-right"><a href="http://journal.digital-atelier.com/3/index.php?route=common/home" class="btn btn-primary button">Continue</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
