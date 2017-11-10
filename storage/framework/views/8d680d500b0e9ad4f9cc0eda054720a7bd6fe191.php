
<?php if($promotions->count()): ?>
<div class="tabs mb-xlg">
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab"><i class="fa fa-star"></i> Khuyến mãi</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active">
            <ul class="simple-post-list">

                <?php $__currentLoopData = $promotions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $promotion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li>
                    <div class="post-info">
                        <a href="<?php echo e(route('promotion_detail', ['slug' => $promotion->slug])); ?>"><?php echo e($promotion->title); ?></a>
                    </div>
                </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </ul>
        </div>
    </div>
</div>
<?php endif; ?>
