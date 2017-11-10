<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;

class ModelObserver
{
    /**
     * @param $tags
     */
    protected function clearCacheTags($tags)
    {
        \Cache::tags($tags)->flush();
    }

    /**
     * @param $model Model
     */
    public function saved($model)
    {
        $table = $model->getTable();
        if ($table == 'media') {
            $this->clearCacheTags('albums');
        }

        $this->clearCacheTags($model->getTable());
    }

    /**
     * @param $model Model
     */
    public function deleted($model)
    {
        $this->clearCacheTags($model->getTable());
    }

    /**
     * @param $model Model
     */
    public function restored($model)
    {
        $this->clearCacheTags($model->getTable());
    }
}
