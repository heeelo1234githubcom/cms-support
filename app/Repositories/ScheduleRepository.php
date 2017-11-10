<?php

namespace App\Repositories;

use App\Models\Schedule;
use Illuminate\Contracts\Cache\Store;

/**
 * Class ScheduleRepository
 * @package App\Repositories
 */
class ScheduleRepository extends BaseRepository
{
    /**
     * ScheduleRepository constructor.
     * @param Store $store
     */
    public function __construct(Store $store)
    {
        parent::__construct($store);

        $this->setModel(new Schedule());
    }
}
