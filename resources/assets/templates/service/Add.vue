<template>
    <main-layout>

		<div class="px-content">
			<div class="page-header">
				<h1>
					<i class="page-header-icon fa fa-plus"></i>
					{{ header }}
				</h1>
			</div>
			<div class="row">

				<form ref="formService"  @submit.prevent="onSaveService" :action="saveServiceUrl" method="post" autocomplete="off">
					<div class="col-md-12">

						<div class="panel">

							<div class="panel-body">

								<div class="row">
									<div class="col-md-6">
										<fieldset class="form-group">
											<label for="input-title">{{ labelTitle }}</label>
											<input v-on:keyup="makeSlug" v-on:blur="makeSlug" name="title" id="input-title" type="text" class="form-control" :placeholder="labelTitle">
										</fieldset>
									</div>

									<div class="col-md-6">
										<fieldset class="form-group">
											<label for="input-slug">{{ labelSlug }}</label>
											<input v-on:keyup="makeSlug" v-on:blur="makeSlug" ref="slug" name="slug" id="input-slug" type="text" class="form-control" :placeholder="labelSlug">
										</fieldset>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6">
										<fieldset class="form-group">
											<label for="input-title">Dịch vụ cha</label>
											<select data-allow-clear="true" name="parent_id" ref="parent_selection" id="input-parent"></select>
										</fieldset>
									</div>

									<div class="col-md-6">
										<fieldset class="form-group">
											<fieldset class="form-group">
												<label class="custom-file">{{ labelCover }}
													<input accept="image/*" name="cover" type="file" class="custom-file-input">
													<span class="custom-file-control form-control"> {{ chooseFile }}</span>
												</label>
											</fieldset>
										</fieldset>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6">

										<fieldset class="form-group">
											<label class="m-b-10">Hiển thị tại trang chủ</label>
											<div>
												<label class="custom-control custom-radio radio-inline">
													<input type="radio" name="show_at_home" value="yes" class="custom-control-input">
													<span class="custom-control-indicator"></span>
													Có
												</label>
												<label class="custom-control custom-radio radio-inline">
													<input type="radio" name="show_at_home" value="no" class="custom-control-input" checked>
													<span class="custom-control-indicator"></span>
													Không
												</label>
											</div>
										</fieldset>

									</div>

									<div class="col-md-6">

										<fieldset class="form-group">
											<label class="m-b-10">{{ labelStatus }}</label>
											<div>
												<label class="custom-control custom-radio radio-inline">
													<input type="radio" name="status" value="enable" class="custom-control-input" checked>
													<span class="custom-control-indicator"></span>
													{{ statusEnable }}
												</label>
												<label class="custom-control custom-radio radio-inline">
													<input type="radio" name="status" value="disable" class="custom-control-input">
													<span class="custom-control-indicator"></span>
													{{ statusDisable }}
												</label>
											</div>
										</fieldset>

									</div>
								</div>

								<fieldset class="form-group m-b-0">
									<label for="input-content">{{ labelContent }}</label>
									<textarea ref="summerNote" name="content" id="input-content" class="form-control textarea-large"></textarea>
								</fieldset>
							</div>

						</div>
					</div>

					<div class="col-md-12 text-right">
						<button :data-loading-text="loadingText" type="submit" ref="submitButton" class="btn btn-lg btn-primary mt15">{{ addService }}</button>
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
            return Application.languages['service']['add'];
		},

		mounted () {
            this.$root.fixedMenu = false;
			this.initSummerNote();

			/* init select 2*/
            let self = this;
            this.$parentBox = $( this.$refs['parent_selection'] ).select2({
                placeholder: 'Chọn dịch vụ cha',
                language: {
                    inputTooShort: function () {
                        return 'Xin vui lòng nhập tên dịch vụ muốn tìm.';
                    },
                    searching: function () {
                        return 'Đang tìm dịch vụ...';
                    },
                    noResults: function () {
                        return 'Không tìm thấy hoặc chưa có dịch vụ nào.';
                    },
                    loadingMore: function () {
                        return 'Đang lấy thêm dịch vụ...';
                    }
                },
                ajax: {
                    url: self.$data['getParentServiceUrl'],
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
                            length: 10,
                            searchBox: true
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

            initSummerNote () {
				/* init summer note */
                $( this.$refs['summerNote'] ).summernote({
                    height: 450,
                    toolbar: [
                        ['fontstyle', ['style', 'fontsize']],
                        ['style', ['bold', 'italic', 'underline', 'clear']],
                        ['font', ['strikethrough', 'superscript', 'subscript']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['insert', ['picture', 'link', 'video', 'table', 'hr']],
                        ['history', ['undo', 'redo']],
                        ['misc', ['codeview', 'fullscreen', 'help']],
                    ],
                });
			},

            onSaveService ($e) {

                $e.preventDefault();

                if (this.loading) {
                    return false;
                }

				/* show loading */
                this.showLoading();

                $('.has-error').removeClass('has-error');
                $('.form-message').remove();

                // submit data
                let $formData = new FormData( this.$refs['formService'] );
                $formData.delete('files');

                let self = this;
                this.$http.post(this.$data['saveServiceUrl'], $formData).then(
                    function ($response) {

                        if (typeof $response.body.message !== 'undefined') {

                            /**
                             * show notify
                             */
                            self.$parent.$emit('showNotify', $response.body.message, {
                                type: 'notice'
                            });
                        }

                        /* reset form */
                        self.$refs['formService'].reset();
                        $( self.$refs['summerNote'] ).summernote('code', '');

                        let option = new Option('', '', true, true);
                        self.$parentBox.append(option);
                        self.$parentBox.trigger('change');

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
            },

            makeSlug ($e) {
                this.$root.makeSlug($e, this.$refs['slug']);
			}
		}
    }
</script>