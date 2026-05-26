<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_name',
        'department_id',
        'project_status',
        'allocated_budget',
        'actual_spending',
        'start_date',
        'expected_completion',
        'completion_date',
        'progress_percentage',
    ];

    protected $casts = [
        'allocated_budget' => 'decimal:2',
        'actual_spending' => 'decimal:2',
        'start_date' => 'date',
        'expected_completion' => 'date',
        'completion_date' => 'date',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
