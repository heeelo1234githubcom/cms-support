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
										<!--<th>{{ title }}</th>--><!-- style="width: 280px;"-->
										<th>{{ email }}</th>
										<!--<th style="width: 170px;">{{ phone }}</th>-->
										<th style="width: 140px;">{{ created_at }}</th>
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
            return Application.languages['promotionUser'];
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
						    'url': self.$data['getPromotionUserUrl'],
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
						/*fixedColumns: {
							leftColumns: 3
						},*/
						"language": {
							"lengthMenu":  self.$data['display'] + ' <select>'+
								'<option value="20">20</option>'+
								'<option value="50">50</option>'+
								'<option value="100">100</option>'+
							'</select> ' + self.$data['promotion_user'],

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
						"columns": [/*{
							'data': 'name',
						}, */{
							'data': 'email'
						}/*, {
							'data': 'phone'
						}*/, {
                            'data': 'created_at'
                        }]
					});

			}
		}
    }
</script>