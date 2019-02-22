<template>
    <div>
        <h1>Update album</h1>
        <form @submit.prevent="addAlbum">

            <label>Title:</label>
            <input type="text" class="input form-control" v-model="album.title">

            <quill-editor v-model="album.body"
                          ref="myQuillEditor"
                          :options="editorOption">
            </quill-editor>


            <div class="control">
                Private
                <label class="radio">
                    <input type="radio" v-model="album.private" v-bind:value="1">
                    Yes
                </label>
                <label class="radio">
                    <input type="radio" v-model="album.private" v-bind:value="0">
                    No
                </label>
            </div>

            <div class="control">
                Published
                <label class="radio">
                    <input type="radio" v-model="album.published_at" v-bind:value="album.published_at">
                    Yes
                </label>
                <label class="radio">
                    <input type="radio" v-model="album.published_at" value="">
                    No
                </label>
            </div>

            <b-field label="Enter some categories">
                <b-taginput
                        v-model="album.categories"
                        :data="filteredCategories"
                        autocomplete
                        allow-new
                        field="name"
                        icon="label"
                        placeholder="Add a category"
                        icon-pack="fas"
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
                        icon-pack="fas"
                        @typing="getFilteredCosplayers">
                </b-taginput>
            </b-field>

            <div class="form-group">
                <button class="btn btn-primary">Create</button>
            </div>
        </form>
    </div>
</template>

<script>
    import 'quill/dist/quill.core.css'
    import 'quill/dist/quill.snow.css'
    import 'quill/dist/quill.bubble.css'

    import {quillEditor} from 'vue-quill-editor'


    export default {
        components: {
            quillEditor,
        },
        data() {
            return {
                album: {},
                filteredCategories: [],
                filteredCosplayers: [],
                tags: [],
                isSelectOnly: false,
                allowNew: true,
                editorOption: {
                    placeholder: 'Enter your description...',
                    theme: 'snow',
                    // scrollingContainer: 'overflow-y: auto'
                },
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
            updateAlbum() {
                this.axios.patch(`/api/admin/albums/${this.$route.params.slug}`, this.album)
                    .then(res => res.data)
                    .then(res => {
                        console.log(res);
                        this.$router.push({name: 'albums.index'});
                    })
                    .catch(res => {
                        this.$toast.open({
                            message: `Unable to update the album <br><small>${res.message}</small>`,
                            type: 'is-danger',
                            duration: 5000,
                        });
                        console.log(res.response.data.errors);
                    });
            },
            getFilteredCategories(text) {
                this.axios.get('/api/admin/categories')
                    .then(res => res.data)
                    .then(res => {
                        this.filteredCategories = res.filter((option) => {
                            return option.name
                                .toString()
                                .toLowerCase()
                                .indexOf(text.toLowerCase()) >= 0
                        })
                    });
            },
            getFilteredCosplayers(text) {
                this.axios.get('/api/admin/cosplayers')
                    .then(res => res.data)
                    .then(res => {
                        this.filteredCosplayers = res.filter((option) => {
                            return option.name
                                .toString()
                                .toLowerCase()
                                .indexOf(text.toLowerCase()) >= 0
                        })
                    });
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
            },
            addAlbum() {
                this.axios.post('/api/admin/albums', this.album)
                    .then(res => res.data)
                    .then(res => {
                        console.log(res);
                        this.$router.push({name: 'albums.edit', params: { slug: res.data.slug }});
                    })
                    .catch(res => {
                        console.log(res);
                    });
            }
        }
    }
</script>
