<template>
	<photo></photo>
</template>

<script>

    import Photo from './../media/Photo.vue';

    export default {
        components: {
            Photo
        },

        destroyed () {
            this.$root.currentObjectId = null;
        },
    }
</script>