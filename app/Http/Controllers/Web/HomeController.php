<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactFormRequest;
use App\Http\Requests\NewsletterFormRequest;
use App\Models\Contact;
use App\Models\Media;
use App\Models\Page;
use App\Models\Promotion;
use App\Models\PromotionUser;
use App\Models\Service;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        /* @var $page Page */
        $page = null;
        if ($homePageId = config('webConfigs.home_id')) {
            $page = app('page')->getById($homePageId);
        }

        $data = [
            'title' => 'Trang chủ',
            'content' => ($page && $page->isEnable()) ? $page->content : ''
        ];

        return view('frontend.home.index', $data);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function contact()
    {
        return view('frontend.home.contact', [
            'title' => 'Liên hệ',
        ]);
    }

    /**
     * @param ContactFormRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function contactSubmit(ContactFormRequest $request)
    {
        $data =  $request->all();
        $data['status'] = 'disable';

        try {

            Contact::create($data);

        } catch (\Exception $e) {}

        return response()->json([
            'success' => true,
            'message' => 'Đã gửi liên hệ thành công.'
        ]);
    }

    /**
     * @param NewsletterFormRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function newsletter(NewsletterFormRequest $request)
    {
        try {

            PromotionUser::create([
                'email' => $request->input('newsletter_email'),
                'phone' => '',
                'name' => '',
                'content' => ''
            ]);

        } catch (\Exception $e) {}

        return response()->json([
            'success' => true,
            'message' => 'Đã đăng ký nhận tin thành công.'
        ]);
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function siteMap()
    {
        $data = [
            'baseUrl' => \URL::to('/'),
            'lastModify' => date('Y-m-d\TH:i:s+00:00')
        ];

        $view = view('frontend.home.site_map', $data)->render();
        $view = '<?xml version="1.0" encoding="UTF-8"?>'
            . '<?xml-stylesheet type="text/xsl" href="' .\URL::to('/'). '/sitemap.xsl"?>' . "\n"
            . $view;

        return response($view, 200, [
            'Content-Type' => 'text/xml'
        ]);
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function siteMapMisc()
    {
        $data = [
            'baseUrl' => \URL::to('/'),
            'lastModify' => date('Y-m-d\TH:i:s+00:00')
        ];

        $view = view('frontend.home.site_map_misc', $data)->render();
        $view = '<?xml version="1.0" encoding="UTF-8"?>'
            . '<?xml-stylesheet type="text/xsl" href="' .\URL::to('/'). '/sitemap.xsl"?>' . "\n"
            . $view;

        return response($view, 200, [
            'Content-Type' => 'text/xml'
        ]);
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function siteMapService()
    {
        $data = [
            'baseUrl' => \URL::to('/'),
            'lastModify' => date('Y-m-d\TH:i:s+00:00'),
            'items' => Service::whereStatus('enable')->get(),
            'priority' => '0.9'
        ];

        $view = view('frontend.home.site_map_url', $data)->render();
        $view = '<?xml version="1.0" encoding="UTF-8"?>'
            . '<?xml-stylesheet type="text/xsl" href="' .\URL::to('/'). '/sitemap.xsl"?>' . "\n"
            . $view;

        return response($view, 200, [
            'Content-Type' => 'text/xml'
        ]);
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function siteMapPromotion()
    {
        $data = [
            'baseUrl' => \URL::to('/'),
            'lastModify' => date('Y-m-d\TH:i:s+00:00'),
            'items' => Promotion::whereStatus('enable')->get(),
            'priority' => '0.8'
        ];

        $view = view('frontend.home.site_map_url', $data)->render();
        $view = '<?xml version="1.0" encoding="UTF-8"?>'
            . '<?xml-stylesheet type="text/xsl" href="' .\URL::to('/'). '/sitemap.xsl"?>' . "\n"
            . $view;

        return response($view, 200, [
            'Content-Type' => 'text/xml'
        ]);
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function siteMapMedia()
    {
        $data = [
            'baseUrl' => \URL::to('/'),
            'lastModify' => date('Y-m-d\TH:i:s+00:00'),
            'items' => app('album')->getPhotoAlbums(),
            'priority' => '0.7'
        ];

        $view = view('frontend.home.site_map_media', $data)->render();
        $view = '<?xml version="1.0" encoding="UTF-8"?>'
            . '<?xml-stylesheet type="text/xsl" href="' .\URL::to('/'). '/sitemap.xsl"?>' . "\n"
            . $view;

        return response($view, 200, [
            'Content-Type' => 'text/xml'
        ]);
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function siteMapPage()
    {
        $data = [
            'baseUrl' => \URL::to('/'),
            'lastModify' => date('Y-m-d\TH:i:s+00:00'),
            'items' => Page::whereStatus('enable')->get(),
            'priority' => '0.9'
        ];

        $view = view('frontend.home.site_map_url', $data)->render();
        $view = '<?xml version="1.0" encoding="UTF-8"?>'
            . '<?xml-stylesheet type="text/xsl" href="' .\URL::to('/'). '/sitemap.xsl"?>' . "\n"
            . $view;

        return response($view, 200, [
            'Content-Type' => 'text/xml'
        ]);
    }
}
