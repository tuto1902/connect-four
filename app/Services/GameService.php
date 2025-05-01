<?php

namespace App\Services;

use App\Models\GameRequest;

class GameService
{
    public static function store($user)
    {
        GameRequest::create([
            'user' => $user
        ]);
    }
}
