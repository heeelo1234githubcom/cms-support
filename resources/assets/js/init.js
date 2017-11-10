
/**
 * Vue is a modern JavaScript library for building interactive web interfaces
 * using reactive data binding and reusable components. Vue's API is clean
 * and simple, leaving you to focus on building your next great project.
 */
window.Vue = require('vue');

/* Vue Resource */
let VueResource = require('vue-resource');
Vue.use(VueResource);

/* Vue Validator */
import VeeValidate, { Validator } from 'vee-validate';
Vue.use(VeeValidate);

import dictionary from './validate_dictionary';
Validator.updateDictionary(dictionary);

/**
 * register base request path
 * @type {string}
 */
Vue.http.options.root = Application.baseApiUrl;
Vue.http.interceptors.push(function(request, next) {

    request.headers.set('Authorization', localStorage.getItem('jwt-token'));

    // continue to next interceptor
    next(function(response) {

        if (response.status === 200) {

            /**
             * re-generate token time
             */
            localStorage.setItem('jwt-time-generate', Math.floor(Date.now() / 1000));

            /**
             * update token
             */
            if (typeof response.body.token !== 'undefined') {
                localStorage.setItem('jwt-token', 'Bearer ' + response.body.token);
            }
        }
    });
});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import routes from './routes';
const app = new Vue({
    el: '#application-wrapper',
    data: {
        currentRoute: window.location.pathname,
        user: {
            name: null
        },
        authenticated: false,
        fixedMenu: true,
        modal: {
            title: '',
            message: ''
        },
        currentObjectId: null
    },
    computed: {
        ViewComponent () {

            if (this.currentRoute === '/manage/login' && this.authenticated) {
                this.go('/manage');
            }

            let matchingView = routes[this.currentRoute];

            /* detect edit template */
            if (-1 !== this.currentRoute.indexOf('edit')) {
                let params = this.currentRoute.split('/');

                this.$root.currentObjectId = _.last(params);
                params = params.slice(2);
                params = params.slice(0, -2);
                params.push('Edit');

                matchingView = params.join('/');
            }

            /* comment by service template */
            if (-1 !== this.currentRoute.indexOf('comment')) {
                let params = this.currentRoute.split('/');

                let $last = _.last(params);

                if ($last !== 'comment') {
                    this.$root.currentObjectId = $last;
                    params = params.slice(2);
                    params = params.slice(0, -2);
                    params.push('CommentService');

                    matchingView = params.join('/');
                }
            }

            /* media by album */
            if (-1 !== this.currentRoute.indexOf('media')) {
                let params = this.currentRoute.split('/');

                let $last = _.last(params);

                if ($last !== 'photo' && $last !== 'video') {
                    params = params.slice(2);
                    params = params.slice(0, -1);

                    if (params[0] === 'media' && -1 !== _.indexOf(['video', 'photo'], params[1])) {
                        this.$root.currentObjectId = $last;

                        $last = _.last(params);
                        params = params.slice(0, -1);
                        $last = $last.charAt(0).toUpperCase() + $last.slice(1);
                        params.push($last + 'Album');

                        matchingView = params.join('/');
                    }
                }
            }

            return matchingView
                ? require('./../templates/' + matchingView + '.vue')
                : require('./../templates/404.vue')
        }
    },

    render (h) {
        return h(this.ViewComponent)
    },

    created () {
        this.$validator.setLocale(Application.locale);

        this.$on('userHasLoggedOut', function () {
            this.destroyLogin()
        });

        this.$on('userHasLoggedIn', function (user) {
            this.setLogin(user)
        });

        this.$on('showNotify', function (title, message, options) {
            this.showNotify(title, message, options);
        });
    },

    mounted () {

        // The app has just been initialized, check if we can get the user data with an already existing token
        let token = localStorage.getItem('jwt-token');
        if (token) {

            let that = this;
            this.authenticated = true;
            let $jwtTimeGenerate = localStorage.getItem('jwt-time-generate');

            if ( !$jwtTimeGenerate) {
                $jwtTimeGenerate = 0;
            }

            /* remember token for 2 weeks */
            let $isRememberLogin = localStorage.getItem('remember-login');
            let $now = Math.floor(Date.now() / 1000);

            /* validate token within 24 hours */
            if (($now - $jwtTimeGenerate) > (3600 * 24)) {

                if ($isRememberLogin !== 'true') {
                    return that.destroyLogin();
                }

                /**
                 * refresh token
                 */
                return this.$http.post('auth/refresh', {}).then(() => {

                    /**
                     * refresh successful
                     */
                    that.getUserInfo();

                }, () => {

                    /* error response */
                    that.destroyLogin();
                });
            }

            that.getUserInfo();

            return true;
        }

        this.go('/manage/login');
    },

    methods: {

        setLogin (user) {
            this.user = user;
            this.authenticated = true;
        },

        destroyLogin () {
            this.user = {
                name: null
            };
            this.authenticated = false;
            localStorage.removeItem('jwt-token');

            /**
             * go to login page
             */
            this.go('/manage/login');
        },

        getUserInfo () {

            let that = this;
            this.$http.post('auth/user').then(response => {
                that.setLogin(response.body);
            }, () => {
                that.destroyLogin();
            });

        },

        /**
         * show app notify
         * @param message
         * @param options
         */
        showNotify (message, options) {

            options = options || {};

            let title;
            if (typeof options.type !== 'undefined') {
                title = (options.type === 'error') ? 'Có lỗi xảy ra' : 'Thông báo';
            } else {
                options.type = 'error';
            }

            options = jQuery.extend(options, {
                title: title,
                message: message
            });

            $.growl[options.type](options);
        },

        go (href) {
            this.currentObjectId = null;
            this.currentRoute = href;
            window.history.pushState(null, routes[href], href);
        },

        makeSlug ($event, $target) {

            /* check arrow keys */
            if (-1 !== _.indexOf([37, 38, 39, 40], $event.keyCode)) {
                return;
            }

            let $value = URLify($event.target.value);

            $target.value = $value
                .replace(/^-+/, '')
                .replace(/-+$/, '');
        },

        makeRequest ($requestUrl, isModal) {

            let self = this;
            isModal = isModal || false;

            this.$http.post($requestUrl).then(
                function ($response) {

                    if (typeof $response.body.message !== 'undefined') {
                        /**
                         * show notify
                         */
                        self.$root['showNotify']($response.body.message, {
                            type: 'notice'
                        });
                    }

                    /* reload dataTable without reset paging */
                    self.$root['dataTable'].api().ajax.reload(null, false);

                    if (isModal) {
                        self.$root.modal = $response.body.modal;
                        self.showPopup();
                    }
                },

                function ($response) {
                    if (typeof $response.body.error !== 'undefined') {

                        /**
                         * show notify
                         */
                        self.$root['showNotify']($response.body.error, {
                            type: 'error'
                        });
                    }
                }
            );
        },

        showPopup () {
            let $modalContainer = $('#modal-container');
            $modalContainer.modal();
            $modalContainer.on('hidden.bs.modal', function () {
                $('.youtube-embed').remove();

                $(this).data('bs.modal', null);
                $(this).data('modal', null);
            });
        }
    }
});

