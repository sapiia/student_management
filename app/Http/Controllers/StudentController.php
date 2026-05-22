<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(): View
    {
        $students = Student::with(['user', 'schoolClass'])->orderBy('name')->get();

        return view('students.index', compact('students'));
    }

    public function create(): View
    {
        return view('students.create', [
            'classes' => SchoolClass::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8'],
            'school_class_id' => ['nullable', 'exists:school_classes,id'],
            'student_number' => ['nullable', 'string', 'max:50', 'unique:students,student_number'],
            'guardian_name' => ['nullable', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => 'student',
            'password' => Hash::make($data['password']),
        ]);

        Student::create([
            'user_id' => $user->id,
            'school_class_id' => $data['school_class_id'] ?? null,
            'name' => $data['name'],
            'student_number' => $data['student_number'] ?? 'STU-'.str_pad((string) $user->id, 4, '0', STR_PAD_LEFT),
            'guardian_name' => $data['guardian_name'] ?? null,
        ]);

        return redirect()->route('admin.students.index')->with('status', 'Student added.');
    }

    public function edit(Student $student): View
    {
        return view('students.edit', [
            'student' => $student->load('user'),
            'classes' => SchoolClass::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Student $student)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,'.($student->user_id ?? 'NULL')],
            'password' => ['nullable', 'min:8'],
            'school_class_id' => ['nullable', 'exists:school_classes,id'],
            'student_number' => ['nullable', 'string', 'max:50', 'unique:students,student_number,'.$student->id],
            'guardian_name' => ['nullable', 'string', 'max:255'],
        ]);

        $user = $student->user ?: User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => 'student',
            'password' => Hash::make($data['password'] ?? 'password'),
        ]);

        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => 'student',
        ];

        if (! empty($data['password'] ?? null)) {
            $userData['password'] = Hash::make($data['password']);
        }

        $user->update($userData);

        $student->update([
            'user_id' => $user->id,
            'school_class_id' => $data['school_class_id'] ?? null,
            'name' => $data['name'],
            'student_number' => $data['student_number'] ?? null,
            'guardian_name' => $data['guardian_name'] ?? null,
        ]);

        return redirect()->route('admin.students.index')->with('status', 'Student updated.');
    }

    public function destroy(Student $student)
    {
        $user = $student->user;

        $student->delete();
        $user?->delete();

        return redirect()->route('admin.students.index')->with('status', 'Student deleted.');
    }
}
