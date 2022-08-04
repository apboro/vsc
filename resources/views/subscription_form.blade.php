<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? 'Заполнение формы договора' }}</title>

    <style>
        body {
            display: flex;
            justify-content: center;
            font-family: Nunito, serif;
            background-color: #fff;
            /*background-color: #b1d7f2;*/
        }

        .container {
            width: 100%;
            max-width: 1200px;
        }
    </style>
</head>
<body>
<div class="container">
    <div id="vsc-lead"></div>
</div>
</body>
<script src="{{ mix('/js/lead.js') }}"></script>
</html>
