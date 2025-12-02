<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollMonthly extends Model
{
    protected $fillable = [
        'employee_id',
        'year',
        'month',
        'salary',
        'bonus',
        'penalty',
        'advance',
        'official_salary',
        'vacation',
        'to_cash',
        'paid',
        'remaining',
    ];

    protected $casts = [
        'salary' => 'decimal:2',
        'bonus' => 'decimal:2',
        'penalty' => 'decimal:2',
        'advance' => 'decimal:2',
        'official_salary' => 'decimal:2',
        'vacation' => 'decimal:2',
        'to_cash' => 'decimal:2',
        'paid' => 'decimal:2',
        'remaining' => 'decimal:2',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function calculateToCash(): float
    {
        return floatval($this->salary) + floatval($this->bonus) - floatval($this->penalty) - floatval($this->advance) - floatval($this->official_salary) - floatval($this->vacation);
    }
}
