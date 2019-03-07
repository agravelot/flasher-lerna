<template>
    <div>
        <masonry :cols="{default: 3, 1000: 2, 700: 1, 400: 1}"
                 :gutter="{default: '30px', 700: '15px'}">
            <div v-for="(album, index) in albums" :key="index">
                <a :href="'/albums/' + album.slug">
                    <div class="card album">
                        <div v-if="album.media" class="card-image">
                            <figure>
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
            </div>
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

<!--<img class="responsive-media" srcset="{{ $media->getSrcset($conversion) }}" sizes="1px"
        src="{{ $media->getUrl($conversion) }}" alt="{{ $media->name }}" width="{{ $width }}">-->

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
        mounted() {
            this.fetchAlbums();

            //TODO Fix
            setTimeout(() => {
                const responsiveMedias = document.getElementsByClassName('responsive-media');
                Array.from(responsiveMedias).forEach((el) => {
                    el.sizes = `${Math.ceil((el.getBoundingClientRect().width / window.innerWidth) * 100)}vw`;
                });
            }, 2000)
        },
        methods: {
            onPageChanged(page) {
                this.page = page;
                this.fetchAlbums();
            },
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