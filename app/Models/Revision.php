<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

/**
 * @property int $id
 * @property int $tip_id
 * @property string $content
 * @property int $created_by
 * @property int $deleted_by
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */

class Revision extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tip_id',
        'content',
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

    public function tip()
    {
        return $this->belongsTo(Tip::class, 'tip_id');
    }

    public function reactions()
    {
        return $this->hasMany(Reaction::class, 'record_id')
            ->where('record_type', Revision::class);

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
