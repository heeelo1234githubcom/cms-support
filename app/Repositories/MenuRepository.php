<?php

namespace App\Repositories;

use App\Models\Menu;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

/**
 * Class MenuRepository
 * @package App\Repositories
 */
class MenuRepository extends BaseRepository
{
    /**
     * MenuRepository constructor.
     * @param Store $store
     */
    public function __construct(Store $store)
    {
        parent::__construct($store);

        $this->setModel(new Menu());
    }

    /**
     * @param string $type
     * @param string $currentMenuId
     * @return array
     */
    public function getParentMenu($type = '', $currentMenuId = '')
    {
        /* @var $menu Collection|Menu[] */
        $menu = $this->getModel()
            ->whereNull('parent_id')
            ->where('type', $type)
            ->where('menu_id', '<>', $currentMenuId)
            ->where('status', '<>', 'deleted')
            ->get();

        if ($menu->count()) {
            $items = [];

            foreach ($menu as $item) {
                $items[] = [
                    'id' => $item->menu_id,
                    'text' => $item->title
                ];
            }

            return $items;
        }

        return [];
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getMenuTop()
    {
        if ( !$this->has('menuTopContent')) {

            /* @var $view View */
            $view = view('frontend.layouts.components.menu', [
                'menu' => $this->getModel()
                    ->whereNull('parent_id')
                    ->whereStatus('enable')
                    ->where('type', 'top')
                    ->orderBy('sort', 'asc')
                    ->get()
            ]);

            $this->forever('menuTopContent', $view->render());
        }

        return $this->get('menuTopContent');
    }

    /**
     * @return mixed
     */
    public function getSideBarMenu()
    {
        if ( !$this->has('menuRightContent')) {

            /* @var $view View */
            $view = view('frontend.layouts.components.menu_side_bar', [
                'menu' => $this->getModel()
                    ->whereNull('parent_id')
                    ->whereStatus('enable')
                    ->where('type', 'right')
                    ->orderBy('sort', 'asc')
                    ->get(),

                'title' => config('webConfigs.menu_right_title')
            ]);

            $this->forever('menuRightContent', $view->render());
        }

        return $this->get('menuRightContent');
    }
}
