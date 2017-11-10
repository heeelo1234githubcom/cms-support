<?php

namespace App\Repositories;

use App\Models\Service;
use Illuminate\Contracts\Cache\Store;
use Illuminate\View\View;

/**
 * Class ServiceRepository
 * @package App\Repositories
 */
class ServiceRepository extends BaseRepository
{
    /**
     * ServiceRepository constructor.
     * @param Store $store
     */
    public function __construct(Store $store)
    {
        parent::__construct($store);

        $this->setModel(new Service());
    }

    /**
     * @return mixed
     */
    public function getHomeList()
    {
        if ( !$this->get('listHomeService')) {

            $data = [
                'services' => $this->getHomeServices()
            ];

            /* @var $view View */
            $view = view('frontend.home.services', $data);

            $this->forever('listHomeService', $view->render());
        }

        return $this->get('listHomeService');
    }

    /**
     * @return mixed
     */
    public function getHomeServices()
    {
        return $this->rememberForever('listServices', function() {
            return $this->getModel()
                ->whereStatus('enable')
                ->where('show_at_home', 'yes')
                ->get();
        });
    }

    /**
     * @return mixed
     */
    public function getSideBarServices()
    {
        if ( !$this->get('listSideBarService')) {

            $data = [
                'services' => $this->getHomeServices()
            ];

            /* @var $view View */
            $view = view('frontend.layouts.components.service_side_bar', $data);

            $this->forever('listSideBarService', $view->render());
        }

        return $this->get('listSideBarService');
    }
}
