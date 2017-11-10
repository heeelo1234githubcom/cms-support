<template>
    <main-layout>

		<div class="px-content">
			<div class="page-header">
				<h1>
					<i class="page-header-icon fa fa-bars"></i>
					{{ header }} <span v-if="currentServiceTitle">(Dịch vụ: {{ currentServiceTitle }})</span>
				</h1>
			</div>
			<div class="row">

				<div class="col-md-12">

					<div class="panel-body no-padding">
						<div class="table-primary">

							<table ref="dataTable" class="table table-striped table-bordered dataTable">
								<thead>
									<tr>
										<th style="width: 150px;">{{ comment_name }}</th>
										<th style="width: 210px;">{{ comment_email }}</th>
										<th style="width: 100px;">{{ comment_phone }}</th>
										<th>{{ comment_content }}</th>
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
            return Application.languages['service']['comment'];
		},

		mounted () {
            this.$root.fixedMenu = false;
            this.initDataTable();
            $('.dataTables_filter input').attr('placeholder', this.$data['searchText']);
		},

        destroyed () {
            this.$root.fixedMenu = true;
            this.$root.currentObjectId = null;
		},

		methods: {
            initDataTable () {

                let self = this;
                let $data = {};
                if (this.$root['currentObjectId']) {
                    $data.service_id = this.$root['currentObjectId'];
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
                            'url': self.$data['getCommentUrl'],
                            'type': 'post',
                            'beforeSend': function (request) {
                                request.setRequestHeader("Authorization", localStorage.getItem('jwt-token'));
                            },
							'data': $data,
                            complete: function($response) {
                                self.currentServiceTitle = $response['responseJSON']['serviceTitle'];
                            },
                            error: function ($response) {
                                if (typeof $response['responseJSON'] !== 'undefined') {
                                    self.$root['showNotify']($response['responseJSON'].error, {
                                        type: 'error'
                                    });

                                    self.$root.go('/manage/service/comment')
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
						"order": [[5, 'desc']],
						/*fixedColumns: {
							leftColumns: 3
						},*/
						"language": {
							"lengthMenu":  self.$data['display'] + ' <select>'+
								'<option value="10">10</option>'+
								'<option value="20">20</option>'+
								'<option value="50">50</option>'+
								'<option value="100">100</option>'+
							'</select> ' + self.$data['comments'],

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
							'data': 'comment_name'
						}, {
							'data': 'comment_email'
						}, {
							'data': 'comment_phone'
						}, {
                            'data': 'comment_content',
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

			}
		}
    }
</script>