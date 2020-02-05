<template>
  <section>
    <h1 class="title">
      {{ isCreating ? 'Create album' : 'Update album' }}
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
            <form @submit.prevent="updateOrCreateAlbum">
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
                  maxlength="155"
                  type="textarea"
                  placeholder="Helpful description for SEO"
                />
              </b-field>

              <b-field
                :type="errors.body ? 'is-danger' : ''"
                :message="errors.body ? errors.body[0] : null"
                label="Description"
              >
                <quill-editor
                  ref="myQuillEditor"
                  v-model="album.body"
                  :options="editorOption"
                />
              </b-field>

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
                <b-button
                  :loading="loading"
                  tag="input"
                  native-type="submit"
                  type="is-primary"
                  :value="isCreating ? 'Create' : 'Update'"
                />
                <b-button
                  v-if="!isCreating"
                  :loading="loading"
                  type="is-danger"
                  @click="confirmDeleteAlbum()"
                >
                  Delete
                </b-button>
              </div>
            </form>
          </b-tab-item>
          <b-tab-item
            v-if="!isCreating"
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
            <div>
              <draggable
                v-model="album.medias"
                v-bind="dragOptions"
                @change="updateMediasOrder"
                @start="drag=true"
                @end="drag=false"
              >
                <transition-group
                  class="columns is-multiline"
                  type="transition"
                  :name="!drag ? 'flip-list' : null"
                >
                  <div
                    v-for="picture in album.medias"
                    :key="picture.id"
                    class="column is-two-thirds-tablet is-half-desktop is-one-third-widescreen"
                  >
                    <div class="box has-grab-cursor">
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
                </transition-group>
              </draggable>
            </div>
          </b-tab-item>

          <b-tab-item
            v-if="!isCreating"
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
import 'quill/dist/quill.core.css';
import 'quill/dist/quill.snow.css';
import 'quill/dist/quill.bubble.css';
import Component from 'vue-class-component';
import vue2Dropzone from 'vue2-dropzone';
import 'vue2-dropzone/dist/vue2Dropzone.min.css';
import ShareAlbum from '../../../components/admin/ShareAlbum.vue';
import Album from '../../../models/album';
import { quillEditor } from 'vue-quill-editor';
import {showSuccess, showError} from "../../../admin/toast";
import Category from "../../../models/category";
import Cosplayer from "../../../models/cosplayer";
import FilterableById from "../../../models/interfaces/filterableById";
import Buefy from "../../../admin/Buefy.vue";
import {Prop} from 'vue-property-decorator';
import {DropzoneOptions} from 'dropzone';
import {debounce} from "../../../debounce";
import draggable from 'vuedraggable'

interface AlbumErrorsInterface {
    title?: object;
    meta_description?: object;
    body?: object;
    cosplayers?: object;
    categories?: object;
    published_at?: object;
    private?: object;
}

@Component({
    name: 'AlbumsForm',
    components: {
        vueDropzone: vue2Dropzone,
        quillEditor,
        ShareAlbum,
        draggable,
    },
})
export default class AlbumsForm extends Buefy {

    @Prop({ required: true, type: Boolean })
    protected isCreating: boolean;

