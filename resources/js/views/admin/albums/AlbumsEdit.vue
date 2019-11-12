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
                  @typing="getFilteredCategories"
                  autocomplete
                  field="name"
                  placeholder="Add a category"
                  icon="tag"
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
                  @typing="getFilteredCosplayers"
                  autocomplete
                  field="name"
                  placeholder="Add a cosplayer"
                  icon="user-tag"
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
                    v-model.numeric="album.private"
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
                  @click="confirmDeleteAlbum()"
                  class="button is-bottom-right is-danger"
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
              v-on:vdropzone-sending="sendingEvent"
              v-on:vdropzone-complete="refreshMedias"
              class="has-margin-bottom-md"
            />
            <div class="columns is-multiline">
              <div
                v-for="picture in album.medias"
                class="column is-two-thirds-tablet is-half-desktop is-one-third-widescreen"
              >
                <img
                  :src="picture.thumb"
                  :alt="picture.name"
                >
                <a
                  @click="deleteAlbumPicture(album.slug, picture.id)"
                  class="button has-text-danger"
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
import AlbumDesc from './AlbumDesc.vue';
import ShareAlbum from '../../../components/admin/ShareAlbum.vue';
import Album from '../../../models/album';
import { quillEditor } from 'vue-quill-editor';

@Component({
    name: 'AlbumsEdit',
    components: {
        vueDropzone: vue2Dropzone,
        quillEditor,
        AlbumDesc,
        ShareAlbum,
    },
    extends: AlbumDesc,
})
export default class AlbumsEdit extends AlbumDesc {
    protected album: Album;
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
        chunkSize: 1000000,
        //autoProcessQueue: false,
        retryChunks: true,
        retryChunksLimit: 15,
        maxThumbnailFilesize: 25,
        // If true, the individual chunks of a file are being uploaded simultaneously.
        // parallelChunkUploads: true,
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
                throw err;
            });
    }

    sendingEvent(file: File, xhr: XMLHttpRequest, formData: FormData): void {
        if (!this.album.slug) {
            throw new DOMException('album slug is null');
        }
        formData.append('album_slug', this.album.slug as string);
    }

    updateAlbum(): void {
        this.axios
            .patch(`/api/admin/albums/${this.$route.params.slug}`, this.album)
            .then(res => res.data)
            .then(res => {
                this.album = res.data;
                this.showSuccess('Album updated');
                this.$router.push({ name: 'admin.albums.edit', params: { slug: this.album.slug } });
            })
            .catch(err => {
                this.showError(
                    `Unable to update the album <br><small>${err.response.data.message}</small>`
                );
                this.errors = err.response.data.errors;
                throw err;
            });
    }

    refreshMedias(): void {
        this.axios
            .get(`/api/admin/albums/${this.$route.params.slug}`)
            .then(res => res.data)
            .then(res => {
                this.album.medias = res.data.medias;
            })
            .catch(err => {
                this.showError(
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
        this.axios
            .delete(`/api/admin/albums/${this.album.slug}`)
            .then(() => {
                this.$router.push({ name: 'admin.albums.index' });
                this.showSuccess('Album successfully deleted!');
            })
            .catch(err => {
                this.showError(`Unable to delete the picture`);
                throw err;
            });
    }

    deleteAlbumPicture(albumSlug: string, mediaId: number): void {
        this.axios
            .delete(`/api/admin/album-pictures/${albumSlug}`, {
                data: {
                    media_id: mediaId,
                },
            })
            .then(() => {
                this.refreshMedias();
                this.showSuccess('Picture successfully deleted!');
            })
            .catch(err => {
                this.showError('Unable to delete the picture');
                throw err;
            });
    }
}
</script>
