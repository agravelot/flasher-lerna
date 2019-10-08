<template>
    <div>
        <!--TODO Add nothing to show-->
        <masonry
            :gutter="{ default: '30px', 700: '15px' }"
            :cols="{ default: 3, 1000: 2, 700: 1, 400: 1 }"
        >
            <a
                v-for="(album, index) in albums"
                :key="index"
                :href="'/albums/' + album.slug"
                class="has-margin-right-md"
            >
                <div class="card album">
                    <div v-if="album.media" class="card-image">
                        <figure class="image">
                            <img
                                v-if="album.media.src_set"
                                class="responsive-media"
                                :srcset="album.media.src_set"
                                :alt="album.media.name"
                                sizes="1px"
                                loading="auto"
                            />
                            <img
                                v-else
                                :src="album.media.thumb"
                                :alt="album.media.name"
                                loading="auto"
                            />
                        </figure>
                    </div>
                    <div class="card-content">
                        <div class="content">
                            <h3 class="title is-5">{{ album.title }}</h3>
                        </div>
                    </div>
                </div>
            </a>
        </masonry>

        <div class="has-margin-md"></div>

        <div v-if="total > perPage">
            <b-pagination
                v-on:change="onPageChanged"
                :total="total"
                :current.sync="page"
                order="is-centered"
                :per-page="perPage"
            >
            </b-pagination>
        </div>
    </div>
</template>

<script lang="ts">
import Component from 'vue-class-component';
import Buefy from '../../admin/Buefy.vue';
import { Prop } from 'vue-property-decorator';
import VueMasonry from 'vue-masonry-css';

Buefy.use(VueMasonry);

@Component({
    name: 'AlbumsMasonry',
})
export default class AlbumsMasonry extends Buefy {
    @Prop() readonly data: any;

    protected albums: Array<object> = [];
    protected total: number = 0;
    protected perPage: number = 0;
    protected page: number = 1;
    protected loading: boolean = false;

    updated(): void {
        this.$nextTick(() => {
            this.onResize();
        });
    }

    created(): void {
        window.addEventListener('resize', this.onResize);
    }

    beforeDestroy(): void {
        window.removeEventListener('resize', this.onResize);
    }

    mounted() {
        this.albums = this.data.data;
        this.perPage = this.data.meta.per_page;
        this.total = this.data.meta.total;
        this.loading = false;
        if (!this.albums) {
            console.warn('Albums not eager loaded, requesting server...');
            this.fetchAlbums();
        }
        this.$nextTick(() => {
            this.onResize();
        });
    }

    onPageChanged(page: number): void {
        this.page = page;
        this.fetchAlbums();
    }

    onResize() {
        this.refreshSizes();
    }

    refreshSizes(): void {
        const responsiveMedias: HTMLCollectionOf<Element> = document.getElementsByClassName(
            'responsive-media'
        );
        Array.from(responsiveMedias).forEach((el: Element) => {
            (<HTMLImageElement>el).sizes = `${Math.ceil(
                (el.getBoundingClientRect().width / window.innerWidth) * 100
            )}vw`;
        });
    }

    fetchAlbums(): void {
        this.axios
            .get('/api/albums', {
                params: {
                    page: this.page,
                },
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
                this.$buefy.snackbar.open({
                    message: 'Unable to load albums, maybe you are offline?',
                    type: 'is-danger',
                    position: 'is-top',
                    actionText: 'Retry',
                    indefinite: true,
                    onAction: () => {
                        this.fetchAlbums();
                    },
                });
                throw err;
            });
    }
}
</script>
