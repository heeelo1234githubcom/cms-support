
<ul class="dropdown-menu">
    <?php $__currentLoopData = $subs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

        <li><a href="<?php echo e($sub->getUrl()); ?>"><?php echo e($sub->title); ?></a></li>

    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</ul>