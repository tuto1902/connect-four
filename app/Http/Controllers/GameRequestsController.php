<?php

namespace App\Http\Controllers;

use App\Models\GameRequest;
use App\Services\GameService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GameRequestsController extends Controller
{
    public function index()
    {
        $gameRequests = GameRequest::orderBy('is_winner')->orderBy('created_at')->get();

        return Inertia::render('GameRequests', [
            'gameRequests' => $gameRequests
        ]);
    }

    public function store(Request $request)
    {
        GameService::store($request->input('user'));

        return to_route('games.list');
    }
}
