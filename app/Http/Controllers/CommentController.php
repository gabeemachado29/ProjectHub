<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreCommentRequest; // Importado

class CommentController extends Controller
{
    // Usa o StoreCommentRequest e Route Model Binding
    public function store(StoreCommentRequest $request, Task $task)
    {
        // Validação e autorização são automáticas
        $task->comments()->create([
            'content' => $request->validated('content'),
            'user_id' => Auth::id()
        ]);

        return redirect()->back()->with('success', 'Comentário adicionado!');
    }
}