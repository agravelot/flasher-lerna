<template>
  <section>
    <h1 class="title">
      Update album
    </h1>

    <div class="card">
      <div class="card-content">
        <b-tabs
          type="is-boxed"
          size="is-medium"
          class="block"
        >
          <b-tab-item
            label="Album"
            icon="info"
          >
            <form @submit.prevent="updateAlbum">
              <b-field
                :type="errors.title ? 'is-danger' : ''"
                :message="errors.title ? errors.title[0] : null"
                label="Title"
              >
                <b-input
                  v-model="album.title"
                  type="text"
                  maxlength="30"
                />
              </b-field>

              <b-field
                :type="errors.meta_description ? 'is-danger' : ''"
                :message="errors.meta_description ? errors.meta_description[0] : null"
                label="Meta description"
              >
                <b-input
                  v-model="album.meta_description"
                  maxlength="254"
                  type="textarea"
                  placeholder="Helpful description for SEO"
                />
              </b-field>

              <quill-editor
                ref="myQuillEditor"
                v-model="album.body"
                :options="editorOption"
              />

              <b-field
                :type="errors.categories ? 'is-danger' : ''"
                :message="errors.categories ? errors.categories[0] : null"
                label="Enter some categories"
              >
                <b-taginput
                  v-model="album.categories"
                  :data="filteredCategories"
                  :allow-new="false"
                  autocomplete
                  field="name"
                  placeholder="Add a category"
                  icon="tag"
                  @typing="getFilteredCategories"
                />
              </b-field>

              <b-field
                :type="errors.cosplayers ? 'is-danger' : ''"
                :message="errors.cosplayers ? errors.cosplayers[0] : null"
                label="Enter some cosplayers"
              >
                <b-taginput
                  v-model="album.cosplayers"
                  :data="filteredCosplayers"
                  :allow-new="false"
                  autocomplete
                  field="name"
                  placeholder="Add a cosplayer"
                  icon="user-tag"
                  @typing="getFilteredCosplayers"
                />
              </b-field>

              <b-field
                :type="errors.published_at ? 'is-danger' : ''"
                :message="errors.published_at ? errors.published_at[0] : null"
                label="Should this album be published?"
              >
                <div class="field">
                  <b-switch
                    v-model="album.published_at"
                    :true-value="album.published_at || new Date().toISOString()"
                    :false-value="null"
                  >
                    {{ album.published_at ? 'Yes' : 'No' }}
                  </b-switch>
                </div>
              </b-field>

              <b-field
                :type="errors.private ? 'is-danger' : ''"
                :message="errors.private ? errors.private[0] : null"
                label="Should it be accessible publicly?"
              >
                <div class="field">
                  <b-switch
                    v-model="album.private"
                    :true-value="false"
                    :false-value="true"
                  >
                    {{ album.private ? 'No' : 'Yes' }}
                  </b-switch>
                </div>
              </b-field>
              <div class="buttons">
                <button class="button is-primary">
                  Update
                </button>
                <a
                  class="button is-bottom-right is-danger"
                  @click="confirmDeleteAlbum()"
                >
                  Delete
                </a>
              </div>
            </form>
          </b-tab-item>
          <b-tab-item
            label="Pictures"
            icon="images"
          >
            <vue-dropzone
              id="dropzone"
              ref="myVueDropzone"
              :options="dropzoneOptions"
              class="has-margin-bottom-md"
              @vdropzone-sending="sendingEvent"
              @vdropzone-complete="refreshMedias"
            />
            <div class="columns is-multiline">
              <div
                v-for="(picture, index) in album.medias"
                :key="index"
                class="column is-two-thirds-tablet is-half-desktop is-one-third-widescreen"
              >
                <img
                  :src="picture.thumb"
                  :alt="picture.name"
                >
                <a
                  class="button has-text-danger"
                  @click="deleteAlbumPicture(picture.id)"
                >
                  Delete
                </a>
              </div>
            </div>
          </b-tab-item>

          <b-tab-item
            label="Share"
            icon="share"
          >
            <h3 class="title is-3">
              Share
            </h3>
            <share-album :album="album" />
          </b-tab-item>
        </b-tabs>
      </div>
    </div>
  </section>
</template>

<script lang="ts">
import Component from 'vue-class-component';
import vue2Dropzone from 'vue2-dropzone';
import 'vue2-dropzone/dist/vue2Dropzone.min.css';
import AlbumBase from './AlbumBase.vue';
import ShareAlbum from '../../../components/admin/ShareAlbum.vue';
import Album from '../../../models/album';
import { quillEditor } from 'vue-quill-editor';
import {showSuccess, showError} from "../../../admin/toast";

