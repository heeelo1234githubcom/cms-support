<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Repositories\PromotionRepository;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    /**
     * @param string $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function detail($slug)
    {
        /* @var $promotion Promotion */
        $promotion = app('promotion')->getByColumns([
            'slug' => $slug,
            'status' => 'enable'
        ]);

        if ( !$promotion) {
            abort(404, 'Không tìm thấy trang web theo yêu cầu.');
        }

        $data = [
            'title' => $promotion->title,
            'promotion' => $promotion,
            'breadcrumb' => 'Khuyến mãi'
        ];

        return view('frontend.promotion.detail', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $page = $request->input('page');
        if ($page == 1) {
            return response()->redirectToRoute('promotion_page', [], 301);
        }

        if ( !$page) {
            $page = 1;
        }

        $itemPerPage = 10;
        $sort = [
            'column' => 'promotion_id',
            'dir' => 'desc'
        ];
        $width = [];
        $condition = ['status' => 'enable'];
        $select = ['title', 'slug', 'content'];

        /* @var $repository PromotionRepository */
        $repository = app('promotion');

        $promotions = $repository->getRecords($page, $itemPerPage, '', $sort, $width, $condition, '', $select);

        if ( !$promotions->count()) {
            abort(404);
        }

        return view('frontend.promotion.index', [
            'title' => 'Khuyến mãi',
            'promotions' => $promotions
        ]);
    }
}
