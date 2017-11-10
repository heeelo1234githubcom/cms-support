<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Repositories\ScheduleRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ScheduleController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listSchedules(Request $request)
    {
        /* @var $repository ScheduleRepository */
        $repository = app('schedule');

        $pageLength = (int) $request->input('length', 20);

        $pageIndex = $request->input('start', 0);
        $pageIndex = (int) ceil($pageIndex / $pageLength) + 1;

        if ( !$pageIndex) {
            $pageIndex = 1;
        }

        /* sort */
        $sorts = ['schedule_name', 'schedule_email', 'schedule_phone', 'schedule_time', 'status', 'schedule_id'];
        $sort = $request->input('order', []);
        if ($sort) {

            $sort = $sort[0];
            $sort['column'] = $sorts[$sort['column']];
        }

        /* search */
        $searchQuery = $request->input('search.value');

        $with = [];

        /* get schedules */
        /* @var $schedules Collection|Schedule[]|LengthAwarePaginator */
        $schedules = $repository->getRecords($pageIndex, $pageLength, $searchQuery, $sort, $with);

        $items = [];
        $totalRows = $schedules->total();

        if ($schedules->count()) {

            /**
             * check for import data
             */
            $carbon = new Carbon();
            foreach ($schedules as $schedule) {

                $items[] = [
                    'schedule_name' => $schedule->schedule_name,
                    'schedule_email' => $schedule->schedule_email,
                    'schedule_phone' => $schedule->schedule_phone,
                    'schedule_time' => $carbon->setTimestamp(strtotime($schedule->schedule_time))->format('H:i - d/m/Y'),
                    'status' => $schedule->getViewStatusText(),
                    'option' => $schedule->getOptions()
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
        /* @var $schedule Schedule */
        if ( !$schedule = app('schedule')->getById($id)) {
            abort(404, 'Không tìm thấy lịch khám theo yêu cầu.');
        }

        $content = '<div>Tên người đặt khám: <strong>' .$schedule->schedule_name. '</strong></div>';
        $content .= '<div>Email: <strong>' .$schedule->schedule_email. '</strong></div>';
        $content .= '<div>Số điện thoại: <strong>' .$schedule->schedule_phone. '</strong></div>';
        $content .= '<div>Thời gian khám: <strong>' .(new Carbon())->setTimestamp(strtotime($schedule->schedule_time))->format('H:i - d/m/Y'). '</strong></div>';
        $content .= '<div>Nội dung: ' .$schedule->schedule_content . '</div>';

        if ($schedule->isDisable()) {
            $schedule->setStatusEnable();
            $schedule->save();
        }

        return response()->json([
            'success' => true,
            'modal' => [
                'title' => 'Chi tiết lịch khám',
                'content' => $content
            ]
        ]);
    }
}
