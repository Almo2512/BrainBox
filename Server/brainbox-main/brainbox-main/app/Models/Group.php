<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TodoList;
use App\Models\User;
use App\Models\Note;

class Group extends Model
{
  protected $fillable = ['title'];
    use HasFactory;
    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('is_admin')->withTimestamps();
    }
  
  public function todoLists(){
    return $this->hasMany(ToDOList::class);
  }
  public function notes()
    {
        return $this->hasMany(Note::class);
    }
  public function updateMemberCount()
    {
        $this->update(['member_count' => $this->users()->count()]);
    }

}
