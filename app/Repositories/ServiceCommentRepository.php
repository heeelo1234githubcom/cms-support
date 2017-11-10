<?php

namespace App\Repositories;

use App\Models\ServiceComment;
use Illuminate\Contracts\Cache\Store;

/**
 * Class ServiceCommentRepository
 * @package App\Repositories
 */
class ServiceCommentRepository extends BaseRepository
{
    /**
     * ServiceCommentRepository constructor.
     * @param Store $store
     */
    public function __construct(Store $store)
    {
        parent::__construct($store);

        $this->setModel(new ServiceComment());
    }
}
