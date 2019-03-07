<template>
    <div>

    </div>
</template>

<script>

export default {
    name: "AlbumsShow",
    data() {
        return {
            albums: null,
        };
    },
    created() {
        this.fetchAlbum();
    },
    methods: {
        fetchAlbum() {
            Vue.axios.get(`/api/admin/albums/${this.$route.params.slug}`)
                .then(res => res.data)
                .then(res => {
                    this.album = res.data;
                })
                .catch(err => {
                    this.album = null;
                    throw err;
                });
        },
        /**
         * Delete album from slug
         */
        deleteSelectedAlbum(album) {
            Vue.axios
                .delete(`/api/admin/albums/${album.slug}`)
                .then(res => {
                    this.fetchAlbums();
                })
                .catch(err => {
                    throw err;
                });
        },
    },
};
</script>
