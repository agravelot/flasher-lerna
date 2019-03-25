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

        <div class="column is-10 is-offset-1">
        <div class="card article">
            <div class="card-content has-text-centered">
                <div class="field has-addons">
                    <div v-if="album.download" class="control">
                        <a class="button" :href="album.download">
                            <span class="icon is-small"><i class="fas fa-download"></i></span>
                            <span>Download</span>
                        </a>
                    </div>
                    <div v-if="album.edit" class="control">
                        <a class="button" :href="album.edit">
                            <span class="icon is-small"><i class="fas fa-edit"></i></span>
                            <span>Edit</span>
                        </a>
                    </div>
                </div>

                <div class="tags has-addons level-item">
                    <span class="tag is-rounded is-info">@{{ album.user.name }}</span>
                    <span class="tag is-rounded">{{ album.created_at }}</span>
                    </div>
                <div v-if="album.body" class="content article-body">
                    <p class="has-text-justified" v-html="album.body"></p>
                    </div>
                <div v-if="album.categories" class="tags">
                    <!--@each('categories.partials._category_tag', $album->categories, 'category')-->
                    </div>
                <div v-if="album.cosplayers" class="columns is-multiline is-mobile">
                    <!--@each('cosplayers.partials._cosplayer_badge', $album->cosplayers, 'cosplayer')-->

                </div>
            </div>
        </div>

         <!--{{&#45;&#45;@foreach ($album->getMedia('pictures') as $key => $picture)&#45;&#45;}}-->
    <!--{{&#45;&#45;<div id="modal-{{ $key }}" class="modal modal-fx-fadeInScale">&#45;&#45;}}-->
    <!--{{&#45;&#45;<div class="modal-background"></div>&#45;&#45;}}-->
    <!--{{&#45;&#45;<div class="modal-content is-huge is-image">&#45;&#45;}}-->
        <!--{{&#45;&#45;<img srcset="{{ $picture->getSrcset() }}" src="{{ $picture->getUrl() }}" alt="{{ $picture->name }}">&#45;&#45;}}-->
        <!--{{&#45;&#45;</div>&#45;&#45;}}-->
    <!--{{&#45;&#45;<button class="modal-close is-large" aria-label="{{ __('close') }}"></button>&#45;&#45;}}-->
    <!--{{&#45;&#45;</div>&#45;&#45;}}-->
    <!--{{&#45;&#45;@endforeach&#45;&#45;}}-->
    </div>
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
                let slug = window.location.pathname.split('/')[window.location.pathname.split('/').length -1];
                // Workaround until moved to vue router
                if (slug === 'edit') {
                    slug = this.$route.params.slug;
                }
                this.axios.get(`/api/admin/albums/${slug}`)
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