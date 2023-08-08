<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="{{ route('projects.import') }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file">
        <select name="method">
            <option value="PHP">PHP</option>
            <option value="SQL">SQL</option>
        </select>
        <button type="submit">Import</button>
    </form>
    <br>
    <br>
    <h3>Projects: {{ $projectCount }}</h3>
    <h3>Keywords: {{ $keywordCount }}</h3>
    <h3>Technologies: {{ $technologyCount }}</h3>
</body>

</html>