<template>
    <div>
        <h1>Create A Post</h1>
        <form @submit.prevent="addAlbum">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Title:</label>
                        <input type="text" class="form-control" v-model="album.title">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Body:</label>
                        <textarea class="form-control" v-model="album.body" rows="5"></textarea>
                    </div>
                </div>
            </div>
            <p>
                Private
                <label>
                    Yes
                    <input type="radio" v-model="album.private" v-bind:value="1">
                </label>
                <label>
                    No
                    <input type="radio" v-model="album.private" v-bind:value="0">
                </label>
            </p>
            <p>
                Published
                <label>
                    Public
                    <input type="radio" v-model="album.published_at" v-bind:value="album.published_at">
                </label>
                <label>
                    Draft
                    <input type="radio" v-model="album.published_at" value="">
                </label>
            </p>
            <b-field label="Enter some categories">
                <b-taginput
                        v-model="album.categories"
                        :data="filteredCategories"
                        autocomplete
                        allow-new
                        field="name"
                        icon="label"
                        placeholder="Add a category"
                        @typing="getFilteredCategories">
                </b-taginput>
            </b-field>

            <b-field label="Enter some cosplayers">
                <b-taginput
                        v-model="album.cosplayers"
                        :data="filteredCosplayers"
                        autocomplete
                        allow-new
                        field="name"
                        icon="label"
                        placeholder="Add a cosplayer"
                        @typing="getFilteredCosplayers">
                </b-taginput>
            </b-field>
            <div v-for="media in album.medias">
                <img v-bind:src="media.thumb" v-bind:alt="media.name">
                <a class="button has-text-danger" v-on:click="deleteAlbumPicture(album.slug, media.id)">
                    Delete
                </a>
            </div>

            <div class="form-group">
                <button class="btn btn-primary">Create</button>
            </div>
        </form>

        <vue-dropzone ref="myVueDropzone" v-on:vdropzone-sending="sendingEvent" v-on:vdropzone-complete="refreshMedias"
                      :options="dropzoneOptions"></vue-dropzone>
    </div>
</template>

<script>
    import vue2Dropzone from 'vue2-dropzone';
    import 'vue2-dropzone/dist/vue2Dropzone.min.css'


    export default {
        components: {
            vueDropzone: vue2Dropzone
        },
        data() {
            return {
                album: {},
                filteredCategories: [],
                filteredCosplayers: [],
                tags: [],
                isSelectOnly: false,
                allowNew: true,
                dropzoneOptions: {
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
                    headers: {
                        'X-CSRF-Token': document.head.querySelector('meta[name="csrf-token"]').content,
                    },
                }
            }
        },
        created() {
            this.axios.get(`/api/admin/albums/${this.$route.params.slug}`)
                .then(res => res.data)
                .then(res => {
                    this.album = res.data;
                })
                .catch(err => {
                    throw err;
                })
        },
        methods: {
            sendingEvent(file, xhr, formData) {
                formData.append('album_slug', this.album.slug);
            },
            addAlbum() {
                console.log(this.album);
                let uri = '/api/admin/albums';
                this.axios.post(uri, this.album)
                    .then(res => res.data)
                    .then(res => {
                        console.log(res);
                        this.$router.push({name: 'albums.index'});
                    })
                    .catch(res => {
                        console.log(res);
                    });
            },
            getFilteredCategories(text) {
                this.filteredCategories = this.album.categories.filter((option) => {
                    return option.name
                        .toString()
                        .toLowerCase()
                        .indexOf(text.toLowerCase()) >= 0
                })
            },
            getFilteredCosplayers(text) {
                this.filteredCategories = this.album.cosplayers.filter((option) => {
                    return option.name
                        .toString()
                        .toLowerCase()
                        .indexOf(text.toLowerCase()) >= 0
                })
            },
            refreshMedias() {
                this.axios.get(`/api/admin/albums/${this.$route.params.slug}`)
                    .then(res => res.data)
                    .then(res => {
                        this.album.medias = res.data.medias;
                    })
                    .catch(err => {
                        throw err;
                    })
            },
            deleteAlbumPicture(albumSlug, mediaId) {
                return this.axios.delete(`/api/admin/album-pictures/${albumSlug}`, {
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
                        alert('Something went wrong.');
                        throw err;
                    });
            }
        }
    }
</script>
