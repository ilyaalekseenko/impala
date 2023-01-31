<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Impala-CRM</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('/css/app.css') }}"  rel="stylesheet">
    <link href="{{ asset('/css/css1.css') }}"  rel="stylesheet">
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://code.iconify.design/2/2.2.1/iconify.min.js"></script>
    <script src="https://code.iconify.design/iconify-icon/1.0.0-beta.3/iconify-icon.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body >
<div id="app">
    <div>
        <div class="container-fluid">
            <div class="row new_head">

            </div>
        </div>

        <div class="container-fluid container_header_align">
            <div class="row">
                @yield('content')
            </div>
        </div>

    </div>
</div>
</body>
</html>
<script>

</script>
