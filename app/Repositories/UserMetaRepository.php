<?php

namespace App\Repositories;

use App\Models\UserMeta;
use Illuminate\Contracts\Cache\Store;

/**
 * Class UserMetaRepository
 * @package App\Repositories
 */
class UserMetaRepository extends BaseRepository
{
    /**
     * UserMetaRepository constructor.
     * @param Store $store
     */
    public function __construct(Store $store)
    {
        parent::__construct($store);

        $this->setModel(new UserMeta());
    }
}
