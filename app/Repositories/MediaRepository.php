<?php

namespace App\Repositories;

use App\Models\Album;
use App\Models\Media;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Database\Query\Builder;

/**
 * Class MediaRepository
 * @package App\Repositories
 */
class MediaRepository extends BaseRepository
{
    /**
     * MediaRepository constructor.
     * @param Store $store
     */
    public function __construct(Store $store)
    {
        parent::__construct($store);

        $this->setModel(new Media());
    }

    /**
     * @param $album Album
     * @return mixed
     */
    public function getMediaByAlbum($album)
    {
        return $this->rememberForever('list_media_by_album_' . $album->album_id, function () use ($album) {

            return $album->media()
                ->orderBy('media_id', 'desc')
                ->get();
        });
    }

    /**
     * @param $album Album
     * @return string
     */
    public function getAlbumCover($album)
    {
        return $this->rememberForever('album_cover_' . $album->album_id, function () use ($album) {

            /* @var $photo Media */
            $photo = $album->media()->first();

            return ($photo) ? $photo->file : '/assets/frontend/images/no-image.jpg';
        });
    }

    /**
     * @param int $page
     * @param int $itemPerPage
     * @return mixed
     */
    public function getVideos($page = 1, $itemPerPage = 15)
    {
        $key = 'video_by_page_' . $page . '_items_' . $itemPerPage;

        return $this->rememberForever($key, function () use ($page, $itemPerPage) {

            $model = $this->getModel()->whereStatus('enable');

            $model = $model->whereHas('album', function ($query) {

                /* @var $query Builder */
                $query->where([
                    'status' => 'enable',
                    'type' => 'video'
                ]);
            });

            $model = $model->orderBy('media_id', 'desc');

            return $model->paginate($itemPerPage, ['*'], 'page', $page);
        });
    }
}
