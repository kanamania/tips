<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Tip extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'usage',
        'created_by',
        'deleted_by',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function($model){
            $model->created_by = Auth::id();
        });
        static::deleting(function($model){
            $model->deleted_by = Auth::id();
        });
    }

    public function revisions()
    {
        return $this->hasMany(Revision::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function remover()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

}
