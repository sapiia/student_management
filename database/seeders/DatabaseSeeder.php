<?php

namespace Database\Seeders;

use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Models\MidtermScore;
use App\Models\Quiz;
use App\Models\QuizScore;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use App\Models\TeacherAssignment;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Julianne Davis', 'role' => 'admin', 'password' => Hash::make('password')]
        );

        User::updateOrCreate(
            ['email' => 's.rich@staff.edu'],
            ['name' => 'Sarah Richardson', 'role' => 'admin', 'password' => Hash::make('password'), 'updated_at' => now()->subDays(42)]
        );

        $teacher = User::updateOrCreate(
            ['email' => 'teacher@example.com'],
            ['name' => 'Prof. Henderson', 'role' => 'teacher', 'password' => Hash::make('password')]
        );

        $teachers = collect([
            $teacher,
            User::updateOrCreate(['email' => 'm.kinsley@faculty.com'], ['name' => 'Marcus Kinsley', 'role' => 'teacher', 'password' => Hash::make('password')]),
            User::updateOrCreate(['email' => 'science@example.com'], ['name' => 'Noah Patel', 'role' => 'teacher', 'password' => Hash::make('password')]),
            User::updateOrCreate(['email' => 'maya.chen@faculty.com'], ['name' => 'Maya Chen', 'role' => 'teacher', 'password' => Hash::make('password')]),
            User::updateOrCreate(['email' => 'elena.roberts@faculty.com'], ['name' => 'Elena Roberts', 'role' => 'teacher', 'password' => Hash::make('password')]),
        ]);

        $classes = collect([
            SchoolClass::updateOrCreate(['name' => '10th Grade Mathematics (A)'], ['grade_level' => '10']),
            SchoolClass::updateOrCreate(['name' => 'Calculus II (Section A)'], ['grade_level' => '12']),
            SchoolClass::updateOrCreate(['name' => 'Geometry (Section B)'], ['grade_level' => '10']),
            SchoolClass::updateOrCreate(['name' => 'Algebra 101 (Section C)'], ['grade_level' => '9']),
            SchoolClass::updateOrCreate(['name' => 'Integrated Science (A)'], ['grade_level' => '10']),
            SchoolClass::updateOrCreate(['name' => 'World Literature (B)'], ['grade_level' => '11']),
        ]);

        $subjects = collect([
            'math' => Subject::updateOrCreate(['code' => 'MATH10'], ['name' => 'Mathematics']),
            'calculus' => Subject::updateOrCreate(['code' => 'CALC12'], ['name' => 'Calculus']),
            'geometry' => Subject::updateOrCreate(['code' => 'GEO10'], ['name' => 'Geometry']),
            'algebra' => Subject::updateOrCreate(['code' => 'ALG09'], ['name' => 'Algebra']),
            'science' => Subject::updateOrCreate(['code' => 'SCI10'], ['name' => 'Science']),
            'literature' => Subject::updateOrCreate(['code' => 'LIT11'], ['name' => 'Literature']),
        ]);

        $primaryStudents = $this->seedStudents($classes[0], [
            ['Liam Aristhone', 'liam.a@edu.pulse', 'STU-2983', 98, 97],
            ['Sofia Martinez', 's.martinez@edu.pulse', 'STU-3122', 82, 84],
            ['Jameson Wu', 'j.wu@edu.pulse', 'STU-3005', 64, 66],
            ['Ari Walker', 'student@example.com', 'STU-1001', 88, 90],
            ['Bella Kim', 'bella@example.com', 'STU-1002', 91, 92],
            ['Diego Santos', 'diego@example.com', 'STU-1003', 79, 81],
            ['Fatima Ali', 'fatima@example.com', 'STU-1004', 93, 91],
            ['Noah Brooks', 'noah.brooks@edu.pulse', 'STU-3011', 87, 86],
            ['Emma Clarke', 'emma.clarke@edu.pulse', 'STU-3012', 95, 94],
            ['Oliver Reed', 'oliver.reed@edu.pulse', 'STU-3013', 72, 75],
            ['Mia Johnson', 'mia.johnson@edu.pulse', 'STU-3014', 84, 82],
            ['Ethan Park', 'ethan.park@edu.pulse', 'STU-3015', 77, 80],
            ['Amara Singh', 'amara.singh@edu.pulse', 'STU-3016', 89, 88],
            ['Lucas Chen', 'lucas.chen@edu.pulse', 'STU-3017', 92, 90],
            ['Nora Evans', 'nora.evans@edu.pulse', 'STU-3018', 69, 72],
            ['Mateo Rivera', 'mateo.rivera@edu.pulse', 'STU-3019', 86, 85],
            ['Chloe Bennett', 'chloe.bennett@edu.pulse', 'STU-3020', 90, 91],
            ['Henry Carter', 'henry.carter@edu.pulse', 'STU-3021', 81, 79],
            ['Ava Thompson', 'ava.thompson@edu.pulse', 'STU-3022', 94, 95],
            ['Leo Morgan', 'leo.morgan@edu.pulse', 'STU-3023', 73, 76],
            ['Grace Nguyen', 'grace.nguyen@edu.pulse', 'STU-3024', 88, 87],
            ['Daniel Foster', 'daniel.foster@edu.pulse', 'STU-3025', 78, 80],
            ['Hana Ito', 'hana.ito@edu.pulse', 'STU-3026', 96, 94],
            ['Isaac Bell', 'isaac.bell@edu.pulse', 'STU-3027', 83, 84],
            ['Zoe Wallace', 'zoe.wallace@edu.pulse', 'STU-3028', 91, 90],
            ['Mason Price', 'mason.price@edu.pulse', 'STU-3029', 67, 70],
            ['Laila Hassan', 'laila.hassan@edu.pulse', 'STU-3030', 85, 86],
            ['Owen Russell', 'owen.russell@edu.pulse', 'STU-3031', 76, 77],
            ['Priya Shah', 'priya.shah@edu.pulse', 'STU-3032', 93, 92],
            ['Samir Khan', 'samir.khan@edu.pulse', 'STU-3033', 80, 82],
            ['Ella Cooper', 'ella.cooper@edu.pulse', 'STU-3034', 89, 90],
            ['Ryan Murphy', 'ryan.murphy@edu.pulse', 'STU-3035', 74, 73],
            ['Tara Wilson', 'tara.wilson@edu.pulse', 'STU-3036', 92, 93],
            ['Kai Anderson', 'kai.anderson@edu.pulse', 'STU-3037', 86, 84],
        ]);

        $assignments = collect([
            TeacherAssignment::updateOrCreate(['teacher_id' => $teachers[0]->id, 'school_class_id' => $classes[0]->id, 'subject_id' => $subjects['math']->id]),
            TeacherAssignment::updateOrCreate(['teacher_id' => $teachers[0]->id, 'school_class_id' => $classes[1]->id, 'subject_id' => $subjects['calculus']->id]),
            TeacherAssignment::updateOrCreate(['teacher_id' => $teachers[0]->id, 'school_class_id' => $classes[2]->id, 'subject_id' => $subjects['geometry']->id]),
            TeacherAssignment::updateOrCreate(['teacher_id' => $teachers[0]->id, 'school_class_id' => $classes[3]->id, 'subject_id' => $subjects['algebra']->id]),
            TeacherAssignment::updateOrCreate(['teacher_id' => $teachers[1]->id, 'school_class_id' => $classes[4]->id, 'subject_id' => $subjects['science']->id]),
            TeacherAssignment::updateOrCreate(['teacher_id' => $teachers[2]->id, 'school_class_id' => $classes[5]->id, 'subject_id' => $subjects['literature']->id]),
        ]);

        $this->seedAcademicRecords($assignments[0], $primaryStudents, [98, 82, 64, 88, 91, 79, 93], [100, 85, 72, 94, 96, 86, 93]);

        $supportingClasses = [
            [$classes[1], $assignments[1], 'CAL', 88],
            [$classes[2], $assignments[2], 'GEO', 74],
            [$classes[3], $assignments[3], 'ALG', 62],
            [$classes[4], $assignments[4], 'SCI', 84],
            [$classes[5], $assignments[5], 'LIT', 79],
        ];

        foreach ($supportingClasses as [$schoolClass, $assignment, $prefix, $targetAverage]) {
            $students = $this->seedStudents($schoolClass, collect(range(1, 8))->map(function (int $index) use ($schoolClass, $prefix, $targetAverage) {
                $name = $schoolClass->grade_level.' '.$prefix.' Student '.$index;

                return [
                    $name,
                    strtolower(str_replace(' ', '.', $name)).'@edu.pulse',
                    $prefix.'-'.str_pad((string) $index, 4, '0', STR_PAD_LEFT),
                    max(45, min(99, $targetAverage + (($index % 4) - 2) * 3)),
                    max(45, min(99, $targetAverage + (($index % 5) - 2) * 2)),
                ];
            })->all());

            $this->seedAcademicRecords($assignment, $students, [$targetAverage], [94, 90, 86, 92, 88]);
        }
    }

    private function seedStudents(SchoolClass $schoolClass, array $rows)
    {
        return collect($rows)->map(function (array $row) use ($schoolClass) {
            [$name, $email, $number, $quizScore, $midtermScore] = $row;

            $user = User::updateOrCreate(
                ['email' => $email],
                ['name' => $name, 'role' => 'student', 'password' => Hash::make('password')]
            );

            return Student::updateOrCreate(
                ['student_number' => $number],
                [
                    'user_id' => $user->id,
                    'school_class_id' => $schoolClass->id,
                    'name' => $name,
                    'guardian_name' => 'Main Academy Guardian',
                    'quiz_score' => $quizScore,
                    'midterm_score' => $midtermScore,
                ]
            );
        });
    }

    private function seedAcademicRecords(TeacherAssignment $assignment, $students, array $baseScores, array $attendanceTargets): void
    {
        $sessionDates = collect(range(0, 7))->map(fn (int $index) => now()->subDays(28 - ($index * 4))->toDateString());

        foreach ($sessionDates as $sessionIndex => $date) {
            $session = AttendanceSession::updateOrCreate(
                ['teacher_assignment_id' => $assignment->id, 'session_date' => $date],
                ['topic' => 'Unit '.($sessionIndex + 1)]
            );

            foreach ($students as $studentIndex => $student) {
                $attendanceTarget = $attendanceTargets[$studentIndex % count($attendanceTargets)];
                $status = match (true) {
                    $attendanceTarget >= 98 => 'present',
                    $attendanceTarget < 76 && $sessionIndex % 3 === 0 => 'absent',
                    $attendanceTarget < 88 && $sessionIndex % 4 === 0 => 'late',
                    default => 'present',
                };

                AttendanceRecord::updateOrCreate(
                    ['attendance_session_id' => $session->id, 'student_id' => $student->id],
                    ['status' => $status]
                );
            }
        }

        foreach (['Quiz 1', 'Quiz 2', 'Quiz 3', 'Quiz 4'] as $quizIndex => $title) {
            $quiz = Quiz::updateOrCreate(
                ['teacher_assignment_id' => $assignment->id, 'title' => $title],
                ['quiz_date' => now()->subDays(24 - ($quizIndex * 6))->toDateString(), 'max_score' => 100]
            );

            foreach ($students as $studentIndex => $student) {
                $base = $baseScores[$studentIndex % count($baseScores)];
                $score = max(42, min(100, $base + ($quizIndex - 1) * 2 + (($studentIndex % 3) - 1)));

                QuizScore::updateOrCreate(
                    ['quiz_id' => $quiz->id, 'student_id' => $student->id],
                    ['score' => $score]
                );
            }
        }

        foreach ($students as $studentIndex => $student) {
            $base = $baseScores[$studentIndex % count($baseScores)];

            MidtermScore::updateOrCreate(
                ['teacher_assignment_id' => $assignment->id, 'student_id' => $student->id],
                ['score' => max(45, min(100, $base + (($studentIndex % 5) - 2)))]
            );
        }
    }
}
