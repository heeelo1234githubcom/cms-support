<?php

namespace App\Models;

use App\Traits\Status;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Schedule
 * @package App\Models
 *
 * @property int $schedule_id
 * @property string $schedule_name
 * @property string $schedule_email
 * @property string $schedule_phone
 * @property string $schedule_content
 * @property string $schedule_time
 * @property string $status
 */
class Schedule extends Model
{
    use Status;

    /**
     * @inheritdoc
     * @var string
     */
    public $table = 'schedules';

    /**
     * @inheritdoc
     * @var string
     */
    public $primaryKey = 'schedule_id';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'schedule_name',
        'schedule_email',
        'schedule_phone',
        'schedule_content',
        'schedule_time',
        'status',
    ];

    /**
     * @return string
     */
    public function getViewStatusText()
    {
        return ($this->isDisable()) ? 'Chưa xem' : 'Đã xem';
    }

    /**
     * @return string
     */
    public function getOptions()
    {
        return '<a class="view-link" href="' .route('get_schedule', ['id' => $this->schedule_id]). '" data-toggle="tooltip" title="' .trans('common.view_schedule'). '"><i class="fa fa-eye text-info"></i></a>';
    }
}
