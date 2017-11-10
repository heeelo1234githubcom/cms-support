<template>
	<video-album></video-album>
</template>

<script>

    import VideoAlbum from './../media/Video.vue';

    export default {
        components: {
            VideoAlbum
        },

        destroyed () {
            this.$root.currentObjectId = null;
        },
    }
</script>