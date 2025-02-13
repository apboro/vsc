<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }}</title>

    <script>
        const message = {!! $message ? '"' . $message . '"' : 'null' !!};
    </script>
</head>
<body>
<div id="app"></div>
</body>
<script src="{{ mix('/js/login.js') }}"></script>
</html>
