<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Procurement extends Model
{
    use HasFactory;

    protected $fillable = [
        'procurement_code',
        'title',
        'department_id',
        'budget_allocated',
        'actual_cost',
        'vendor_id',
        'status',
        'initiated_by',
        'approved_by',
        'start_date',
        'completion_date',
    ];

    protected $casts = [
        'budget_allocated' => 'decimal:2',
        'actual_cost' => 'decimal:2',
        'start_date' => 'date',
        'completion_date' => 'date',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function initiator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'initiated_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
