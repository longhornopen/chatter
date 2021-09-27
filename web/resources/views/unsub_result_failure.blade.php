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
<div class="app-title-bar large" style=" margin: 10px 20px; display: flex; align-items: center;">
    <img class="app-logo large" src="/images/logo/logo.svg" width="80" height="80" alt="Chatter logo">
    <span class="app-title large" style="font-size: xxx-large; font-weight: 900;">CHATTER</span>
</div>
<div class="color-bar"></div>
<div class="home-content">
    <h2>Error while unsubscribing.</h2>
    <p>Sorry, we were unable to unsubscribe you from email updates in your course.  Check to make sure that you're clicking the 'unsubscribe' link in a recent email.</p>
    <p>You can also unsubscribe yourself by going into your course's Chatter board and using the settings (gear) icon in the upper right.</p>
</div>
<body>

</body>
</html>
