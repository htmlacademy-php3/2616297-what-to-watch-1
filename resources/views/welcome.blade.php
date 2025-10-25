<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <link href="{{ mix('/css/main.css') }}" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

    </head>
    <body class="antialiased">
        <div id="root"></div>

        <script src="{{ mix('js/app.js') }}"></script>
    </body>
</html>
