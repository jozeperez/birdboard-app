<!DOCTYPE html>
<html>
<head>
    <title>BirdBoard App</title>
</head>
<body>
    <h1>BirdBoard App</h1>

    <ul>
        @forelse ($projects as $project)
            <li>
                <a href="{{ $project->path() }}">
                    {{ $project->title }}
                </a>
            </li>
        @empty
            <li>No projects yet.</li>
        @endforelse
    </ul>
</body>
</html>
