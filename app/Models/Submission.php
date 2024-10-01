<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'assessment_no',
        'textarea1',
        'textarea2',
        'textarea3',
        'exercise_type',
        'grade',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

