<?php
/* @var $services \Illuminate\Support\Collection|\App\Models\Service[] */
?>
<?php if($services->count()): ?>

<div class="container m-t-20">

    <h2 class="text-primary m-b-10">Dịch vụ Nha Khoa</h2>

    <div class="row">
        <ul class="blog-list sort-destination p-none">

            <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <li class="col-sm-4 col-md-4 p-md isotope-item">
                    <div class="blog-item">
                        <a href="<?php echo e(route('service_detail', ['slug' => $service->slug])); ?>" class="text-decoration-none">
                            <span class="thumb-info thumb-info-lighten m-b-10">
                                <span class="thumb-info-wrapper m-none">
                                    <img data-plugin-lazyload data-plugin-options="{'effect' : 'fadeIn'}" data-original="<?php echo e($service->getPublicCover()); ?>" class="img-responsive" alt="<?php echo e($service->title); ?>">
                                </span>
                            </span>
                            <span class="blog-item-content">
                                <span class="category text-uppercase font-weight-semibold"><?php echo e($service->title); ?></span>
                                
                            </span>
                        </a>
                    </div>
                </li>

                <?php if(($loop->iteration % 3) == 0 && ($loop->iteration != $services->count())): ?>
                    </ul>
                    <ul class="blog-list sort-destination p-none">
                <?php endif; ?>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </ul>

    </div>
</div>
<?php endif; ?>
