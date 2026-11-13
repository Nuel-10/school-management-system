<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentResultApprovalHistory extends Model
{
    protected $fillable = [
        'student_result_id',
        'user_id',
        'action',
        'previous_status',
        'new_status',
        'remarks',
    ];

    public function studentResult(): BelongsTo
    {
        return $this->belongsTo(StudentResult::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
