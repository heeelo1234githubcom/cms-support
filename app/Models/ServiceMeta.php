<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ServiceMeta
 * @package App\Models
 *
 * @property int $meta_id
 * @property int $service_id
 * @property string $meta_name
 * @property string $meta_value
 */
class ServiceMeta extends Model
{
    /**
     * @inheritdoc
     * @var string
     */
    public $table = 'service_meta';

    /**
     * @inheritdoc
     * @var string
     */
    public $primaryKey = 'meta_id';

    /**
     * @inheritdoc
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'service_id',
        'meta_name',
        'meta_value',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function service()
    {
        return $this->belongsTo('App\Models\Service', 'service_id');
    }
}
