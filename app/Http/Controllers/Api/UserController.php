<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\UserFormRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class UserController extends Controller
{
    /**
     * @param UpdateProfileRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile(UpdateProfileRequest $request)
    {
        /* @var User $user */
        $user = auth()->user();
        $user->name = $request->input('name');

        if ($request->has('newPassword')) {
            $user->password = bcrypt($request->input('newPassword'));
        }

        $user->save();

        if ($request->hasFile('avatar')) {

            /* @var UploadedFile $avatar */
            $avatar = $request->file('avatar');

            $currentDate = (new Carbon())->format('Y/m/d');
            $extension = $avatar->getClientOriginalExtension();
            $filename = str_slug(substr($avatar->getClientOriginalName(), 0, -3)) . '.' . $extension;

            $path = storage_path('app/public/' . $currentDate);

            $i = 1;
            $checkFilename = $filename;
            while(file_exists($path . '/' . $checkFilename)) {
                $checkFilename = $i . '-' . $filename;
                $i++;
            }

            $avatar->move($path, $checkFilename);

            /* resize avatar */
            \Image::make($path . '/' . $checkFilename)
                ->resize(75, 75)
                ->save($path . '/75x75_' . $checkFilename);

            /* update user avatar */
            $user->updateInfo([
                'avatar' => '/storage/' . $currentDate . '/75x75_' . $checkFilename
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => trans('common.update_data_successfully')
        ]);
    }

    /**
     * @param UserFormRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(UserFormRequest $request)
    {
        $data = $request->all();
        $user = null;

        /* @var $user User */
        if (isset($data['user_id'])) {
            if ( !$user = app('user')->getById($data['user_id'])) {
                abort(404, 'Không tìm thấy người dùng theo yêu cầu.');
            }
        }

        if ($user) {

            if ($user->user_id != 1) {

                /* update exist user */
                $user->update([
                    'name' => $data['name'],
                    'level' => $data['level'],
                    'password' => $data['password'] ? bcrypt($data['password']) : $user->password,
                    'status' => $data['status'],
                ]);
            }

        } else {

            /* create new user */
            User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'level' => $data['level'],
                'password' => bcrypt($data['password']),
                'status' => $data['status'],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => trans('common.update_data_successfully'),
            'reset' => !$data['user_id']
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listUsers(Request $request)
    {
        /* @var $repository UserRepository */
        $repository = app('user');

        $pageLength = (int) $request->input('length', 20);

        $pageIndex = $request->input('start', 0);
        $pageIndex = (int) ceil($pageIndex / $pageLength) + 1;

        if ( !$pageIndex) {
            $pageIndex = 1;
        }

        /* sort */
        $sorts = ['name', 'email', 'level', 'status', 'user_id'];
        $sort = $request->input('order', []);
        if ($sort) {

            $sort = $sort[0];
            $sort['column'] = $sorts[$sort['column']];
        }

        /* search */
        $searchQuery = $request->input('search.value');

        $with = [];

        /* get users */
        /* @var $users Collection|User[]|LengthAwarePaginator */
        $users = $repository->getRecords($pageIndex, $pageLength, $searchQuery, $sort, $with);

        $items = [];
        $totalRows = $users->total();

        if ($users->count()) {

            /**
             * check for import data
             */
            foreach ($users as $user) {

                $items[] = [
                    'name' => $user->name,
                    'email' => $user->email,
                    'level' => $user->getLevel(),
                    'status' => $user->getStatusText(),
                    'option' => $user->getOptions()
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
        /* @var $user User */
        if ( !$user = app('user')->getById($id)) {
            abort(404, 'Không tìm thấy người dùng theo yêu cầu.');
        }

        return response()->json([
            'success' => true,
            'data' => $user->toArray()
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeStatus($id)
    {
        /* @var $user User */
        if ( !$user = app('user')->getById($id)) {
            abort(404, 'Không tìm thấy người dùng theo yêu cầu.');
        }

        /**
         * update user status
         */
        if ($user->user_id != 1) {
            if ($user->isDisable()) {
                $user->setStatusEnable();
            } else {
                $user->setStatusDisable();
            }
            $user->save();
        }

        return response()->json([
            'success' => true,
            'message' => trans('common.update_data_successfully'),
        ]);
    }
}
