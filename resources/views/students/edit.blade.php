<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
</head>
<body>

    <h1>Edit Student</h1>

    <form action="/students/{{ $student->id }}" method="POST">
        @csrf
        @method('PUT')

        <input type="text" name="name" value="{{ $student->name }}">

        <button type="submit">Update</button>
    </form>

    <br>

    <a href="/students">Back</a>

</body>
</html>