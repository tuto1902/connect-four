<?php

namespace App\Http\Controllers;

use App\Models\GameRequest;
use App\Services\GameService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GameController extends Controller
{
    public function index()
    {
        return Inertia::render('Game');
    }

    public function store(Request $request)
    {
        GameService::store($request->input('user'));

        dd($request->query('from'));

        if($request->query('from') == 'list') {
            return to_route('games.list');
        }

        return to_route('home');
    }
}
