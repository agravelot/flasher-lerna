<template>
    <div>
        <h1 class="title">Update album</h1>
        <b-tabs type="is-boxed" size="is-medium" class="block">
            <b-tab-item label="Album" icon-pack="fas" icon="info">
                <form @submit.prevent="updateAlbum">

                    <b-field label="Title"
                             :type="errors.title ? 'is-danger' : ''"
                             :message="errors.title ? errors.title[0] : null">
                        <b-input type="text"
                                 v-model="album.title"
                                 maxlength="30">
                        </b-input>
                    </b-field>

                    <quill-editor v-model="album.body" ref="myQuillEditor" :options="editorOption"></quill-editor>

                    <b-field label="Enter some categories"
                             :type="errors.categories ? 'is-danger' : ''"
                             :message="errors.categories ? errors.categories[0] : null">
                        <b-taginput
                                v-model="album.categories"
                                :data="filteredCategories"
                                autocomplete
                                :allow-new="false"
                                field="name"
                                placeholder="Add a category"
                                icon-pack="fas"
                                icon="tag"
                                @typing="getFilteredCategories">
                        </b-taginput>
                    </b-field>

                    <b-field label="Enter some cosplayers"
                             :type="errors.cosplayers ? 'is-danger' : ''"
                             :message="errors.cosplayers ? errors.cosplayers[0] : null">
                        <b-taginput
                                v-model="album.cosplayers"
                                :data="filteredCosplayers"
                                autocomplete
                                :allow-new="false"
                                field="name"
                                placeholder="Add a cosplayer"
                                icon-pack="fas"
                                icon="user-tag"
                                @typing="getFilteredCosplayers">
                        </b-taginput>
                    </b-field>

                    <b-field label="Should this album be published?"
                             :type="errors.published_at ? 'is-danger' : ''"
                             :message="errors.published_at ? errors.published_at[0] : null">
                        <div class="field">
                            <b-switch v-model="album.published_at"
                                      :true-value="album.published_at || new Date().toISOString()"
                                      :false-value=null>
                                {{ album.published_at ? 'Yes' : 'No' }}
                            </b-switch>
                        </div>
                    </b-field>

                    <b-field label="Should it be accessible publicly?"
                             :type="errors.private ? 'is-danger' : ''"
                             :message="errors.private ? errors.private[0] : null">
                        <div class="field">
                            <b-switch v-model.numeric="album.private"
                                      :true-value=0
                                      :false-value=1>
                                {{ album.private ? 'No' : 'Yes' }}
                            </b-switch>
                        </div>
                    </b-field>

                    <button class="button is-primary">Update</button>
                </form>

            </b-tab-item>
            <b-tab-item label="Pictures" icon-pack="fas" icon="images">
                <vue-dropzone ref="myVueDropzone" :options="dropzoneOptions" v-on:vdropzone-sending="sendingEvent"
                              v-on:vdropzone-complete="refreshMedias" class="has-margin-bottom-md"></vue-dropzone>
                <div class="columns">
                    <div v-for="picture in album.medias" class="column">
                        <img :src="picture.thumb" :alt="picture.name">
                        <a class="button has-text-danger" @click="deleteAlbumPicture(album.slug, picture.id)">
                            Delete
                        </a>
                    </div>
                </div>
            </b-tab-item>
            <b-tab-item label="Prévualisation" icon-pack="fas" icon="eye">
                <album-show-gallery></album-show-gallery>
            </b-tab-item>
        </b-tabs>

    </div>
</template>

