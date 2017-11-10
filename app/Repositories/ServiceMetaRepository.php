<?php

namespace App\Repositories;

use App\Models\ServiceMeta;
use Illuminate\Contracts\Cache\Store;

/**
 * Class ServiceMetaRepository
 * @package App\Repositories
 */
class ServiceMetaRepository extends BaseRepository
{
    /**
     * ServiceMetaRepository constructor.
     * @param Store $store
     */
    public function __construct(Store $store)
    {
        parent::__construct($store);

        $this->setModel(new ServiceMeta());
    }
}
