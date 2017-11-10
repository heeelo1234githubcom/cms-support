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

				<form ref="formProfile"  @submit.prevent="onUpdateProfile" :action="updateProfileUrl" method="post" autocomplete="off">
					<div class="col-md-6">

						<div class="panel">
							<div class="panel-heading">
								<div class="panel-title"><strong>{{ generalInfo }}</strong></div>
							</div>

							<div class="panel-body">
								<fieldset class="form-group">
									<label>{{ email }}</label>
									<input disabled type="text" class="form-control" :value="$root.user.email">
								</fieldset>

								<fieldset class="form-group">
									<label for="input-name">{{ name }}</label>
									<input name="name" id="input-name" type="text" class="form-control" :placeholder="name" :value="$root.user.name">
								</fieldset>

								<div class="row">
									<div class="col-md-8">

										<fieldset class="form-group">

											<label class="custom-file">{{ avatar }}
												<input accept="image/*" name="avatar" type="file" class="custom-file-input" v-on:change="onChangeAvatarFile">
												<span class="custom-file-control form-control"> {{ chooseFile }}</span>
											</label>

										</fieldset>

									</div>

									<div class="col-md-4">
										<img v-if="$root.user.avatar" ref="avatarPreview" class="avatar-preview" :src="$root.user.avatar" />
										<img v-else ref="avatarPreview" class="avatar-preview" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" />
									</div>
								</div>
							</div>

						</div>
					</div>

					<div class="col-md-6">

						<div class="panel">
							<div class="panel-heading">
								<div class="panel-title"><strong>{{ changePassword }}</strong></div>
							</div>

							<div class="panel-body">
								<fieldset class="form-group">
									<label for="input-old-password">{{ oldPassword }}</label>
									<input name="oldPassword" id="input-old-password" type="password" class="form-control" :placeholder="oldPassword">
								</fieldset>

								<fieldset class="form-group">
									<label for="input-new-password">{{ newPassword }}</label>
									<input name="newPassword" id="input-new-password" type="password" class="form-control" :placeholder="newPassword">
								</fieldset>

								<fieldset class="form-group">
									<label for="input-password-confirm">{{ confirmPassword }}</label>
									<input name="confirmPassword" id="input-password-confirm" type="password" class="form-control" :placeholder="confirmPassword">
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
            return Application.languages.profile;
		},

		methods: {
            onUpdateProfile ($e) {

                $e.preventDefault();

                if (this.loading) {
                    return false;
                }

				/* show loading */
                this.showLoading();

                $('.has-error').removeClass('has-error');
                $('.form-message').remove();

                // submit data
                let $formData = new FormData( this.$refs['formProfile'] );

                let self = this;
                this.$http.post(this.$data['updateProfileUrl'], $formData).then(
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

            onChangeAvatarFile ($e) {
                let reader  = new FileReader();
                let self = this;

                reader.addEventListener("load", function () {
                    self.$refs['avatarPreview'].src = reader.result;
                }, false);

                reader.readAsDataURL($e.target.files[0]);
			}
		}
    }
</script>