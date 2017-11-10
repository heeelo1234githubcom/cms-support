<template>
    <div class="main-wrapper px-navbar-fixed">

        <left-navbar></left-navbar>

        <top-navbar></top-navbar>

        <slot></slot>

        <div class="modal fade" data-backdrop="static" data-keyboard="false" id="modal-container" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">×</button>
                        <h4 class="modal-title">{{ $root.modal['title'] }}</h4>
                    </div>

                    <div class="modal-body" v-html="$root.modal['content']"></div>

                    <div class="modal-footer">
                        <button type="button" class="btn" data-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>

		<footer-wrapper></footer-wrapper>
    </div>
</template>

<script>

    import LeftNavbar from '../templates/navbar/LeftNavbar.vue';
    import TopNavbar from '../templates/navbar/TopNavbar.vue';
    import FooterWrapper from '../templates/Footer.vue';

    export default {
        mounted () {

            /**
			 * init px-nav
             */
            $('.px-nav').pxNav();
		},
        components: {
            LeftNavbar,
            TopNavbar,
            FooterWrapper,
        }
    }
</script>