<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PromotionFormRequest;
use App\Models\Promotion;
use App\Models\PromotionUser;
use App\Repositories\PromotionRepository;
use App\Repositories\PromotionUserRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class PromotionController extends Controller
{
    /**
     * @param PromotionFormRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(PromotionFormRequest $request)
    {
        $data = $request->all();
        $promotion = null;

        /* @var $promotion Promotion */
        if (isset($data['promotion_id'])) {
            if ( !$promotion = app('promotion')->getById($data['promotion_id'])) {
                abort(404, 'Không tìm thấy khuyến mãi theo yêu cầu.');
            }
        }

        $carbon = new Carbon();
        $startDate = '';
        $endDate = '';

        if ($data['start_date']) {
            $startDate =  $carbon->createFromFormat('d/m/Y', $data['start_date'])->format('Y-m-d');
        }

        if ($data['end_date']) {
            $endDate =  $carbon->createFromFormat('d/m/Y', $data['end_date'])->format('Y-m-d');
        }

        /**
         * default date
         */
        if ( !$startDate) {
            $startDate = '1970-01-01';
        }

        if ( !$endDate) {
            $endDate = '2099-12-31';
        }

        if ($promotion) {

            /* update exist promotion */
            $promotion->update([
                'title' => $data['title'],
                'slug' => $data['slug'],
                'content' => $data['content'],
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => $data['status'],
            ]);

        } else {

            /* create new promotion */
            Promotion::create([
                'title' => $data['title'],
                'slug' => $data['slug'],
                'content' => $data['content'],
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => $data['status'],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => trans('common.update_data_successfully'),
            'reset' => !$data['promotion_id']
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listPromotions(Request $request)
    {
        /* @var $repository PromotionRepository */
        $repository = app('promotion');

        $pageLength = (int) $request->input('length', 20);

        $pageIndex = $request->input('start', 0);
        $pageIndex = (int) ceil($pageIndex / $pageLength) + 1;

        if ( !$pageIndex) {
            $pageIndex = 1;
        }

        $pageIndex = $request->input('page', $pageIndex);

        /* sort */
        $sorts = ['title', 'start_date', 'end_date', 'status', 'promotion_id'];
        $sort = $request->input('order', []);
        if ($sort) {

            $sort = $sort[0];
            $sort['column'] = $sorts[$sort['column']];
        }

        /* search */
        $searchQuery = $request->input('search.value');

        $with = [];
        $condition = [];

        /* get promotions */
        /* @var $promotions Collection|Promotion[]|LengthAwarePaginator */
        $promotions = $repository->getRecords($pageIndex, $pageLength, $searchQuery, $sort, $with, $condition);

        $items = [];
        $totalRows = $promotions->total();

        if ($promotions->count()) {

            /**
             * check for import data
             */
            foreach ($promotions as $promotion) {

                $items[] = [
                    'promotion_id' => $promotion->promotion_id,
                    'title' => $promotion->title,
                    'start_date' => $promotion->getStartDate(),
                    'end_date' => $promotion->getEndDate(),
                    'status' => $promotion->getStatusText(),
                    'option' => $promotion->getOptions()
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
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listPromotionUsers(Request $request)
    {
        /* @var $repository PromotionUserRepository */
        $repository = app('promotionUser');

        $pageLength = (int) $request->input('length', 20);

        $pageIndex = $request->input('start', 0);
        $pageIndex = (int) ceil($pageIndex / $pageLength) + 1;

        if ( !$pageIndex) {
            $pageIndex = 1;
        }

        $pageIndex = $request->input('page', $pageIndex);

        /* sort */
        $sorts = ['name', 'email', 'phone', 'created_at', 'id'];
        $sort = $request->input('order', []);
        if ($sort) {

            $sort = $sort[0];
            $sort['column'] = $sorts[$sort['column']];
        }

        /* search */
        $searchQuery = $request->input('search.value');

        $with = [];
        $condition = [];

        /* get promotions */
        /* @var $promotions Collection|PromotionUser[]|LengthAwarePaginator */
        $promotions = $repository->getRecords($pageIndex, $pageLength, $searchQuery, $sort, $with, $condition);

        $items = [];
        $totalRows = $promotions->total();

        if ($promotions->count()) {

            /**
             * check for import data
             */
            foreach ($promotions as $promotion) {

                $items[] = [
                    'id' => $promotion->id,
                    'name' => $promotion->name,
                    'email' => $promotion->email,
                    'phone' => $promotion->phone,
                    'created_at' => $promotion->getDate()
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
    public function get($id)
    {
        /* @var $promotion Promotion */
        if ( !$promotion = app('promotion')->getById($id)) {
            abort(404, 'Không tìm thấy khuyến mãi theo yêu cầu.');
        }

        return response()->json([
            'success' => true,
            'data' => [
                'promotion_id' => $promotion->promotion_id,
                'title' => $promotion->title,
                'slug' => $promotion->slug,
                'content' => $promotion->content,
                'start_date' => $promotion->getStartDate(),
                'end_date' => $promotion->getEndDate(),
                'status' => $promotion->status,
            ]
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeStatus($id)
    {
        /* @var $promotion Promotion */
        if ( !$promotion = app('promotion')->getById($id)) {
            abort(404, 'Không tìm thấy khuyến mãi theo yêu cầu.');
        }

        /**
         * update promotion status
         */
        if ($promotion->isDisable()) {
            $promotion->setStatusEnable();
        } else {
            $promotion->setStatusDisable();
        }
        $promotion->save();

        return response()->json([
            'success' => true,
            'message' => trans('common.update_data_successfully'),
        ]);
    }
}
