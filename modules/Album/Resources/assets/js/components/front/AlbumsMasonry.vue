<template>
    <div>
        <!--TODO Add nothing to show-->
        <masonry :gutter="{default: '30px', 700: '15px'}"
                :cols="{default: 3, 1000: 2, 700: 1, 400: 1}">
            <a v-for="(album, index) in albums" :key="index" :href="'/albums/' + album.slug" class="has-margin-right-md">
                <div class="card album">
                    <div v-if="album.media" class="card-image">
                        <figure class="image">
                            <img v-if="album.media.src_set" class="responsive-media" :src="album.media.thumb"
                                 :srcset="album.media.src_set" :alt="album.media.name" sizes="1px">
                            <img v-else :src="album.media.thumb" :alt="album.media.name">
                        </figure>
                    </div>
                    <div class="card-content">
                        <div class="content">
                            <p class="is-5">{{ album.title }}</p>
                        </div>
                    </div>
                </div>
            </a>
        </masonry>

        <div class="has-margin-md"></div>

        <b-pagination
                v-on:change="onPageChanged"
                :total="total"
                :current.sync="page"
                order="is-centered"
                icon-pack="fas"
                :per-page="perPage">
        </b-pagination>
    </div>
</template>

<script>
    import VueMasonry from 'vue-masonry-css'

    window.Vue.use(VueMasonry);

    export default {
        name: "AlbumsMasonry",
        data() {
            return {
                albums: [],
                total: null,
                perPage: null,
                page: 1,
            }
        },
        updated() {
            this.$nextTick(() => {
                this.onResize();
            });
        },
        created() {
            if (window) {
                window.addEventListener('resize', this.onResize)
            }
        },

        beforeDestroy() {
            window.removeEventListener('resize', this.onResize)
        },
        mounted() {
            this.fetchAlbums();
            this.$nextTick(() => {
                this.onResize();
            });
        },
        methods: {
            onPageChanged(page) {
                this.$nextTick(() => {
                    this.onResize();
                });
                this.page = page;
                this.fetchAlbums();
            },
            onResize() {
                this.refreshSizes();
            },
            refreshSizes() {
                const responsiveMedias = document.getElementsByClassName('responsive-media');
                Array.from(responsiveMedias).forEach((el) => {
                    el.sizes = `${Math.ceil((el.getBoundingClientRect().width / window.innerWidth) * 100)}vw`;
                });
            },
            fetchAlbums() {
                Vue.axios.get('/api/albums', {
                    params: {
                        page: this.page,
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
                        }
                    );
            }
        }
    }
</script>

<style scoped>

</style>