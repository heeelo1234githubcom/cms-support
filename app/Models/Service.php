<?php

namespace App\Models;

use App\Traits\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Class Service
 * @package App\Models
 *
 * @property int $service_id
 * @property int $parent_id
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property string $show_at_home
 * @property string $status
 * @property Collection|ServiceMeta[] $meta
 * @property Collection|ServiceComment[] $comments
 * @property static $parent
 * @property Collection|static[] $subs
 */
class Service extends Model
{
    use Status;

    /**
     * @inheritdoc
     * @var string
     */
    public $table = 'services';

    /**
     * @inheritdoc
     * @var string
     */
    public $primaryKey = 'service_id';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'title',
        'slug',
        'content',
        'show_at_home',
        'status',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function meta()
    {
        return $this->hasMany('App\Models\ServiceMeta', 'service_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany('App\Models\ServiceComment', 'service_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo('App\Models\Service', 'parent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subs()
    {
        return $this->hasMany('App\Models\Service', 'parent_id');
    }

    /**
     * @param array $data
     */
    public function updateMeta($data = [])
    {
        if ($data) {
            foreach ($data as $key => $value) {

                /**
                 * update service meta
                 */
                $this->meta()->updateOrCreate([
                    'service_id' => $this->service_id,
                    'meta_name' => $key
                ], [
                    'meta_value' => $value
                ]);
            }
        }
    }

    /**
     * @param string $metaName
     * @param bool $text
     * @return mixed
     */
    public function getMetaData($metaName = '', $text = true)
    {
        $meta = $this->meta->pluck('meta_value', 'meta_name');

        $default = $text ? '<span class="empty">' .trans('common.null'). '</span>' : null;

        return $meta->get($metaName, $default);
    }

    /**
     * @return string
     */
    public function getCover()
    {
        $cover = $this->getMetaData('cover', false);

        return $cover ? '<img src="' .$cover. '" class="cover-table-preview" />' : '<span class="empty">' .trans('common.null'). '</span>';
    }

    /**
     * @return string
     */
    public function getOptions()
    {
        $options = [];

        /* edit link */
        $options[] = '<a class="option-link" href="/manage/service/edit/' .$this->service_id. '" data-toggle="tooltip" title="' .trans('common.edit_service'). '"><i class="fa fa-pencil"></i></a>';

        /* comment link */
        //$options[] = '<a class="option-link" href="/manage/service/comment/' .$this->service_id. '" data-toggle="tooltip" title="' .trans('common.service_comments'). '"><i class="fa fa-comments text-warning"></i></a>';

        /* delete link */
        $options[] = '<a data-title="dịch vụ" class="remove-record" href="' .route('remove_service', ['id' => $this->service_id]). '" data-toggle="tooltip" title="' .trans('common.remove_service'). '"><i class="fa fa-trash text-danger"></i></a>';

        return implode('&nbsp;&nbsp;&nbsp;', $options);
    }

    /**
     * @return mixed
     */
    public function getPublicCover()
    {
        $cover = $this->getMetaData('cover', false);

        if ( !$cover) {
            $cover = '/assets/frontend/images/no-image.jpg';
        }

        return $cover;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return str_limit(strip_tags($this->content), 200);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getSubServices()
    {
        $services = $this->subs;

        return view('frontend.service.list_subs', [
            'subs' => $services
        ]);
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return route('service_detail', ['slug' => $this->slug]);
    }
}
