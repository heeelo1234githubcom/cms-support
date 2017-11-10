<?php $__env->startSection('main_content'); ?>

    <div class="blog-posts">

        <h2><?php echo e($title); ?></h2>

        <?php $__currentLoopData = $subs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <article class="post post-large <?php if($loop->last): ?> no-border <?php endif; ?>">
                <div class="post-content">

                    <h2 class="font-size-medium"><a href="<?php echo e(route('service_detail', ['slug' => $sub->slug])); ?>">&raquo; <?php echo e($sub->title); ?></a></h2>
                    <p class="line-height-27"><?php echo e($sub->getDescription()); ?></p>

                </div>
            </article>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontend.layouts.main_content', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>