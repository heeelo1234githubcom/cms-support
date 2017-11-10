<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MenuFormRequest;
use App\Models\Menu;
use App\Models\Page;
use App\Models\Promotion;
use App\Models\Service;
use App\Repositories\MenuRepository;
use App\Repositories\PageRepository;
use App\Repositories\PromotionRepository;
use App\Repositories\ServiceRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class MenuController extends Controller
{
    public function parent(Request $request)
    {
        $type = $request->input('type');
        $currentMenuId = $request->input('currentMenuId');

        /* @var $repository MenuRepository */
        $repository = app('menu');

        return response()->json([
            'success' => true,
            'menu' => $repository->getParentMenu($type, $currentMenuId)
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $search = $request->input('search.value');

        $data = [];

        /* search page */
        /* @var $pages Collection|Page[] */
        $pages = app('page')->search($search);
        if ($pages->count()) {

            $items = [];
            foreach ($pages as $page) {
                $items[] = [
                    'id' => 'page_' . $page->page_id,
                    'text' => $page->title
                ];
            }

            $data[] = [
                'id' => 'page',
                'text' => 'Trang',
                'children' => $items
            ];
        }

        /* search service */
        /* @var $services Collection|Service[] */
        $services = app('service')->search($search);
        if ($services->count()) {

            $items = [];
            foreach ($services as $service) {
                $items[] = [
                    'id' => 'service_' . $service->service_id,
                    'text' => $service->title
                ];
            }

            $data[] = [
                'id' => 'service',
                'text' => 'Dịch vụ',
                'children' => $items
            ];
        }

        /* search promotion */
        /* @var $promotions Collection|Promotion[] */
        $promotions = app('promotion')->search($search);
        if ($promotions->count()) {

            $items = [];
            foreach ($promotions as $promotion) {
                $items[] = [
                    'id' => 'promotion_' . $promotion->promotion_id,
                    'text' => $promotion->title
                ];
            }

            $data[] = [
                'id' => 'promotion',
                'text' => 'Khuyến mãi',
                'children' => $items
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * @param MenuFormRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(MenuFormRequest $request)
    {
        $data = $request->all();
        $menu = null;

        /* @var $menu Menu */
        if (isset($data['menu_id'])) {
            if ( !$menu = app('menu')->getById($data['menu_id'])) {
                abort(404, 'Không tìm thấy menu theo yêu cầu.');
            }
        }

        $url = $data['url'];
        if (isset($data['select_url']) && $data['select_url']) {
            list($type, $typeId) = explode('_', $data['select_url']);

            /* @var $repository PageRepository|ServiceRepository|PromotionRepository */
            $repository = app($type);

            /* @var $object Page|Promotion|Service */
            if ($object = $repository->getById($typeId)) {
                $url = route($type . '_detail', ['slug' => $object->slug]);
            }
        }

        if ( !$url) {
            $url = '';
        }

        if ($menu) {

            /* update exist menu */
            $menu->update([
                'title' => $data['title'],
                'url' => $url,
                'sort' => $data['sort'],
                'parent_id' => isset($data['parent_id']) ? $data['parent_id'] : null,
                'status' => $data['status'],
            ]);

        } else {

            /* create new menu */
            Menu::create([
                'title' => $data['title'],
                'url' => $url,
                'sort' => $data['sort'],
                'type' => $data['type'],
                'parent_id' => isset($data['parent_id']) ? $data['parent_id'] : null,
                'status' => $data['status'],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => trans('common.update_data_successfully'),
            'reset' => !$data['menu_id']
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listMenu(Request $request)
    {
        /* @var $repository MenuRepository */
        $repository = app('menu');

        $pageLength = (int) $request->input('length', 200);

        $pageIndex = $request->input('start', 0);
        $pageIndex = (int) ceil($pageIndex / $pageLength) + 1;

        if ( !$pageIndex) {
            $pageIndex = 1;
        }

        $pageIndex = $request->input('page', $pageIndex);

        /* sort */
        $sorts = ['title', 'parent_id', 'sort', 'status', 'sort'];
        $sort = $request->input('order', []);
        if ($sort) {

            $sort = $sort[0];
            $sort['column'] = $sorts[$sort['column']];
        }

        /* search */
        $searchQuery = $request->input('search.value');

        $with = ['parent'];
        $condition = [];

        if ($request->has('type')) {
            $condition['type'] = $request->input('type');
        }

        /* get users */
        /* @var $menu Collection|Menu[]|LengthAwarePaginator */
        $menu = $repository->getRecords($pageIndex, $pageLength, $searchQuery, $sort, $with, $condition);

        $items = [];
        $totalRows = $menu->total();

        if ($menu->count()) {

            /**
             * check for import data
             */
            foreach ($menu as $item) {

                $items[] = [
                    'menu_id' => $item->menu_id,
                    'title' => $item->title,
                    'parent_id' => ($item->parent) ? $item->parent->title : '',
                    'sort' => $item->sort,
                    'status' => $item->getStatusText(),
                    'option' => $item->getOptions()
                ];
            }
        }

        return response()->json([
            'data' => $items,
            'draw' => $request->input('draw', $pageIndex),
            'recordsTotal' => $totalRows,
            'recordsFiltered' => $totalRows,
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function remove($id)
    {
        /* @var $menu Menu */
        if ( !$menu = app('menu')->getById($id)) {
            abort(404, 'Không tìm thấy menu theo yêu cầu.');
        }

        /**
         * update album status
         */
        $menu->setStatusDeleted();
        $menu->save();

        return response()->json([
            'success' => true,
            'message' => trans('common.update_data_successfully'),
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function get($id)
    {
        /* @var $menu Menu */
        if ( !$menu = app('menu')->getById($id)) {
            abort(404, 'Không tìm thấy menu theo yêu cầu.');
        }

        return response()->json([
            'success' => true,
            'data' => [
                'menu_id' => $menu->menu_id,
                'title' => $menu->title,
                'sort' => $menu->sort,
                'url' => $menu->url,
                'status' => $menu->status,
                'type' => $menu->type ? $menu->type : 'top',
                'parent_id' => $menu->parent_id,
                'has_sub' => $menu->subs->count() ? 'yes' : 'no',
                'parentTitle' => $menu->parent ? $menu->parent->title : ''
            ]
        ]);
    }
}
