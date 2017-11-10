<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AlbumFormRequest;
use App\Models\Album;
use App\Repositories\AlbumRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class AlbumController extends Controller
{
    /**
     * @param AlbumFormRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(AlbumFormRequest $request)
    {
        $data = $request->all();
        $album = null;

        /* @var $album Album */
        if (isset($data['album_id'])) {
            if ( !$album = app('album')->getById($data['album_id'])) {
                abort(404, 'Không tìm thấy album theo yêu cầu.');
            }
        }

        if ($album) {

            /* update exist user */
            $album->update([
                'title' => $data['title'],
                'slug' => $data['slug'],
                'description' => $data['description'] ? $data['description'] : '',
                'status' => $data['status'],
            ]);

        } else {

            /* create new album */
            Album::create([
                'title' => $data['title'],
                'slug' => $data['slug'],
                'type' => $data['type'],
                'description' => $data['description'] ? $data['description'] : '',
                'status' => $data['status'],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => trans('common.update_data_successfully'),
            'reset' => !$data['album_id']
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listAlbums(Request $request)
    {
        /* @var $repository AlbumRepository */
        $repository = app('album');

        $selectBox = $request->input('selectBox');
        $pageLength = (int) $request->input('length', 20);

        $pageIndex = $request->input('start', 0);
        $pageIndex = (int) ceil($pageIndex / $pageLength) + 1;

        if ( !$pageIndex) {
            $pageIndex = 1;
        }

        $pageIndex = $request->input('page', $pageIndex);

        /* sort */
        $sorts = ['title', 'slug', 'description', 'status', 'album_id'];
        $sort = $request->input('order', []);
        if ($sort) {

            $sort = $sort[0];
            $sort['column'] = $sorts[$sort['column']];
        }

        /* search */
        $searchQuery = $request->input('search.value');

        $with = [];
        $condition = [];

        if ($request->has('type')) {
            $condition['type'] = $request->input('type');
        }

        /* get users */
        /* @var $albums Collection|Album[]|LengthAwarePaginator */
        $albums = $repository->getRecords($pageIndex, $pageLength, $searchQuery, $sort, $with, $condition);

        $items = [];
        $totalRows = $albums->total();

        if ($albums->count()) {

            /**
             * check for import data
             */
            foreach ($albums as $album) {

                if ($selectBox) {

                    $items[] = [
                        'id' => $album->album_id,
                        'text' => $album->title
                    ];

                } else {
                    $items[] = [
                        'album_id' => $album->album_id,
                        'title' => $album->title,
                        'slug' => $album->slug,
                        'description' => $album->description,
                        'type' => $album->getType(),
                        'status' => $album->getStatusText(),
                        'option' => $album->getOptions()
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
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function get($id)
    {
        /* @var $album Album */
        if ( !$album = app('album')->getById($id)) {
            abort(404, 'Không tìm thấy album theo yêu cầu.');
        }

        return response()->json([
            'success' => true,
            'data' => $album->toArray()
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeStatus($id)
    {
        /* @var $album Album */
        if ( !$album = app('album')->getById($id)) {
            abort(404, 'Không tìm thấy album theo yêu cầu.');
        }

        /**
         * update album status
         */
        if ($album->isDisable()) {
            $album->setStatusEnable();
        } else {
            $album->setStatusDisable();
        }
        $album->save();

        return response()->json([
            'success' => true,
            'message' => trans('common.update_data_successfully'),
        ]);
    }
}
