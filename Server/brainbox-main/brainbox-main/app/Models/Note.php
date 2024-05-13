<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TodoList;
use App\Models\User;
use App\Models\Group;

class Note extends Model
{
    use HasFactory;
    protected $fillable = ["description"];
    public function users()
    {
        return $this->belongsTo(User::class);
    }
    public function groups(){
        return $this->belongsTo(Group::class);
    }
    public function todoLists(){
        return $this->belongsTo(ToDoList::class);
    }
}
