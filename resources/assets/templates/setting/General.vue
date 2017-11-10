<template>
    <main-layout>

		<div class="px-content">
			<div class="page-header">
				<h1>
					<i class="page-header-icon fa fa-pencil"></i>
					{{ title }}
				</h1>
			</div>
			<div class="row">

				<form ref="formSetting"  @submit.prevent="onUpdateSetting" :action="updateSettingUrl" method="post" autocomplete="off">
					<div class="col-md-12">

						<div class="panel">

							<div class="panel-body">

								<div class="row">
									<div class="col-md-6">
										<fieldset class="form-group">
											<label for="input-title">{{ webTitle }}</label>
											<input name="web_title" id="input-title" type="text" class="form-control" :placeholder="webTitle" :value="$data['configs']['web_title']">
										</fieldset>
									</div>

									<div class="col-md-6">
										<fieldset class="form-group">
											<label for="input-hotline">{{ hotline }}</label>
											<input name="hotline_number" id="input-hotline" type="text" class="form-control" :placeholder="hotline" :value="$data['configs']['hotline_number']">
										</fieldset>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6">
										<fieldset class="form-group">
											<label for="input-home-id">Trang chủ</label>
											<select data-allow-clear="true" name="home_id" ref="home_selection" id="input-home-id"></select>
										</fieldset>
									</div>

									<div class="col-md-6">
										<fieldset class="form-group">
											<label for="input-intro-id">Trang giới thiệu</label>
											<select data-allow-clear="true" name="intro_id" ref="intro_selection" id="input-intro-id"></select>
										</fieldset>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6">
										<fieldset class="form-group">
											<label for="input-tracking-code">{{ trackingCode }}</label>
											<input name="tracking_code" id="input-tracking-code" type="text" class="form-control" :placeholder="trackingCode" :value="$data['configs']['tracking_code']">
										</fieldset>
									</div>

									<div class="col-md-6">
										<fieldset class="form-group">
											<label for="input-social">{{ socialUrl }}</label>
											<input name="social_url" id="input-social" type="text" class="form-control" :placeholder="socialUrl" :value="$data['configs']['social_url']">
										</fieldset>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6">
										<fieldset class="form-group">
											<label for="input-app-id">{{ facebookAppId }}</label>
											<input name="facebook_app_id" id="input-app-id" type="text" class="form-control" :placeholder="facebookAppId" :value="$data['configs']['facebook_app_id']">
										</fieldset>
									</div>

									<div class="col-md-6">

										<fieldset class="form-group">
											<label class="custom-file">{{ cover }}
												<input accept="image/*" name="website_cover" type="file" class="custom-file-input">
												<span class="custom-file-control form-control"> Chọn file ảnh...</span>
											</label>

										</fieldset>
									</div>
								</div>

								<div class="row">
									<div class="col-md-4">
										<fieldset class="form-group">
											<label for="input-chat-code">Zopim Chat Code</label>
											<input name="chat_code" id="input-chat-code" type="text" class="form-control" placeholder="Zopim Chat Code" :value="$data['configs']['chat_code']">
										</fieldset>
									</div>

									<div class="col-md-4">
										<fieldset class="form-group">
											<label class="m-b-10">Slide Trang chủ</label>
											<div>
												<label class="custom-control custom-radio radio-inline">
													<input type="radio" name="home_slide" value="none" class="custom-control-input" :checked="$data['configs']['home_slide'] == 'none'">
													<span class="custom-control-indicator"></span>
													Không chạy
												</label>
												<label class="custom-control custom-radio radio-inline">
													<input type="radio" name="home_slide" value="default" class="custom-control-input" :checked="$data['configs']['home_slide'] == 'default'">
													<span class="custom-control-indicator"></span>
													Mặc định
												</label>
												<label class="custom-control custom-radio radio-inline">
													<input type="radio" name="home_slide" value="slide" class="custom-control-input" :checked="$data['configs']['home_slide'] == 'slide'">
													<span class="custom-control-indicator"></span>
													Chạy từ Slide
												</label>
											</div>
										</fieldset>
									</div>

									<div class="col-md-4">
										<fieldset class="form-group">
											<label class="m-b-10">Cho phép Google Index</label>
											<div>
												<label class="custom-control custom-radio radio-inline">
													<input type="radio" name="google_index" value="index,follow" class="custom-control-input" :checked="$data['configs']['google_index'] == 'index,follow'">
													<span class="custom-control-indicator"></span>
													Có
												</label>
												<label class="custom-control custom-radio radio-inline">
													<input type="radio" name="google_index" value="noindex" class="custom-control-input" :checked="$data['configs']['google_index'] == 'noindex'">
													<span class="custom-control-indicator"></span>
													Không
												</label>
											</div>
										</fieldset>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6">
										<fieldset class="form-group">
											<label for="input-menu-right-title">Tiêu đề Menu phải</label>
											<input name="menu_right_title" id="input-menu-right-title" type="text" class="form-control" placeholder="Tiêu đề Menu phải" :value="$data['configs']['menu_right_title']">
										</fieldset>
									</div>

									<div class="col-md-6">
										<fieldset class="form-group">
											<label for="input-menu-bottom-title">Tiêu đề Menu Bottom</label>
											<input name="menu_bottom_title" id="input-menu-bottom-title" type="text" class="form-control" placeholder="Tiêu đề Menu Bottom" :value="$data['configs']['menu_bottom_title']">
										</fieldset>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6">
										<fieldset class="form-group">
											<label for="input-description">{{ description }}</label>
											<textarea name="web_description" id="input-description" type="text" class="form-control textarea-small" :placeholder="description">{{ $data['configs']['web_description'] }}</textarea>
										</fieldset>
									</div>

									<div class="col-md-6">
										<fieldset class="form-group">
											<label for="input-keywords">{{ keywords }}</label>
											<textarea name="web_keyword" id="input-keywords" type="text" class="form-control textarea-small" :placeholder="keywords">{{ $data['configs']['web_keyword'] }}</textarea>
										</fieldset>
									</div>
								</div>

								<fieldset class="form-group m-b-0">
									<label for="input-contact-info">{{ contactInfo }}</label>
									<textarea ref="summerNote" name="contact_info" id="input-contact-info" type="text" class="form-control textarea-medium" :placeholder="contactInfo">{{ $data['configs']['contact_info'] }}</textarea>
								</fieldset>
							</div>

						</div>
					</div>

					<div class="col-md-12 text-right">
						<button :data-loading-text="loadingText" type="submit" ref="submitButton" class="btn btn-lg btn-primary mt15">{{ update }}</button>
					</div>

				</form>

			</div>
		</div>

    </main-layout>
