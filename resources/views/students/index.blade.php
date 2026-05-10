<!DOCTYPE html>
<html>
<head>
    <title>Students</title>
</head>
<body>

    <h1>Student List</h1>

    <a href="/students/create">Add Student</a>

    <br><br>

    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Actions</th>
        </tr>

        @foreach($students as $student)
        <tr>
            <td>{{ $student->id }}</td>
            <td>{{ $student->name }}</td>
            <td>
                <a href="/students/{{ $student->id }}/edit">Edit</a>

                <form action="/students/{{ $student->id }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')

                    <button type="submit">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach

    </table>

</body>
</html>