    protected drag = false;
    protected errors: AlbumErrorsInterface = {};
    protected album: Album = new Album();
    protected loading = true;
    protected allowNew = false;
    protected allCategories: Category[] = [];
    protected allCosplayers: Cosplayer[] = [];
    protected filteredCategories: Category[] = [];
    protected filteredCosplayers: Cosplayer[] = [];
    protected editorOption: object = {
        placeholder: 'Enter your description...',
        theme: 'snow',
    };
    protected dropzoneOptions: DropzoneOptions = {
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

    dragOptions = {
        animation: 200,
        group: "description",
        disabled: false,
        ghostClass: "ghost"
    };

    created(): void {
        if (this.isCreating) {
            this.album = new Album();
            this.loading = false;
        } else {
            this.fetchAlbum();
        }
    }

    updateOrCreateAlbum(): void {
        this.loading = true;

        if (this.isCreating) {
            this.createAlbum();
        } else {
            this.updateAlbum();
        }

        this.loading = false;
    }

    async fetchAlbum(): Promise<void> {
        this.loading = true;

        try {
            const res = await this.axios.get(`/api/admin/albums/${this.$route.params.slug}`);
            const { data } = res.data;
            this.album = data;
            this.loading = false;
        } catch (exception) {
            showError(this.$buefy,'Unable to fetch album');
            console.error(exception);
        }
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

    async updateAlbum(): Promise<void> {
        if (this.album === undefined) {
            throw new DOMException('Unable to update undefined album.');
        }

        try {
            const res = await this.axios.patch(`/api/admin/albums/${this.$route.params.slug}`, this.album);
            const { data } = res.data;
            this.album = data;
            showSuccess(this.$buefy,'Album updated');
            this.errors = {};
        } catch (exception) {
            showError(
                this.$buefy,
                `Unable to update the album <br><small>${exception.response.data.message}</small>`
            );
            this.errors = exception.response.data.errors;
            throw exception;
        }
    }

    async createAlbum(): Promise<void> {
        try {
            const res = await this.axios.post(`/api/admin/albums/`, this.album);
            const { data } = res.data;
            this.album = data;
            this.errors = {};
            showSuccess(this.$buefy,'Album successfully created');
            await this.$router.push({name: 'admin.albums.edit', params: {slug: this.album.slug}});
        } catch (exception) {
            showError(
                this.$buefy,
                `Unable to create the album <br><small>${exception.response.data.message}</small>`
            );
            this.errors = exception.response.data.errors;
            throw exception;
        }
    }

    async refreshMedias(): Promise<void> {
        if (this.album === undefined) {
            throw new DOMException('Unable to refresh undefined album.');
        }
        try {
            const res = await this.axios.get(`/api/admin/albums/${this.$route.params.slug}`);
            const { data } = res.data;
            this.album.medias = data.medias;
        } catch (exception) {
            showError(
                this.$buefy,
                `Unable to refresh the album <br><small>${exception.response.data.message}</small>`
            );
            throw exception;
        }
    }

    async confirmDeleteAlbum(): Promise<void> {
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

    async deleteAlbum(): Promise<void> {
        if (this.album === undefined) {
            throw new DOMException('Unable to delete undefined album.');
        }

        this.loading = true;

        try {
            await this.axios.delete(`/api/admin/albums/${this.album.slug}`);
            showSuccess(this.$buefy,'Album successfully deleted!');
            await this.$router.push({name: 'admin.albums.index'});
        } catch (exception) {
            showError(this.$buefy,`Unable to delete the picture`);
            throw exception;
        }

        this.loading = false;
    }

    async deleteAlbumPicture(mediaId: number): Promise<void> {
        if (this.album === undefined) {
            throw new DOMException('Unable to delete media from undefined album.');
        }

        try {
            await this.axios.delete(`/api/admin/album-pictures/${this.album.slug}`, {
                data: {
                    // eslint-disable-next-line @typescript-eslint/camelcase
                    media_id: mediaId,
                },
            });
            // Do not refresh here because this process is run async in the backend.
            // this.refreshMedias();
            this.album.medias = this.album.medias.filter(m => m.id !== mediaId);
            showSuccess(this.$buefy, 'Picture successfully deleted!');
        } catch (exception) {
            showError(this.$buefy, 'Unable to delete the picture');
            throw exception;
        }
    }

    async updateMediasOrder(): Promise<void> {
        try {
            const data = { 'media_ids': this.album.medias.map(m => m.id)};
            await this.axios.patch(
                `/api/admin/albums/${this.$route.params.slug}/media-ordering`,
                data
            );
            showSuccess(this.$buefy, 'Pictures successfully re-ordered');
        } catch (exception) {
            showError(this.$buefy, 'Unable to re-ordered the pictures');
            throw exception;
        }
    }

    isCategoryAlreadySelected(filterable: FilterableById): boolean {
        return this.album.categories.some(c => c.id === filterable.id);
    }

    isCategoryNotAlreadySelected(filterable: FilterableById): boolean {
        return ! this.isCategoryAlreadySelected(filterable);
    }

    isCosplayerAlreadySelected(filterable: FilterableById): boolean {
        return this.album.cosplayers.some(c => c.id === filterable.id);
    }

    isCosplayerNotAlreadySelected(filterable: FilterableById): boolean {
        return ! this.isCategoryAlreadySelected(filterable);
    }

    getFilteredCategories(text: string): void {
        const callback = (text: string): void => {
            this.axios.get('/api/admin/categories', {
                    params: {
                        'filter[name]': text,
                    },
                })
                .then(res => res.data)
                .then(res => {
                    this.filteredCategories = res.data.filter(this.isCategoryNotAlreadySelected);
                })
                .catch(err => {
                    // this.filteredCosplayers = [];
                    showError(this.$buefy,'Unable to load categories, maybe you are offline?', () => this.getFilteredCategories(text));
                    throw err;
                });
        };
        debounce(callback, text, 200);
    }

    getFilteredCosplayers(text: string): void {
        const callback = (text: string): void => {
            this.axios.get('/api/admin/cosplayers', {
                    params: {
                        'filter[name]': text,
                    },
                })
                .then(res => res.data)
                .then(res => {
                    this.filteredCosplayers = res.data.filter(this.isCosplayerNotAlreadySelected);
                })
                .catch(err => {
                    // this.filteredCosplayers = [];
                    showError(this.$buefy,'Unable to load cosplayers, maybe you are offline?', () => this.getFilteredCosplayers(text))
                    throw err;
                });
        };
        debounce(callback, text, 200);
    }
}
</script>

<style scoped>
    .has-grab-cursor {
        cursor: grab;
    }
</style>
