<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Page;

class PageController extends Controller
{
    /**
     * @param string $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function detail($slug)
    {
        /* @var $page Page */
        $page = app('page')->getByColumns([
            'slug' => $slug,
            'status' => 'enable'
        ]);

        if ( !$page) {
            abort(404, 'Không tìm thấy trang web theo yêu cầu.');
        }

        /* check home page */
        $homeId = config('webConfigs.home_id');
        if ($homeId == $page->page_id) {
            return response()->redirectToRoute('home_page', [], 301);
        }

        $data = [
            'title' => $page->title,
            'page' => $page
        ];

        return view('frontend.page.detail', $data);
    }
}
