<template>
    <div>

    </div>
</template>

<script>

export default {
    data() {
        return {
            albums: null,
        };
    },
    created() {
        this.fetchAlbum('et-et-distinctio-occaecati-aliquam-veritatis-itaque');
    },
    // mounted() {
    //     this.fetchAlbum();
    // },
    filters: {
        /**
         * Filter to truncate string, accepts a length parameter
         */
        truncate(value, length) {
            return value.length > length ? value.substr(0, length) + '...' : value;
        },
    },
    methods: {
        fetchAlbum(slug) {

            Vue.axios.get(`/api/admin/albums/${slug}`, {
                })
                .then(res => res.data)
                .then(res => {
                    this.album = res.data;
                })
                .catch(err => {
                    this.album = null;
                    throw err;
                });
        },

        confirmDeleteSelectedAlbums() {
            this.$dialog.confirm({
                title: 'Deleting Albums',
                message:
                    'Are you sure you want to <b>delete</b> these albums? This action cannot be undone.',
                confirmText: 'Delete Albums',
                type: 'is-danger',
                hasIcon: true,
                onConfirm: () => {
                    this.checkedRows.forEach(el => this.deleteAlbum(el));
                    this.$toast.open('Albums deleted!');
                },
            });
        },
        /**
         * Delete album from slug
         */
        deleteAlbum(album) {
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
