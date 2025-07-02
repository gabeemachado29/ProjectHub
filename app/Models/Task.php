<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'status',
        'project_id',
        'assigned_to',
        'due_date'
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    // ... (relacionamentos existentes) ...
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Obtém todos os arquivos associados à tarefa.
     */
    public function files()
    {
        return $this->hasMany(File::class);
    }
}