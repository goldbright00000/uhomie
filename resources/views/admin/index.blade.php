<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="icon" type="image/png" sizes="96x96" href="<%= webpackConfig.output.publicPath %>favicon.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>uHomie - Admin </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <!--  Fonts and icons     -->
    <link rel="shortcut icon" href="{{ asset('/images/favicon.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:300,400,500,700,400italic">
    <link rel="stylesheet" href="//fonts.googleapis.com/icon?family=Material+Icons">
    <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/css/admin-panel/material-dashboard.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
  <body>
    <div id="app"></div>
    <!-- built files will be auto injected -->
  </body>
  <script>
    window.ROLE = {{ $role }};
  </script>
  <script src="{{ asset('js/admin/main.js') }}">
  </script>
</html>
