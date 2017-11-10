<?php

namespace App\Models;

use App\Traits\Status;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Album
 * @package App\Models
 *
 * @property int $album_id
 * @property string $title
 * @property string $slug
 * @property string $description
 * @property string $type
 * @property string $status
 */
class Album extends Model
{
    use Status;

    /**
     * @inheritdoc
     * @var string
     */
    public $table = 'albums';

    /**
     * @inheritdoc
     * @var string
     */
    public $primaryKey = 'album_id';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'type',
        'status',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function media()
    {
        return $this->hasMany('App\Models\Media', 'album_id');
    }

    /**
     * @return string
     */
    public function getType()
    {
        return ($this->type == 'photo') ? 'Ảnh' : 'Video';
    }

    /**
     * @return string
     */
    public function getOptions()
    {
        $options = [];

        $options[] = '<a class="ajax-update-record" href="' .route('get_album', ['id' => $this->album_id]). '" data-toggle="tooltip" title="Cập nhật album"><i class="fa fa-pencil text-info"></i></a>';

        $media = $this->getType();
        $icon = ($this->type == 'photo') ? 'fa-file-image-o' : 'fa-file-video-o';

        /* comment link */
        $options[] = '<a class="option-link" href="/manage/media/' .$this->type. '/' .$this->album_id. '" data-toggle="tooltip" title="Danh sách ' .$media. '"><i class="fa ' .$icon. ' text-warning"></i></a>';

        if ($this->isDisable()) {
            $options[] = '<a data-title="album" class="enable-record" href="' .route('change_album_status', ['id' => $this->album_id]). '" data-toggle="tooltip" title="Kích hoạt album"><i class="fa fa-eye text-primary"></i></a>';
        } else {
            $options[] = '<a data-title="album" class="remove-record" href="' .route('change_album_status', ['id' => $this->album_id]). '" data-toggle="tooltip" title="Xóa album"><i class="fa fa-trash text-danger"></i></a>';
        }

        return implode('&nbsp;&nbsp;&nbsp;', $options);
    }

    /**
     * @return string
     */
    public function getCover()
    {
        return app('media')->getAlbumCover($this);
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return route('album_detail', ['slug' => $this->slug]);
    }
}
