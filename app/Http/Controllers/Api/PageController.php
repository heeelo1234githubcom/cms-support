<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PageFormRequest;
use App\Models\Page;
use App\Repositories\PageRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Symfony\Component\DomCrawler\Crawler;

class PageController extends Controller
{
    /**
     * @param PageFormRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(PageFormRequest $request)
    {
        $data = $request->all();
        $page = null;

        /* @var $page Page */
        if (isset($data['page_id'])) {
            if ( !$page = app('page')->getById($data['page_id'])) {
                abort(404, 'Không tìm thấy trang theo yêu cầu.');
            }
        }

        /* @var $dom Crawler */
        $data['content'] = uploadContentImage($data['content'], $request);

        if ($page) {

            /* update exist service */
            $page->update([
                'title' => $data['title'],
                'slug' => $data['slug'],
                'content' => $data['content'],
                'status' => $data['status'],
            ]);

        } else {

            /* create new page */
            Page::create([
                'title' => $data['title'],
                'slug' => $data['slug'],
                'content' => $data['content'],
                'status' => $data['status'],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => trans('common.update_data_successfully')
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listPages(Request $request)
    {
        /* @var $repository PageRepository */
        $repository = app('page');

        $pageLength = (int) $request->input('length', 20);

        $pageIndex = $request->input('start', 0);
        $pageIndex = (int) ceil($pageIndex / $pageLength) + 1;

        if ( !$pageIndex) {
            $pageIndex = 1;
        }

        /* sort */
        $sorts = ['title', 'slug', 'status', 'page_id'];
        $sort = $request->input('order', []);
        if ($sort) {

            $sort = $sort[0];
            $sort['column'] = $sorts[$sort['column']];
        }

        /* search */
        $searchQuery = $request->input('search.value');

        $comboBox = $request->input('comboBox');
        $with = [];

        /* get pages */
        /* @var $pages Collection|Page[]|LengthAwarePaginator */
        $pages = $repository->getRecords($pageIndex, $pageLength, $searchQuery, $sort, $with);

        $items = [];
        $totalRows = $pages->total();

        if ($pages->count()) {

            /**
             * check for import data
             */
            foreach ($pages as $page) {

                if ($comboBox) {
                    $items[] = [
                        'id' => $page->page_id,
                        'text' => $page->title,
                    ];
                } else {
                    $items[] = [
                        'title' => $page->title,
                        'slug' => $page->slug,
                        'status' => $page->getStatusText(),
                        'option' => $page->getOptions()
                    ];
                }
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
    public function get(Request $request)
    {
        $pageId = $request->input('page_id');

        /* @var $page Page */
        if ( !$page = app('page')->getById($pageId)) {
            abort(404, 'Không tìm thấy trang theo yêu cầu.');
        }

        return response()->json([
            'success' => true,
            'data' => $page->toArray()
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeStatus($id)
    {
        /* @var $page Page */
        if ( !$page = app('page')->getById($id)) {
            abort(404, 'Không tìm thấy trang theo yêu cầu.');
        }

        if ($page->isDisable()) {
            $page->setStatusEnable();
        } else {
            $page->setStatusDisable();
        }

        $page->save();

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật dữ liệu thành công!'
        ]);
    }
}
