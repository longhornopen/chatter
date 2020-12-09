<html>
<body>
Choose a user to log in as:
<ul>
    @foreach ($users as $user)
    <li><a href="dologin?id={{$user->id}}">{{$user->name}} ({{$user->role}})</a></li>
        @endforeach
</ul>
</body>
</html>
