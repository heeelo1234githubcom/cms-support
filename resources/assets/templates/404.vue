<template>
    <main-layout>

		<div class="px-content">
			<div class="page-header">
				<h1>
					<i class="page-header-icon fa fa-exclamation-triangle"></i>
					{{ errorTitle }}
				</h1>
			</div>
			<div class="row">

				<div class="col-md-12">

					<div class="panel panel-danger panel-dark panel-body-colorful widget-profile widget-profile-centered">
						<div class="panel-body" v-html="notFoundExceptionMessage"></div>
					</div>

				</div>

			</div>
		</div>

    </main-layout>
</template>

<script>

    import MainLayout from '../templates/App.vue';
    import VLink from '../templates/components/VLink.vue';

    export default {
        components: {
            MainLayout,
            VLink
        },

		data () {
            return Application.languages.errorData;
		}
    }
</script>