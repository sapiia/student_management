<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Show admin dashboard with average scores
    public function dashboard()
    {
        $students = Student::all();
        $totalStudents = $students->count();

        $quizAverage = $students->whereNotNull('quiz_score')->avg('quiz_score');
        $midtermAverage = $students->whereNotNull('midterm_score')->avg('midterm_score');

        // Calculate overall average (quiz + midterm) / 2
        $overallAverage = null;
        if ($quizAverage && $midtermAverage) {
            $overallAverage = ($quizAverage + $midtermAverage) / 2;
        }

        return view('admin.scores', compact(
            'students',
            'totalStudents',
            'quizAverage',
            'midtermAverage',
            'overallAverage'
        ));
    }

    // Show upload form
    public function showUpload()
    {
        return view('admin.upload');
    }

    // Handle CSV upload
    public function upload(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt'
        ]);

        $file = $request->file('csv_file');
        $path = $file->getRealPath();

        $data = array_map('str_getcsv', file($path));
        $header = array_shift($data); // Remove header row

        $imported = 0;
        foreach ($data as $row) {
            if (count($row) >= 3) {
                Student::updateOrCreate(
                    ['name' => $row[0]],
                    [
                        'quiz_score' => is_numeric($row[1]) ? $row[1] : null,
                        'midterm_score' => is_numeric($row[2]) ? $row[2] : null,
                    ]
                );
                $imported++;
            }
        }

        return redirect()->route('admin.dashboard')
            ->with('success', "Successfully imported {$imported} students from CSV file.");
    }
}
