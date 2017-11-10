<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SlideFormRequest;
use App\Models\Slide;
use App\Repositories\SlideRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class SlideController extends Controller
{
    /**
     * @param SlideFormRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(SlideFormRequest $request)
    {
        $data = $request->all();
        $slide = null;

        /* @var $slide Slide */
        if (isset($data['slide_id'])) {
            if ( !$slide = app('slide')->getById($data['slide_id'])) {
                abort(404, 'Không tìm thấy slide theo yêu cầu.');
            }
        }

        /* upload slide file */
        $mediaFile = '';
        if (isset($data['path'])) {

            /* @var $file UploadedFile */
            $file = $data['path'];

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

        if ($slide) {

            /* update exist slide */
            $slide->update([
                'title' => $data['title'],
                'path' => $mediaFile ? $mediaFile : $slide->path,
                'status' => $data['status'],
            ]);

        } else {

            /* create new slide */
            Slide::create([
                'title' => $data['title'],
                'path' => $mediaFile,
                'status' => $data['status'],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => trans('common.update_data_successfully'),
            'reset' => !$data['slide_id']
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listSlides(Request $request)
    {
        /* @var $repository SlideRepository */
        $repository = app('slide');

        $pageLength = (int) $request->input('length', 20);

        $pageIndex = $request->input('start', 0);
        $pageIndex = (int) ceil($pageIndex / $pageLength) + 1;

        if ( !$pageIndex) {
            $pageIndex = 1;
        }

        /* sort */
        $sorts = ['title', 'path', 'status', 'slide_id'];
        $sort = $request->input('order', []);
        if ($sort) {

            $sort = $sort[0];
            $sort['column'] = $sorts[$sort['column']];
        }

        /* search */
        $searchQuery = $request->input('search.value');

        $with = [];
        $condition = [];

        /* get slides */
        /* @var $slides Collection|Slide[]|LengthAwarePaginator */
        $slides = $repository->getRecords($pageIndex, $pageLength, $searchQuery, $sort, $with, $condition);

        $items = [];
        $totalRows = $slides->total();

        if ($slides->count()) {

            /**
             * check for import data
             */
            foreach ($slides as $slide) {

                $items[] = [
                    'title' => $slide->title,
                    'path' => $slide->getFile(),
                    'status' => $slide->getStatusText(),
                    'option' => $slide->getOptions()
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
        /* @var $slide Slide */
        if ( !$slide = app('slide')->getById($id)) {
            abort(404, 'Không tìm thấy slide theo yêu cầu.');
        }

        return response()->json([
            'success' => true,
            'data' => [
                'slide_id' => $slide->slide_id,
                'title' => $slide->title,
                'path' => $slide->path,
                'status' => $slide->status
            ]
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeStatus($id)
    {
        /* @var $slide Slide */
        if ( !$slide = app('slide')->getById($id)) {
            abort(404, 'Không tìm thấy slide theo yêu cầu.');
        }

        /**
         * update album status
         */
        if ($slide->isDisable()) {
            $slide->setStatusEnable();
        } else {
            $slide->setStatusDisable();
        }
        $slide->save();

        return response()->json([
            'success' => true,
            'message' => trans('common.update_data_successfully'),
        ]);
    }
}
