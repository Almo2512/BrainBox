<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Note;
use App\Models\Task;
use App\Models\User;


class TodoList extends Model
{
    protected $fillable = ['title'];
    use HasFactory;
    public function tasks()
{
    return $this->hasMany(Task::class);
}
public function notes()
{
    return $this->hasOne(Note::class);
}
public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
