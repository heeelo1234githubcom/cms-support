<?php

/* web title */
$webTitle = config('webConfigs.web_title');
if (isset($title)) {
    $webTitle .= ' - ' . $title;
};

/* app name */
$appName = config('app.name');

/* keyword & description */
$webKeyword = isset($keyword) ? $keyword : config('webConfigs.web_keyword');
$webDescription = isset($description) ? $description : config('webConfigs.web_description');

/* cover image */
$cover = route('home_page') . config('webConfigs.website_cover');

/* canonical */
$canonical = URL::full();
?>
<!DOCTYPE html>
<html lang="<?php echo e(config('app.locale')); ?>">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <title><?php echo e($webTitle); ?></title>

    <meta name="theme-color" content="#0088cc">
    <meta name="msapplication-navbutton-color" content="#0088cc">
    <meta name="apple-mobile-web-app-status-bar-style" content="#0088cc">

    <meta name="keywords" content="<?php echo e($webKeyword); ?>" />
    <meta name="description" content="<?php echo e($webDescription); ?>" />
    <meta name="author" content="<?php echo e($appName); ?>" />
    <meta name="generator" content="<?php echo e($appName); ?>" />

    <meta property="og:title" content="<?php echo e($webTitle); ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="<?php echo e($canonical); ?>" />
    <meta property="og:site_name" content="<?php echo e($appName); ?>" />
    <meta property="og:description" content="<?php echo e($webDescription); ?>" />
    <?php if(config('webConfigs.facebook_app_id')): ?>
    <meta property="fb:app_id" content="<?php echo e(config('webConfigs.facebook_app_id')); ?>" />
    <?php endif; ?>
    <meta property="og:image" content="<?php echo e($cover); ?>" />
    <meta itemprop="image" content="<?php echo e($cover); ?>" />
    <link rel="image_src" href="<?php echo e($cover); ?>" />
    <link rel="canonical" href="<?php echo e($canonical); ?>" />

    <?php if(config('webConfigs.google_index')): ?>
    <meta name="robots" content="<?php echo e(config('webConfigs.google_index')); ?>" />
    <?php endif; ?>

    <!-- Favicon -->
    <link rel="shortcut icon" href="/assets/frontend/images/favicon.png" type="image/x-icon" />
    <link rel="apple-touch-icon" href="/assets/frontend/images/apple-touch-icon.png" />

    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <!-- Vendor CSS -->
    <link href="<?php echo e(mix('/assets/frontend/css/style.css')); ?>" rel="stylesheet" type="text/css" />

    <!-- Head Libs -->
    <script src="/assets/frontend/js/modernizr/modernizr.min.js"></script>

</head>
<body>
    <div class="body">

        <?php echo $__env->make('frontend.layouts.components.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

        <div role="main" class="main">

            <?php echo $__env->yieldContent('content'); ?>

        </div>

        <?php echo $__env->make('frontend.layouts.components.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    </div>

    <!-- JS -->
    <script src="<?php echo e(mix('/assets/frontend/js/app.js')); ?>"></script>
    <?php echo $__env->yieldContent('page-script'); ?>

    <?php if(config('webConfigs.tracking_code')): ?>
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
        ga('create', '<?php echo e(config('webConfigs.tracking_code')); ?>', 'auto');
        ga('send', 'pageview');
    </script>
     <?php endif; ?>

    <?php if(config('webConfigs.chat_code')): ?>
    <!--Start of Zendesk Chat Script-->
    <script type="text/javascript">
        window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
            d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
        _.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute("charset","utf-8");
            $.src="https://v2.zopim.com/?<?php echo e(config('webConfigs.chat_code')); ?>";z.t=+new Date;$.
                type="text/javascript";e.parentNode.insertBefore($,e)})(document,"script");
    </script>
    <!--End of Zendesk Chat Script-->
    <?php endif; ?>

    <?php if(config('webConfigs.facebook_app_id') || config('webConfigs.social_url')): ?>
    <div id="fb-root"></div>
    <script>(function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.9&appId=<?php echo e(config('webConfigs.facebook_app_id')); ?>";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>
    <?php endif; ?>

</body>
</html>