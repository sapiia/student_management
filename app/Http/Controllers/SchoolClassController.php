<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SchoolClassController extends Controller
{
    public function index(): View
    {
        $classes = SchoolClass::orderBy('name')->get();

        return view('classes.index', compact('classes'));
    }

    public function create(): View
    {
        return view('classes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:school_classes,name'],
        ]);

        SchoolClass::create($data);

        return redirect()->route('admin.classes.index')->with('status', 'Class added.');
    }

    public function edit(SchoolClass $class): View
    {
        return view('classes.edit', compact('class'));
    }

    public function update(Request $request, SchoolClass $class)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:school_classes,name,' . $class->id],
        ]);

        $class->update($data);

        return redirect()->route('admin.classes.index')->with('status', 'Class updated.');
    }

    public function destroy(SchoolClass $class)
    {
        $class->delete();

        return redirect()->route('admin.classes.index')->with('status', 'Class deleted.');
    }
}
