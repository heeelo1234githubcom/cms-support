<?php

namespace App\Models;

use App\Traits\Status;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ServiceComment
 * @package App\Models
 *
 * @property int $comment_id
 * @property int $service_id
 * @property string $comment_email
 * @property string $comment_name
 * @property string $comment_phone
 * @property string $comment_content
 * @property string $status
 * @property Service $service
 */
class ServiceComment extends Model
{
    use Status;

    /**
     * @inheritdoc
     * @var string
     */
    public $table = 'service_comments';

    /**
     * @inheritdoc
     * @var string
     */
    public $primaryKey = 'comment_id';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'service_id',
        'comment_email',
        'comment_name',
        'comment_phone',
        'comment_content',
        'status',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function service()
    {
        return $this->belongsTo('App\Models\Service', 'service_id');
    }

    /**
     * @return string
     */
    public function getOptions()
    {
        if ($this->isDisable()) {
            return '<a data-title="comment" class="enable-record" href="' .route('change_comment_status', ['id' => $this->comment_id]). '" data-toggle="tooltip" title="' .trans('common.enable_comment'). '"><i class="fa fa-eye text-info"></i></a>';
        } else {
            return '<a data-title="comment" class="remove-record" href="' .route('change_comment_status', ['id' => $this->comment_id]). '" data-toggle="tooltip" title="' .trans('common.remove_comment'). '"><i class="fa fa-trash text-danger"></i></a>';
        }
    }
}
