<?php

namespace App\Models;

use App\Traits\Status;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Page
 * @package App\Models
 *
 * @property int $page_id
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property string $status
 */
class Page extends Model
{
    use Status;

    /**
     * @inheritdoc
     * @var string
     */
    public $table = 'pages';

    /**
     * @inheritdoc
     * @var string
     */
    public $primaryKey = 'page_id';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
        'status',
    ];

    /**
     * @return string
     */
    public function getOptions()
    {
        $options = [];

        $options[] = '<a data-title="trang" class="option-link" href="/manage/page/edit/' .$this->page_id. '" data-toggle="tooltip" title="' .trans('common.update_page'). '"><i class="fa fa-pencil text-info"></i></a>';

        if ($this->isDisable()) {
            $options[] = '<a data-title="trang" class="enable-record" href="' .route('change_page_status', ['id' => $this->page_id]). '" data-toggle="tooltip" title="' .trans('common.enable_page'). '"><i class="fa fa-eye text-info"></i></a>';
        } else {
            $options[] = '<a data-title="trang" class="remove-record" href="' .route('change_page_status', ['id' => $this->page_id]). '" data-toggle="tooltip" title="' .trans('common.remove_page'). '"><i class="fa fa-trash text-danger"></i></a>';
        }

        return implode('&nbsp;&nbsp;&nbsp;', $options);
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return route('page_detail', ['slug' => $this->slug]);
    }
}
