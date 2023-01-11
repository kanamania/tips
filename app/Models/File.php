<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

/**
 * @property int $id
 * @property string $name
 * @property string $hash
 * @property string $mime
 * @property string $ext
 * @property string $size
 * @property string $hash_name
 * @property string $thumb_url
 * @property string $delete_url
 * @property int $created_by
 * @property int $deleted_by
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */

class File extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'hash',
        'mime',
        'ext',
        'size',
        'created_by',
        'deleted_by',
    ];

    protected $appends = [
        'hash_name',
        'thumb_url',
        'delete_url',
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

    public function getHashNameAttribute()
    {
        return $this->hash.'.'.$this->ext;
    }

    public function getDeleteUrlAttribute()
    {
        return action('FileController@deleteFile', $this->id);
    }
    public function getThumbUrlAttribute()
    {
        return action('FileController@getThumbnail', $this->id);
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
