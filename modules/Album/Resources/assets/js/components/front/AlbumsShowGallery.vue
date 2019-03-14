<template>
    <div class="container is-centered has-margin-top-md">
        <h1 class="title is-2 has-text-centered">{{ album.title }}</h1>

        <!--TODO Add nothing to show-->
        <masonry :gutter="{default: '0px'}"
                 :cols="{default: 3, 1000: 2, 700: 1, 400: 1}">
            <a v-for="(media, index) in album.medias" :key="index">
                <figure class="image">
                    <img v-if="media.src_set" class="responsive-media" :srcset="media.src_set" :src="media.thumb"
                         :alt="media.name" sizes="1px">
                    <img v-else :src="media.thumb" :alt="media.name">
                </figure>
            </a>
        </masonry>
    </div>
</template>

<script>
    export default {
        name: "AlbumsShowGallery",
        data() {
            return {
                album: {},
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
            this.fetchAlbum();
            this.$nextTick(() => {
                this.onResize();
            });
        },
        methods: {
            onResize() {
                this.refreshSizes();
            },
            refreshSizes() {
                const responsiveMedias = document.getElementsByClassName('responsive-media');
                Array.from(responsiveMedias).forEach((el) => {
                    el.sizes = `${Math.ceil((el.getBoundingClientRect().width / window.innerWidth) * 100)}vw`;
                });
            },
            fetchAlbum() {
                this.axios.get(`/api/albums/${window.location.pathname.split('/')[window.location.pathname.split('/').length -1]}`)
                    .then(res => res.data)
                    .then(res => {
                        this.loading = false;
                        this.album = res.data;
                    })
                    .catch(err => {
                            this.album = {};
                            this.loading = false;
                            this.$snackbar.open({
                                message: 'Unable to load album, maybe you are offline?',
                                type: 'is-danger',
                                position: 'is-top',
                                actionText: 'Retry',
                                indefinite: true,
                                onAction: () => {
                                    this.fetchAlbum();
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