<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = ['teacher_assignment_id', 'title', 'quiz_date', 'max_score'];

    protected function casts(): array
    {
        return [
            'quiz_date' => 'date',
        ];
    }

    public function teacherAssignment(): BelongsTo
    {
        return $this->belongsTo(TeacherAssignment::class);
    }

    public function scores(): HasMany
    {
        return $this->hasMany(QuizScore::class);
    }
}
