<?php $__env->startSection('main_content'); ?>

    <div class="blog-posts">

        <h2 class="m-b-15"><?php echo e($title); ?></h2>

        <?php if($media->count()): ?>
        <div class="row">
            <ul class="blog-list sort-destination p-none">

                <?php $__currentLoopData = $media; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $video): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <li class="col-sm-4 col-md-4 p-md isotope-item">
                        <div class="blog-item">
                            <a href="<?php echo e($video->getVideoUrl()); ?>" class="text-decoration-none" data-toggle="lightbox" data-width="960">
                                <span class="thumb-info thumb-info-lighten m-b-10">
                                    <span class="thumb-info-wrapper m-none">
                                        <img data-plugin-lazyload data-plugin-options="{'effect' : 'fadeIn'}" data-original="<?php echo e($video->getVideoImage()); ?>" class="img-responsive" alt="<?php echo e($video->title); ?>">
                                    </span>
                                </span>
                            </a>
                            <span class="blog-item-content">
                                <span class="category"><?php echo e($video->title); ?></span>
                            </span>
                        </div>
                    </li>

                    <?php if(($loop->iteration % 3) == 0 && ($loop->iteration != $media->count())): ?>
                        </ul>
                        <ul class="blog-list sort-destination p-none">
                    <?php endif; ?>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </ul>

            <?php echo e($media->links()); ?>

        </div>
        <?php endif; ?>

        <?php if( !$media->count()): ?>
            Chưa có dữ liệu.
        <?php endif; ?>

    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontend.layouts.main_content', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>