<sitemapindex xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/siteindex.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <sitemap>
        <loc><?php echo e(route('site_map_misc')); ?></loc>
        <lastmod><?php echo e($lastModify); ?></lastmod>
    </sitemap>
    <sitemap>
        <loc><?php echo e(route('site_map_service')); ?></loc>
        <lastmod><?php echo e($lastModify); ?></lastmod>
    </sitemap>
    <sitemap>
        <loc><?php echo e(route('site_map_promotion')); ?></loc>
        <lastmod><?php echo e($lastModify); ?></lastmod>
    </sitemap>
    <sitemap>
        <loc><?php echo e(route('site_map_media')); ?></loc>
        <lastmod><?php echo e($lastModify); ?></lastmod>
    </sitemap>
    <sitemap>
        <loc><?php echo e(route('site_map_page')); ?></loc>
        <lastmod><?php echo e($lastModify); ?></lastmod>
    </sitemap>
</sitemapindex>
