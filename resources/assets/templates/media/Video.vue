<template>
	<main-layout>

		<div class="px-content">

			<div class="row">

				<div class="col-md-3">

					<div class="page-header">
						<h1>
							<i :class="{ 'page-header-icon fa fa-plus': !formData.media_id, 'page-header-icon fa fa-pencil': formData.media_id }"></i>
							{{ formHeader }}
						</h1>
					</div>

					<div class="col-md-12" style="margin-top: 10px;">

						<div class="panel">

							<form class="panel-body" ref="formMedia"  @submit.prevent="onSaveMedia" :action="saveMediaUrl" method="post" autocomplete="off">

								<input type="hidden" name="media_id" :value="formData.media_id">

								<fieldset class="form-group">
									<label for="input-title">{{ title }}</label>
									<input name="title" id="input-title" type="text" class="form-control" :placeholder="title" :value="formData.title">
								</fieldset>

								<fieldset class="form-group">
									<label for="input-album">{{ album }}</label>
									<select :disabled="formData.album_id != '' && $root.currentObjectId != null" name="album_id" ref="album_selection" id="input-album"></select>
								</fieldset>

								<fieldset class="form-group">
									<label for="input-file">{{ file }}</label>
									<input name="file" id="input-file" type="text" class="form-control" :placeholder="file" :value="formData.file">
								</fieldset>

								<fieldset class="form-group m-b-10">
									<label for="input-description">{{ description }}</label>
									<textarea name="description" id="input-description" type="text" class="form-control" style="height: 150px;"></textarea>
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
							<button v-if="formData.media_id" v-on:click="makeAddForm" class="btn btn-danger btn-small"><i class="fa fa-plus"></i> Thêm video mới</button>
							&nbsp;&nbsp;&nbsp;
							<button v-on:click="onSaveMedia" :data-loading-text="loadingText" type="submit" ref="submitButton" class="btn btn-small btn-primary mt15">{{ save }}</button>
						</div>

					</div>

				</div>

				<div class="col-md-9">

					<div class="page-header">
						<h1>
							<i class="page-header-icon fa fa-bars"></i>
							{{ listHeader }} {{ albumTitle }}
						</h1>
					</div>
					<div class="row">

						<div class="col-md-12">

							<div class="panel-body no-padding">
								<div class="table-primary">

									<table ref="dataTable" class="table table-striped table-bordered dataTable">
										<thead>
										<tr>
											<th>{{ title }}</th>
											<th style="width: 250px;">{{ album }}</th>
											<th style="width: 100px;">Video</th>
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
            let $data = Application.languages['mediaVideo'];
            $data['albumTitle'] = '';
            $data['formData'] = {
                'media_id': '',
                'album_id': '',
                'title': '',
                'file': '',
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

			/* init select 2*/
            this.$groupBox = $( this.$refs['album_selection'] ).select2({
                placeholder: 'Chọn album',
                language: {
                    inputTooShort: function () {
                        return 'Xin vui lòng nhập tên album muốn tìm.';
                    },
                    searching: function () {
                        return 'Đang tìm album...';
                    },
                    noResults: function () {
                        return 'Không tìm thấy hoặc chưa có album nào.';
                    },
                    loadingMore: function () {
                        return 'Đang lấy thêm album...';
                    }
                },
                ajax: {
                    url: self.$data['getAlbumUrl'],
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
                            selectBox: true,
                            type: 'video'
                        };
                    },
                    processResults: function (data, params) {
                        // parse the results into the format expected by Select2
                        // since we are using custom formatting functions we do not need to
                        // alter the remote JSON data, except to indicate that infinite
                        // scrolling can be used
                        params.page = params.page || 1;

                        return {
                            results: data.data,
                            pagination: {
                                more: (params.page * 10) < data.recordsTotal
                            }
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
            //this.$root.currentObjectId = null;

			/* remove click event listener */
            $(document).off('click', 'a.ajax-update-record');
        },

        methods: {
            initDataTable () {

                let self = this;
                let $data = {};
                if (this.$root['currentObjectId']) {
                    $data.album_id = this.$root['currentObjectId'];
                }

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
                            'url': self.$data['getMediaUrl'],
                            'type': 'post',
                            'beforeSend': function (request) {
                                request.setRequestHeader("Authorization", localStorage.getItem('jwt-token'));
                            },
                            data: $data,
                            complete: function($response) {
                                if ($response['responseJSON']['albumTitle']) {
                                    self.albumTitle = '(Album: ' + $response['responseJSON']['albumTitle'] + ')';
                                }
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
                            '</select> ' + self.$data['media'],

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
                            'data': 'albumTitle',
                            'orderable': false
                        }, {
                            'data': 'file',
                            'orderable': false
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

            onSaveMedia ($e) {

                $e.preventDefault();

                if (this.loading) {
                    return false;
                }

				/* show loading */
                this.showLoading();

                $('.has-error').removeClass('has-error');
                $('.form-message').remove();

                // submit data
                let $formData = new FormData( this.$refs['formMedia'] );
                $formData.delete('files');

                let self = this;
                this.$http.post(this.$data['saveMediaUrl'], $formData).then(
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
                            self.$refs['formMedia'].reset();
                            self.formData = {
                                'media_id': '',
                                'album_id': '',
                                'title': '',
                                'file': '',
                                'description': '',
                                'status': 'enable'
                            };
                            self.$data['formHeader'] = 'Thêm video';
                            let option = new Option('', '', true, true);
                            self.$groupBox.append(option);
                            self.$groupBox.trigger('change');
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
                $( this.$refs['formMedia'] ).addClass('form-loading form-loading-inverted');
            },

            hideFormLoading () {
                $( this.$refs['formMedia'] ).removeClass('form-loading form-loading-inverted');
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

                            self.$data['formHeader'] = 'Cập nhật video:';

							/* change group */
                            let option = new Option($response.body.data['albumTitle'], $response.body.data.album_id, true, true);
                            self.$groupBox.append(option);
                            self.$groupBox.trigger('change');

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

                            self.$refs['formMedia'].reset();
                            self.formData = {
                                'media_id': '',
                                'album_id': '',
                                'title': '',
                                'file': '',
                                'description': '',
                                'status': 'enable'
                            };
                            self.$data['formHeader'] = 'Thêm video';
                            self.hideFormLoading();
                        }
                    }
                );
            },

            makeAddForm () {

                $('.has-error').removeClass('has-error');
                $('.form-message').remove();

                this.$refs['formMedia'].reset();
                this.formData = {
                    'media_id': '',
                    'album_id': '',
                    'title': '',
                    'file': '',
                    'description': '',
                    'status': 'enable'
                };
                this.$data['formHeader'] = 'Thêm video';
                let option = new Option('', '', true, true);
                this.$groupBox.append(option);
                this.$groupBox.trigger('change');
            }
        }
    }
</script>