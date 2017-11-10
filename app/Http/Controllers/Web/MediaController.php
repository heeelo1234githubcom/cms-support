<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Media;
use App\Models\Page;
use App\Repositories\AlbumRepository;
use App\Repositories\MediaRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class MediaController extends Controller
{
    /**
     * @param string $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function detail($slug)
    {
        /* @var $page Page */
        $page = app('page')->getByColumns([
            'slug' => $slug,
            'status' => 'enable'
        ]);

        if ( !$page) {
            abort(404, 'Không tìm thấy trang web theo yêu cầu.');
        }

        /* check home page */
        $homeId = config('webConfigs.home_id');
        if ($homeId == $page->page_id) {
            return response()->redirectToRoute('home_page', [], 301);
        }

        $data = [
            'title' => $page->title,
            'page' => $page
        ];

        return view('frontend.page.detail', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function photo(Request $request)
    {
        $page = $request->input('page');
        if ($page == 1) {
            return response()->redirectToRoute('photo_page', [], 301);
        }

        if ( !$page) {
            $page = 1;
        }

        $itemPerPage = 15;

        /* @var $repository AlbumRepository */
        $repository = app('album');

        /* @var $albums Collection|Album[] */
        $albums = $repository->getAlbums($page, $itemPerPage);

        if ( !$albums->count()) {
            abort(404);
        }

        return view('frontend.media.photoAlbum', [
            'title' => 'Thư viện hình ảnh',
            'albums' => $albums,
            'breadcrumb' => 'Hình ảnh & Video'
        ]);
    }

    /**
     * @param $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function albumDetail($slug)
    {
        /* @var $album Album */
        $album = app('album')->getByColumns([
            'slug' => $slug,
            'status' => 'enable',
            'type' => 'photo'
        ]);

        if ( !$album) {
            abort(404);
        }

        /* @var $repository MediaRepository */
        $repository = app('media');

        /* @var $media Collection|Media[] */
        $media = $repository->getMediaByAlbum($album);

        return view('frontend.media.photo', [
            'title' => $album->title,
            'breadcrumb' => 'Hình ảnh & Video</li><li><a href="' .route('photo_page'). '">Hình ảnh</a>',
            'media' => $media
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function video(Request $request)
    {
        $page = $request->input('page');
        if ($page == 1) {
            return response()->redirectToRoute('video_page', [], 301);
        }

        if ( !$page) {
            $page = 1;
        }

        $itemPerPage = 15;

        /* @var $repository MediaRepository */
        $repository = app('media');

        /* @var $media Collection|Media[] */
        $media = $repository->getVideos($page, $itemPerPage);

        return view('frontend.media.video', [
            'title' => 'Video',
            'breadcrumb' => 'Hình ảnh & Video',
            'media' => $media
        ]);
    }
}
