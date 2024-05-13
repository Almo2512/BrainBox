<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\TodoList;
use App\Models\Group;
use App\Models\Note;
use App\Models\Admin;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function notes()
    {
        return $this->hasMany(Note::class);
    }
    

    public function groups()
    {
        return $this->belongsToMany(Group::class)->withTimestamps();
        }
    
    public function todoLists()
    {
        return $this->hasMany(ToDoList::class);
    }
    public function admins() 
    {
        return $this->belongsToMany(Admin::class)->withPivot('blocked');
    }
    public function hasRole($role)
    {
        return $this->role === $role;
    }
}
