<?php

use \Symfony\Component\DomCrawler\Crawler;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Illuminate\Http\Request;

if ( !function_exists('appVersion')) {

    /**
     * @return string
     */
    function appVersion()
    {
        return (env('APP_ENV') == 'local') ? time() : '1.0.0';
    }
}

if ( !function_exists('isDevelopment')) {

    /**
     * @return bool
     */
    function isDevelopment()
    {
        return env('APP_ENV') !== 'production';
    }
}

if ( !function_exists('getAppLanguage')) {

    /**
     * @return string
     */
    function getAppLanguage()
    {
        $locale = config('app.locale');
        $file = base_path('resources/lang/' . $locale . '/backend.php');

        if ( !file_exists($file)) {
            abort(404, 'File language not found.');
        }

        $language = require($file);

        return $language;
    }
}

if ( !file_exists('getApplicationConfigs')) {

    /**
     * @return string
     */
    function getApplicationConfigs()
    {
        return json_encode([
            'baseApiUrl' => route('application_api'),
            'locale' => config('app.locale'),
            'languages' => getAppLanguage()
        ]);
    }
}

if ( !function_exists('uploadContentImage')) {

    /**
     * @param string $content
     * @param Request $request
     * @return string
     */
    function uploadContentImage($content, $request)
    {
        /* @var $dom Crawler */
        $dom = new Crawler();
        $dom->addHtmlContent($content);

        $currentDate = (new Carbon())->format('Y/m/d');
        $path = storage_path('app/public/' . $currentDate);

        if ( !is_dir($path)) {
            if (false === @mkdir($path, 0777, true) && !is_dir($path)) {
                throw new FileException(sprintf('Unable to create the "%s" directory', $path));
            }
        }

        $dom->filter('img')->each(function (Crawler $image) use ($path, $currentDate, $request) {

            $imgSrc = $image->attr('src');

            /* get image filename */
            $filename = $image->attr('data-filename');

            if (!$filename) {
                $url = parse_url($imgSrc);

                if (!isset($url['host']) || ($url['host'] === $request->getHost())) {
                    return;
                }
            }

            $image = $image->getNode(0);

            if ($filename) {

                $image->removeAttribute('data-filename');

                $filename = explode('.', $filename);

                $extension = strtolower(array_pop($filename));
                $filename = strtolower(str_slug(implode('-', $filename))) . '.' . $extension;

            } else {

                $filename = explode('/', $imgSrc);
                $filename = array_pop($filename);

                $filename = explode('.', $filename);

                $extension = strtolower(array_pop($filename));
                $filename = strtolower(str_slug(implode('-', $filename))) . '.' . $extension;
            }

            $i = 1;
            $checkFilename = $filename;
            while (file_exists($path . '/' . $checkFilename)) {
                $checkFilename = $i . '-' . $filename;
                $i++;
            }

            /* save image */
            try {

                \Image::make($imgSrc)
                    ->save($path . '/' . $checkFilename);

            } catch (\Exception $e) {
                return;
            }

            /* update image src */
            $image->setAttribute('src', '/storage/' . $currentDate . '/' . $checkFilename);
        });

        /* remove content body tag */
        $content = $dom->html();

        $content = str_replace('<body>', '', $content);
        $content = str_replace('</body>', '', $content);

        return $content;
    }
}

if ( !function_exists('img')) {

    /**
     * @param string $image
     * @return string
     */
    function img($image = '')
    {
        return '/assets/frontend/images/' . $image;
    }
}

if ( !function_exists('getIntroUrl')) {

    /**
     * @return string
     */
    function getIntroUrl()
    {
        if ($introId = config('webConfigs.intro_id')) {

            /* @var $page \App\Models\Page */
            if ($page = app('page')->getById($introId)) {
                return route('page_detail', ['slug' => $page->slug]);
            }
        }

        return '';
    }
}

if ( !function_exists('getMenu')) {

    /**
     * @return mixed
     */
    function getMenu()
    {
        return app('menu')->getMenuTop();
    }
}

if ( !function_exists('listHomeService')) {

    /**
     * @return mixed
     */
    function listHomeServices()
    {
        return app('service')->getHomeList();
    }
}

if ( !function_exists('get_contact_info')) {

    /**
     * @return mixed
     */
    function get_contact_info() {
        $info = config('webConfigs.contact_info');

        $info = str_replace("\r", '', $info);
        $info = str_replace("\n", '', $info);

        return $info;
    }
}
