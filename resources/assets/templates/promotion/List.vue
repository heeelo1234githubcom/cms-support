<template>
    <main-layout>

		<div class="px-content">

			<div class="row">

				<div class="col-md-4">

					<div class="page-header">
						<h1>
							<i :class="{ 'page-header-icon fa fa-plus': !formData.promotion_id, 'page-header-icon fa fa-pencil': formData.promotion_id }"></i>
							{{ formHeader }}
						</h1>
					</div>

					<div class="col-md-12" style="margin-top: 10px;">

						<div class="panel">

							<form class="panel-body" ref="formPromotion"  @submit.prevent="onSavePromotion" :action="savePromotionUrl" method="post" autocomplete="off">

								<input type="hidden" name="promotion_id" :value="formData.promotion_id">

								<fieldset class="form-group">
									<label for="input-title">{{ title }}</label>
									<input v-on:keyup="makeSlug" v-on:blur="makeSlug" name="title" id="input-title" type="text" class="form-control" :placeholder="title" :value="formData.title">
								</fieldset>

								<fieldset class="form-group">
									<label for="input-slug">{{ slug }}</label>
									<input v-on:keyup="makeSlug" v-on:blur="makeSlug" ref="slug" name="slug" id="input-slug" type="text" class="form-control" :placeholder="slug" :value="formData.slug">
								</fieldset>

								<fieldset class="form-group m-b-10">
									<label for="input-content">{{ content }}</label>
									<textarea ref="summerNote" name="content" id="input-content" class="form-control textarea-medium">{{ formData.content }}</textarea>
								</fieldset>

								<fieldset class="form-group">
									<label>{{ time }}</label>
									<div class="input-daterange input-group" id="datepicker-range">
										<input name="start_date" type="text" class="form-control" :placeholder="start_date" :value="formData.start_date">
										<span class="input-group-addon">đến</span>
										<input name="end_date" type="text" class="form-control" :placeholder="end_date" :value="formData.end_date">
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

							<button v-if="formData.promotion_id" v-on:click="makeAddForm" class="btn btn-danger btn-small"><i class="fa fa-plus"></i> Thêm khuyến mãi mới</button>
							&nbsp;&nbsp;&nbsp;
							<button v-on:click="onSavePromotion" :data-loading-text="loadingText" type="submit" ref="submitButton" class="btn btn-small btn-primary mt15">{{ save }}</button>
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
											<th>Tiêu đề khuyễn mãi</th>
											<th style="width: 110px;">Bắt đầu</th>
											<th style="width: 110px;">Kết thúc</th>
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
            let $data = Application.languages['promotion'];
            $data['formData'] = {
                'promotion_id': '',
                'title': '',
				'slug': '',
				'content': '',
				'start_date': '',
				'end_date': '',
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

            this.initSummerNote();

            $('#datepicker-range').datepicker({
                format: 'dd/mm/yyyy'
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
                            'url': self.$data['getPromotionUrl'],
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
						"pageLength": 15,
						"ordering": true,
						"order": [[4, 'desc']],
						/*fixedColumns: {
							leftColumns: 3
						},*/
						"language": {
							"lengthMenu":  self.$data['display'] + ' <select>'+
								'<option value="15">15</option>'+
								'<option value="50">50</option>'+
								'<option value="100">100</option>'+
							'</select> ' + self.$data['promotion'],

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
							'data': 'start_date'
						}, {
						    'data': 'end_date'
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

            onSavePromotion ($e) {

                $e.preventDefault();

                if (this.loading) {
                    return false;
                }

				/* show loading */
                this.showLoading();

                $('.has-error').removeClass('has-error');
                $('.form-message').remove();

                // submit data
                let $formData = new FormData( this.$refs['formPromotion'] );
                $formData.delete('files');

                let self = this;
                this.$http.post(this.$data['savePromotionUrl'], $formData).then(
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
                            self.$refs['formPromotion'].reset();

                            $( self.$refs['summerNote'] ).summernote('code', '');
						}

						// reload promotion table
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

                            if (-1 !== _.indexOf(['start_date', 'end_date'], $errorKey)) {
                                $('[name=' +$errorKey+ ']').parent().parent()
                                    .addClass('has-error')
                                    .append('<small class="form-message light">' +$message[0]+ '</small>');
							} else {
								$('[name=' +$errorKey+ ']').parent()
                                    .addClass('has-error')
                                    .append('<small class="form-message light">' +$message[0]+ '</small>');
							}
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
                $( this.$refs['formPromotion'] ).addClass('form-loading form-loading-inverted');
			},

            hideFormLoading () {
                $( this.$refs['formPromotion'] ).removeClass('form-loading form-loading-inverted');
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

                            $( self.$refs['summerNote'] ).summernote('code', $response.body.data.content);

                            self.$data['formHeader'] = 'Cập nhật khuyến mãi:';
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

                            self.$refs['formPromotion'].reset();
                            self.formData = {
                                'promotion_id': '',
                                'title': '',
                                'slug': '',
                                'content': '',
                                'start_date': '',
                                'end_date': '',
                                'status': 'enable'
                            };
                            self.$data['formHeader'] = 'Thêm khuyến mãi';
                            self.hideFormLoading();
                            $( self.$refs['summerNote'] ).summernote('code', '');
                        }
                    }
                );
			},

            makeAddForm () {

                $('.has-error').removeClass('has-error');
                $('.form-message').remove();

                this.$refs['formPromotion'].reset();
                this.formData = {
                    'promotion_id': '',
                    'title': '',
                    'slug': '',
                    'content': '',
                    'start_date': '',
                    'end_date': '',
                    'status': 'enable'
                };
                this.$data['formHeader'] = 'Thêm khuyến mãi';
                $( this.$refs['summerNote'] ).summernote('code', '');
			},

            makeSlug ($e) {
                this.$root.makeSlug($e, this.$refs['slug']);
            },

            initSummerNote () {
				/* init summer note */
                $( this.$refs['summerNote'] ).summernote({
                    height: 250,
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
		}
    }
</script>