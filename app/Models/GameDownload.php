<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameDownload extends Model
{
    protected $fillable = [
        'user_id',
        'game_id',
        'downloaded',
    ];

    protected function casts(): array
    {
        return [
            'downloaded' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }
}
