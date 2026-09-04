@section('page.meta')
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{{ csrf_token() }}" />
@show

<title>@yield('page.title')</title>

<!-- Vendor CSS -->
<link href="/assets/vendors/bootgrid/jquery.bootgrid.min.css" rel="stylesheet">
<link href="/assets/vendors/animate-css/animate.min.css" rel="stylesheet">
<link href="/assets/vendors/sweet-alert/sweet-alert.min.css" rel="stylesheet">
<link href="/assets/vendors/material-icons/material-design-iconic-font.min.css" rel="stylesheet">
<link href="/assets/vendors/socicon/socicon.min.css" rel="stylesheet">

<!-- CSS -->
<link href="/assets/css/app.min.1.css" rel="stylesheet">
<link href="/assets/css/app.min.2.css" rel="stylesheet">
<link href="/assets/css/app.css" rel="stylesheet">
