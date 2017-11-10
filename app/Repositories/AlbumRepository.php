<?php

namespace App\Repositories;

use App\Models\Album;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Database\Query\Builder;

/**
 * Class AlbumRepository
 * @package App\Repositories
 */
class AlbumRepository extends BaseRepository
{
    /**
     * AlbumRepository constructor.
     * @param Store $store
     */
    public function __construct(Store $store)
    {
        parent::__construct($store);

        $this->setModel(new Album());
    }

    /**
     * @param int $page
     * @param int $itemPerPage
     * @param string $type
     * @return mixed
     */
    public function getAlbums($page = 1, $itemPerPage = 15, $type = 'photo')
    {
        $key = 'album_' . $type . '_by_page_' . $page . '_items_' . $itemPerPage;

        return $this->rememberForever($key, function () use ($page, $itemPerPage, $type) {

            $model = $this->getModel()->where([
                'status' => 'enable',
                'type' => $type
            ]);

            $model = $model->whereHas('media', function ($query) {

                /* @var $query Builder */
                $query->whereStatus('enable');
            });

            $model = $model->orderBy('album_id', 'desc');

            return $model->paginate($itemPerPage, ['*'], 'page', $page);
        });
    }

    /**
     * @return mixed
     */
    public function getPhotoAlbums()
    {
        $model = $this->getModel()->where([
            'status' => 'enable',
            'type' => 'photo'
        ]);

        $model = $model->whereHas('media', function ($query) {

            /* @var $query Builder */
            $query->whereStatus('enable');
        });

        $model = $model->orderBy('album_id', 'desc');

        return $model->get();
    }
}