<script lang="ts">
    import Component from 'vue-class-component';
    import vue2Dropzone from 'vue2-dropzone';
    import 'vue2-dropzone/dist/vue2Dropzone.min.css'
    import AlbumDesc from './AlbumDesc';
    import AlbumsShowGallery from './AlbumsShowGallery';

    @Component({
        name: "AlbumsEdit",
        components: {
            vueDropzone: vue2Dropzone,
            'album-desc': AlbumDesc,
            'album-show-gallery': AlbumsShowGallery,
        },
        extends: AlbumDesc,
    })
    export default class AlbumsEdit extends AlbumDesc {

        isSelectOnly: boolean = false;
        allowNew: boolean = false;
        dropzoneOptions: object = {
            url: '/api/admin/album-pictures',
            thumbnailWidth: 200,
            addRemoveLinks: true,
            // Setup chunking
            chunking: true,
            method: 'POST',
            maxFilesize: 400000000,
            chunkSize: 1000000,
            // If true, the individual chunks of a file are being uploaded simultaneously.
            // parallelChunkUploads: true,
            acceptedFiles: 'image/*',
            retryChunks: true,
            dictDefaultMessage: "<i class='fas fa-images'></i> Upload",
            headers: {
                'X-CSRF-Token': (<HTMLMetaElement>document.head.querySelector('meta[name="csrf-token"]')).content
            }
        };

        created(): void {
            this.fetchData();
        }

        fetchData(): void {
            this.axios.get(`/api/admin/albums/${this.$route.params.slug}`)
                .then(res => res.data)
                .then(res => {
                    this.album = res.data;
                })
                .catch(err => {
                    throw err;
                });
            this.axios.get('/api/admin/categories')
                .then(res => res.data)
                .then(res => {
                    this.allCategories = res.data;
                })
                .catch(err => {
                    throw err;
                });
            this.axios.get('/api/admin/cosplayers')
                .then(res => res.data)
                .then(res => {
                    this.allCosplayers = res.data;
                })
                .catch(err => {
                    throw err;
                });
        }

        sendingEvent(file: File, xhr: XMLHttpRequest, formData: FormData): void {
            if (!this.album.slug) {
                throw new DOMException('album slug is null');
            }
            formData.append('album_slug', (<string>this.album.slug));
        }

        updateAlbum(): void {
            this.axios.patch(`/api/admin/albums/${this.$route.params.slug}`, this.album)
                .then(res => res.data)
                .then(res => {
                    this.$toast.open({
                        message: `Album updated`,
                        type: 'is-success',
                        duration: 5000,
                    });
                    // this.$router.push({name: 'admin.albums.index'});
                })
                .catch(err => {
                    this.$toast.open({
                        message: `Unable to update the album <br><small>${err.response.data.message}</small>`,
                        type: 'is-danger',
                        duration: 5000,
                    });
                    this.errors = err.response.data.errors;
                });
        }

        getFilteredCategories(text: string): void {
            this.filteredCategories = this.allCategories.filter((option) => {
                return option.name
                    .toString()
                    .toLowerCase()
                    .indexOf(text.toLowerCase()) >= 0
            });
        }

        getFilteredCosplayers(text: string): void {
            this.filteredCosplayers = this.allCosplayers.filter((option) => {
                return option.name
                    .toString()
                    .toLowerCase()
                    .indexOf(text.toLowerCase()) >= 0
            });
        }

        refreshMedias(): void {
            this.axios.get(`/api/admin/albums/${this.$route.params.slug}`)
                .then(res => res.data)
                .then(res => {
                    this.album.medias = res.data.medias;
                })
                .catch(err => {
                    this.$toast.open({
                        message: `Unable to refresh the album <br><small>${err.response.data.message}</small>`,
                        type: 'is-danger',
                        duration: 5000,
                    });
                    throw err;
                })
        }

        deleteAlbumPicture(albumSlug: string, mediaId: number): void {
            this.axios.delete(`/api/admin/album-pictures/${albumSlug}`, {
                data: {
                    media_id: mediaId,
                },
            })
                .then(res => {
                    this.refreshMedias();
                    this.$toast.open({
                        message: 'Picture successfully deleted!',
                        type: 'is-success'
                    });
                })
                .catch(err => {
                    this.$toast.open({
                        message: `Unable to delete the picture`,
                        type: 'is-danger',
                        duration: 5000,
                    });
                    throw err;
                });
        }
    }
</script>
