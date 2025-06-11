<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Task $task)
    {
        $request->validate([
            'content' => 'required|string'
        ]);

        $task->comments()->create([
            'content' => $request->content,
            'user_id' => Auth::id()
        ]);

        return redirect()->back()->with('success', 'Comentário adicionado!');
    }
}
