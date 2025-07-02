<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function store(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $request->validate([
            'file' => 'required|file|max:10240', // Max 10MB
        ]);

        $uploadedFile = $request->file('file');
        $path = $uploadedFile->store('task_files', 'private');

        $task->files()->create([
            'original_name' => $uploadedFile->getClientOriginalName(),
            'path' => $path,
            'mime_type' => $uploadedFile->getMimeType(),
            'size' => $uploadedFile->getSize(),
        ]);

        return redirect()->route('tasks.show', $task)->with('success', 'Arquivo enviado com sucesso!');
    }

    public function download(File $file)
    {
        $this->authorize('update', $file->task);

        return Storage::disk('private')->download($file->path, $file->original_name);
    }

    public function destroy(File $file)
    {
        $this->authorize('update', $file->task);

        Storage::disk('private')->delete($file->path);
        $file->delete();

        return redirect()->route('tasks.show', $file->task)->with('success', 'Arquivo removido com sucesso!');
    }
}