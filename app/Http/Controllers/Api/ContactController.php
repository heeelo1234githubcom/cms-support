<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Repositories\ContactRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ContactController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listContacts(Request $request)
    {
        /* @var $repository ContactRepository */
        $repository = app('contact');

        $pageLength = (int) $request->input('length', 20);

        $pageIndex = $request->input('start', 0);
        $pageIndex = (int) ceil($pageIndex / $pageLength) + 1;

        if ( !$pageIndex) {
            $pageIndex = 1;
        }

        /* sort */
        $sorts = ['contact_name', 'contact_email', 'contact_phone', 'status', 'contact_id'];
        $sort = $request->input('order', []);
        if ($sort) {

            $sort = $sort[0];
            $sort['column'] = $sorts[$sort['column']];
        }

        /* search */
        $searchQuery = $request->input('search.value');

        $with = [];

        /* get contacts */
        /* @var $contacts Collection|Contact[]|LengthAwarePaginator */
        $contacts = $repository->getRecords($pageIndex, $pageLength, $searchQuery, $sort, $with);

        $items = [];
        $totalRows = $contacts->total();

        if ($contacts->count()) {

            /**
             * check for import data
             */
            foreach ($contacts as $contact) {

                $items[] = [
                    'contact_name' => $contact->contact_name,
                    'contact_email' => $contact->contact_email,
                    'contact_phone' => $contact->contact_phone,
                    'status' => $contact->getViewStatusText(),
                    'option' => $contact->getOptions()
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
        /* @var $contact Contact */
        if ( !$contact = app('contact')->getById($id)) {
            abort(404, 'Không tìm thấy liên hệ theo yêu cầu.');
        }

        $content = '<div>Tên người đặt khám: <strong>' .$contact->contact_name. '</strong></div>';
        $content .= '<div>Email: <strong>' .$contact->contact_email. '</strong></div>';
        $content .= '<div>Số điện thoại: <strong>' .$contact->contact_phone. '</strong></div>';
        $content .= '<div>Nội dung: ' .$contact->contact_content . '</div>';

        if ($contact->isDisable()) {
            $contact->setStatusEnable();
            $contact->save();
        }

        return response()->json([
            'success' => true,
            'modal' => [
                'title' => 'Chi tiết liên hệ',
                'content' => $content
            ]
        ]);
    }
}
