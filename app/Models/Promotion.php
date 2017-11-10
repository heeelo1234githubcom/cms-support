<?php

namespace App\Models;

use App\Traits\Status;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Promotion
 * @package App\Models
 *
 * @property int $promotion_id
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property string $start_date
 * @property string $end_date
 * @property string $status
 */
class Promotion extends Model
{
    use Status;

    /**
     * @inheritdoc
     * @var string
     */
    public $table = 'promotions';

    /**
     * @inheritdoc
     * @var string
     */
    public $primaryKey = 'promotion_id';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
        'start_date',
        'end_date',
        'status',
    ];

    /**
     * @return string
     */
    public function getOptions()
    {
        $options = [];

        $options[] = '<a class="ajax-update-record" href="' .route('get_promotion', ['id' => $this->promotion_id]). '" data-toggle="tooltip" title="Cập nhật khuyến mãi"><i class="fa fa-pencil text-info"></i></a>';

        if ($this->isDisable()) {
            $options[] = '<a data-title="khuyến mãi" class="enable-record" href="' .route('change_promotion_status', ['id' => $this->promotion_id]). '" data-toggle="tooltip" title="Kích hoạt khuyến mãi"><i class="fa fa-eye text-primary"></i></a>';
        } else {
            $options[] = '<a data-title="khuyến mãi" class="remove-record" href="' .route('change_promotion_status', ['id' => $this->promotion_id]). '" data-toggle="tooltip" title="Xóa khuyến mãi"><i class="fa fa-trash text-danger"></i></a>';
        }

        return implode('&nbsp;&nbsp;&nbsp;', $options);
    }

    /**
     * @param string $date
     * @param string $format
     * @return string
     */
    public function getDate($date = '', $format = 'd/m/Y')
    {
        return (new Carbon($date))->format($format);
    }

    /**
     * @return string
     */
    public function getStartDate()
    {
        $startDate = $this->getDate($this->start_date);

        return ($startDate == '01/01/1970') ? '' : $startDate;
    }

    /**
     * @return string
     */
    public function getEndDate()
    {
        $endDate = $this->getDate($this->end_date);

        return ($endDate == '31/12/2099') ? '' : $endDate;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return str_limit($this->content, 350);
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return route('promotion_detail', ['slug' => $this->slug]);
    }
}
