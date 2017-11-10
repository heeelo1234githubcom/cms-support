<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PhotoFormRequest;
use App\Http\Requests\VideoFormRequest;
use App\Models\Media;
use App\Repositories\AlbumRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class MediaController extends Controller
{
    /**
     * @param PhotoFormRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function savePhoto(PhotoFormRequest $request)
    {
        $data = $request->all();
        $media = null;

        /* @var $media Media */
        if (isset($data['media_id'])) {
            if ( !$media = app('media')->getById($data['media_id'])) {
                abort(404, 'Không tìm thấy ảnh theo yêu cầu.');
            }
        }

        /* upload media file */
        $mediaFile = '';
        if (isset($data['file'])) {

            /* @var $file UploadedFile */
            $file = $data['file'];

            $currentDate = (new Carbon())->format('Y/m/d');
            $extension = $file->getClientOriginalExtension();
            $filename = str_slug(substr($file->getClientOriginalName(), 0, -3)) . '.' . $extension;

            $path = storage_path('app/public/' . $currentDate);

            $i = 1;
            $checkFilename = $filename;
            while(file_exists($path . '/' . $checkFilename)) {
                $checkFilename = $i . '-' . $filename;
                $i++;
            }

            $mediaFile = '/storage/' . $currentDate . '/' . $checkFilename;
            $file->move($path, $checkFilename);
        }

        if ($media) {

            /* update exist media */
            $media->update([
                'title' => $data['title'],
                'album_id' => $data['album_id'],
                'file' => $mediaFile ? $mediaFile : $media->file,
                'description' => $data['description'] ? $data['description'] : '',
                'status' => $data['status'],
            ]);

        } else {

            /* create new media */
            Media::create([
                'title' => $data['title'],
                'album_id' => $data['album_id'],
                'file' => $mediaFile,
                'description' => $data['description'] ? $data['description'] : '',
                'status' => $data['status'],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => trans('common.update_data_successfully'),
            'reset' => !$data['media_id']
        ]);
    }

    /**
     * @param VideoFormRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveVideo(VideoFormRequest $request)
    {
        $data = $request->all();
        $media = null;

        /* @var $media Media */
        if (isset($data['media_id'])) {
            if ( !$media = app('media')->getById($data['media_id'])) {
                abort(404, 'Không tìm thấy video theo yêu cầu.');
            }
        }

        if ($media) {

            /* update exist media */
            $media->update([
                'title' => $data['title'],
                'album_id' => $data['album_id'],
                'file' => $data['file'],
                'description' => $data['description'] ? $data['description'] : '',
                'status' => $data['status'],
            ]);

        } else {

            /* create new media */
            Media::create([
                'title' => $data['title'],
                'album_id' => $data['album_id'],
                'file' => $data['file'],
                'description' => $data['description'] ? $data['description'] : '',
                'status' => $data['status'],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => trans('common.update_data_successfully'),
            'reset' => !$data['media_id']
        ]);
    }

    /**
     * @param $type
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listMedia($type, Request $request)
    {
        /* @var $repository AlbumRepository */
        $repository = app('media');

        $pageLength = (int) $request->input('length', 20);

        $pageIndex = $request->input('start', 0);
        $pageIndex = (int) ceil($pageIndex / $pageLength) + 1;

        if ( !$pageIndex) {
            $pageIndex = 1;
        }

        /* sort */
        $sorts = ['title', 'albumTitle', 'file', 'status', 'media_id'];
        $sort = $request->input('order', []);
        if ($sort) {

            $sort = $sort[0];
            $sort['column'] = $sorts[$sort['column']];
        }

        /* search */
        $searchQuery = $request->input('search.value');

        $with = ['album'];
        $condition = [];
        if ($request->has('album_id')) {
            $condition['album_id'] = $request->input('album_id');
        }

        /* get users */
        /* @var $media Collection|Media[]|LengthAwarePaginator */
        $media = $repository->getRecords($pageIndex, $pageLength, $searchQuery, $sort, $with, $condition, $type);

        $items = [];
        $totalRows = $media->total();

        $album = null;
        if ($media->count()) {

            /**
             * check for import data
             */
            foreach ($media as $item) {

                if ($request->has('album_id') && !$album) {
                    $album = $item->album;
                }

                $items[] = [
                    'title' => $item->title,
                    'albumTitle' => $item->getAlbumLink(),
                    'file' => $item->getFile($type),
                    'status' => $item->getStatusText(),
                    'option' => $item->getOptions($type)
                ];
            }
        }

        return response()->json([
            'data' => $items,
            'draw' => $request->input('draw', $pageIndex),
            'recordsTotal' => $totalRows,
            'recordsFiltered' => $totalRows,
            'albumTitle' => ($album) ? $album->title : ''
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function get($id)
    {
        /* @var $media Media */
        if ( !$media = app('media')->getById($id)) {
            abort(404, 'Không tìm thấy dữ liệu theo yêu cầu.');
        }

        return response()->json([
            'success' => true,
            'data' => [
                'media_id' => $media->media_id,
                'album_id' => $media->album_id,
                'title' => $media->title,
                'file' => $media->file,
                'status' => $media->status,
                'albumTitle' => $media->album->title,
            ]
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeStatus($id)
    {
        /* @var $media Media */
        if ( !$media = app('media')->getById($id)) {
            abort(404, 'Không tìm thấy dữ liệu theo yêu cầu.');
        }

        /**
         * update album status
         */
        if ($media->isDisable()) {
            $media->setStatusEnable();
        } else {
            $media->setStatusDisable();
        }
        $media->save();

        return response()->json([
            'success' => true,
            'message' => trans('common.update_data_successfully'),
        ]);
    }
}
