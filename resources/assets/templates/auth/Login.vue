<template>

	<login-wrapper>
		<div class="page-signin-modal modal">

			<div class="modal-dialog">

				<div class="modal-content">

					<form ref="formLogin"  @submit.prevent="onLogin" :action="loginUrl" method="post" class="p30" autocomplete="off">
						<h4 class="mt0 mb30 text-center font-weight-semi-bold">{{ loginTitle }}</h4>

						<fieldset :class="{'page-signin-form-group form-group form-group-lg': true, 'has-error': errors.has('email') }">
							<div class="page-signin-icon text-muted">
								<i class="fa fa-envelope"></i>
							</div>
							<input type="text" v-validate="'required|email'" name="email" class="page-signin-form-control form-control" :placeholder="email">
							<small v-show="errors.has('email')" class="form-message light">{{ errors.first('email') }}</small>
						</fieldset>

						<fieldset :class="{'page-signin-form-group form-group form-group-lg': true, 'has-error': errors.has('password') }">
							<div class="page-signin-icon text-muted">
								<i class="fa fa-key"></i>
							</div>
							<input type="password" v-validate="'required'" name="password" class="page-signin-form-control form-control" :placeholder="password">
							<small v-show="errors.has('password')" class="form-message light">{{ errors.first('password') }}</small>
						</fieldset>

						<div class="clearfix m-bt-10">
							<label class="custom-control custom-checkbox pull-xs-left font-size-13">
								<input type="checkbox" checked name="rememberLogin" class="custom-control-input">
								<span class="custom-control-indicator"></span>
								{{ remember }}
							</label>
							<!-- <v-link href="/manage/forgot-password" class="font-size-13 text-muted pull-xs-right">{{ forgotPassword }}</v-link> -->
						</div>

						<button :data-loading-text="loadingText" type="submit" ref="submitButton" class="btn btn-block btn-lg btn-primary mt15">{{ login }}</button>

						<!--<div class="login-or">
							<hr class="hr-or">
							<span class="span-or">{{ or }}</span>
							<div class="social-buttons">
								<a :href="fbLoginUrl" class="btn btn-info">
									<i class="fa fa-facebook"></i> - {{ loginWithFb }}
								</a>
								<a :href="googleLoginUrl" class="btn btn-danger">
									<i class="fa fa-google"></i> - {{ loginWithGoogle }}
								</a>
							</div>
						</div>-->

					</form>

				</div>

			</div>

		</div>
	</login-wrapper>

</template>

<script>

    import LoginWrapper from '../../templates/LoginWrapper.vue';
    import VLink from '../components/VLink.vue';

    export default {

        components: {
            LoginWrapper,
            VLink
        },

        data () {
            return Application.languages.formLogin;
        },

        methods: {

            /**
             * submit login
             * @param $e event
             */
            onLogin ($e) {

                $e.preventDefault();

                if (this.loading) {
                    return false;
                }

                let self = this;
                this.$validator.validateAll().then(function (success) {

                    if (success) {

						/* show loading */
                        self.showLoading();

                        // submit data
                        let $formData = new FormData( self.$refs.formLogin );

                        self.$http.post(self.loginUrl, $formData).then(
                            function ($response) {
                                localStorage.setItem('remember-login', ('on' === $formData.get('rememberLogin')));

                                /**
                                 * set login
                                 */
                                self.$parent.$emit('userHasLoggedIn', $response.body.user);

                                self.hideLoading();

                                /**
                                 * go to dashboard
                                 */
                                self.$parent.go('/manage');

                            }, function ($response) {

                                if (typeof $response.body.error !== 'undefined') {

                                    /**
                                     * check error
                                     */
                                    if ($response.body.error === 'invalid_credentials') {
                                        $response.body.error = 'Thông tin đăng nhập không chính xác!';
                                    }

                                    /**
                                     * show notify
                                     */
                                    self.$parent.$emit('showNotify', $response.body.error, {
                                        type: 'error'
                                    });
                                }

                                self.hideLoading();
                            }
                        );
                    }
                }, function () {
                    console.log('Form Login invalid.')
                });
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
