<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'last_name',
        'first_name',
        'avatar',
        'cover_image',
        'gender',
        'birth_date',
        'phone',
        'address',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    public function friends(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'friends','user_id','friend_id')->withTimestamps();
    }

    public function friendRequests(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'friends','user_id','friend_id')
            ->wherePivot('status','=', false);
    }
    public function requestFriends(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'friends','friend_id','user_id')
            ->wherePivot('status','=', false);
    }

    public function friendsOfThisUser()
    {
        return $this->belongsToMany(self::class, 'friends', 'user_id', 'friend_id')
            ->wherePivot('status', '=', true)
            ->withPivot('status');
    }

    public function thisUserFriendOf()
    {
        return $this->belongsToMany(self::class, 'friends', 'friend_id', 'user_id')
            ->wherePivot('status', '=', true)
            ->withPivot('status');
    }
    public function allFriends()
    {
        return $this->friendsOfThisUser->merge($this->thisUserFriendOf);
    }
    public function reactions()
    {
        return $this->hasMany(Reaction::class);
    }
}
