<template>
    <main-layout>

		<div class="px-content">
			<div class="page-header">
				<h1>
					<i class="page-header-icon fa fa-bars"></i>
					{{ header }}
				</h1>
			</div>
			<div class="row">

				<div class="col-md-12">

					<div class="panel-body no-padding">
						<div class="table-primary">

							<table ref="dataTable" class="table table-striped table-bordered dataTable">
								<thead>
									<tr>
										<th class="auto-column">{{ title }}</th>
										<th class="slug-column">{{ slug }}</th>
										<th class="cover-column">{{ cover }}</th>
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
            return Application.languages['service']['list'];
		},

		mounted () {
            this.$root.fixedMenu = false;

            this.initDataTable();
            $('.dataTables_filter input').attr('placeholder', this.$data['searchText']);
		},

        destroyed () {
            this.$root.fixedMenu = true;
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
						    'url': self.$data['getServicesUrl'],
							'type': 'post',
                            'beforeSend': function (request) {
                                request.setRequestHeader("Authorization", localStorage.getItem('jwt-token'));
                            }
                        },
						//"dom": 'lfrtip',
						"searchDelay": 800,
                        "autoWidth": false,
						"processing": true,
						"serverSide": true,
						"pageLength": 20,
						"ordering": true,
						"order": [[4, 'desc']],
						/*fixedColumns: {
							leftColumns: 3
						},*/
						"language": {
							"lengthMenu":  self.$data['display'] + ' <select>'+
								'<option value="20">20</option>'+
								'<option value="50">50</option>'+
								'<option value="100">100</option>'+
							'</select> ' + self.$data['services'],

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
							'data': 'title',
							'className': 'auto-column'
						}, {
							'data': 'slug',
							'className': 'slug-column'
						}, {
							'data': 'cover',
							'className': 'cover-column',
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