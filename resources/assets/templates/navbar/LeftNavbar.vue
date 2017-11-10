<template>

    <nav class="px-nav px-nav-fixed px-nav-left px-nav-animate px-nav-collapse">
        <button type="button" class="px-nav-toggle" data-toggle="px-nav">
            <span class="px-nav-toggle-arrow"></span>
            <span class="navbar-toggle-icon"></span>
            <span class="px-nav-toggle-label font-size-11"> {{ hideMenu }}</span>
        </button>

        <ul class="px-nav-content">

            <li class="px-nav-item px-nav-dropdown">
                <a href="#">
					<i class="px-nav-icon fa fa-cogs"></i>
					<span class="px-nav-label">{{ setting }}</span>
				</a>

                <ul class="px-nav-dropdown-menu">
                    <li class="px-nav-item">
						<v-link href="/manage/setting/general">
							<i class="fa fa-cogs"></i> &nbsp;
							<span class="px-nav-label">{{ settingGeneral }}</span>
						</v-link>
					</li>

					<li class="px-nav-item">
						<v-link href="/manage/setting/menu">
							<i class="ion-navicon-round"></i> &nbsp;
							<span class="px-nav-label">{{ menu }}</span>
						</v-link>
					</li>
                </ul>
            </li>

			<li class="px-nav-item px-nav-dropdown">
				<a href="#">
					<i class="px-nav-icon ion-ios-paper-outline"></i>
					<span class="px-nav-label">Trang</span>
				</a>

				<ul class="px-nav-dropdown-menu">
					<li class="px-nav-item">
						<v-link href="/manage/page">
							<i class="ion-navicon-round"></i> &nbsp;
							<span class="px-nav-label">{{ pageList }}</span>
						</v-link>
					</li>

					<li class="px-nav-item">
						<v-link href="/manage/page/add">
							<i class="fa fa-plus"></i> &nbsp;
							<span class="px-nav-label">Thêm trang mới</span>
						</v-link>
					</li>
				</ul>
            </li>

			<li class="px-nav-item">
				<v-link href="/manage/schedule">
					<i class="px-nav-icon fa fa-calendar"></i>
					<span class="px-nav-label">{{ scheduleList }}</span>
				</v-link>
			</li>

			<li class="px-nav-item">
				<v-link href="/manage/contact">
					<i class="px-nav-icon fa fa-address-card-o"></i>
					<span class="px-nav-label">{{ contactList }}</span>
				</v-link>
			</li>

			<li class="px-nav-item px-nav-dropdown">

				<a href="#">
					<i class="px-nav-icon fa fa-cart-plus"></i>
					<span class="px-nav-label">Khuyễn mãi</span>
				</a>

				<ul class="px-nav-dropdown-menu">
					<li class="px-nav-item">
						<v-link href="/manage/promotion">
							<i class="ion-navicon-round"></i> &nbsp;
							<span class="px-nav-label">Danh sách khuyến mãi</span>
						</v-link>
					</li>

					<li class="px-nav-item">
						<v-link href="/manage/promotion-user">
							<i class="fa fa-users"></i> &nbsp;
							<span class="px-nav-label">Người đăng ký</span>
						</v-link>
					</li>
				</ul>
			</li>

            <li class="px-nav-item px-nav-dropdown">
                <a href="#">
					<i class="px-nav-icon fa fa-cubes"></i>
					<span class="px-nav-label">{{ services }}</span>
				</a>

                <ul class="px-nav-dropdown-menu">
					<li class="px-nav-item">
						<v-link href="/manage/service">
							<i class="ion-navicon-round"></i> &nbsp;
							<span class="px-nav-label">{{ list }}</span>
						</v-link>
					</li>

					<!--<li class="px-nav-item">
						<v-link href="/manage/service/comment">
							<i class="fa fa-comments"></i> &nbsp;
							<span class="px-nav-label">{{ commentList }}</span>
						</v-link>
					</li>-->

					<li class="px-nav-item">
						<v-link href="/manage/service/add">
							<i class="ion-ios-plus-outline"></i> &nbsp;
							<span class="px-nav-label">{{ addService }}</span>
						</v-link>
					</li>
                </ul>
            </li>
            <li class="px-nav-item px-nav-dropdown">
                <a href="#">
					<i class="px-nav-icon fa fa-picture-o"></i>
					<span class="px-nav-label">{{ mediaLibrary }}</span>
				</a>

                <ul class="px-nav-dropdown-menu">
					<li class="px-nav-item">
						<v-link href="/manage/media/album">
							<i class="fa fa-book"></i> &nbsp;
							<span class="px-nav-label">{{ album }}</span>
						</v-link>
					</li>

					<li class="px-nav-item">
						<v-link href="/manage/media/photo">
							<i class="fa fa-file-image-o"></i> &nbsp;
							<span class="px-nav-label">{{ photoList }}</span>
						</v-link>
					</li>

					<li class="px-nav-item">
						<v-link href="/manage/media/video">
							<i class="fa fa-file-video-o"></i> &nbsp;
							<span class="px-nav-label">{{ videoList }}</span>
						</v-link>
					</li>

					<li class="px-nav-item">
						<v-link href="/manage/media/slide">
							<i class="fa fa-file-image-o"></i> &nbsp;
							<span class="px-nav-label">Slide</span>
						</v-link>
					</li>
                </ul>
            </li>

			<li class="px-nav-item">
				<v-link href="/manage/user">
					<i class="px-nav-icon fa fa-users"></i>
					<span class="px-nav-label">{{ userList }}</span>
				</v-link>
			</li>
        </ul>
    </nav>

</template>

<script>

    import VLink from './../components/VLink.vue';

    export default {
        components: {
            VLink,
        },

        data () {
            return Application.languages.leftNavbarData;
        },
    }
</script>
