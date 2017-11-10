<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserMeta
 * @package App\Models
 *
 * @property integer $meta_id
 * @property integer $user_id
 * @property string $meta_key
 * @property string $meta_value
 */
class UserMeta extends Model
{
    /**
     * @inheritdoc
     * @var string
     */
    public $table = 'user_meta';

    /**
     * @inheritdoc
     * @var string
     */
    public $primaryKey = 'meta_id';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'meta_id',
        'meta_key',
        'meta_name',
        'meta_value',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
