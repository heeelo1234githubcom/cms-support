<template>

    <nav class="navbar px-navbar">

        <div class="navbar-header">
            <v-link class="navbar-brand px-demo-brand" href="/manage">
                <strong>{{ appName }}</strong> Admin
            </v-link>
        </div>

        <div class="collapse navbar-collapse" id="px-demo-navbar-collapse">

            <ul class="nav navbar-nav navbar-right">

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">

						<img :src="$root.user.avatar" v-if="$root.user.avatar" class="px-navbar-image" />
						<span v-else class="fa fa-user-circle px-navbar-image"></span>
						 &nbsp; <span>{{ $root.user.name }}</span>
                    </a>
                    <ul class="dropdown-menu">
						<li>
							<v-link href="/manage/profile"><i class="dropdown-icon fa fa-user"></i> &nbsp; {{ updateProfile }}</v-link>
						</li>
                        <li class="divider"></li>
                        <li><a href="javascript:;" v-on:click="onLogout"><i class="dropdown-icon fa fa-power-off"></i> &nbsp; {{ logOut }}</a></li>
                    </ul>
                </li>

            </ul>
        </div>
    </nav>

</template>

<script>

    import VLink from './../components/VLink.vue';

    export default {
        components: {
            VLink,
        },

        data () {
            return Application.languages.topNavbarData;
        },

		methods: {
            onLogout ($e) {

                $e.preventDefault();

                let self = this;
                bootbox.confirm({
                    message: self.$data['logoutConfirmText'],
                    buttons: {
                        confirm: {
                            label: self.$data['yes'],
                            className: 'btn-success'
                        },
                        cancel: {
                            label: self.$data['no'],
                            className: 'btn-danger'
                        }
                    },
                    callback: function ($result) {
                        if ($result) {
                            localStorage.removeItem('jwt-token');
                            localStorage.removeItem('jwt-time-generate');
                            self.$root.authenticated = false;
                            self.$root.go('/manage/login');
						}
                    }
                });
			}
		}
    }
</script>