window.addEventListener('popstate', () => {
    app.currentRoute = window.location.pathname;
});

$(document).ready(function () {

    /* init option links */
    $(document).on('click', 'a.option-link', function ($e) {
        $e.preventDefault();

        app.go( $(this).attr('href') );
    });

    $(document).on('click', 'a.remove-record', function ($e) {
        $e.preventDefault();

        let $requestUrl = $( this ).attr('href');
        let $record = $( this ).data('title');

        bootbox.confirm({
            message: 'Bạn có thực sự muốn xóa ' +$record+ ' này không?',
            buttons: {
                confirm: {
                    label: 'Có',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'Không',
                    className: 'btn-danger'
                }
            },
            callback: function ($result) {

                if ($result) {
                    app.makeRequest($requestUrl);
                }
            }
        });
    });

    $(document).on('click', 'a.enable-record', function ($e) {
        $e.preventDefault();

        let $requestUrl = $( this ).attr('href');
        let $record = $( this ).data('title');

        bootbox.confirm({
            message: 'Bạn có thực sự muốn hiển thị ' +$record+ ' này không?',
            buttons: {
                confirm: {
                    label: 'Có',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'Không',
                    className: 'btn-danger'
                }
            },
            callback: function ($result) {

                if ($result) {
                    app.makeRequest($requestUrl);
                }
            }
        });
    });

    $(document).on('click', 'a.view-link', function ($e) {
        $e.preventDefault();

        let $requestUrl = $( this ).attr('href');
        app.makeRequest($requestUrl, true);
    });

    $(document).on('click', 'img.video-preview', function ($e) {
        $e.preventDefault();

        app.$root['modal'] = {
            'title': 'Video: ' + $(this).data('title'),
            'content': ''
        };
        $('#modal-container .modal-body').html('<div class="youtube-embed" style="text-align: center"><iframe width="100%" height="450" src="https://www.youtube.com/embed/' +$(this).data('src')+ '?autoplay=1" frameborder="0" allowfullscreen></iframe></div>')

        app.showPopup();
    });
});
