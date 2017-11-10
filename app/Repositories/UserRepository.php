<?php

namespace App\Repositories;

use Illuminate\Contracts\Cache\Store;
use App\Models\User;

/**
 * Class UserRepository
 * @package App\Repositories
 */
class UserRepository extends BaseRepository
{
    /**
     * UserRepository constructor.
     * @param Store $store
     */
    public function __construct(Store $store)
    {
        parent::__construct($store);

        $this->setModel(new User());
    }
}
