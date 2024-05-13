<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TodoList;

class Task extends Model
{
    use HasFactory;
    protected $fillable = ['title'];

    public function todoList()
{
    return $this->belongsTo(TodoList::class);
}
    
}
