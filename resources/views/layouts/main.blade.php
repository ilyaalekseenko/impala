<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Impala-CRM</title>
    <link rel="icon" href="{{ asset('/favicon.svg') }}">
    <link href="{{ asset('/assets/css/iconsax.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/css/app.css') }}" rel="stylesheet">

    <script src="{{ asset('/assets/js/app.js') }}" defer></script>
</head>
<body>
<div id="app">
    <vue3-confirm-dialog></vue3-confirm-dialog>
    <header-menu-component :user="{{Auth::user()}}"></header-menu-component>
    @yield('content')
    <modals-container></modals-container>
</div>
</body>
</html>
