<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Chatter update for: {{$course->name}}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
    </style>
</head>

<body>
<div>
    <p style="font-size:125%;">
        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAQAAAC0NkA6AAAC8HpUWHRSYXcgcHJvZmlsZSB0eXBlIGV4aWYAAHja7ZZdrtwgDIXfWUWXENsYm+UQfqTuoMvvgWTSmblzpVv1vlSaoADjwMH4c8iE/uvnCD9wUbYYopqnnNKGK+aYuaDj23GVVdMWV70u4fMZPdrD9YBhkjny+OnpHH+z0yVwNAU9vRPyej7YHx/keOr7k9C5kEyPphPtFMr1cnk9oFOgHNvaUna738Lej/acf4QBd5iV2NK+RJ5/R0P0msIozF1INtQs8XBA5k1BCjq+asbAo08iqFXo9AQBeRWn7c6r8Ezl6tEn9icokg57gOExmOlqX9pJXwc/rBDf50m9Vn6wm1xLPAR53mM0D2P0Y3clJoQ0nZu6bWX1MHBHyGVNSyiGW9G3VTKKB2RvBZ221W1HqZSJgWVQpEaFBvXVVqpwMXJnQ8tcAWraXIwzV0Fmk8RZaLBJlgZqLBV4BVa+fKG1bl7LVXIs3AgjmSBGmMFhVt9RPhUaY6Y80eZXrOAXz0SFG5PcrDEKQGjc8khXgG/l+ZpcBQR1hdmxwbLth8SudObWzCNZoAUDFe3xWpC1UwAhwtoKZ0hAYEskSok2YzYixNHBp0DI8dLwDgSkyg1echRJgOM818YcozWWlQ8zziyAUEliQJOlgNU82JA/Fh05VFQ0qmpSU9esJUmKSVNKlubhV0wsmloyM7dsxcWjqyc39+DZS+YsOBw1p2zZc86lYNEC5YLZBQNK2XmXPe66p9123/NeKtKnxqo1Vaseaq6lcZOGc6KlZs1bbqVTRyr12LWnbt177mUg1YaMOHSkYcNHHuWiRuHA+qF8nRrdqPEiNQfaRQ1TzW4SNI8TncxAjCOBuE0CSGiezDanGDlMdJPZlhlvhTK81Amn0SQGgrET66CL3R9yD9xCjP/EjW/kwkT3HeTCRPcJuY/cXlBr82tTNwmL0HwNZ1A3weuHAd0Le5kftS+34W8nvIXeQm+ht9Bb6C30Fvp/hAb+PGT8Sf8Nw4+iyR8OaZcAAA39aVRYdFhNTDpjb20uYWRvYmUueG1wAAAAAAA8P3hwYWNrZXQgYmVnaW49Iu+7vyIgaWQ9Ilc1TTBNcENlaGlIenJlU3pOVGN6a2M5ZCI/Pgo8eDp4bXBtZXRhIHhtbG5zOng9ImFkb2JlOm5zOm1ldGEvIiB4OnhtcHRrPSJYTVAgQ29yZSA0LjQuMC1FeGl2MiI+CiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPgogIDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSIiCiAgICB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIKICAgIHhtbG5zOnN0RXZ0PSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VFdmVudCMiCiAgICB4bWxuczpkYz0iaHR0cDovL3B1cmwub3JnL2RjL2VsZW1lbnRzLzEuMS8iCiAgICB4bWxuczpHSU1QPSJodHRwOi8vd3d3LmdpbXAub3JnL3htcC8iCiAgICB4bWxuczp0aWZmPSJodHRwOi8vbnMuYWRvYmUuY29tL3RpZmYvMS4wLyIKICAgIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIKICAgeG1wTU06RG9jdW1lbnRJRD0iZ2ltcDpkb2NpZDpnaW1wOmJlODRlNGFmLTExODMtNGVjMy1iODAwLWY0OWI5MTkwZWFkYyIKICAgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDowMTJlZmI2Yy03ZDUwLTQwYmMtYmIzNi1hNDIzMjJmZjM4NmQiCiAgIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDpmMGNhMTU0MS1mMmRhLTRlM2EtYjBmYi03YzgzNWZiYWZkMzIiCiAgIGRjOkZvcm1hdD0iaW1hZ2UvcG5nIgogICBHSU1QOkFQST0iMi4wIgogICBHSU1QOlBsYXRmb3JtPSJMaW51eCIKICAgR0lNUDpUaW1lU3RhbXA9IjE2Mjc5MTAwMzExMTg2MzUiCiAgIEdJTVA6VmVyc2lvbj0iMi4xMC4yNCIKICAgdGlmZjpPcmllbnRhdGlvbj0iMSIKICAgeG1wOkNyZWF0b3JUb29sPSJHSU1QIDIuMTAiPgogICA8eG1wTU06SGlzdG9yeT4KICAgIDxyZGY6QmFnPgogICAgIDxyZGY6bGkKICAgICAgc3RFdnQ6YWN0aW9uPSJzYXZlZCIKICAgICAgc3RFdnQ6Y2hhbmdlZD0iLyIKICAgICAgc3RFdnQ6aW5zdGFuY2VJRD0ieG1wLmlpZDowNGJlNWY3Mi1lODIzLTRhNzAtOGJjMC1kOWJkNzc1MDQ4YTAiCiAgICAgIHN0RXZ0OnNvZnR3YXJlQWdlbnQ9IkdpbXAgMi4xMCAoTGludXgpIgogICAgICBzdEV2dDp3aGVuPSIyMDIxLTA4LTAyVDA4OjEyOjQwLTA1OjAwIi8+CiAgICAgPHJkZjpsaQogICAgICBzdEV2dDphY3Rpb249InNhdmVkIgogICAgICBzdEV2dDpjaGFuZ2VkPSIvIgogICAgICBzdEV2dDppbnN0YW5jZUlEPSJ4bXAuaWlkOjYzOTdmNzdiLTIxN2QtNDJiYi1iMWNmLTllNTEzZjY0NTU0OCIKICAgICAgc3RFdnQ6c29mdHdhcmVBZ2VudD0iR2ltcCAyLjEwIChMaW51eCkiCiAgICAgIHN0RXZ0OndoZW49IjIwMjEtMDgtMDJUMDg6MTM6NTEtMDU6MDAiLz4KICAgIDwvcmRmOkJhZz4KICAgPC94bXBNTTpIaXN0b3J5PgogIDwvcmRmOkRlc2NyaXB0aW9uPgogPC9yZGY6UkRGPgo8L3g6eG1wbWV0YT4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgIAo8P3hwYWNrZXQgZW5kPSJ3Ij8+Z0o0UgAAASJpQ0NQSUNDIHByb2ZpbGUAACiRnZCxSsNQFIa/VNEidlIcxCGDjgUXM7lUhSAoxFjB6JQmKRaTGJKU4hv4JvowHQTBN/AFFJz9b3RwMIs3HP6Pwzn/f2+gY6dRVi3uQpbXpesPgsvgyl5+o0uHnr6dMKqKgeed0Ho+X7GMvvSNV/vcn2cpTqpIOlflUVHWYO2LnVldGFaxfjv0D8UPYjvO8lj8JN6Os9iw2fWzdBr9eJrbrCb5xbnpq7ZwOeYUD5sRUyak1PSluTpHOOxJXUpC7qmIpCmJejPN1NyIKjm5HIiGIt2mJW+zyfOUMpLHRF4m4Y5MniYP83+/1z7Omk1rY16EZdi0FlSd8RjeH6EXwNozrFy3ZHV/v61lxmlm/vnGL7qLUE6H2ypKAAAAAmJLR0QA/4ePzL8AAAAJcEhZcwAACxMAAAsTAQCanBgAAAAHdElNRQflCAINDTMSgi/TAAAEFElEQVRYw8WYXWhcVRDHf/drN25MU9rQtDSakpSYpgjFSCwaymLJQzUG+yJKq7aB+iAWSkF8EYrQh74UlTRopUUL0vpg/UgQUlc2KIhWaV21altdBKOU1G1Dm2TZ7N4PH8xudu8959zb1sZz3nbmzuyZ85//zByN8KVh0kAXHdxFEwkgT44JLvIz09h44QbUy6KdAfroph4Do0ri4DDLGVKMkKXETa46+hllBhdPsV1mGKWfuht3YJIkzZzSfPWeI00S80ZcrOAQ+cgOyjvPMCuiOdDoIRMSInnoMvSE3jM6W5m8KQflPclWdLWLbVy/JRceHtfZXuvGqAnU4xyhwefYJc9PfMVZ/iJGouYL8YrTxy9cEAt7AoFy+ZW9rCGOiYFFPZs5GQlzk/SIEZXxKZZ4m+bANcZ4KtKtZYJIMxn2IcrhDUmCaTxMLgLShv15kwzkxWkaFTDfRSlC3iRrCSTtUygyEEI5n0cIWbo6Fv2ByzxHIgRDT2NHIJv+BaYdDYhfUycUsCpSRo1i/aveyYxPZLMzAkefi+Bkhk7Q0RgIhMbl71AnLlMQWkcSDKDpmPQFckErH1JJpPUU2cmXODgKrT5Mkwa6AyKDtlAnCVqZY4zPeATo4klWC/m3mwboFdLEJ6FneYwiGeIVal3DFxKE9ep0CCkvyYaQ2r8DkzFKgMYWtjPBLq4INA06YJ8EF59Sr3DyKAUK83/E4gPOU4fJcaGlfTAkcWLzaiUY/rWOLB7T3FnJmXY0YL/Q0pAuzWyDFzjIUkFh28hJ2gCDpvnfLpHFw2ClBCIcVaSSw7dsrsKMQTsHmKpIX6zB0yomhFaOysNV3h9XcNbM60zVFITLJCv008gJSfsxZJJTosjmnfms7uJdNqBhc4WLzNLEPSznQ44wxjTreU7ap+RgUMmmf85XlRZ+wMPlDE/QSAyLOK28whQucxQUNmwGZclY3u9jAjFO4OJwnOU+EGzij1C674VlXFWo7Eer1JtxQa3U2MQpUqT4XWLhKsvAIqWo03uAGKfwyLNRQoEWFjFewhHaSGHp2KQUE4YHtPAQcJqzEo0SJVrZLSxzHilsHY8R8lKibkbjXuLA14raUcdBVgsleUbwdCDLuPTz+zBpRMMjJz2vxiBbJLJxsqADJQ5TlCg9SDs5XDSWKNrS5yWTSZHDC+cPtkQLV3+Mu8lVtwSBtVY6y6Rr28OkVLHIXt7E5RqdkmC9LMGVr7kTtakLu8BbXMblPSH1r+OSJAaHgkEMNtzVbOzgUeIAd/hO0cY30Rtu8ejgBfr8Ee4njoGOyRKe4bcoo4PmG4KOBYYg/yrwPT8yy0oeoEWCqmme5SMZ5G/TOPc/DKblEfu72ztiL9JjwaI9e/zHDziL8hQV5ZJu+VHtHzFBXmEITou6AAAAAElFTkSuQmCC" alt="Chatter logo" />
        Chatter update for: {{$course->name}}
    </p>
    <p>People are talking about your course in Chatter!  Visit your course to join in or to change your notification settings.</p>
    <hr>
    <ul>
        @foreach ($posts as $post)
            <li>
                @if ($post->is_new)
                <span style="background-color:#B35C22;color:white;padding-left:5px;padding-right:5px;">NEW</span>
                @endif
                <span>{{$post->title}}</span>
                @if (count($post->comments) > 0)
                <span style="color:#666666;font-style:italic;">({{count($post->comments)}} new comments)</span>
                @endif
            </li>
        @endforeach
    </ul>
    <div style="font-size: 80%;">
        Thanks for using Chatter!  If you'd like to stop receiving these emails, <a href="{{$unsub_url}}">click here to unsubscribe</a>.
    </div>
</div>
</body>
</html>
