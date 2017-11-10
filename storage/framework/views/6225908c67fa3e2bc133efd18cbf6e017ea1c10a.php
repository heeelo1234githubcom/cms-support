<footer id="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="newsletter">
                    <h4>Khuyến mãi</h4>
                    <p>Đăng ký nhận tin khuyến mãi.</p>
                    <form action="<?php echo e(route('newsletter_form')); ?>" class="ajax-form-submit" method="post">
                        <?php echo e(csrf_field()); ?>

                        <div class="input-group">
                            <input class="form-control" placeholder="Nhập email của bạn" name="newsletter_email"type="text">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="submit" data-loading-text="...">Gửi</button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-1"></div>
            <div class="col-md-8">
                <div class="contact-details">
                    <h4>Thông tin liên hệ</h4>
                    <?php echo config('webConfigs.contact_info'); ?>

                </div>
            </div>
        </div>
    </div>
    <div class="footer-copyright">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <p>© Copyright 2017 <?php echo e(config('app.name')); ?>. All Rights Reserved.</p>
                </div>
                <div class="col-sm-4">
                    <nav id="sub-menu">
                        <ul>
                            <li><a href="<?php echo e(route('sitemap_page')); ?>">Sitemap</a></li>
                            <li><a href="<?php echo e(route('contact_page')); ?>">Liên hệ</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</footer>