</template>

<script>

    import MainLayout from './../App.vue';
    import VLink from './../components/VLink.vue';

    export default {
        components: {
            MainLayout,
            VLink
        },

		data () {
            let $data = Application.languages.settings;
            $data['configs'] = {
                'web_title': '',
                'hotline_number': '',
                'web_description': '',
                'web_keyword': '',
                'contact_info': '',
                'tracking_code': '',
                'social_url': '',
                'website_cover': '',
                'facebook_app_id': '',
                'home_id': '',
                'intro_id': '',
                'chat_code': '',
                'menu_right_title': '',
                'menu_bottom_title': '',
                'home_slide': 'default',
				'google_index': 'noindex'
			};

            return $data;
		},

		created () {
			this.fetchData();
		},

        /*watch: {
            configs: 'fetchData'
        },*/

		mounted () {
            this.$root.fixedMenu = false;

            let self = this;
			/* enable summer note */
            $( self.$refs['summerNote'] ).summernote({
                height: 350,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'table', 'hr']],
                    ['history', ['undo', 'redo', 'codeview']],
                ],
            });

			/* init select 2*/
            this.$homeBox = $( this.$refs['home_selection'] ).select2({
                placeholder: 'Chọn trang chủ...',
                language: {
                    inputTooShort: function () {
                        return 'Xin vui lòng nhập tên trang muốn tìm.';
                    },
                    searching: function () {
                        return 'Đang tìm trang...';
                    },
                    noResults: function () {
                        return 'Không tìm thấy hoặc chưa có trang nào.';
                    },
                    loadingMore: function () {
                        return 'Đang lấy thêm trang...';
                    }
                },
                ajax: {
                    url: self.$data['getPageUrl'],
                    dataType: 'json',
                    type: 'post',
                    delay: 250,
                    'beforeSend': function (request) {
                        request.setRequestHeader("Authorization", localStorage.getItem('jwt-token'));
                    },
                    data: function (params) {
                        return {
                            search: {
                                value: params.term
                            },
                            page: params.page,
                            length: 20,
							comboBox: true
                        };
                    },
                    processResults: function (data, params) {
                        // parse the results into the format expected by Select2
                        // since we are using custom formatting functions we do not need to
                        // alter the remote JSON data, except to indicate that infinite
                        // scrolling can be used
                        params.page = params.page || 1;

                        return {
                            results: data.data
                        };
                    },
                    cache: false
                },
                escapeMarkup: function (markup) { return markup; },
                minimumInputLength: 0
            });

			/* init select 2*/
            this.$introBox = $( this.$refs['intro_selection'] ).select2({
                placeholder: 'Chọn trang giới thiệu...',
                language: {
                    inputTooShort: function () {
                        return 'Xin vui lòng nhập tên trang muốn tìm.';
                    },
                    searching: function () {
                        return 'Đang tìm trang...';
                    },
                    noResults: function () {
                        return 'Không tìm thấy hoặc chưa có trang nào.';
                    },
                    loadingMore: function () {
                        return 'Đang lấy thêm trang...';
                    }
                },
                ajax: {
                    url: self.$data['getPageUrl'],
                    dataType: 'json',
                    type: 'post',
                    delay: 250,
                    'beforeSend': function (request) {
                        request.setRequestHeader("Authorization", localStorage.getItem('jwt-token'));
                    },
                    data: function (params) {
                        return {
                            search: {
                                value: params.term
                            },
                            page: params.page,
                            length: 20,
                            comboBox: true
                        };
                    },
                    processResults: function (data, params) {
                        // parse the results into the format expected by Select2
                        // since we are using custom formatting functions we do not need to
                        // alter the remote JSON data, except to indicate that infinite
                        // scrolling can be used
                        params.page = params.page || 1;

                        return {
                            results: data.data
                        };
                    },
                    cache: false
                },
                escapeMarkup: function (markup) { return markup; },
                minimumInputLength: 0
            });
		},

        destroyed () {
            this.$root.fixedMenu = true;
		},

		methods: {

            fetchData () {
				/* get form data */
                let self = this;
                this.$http.post(this.$data['getSettingUrl']).then(
                    function ($response) {
                        if (typeof $response.body.configs !== 'undefined') {
                            self.configs = $response.body.configs;
                            $( self.$refs['summerNote'] ).summernote('code', $response.body.configs['contact_info']);

                            if ($response.body.configs['home_id']) {
                                let option = new Option($response.body.configs['homeTitle'], $response.body.configs['home_id'], true, true);
                                self.$homeBox.append(option);
                                self.$homeBox.trigger('change');
							}

                            if ($response.body.configs['intro_id']) {
                                let option = new Option($response.body.configs['introTitle'], $response.body.configs['intro_id'], true, true);
                                self.$introBox.append(option);
                                self.$introBox.trigger('change');
                            }
                        }
                    }
                );
			},

            onUpdateSetting ($e) {

                $e.preventDefault();

                if (this.loading) {
                    return false;
                }

				/* show loading */
                this.showLoading();

                $('.has-error').removeClass('has-error');
                $('.form-message').remove();

                // submit data
                let $formData = new FormData( this.$refs['formSetting'] );

                let self = this;
                this.$http.post(this.$data['updateSettingUrl'], $formData).then(
                    function ($response) {

                        if (typeof $response.body.message !== 'undefined') {

                            /**
                             * show notify
                             */
                            self.$parent.$emit('showNotify', $response.body.message, {
                                type: 'notice'
                            });
                        }

                        self.hideLoading();

                    }, function ($response) {

                        if (typeof $response.body.error !== 'undefined') {

                            /**
                             * show notify
                             */
                            self.$parent.$emit('showNotify', $response.body.error, {
                                type: 'error'
                            });
                        }

                        /* show form errors */
                        _.forEach($response.body, function ($message, $errorKey) {
                            $('[name=' +$errorKey+ ']').parent()
                                .addClass('has-error')
                                .append('<small class="form-message light">' +$message[0]+ '</small>');
                        });

                        self.hideLoading();
                    }
                );
			},

            /**
             * show button loading
             */
            showLoading () {
                this.loading = true;
                $( this.$refs['submitButton'] ).button('loading');
            },

            /**
             * hide button loading
             */
            hideLoading () {
                this.loading = false;
                $( this.$refs['submitButton'] ).button('reset');
            }
		}
    }
</script>