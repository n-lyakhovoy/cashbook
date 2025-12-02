<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    protected $fillable = [
        'last_name',
        'first_name',
        'department',
        'position',
        'salary',
        'priority',
    ];

    public function payouts(): HasMany
    {
        return $this->hasMany(Payout::class);
    }

    public function payrollMonthly(): HasMany
    {
        return $this->hasMany(PayrollMonthly::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->last_name} {$this->first_name}";
    }
}
