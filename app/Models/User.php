<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
/**
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $password
 * @property string $google
 * @property string $github
 * @property string $facebook
 * @property string $remember_token
 * @property string $email_verified_at
 * @property int $created_by
 * @property int $deleted_by
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'google',
        'github',
        'facebook',
        'avatar_id',
        'deleted_by',
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
    ];

    protected $appends = [
        'name',
    ];
    protected static function boot()
    {
        parent::boot();

        static::deleting(function($model){
            $model->deleted_by = Auth::id();
        });
    }

    public function getNameAttribute()
    {
        return sprintf("%s %s", $this->first_name, $this->last_name);
    }
    public function avatar()
    {
        return $this->hasMany(File::class, 'avatar_id');
    }
    public function tips()
    {
        return $this->hasMany(Tip::class, 'created_by');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class, 'created_by');
    }
    public function revisions()
    {
        return $this->hasMany(Revision::class, 'created_by');
    }
    public function reactions()
    {
        return $this->hasMany(Reaction::class, 'created_by');
    }
}
