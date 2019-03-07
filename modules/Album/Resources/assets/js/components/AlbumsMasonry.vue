<template>
    <masonry
            :cols="3"
            :gutter="30"
    >
        <div v-for="(album, index) in albums" :key="index">
            <a :href="'/albums/' + album.slug">
                <div class="card album">
                    <div class="card-image">
                        <figure>
                            <img :src="album.media.thumb" alt="">
                        </figure>
                    </div>
                    <div class="card-content">
                        <div class="content">
                            <p class="title is-5">{{ album.title }}</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </masonry>
</template>

<script>
    import VueMasonry from 'vue-masonry-css'

    window.Vue.use(VueMasonry);

    export default {
        name: "AlbumsMasonry",
        data() {
            return {
                albums: [],
            }
        },
        mounted() {
            this.fetchAlbums();
        },
        methods: {
            fetchAlbums() {
                Vue.axios.get('/api/albums', {
                    params: {
                        page: this.page,
                        // sort: sortOrder + this.sortField
                    }
                })
                    .then(res => res.data)
                    .then(res => {
                        this.perPage = res.meta.per_page;
                        this.total = res.meta.total;
                        this.albums = res.data;
                        this.loading = false;
                    })
                    .catch(err => {
                        this.albums = [];
                        this.total = 0;
                        this.loading = false;
                        this.$snackbar.open({
                            message: 'Unable to load albums, maybe you are offline?',
                            type: 'is-danger',
                            position: 'is-top',
                            actionText: 'Retry',
                            indefinite: true,
                            onAction: () => {
                                this.fetchAlbums();
                            }
                        });
                        throw err;
                    });
            }
        }
    }
</script>

<style scoped>

</style>