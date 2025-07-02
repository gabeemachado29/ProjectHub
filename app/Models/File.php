<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'original_name',
        'path',
        'mime_type',
        'size',
    ];

    /**
     * Obtém a tarefa à qual o arquivo pertence.
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}