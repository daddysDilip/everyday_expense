@if(!Auth::guest())
@php
  session_start();
  $_SESSION['KCFINDER'] = array();
  $_SESSION['KCFINDER']['disabled'] = false;
@endphp
@endif

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" type="image/x-icon" />

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('backend/plugins/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('backend/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">

    <link href="{{ asset('backend/dist/css/adminlte.min.css') }}" rel="stylesheet">

    @yield('extracss')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

<!-- Navbar -->
  @include('layouts.admin.navbar')

<!-- /.navbar -->

<!-- Main Sidebar Container -->
  @include('layouts.admin.sidebar')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">


  @yield('content')

</div>
<!-- /.content-wrapper -->
<footer class="main-footer">
  <strong>Copyright &copy; 2014-{{ date('Y') }} <a href="{{ url('/') }}">daddyscode.com</a>.</strong>
  All rights reserved.

</footer>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
  <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

@include('layouts.admin.foot')
  @yield('extrajs')
<script>
  $(document).ready(function(){
      $(".alert").delay(5000).slideUp(300);
  });
</script>
</body>
</html>
