<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\TeacherAssignment;
use App\Support\PerformanceMetrics;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StudentPortalController extends Controller
{
    public function dashboard(): View
    {
        $student = Auth::user()->student;
        $assignments = collect();
        $subjects = collect();
        $chart = null;

        if ($student?->school_class_id) {
            $assignments = TeacherAssignment::with(['subject', 'teacher', 'schoolClass'])
                ->where('school_class_id', $student->school_class_id)
                ->get();

            $subjects = $assignments->map(function (TeacherAssignment $assignment) use ($student) {
                $quizzes = Quiz::where('teacher_assignment_id', $assignment->id)
                    ->with(['scores' => fn ($query) => $query->where('student_id', $student->id)])
                    ->orderBy('quiz_date')
                    ->get();

                $quizList = [];
                $prevScore = null;

                foreach ($quizzes as $quiz) {
                    $scoreObj = $quiz->scores->first();
                    $currentScore = $scoreObj ? round(($scoreObj->score / max(1, $quiz->max_score)) * 100, 1) : null;
                    $progress = null;

                    if ($currentScore !== null && $prevScore !== null) {
                        $progress = round($currentScore - $prevScore, 1);
                    }

                    if ($currentScore !== null) {
                        $prevScore = $currentScore;
                    }

                    $quizList[] = [
                        'title' => $quiz->title,
                        'date' => $quiz->quiz_date,
                        'score' => $currentScore,
                        'progress' => $progress,
                    ];
                }

                return [
                    'assignment' => $assignment,
                    'attendance' => PerformanceMetrics::attendancePercent($student, $assignment),
                    'quizAverage' => PerformanceMetrics::quizAverage($student, $assignment),
                    'midterm' => PerformanceMetrics::midtermScore($student, $assignment),
                    'quizzes' => $quizList,
                ];
            });

            if ($assignments->isNotEmpty()) {
                $chart = PerformanceMetrics::studentChart($student, $assignments->first());
            }
        }

        return view('student.dashboard', compact('student', 'assignments', 'subjects', 'chart'));
    }
}
