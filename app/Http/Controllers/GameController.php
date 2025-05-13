<?php

namespace App\Http\Controllers;

use App\Models\GameRequest;
use App\Services\GameService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GameController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->query('user');
        return Inertia::render('Game', [ 'user' => $user ]);
    }

    public function store(Request $request)
    {
        GameService::store($request->input('user'));

        return back();
    }
}
