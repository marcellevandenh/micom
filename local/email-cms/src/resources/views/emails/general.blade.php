{{-- @component('mail::message')
{!! $body !!}
@endcomponent --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? config('app.name') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 16px;
            color: #333;
            line-height: 1.5;
            background: #f9f9f9;
            margin: 0;
            padding: 20px;
        }
        .email-container {
            max-width: 600px;
            margin: auto;
            background: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }
        .email-header {
            background: white;
            color: #145F48;
            padding: 15px;
            text-align: center;
        }
        .email-body {
            padding: 20px;

        }

        .email-salutation {
            margin-top: 20px;
            font-size: 14px;
            line-height: 1.4;
        }
        .email-salutation a {
            color: #145F48;
        }

        img {
            max-width: 100%;
            height: auto;
            display: block;
        }
        .email-header img {
            max-width: 150px;
            margin: 0 auto;
        }
        .email-footer img {
            max-width: 240px;
            margin: 10px auto 0;
        }


        button,
        .button {
            display: inline-block;
            padding: 0.6em 1.2em;
            font-size: 16px;
            font-weight: 500;
            color: #fff;
            background-color: #145F48;
            border: none;
            border-radius: 4px;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        /* Hover state */
        button:hover,
        .button:hover {
            background-color: #2AA048;
            transform: translateY(-2px);
        }

        /* Active (clicked) state */
        button:active,
        .button:active {
            background-color: #2AA048;
            transform: translateY(0);
        }

        /* Disabled state */
        button:disabled,
        .button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        /* Optional: Full-width button */
        .button-full {
            display: block;
            width: 100%;
        }

        /* Optional: Outline style */
        .button-outline {
            background: transparent;
            color: #2AA048;
            border: 2px solid #2AA048;
        }

        .button-outline:hover {
            background: #2AA048;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            {{-- <h1>{{ config('app.name') }}</h1> --}}
            @if($logo !== null)
             <img src="{{ $logo }}" alt="Logo" style="max-width:150px;height:auto;display:block;margin:0 auto;">
            @else
                {{-- <h1>{{ config('app.name') }}</h1> --}}
            @endif
        </div>

        <div class="email-body">
            {!! $body !!} {{-- Rendered email content with placeholders replaced --}}


            <div class="email-salutation">
                <p>Many thanks,</p>
                <p>Mike and Heather</p>
                <p>03333 448987</p>
                <p><a href="https://www.nfanuk.com">www.nfanuk.com</a></p>

            </div>
        </div>
        <div class="email-footer">
            @isset($footer_logo)
            <img src="{{ $footer_logo }}" alt="Nfan Footer Logo" style="max-width:240px;height:auto;display:block;margin:10px auto 0;">
            @endisset
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
</body>
</html>

