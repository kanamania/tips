<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * @property int $id
 * @property int $reaction_id
 * @property string $reaction
 * @property string $created_at
 * @property string $updated_at
 */

class ReactionLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'reaction_id',
        'reaction', //up,neutral,down
    ];

    public function reaction()
    {
        return $this->belongsTo(Reaction::class, 'reaction_id');
    }
}
