<?php

namespace App\Repositories;

use App\Models\Promotion;
use Illuminate\Contracts\Cache\Store;

/**
 * Class PromotionRepository
 * @package App\Repositories
 */
class PromotionRepository extends BaseRepository
{
    /**
     * PromotionRepository constructor.
     * @param Store $store
     */
    public function __construct(Store $store)
    {
        parent::__construct($store);

        $this->setModel(new Promotion());
    }

    /**
     * @return mixed
     */
    public function getSideBarPromotion()
    {
        if ( !$this->has('promotionSideBar')) {

            $view = view('frontend.promotion.sidebar', [
                'promotions' => $this->getModel()
                    ->whereStatus('enable')
                    ->orderBy('promotion_id', 'desc')
                    ->take(5)->get()
            ]);

            $this->forever('promotionSideBar', $view->render());
        }

        return $this->get('promotionSideBar');
    }
}
