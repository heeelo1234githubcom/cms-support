<?php

namespace App\Repositories;

use App\Models\Slide;
use Illuminate\Contracts\Cache\Store;
use Illuminate\View\View;

/**
 * Class SlideRepository
 * @package App\Repositories
 */
class SlideRepository extends BaseRepository
{
    /**
     * MediaRepository constructor.
     * @param Store $store
     */
    public function __construct(Store $store)
    {
        parent::__construct($store);

        $this->setModel(new Slide());
    }

    /**
     * @return mixed
     */
    public function getHomeSlides()
    {
        if ( !$this->get('listHomeSlides')) {

            $data = [
                'slides' => $this->getSlides()
            ];

            /* @var $view View */
            $view = view('frontend.home.slider', $data);

            $this->forever('listHomeSlides', $view->render());
        }

        return $this->get('listHomeSlides');
    }

    /**
     * @return mixed
     */
    public function getSlides()
    {
        return $this->getModel()->whereStatus('enable')->get();
    }
}
