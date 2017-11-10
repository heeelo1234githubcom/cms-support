<?php

namespace App\Models;

use App\Traits\Status;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Media
 * @package App\Models
 *
 * @property int $media_id
 * @property int $album_id
 * @property string $title
 * @property string $description
 * @property string $file
 * @property string $status
 * @property Album $album
 */
class Media extends Model
{
    use Status;

    /**
     * @inheritdoc
     * @var string
     */
    public $table = 'media';

    /**
     * @inheritdoc
     * @var string
     */
    public $primaryKey = 'media_id';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'album_id',
        'title',
        'description',
        'file',
        'status',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function album()
    {
        return $this->belongsTo('App\Models\Album', 'album_id');
    }

    /**
     * @param string $type
     * @return string
     */
    public function getFile($type = 'photo')
    {
        return ($type == 'photo')
            ? '<img class="photo-preview" src="' .$this->file. '" style="width: 100px;" />'
                : '<img data-title="' .$this->title. '" data-src="' .$this->file. '" class="video-preview" src="https://img.youtube.com/vi/' .$this->file. '/default.jpg" style="width: 100px;" />';
    }

    /**
     * @param string $type
     * @return string
     */
    public function getOptions($type = 'photo')
    {
        $options = [];

        $media = ($type == 'photo') ? 'ảnh' : 'video';
        $options[] = '<a class="ajax-update-record" href="' .route('get_media', ['id' => $this->media_id]). '" data-toggle="tooltip" title="Cập nhật ' .$media. '"><i class="fa fa-pencil text-info"></i></a>';

        if ($this->isDisable()) {
            $options[] = '<a data-title="' .$media. '" class="enable-record" href="' .route('change_media_status', ['id' => $this->media_id]). '" data-toggle="tooltip" title="Kích hoạt '.$media.'"><i class="fa fa-eye text-primary"></i></a>';
        } else {
            $options[] = '<a data-title="' .$media. '" class="remove-record" href="' .route('change_media_status', ['id' => $this->media_id]). '" data-toggle="tooltip" title="Xóa ' .$media. '"><i class="fa fa-trash text-danger"></i></a>';
        }

        return implode('&nbsp;&nbsp;&nbsp;', $options);
    }

    /**
     * @return string
     */
    public function getAlbumLink()
    {
        return '<a class="option-link" href="/manage/media/' .$this->album->type. '/' .$this->album_id. '" data-toggle="tooltip">' .$this->album->title. '</a>';
    }

    /**
     * @return string
     */
    public function getVideoUrl()
    {
        return 'https://www.youtube.com/watch?v=' . $this->file;
    }

    /**
     * @return string
     */
    public function getVideoImage()
    {
        return 'https://img.youtube.com/vi/' .$this->file. '/0.jpg';
    }
}
