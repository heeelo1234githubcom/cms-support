<sitemapindex xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/siteindex.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <sitemap>
        <loc>{{ route('site_map_misc') }}</loc>
        <lastmod>{{ $lastModify }}</lastmod>
    </sitemap>
    <sitemap>
        <loc>{{ route('site_map_service') }}</loc>
        <lastmod>{{ $lastModify }}</lastmod>
    </sitemap>
    <sitemap>
        <loc>{{ route('site_map_promotion') }}</loc>
        <lastmod>{{ $lastModify }}</lastmod>
    </sitemap>
    <sitemap>
        <loc>{{ route('site_map_media') }}</loc>
        <lastmod>{{ $lastModify }}</lastmod>
    </sitemap>
    <sitemap>
        <loc>{{ route('site_map_page') }}</loc>
        <lastmod>{{ $lastModify }}</lastmod>
    </sitemap>
</sitemapindex>
