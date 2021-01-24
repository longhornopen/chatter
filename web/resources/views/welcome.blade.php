<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Welcome to Chatter</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Custom CSS -->
        <script src="{{ mix('js/manifest.js') }}"></script>
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    </head>
    <div class="app-title-bar large">
        <img class="app-logo large" src="/images/logo/logo.svg" alt="Chatter logo">
        <span class="app-title large">CHATTER</span>
    </div>
    <div class="color-bar"></div>
    <div class="home-content">
        <h2>Welcome to Chatter!</h2>
        <p>Chatter is a communication platform where instructors and students can post discussion posts containing questions, comments, and notices.</p>
        <p>Go to your class homepage to use Chatter.</p>
    </div>
    <body>

    </body>
</html>