@Component({
    name: 'AlbumsEdit',
    components: {
        vueDropzone: vue2Dropzone,
        quillEditor,
        AlbumBase,
        ShareAlbum,
    },
    extends: AlbumBase,
})
export default class AlbumsEdit extends AlbumBase {
    protected album: Album | undefined;
    protected allowNew = false;
    protected dropzoneOptions: object = {
        url: '/api/admin/album-pictures',
        thumbnailWidth: 200,
        addRemoveLinks: true,
        parallelUploads: 5,
        // Setup chunking
        chunking: true,
        method: 'POST',
        maxFilesize: 400000000,
        chunkSize: 5000000,
        //autoProcessQueue: false,
        retryChunks: true,
        retryChunksLimit: 15,
        maxThumbnailFilesize: 25,
        // If true, the individual chunks of a file are being uploaded simultaneously.
        parallelChunkUploads: false,
        acceptedFiles: 'image/*',
        dictDefaultMessage: "<i class='fas fa-images'></i> Upload",
        headers: {
            'X-CSRF-Token': ((
                document.head.querySelector('meta[name="csrf-token"]') as HTMLMetaElement
            )).content,
        },
    };

    created(): void {
        this.fetchAlbum();
    }

    fetchAlbum(): void {
        this.axios
            .get(`/api/admin/albums/${this.$route.params.slug}`)
            .then(res => res.data)
            .then(res => {
                this.album = res.data;
            })
            .catch(err => {
                showError('Unable to fetch album');
                throw err;
            });
    }

    sendingEvent(file: File, xhr: XMLHttpRequest, formData: FormData): void {
        if (this.album === undefined) {
            throw new DOMException('Unable to send media with undefined album.');
        }
        if (!this.album.slug) {
            throw new DOMException('album slug is null');
        }
        formData.append('album_slug', this.album.slug as string);
    }

    updateAlbum(): void {
        if (this.album === undefined) {
            throw new DOMException('Unable to update undefined album.');
        }
        this.axios
            .patch(`/api/admin/albums/${this.$route.params.slug}`, this.album)
            .then(res => res.data)
            .then(res => {
                this.errors = {};
                this.album = res.data;
                showSuccess('Album updated');
                this.$router.push({ name: 'admin.albums.edit', params: { slug: this.album.slug } });
            })
            .catch(err => {
                showError(
                    `Unable to update the album <br><small>${err.response.data.message}</small>`
                );
                this.errors = err.response.data.errors;
                throw err;
            });
    }

    refreshMedias(): void {
        if (this.album === undefined) {
            throw new DOMException('Unable to refresh undefined album.');
        }
        this.axios
            .get(`/api/admin/albums/${this.$route.params.slug}`)
            .then(res => res.data)
            .then(res => {
                this.album.medias = res.data.medias;
            })
            .catch(err => {
                showError(
                    `Unable to refresh the album <br><small>${err.response.data.message}</small>`
                );
                throw err;
            });
    }

    confirmDeleteAlbum(): void {
        this.$buefy.dialog.confirm({
            title: 'Deleting Album',
            message:
                'Are you sure you want to <b>delete</b> this album? This action cannot be undone.',
            confirmText: 'Delete Album',
            type: 'is-danger',
            hasIcon: true,
            onConfirm: () => this.deleteAlbum(),
        });
    }

    deleteAlbum(): void {
        if (this.album === undefined) {
            throw new DOMException('Unable to delete undefined album.');
        }
        this.axios
            .delete(`/api/admin/albums/${this.album.slug}`)
            .then(() => {
                this.$router.push({ name: 'admin.albums.index' });
                showSuccess('Album successfully deleted!');
            })
            .catch(err => {
                showError(`Unable to delete the picture`);
                throw err;
            });
    }

    deleteAlbumPicture(mediaId: number): void {
        if (this.album === undefined) {
            throw new DOMException('Unable to delete media from undefined album.');
        }
        this.axios
            .delete(`/api/admin/album-pictures/${this.album.slug}`, {
                data: {
                    // eslint-disable-next-line @typescript-eslint/camelcase
                    media_id: mediaId,
                },
            })
            .then(() => {
                this.refreshMedias();
                showSuccess('Picture successfully deleted!');
            })
            .catch(err => {
                showError('Unable to delete the picture');
                throw err;
            });
    }
}
</script>
