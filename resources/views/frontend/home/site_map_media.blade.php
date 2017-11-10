
<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ route('video_page') }}</loc>
        <lastmod>{{ $lastModify }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>{{ $priority }}</priority>
    </url>
    <url>
        <loc>{{ route('photo_page') }}</loc>
        <lastmod>{{ $lastModify }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>{{ $priority }}</priority>
    </url>
    @foreach($items as $item)
    <url>
        <loc>{{ $item->getUrl() }}</loc>
        <lastmod>{{ $lastModify }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>{{ $priority }}</priority>
    </url>
    @endforeach
</urlset>
