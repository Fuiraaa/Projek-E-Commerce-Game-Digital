<?php

namespace App\Policies;

use App\Models\Game;
use App\Models\User;

class GamePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Game $game): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isDeveloper() && $user->is_verified;
    }

    public function update(User $user, Game $game): bool
    {
        return $user->isDeveloper() && $game->developer_id === $user->id;
    }

    public function delete(User $user, Game $game): bool
    {
        return $user->isDeveloper() && $game->developer_id === $user->id;
    }
}
