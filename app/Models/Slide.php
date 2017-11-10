<?php

namespace App\Models;

use App\Traits\Status;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Media
 * @package App\Models
 *
 * @property int $slide_id
 * @property string $title
 * @property string $path
 * @property string $status
 */
class Slide extends Model
{
    use Status;

    /**
     * @inheritdoc
     * @var string
     */
    public $table = 'slides';

    /**
     * @inheritdoc
     * @var string
     */
    public $primaryKey = 'slide_id';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'title',
        'path',
        'status',
    ];

    /**
     * @return string
     */
    public function getFile()
    {
        return '<img src="' .$this->path. '" style="width: 100%;" />';
    }

    /**
     * @param string $type
     * @return string
     */
    public function getOptions($type = 'photo')
    {
        $options = [];

        $options[] = '<a class="ajax-update-record" href="' .route('get_slide', ['id' => $this->slide_id]). '" data-toggle="tooltip" title="Cập nhật slide"><i class="fa fa-pencil text-info"></i></a>';

        if ($this->isDisable()) {
            $options[] = '<a data-title="slide" class="enable-record" href="' .route('change_slide_status', ['id' => $this->slide_id]). '" data-toggle="tooltip" title="Kích hoạt slide"><i class="fa fa-eye text-primary"></i></a>';
        } else {
            $options[] = '<a data-title="slide" class="remove-record" href="' .route('change_slide_status', ['id' => $this->slide_id]). '" data-toggle="tooltip" title="Xóa slide"><i class="fa fa-trash text-danger"></i></a>';
        }

        return implode('&nbsp;&nbsp;&nbsp;', $options);
    }
}
