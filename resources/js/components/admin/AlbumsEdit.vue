<template>
    <section>
        <h1 class="title">Update album</h1>

        <div class="card">
            <div class="card-content">
                <b-tabs type="is-boxed" size="is-medium" class="block">
                    <b-tab-item label="Album" icon-pack="fas" icon="info">
                        <form @submit.prevent="updateAlbum">
                            <b-field
                                label="Title"
                                :type="errors.title ? 'is-danger' : ''"
                                :message="errors.title ? errors.title[0] : null"
                            >
                                <b-input type="text" v-model="album.title" maxlength="30">
                                </b-input>
                            </b-field>

                            <quill-editor
                                v-model="album.body"
                                ref="myQuillEditor"
                                :options="editorOption"
                            ></quill-editor>

                            <b-field
                                label="Enter some categories"
                                :type="errors.categories ? 'is-danger' : ''"
                                :message="errors.categories ? errors.categories[0] : null"
                            >
                                <b-taginput
                                    v-model="album.categories"
                                    :data="filteredCategories"
                                    autocomplete
                                    :allow-new="false"
                                    field="name"
                                    placeholder="Add a category"
                                    icon-pack="fas"
                                    icon="tag"
                                    @typing="getFilteredCategories"
                                >
                                </b-taginput>
                            </b-field>

                            <b-field
                                label="Enter some cosplayers"
                                :type="errors.cosplayers ? 'is-danger' : ''"
                                :message="errors.cosplayers ? errors.cosplayers[0] : null"
                            >
                                <b-taginput
                                    v-model="album.cosplayers"
                                    :data="filteredCosplayers"
                                    autocomplete
                                    :allow-new="false"
                                    field="name"
                                    placeholder="Add a cosplayer"
                                    icon="user-tag"
                                    @typing="getFilteredCosplayers"
                                >
                                </b-taginput>
                            </b-field>

                            <b-field
                                label="Should this album be published?"
                                :type="errors.published_at ? 'is-danger' : ''"
                                :message="errors.published_at ? errors.published_at[0] : null"
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
                                label="Should it be accessible publicly?"
                                :type="errors.private ? 'is-danger' : ''"
                                :message="errors.private ? errors.private[0] : null"
                            >
                                <div class="field">
                                    <b-switch
                                        v-model.numeric="album.private"
                                        :true-value="0"
                                        :false-value="1"
                                    >
                                        {{ album.private ? 'No' : 'Yes' }}
                                    </b-switch>
                                </div>
                            </b-field>
                            <div class="buttons">
                                <button class="button is-primary">Update</button>
                                <a
                                    class="button is-bottom-right is-danger"
                                    @click="confirmDeleteAlbum()"
                                >
                                    Delete
                                </a>
                            </div>
                        </form>
                    </b-tab-item>
                    <b-tab-item label="Pictures" icon-pack="fas" icon="images">
                        <vue-dropzone
                            ref="myVueDropzone"
                            :options="dropzoneOptions"
                            v-on:vdropzone-sending="sendingEvent"
                            v-on:vdropzone-complete="refreshMedias"
                            class="has-margin-bottom-md"
                        ></vue-dropzone>
                        <div class="columns is-multiline">
                            <div
                                v-for="picture in album.medias"
                                class="column is-two-thirds-tablet is-half-desktop is-one-third-widescreen"
                            >
                                <img :src="picture.thumb" :alt="picture.name" />
                                <a
                                    class="button has-text-danger"
                                    @click="deleteAlbumPicture(album.slug, picture.id)"
                                >
                                    Delete
                                </a>
                            </div>
                        </div>
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
import Album from '../../models/album';

@Component({
    name: 'AlbumsEdit',
    components: {
        vueDropzone: vue2Dropzone,
        'album-desc': AlbumDesc,
    },
    extends: AlbumDesc,
})
export default class AlbumsEdit extends AlbumDesc {
    allowNew: boolean = false;
    dropzoneOptions: object = {
        url: '/api/admin/album-pictures',
        thumbnailWidth: 200,
        addRemoveLinks: true,
        parallelUploads: 5,
        // Setup chunking
        chunking: true,
        method: 'POST',
        maxFilesize: 400000000,
        chunkSize: 1000000,
        autoProcessQueue: false,
        retryChunks: true,
        retryChunksLimit: 15,
        maxThumbnailFilesize: 25,
        // If true, the individual chunks of a file are being uploaded simultaneously.
        // parallelChunkUploads: true,
        acceptedFiles: 'image/*',
        dictDefaultMessage: "<i class='fas fa-images'></i> Upload",
        headers: {
            'X-CSRF-Token': (<HTMLMetaElement>(
                document.head.querySelector('meta[name="csrf-token"]')
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
        formData.append('album_slug', <string>this.album.slug);
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
        this.$dialog.confirm({
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
            .then(res => {
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
            .then(res => {
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
