<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    // Display all students
    public function index()
    {
        $students = Student::all();

        return view('students.index', compact('students'));
    }

    // Show create form
    public function create()
    {
        return view('students.create');
    }

    // Store new student
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        Student::create([
            'name' => $request->name
        ]);

        return redirect('/students');
    }

    // Show edit form
    public function edit($id)
    {
        $student = Student::findOrFail($id);

        return view('students.edit', compact('student'));
    }

    // Update student
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $student = Student::findOrFail($id);

        $student->update([
            'name' => $request->name
        ]);

        return redirect('/students');
    }

    // Delete student
    public function destroy($id)
    {
        $student = Student::findOrFail($id);

        $student->delete();

        return redirect('/students');
    }
}