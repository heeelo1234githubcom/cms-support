<?php $__env->startSection('content'); ?>

    <section class="page-header page-header-color page-header-primary">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li><a href="<?php echo e(route('home_page')); ?>">Trang chủ</a></li>
                        <?php if(isset($breadcrumb)): ?>
                            <li><?php echo $breadcrumb; ?></li>
                        <?php endif; ?>
                        <li class="active"><?php echo e($title); ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <div class="container">
        <div class="row">
            <div class="col-md-9">

                <?php echo $__env->yieldContent('main_content'); ?>

            </div>

            <?php echo $__env->make('frontend.layouts.components.side_bar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontend.layouts.main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>