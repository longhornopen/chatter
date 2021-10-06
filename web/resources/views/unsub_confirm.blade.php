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
    <h2>Unsubscribe?</h2>
    <p>Click the button below if you'd like to stop receiving emails about this course.</p>
    <form method="post" action="/course/{{$course_id}}/unsubscribe_complete">
        @csrf
        <input type="hidden" name="user" value="{{$user}}">
        <input type="hidden" name="token" value="{{$token}}">
        <button type="submit" class="btn btn-primary">Unsubscribe</button>
    </form>
</div>
<body>

</body>
</html>
