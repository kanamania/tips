<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * @property int $id
 * @property int $record_id
 * @property string $record_type
 * @property string $reaction
 * @property int $created_by
 * @property string $created_at
 * @property string $updated_at
 */

class Reaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'record_type',
        'record_id',
        'reaction', //up,neutral,down
        'created_by',
    ];
    protected static function boot()
    {
        parent::boot();

        static::creating(function($model){
            $model->created_by = Auth::id();
        });
    }

    public function logs()
    {
        return $this->hasMany(ReactionLog::class, 'reaction_id');
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
