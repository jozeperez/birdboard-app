<!DOCTYPE html>
<html>
<head>
    <title>BirdBoard App</title>
</head>
<body>
    <h1>Create a Project</h1>

    <form method="post" action="/projects">
        @csrf

        <div>
            <label for="title">Title</label>

            <input type="text" name="title" placeholder="Title">
        </div>

        <div>
            <label for="description">Description</label>

            <textarea name="description"></textarea>
        </div>

        <div>
            <button type="submit">Create Project</button>
        </div>
    </form>
</body>
</html>
