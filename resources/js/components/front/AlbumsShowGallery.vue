<template>
  <div v-if="album">
    <div v-if="album.medias && album.medias.length">
      <!--            <h2 class="title is-2 has-text-centered">Pictures</h2>-->
      <!--TODO Add nothing to show-->
      <masonry
        :gutter="{ default: '0.5rem' }"
        :cols="{ default: 3, 1000: 2, 700: 1, 400: 1 }"
      >
        <div
          v-for="(media, index) in album.medias"
          :key="index"
          class="has-margin-top-sm"
          @click="openPicture(media)"
        >
          <figure class="image">
            <img
              v-if="media.src_set"
              :srcset="media.src_set"
              :src="media.thumb"
              :alt="media.name"
              class="responsive-media"
              sizes="1px"
              loading="auto"
            >
            <img
              v-else
              :src="media.thumb"
              :alt="media.name"
              loading="auto"
            >
          </figure>
        </div>
      </masonry>
    </div>

    <div
      v-if="openedPicture"
      class="modal is-active modal-fx-fadeInScale"
    >
      <div
        class="modal-background"
        @click="closePicture()"
      />
      <div class="modal-content is-huge is-image">
        <img
          :srcset="openedPicture.src_set"
          :src="openedPicture.thumb"
          :alt="openedPicture.name"
        >
      </div>
      <button
        class="modal-close is-large"
        aria-label="close"
        @click="closePicture()"
      />
    </div>
  </div>
</template>

<script lang="ts">
import Component from 'vue-class-component';
import { Prop } from 'vue-property-decorator';
import Vue from 'vue';
import 'bulma-modal-fx/src/_js/modal-fx';
import VueMasonry from 'vue-masonry-css';

Vue.use(VueMasonry);

@Component({
  name: 'AlbumsShowGallery'
})
export default class AlbumsShowGallery extends Vue {
    @Prop() readonly data: any;

    protected album: object = null;
    protected openedPicture: object = null;
    loading = false;

    updated (): void {
      this.$nextTick(() => {
        this.onResize();
      });
    }

    created (): void {
      window.addEventListener('resize', this.onResize);
    }

    beforeDestroy (): void {
      window.removeEventListener('resize', this.onResize);
    }

    mounted (): void {
      this.album = this.data.data;

      if (!this.album) {
        console.warn('Album is not eager loaded, requesting...');
        this.fetchAlbum();
      }
      this.$nextTick(() => {
        this.onResize();
      });
    }

    openPicture (media: object): void {
      this.openedPicture = media;
    }

    closePicture (): void {
      this.openedPicture = null;
    }

    onResize (): void {
      this.refreshSizes();
    }

    refreshSizes (): void {
      const responsiveMedias: HTMLCollectionOf<Element> = document.getElementsByClassName(
        'responsive-media'
      );
      Array.from(responsiveMedias).forEach(
        (el: Element): void => {
          (el as HTMLImageElement).sizes = `${Math.ceil(
                    (el.getBoundingClientRect().width / window.innerWidth) * 100
                )}vw`;
        }
      );
    }

    fetchAlbum (): void {
      let slug = window.location.pathname.split('/')[
        window.location.pathname.split('/').length - 1
      ];
      // Workaround until moved to vue router
      if (slug === 'edit') {
        slug = this.$route.params.slug;
      }
      this.axios
        .get(`/api/albums/${slug}`)
        .then(res => res.data)
        .then(res => {
          this.loading = false;
          this.album = res.data;
        })
        .catch(err => {
          this.album = {};
          this.loading = false;
          this.$buefy.snackbar.open({
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
        });
    }
}
</script>
