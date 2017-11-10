<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateConfigRequest;
use App\Models\Page;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;

class SettingController extends Controller
{
    /**
     * @param UpdateConfigRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateConfigRequest $request)
    {
        $configs = $request->all();

        if (isset($configs['website_cover'])) {

            /* @var UploadedFile $cover */
            $cover = $configs['website_cover'];

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

            /* resize avatar */
            \Image::make($path . '/' . $checkFilename)
                ->save($path . '/' . $checkFilename);

            $configs['website_cover'] = '/storage/' . $currentDate . '/' . $checkFilename;
        }

        app('appConfig')->updateConfigs($configs);

        return response()->json([
            'success' => true,
            'message' => trans('common.update_data_successfully')
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function get()
    {
        $defaults = [
            'web_title' => '',
            'hotline_number' => '',
            'web_description' => '',
            'web_keyword' => '',
            'contact_info' => '',
            'tracking_code' => '',
            'social_url' => '',
            'website_cover' => '',
            'facebook_app_id' => '',
            'home_id' => '',
            'intro_id' => '',
            'chat_code' => '',
            'menu_right_title' => '',
            'menu_bottom_title' => '',
            'home_slide' => 'default',
            'google_index' => 'noindex',
        ];

        $configs = app('appConfig')->getConfigs();
        $configs = array_merge($defaults, $configs);

        /* @var $page Page */
        if ($configs['home_id']) {
            if ($page = app('page')->getById($configs['home_id'])) {
                $configs['homeTitle'] = $page->title;
            }
        }

        if ($configs['intro_id']) {
            if ($page = app('page')->getById($configs['intro_id'])) {
                $configs['introTitle'] = $page->title;
            }
        }

        return response()->json([
            'success' => true,
            'configs' => $configs
        ]);
    }
}
