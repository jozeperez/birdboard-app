<!DOCTYPE html>
<html>
<head>
    <title>BirdBoard App</title>
</head>
<body>
    <h1>BirdBoard App</h1>

    <ul>
        @foreach ($projects as $project)
            <li>{{ $project->title }}</li>
        @endforeach
    </ul>
</body>
</html>
