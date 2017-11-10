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

    <div id="google-map" class="google-map"></div>

    <div class="container">

        <div class="row">
            <div class="col-md-6">

                <h2 class="mb-sm mt-sm">Liên hệ</h2>

                <form class="ajax-form-submit" action="<?php echo e(route('contact_submit')); ?>" method="post">
                    <?php echo e(csrf_field()); ?>

                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-6">
                                <label>Tên của bạn *</label>
                                <input type="text" maxlength="100" placeholder="Tên của bạn" class="form-control" name="contact_name">
                            </div>
                            <div class="col-md-6">
                                <label>Số điện thoại</label>
                                <input type="text" maxlength="20" placeholder="Số điện thoại" class="form-control" name="contact_phone">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-12">
                                <label>Email *</label>
                                <input type="text" maxlength="150" placeholder="Email của bạn" class="form-control" name="contact_email">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-12">
                                <label>Nội dung *</label>
                                <textarea placeholder="Nội dung liên hệ" maxlength="5000" rows="10" class="form-control" name="contact_content"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary btn-md mb-xlg" data-loading-text="Đang xử lý...">Gửi liên hệ</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-md-6">
                <h4 class="heading-primary">Thông tin</h4>
                <?php echo config('webConfigs.contact_info'); ?>


            </div>

        </div>

    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-script'); ?>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAvyQoGModCTQjNWnYFte14tzGBzUUP9MA"></script>
    <script>
        function initMap() {
            var address = {lat: 21.0134783, lng: 105.8225248};
            var center = {lat: 21.0137783, lng: 105.8225248};
            var map = new google.maps.Map(document.getElementById('google-map'), {
                zoom: 18,
                center: center
            });

            var contentString = '<div class="map-info-content"><strong>Nha Khoa Giá Tốt</strong><?php echo get_contact_info(); ?></div>';

            var infowindow = new google.maps.InfoWindow({
                content: contentString
            });

            var marker = new google.maps.Marker({
                position: address,
                map: map,
                title: 'Nha Khoa Giá Tốt'
            });

            marker.addListener('click', function(event) {
                infowindow.open(map, marker);
            });
            infowindow.open(map, marker);
        }
        initMap();
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontend.layouts.main_content', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>