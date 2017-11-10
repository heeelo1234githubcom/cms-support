<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Config
 * @package App\Models
 *
 * @property int $config_id
 * @property string $config_name
 * @property string $config_value
 */
class Config extends Model
{
    /**
     * @inheritdoc
     * @var string
     */
    public $table = 'configs';

    /**
     * @inheritdoc
     * @var string
     */
    public $primaryKey = 'config_id';

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
        'config_name',
        'config_value',
    ];
}
