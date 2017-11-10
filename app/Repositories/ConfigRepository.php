<?php

namespace App\Repositories;

use App\Models\Config;
use App\Models\Page;
use Illuminate\Contracts\Cache\Store;

/**
 * Class ConfigRepository
 * @package App\Repositories
 */
class ConfigRepository extends BaseRepository
{
    /**
     * ConfigRepository constructor.
     * @param Store $store
     */
    public function __construct(Store $store)
    {
        parent::__construct($store);

        $this->setModel(new Config());
    }

    /**
     * @return array
     */
    public function getConfigs()
    {
        $model = $this->getModel();

        return $model::get()
            ->pluck('config_value', 'config_name')
            ->toArray();
    }

    /**
     * @param array $configs
     */
    public function updateConfigs($configs = [])
    {
        /* @var $model Config */
        $model = $this->getModel();

        foreach ($configs as $key => $value) {
            $model::updateOrCreate([
                'config_name' => $key
            ], [
                'config_value' => $value ? $value : ''
            ]);
        }
    }

    /**
     * init configs
     */
    public function init()
    {
        if ($configs = $this->getModel()->get()->pluck('config_value', 'config_name')->toArray()) {
            config(['webConfigs' => $configs]);
        }
    }
}
