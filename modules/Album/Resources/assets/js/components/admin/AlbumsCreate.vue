<template>
    <div>
        <h1 class="title">Create album</h1>

        <form @submit.prevent="createAlbum">
            <b-field label="Title"
                     :type="errors.title ? 'is-danger' : ''"
                     :message="errors.title ? errors.title[0] : null">
                <b-input type="text" v-model="album.title" maxlength="30"></b-input>
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
                    <b-switch v-model="album.private"
                              :false-value=1
                              :true-value=0>
                        {{ album.private ? 'No' : 'Yes' }}
                    </b-switch>
                </div>
            </b-field>

            <button class="button is-primary">Create</button>
        </form>
    </div>
</template>

<script lang="ts">
    import Component from 'vue-class-component';
    import AlbumDesc from './AlbumDesc';

    @Component({
        name: "AlbumsCreate",
        extends: AlbumDesc,
        components: {
            'album-desc': AlbumDesc,
        },
    })
    export default class AlbumsCreate extends AlbumDesc {

        created() {
            this.fetchData();
        }

        fetchData() {
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

        createAlbum() {
            this.axios.post(`/api/admin/albums/`, this.album)
                .then(res => res.data)
                .then(res => {
                    this.$toast.open({
                        message: `Album successfully created`,
                        type: 'is-success',
                    });
                    this.$router.push({name: 'admin.albums.edit', params: {slug: res.data.slug}});
                })
                .catch(err => {
                    this.$toast.open({
                        message: `Unable to create the album <br><small>${err.response.data.message}</small>`,
                        type: 'is-danger',
                        duration: 5000,
                    });
                    this.errors = err.response.data.errors;
                });
        }
    }
</script>
