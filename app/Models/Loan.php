<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Loan extends Model
{
    protected $fillable = [
        'user_id', 'book_id', 'processed_by',
        'loan_date', 'due_date', 'return_date',
        'status', 'fine_amount', 'fine_paid', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'loan_date'   => 'date',
            'due_date'    => 'date',
            'return_date' => 'date',
            'fine_amount' => 'decimal:2',
            'fine_paid'   => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /** Calculate fine: Rp 1.000/day overdue */
    public function calculateFine(): float
    {
        if ($this->status === 'returned' && $this->return_date) {
            $lateDays = max(0, $this->due_date->diffInDays($this->return_date, false) * -1);
            // diffInDays with false returns negative if return > due
            $lateDays = $this->return_date->gt($this->due_date)
                ? $this->due_date->diffInDays($this->return_date)
                : 0;
            return $lateDays * 1000;
        }

        if (in_array($this->status, ['active', 'overdue'])) {
            $today = Carbon::today();
            if ($today->gt($this->due_date)) {
                return $this->due_date->diffInDays($today) * 1000;
            }
        }

        return 0;
    }

    public function isOverdue(): bool
    {
        if ($this->status !== 'returned') {
            return Carbon::today()->gt($this->due_date);
        }
        return false;
    }
}
