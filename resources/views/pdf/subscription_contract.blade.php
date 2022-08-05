<?php

/** @var array $lines */
?>
    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>Договор</title>
    <style>
        html, body {
            font-family: DejaVu Sans;
        }

        h1 {
            font-size: 16px;
        }

        p {
            font-size: 14px;
        }
    </style>
</head>
<body>
<h1>Договор</h1>
@foreach($lines as $line)
    <p>{{ $line }}</p>
@endforeach
</body>
</html>
