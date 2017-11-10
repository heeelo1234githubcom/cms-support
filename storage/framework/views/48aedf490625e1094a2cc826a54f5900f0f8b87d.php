
<?php if($menu->count()): ?>
    <?php if($title): ?><h4 class="heading-primary"><?php echo e($title); ?></h4><?php endif; ?>
    <ul class="nav nav-list mb-xlg">
        <?php $__currentLoopData = $menu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <li>
            <a href="<?php echo e($item->url); ?>"><?php echo e($item->title); ?></a>

            <?php echo $item->getSubSideBarMenu(); ?>


        </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
<?php endif; ?>
