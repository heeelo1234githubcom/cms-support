<template>
	<main-layout>

		<div class="px-content">
			<div class="page-header">
				<h1>
					<i class="page-header-icon fa fa-pencil"></i>
					{{ header }}
				</h1>
			</div>
			<div class="row">

				<form ref="formPage"  @submit.prevent="onSavePage" :action="savePageUrl" method="post" autocomplete="off">

					<input name="page_id" type="hidden" :value="$data['pageFormData']['page_id']" />

					<div class="col-md-12">

						<div class="panel">

							<div class="panel-body">

								<div class="row">
									<div class="col-md-6">
										<fieldset class="form-group">
											<label for="input-title">{{ labelTitle }}</label>
											<input v-on:keyup="makeSlug" v-on:blur="makeSlug" name="title" id="input-title" type="text" class="form-control" :placeholder="labelTitle" :value="$data['pageFormData']['title']">
										</fieldset>
									</div>

									<div class="col-md-6">
										<fieldset class="form-group">
											<label for="input-slug">{{ labelSlug }}</label>
											<input v-on:keyup="makeSlug" v-on:blur="makeSlug" ref="slug" name="slug" id="input-slug" type="text" class="form-control" :placeholder="labelSlug" :value="$data['pageFormData']['slug']">
										</fieldset>
									</div>
								</div>

								<div class="row">

									<div class="col-md-6">

										<fieldset class="form-group">
											<label class="m-b-10">{{ labelStatus }}</label>
											<div>
												<label class="custom-control custom-radio radio-inline">
													<input type="radio" name="status" value="enable" class="custom-control-input" :checked="$data['pageFormData']['status'] == 'enable'">
													<span class="custom-control-indicator"></span>
													{{ statusEnable }}
												</label>
												<label class="custom-control custom-radio radio-inline">
													<input type="radio" name="status" value="disable" class="custom-control-input" :checked="$data['pageFormData']['status'] == 'disable'">
													<span class="custom-control-indicator"></span>
													{{ statusDisable }}
												</label>
											</div>
										</fieldset>

									</div>
								</div>

								<fieldset class="form-group m-b-0">
									<label for="input-content">{{ labelContent }}</label>
									<textarea ref="summerNote" name="content" id="input-content" type="text" class="form-control textarea-large">{{ $data['pageFormData']['content'] }}</textarea>
								</fieldset>
							</div>

						</div>
					</div>

					<div class="col-md-12 text-right">
						<button :data-loading-text="loadingText" type="submit" ref="submitButton" class="btn btn-lg btn-primary mt15">{{ save }}</button>
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

            let $data = Application.languages['pageEdit'];
            $data['pageFormData'] = {
                'page_id': '',
                'title': '',
                'slug': '',
                'status': 'enable',
                'content': ''
            };

            return $data;
        },

        mounted () {
            this.$root.fixedMenu = false;
        },

        destroyed () {
            this.$root.fixedMenu = true;
            this.$root.currentObjectId = null;
        },

        created () {
            this.fetchData();
        },

        methods: {

            fetchData () {
				/* get form data */
                let self = this;
                this.$http.post(this.$data['getPageUrl'], {page_id: this.$root['currentObjectId']}).then(
                    function ($response) {
                        if (typeof $response.body.data !== 'undefined') {

                            self.pageFormData = $response.body.data;

                            setTimeout(function () {
                                self.initSummerNote();
                            }, 1000);
                        }
                    },

                    function ($response) {

                        if (typeof $response['body'].error !== 'undefined') {

                            /**
                             * show notify
                             */
                            self.$parent.$emit('showNotify', $response['body'].error, {
                                type: 'error'
                            });

                            self.$root.go('/manage/page');
                        }
                    }
                );
            },

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

            onSavePage ($e) {

                $e.preventDefault();

                if (this.loading) {
                    return false;
                }

				/* show loading */
                this.showLoading();

                $('.has-error').removeClass('has-error');
                $('.form-message').remove();

                // submit data
                let $formData = new FormData( this.$refs['formPage'] );

                let self = this;
                this.$http.post(this.$data['savePageUrl'], $formData).then(
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
            },

            makeSlug ($e) {
                this.$root.makeSlug($e, this.$refs['slug']);
            }
        }
    }
</script>