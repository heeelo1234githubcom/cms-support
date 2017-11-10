<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PromotionUser
 * @package App\Models
 *
 * @property int $id
 * @property int $promotion_id
 * @property string $email
 * @property string $phone
 * @property string $name
 * @property string $content
 * @property string $created_at
 * @property string $updated_at
 */
class PromotionUser extends Model
{
    /**
     * @inheritdoc
     * @var string
     */
    public $table = 'newsletters';

    /**
     * @inheritdoc
     * @var string
     */
    public $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'promotion_id',
        'email',
        'phone',
        'name',
        'content',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function promotion()
    {
        return $this->belongsTo('App\Models\Promotion', 'promotion_id');
    }

    /**
     * @return string
     */
    public function getDate()
    {
        return (new Carbon($this->created_at))->format('d/m/Y');
    }
}
