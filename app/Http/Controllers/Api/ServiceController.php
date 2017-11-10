<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceFormRequest;
use App\Models\Service;
use App\Models\ServiceComment;
use App\Repositories\ServiceCommentRepository;
use App\Repositories\ServiceRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Symfony\Component\DomCrawler\Crawler;

class ServiceController extends Controller
{
    /**
     * @param ServiceFormRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(ServiceFormRequest $request)
    {
        $data = $request->all();
        $service = null;

        /* @var $service Service */
        if (isset($data['service_id'])) {
            if ( !$service = app('service')->getById($data['service_id'])) {
                abort(404, 'Không tìm thấy dịch vụ theo yêu cầu.');
            }
        }

        /* @var $dom Crawler */
        $data['content'] = uploadContentImage($data['content'], $request);

        if ($service) {

            /* update exist service */
            $service->update([
                'title' => $data['title'],
                'slug' => $data['slug'],
                'parent_id' => isset($data['parent_id']) ? $data['parent_id'] : null,
                'show_at_home' => $data['show_at_home'],
                'content' => $data['content'],
                'status' => $data['status'],
            ]);

        } else {

            /* create new service */
            $service = Service::create([
                'title' => $data['title'],
                'slug' => $data['slug'],
                'parent_id' => isset($data['parent_id']) ? $data['parent_id'] : null,
                'show_at_home' => $data['show_at_home'],
                'content' => $data['content'],
                'status' => $data['status'],
            ]);
        }

        /* check cover */
        if (isset($data['cover'])) {

            /* @var $cover UploadedFile */
            $cover = $data['cover'];

            $currentDate = (new Carbon())->format('Y/m/d');
            $extension = $cover->getClientOriginalExtension();
            $filename = str_slug(substr($cover->getClientOriginalName(), 0, -3)) . '.' . $extension;

            $path = storage_path('app/public/' . $currentDate);

            $i = 1;
            $checkFilename = $filename;
            while(file_exists($path . '/' . $checkFilename)) {
                $checkFilename = $i . '-' . $filename;
                $i++;
            }

            $cover->move($path, $checkFilename);

            /* update user avatar */
            $service->updateMeta([
                'cover' => '/storage/' . $currentDate . '/' . $checkFilename
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
    public function listServices(Request $request)
    {
        /* @var $repository ServiceRepository */
        $repository = app('service');

        $pageLength = (int) $request->input('length', 20);

        $pageIndex = $request->input('start', 0);
        $pageIndex = (int) ceil($pageIndex / $pageLength) + 1;

        if ( !$pageIndex) {
            $pageIndex = 1;
        }

        /* sort */
        $sorts = ['title', 'slug', 'cover', 'status', 'service_id'];
        $sort = $request->input('order', []);
        if ($sort) {

            $sort = $sort[0];
            $sort['column'] = $sorts[$sort['column']];
        }

        /* search */
        $searchQuery = $request->input('search.value');
        $searchBox = $request->input('searchBox');
        $currentServiceId = $request->input('currentServiceId');

        $with = ['meta'];

        /* get services */
        /* @var $services Collection|Service[]|LengthAwarePaginator */
        $services = $repository->getRecords($pageIndex, $pageLength, $searchQuery, $sort, $with);

        $items = [];
        $totalRows = $services->total();

        if ($services->count()) {

            /**
             * check for import data
             */
            foreach ($services as $service) {

                if ($searchBox) {

                    if ($service->service_id != $currentServiceId && !$service->parent_id) {
                        $items[] = [
                            'text' => $service->title,
                            'id' => $service->service_id
                        ];
                    }

                } else {

                    $items[] = [
                        'title' => $service->title,
                        'slug' => $service->slug,
                        'status' => $service->getStatusText(),
                        'cover' => $service->getCover(),
                        'option' => $service->getOptions()
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
        $serviceId = $request->input('service_id');

        /* @var $service Service */
        if ( !$service = app('service')->getById($serviceId)) {
            abort(404, 'Không tìm thấy dịch vụ theo yêu cầu.');
        }

        $data = $service->toArray();
        if ( !$service->show_at_home) {
            $data['show_at_home'] = 'no';
        }

        if ($service->parent_id) {
            $data['parentTitle'] = $service->parent->title;
        }

        $data['has_sub'] = $service->subs->count() ? 'yes' : 'no';

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function remove($id)
    {
        /* @var $service Service */
        if ( !$service = app('service')->getById($id)) {
            abort(404, 'Không tìm thấy dịch vụ theo yêu cầu.');
        }

        $service->setStatusDeleted();
        $service->save();

        return response()->json([
            'success' => true,
            'message' => 'Xóa dịch vụ thành công!'
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getComments(Request $request)
    {
        $id = $request->input('service_id');

        $service = null;
        if ($id) {
            /* @var $service Service */
            if ( !$service = app('service')->getById($id)) {
                abort(404, 'Không tìm thấy dịch vụ theo yêu cầu.');
            }
        }

        /* @var $repository ServiceCommentRepository */
        $repository = app('serviceComment');
        $pageLength = (int) $request->input('length', 20);

        $pageIndex = $request->input('start', 0);
        $pageIndex = (int) ceil($pageIndex / $pageLength) + 1;

        if ( !$pageIndex) {
            $pageIndex = 1;
        }

        /* sort */
        $sorts = [
            'comment_name',
            'comment_email',
            'comment_phone',
            'comment_content',
            'status',
            'comment_id'
        ];
        $sort = $request->input('order', []);
        if ($sort) {

            $sort = $sort[0];
            $sort['column'] = $sorts[$sort['column']];
        }

        /* search */
        $searchQuery = $request->input('search.value');

        $with = ['service'];

        $condition = [];
        if ($id) {
            $condition['service_id'] = $id;
        }

        /* get services */
        /* @var $comments Collection|ServiceComment[]|LengthAwarePaginator */
        $comments = $repository->getRecords($pageIndex, $pageLength, $searchQuery, $sort, $with, $condition);

        $items = [];
        $totalRows = $comments->total();

        if ($comments->count()) {

            /**
             * check for import data
             */
            foreach ($comments as $comment) {

                $items[] = [
                    'comment_name' => $comment->comment_name,
                    'comment_email' => $comment->comment_email,
                    'comment_phone' => $comment->comment_phone,
                    'comment_content' => $comment->comment_content,
                    'status' => $comment->getStatusText(),
                    'option' => $comment->getOptions()
                ];
            }
        }

        return response()->json([
            'data' => $items,
            'draw' => $request->input('draw', $pageIndex),
            'recordsTotal' => $totalRows,
            'recordsFiltered' => $totalRows,
            'serviceTitle' => ($id) ? $service->title : ''
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeCommentStatus($id)
    {
        /* @var $comment ServiceComment */
        if ( !$comment = app('serviceComment')->getById($id)) {
            abort(404, 'Không tìm thấy comment theo yêu cầu.');
        }

        if ($comment->isDisable()) {
            $comment->setStatusEnable();
        } else {
            $comment->setStatusDisable();
        }

        $comment->save();

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật dữ liệu thành công!'
        ]);
    }
}
