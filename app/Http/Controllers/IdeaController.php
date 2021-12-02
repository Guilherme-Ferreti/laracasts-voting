<?php

namespace App\Http\Controllers;

use App\Models\Idea;

class IdeaController extends Controller
{
    public function index()
    {
        return view('idea.index');
    }

    public function show(Idea $idea)
    {
        return view('idea.show', [
            'idea' => $idea,
            'backUrl' => url()->previous() !== url()->full() ? url()->previous() : route('idea.index'),
        ]);
    }
}
