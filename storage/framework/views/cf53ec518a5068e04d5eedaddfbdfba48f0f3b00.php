<?php $__env->startSection('main_content'); ?>

    <div class="blog-posts single-post">

        <article class="post post-large blog-single-post">

            <div class="post-content">

                <h2><?php echo e($page->title); ?></h2>

                <div class="line-height-27">
                    <?php echo $page->content; ?>

                </div>

                <?php if(config('webConfigs.facebook_app_id') || config('webConfigs.social_url')): ?>

                    <div class="post-block post-share">
                        <div class="fb-comments" data-href="<?php echo e(URL::current()); ?>" width="100%" data-numposts="10"></div>
                    </div>

                    <div class="fb-share-button right" data-href="<?php echo e(URL::current()); ?>" data-layout="button_count" data-size="large" data-mobile-iframe="false">
                        <a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo e(urlencode(URL::current())); ?>">Share</a>
                    </div>

                <?php endif; ?>

            </div>

        </article>

    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontend.layouts.main_content', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>