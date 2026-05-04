<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    protected $fillable = [
        'developer_id',
        'title',
        'slug',
        'description',
        'price',
        'cover_image',
        'status',
        'game_file',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }

    public function developer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'developer_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function libraries(): HasMany
    {
        return $this->hasMany(Library::class);
    }

    public function downloads(): HasMany
    {
        return $this->hasMany(GameDownload::class);
    }
}
