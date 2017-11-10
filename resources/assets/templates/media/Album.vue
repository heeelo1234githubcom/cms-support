<template>
    <main-layout>

		<div class="px-content">

			<div class="row">

				<div class="col-md-3">

					<div class="page-header">
						<h1>
							<i :class="{ 'page-header-icon fa fa-plus': !formData.album_id, 'page-header-icon fa fa-pencil': formData.album_id }"></i>
							{{ formHeader }}
						</h1>
					</div>

					<div class="col-md-12" style="margin-top: 10px;">

						<div class="panel">

							<form class="panel-body" ref="formAlbum"  @submit.prevent="onSaveAlbum" :action="saveAlbumUrl" method="post" autocomplete="off">

								<input type="hidden" name="album_id" :value="formData.album_id">

								<fieldset class="form-group">
									<label for="input-title">{{ title }}</label>
									<input v-on:keyup="makeSlug" v-on:blur="makeSlug" name="title" id="input-title" type="text" class="form-control" :placeholder="title" :value="formData.title">
								</fieldset>

								<fieldset class="form-group">
									<label for="input-slug">{{ slug }}</label>
									<input v-on:keyup="makeSlug" v-on:blur="makeSlug" ref="slug" name="slug" id="input-slug" type="text" class="form-control" :placeholder="slug" :value="formData.slug">
								</fieldset>

								<fieldset class="form-group m-b-10">
									<label for="input-description">{{ description }}</label>
									<textarea name="description" id="input-description" type="text" class="form-control" style="height: 150px;"></textarea>
								</fieldset>

								<fieldset class="form-group">
									<label class="m-b-10">{{ type }}</label>
									<div>
										<label class="custom-control custom-radio radio-inline">
											<input :disabled="formData.album_id != ''" type="radio" name="type" value="photo" class="custom-control-input" :checked="formData.type == 'photo'">
											<span class="custom-control-indicator"></span>
											Ảnh
										</label>
										<label class="custom-control custom-radio radio-inline">
											<input :disabled="formData.album_id != ''" type="radio" name="type" value="video" class="custom-control-input" :checked="formData.type == 'video'">
											<span class="custom-control-indicator"></span>
											Video
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
							<button v-if="formData.album_id" v-on:click="makeAddForm" class="btn btn-danger btn-small"><i class="fa fa-plus"></i> Thêm album mới</button>
							&nbsp;&nbsp;&nbsp;
							<button v-on:click="onSaveAlbum" :data-loading-text="loadingText" type="submit" ref="submitButton" class="btn btn-small btn-primary mt15">{{ save }}</button>
						</div>

					</div>

				</div>

				<div class="col-md-9">

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
											<th style="width: 250px;">{{ title }}</th>
											<th style="width: 250px;">{{ slug }}</th>
											<th>{{ description }}</th>
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
            let $data = Application.languages['mediaAlbum'];
            $data['formData'] = {
                'album_id': '',
                'title': '',
				'slug': '',
				'type': 'photo',
				'description': '',
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
                            'url': self.$data['getAlbumUrl'],
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
							'</select> ' + self.$data['album'],

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
							'data': 'title'
						}, {
							'data': 'slug'
						}, {
						    'data': 'description'
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

            makeSlug ($e) {
                this.$root.makeSlug($e, this.$refs['slug']);
            },

            onSaveAlbum ($e) {

                $e.preventDefault();

                if (this.loading) {
                    return false;
                }

				/* show loading */
                this.showLoading();

                $('.has-error').removeClass('has-error');
                $('.form-message').remove();

                // submit data
                let $formData = new FormData( this.$refs['formAlbum'] );
                $formData.delete('files');

                let self = this;
                this.$http.post(this.$data['saveAlbumUrl'], $formData).then(
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
                            self.$refs['formAlbum'].reset();
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
                $( this.$refs['formAlbum'] ).addClass('form-loading form-loading-inverted');
			},

            hideFormLoading () {
                $( this.$refs['formAlbum'] ).removeClass('form-loading form-loading-inverted');
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

                            self.$data['formHeader'] = 'Cập nhật album:';
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

                            self.$refs['formAlbum'].reset();
                            self.formData = {
                                'album_id': '',
                                'title': '',
                                'slug': '',
                                'type': 'photo',
                                'description': '',
                                'status': 'enable'
                            };
                            self.$data['formHeader'] = 'Thêm album';
                            self.hideFormLoading();
                        }
                    }
                );
			},

            makeAddForm () {

                $('.has-error').removeClass('has-error');
                $('.form-message').remove();

                this.$refs['formAlbum'].reset();
                this.formData = {
                    'album_id': '',
                    'title': '',
                    'slug': '',
                    'type': 'photo',
                    'description': '',
                    'status': 'enable'
                };
                this.$data['formHeader'] = 'Thêm album';
			}
		}
    }
</script>