<?php

namespace App\Repositories;

use App\Models\Page;
use Illuminate\Contracts\Cache\Store;

/**
 * Class PageRepository
 * @package App\Repositories
 */
class PageRepository extends BaseRepository
{
    /**
     * PageRepository constructor.
     * @param Store $store
     */
    public function __construct(Store $store)
    {
        parent::__construct($store);

        $this->setModel(new Page());
    }
}
