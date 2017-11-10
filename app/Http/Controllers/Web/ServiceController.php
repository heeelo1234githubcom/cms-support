<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Support\Collection;

class ServiceController extends Controller
{
    /**
     * @param string $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function detail($slug)
    {
        /* @var $service Service */
        $service = app('service')->getByColumns([
            'slug' => $slug,
            'status' => 'enable'
        ]);

        if ( !$service) {
            abort(404, 'Không tìm thấy trang web theo yêu cầu.');
        }

        $data = [
            'title' => $service->title,
            'service' => $service,
            'breadcrumb' => 'Thông tin & Dịch vụ'
        ];

        /* @var $subs Collection|Service[] */
        $subs = $service->subs()->whereStatus('enable')->get();
        if ($subs->count()) {
            $data['subs'] = $subs;

            return view('frontend.service.list_subs', $data);
        }

        return view('frontend.service.detail', $data);
    }
}
