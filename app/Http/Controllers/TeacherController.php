<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\TeacherAssignment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class TeacherController extends Controller
{
    public function index(): View
    {
        return view('teachers.index', [
            'teachers' => User::where('role', 'teacher')->with('teacherAssignments.schoolClass', 'teacherAssignments.subject')->orderBy('name')->get(),
            'classes' => SchoolClass::orderBy('name')->get(),
            'subjects' => Subject::orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('teachers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8'],
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => 'teacher',
            'password' => Hash::make($data['password']),
        ]);

        return redirect()->route('admin.teachers.index')->with('status', 'Teacher added.');
    }

    public function edit(User $teacher): View
    {
        abort_unless($teacher->role === 'teacher', 404);

        return view('teachers.edit', compact('teacher'));
    }

    public function update(Request $request, User $teacher): RedirectResponse
    {
        abort_unless($teacher->role === 'teacher', 404);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,'.$teacher->id],
            'password' => ['nullable', 'min:8'],
        ]);

        $teacher->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => empty($data['password']) ? $teacher->password : Hash::make($data['password']),
        ]);

        return redirect()->route('admin.teachers.index')->with('status', 'Teacher updated.');
    }

    public function destroy(User $teacher): RedirectResponse
    {
        abort_unless($teacher->role === 'teacher', 404);

        $teacher->delete();

        return redirect()->route('admin.teachers.index')->with('status', 'Teacher deleted.');
    }

    public function storeAssignment(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'teacher_id' => ['required', 'exists:users,id'],
            'school_class_id' => ['required', 'exists:school_classes,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
        ]);

        abort_unless(User::whereKey($data['teacher_id'])->where('role', 'teacher')->exists(), 422);

        TeacherAssignment::firstOrCreate($data);

        return back()->with('status', 'Assignment saved.');
    }

    public function destroyAssignment(TeacherAssignment $teacherAssignment): RedirectResponse
    {
        $teacherAssignment->delete();

        return back()->with('status', 'Assignment removed.');
    }
}
