<template>
    <main-layout>

		<div class="px-content">

			<div class="row">

				<div class="col-md-4">

					<div class="page-header">
						<h1>
							<i :class="{ 'page-header-icon fa fa-plus': !formData.menu_id, 'page-header-icon fa fa-pencil': formData.menu_id }"></i>
							{{ formHeader }}
						</h1>
					</div>

					<div class="col-md-12" style="margin-top: 10px;">

						<div class="panel">

							<form class="panel-body" ref="formMenu"  @submit.prevent="onSaveMenu" :action="saveMenuUrl" method="post" autocomplete="off">

								<input type="hidden" name="menu_id" :value="formData.menu_id">

								<fieldset class="form-group">
									<label for="input-title">{{ title }}</label>
									<input name="title" id="input-title" type="text" class="form-control" :placeholder="title" :value="formData.title">
								</fieldset>

								<fieldset class="form-group">
									<label class="m-b-10">Loại menu</label>
									<div>
										<label class="custom-control custom-radio radio-inline">
											<input v-on:click="changeFormData" type="radio" :disabled="formData.menu_id != ''" name="type" value="top" class="custom-control-input" :checked="formData.type == 'top'">
											<span class="custom-control-indicator"></span>
											Top
										</label>
										<label class="custom-control custom-radio radio-inline">
											<input v-on:click="changeFormData" type="radio" :disabled="formData.menu_id != ''" name="type" value="right" class="custom-control-input" :checked="formData.type == 'right'">
											<span class="custom-control-indicator"></span>
											Right
										</label>

										<!--<label class="custom-control custom-radio radio-inline">
											<input v-on:click="changeFormData" type="radio" :disabled="formData.menu_id != ''" name="type" value="bottom" class="custom-control-input" :checked="formData.type == 'bottom'">
											<span class="custom-control-indicator"></span>
											Bottom
										</label>-->
									</div>
								</fieldset>

								<fieldset class="form-group">
									<label for="input-parent">{{ parent_id }}</label>
									<select :disabled="formData.has_sub == 'yes'" data-allow-clear="true" name="parent_id" ref="parent_selection" id="input-parent"></select>
								</fieldset>

								<fieldset class="form-group panel" style="border: none;">

									<ul class="nav nav-tabs nav-tabs-simple">
										<li class="active">
											<a href="#tabs-custom-url" data-toggle="tab">Đường dẫn</a>
										</li>
										<li>
											<a href="#tabs-select-url" data-toggle="tab">Hoặc chọn từ...</a>
										</li>
									</ul>

									<div class="tab-content tab-content-bordered">
										<div class="tab-pane fade in active" id="tabs-custom-url">
											<input name="url" type="text" class="form-control" :placeholder="url" :value="formData.url">
										</div>
										<div class="tab-pane fade" id="tabs-select-url">
											<select data-allow-clear="true" name="select_url" ref="url_selection"></select>
										</div>
									</div>

								</fieldset>

								<fieldset class="form-group">
									<label for="input-sort">{{ sort }}</label>
									<input ref="sort" name="sort" id="input-sort" type="text" class="form-control" :placeholder="sort" :value="formData.sort">
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

							<button v-if="formData.menu_id" v-on:click="makeAddForm" class="btn btn-danger btn-small"><i class="fa fa-plus"></i> Thêm menu mới</button>
							&nbsp;&nbsp;&nbsp;
							<button v-on:click="onSaveMenu" :data-loading-text="loadingText" type="submit" ref="submitButton" class="btn btn-small btn-primary mt15">{{ save }}</button>
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
											<th>{{ title }}</th>
											<th>{{ parent_id }}</th>
											<th style="width: 70px;">{{ sort }}</th>
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
            let $data = Application.languages['menu'];
            $data['formData'] = {
                'menu_id': '',
                'parent_id': '',
                'title': '',
				'sort': '',
				'url': '',
				'has_sub': 'no',
				'type': 'top',
				'status': 'enable'
			};

            return $data;
		},

		mounted () {
            this.$root.fixedMenu = false;
            this.initDataTable();

            $( this.$refs['sort' ]).TouchSpin();

            let self = this;
            $(document).on('click', 'a.ajax-update-record', function ($e) {
				$e.preventDefault();

				self.fetchFormData( $(this).attr('href') );

                $(this).blur();
            });

			/* init select 2*/
            this.$groupBox = $( this.$refs['parent_selection'] ).select2({
                placeholder: 'Chọn menu cha',
                language: {
                    inputTooShort: function () {
                        return 'Xin vui lòng nhập tên menu muốn tìm.';
                    },
                    searching: function () {
                        return 'Đang tìm menu...';
                    },
                    noResults: function () {
                        return 'Không tìm thấy hoặc chưa có menu nào.';
                    },
                    loadingMore: function () {
                        return 'Đang lấy thêm menu...';
                    }
                },
                ajax: {
                    url: self.$data['getParentMenuUrl'],
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
							type: self.$data['formData']['type'],
							currentMenuId: self.$data['formData']['menu_id']
                        };
                    },
                    processResults: function (data, params) {
                        // parse the results into the format expected by Select2
                        // since we are using custom formatting functions we do not need to
                        // alter the remote JSON data, except to indicate that infinite
                        // scrolling can be used
                        params.page = params.page || 1;

                        return {
                            results: data.menu
                        };
                    },
                    cache: false
                },
                escapeMarkup: function (markup) { return markup; },
                minimumInputLength: 0
            });

            this.$urlBox = $( this.$refs['url_selection'] ).select2({
                placeholder: 'Chọn đường dẫn từ...',
                language: {
                    searching: function () {
                        return 'Đang tải dữ liệu...';
                    },
                    noResults: function () {
                        return 'Không tìm thấy hoặc chưa dữ liệu nào.';
                    },
                    loadingMore: function () {
                        return 'Đang lấy thêm dữ liệu...';
                    }
                },
                ajax: {
                    url: self.$data['searchMenuUrl'],
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
                            }
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

            /* remove click event listener */
            $(document).off('click', 'a.ajax-update-record');
		},

		methods: {

            changeFormData ($e) {
                this.formData['type'] = $e.target.value;
			},

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
                            'url': self.$data['getMenuUrl'],
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
						"dom": 'rt',
						"searchDelay": 800,
                        "autoWidth": false,
						"processing": true,
						"serverSide": true,
						"pageLength": 200,
						"ordering": false,
						"searching": false,
						/*fixedColumns: {
							leftColumns: 3
						},*/
						"language": {
							emptyTable: self.$data['emptyTable'],
							zeroRecords: self.$data['zeroRecords'],
							info: self.$data['info'],
							infoEmpty: '',
							loadingRecords: self.$data['processing'],
							processing: self.$data['processing']
						},
						"columns": [{
							'data': 'title'
						}, {
							'data': 'parent_id'
						}, {
						    'data': 'sort'
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

            onSaveMenu ($e) {

                $e.preventDefault();

                if (this.loading) {
                    return false;
                }

				/* show loading */
                this.showLoading();

                $('.has-error').removeClass('has-error');
                $('.form-message').remove();

                // submit data
                let $formData = new FormData( this.$refs['formMenu'] );
                $formData.delete('files');

                let self = this;
                this.$http.post(this.$data['saveMenuUrl'], $formData).then(
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
                            self.$refs['formMenu'].reset();

                            self.formData = {
                                'menu_id': '',
                                'parent_id': '',
                                'title': '',
                                'sort': '',
                                'url': '',
                                'type': 'top',
                                'status': 'enable'
                            };
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
                $( this.$refs['formMenu'] ).addClass('form-loading form-loading-inverted');
			},

            hideFormLoading () {
                $( this.$refs['formMenu'] ).removeClass('form-loading form-loading-inverted');
			},

            /**
             * @param $requestUrl
             */
            fetchFormData ($requestUrl) {
                let self = this;

                self.showFormLoading();

                $('.has-error').removeClass('has-error');
                $('.form-message').remove();

                let option = new Option('', '', true, true);
                this.$urlBox.append(option);
                this.$urlBox.trigger('change');


                this.$http.post($requestUrl).then(
                    function ($response) {
                        if (typeof $response.body.data !== 'undefined') {
                            self.formData = $response.body.data;

                            self.$data['formHeader'] = 'Cập nhật menu:';

							/* change group */
							if ($response.body.data['parent_id']) {
                                let option = new Option($response.body.data['parentTitle'], $response.body.data.parent_id, true, true);
                                self.$groupBox.append(option);
                                self.$groupBox.trigger('change');
							}

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

                            self.$refs['formMenu'].reset();
                            self.formData = {
                                'menu_id': '',
                                'parent_id': '',
                                'title': '',
                                'sort': '',
                                'url': '',
                                'type': 'top',
                                'status': 'enable'
                            };
                            self.$data['formHeader'] = 'Thêm menu';
                            let option = new Option('', '', true, true);
                            this.$groupBox.append(option);
                            this.$groupBox.trigger('change');

                            self.hideFormLoading();
                        }
                    }
                );
			},

            makeAddForm () {

                $('.has-error').removeClass('has-error');
                $('.form-message').remove();

                this.$refs['formMenu'].reset();
                this.formData = {
                    'menu_id': '',
                    'parent_id': '',
                    'title': '',
                    'sort': '',
                    'url': '',
                    'type': 'top',
                    'status': 'enable'
                };
                this.$data['formHeader'] = 'Thêm menu';
                let option = new Option('', '', true, true);
                this.$groupBox.append(option);
                this.$groupBox.trigger('change');
			}
		}
    }
</script>