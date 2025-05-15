<?php

namespace App\Http\Controllers;

use App\Models\GameRequest;
use App\Services\GameService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

class GameController extends Controller
{
    public function index(Request $request, GameRequest $game)
    {
        if ($game->played_at !== null and $game->is_winner !== null) {
            // return to_route('games.list');
        }

        $game->played_at = Carbon::now();
        $game->save();
        return Inertia::render('Game', [ 'user' => $game->user, 'game_request_id' => $game->id ]);
    }

    public function store(Request $request)
    {
        GameService::store($request->input('user'));

        return back();
    }

    public function update(Request $request, GameRequest $game)
    {
        $game->is_winner = $request->input('is_winner');
        $game->save();
    }
}
