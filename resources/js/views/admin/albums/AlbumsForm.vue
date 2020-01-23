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
            <form @submit.prevent="sendOrCreateAlbum">
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
                <button class="button is-primary">
                  {{ isCreating ? 'Create' : 'Update' }}
                </button>
                <a
                  v-if="!isCreating"
                  class="button is-bottom-right is-danger"
                  @click="confirmDeleteAlbum()"
                >
                  Delete
                </a>
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
import {Prop} from "vue-property-decorator";
import {DropzoneOptions} from "dropzone";

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
    name: 'AlbumsForm.vue',
    components: {
        vueDropzone: vue2Dropzone,
        quillEditor,
        ShareAlbum,
    },
})
export default class AlbumsForm extends Buefy {

    @Prop({ required: true, type: Boolean })
    protected isCreating: boolean;

    protected errors: AlbumErrorsInterface = {};
    protected album: Album = new Album();
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

    created(): void {
        if (this.isCreating) {
            this.album = new Album();
        } else {
            this.fetchAlbum();
        }
    }

    sendOrCreateAlbum(): void {
        if (this.isCreating) {
            this.createAlbum();
        } else {
            this.updateAlbum();
        }
    }

    fetchAlbum(): void {
        this.axios
            .get(`/api/admin/albums/${this.$route.params.slug}`)
            .then(res => res.data)
            .then(res => {
                this.album = res.data;
            })
            .catch(err => {
                showError(this.$buefy,'Unable to fetch album');
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
                showSuccess(this.$buefy,'Album updated');
                this.$router.push({ name: 'admin.albums.edit', params: { slug: this.album.slug } });
            })
            .catch(err => {
                showError(
                    this.$buefy,
                    `Unable to update the album <br><small>${err.response.data.message}</small>`
                );
                this.errors = err.response.data.errors;
                throw err;
            });
    }

    createAlbum(): void {
        this.axios
            .post(`/api/admin/albums/`, this.album)
            .then(res => res.data)
            .then(res => {
                this.errors = {};
                showSuccess(this.$buefy,'Album successfully created');
                this.$router.push({ name: 'admin.albums.edit', params: { slug: res.data.slug } });
            })
            .catch(err => {
                showError(
                    this.$buefy,
                    `Unable to create the album <br><small>${err.response.data.message}</small>`
                );
                this.errors = err.response.data.errors;
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
                    this.$buefy,
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
                showSuccess(this.$buefy,'Album successfully deleted!');
            })
            .catch(err => {
                showError(this.$buefy,`Unable to delete the picture`);
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
                showSuccess(this.$buefy,'Picture successfully deleted!');
            })
            .catch(err => {
                showError(this.$buefy,'Unable to delete the picture');
                throw err;
            });
    }

    debounce(callback: any, text: string, time: number): void {
        (window as any).lastCall = (window as any).lastCall ? (window as any).lastCall : 0;

        if (Date.now() - (window as any).lastCall > time) {
            (window as any).timeout = setTimeout(() => callback(text), time)
        } else {
            clearTimeout(((window as any).timeout as number));
            (window as any).timeout = setTimeout(() => callback(text), time)
        }
        (window as any).lastCall = Date.now()
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
            this.axios
                .get('/api/admin/categories', {
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
        this.debounce(callback, text, 200);
    }

    getFilteredCosplayers(text: string): void {
        const callback = (text: string): void => {
            this.axios
                .get('/api/admin/cosplayers', {
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
        this.debounce(callback, text, 200);
    }
}
</script>
