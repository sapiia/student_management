<!DOCTYPE html>
<html>
<head>
    <title>Add Student</title>
</head>
<body>

    <h1>Add Student</h1>

    <form action="/students" method="POST">
        @csrf

        <input type="text" name="name" placeholder="Student Name">

        <button type="submit">Save</button>
    </form>

    <br>

    <a href="/students">Back</a>

</body>
</html>