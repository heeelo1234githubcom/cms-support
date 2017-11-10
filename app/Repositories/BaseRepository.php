<?php

namespace App\Repositories;

use Illuminate\Cache\Repository;
use Illuminate\Database\Eloquent\Builder;

abstract class BaseRepository extends Repository
{
    /**
     * cache by primary key
     */
    const CACHE_BY_PRIMARY = 'cache_primary_';

    /**
     * cache by columns
     */
    const CACHE_BY_COLUMNS = 'cache_columns_';

    /**
     * The Model instance.
     * @var \Illuminate\Database\Eloquent\Model
     */
    private $model;

    /**
     * @var
     */
    protected $with;

    /**
     * @param $model
     */
    protected function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model|Builder
     */
    protected function getModel()
    {
        return $this->model;
    }

    /**
     * @return mixed
     */
    protected function getWith()
    {
        return $this->with;
    }

    /**
     * @return string
     */
    public function table()
    {
        return $this->getModel()->getTable();
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return $this->tags($this->table())->has($key);
    }

    /**
     * @param string $key
     * @param null $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return $this->tags($this->table())->get($key, $default);
    }

    /**
     * @param string $key
     * @param null $value
     */
    public function forever($key, $value = null)
    {
        if ($value) {
            $this->tags($this->table())->forever($key, $value);
        }
    }

    /**
     * @param string $key
     * @param string $default
     * @return mixed
     */
    public function pull($key, $default = '')
    {
        return $this->tags($this->table())->pull($key, $default);
    }

    /**
     * @param string $key
     * @param null $value
     * @param null $minutes
     */
    public function put($key, $value = null, $minutes = null)
    {
        if ($value) {
            $this->tags($this->table())->put($key, $value, $minutes);
        }
    }

    /**
     * @param string $key
     * @return bool
     */
    public function forget($key)
    {
        return $this->tags($this->table())->forget($key);
    }

    /**
     * flush cache tags
     */
    public function flush()
    {
        $this->tags($this->table())->flush();
    }

    /**
     * @param string $key
     * @param \Closure $callback
     * @return mixed
     */
    public function rememberForever($key, \Closure $callback)
    {
        return $this->tags($this->table())->rememberForever($key, $callback);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getById($id)
    {
        $id = (int) $id;
        $key = self::CACHE_BY_PRIMARY . $id;

        return $this->rememberForever($key, function () use ($id) {

            return $this->getModel()->find($id);
        });
    }

    /**
     * @param int $id
     * @return bool|mixed
     */
    public function remove($id)
    {
        if ($record = $this->getById($id)) {

            /**
             * remove form cache
             */
            $key = self::CACHE_BY_PRIMARY . $id;
            $this->forget($key);

            return $record->delete();
        }

        return true;
    }

    /**
     * @param array $params
     * @param bool $one
     * @return mixed
     */
    public function getByColumns($params = [], $one = true)
    {
        $key = $one ? '_one' : '_multiple';
        $key = self::CACHE_BY_COLUMNS . md5(serialize($params)) . $key;

        return $this->rememberForever($key, function () use ($params, $one) {

            $model = $this->getModel()->where($params);

            return $one ? $model->first() : $model->get();
        });
    }

    /**
     * @param int $pageIndex
     * @param int $pageLength
     * @param string $searchQuery
     * @param array $sort
     * @param array $with
     * @param array $condition
     * @param string $mediaType
     * @param array $select
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getRecords($pageIndex = 1, $pageLength = 20, $searchQuery = '', $sort = [], $with = [], $condition = [], $mediaType = 'photo', $select = ['*'])
    {
        $model = $this->getModel();
        $table = $this->table();

        if ($with) {
            $model = $model->with($with);
        }

        if ($condition) {
            $model = $model->where($condition);
        }

        /* media search */
        if ('media' == $table) {
            $model = $model->where(function($query) use ($searchQuery, $condition, $mediaType) {

                /* @var $query Builder */
                /* filter by media type */
                $query->whereHas('album', function ($query) use ($mediaType) {

                    /* @var $query Builder */
                    $query->where('type', $mediaType);
                });

                if ($searchQuery) {

                    /* search media by title | albumTitle */
                    $query->where(function ($query) use ($condition, $searchQuery) {

                        /* @var $query Builder */
                        $query->where('title', 'like', '%'. $searchQuery .'%');

                        if ( !isset($condition['album_id'])) {
                            $query->orWhereHas('album', function ($query) use ($searchQuery) {

                                /* @var $query Builder */
                                $query->where('title', 'like', '%'. $searchQuery .'%');
                            });
                        }
                    });
                }
            });
        }

        /* search */
        if ($searchQuery) {

            if (in_array($table, ['services', 'pages', 'promotions', 'albums', 'slides'])) {
                $model = $model->where('title', 'like', '%'. $searchQuery .'%');
            }

            if ('service_comments' === $table) {
                $model = $model->where(function ($query) use ($searchQuery, $condition) {

                    /* @var $query Builder */
                    $query->where('comment_email', 'like', '%'. $searchQuery .'%')
                        ->orWhere('comment_name', 'like', '%'. $searchQuery .'%');

                    if ( !isset($condition['service_id'])) {
                        $query->orWhereHas('service', function ($query) use ($searchQuery) {

                            /* @var $query Builder */
                            $query->where('title', 'like', '%'. $searchQuery .'%');
                        });
                    }
                });
            }

            /**
             * schedule
             */
            if ('schedules' === $table) {
                $model = $model->where('schedule_name', 'like', '%'. $searchQuery .'%');
            }

            /**
             * contact
             */
            if ('contacts' === $table) {
                $model = $model->where('contact_name', 'like', '%'. $searchQuery .'%');
            }

            /**
             * user & promotion users
             */
            if ('users' === $table || ('newsletters' === $table)) {
                $model = $model->where('name', 'like', '%'. $searchQuery .'%')
                    ->orWhere('email', 'like', '%'. $searchQuery .'%');
            }
        }

        if ($table !== 'newsletters') {
            $model = $model->where('status', '<>', 'deleted');
        }

        /**
         * sort
         */
        if ($sort) {
            $model = $model->orderBy($sort['column'], $sort['dir']);
        }

        if ($pageLength) {
            return $model->paginate($pageLength, $select, 'page', $pageIndex);
        }

        return $model->get($select);
    }

    /**
     * @param string $search
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function search($search = '')
    {
        $model = $this->getModel();

        if ($search) {
            $model = $model->where('title', 'like', '%' . $search . '%');
        }

        return $model->offset(0)->limit(5)->get();
    }
}
