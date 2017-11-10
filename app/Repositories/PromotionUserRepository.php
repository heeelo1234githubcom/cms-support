<?php

namespace App\Repositories;

use App\Models\PromotionUser;
use Illuminate\Contracts\Cache\Store;

/**
 * Class PromotionUserRepository
 * @package App\Repositories
 */
class PromotionUserRepository extends BaseRepository
{
    /**
     * PromotionUserRepository constructor.
     * @param Store $store
     */
    public function __construct(Store $store)
    {
        parent::__construct($store);

        $this->setModel(new PromotionUser());
    }
}
