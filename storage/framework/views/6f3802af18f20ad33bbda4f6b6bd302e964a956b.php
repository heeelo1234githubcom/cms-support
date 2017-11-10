<?php
/* @var \Illuminate\Support\Collection|\App\Models\Menu[] $menu */
/* @var \App\Models\Menu $item */
?>

<div class="header-nav-main header-nav-main-effect-1 header-nav-main-sub-effect-1 collapse">
    <nav>
        <ul class="nav nav-pills" id="mainNav">

            <li>
                <a href="<?php echo e(route('home_page')); ?>">
                    Trang chủ
                </a>
            </li>

            <?php if($menu->count()): ?>
                <?php $__currentLoopData = $menu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <?php $hasSubs = !!($item->subs->count()); ?>

                    <li class="<?php echo e($item->getLiClasses($hasSubs)); ?>">
                        <a class="<?php echo e($item->dropDownClass($hasSubs)); ?>" href="<?php echo e($item->getUrl()); ?>">
                            <?php echo e($item->title); ?>

                        </a>

                        <?php if($hasSubs): ?>
                            <?php echo $item->getSubMenu(); ?>

                        <?php endif; ?>
                    </li>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>

            <li>
                <a href="<?php echo e(route('promotion_page')); ?>">
                    Khuyến mãi
                </a>
            </li>

            <li class="dropdown">
                <a href="#" class="dropdown-toggle">
                    Hình ảnh & Video
                </a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo e(route('photo_page')); ?>"><i class="fa fa-file-image-o"></i> Hình ảnh</a></li>
                    <li><a href="<?php echo e(route('video_page')); ?>"><i class="fa fa-file-video-o"></i> Video</a></li>
                </ul>
            </li>

            <li>
                <a href="<?php echo e(route('contact_page')); ?>">
                    Liên hệ
                </a>
            </li>

        </ul>
    </nav>
</div>
