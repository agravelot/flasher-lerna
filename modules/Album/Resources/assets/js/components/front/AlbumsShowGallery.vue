<template>

    <div v-if="this.album" class="container is-centered has-margin-top-md">
        <h1 class="title is-1 has-text-centered">{{ album.title }}</h1>

        <div v-if="album.links && (album.links.download || album.links.edit)" class="field has-addons">
            <div v-if="album.links.download" class="control">
                <a class="button" :href="album.links.download">
                    <span class="icon is-small"><i class="fas fa-download"></i></span>
                    <span>Download</span>
                </a>
            </div>
            <div v-if="album.links.edit" class="control">
                <a class="button" :href="album.links.edit">
                    <span class="icon is-small"><i class="fas fa-edit"></i></span>
                    <span>Edit</span>
                </a>
            </div>
        </div>

        <div class="column is-10 is-offset-1">
            <div v-if="album.body" class="content article-body">
                <p class="has-text-justified" v-html="album.body"></p>
            </div>
            <div v-if="album.categories" class="tags">
                <span v-for="category in album.categories" class="tag">
                    <a :href="category.links.related">{{ category.name }}</a>
                </span>
            </div>

            <!--TODO Add nothing to show-->
            <masonry :gutter="{default: '0px'}"
                     :cols="{default: 3, 1000: 2, 700: 1, 400: 1}"
                     class="has-margin-bottom-md">
                <a v-for="(media, index) in album.medias" :key="index" @click="openPicture(media)">
                    <figure class="image">
                        <img v-if="media.src_set" class="responsive-media" :srcset="media.src_set" :src="media.thumb"
                             :alt="media.name" sizes="1px" loading="auto">
                        <img v-else :src="media.thumb" :alt="media.name" loading="auto">
                    </figure>
                </a>
            </masonry>

            <div v-if="album.cosplayers">
                <h2 class="title is-2">Cosplayers</h2>

                <div class="columns is-multiline is-mobile">
                    <div v-for="cosplayer in album.cosplayers" class="column is-2-desktop is-3-tablet is-4-mobile">
                        <figure v-if="cosplayer.thumb" class="is-centered image is-96x96">
                            <img class="is-rounded" :src="cosplayer.thumb">
                        </figure>
                        <figure v-else class="is-centered avatar-circle">
                            <span class="initials"> {{ cosplayer.name.match(/\b\w/g).join('').substring(0, 2).toUpperCase() }}</span>
                        </figure>
                        <a :href="cosplayer.links.related">
                            <p class="has-text-centered has-margin-top-sm">
                                {{ cosplayer.name }}
                            </p>
                        </a>
                    </div>
                </div>
            </div>

            <div v-if="openedPicture" class="modal is-active modal-fx-fadeInScale">
                <div class="modal-background" @click="closePicture()"></div>
                <div class="modal-content is-huge is-image">
                    <img :srcset="openedPicture.src_set" :src="openedPicture.thumb" :alt="openedPicture.name">
                </div>
                <button class="modal-close is-large" aria-label="close" @click="closePicture()"></button>
            </div>

        </div>
    </div>

</template>

<script lang="ts">
    import Component from 'vue-class-component';
    import {Prop} from "vue-property-decorator";
    import VueBuefy from "../../../../../../../resources/js/buefy";

    @Component({
        name: "AlbumsShowGallery",
    })
    export default class AlbumsShowGallery extends VueBuefy {

        @Prop() readonly data: any;

        protected album: object = null;
        protected openedPicture: object = null;
        loading: boolean = false;

        updated(): void {
            this.$nextTick(() => {
                this.onResize();
            });
        }

        created(): void {
            window.addEventListener('resize', this.onResize)
        }

        beforeDestroy(): void {
            window.removeEventListener('resize', this.onResize)
        }

        mounted(): void {
            this.album = this.data.data;

            if (!this.album) {
                console.warn('Album is not eager loaded, requesting...');
                this.fetchAlbum();
            }
            this.$nextTick(() => {
                this.onResize();
            });
        }

        openPicture(media: object): void {
            this.openedPicture = media;
        }

        closePicture(): void {
            this.openedPicture = null;
        }

        onResize(): void {
            this.refreshSizes();
        }

        refreshSizes(): void {
            const responsiveMedias: HTMLCollectionOf<Element> = document.getElementsByClassName('responsive-media');
            Array.from(responsiveMedias).forEach((el: Element): void => {
                (<HTMLImageElement>el).sizes = `${Math.ceil((el.getBoundingClientRect().width / window.innerWidth) * 100)}vw`;
            });
        }

        fetchAlbum(): void {
            let slug = window.location.pathname.split('/')[window.location.pathname.split('/').length - 1];
            // Workaround until moved to vue router
            if (slug === 'edit') {
                slug = this.$route.params.slug;
            }
            this.axios.get(`/api/albums/${slug}`)
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
</script>
