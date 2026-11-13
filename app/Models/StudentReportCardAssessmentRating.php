<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentReportCardAssessmentRating extends Model
{
    protected $fillable = [
        'student_report_card_id',
        'report_card_assessment_item_id',
        'rating',
        'remark',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'decimal:2',
        ];
    }

    public function reportCard(): BelongsTo
    {
        return $this->belongsTo(StudentReportCard::class, 'student_report_card_id');
    }

    public function assessmentItem(): BelongsTo
    {
        return $this->belongsTo(ReportCardAssessmentItem::class, 'report_card_assessment_item_id');
    }
}