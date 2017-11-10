<?php

namespace App\Repositories;

use App\Models\Contact;
use Illuminate\Contracts\Cache\Store;

/**
 * Class ContactRepository
 * @package App\Repositories
 */
class ContactRepository extends BaseRepository
{
    /**
     * ContactRepository constructor.
     * @param Store $store
     */
    public function __construct(Store $store)
    {
        parent::__construct($store);

        $this->setModel(new Contact());
    }
}
