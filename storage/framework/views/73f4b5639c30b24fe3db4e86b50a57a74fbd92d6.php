<div class="col-md-3">
    <aside class="sidebar">

        <?php echo app('menu')->getSideBarMenu(); ?>


        <?php echo app('promotion')->getSideBarPromotion(); ?>


        <?php if(config('webConfigs.social_url')): ?>

            <div class="fb-page" data-href="<?php echo e(config('webConfigs.social_url')); ?>" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true">
                <blockquote cite="<?php echo e(config('webConfigs.social_url')); ?>" class="fb-xfbml-parse-ignore">
                    <a href="<?php echo e(config('webConfigs.social_url')); ?>">Facebook</a>
                </blockquote>
            </div>

        <?php endif; ?>

    </aside>
</div>