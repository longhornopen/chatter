<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Welcome to Chatter</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    @vite(['resources/sass/app.scss'])

</head>
<div class="app-title-bar large" style=" margin: 10px 20px; display: flex; align-items: center;">
    <img class="app-logo large" src="/images/logo/logo.svg" width="80" height="80" alt="Chatter logo">
    <span class="app-title large" style="font-size: xxx-large; font-weight: 900;">CHATTER</span>
</div>
<div class="color-bar"></div>
<div class="home-content">
    <h2>{{$course->name}}</h2>
    <p>Welcome to Chatter, a discussion board for your course.  Let's get you set up.</p>
    <br>
    <p>Would you like to receive updates about this course via email?</p>
    <div style="margin-left:3em;">
    <form method="post" action="/course/{{$course->id}}/welcome">
        @csrf
        <input type="radio" id="email_yes" name="email" value="true" checked>
        <label for="email_yes">Yes, email me updates about new activity.</label>
        <br>
        <input type="radio" id="email_no" name="email" value="false">
        <label for="email_yes">No, I do not want to receive email.</label>
        <br>
        <div><i>You can change this in the Settings menu if you change your mind later.</i></div>
        <br>
        <button type="submit" class="btn btn-primary">OK, take me to my course</button>
    </form>
    </div>
</div>
<body>

</body>
</html>
