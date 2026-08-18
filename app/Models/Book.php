<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    protected $fillable = [
        'title', 'author', 'isbn', 'category_id', 'publisher',
        'publish_year', 'total_copies', 'available_copies',
        'shelf_location', 'description', 'cover_image', 'language', 'pages',
    ];

    protected function casts(): array
    {
        return [
            'publish_year' => 'integer',
            'total_copies' => 'integer',
            'available_copies' => 'integer',
            'pages' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    public function activeLoans(): HasMany
    {
        return $this->hasMany(Loan::class)->whereIn('status', ['active', 'overdue']);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function isAvailable(): bool
    {
        return $this->available_copies > 0;
    }
}
