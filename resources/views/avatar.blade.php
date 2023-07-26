<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>Avatar</h1>
    <form method="POST" action="/upload" enctype="multipart/form-data">

        @csrf
        <input type="file" name="avatar">
        <input type="submit" name="upload">
        <hr>
        <ul>
            @foreach($avatars as $avatar)
            <li>
               <img src="{{asset('storage/avatar/'. $avatar->name)}}">

            </li>
            @endforeach
        </ul>


    </form>
</body>
</html>