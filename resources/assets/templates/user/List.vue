<template>
    <main-layout>

		<div class="px-content">

			<div class="row">

				<div class="col-md-4">

					<div class="page-header">
						<h1>
							<i :class="{ 'page-header-icon fa fa-plus': !formData.user_id, 'page-header-icon fa fa-pencil': formData.user_id }"></i>
							{{ formHeader }}
						</h1>
					</div>

					<div class="col-md-12" style="margin-top: 10px;">

						<div class="panel">

							<form class="panel-body" ref="formUser"  @submit.prevent="onSaveUser" :action="saveUserUrl" method="post" autocomplete="off">

								<input type="hidden" name="user_id" :value="formData.user_id">

								<fieldset class="form-group">
									<label for="input-name">{{ name }}</label>
									<input name="name" id="input-name" type="text" class="form-control" :placeholder="name" :value="formData.name">
								</fieldset>

								<fieldset class="form-group">
									<label for="input-email">{{ email }}</label>
									<input :disabled="formData.user_id != ''" name="email" id="input-email" type="text" class="form-control" :placeholder="email" :value="formData.email">
								</fieldset>

								<fieldset class="form-group">
									<label for="input-password">{{ password }}</label>
									<input name="password" id="input-password" type="password" class="form-control" :placeholder="password">
								</fieldset>

								<fieldset class="form-group">
									<label for="input-confirm-password">{{ password_confirm }}</label>
									<input name="confirmPassword" id="input-confirm-password" type="password" class="form-control" :placeholder="password_confirm">
								</fieldset>

								<fieldset class="form-group">
									<label class="m-b-10">{{ level }}</label>
									<div>
										<label class="custom-control custom-radio radio-inline">
											<input type="radio" name="level" value="admin" class="custom-control-input" :checked="formData.level == 'admin'">
											<span class="custom-control-indicator"></span>
											{{ levelAdmin }}
										</label>
										<label class="custom-control custom-radio radio-inline">
											<input type="radio" name="level" value="normal" class="custom-control-input" :checked="formData.level == 'normal'">
											<span class="custom-control-indicator"></span>
											{{ levelNormal }}
										</label>
									</div>
								</fieldset>

								<fieldset class="form-group">
									<label class="m-b-10">{{ status }}</label>
									<div>
										<label class="custom-control custom-radio radio-inline">
											<input type="radio" name="status" value="enable" class="custom-control-input" :checked="formData.status == 'enable'">
											<span class="custom-control-indicator"></span>
											{{ statusEnable }}
										</label>
										<label class="custom-control custom-radio radio-inline">
											<input type="radio" name="status" value="disable" class="custom-control-input" :checked="formData.status == 'disable'">
											<span class="custom-control-indicator"></span>
											{{ statusDisable }}
										</label>
									</div>
								</fieldset>

							</form>

						</div>

						<div class="text-right" style="margin-top: 15px;">

							<button v-if="formData.user_id" v-on:click="makeAddForm" class="btn btn-danger btn-small"><i class="fa fa-plus"></i> Thêm người dùng mới</button>
							&nbsp;&nbsp;&nbsp;
							<button v-on:click="onSaveUser" :data-loading-text="loadingText" type="submit" ref="submitButton" class="btn btn-small btn-primary mt15">{{ save }}</button>
						</div>

					</div>

				</div>

				<div class="col-md-8">

					<div class="page-header">
						<h1>
							<i class="page-header-icon fa fa-bars"></i>
							{{ listHeader }}
						</h1>
					</div>
					<div class="row">

						<div class="col-md-12">

							<div class="panel-body no-padding">
								<div class="table-primary">

									<table ref="dataTable" class="table table-striped table-bordered dataTable">
										<thead>
										<tr>
											<th>{{ name }}</th>
											<th>{{ email }}</th>
											<th style="width: 150px;">{{ level }}</th>
											<th class="status-column">{{ status }}</th>
											<th class="option-column">{{ option }}</th>
										</tr>
										</thead>
									</table>

								</div>

							</div>
						</div>
					</div>

				</div>

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
            let $data = Application.languages['user'];
            $data['formData'] = {
                'user_id': '',
                'name': '',
				'email': '',
				'level': 'admin',
				'status': 'enable'
			};

            return $data;
		},

		mounted () {
            this.$root.fixedMenu = false;
            this.initDataTable();
            $('.dataTables_filter input').attr('placeholder', this.$data['searchText']);

            let self = this;
            $(document).on('click', 'a.ajax-update-record', function ($e) {
				$e.preventDefault();

				self.fetchFormData( $(this).attr('href') );

                $(this).blur();
            });
		},

        destroyed () {
            this.$root.fixedMenu = true;

            /* remove click event listener */
            $(document).off('click', 'a.ajax-update-record');
		},

		methods: {
            initDataTable () {

                let self = this;

                self.$root.dataTable = $( this.$refs['dataTable'] )
					.on('processing.dt', function ( e, settings, processing ) {
						if (processing) {
						    $('.dataTables_table_wrapper').addClass('processing');
						}
					})
					.on( 'draw.dt', function () {
                        $('.dataTables_table_wrapper').removeClass('processing');

						/* init tooltip */
                        $('[data-toggle="tooltip"]').tooltip();
					})
					.dataTable({
                        "ajax": {
                            'url': self.$data['getUserUrl'],
                            'type': 'post',
                            'beforeSend': function (request) {
                                request.setRequestHeader("Authorization", localStorage.getItem('jwt-token'));
                            },
                            error: function ($response) {
                                if (typeof $response['responseJSON'] !== 'undefined') {
                                    self.$root['showNotify']($response['responseJSON'].error, {
                                        type: 'error'
                                    });
                                }
                            }
                        },
						//"dom": 'lfrtip',
						"searchDelay": 800,
                        "autoWidth": false,
						"processing": true,
						"serverSide": true,
						"pageLength": 10,
						"ordering": true,
						"order": [[4, 'desc']],
						/*fixedColumns: {
							leftColumns: 3
						},*/
						"language": {
							"lengthMenu":  self.$data['display'] + ' <select>'+
								'<option value="10">10</option>'+
								'<option value="20">20</option>'+
								'<option value="50">50</option>'+
								'<option value="100">100</option>'+
							'</select> ' + self.$data['user'],

							paginate: {
								first: self.$data['firstPage'],
								previous: self.$data['previousPage'],
								next: self.$data['nextPage'],
								last: self.$data['lastPage']
							},

							emptyTable: self.$data['emptyTable'],
							zeroRecords: self.$data['zeroRecords'],
							info: self.$data['info'],
							infoEmpty: '',
							loadingRecords: self.$data['processing'],
							processing: self.$data['processing'],
							search: self.$data['search']

						},
						"columns": [{
							'data': 'name'
						}, {
							'data': 'email'
						}, {
						    'data': 'level'
						}, {
							'data': 'status',
							'className': 'status-column'
						}, {
							'data': 'option',
							'className': 'option-column',
							'orderable': false
						}]
					});

			},

            onSaveUser ($e) {

                $e.preventDefault();

                if (this.loading) {
                    return false;
                }

				/* show loading */
                this.showLoading();

                $('.has-error').removeClass('has-error');
                $('.form-message').remove();

                // submit data
                let $formData = new FormData( this.$refs['formUser'] );
                $formData.delete('files');

                let self = this;
                this.$http.post(this.$data['saveUserUrl'], $formData).then(
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
						if ($response.body.reset) {
                            self.$refs['formUser'].reset();
						}

						// reload user table
                        self.$root['dataTable'].api().ajax.reload(null, false);

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

            showFormLoading () {
                $( this.$refs['formUser'] ).addClass('form-loading form-loading-inverted');
			},

            hideFormLoading () {
                $( this.$refs['formUser'] ).removeClass('form-loading form-loading-inverted');
			},

            /**
             * @param $requestUrl
             */
            fetchFormData ($requestUrl) {
                let self = this;

                self.showFormLoading();

                $('.has-error').removeClass('has-error');
                $('.form-message').remove();

                this.$http.post($requestUrl).then(
                    function ($response) {
                        if (typeof $response.body.data !== 'undefined') {
                            self.formData = $response.body.data;

                            self.$data['formHeader'] = 'Cập nhật: ' + $response.body.data.name;
                            self.hideFormLoading();
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

                            self.$refs['formUser'].reset();
                            self.formData = {
                                'user_id': '',
                                'name': '',
                                'email': '',
                                'level': 'admin',
                                'status': 'enable'
                            };
                            self.$data['formHeader'] = 'Thêm người dùng';
                            self.hideFormLoading();
                        }
                    }
                );
			},

            makeAddForm () {

                $('.has-error').removeClass('has-error');
                $('.form-message').remove();

                this.$refs['formUser'].reset();
                this.formData = {
                    'user_id': '',
                    'name': '',
                    'email': '',
                    'level': 'admin',
                    'status': 'enable'
                };
                this.$data['formHeader'] = 'Thêm người dùng';
			}
		}
    }
</script>